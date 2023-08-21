<?php

namespace Classes;

class Paginacion {
    public $pagina_actual;
    public $registros_por_pagina;
    public $total_registros;

    public function __construct($pagina_actual = 1, $registros_por_pagina = 10, $total_registros = 0) {

        // castear un valor es que si llega como un string pero se puede modificar como entero se va a hacer, es decir, modificar el tipo de dato del valor recibido (VIDEO 720)

        $this->pagina_actual = (int) $pagina_actual; // casteo el valor recibido como int (VIDEO 720)
        $this->registros_por_pagina = (int) $registros_por_pagina; // casteo el valor recibido como int (VIDEO 720)
        $this->total_registros = (int) $total_registros; // casteo el valor recibido como int (VIDEO 720)
    }


}