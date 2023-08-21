<?php

namespace Controllers;

use MVC\Router;
use Model\Ponente;
use Intervention\Image\ImageManagerStatic as Image;

class PonentesController {
    // index de ponentes
    public static function index(Router $router) {

        $ponentes = Ponente::all();
        //debuguear($ponentes);

        $router->render("admin/ponentes/index", [
            "titulo" => "Ponentes / Conferencistas",
            "ponentes" => $ponentes
        ]);
    }
    // Crear ponente -> form y prosesamiento
    public static function crear(Router $router) {

        $alertas = [];
        $ponente = new Ponente;

        if($_SERVER["REQUEST_METHOD"] === "POST") {

            // compruebo si se cargó una imagen en el <input name="imagen"> del form de creación de un ponente, y si sí, toda la lógica para generar versiones .png y .webp y guardar dicha imagen fisicamente en el servidor (VIDEO 708)
            if(!empty($_FILES["imagen"]["tmp_name"])) {
                
                $carpeta_imagenes = "../public/img/speakers";
                
                // Si no existe, creo la carpeta que contendrá las imagenes de los ponentes
                if(!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true); // (VIDEO 708)
                } 

                // generamos una imagen.png de 800x800 y un 80% de calidad que se mantienen en memoria (debemos guardarlas fisicamente en el servidor) (VIDEO 708) 
                $imagen_png = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 800)->encode("png", 80);
                $imagen_webp = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 800)->encode("webp", 80);

                // nombre random para la imagen -> f63f4c13c10940b6b0f91e0174e0f0d0
                $nombre_imagen = md5( uniqid( rand(), true ) );
                
                // generamos la clave "imagen" en el $_POST para agregar el nombre generado para la imagen que se va a subir al servidor, asociada al ponente que vamos a crear en la DB, ya que $_POST se va a sincronizar con la instancia de Ponente para hacer el INSERT
                $_POST["imagen"] = $nombre_imagen;
            } 

            // REDES DE EJEMPLO -> https://github.com/lio85
            // REDES DE EJEMPLO -> https://twitter.com/codigoconjuan

            // convierto el array con las redes de un ponente en un string (VIDEO 709)
            // la constante JSON_UNESCAPED_SLASHES como 2do. parametro del json_encode es para eliminar las barras invertidas (VIDEO 709)
            // hay que hacer esto para poder guardar el string de redes de un ponente en el campo "redes" de devwebcamp.ponentes
            $_POST["redes"] = json_encode($_POST["redes"], JSON_UNESCAPED_SLASHES);

            $ponente->sincronizar($_POST);
            $alertas = $ponente->validar();

            // si $alertas está vacío significa que el formulario de creación de un ponente se completó correctamente (incluída la carga de la imagen), por lo que avanzamos a guardar la imagen en el servidor y a crear el registro en la DB (VIDEO 709)
            if(empty($alertas)) {
                
                // Guardar la imagen en el servidor 
                //->save(../public/img/speakers/f63f4c13c10940b6b0f91e0174e0f0d0.png)
                //->save(../public/img/speakers/f63f4c13c10940b6b0f91e0174e0f0d0.webp)
                $imagen_png->save($carpeta_imagenes . "/" . $nombre_imagen . ".png");
                $imagen_webp->save($carpeta_imagenes . "/" . $nombre_imagen . ".webp");

                // Guardar el registro del ponente en la BD
                $resultado = $ponente->guardar();
                
                if($resultado) {
                    header("Location: /admin/ponentes");
                }
            }
            
        }

        $router->render("admin/ponentes/crear", [
            "titulo" => "Registrar Ponente",
            "alertas" => $alertas,
            "ponente" => $ponente
        ]);
    }
    // Editar ponente -> form y prosesamiento
    public static function editar(Router $router) {

        $alertas = [];

        $id = $_GET["id"];

        // capturo el id de ponente que viene en la URL validando que sea un id (VIDEO 712)
        // si el valor a revisar es int, retorna ese int, caso contrario retorna FALSE
        $id = filter_var($_GET["id"], FILTER_VALIDATE_INT) ;

        if(!$id) {
            header("Location: /admin/ponentes");
        }

        $ponente = Ponente::find($id);

        if(!$ponente) {
            header("Location: /admin/ponentes");
        }


        $router->render("admin/ponentes/editar", [
            "titulo" => "Editar Ponente",
            "alertas" => $alertas,
            "ponente" => $ponente
        ]);
    }
}