<?php

namespace Controllers;

use Model\Ponente;

class APIPonentes {
    public static function index() {
        $ponentes = Ponente::all("ASC");
        echo json_encode($ponentes);
    }
}