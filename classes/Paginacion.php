<?php

namespace Classes;

class Paginacion {

    public $pagina_actual; // nro. de página del paginado de la vista (por default = 1)
    public $registros_por_pagina; // cantidad de registros por página del paginado
    public $total_registros; // total de registros de una tabla

    public function __construct($pagina_actual = 1, $registros_por_pagina = 10, $total_registros = 0) {

        // castear un valor es que si llega como un string pero se puede modificar como entero se va a hacer, es decir, modificar el tipo de dato del valor recibido (VIDEO 720)

        $this->pagina_actual = (int) $pagina_actual; // casteo el valor recibido como int (VIDEO 720)
        $this->registros_por_pagina = (int) $registros_por_pagina; // casteo el valor recibido como int (VIDEO 720)
        $this->total_registros = (int) $total_registros; // casteo el valor recibido como int (VIDEO 720)
    }

    // Retorna un int que representa un "salto" de registros de lo que se quiera paginar, para armar el paginado (VIDEO 723)
    public function offset() {
        return $this->registros_por_pagina * ($this->pagina_actual - 1);
    }

    // Retorna la cantidad de paginas que tendrá el páginado usando ceil(), que retorna un float redondeado hacia arriba a partir del parámetro que recibe
    public function total_paginas() {
        return ceil($this->total_registros / $this->registros_por_pagina);
    }
    public function pagina_anterior() {
        $anterior = $this->pagina_actual - 1;
        return ($anterior > 0) ? $anterior : false;
    }
    public function pagina_siguiente() {
        $siguiente = $this->pagina_actual + 1;
        $total_paginas = $this->total_paginas();
        return ($siguiente <= $total_paginas) ?  $siguiente : false;
    }
}