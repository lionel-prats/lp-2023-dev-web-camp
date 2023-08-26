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
        
        const categoria = document.querySelector("[name='categoria_id']") 
        
        // selecciono los elementos HTML con name="dia" (los <inputs type="radio">) del <form> de registro de eventos
        const dias = document.querySelectorAll("[name='dia']") 

        const inputHiddenDia = document.querySelector("[name='dia_id']") 
        const inputHiddenHora = document.querySelector("[name='hora_id']") 

        categoria.addEventListener("change", terminoBusqueda)
        dias.forEach( dia => dia.addEventListener("change", terminoBusqueda))

        // función para cargar el objeto global busqueda con lo que el admin carga tanto en el <select> de categoria como en los inputs de selección del día del evento (función reutilizable)
        function terminoBusqueda(e) {
            busqueda[e.target.name] = e.target.value
            // si el objeto global busqueda está inclompleto salgo de la función
            if(Object.values(busqueda).includes("")){
                return
            } 
            buscarEventos();
        }

        async function buscarEventos() { // Petición a nuestra API
            const { dia, categoria_id } = busqueda
            // try {
                const url = `http://localhost:3000/api/eventos-horario?dia_id=${dia}&categoria_id=${categoria_id}`
                const resultado = await fetch(url)
                const eventos = await resultado.json()
                obtenerHorasDisponibles();
            // } catch (error) {
            //     console.log(error);
            // }
        }

        function obtenerHorasDisponibles(){

            // selecciono todos los <li> hijos del <element id="horas"> (es decir, todos los <li> hijos de <div id="horas">)
            const horasDisponibles = document.querySelectorAll("#horas li")
            horasDisponibles.forEach( hora => hora.addEventListener("click", seleccionarHora))
        }

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