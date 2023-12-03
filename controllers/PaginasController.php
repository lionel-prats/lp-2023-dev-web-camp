<?php

namespace Controllers;
use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Ponente;
use Model\Categoria;

class PaginasController {
    public static function index(Router $router) {
        $router->render("paginas/index", [
            "titulo" => "Inicio"
        ]);
    }
    public static function evento(Router $router) {
        $router->render("paginas/devwebcamp", [
            "titulo" => "Sobre DevWebCamp"
        ]);
    }
    public static function paquetes(Router $router) {
        $router->render("paginas/paquetes", [
            "titulo" => "Sobre DevWebCamp"
        ]);
    }
    public static function conferencias(Router $router) {

        // SELECT * from eventos order by hora_id ASC 
        $eventos = Evento::ordenar("hora_id", "ASC");

        /*  
        $eventos_formateados = [
            "conferencias_v" => [
                (), ()
            ],
            "conferencias_s" => [
                (), ()
            ],
            "workshops_v" => [
                (), ()
            ],
            "workshops_s" => [
                (), ()
            ]
        */
        $eventos_formateados = [];

        foreach($eventos as $evento) {

            # a cada opbjeto evento iterado, le agrego un objeto con cada una de las FK
            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);
            
            if ($evento->dia_id === "1" && $evento->categoria_id === "1"){
                # conferencias del viernes 
                $eventos_formateados["conferencias_v"][] = $evento;
            } 
            if ($evento->dia_id === "2" && $evento->categoria_id === "1"){
                # conferencias del sabado 
                $eventos_formateados["conferencias_s"][] = $evento;
            } 
            if ($evento->dia_id === "1" && $evento->categoria_id === "2"){
                # workshops del viernes 
                $eventos_formateados["workshops_v"][] = $evento;
            } 
            if ($evento->dia_id === "2" && $evento->categoria_id === "2"){
                # workshops del sabado 
                $eventos_formateados["workshops_s"][] = $evento;
            } 
        }

        // debuguear($eventos_formateados);

        $router->render("paginas/conferencias", [
            "titulo" => "Conferencias & Workshops",
            "eventos" => $eventos_formateados
        ]);
    }
}