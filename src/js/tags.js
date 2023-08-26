// IIFE -> Inmediately Invoke Function Expression vvv
(function() {

    const tagsInputs = document.querySelector("#tags_input"); // <input> donde el administrador va cargando las experiencias de un ponente en el form de creación/edición

    // valido si existe el <input id="tags_input"> capturado para que javaScript no rompa en vistas donde no exista, ya que todo el codigo JS se va a compilar en /public/build/js/bundle.min.js (VIDEO 704)
    if(tagsInputs) { // toda esta lógica aplicará solo para los form de creación y edición de un ponente

        const tagsDiv = document.querySelector("#tags") // div que mostrará por pantalla en tiempo real los tags con experiencias que vaya ingresando el administrador en el <input id="tags-input">

        // input hidden con los tags de un ponente
        // en el form de crear ponente vamos cargando los tags acá para el submit
        // en el form de editar, cuando apenas se carga el form, este input ya aparece completo con los tags del ponente provenientes de la DB, ya que hemos completado automáticamente su value en el HTML (revisar /views/admin/ponentes/formulario.php) (VIDEO 714)
        const tagsInputHidden = document.querySelector('[name="tags"]') 

        let tags = []

        // Si pasamos esta validación significa que estamos en el formulario de edición de un ponente y o en el de creación si hubo $alertas pero se completó este <input type="hidden">, ya que en ambos casos, estamos enviando con datta $ponente->tags (VIDEO 714)
        if(tagsInputHidden.value !== "") {

            // cuando apenas se cargue el formulario de edicion de un ponente, se completará la variable global tags con el array de tags asociados al ponente, data que viene de la DB
            tags = tagsInputHidden.value.split(",")
            // tagsInputHidden.value.split(",")) -> convierto un string en array determinando el caracter separador
            mostrarTags()
        }

        // Escuchar los cambios en el input "areas de experiencia - separadas por coma"
        tagsInputs.addEventListener("keypress", guardarTag)
        
        // funcion para ir llenando el array global con las experiencias de un ponente que el administrador va vargando en el input del form de crear ponente
        function guardarTag( e ) {
            
            if(e.keyCode === 44) {

                if(e.target.value.trim() === "" || e.target.value.length < 1) {
                    return
                }

                e.preventDefault(); // cuando el administrador tipee una "," prevengo la accion por default (que es imprimir la "," en el input) para que esta no se imprima y la funcionalidad que buscamos sea exitosa

                tags = [...tags, e.target.value.trim()] // voy completando el array global (o una copia ? ) con las experiencias que va ingresando el administrador de un ponente (VIDEO 704)
                
                tagsInputs.value = ""

                mostrarTags()
            }
        }

        // funcion para ir imprimiendo por pantalla en tiempo real los <li> dentro del <div id="tags_input">, <li> con los nombres de las experiencias que el administrador va tipeando de un ponente
        function mostrarTags() {

            tagsDiv.textContent = ""

            tags.forEach( tag => {
                const etiqueta = document.createElement("LI")
                etiqueta.classList.add("formulario__add")
                etiqueta.textContent = tag
                etiqueta.ondblclick = eliminarTag // añado un listener por un doble click en cada experiencia que se va renderizando

                tagsDiv.appendChild(etiqueta) // voy llenando el <div> con los <li>
            })
            
            actualizarInputHidden()

        }

        function eliminarTag(e) {
            e.target.remove()
            tags = tags.filter( tag => tag !== e.target.textContent)
            
            actualizarInputHidden()
        }

        // actualiza el array de experiencias o tags que cargamos como string ("php,css,html") en el value del input hidden que submietearemos a la BD
        function actualizarInputHidden() {
            tagsInputHidden.value = tags.toString() // convierto el array de tags en un string (Ej, "php,laravel,css,Node") y cargo dicho string en el value del input hidden que vamos a enviar a la base de datos para el INSERT
        }
    }

})(); // En JavaScript, es importante finalizar el bloque de un IIFE con ";" para que no rompa la compilación en un archivo común (/public/build/js/bundle.min.js) (VIDEO 735)


