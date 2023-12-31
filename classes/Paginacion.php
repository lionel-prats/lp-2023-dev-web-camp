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
    // Si queremos 6 registros por página y estamos en la URL con /ponentes?page=1, retorna 0, que será el OFFSET en la consulta SQL a la tabla ponentes (o sea, "saltarse" 0 registros)
    // Si queremos 6 registros por página y estamos estamos en la URL con /ponentes?page=7, retorna 42, que será el OFFSET en la consulta SQL a la tabla ponentes (o sea, "saltarse" 42 registros)
    public function offset() {
        return $this->registros_por_pagina * ($this->pagina_actual - 1);
    }

    // Retorna la cantidad de paginas que tendrá el páginado usando ceil(), que retorna un float redondeado hacia arriba a partir del parámetro que recibe
    public function total_paginas() {
        return ceil($this->total_registros / $this->registros_por_pagina);
    }

    // retorna el nro. de pagina anterior respecto al queryString de la URL (si es 15 retorna 14, si es 23 retorna 22)
    // si se está en la página 1 y se ejecuta este método, retornará false
    public function pagina_anterior() {
        $anterior = $this->pagina_actual - 1;
        return ($anterior > 0) ? $anterior : false;
    }

    // retorna el nro. de pagina siguiente respecto al queryString de la URL (si es 1 retorna 2, si es 74 retorna 75)
    // si se está en el máximo nro. de página y se ejecuta este método retornará, false
    public function pagina_siguiente() {
        $siguiente = $this->pagina_actual + 1;
        $total_paginas = $this->total_paginas();
        return ($siguiente <= $total_paginas) ?  $siguiente : false;
    }

    public function enlace_anterior() {
        $html = "<a href=\"?page={$this->pagina_anterior()}\" class=\"paginacion__enlace paginacion__enlace--texto"; 
        if(!$this->pagina_anterior()) {
            $html .= " paginacion__enlace--invisible";
        }
        $html .= "\">&laquo Anterior</a>";
        return $html;
    }

    public function enlace_siguiente() {
        $html = "<a href=\"?page={$this->pagina_siguiente()}\" class=\"paginacion__enlace paginacion__enlace--texto"; 
        if(!$this->pagina_siguiente()) {
            $html .= " paginacion__enlace--invisible";
        }
        $html .= "\">Siguiente &raquo</a>";
        return $html;
    }

    public function numeros_paginas(){
        $html = "<div class=\"paginacion__contenedor-numeros\">";
        for($i = 1; $i <= $this->total_paginas(); $i++) {
            if($i === $this->pagina_actual){
                $html .= "
                    <span 
                        class=\"paginacion__enlace paginacion__enlace--actual\" 
                        href=\"?page={$i}\"
                    >
                        {$i}
                    </span>
                ";
            } else {
                $html .= "
                    <a 
                        class=\"paginacion__enlace paginacion__enlace--numero\" 
                        href=\"?page={$i}\"
                    >
                        {$i}
                    </a>
                ";

            }
        }
        $html .= "</div>";
        return $html;
    }

    public function paginacion() {
        $html = "";
        if($this->total_registros > 1) {
            $html .= "<div class=\"paginacion\">";
            $html .= $this->enlace_anterior();
            $html .= $this->numeros_paginas();
            $html .= $this->enlace_siguiente();
            $html .= "</div>";
        }
        return $html;
    }
}