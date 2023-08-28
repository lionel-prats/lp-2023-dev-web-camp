// IIFE -> Inmediately Invoke Function Expression vvv
(function() {

    const ponentesInput = document.querySelector("#ponentes"); // input donde se carga el ponente de un evento a crear

    if(ponentesInput) {

        let ponentes = []; // array que contendrá la respuesta de la petición a la API (se mapeará de la respuesta solo con nombre, apellido, e id de cada ponente)
        let ponentesFiltrados = []; // array que se irá rellenando con los ponentes que coincidan con lo que va ingresando el usuario en el input (para renderizar el buscador en la pantalla)

        const listadoPonentes = document.querySelector("#listado-ponentes"); // <ul> donde se inyectará el HTML con los ponentes de ponentesFiltrados

        obtenerPonentes(); // peticion a nuestra API

        ponentesInput.addEventListener("input", buscarPonentes); // escucho por cambios en el input del ponente para generar dinamicamente el buscador en la vista

        async function obtenerPonentes() { // petición a la API
            try {
                const url = `http://localhost:3000/api/ponentes`;
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();
                
                formatearPonentes(resultado);

            } catch (error) {
                console.log(error);
            }
        }

        function formatearPonentes(arrayPonentes = []) { // completo el array ponentes a partir de la respuesta de la API
            ponentes = arrayPonentes.map( ponente => {
                return {
                    nombre: `${ponente.nombre.trim()} ${ponente.apellido.trim()}`,
                    id: ponente.id
                }
            })
        }

        function buscarPonentes(e) {

            busqueda = e.target.value; // capturo lo que ingresó el admin en el input
            
            if(busqueda.length > 3) { // a partir del 4to caracter buscamos el string ingresado en el array filtrado para mostrar por pantalla los ponentes que coincidan con lo ingresado 

                console.log(busqueda);

                // creo una expresión regular utilizando el valor de busqueda (el string mayor a 3 caracteres ingresado por el admin en el input de ponente). El modificador o bandera (flag) "i" se utiliza para que la expresión regular sea insensible a mayúsculas y minúsculas, lo que significa que buscará el subtring ingresado por el admin en el array de ponentes (array formateado de la consulta que hicimos a nuestra API) sin importar si las letras están en mayúsculas o minúsculas. (VIDEO 745)
                const expresion = new RegExp(busqueda, "i");

                // completo ponentesFiltrados, a partir de las coincidencias del string > a 3 caracteres que vaya ingresando el usuario en el input del ponente
                ponentesFiltrados = ponentes.filter( ponente => {
                    if( ponente.nombre.search(expresion) != -1 ) {
                        return ponentes;
                    }
                });
            } else {
                ponentesFiltrados = [];
            }
            mostrarPonentes();
        }

        function mostrarPonentes() {

            // vacío el contenido del <ul> del buscador de ponentes para volver a completarlo según corresponda 
            while(listadoPonentes.firstChild) {
                listadoPonentes.removeChild(listadoPonentes.firstChild);
            }

            if(ponentesFiltrados.length > 0) {
                ponentesFiltrados.forEach( ponente => {
                    const ponenteHTML = document.createElement("LI");
                    ponenteHTML.classList.add("listado-ponentes__ponente");   
                    ponenteHTML.textContent = ponente.nombre;
                    ponenteHTML.dataset.ponenteId = ponente.id;
                    
                    listadoPonentes.appendChild(ponenteHTML);
                });
            } else {
                const noResultados = document.createElement("P");
                noResultados.classList.add("listado-ponentes__no-resultado");   
                noResultados.textContent = "No hay resultados paara tu búsqueda";
                
                listadoPonentes.appendChild(noResultados);
            }


            
            
        }

    }

    
    
})(); // En JavaScript, es importante finalizar el bloque de un IIFE con ";" para que no rompa la compilación en un archivo común (/public/build/js/bundle.min.js) (VIDEO 735)

