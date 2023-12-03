<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    public function comprobarRutas()
    {
        $url_actual = $_SERVER['PATH_INFO'] ?? '/'; // "/login", "/registro", "/admin/dashboard", etc
        $method = $_SERVER['REQUEST_METHOD'];  // "GET" o "POST"

        if ($method === 'GET') {
            $fn = $this->getRoutes[$url_actual] ?? null;
        } else {
            $fn = $this->postRoutes[$url_actual] ?? null;
        }

        if ( $fn ) {
            call_user_func($fn, $this); // mando a ejecutar el controlador y el metodo asociado a la vista que se esta peticionando desde la URL del navegador (esta asociacion la hacemos en index.php)
        } else {
            echo "Página No Encontrada o Ruta no válida";
        }
    }

    // este metodo siempre lo mandan a ejecutar los controladores cuando renderizan una vista
    public function render($view, $datos = [])
    {
        // cuando se renderiza una vista "pasa" adentro de esta funcion (VIDEO 693)
        // demostracion (descomentar este echo y ver lo que pasa...) vvv
        // echo "<h1 style='color: red;'>Pitrola</h1>";
        
        // convertimos las propiedades del array asociativo con info que mandan los controladores a las vistas en variables individuales (destructuramos el array)
        foreach ($datos as $key => $value) {
            // de esta forma voy generando variables con los nombres de los atributos que llegan en $datos (que es el array asociativo con data que mandamos desde los metodos que se ejecutan desde los distintos controladores segun la vista que se este peticionando desde la URL del navegador)
            // entonces, estas variables tienen el mismo contenido que que el valor asociado a la propiedad en $datos y quedan disponibles al igual que $contenido para poder hacer uso en la vista (repaso VIDEO 402)
            // es decir, es como una especie de destructuring de javaScript (repaso VIDEO 402)
            $$key = $value; 
        }

        ob_start(); // inicia un almacenamiento en memoria del contenido del include_once de $view de la linea de abajo (repaso del VIDEO 401)
        
        include_once __DIR__ . "/views/$view.php";

        // en $contenindo almacenamos la "ejecucion" de ob_start(), o sea, el include_once de $view en la linea de arriba (repaso del VIDEO 401)
        // con $contenido = ob_get_clean(), por un lado almacenamos en $contenido la data de $view que almacenamos en memoria con ob_start(), y a su vez, como ya salvaguardamos la data de $view, limpiamos de la memoria el contenido del include_once de $view en la linea de arriba con ob_get_clean(), luego de almacenarlo en la variable $contenido (repaso del VIDEO 401)
        // limpiamos la memoria para que el servidor no colapse (repaso del VIDEO 401)
        $contenido = ob_get_clean(); // Limpia el Buffer

        // Utilizar el Layout de acuerdo a la URL (VIDEO 693)

        // capturamos lo que venga por URL (despues de localhost:3000/)
        $url_actual = $_SERVER['PATH_INFO'] ?? '/';
        
        if(str_contains($url_actual, "/admin")) {
            include_once __DIR__ . '/views/admin-layout.php';
        } else {
            // al incluir layout.php (nuestra master page) es como que queda accesible la variable $contenido (que tiene el "HTML" de $view), y como layout tiene un echo $contenido, se imprime el "HTML" de del archivo renderizado en cada metodo de controlador, o sea, el archivo asociado a una vista en particular (repaso VIDEO 401)
            include_once __DIR__ . '/views/layout.php';
        }

        
    }
}
