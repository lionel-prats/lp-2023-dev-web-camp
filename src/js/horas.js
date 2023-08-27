// IIFE -> Inmediately Invoke Function Expression vvv
(function() {

    const horas = document.querySelector("#horas")
    
    if(horas) {

        // objeto en memoria -> va a contener el tipo de evento seleccionado (<select name="categoria_id">) y el día seleccionado (<input name="dia"> x 2) en el <form> de registro de eventos 
        // Para esto, las claves del objeto coinciden con los atributos name de los elementos HTML
        let busqueda = { 
            categoria_id: "",
            dia: ""
        }
        
        // <select name="categoria_id"> -> select de categorías para la creación de un evento
        const categoria = document.querySelector("[name='categoria_id']") 
        
        // selecciono los elementos HTML con name="dia" (los <inputs type="radio">) del <form> de registro de eventos
        const dias = document.querySelectorAll("[name='dia']") 

        const inputHiddenDia = document.querySelector("[name='dia_id']") 
        const inputHiddenHora = document.querySelector("[name='hora_id']") 

        categoria.addEventListener("change", terminoBusqueda) // escucho por cambios en el select de categorias
        dias.forEach( dia => dia.addEventListener("change", terminoBusqueda)) // escucho por cambios en los inputs de día del evento

        // función para cargar el objeto global busqueda con lo que el admin carga tanto en el <select> de categoria como en los inputs de selección del día del evento (función reutilizable)
        function terminoBusqueda(e) {
            busqueda[e.target.name] = e.target.value
            // si el objeto global busqueda está inclompleto salgo de la función
            // Genero un array con los valores del objeto y verifico si alguno es ""
            if(Object.values(busqueda).includes("")){
                return
            } 
            buscarEventos(); // se va a ejecutar solo si el objeto global busqueda está completo
        }

        async function buscarEventos() { // Petición a nuestra API
            const { dia, categoria_id } = busqueda
            // try {
                const url = `http://localhost:3000/api/eventos-horario?dia_id=${dia}&categoria_id=${categoria_id}`
                const resultado = await fetch(url)
                const eventos = await resultado.json()
                obtenerHorasDisponibles(eventos); 

            // } catch (error) {
            //     console.log(error);
            // }
        }

        // para obtener del DOM los <li> con las horas para seleccionar y quedar escuchando por un click
        function obtenerHorasDisponibles(eventos){

            // con un .map armo un array con los valores hora_id (referencia a la tabla horas) de cada evento iterado (eventos es una consulta via fetch a nuestra API, a la tabla eventos)
            // será un array de tipo ["2", 4, "1"] donde cada elemento es el id de algún registro de la tabla horas 
            const horasTomadas = eventos.map( evento => evento.hora_id)
            
            // NodeList con todos los <li> hijos del <element id="horas"> (es decir, todos los <li> hijos de <div id="horas">)
            const listadoHoras = document.querySelectorAll("#horas li")

            // convierto el NodeList anterior en array de nodos para poder operarlo como array y usar los métodos nativos de JS (VIDEO 742)
            const listadoHorasArray = Array.from(listadoHoras);
            
            // genero un array de nodos compuesto por los <li> de horas para seleccionar en el form de creacion de eventos, cuyos data-hora-id (que son los id correspondientes en la tabla horas) no estén incluidos en el array horasTomadas (que es el array de horas ya asignadas a eventos en la categoría y día seleccionados previamente por el admin para este nuevo evento)
            const resultado = listadoHorasArray.filter( li => !horasTomadas.includes(li.dataset.horaId) )
            
            // reflección: resultado es una variable distinta a la que originalmente capturó los <li> del DOM (listadoHoras). Sin embargo sirve para manipular los elementos capturados en el DOM (en este caso quitarles una clase)
            resultado.forEach( li => li.classList.remove("horas__hora--deshabilitada") )

            // selecciono todos los <li> hijos del <element id="horas"> (es decir, todos los <li> hijos de <div id="horas">) excepto los que tengan la clase "horas__hora--deshabilitada" para ejecutar un listener en ellos (VIDEO 742)
            const horasDisponibles = document.querySelectorAll("#horas li:not(.horas__hora--deshabilitada)")
            horasDisponibles.forEach( hora => hora.addEventListener("click", seleccionarHora))
        }

        // funcion para marcar en el DOM la hora seleccionada y desmarcar la que pudiera haber previamente, y cargar el input:hidden con el id de la hora seleccionada
        function seleccionarHora(e){

            // bloque para desmarcar la hora previamente seleccionada (si la hay), ante un nuevo click
            const horaPrevia = document.querySelector(".horas__hora--seleccionada")
            if(horaPrevia) {
                horaPrevia.classList.remove("horas__hora--seleccionada")
            }

            // agrego la clase CSS para resaltar la hora seleccionada en el form
            e.target.classList.add("horas__hora--seleccionada");
            
            // cargo en el input:hidden el id de la hora seleccionada
            inputHiddenHora.value = e.target.dataset.horaId
        }
    
    }

})(); // En JavaScript, es importante finalizar el bloque de un IIFE con ";" para que no rompa la compilación en un archivo común (/public/build/js/bundle.min.js) (VIDEO 735)