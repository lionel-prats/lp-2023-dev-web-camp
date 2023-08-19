// IIFE -> Inmediately Invoke Function Expression vvv
(function() {

    const tagsInputs = document.querySelector("#tags_input");

    // valido si existe el elemento capturado para que javaScript no rompa en vistas donde no exsita, ya que todo el codigo JS se va a compilar en /public/build/js/bundle.min.js (VIDEO 704)
    if(tagsInputs) {

        let tags = []

        // Escuchar los cambios en el input "areas de experiencia - separadas por coma"
        tagsInputs.addEventListener("keypress", guardarTag)
        
        function guardarTag( e ) {
            
            if(e.keyCode === 44) {

                if(e.target.value.trim() === "" || e.target.value.length < 1) {
                    return
                }

                e.preventDefault(); // cuando el usuario tipee una "," prevengo la accion por default (que es imprimir la "," en el input) para que esta no se imprima y la funcionalidad que buscamos sea exitosa

                tags = [...tags, e.target.value.trim()]
                
                tagsInputs.value = ""

                console.log(tags);
            }
        }

    }

})()


