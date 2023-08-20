// IIFE -> Inmediately Invoke Function Expression vvv
(function() {

    const tagsInputs = document.querySelector("#tags_input"); // div donde se van a ir imprimiendo las experiencias de un ponente

    // valido si existe el elemento capturado para que javaScript no rompa en vistas donde no exsita, ya que todo el codigo JS se va a compilar en /public/build/js/bundle.min.js (VIDEO 704)
    if(tagsInputs) {

        const tagsDiv = document.querySelector("#tags") // div que mostrar por pantalla en tiempo real las experiencia que va a ir ingresando el administrador de un ponente en el input

        const tagsInputHidden = document.querySelector("[name='tags']")

        let tags = []

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

        // actualiza el array de experiencias que cargamos como string ("php,css,html") en el value del input hidden que submietearemos a la BD
        function actualizarInputHidden() {
            tagsInputHidden.value = tags.toString() // convierto el array de tags en un string (Ej, "php,laravel,css,Node") y cargo dicho string en el value del input hidden que vamos a enviar a la base de datos para el INSERT
        }
    }

})()


