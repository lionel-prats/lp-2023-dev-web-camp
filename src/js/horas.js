// IIFE -> Inmediately Invoke Function Expression vvv
(function() {

    const horas = document.querySelector("#horas")

    if(horas) {

        // objeto en memoria -> va a contener el tipo de evento seleccionado (<select name="categoria_id">) y el día seleccionado (<input name="dia"> x 2) en el <form> de registro de eventos 
        let busqueda = {
            categoria_id: "",
            dia: ""
        }
        
        const categoria = document.querySelector("[name='categoria_id']") 
        
        // selecciono los elementos con name="dia" (los <inputs type="radio">) del <form> de registro de eventos
        const dias = document.querySelectorAll("[name='dia']") 

        const inputHiddenDia = document.querySelector("[name='dia_id']") 

        categoria.addEventListener("change", terminoBusqueda)
        dias.forEach( dia => dia.addEventListener("change", terminoBusqueda))

        // función para cargar el objeto global busqueda con lo que el admin carga tanto en el <select> de categoria como en los inputs de selección del día del evento (función reutilizable)
        function terminoBusqueda(e) {
            busqueda[e.target.name] = e.target.value
        }
    
    }

})(); // En JavaScript, es importante finalizar el bloque de un IIFE con ";" para que no rompa la compilación en un archivo común (/public/build/js/bundle.min.js) (VIDEO 735)