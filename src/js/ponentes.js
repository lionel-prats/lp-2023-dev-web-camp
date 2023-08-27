// IIFE -> Inmediately Invoke Function Expression vvv
(function() {

    const ponentesInput = document.querySelector("#ponentes");

    if(ponentesInput) {

        let ponentes = [];
        let ponentesFiltrados = [];

        obtenerPonentes();

        ponentesInput.addEventListener("input", buscarPonentes);

        async function obtenerPonentes() {
            try {
                const url = `http://localhost:3000/api/ponentes`;
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();
                
                formatearPonentes(resultado);

            } catch (error) {
                console.log(error);
            }
        }

        function formatearPonentes(arrayPonentes = []) {
            ponentes = arrayPonentes.map( ponente => {
                return {
                    nombre: `${ponente.nombre.trim()} ${ponente.apellido.trim()}`,
                    id: ponente.id
                }
            })
        }

        function buscarPonentes(e) {
            busqueda = e.target.value;
            if(busqueda.length > 3) {
                console.log(busqueda);

                // creo una expresión regular utilizando el valor de busqueda (el string mayor a 3 caracteres ingresado por el admin en el input de ponente). El modificador o bandera (flag) "i" se utiliza para que la expresión regular sea insensible a mayúsculas y minúsculas, lo que significa que buscará el subtring ingresado por el admin en el array de ponentes (array formateado de la consulta que hicimos a nuestra API) sin importar si las letras están en mayúsculas o minúsculas. (VIDEO 745)
                const expresion = new RegExp(busqueda, "i");

                ponentesFiltrados = ponentes.filter( ponente => {
                    if( ponente.nombre.search(expresion) != -1 ) {
                        return ponentesFiltrados;
                    }
                });

                
                console.log(ponentesFiltrados);


                


            }
        }
    }

    
})(); // En JavaScript, es importante finalizar el bloque de un IIFE con ";" para que no rompa la compilación en un archivo común (/public/build/js/bundle.min.js) (VIDEO 735)

