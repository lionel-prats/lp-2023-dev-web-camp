<?php

namespace Controllers;

use MVC\Router;
use Model\Ponente;
use Classes\Paginacion;
use Intervention\Image\ImageManagerStatic as Image;

class PonentesController {
    // index de ponentes
    public static function index(Router $router) {

        // protejo la vista (cualquier usuario que quiera ingresar a esta vista y no sea admin, será redirigido)
        if(!is_auth()) { // Si el usuario no está logueado, lo redirijo al form de login
            header("Location: /login");
        } elseif(!is_admin()) { // Si el usuario está logueado pero no es admin, lo redirijo al "home de usuarios no admin"
            header("Location: /finalizar-registro");
        }
        // debuguear($_SESSION); --------------------------------------------------------------------------

        // nro. de página del paginado de la vista (por default = 1)
        $pagina_actual = $_GET["page"];

        // con filter_var() valido que el queryString date sea un (int) (VIDEO 721)
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT); 
        
        if(!$pagina_actual || $pagina_actual < 1) {
            header("Location: /admin/ponentes?page=1");
        }
        
        // cantidad de registros por página del paginado
        $registros_por_pagina = "3";

        // (int) total de registros de la tabla ponentes
        $total = Ponente::total();

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        // si se modifica el queryString "page" y el valor supera a la cantidad de páginas del paginado, se redirecciona a la page=1
        if($paginacion->total_paginas() < $pagina_actual) {
            header("Location: /admin/ponentes?page=1");
        }

        $ponentes = Ponente::paginar($registros_por_pagina, $paginacion->offset());
        // Si estamos en la ?page=1 -> Ponente::paginar(6, 0)
        // Si estamos en la ?page=4 -> Ponente::paginar(6, 18)

        $router->render("admin/ponentes/index", [
            "titulo" => "Ponentes / Conferencistas",
            "ponentes" => $ponentes,
            "paginacion" => $paginacion->paginacion()
        ]);
    }
    // Crear ponente -> form y prosesamiento
    public static function crear(Router $router) {

        // protejo la vista (cualquier usuario que quiera ingresar a esta vista y no sea admin, será redirigido)
        if(!is_auth()) { // Si el usuario no está logueado, lo redirijo al form de login
            header("Location: /login");
        } elseif(!is_admin()) { // Si el usuario está logueado pero no es admin, lo redirijo al "home de usuarios no admin"
            header("Location: /finalizar-registro");
        }

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
            // A su vez, si por ejemplo, el usuario que está creando
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
            "ponente" => $ponente,
            "redes" => json_decode($ponente->redes) // (VIDEO 715)
        ]);
    }
    // Editar ponente -> form y prosesamiento
    public static function editar(Router $router) {

        // protejo la vista (cualquier usuario que quiera ingresar a esta vista y no sea admin, será redirigido)
        if(!is_auth()) { // Si el usuario no está logueado, lo redirijo al form de login
            header("Location: /login");
        } elseif(!is_admin()) { // Si el usuario está logueado pero no es admin, lo redirijo al "home de usuarios no admin"
            header("Location: /finalizar-registro");
        }

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

        $ponente->imagen_actual = $ponente->imagen;

        if($_SERVER["REQUEST_METHOD"] === "POST") {

            if(!empty($_FILES["imagen"]["tmp_name"])) { // el admin cargó imagen nueva para el ponente
               
                $carpeta_imagenes = "../public/img/speakers";
                
                if(!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true); 
                } 

                $imagen_png = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 800)->encode("png", 80);
                $imagen_webp = Image::make($_FILES["imagen"]["tmp_name"])->fit(800, 800)->encode("webp", 80);

                // nombre random para la imagen por subir al server
                $nombre_imagen = md5( uniqid( rand(), true ) );
                
                $_POST["imagen"] = $nombre_imagen; // cargo al $_POST que voy a sincronizar con $ponente el nombre de la imagen nueva para el UPDATE en la DB (VIDEO 716)

            } else { // el admin NO cargó imagen nueva para el ponente

                $_POST["imagen"] = $ponente->imagen_actual; // cargo al $_POST que voy a sincronizar con $ponente el nombre de la imagen actual para el UPDATE en la DB (VIDEO 716)

            }
            
            $_POST["redes"] = json_encode($_POST["redes"], JSON_UNESCAPED_SLASHES);

            $ponente->sincronizar($_POST);
            
            $alertas = $ponente->validar();

            if(empty($alertas)) {

                if(isset($nombre_imagen)) { // El admin cargó imagen nueva para el ponente
                    
                    // borro las imagenes viejas del ponente ya que el admin las reemplazó
                    unlink($carpeta_imagenes . "/" . $ponente->imagen_actual . ".png"); 
                    unlink($carpeta_imagenes . "/" . $ponente->imagen_actual . ".webp"); 

                    $imagen_png->save($carpeta_imagenes . "/" . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . "/" . $nombre_imagen . ".webp");

                }

                $resultado = $ponente->guardar();
                
                if($resultado) {
                    header("Location: /admin/ponentes");
                }

            }

        }

        $router->render("admin/ponentes/editar", [
            "titulo" => "Editar Ponente",
            "alertas" => $alertas,
            "ponente" => $ponente,
            "redes" => json_decode($ponente->redes) // (VIDEO 715)
        ]);
    }
    // Eliminar un ponente de la BD (borrado físico)
    public static function eliminar() {

        // protejo la vista (cualquier usuario que quiera ingresar a esta vista y no sea admin, será redirigido)
        if(!is_auth()) { // Si el usuario no está logueado, lo redirijo al form de login
            header("Location: /login");
        } elseif(!is_admin()) { // Si el usuario está logueado pero no es admin, lo redirijo al "home de usuarios no admin"
            header("Location: /finalizar-registro");
        }
        
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
            $id = $_POST["id"];
            
            $ponente = Ponente::find($id);
            
            if(!$ponente) {
                header("Location: /admin/ponentes");
            }
            
            $resultado = $ponente->eliminar();

            
            if($resultado){
                
                $carpeta_imagenes = "../public/img/speakers";

                // borro las imagenes del ponente borrado
                unlink($carpeta_imagenes . "/" . $ponente->imagen . ".png"); 
                unlink($carpeta_imagenes . "/" . $ponente->imagen . ".webp"); 
                
                header("Location: /admin/ponentes");

            }

        }
    }
}