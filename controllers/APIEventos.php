<?php

namespace Controllers;

use Model\EventoHorario;

class APIEventos {
    // retorna los eventos de la tabla eventos de una determinada categoría en un determinado día (recibe los parámetros para la búsqueda en la DB vía GET)
    public static function index() {

        $dia_id = $_GET["dia_id"] ?? "";
        $categoria_id = $_GET["categoria_id"] ?? "";
        $dia_id = filter_var($dia_id, FILTER_VALIDATE_INT); 
        $categoria_id = filter_var($categoria_id, FILTER_VALIDATE_INT); 

        if(!$dia_id || !$categoria_id) {
            // echo "[]";
            echo json_encode([]);
            return;
        }

        $eventos = EventoHorario::whereArray([
            "dia_id" => $dia_id,
            "categoria_id" => $categoria_id
        ]) ?? [];
        echo json_encode($eventos);

    }
}