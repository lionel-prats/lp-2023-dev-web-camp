<?php

function debuguear($variable) : string {
    echo "<pre>";
    print_r($variable);
    //var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function pagina_actual($path) : bool {
    // $_SERVER["PATH_INFO"] no existe cuando estoy en el home, entonces uso un ternario para que no rompa el home al invocarse a la funcion pagina_actual en header.php (VIDEO 766)
    return str_contains($_SERVER["PATH_INFO"] ?? "/", $path); 
}

// Verifica si el usuario está autenticado (logueado)
function is_auth() : bool {
    if(!isset($_SESSION)){
        session_start();
    }
    // debuguear($_SESSION);
    return /* isset($_SESSION["nombre"]) &&  */!empty($_SESSION);
}

// Verifica si el usuario logueado es administrador
function is_admin() : bool {
    if(!isset($_SESSION)){
        session_start();
    }
    // session_start();
    // debuguear($_SESSION);
    return isset($_SESSION["admin"]) && $_SESSION["admin"];
}