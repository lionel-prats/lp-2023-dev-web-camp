// IIFE -> Inmediately Invoke Function Expression vvv
(function() {

    const ponentesInput = document.querySelector("#ponentes");

    if(ponentesInput) {

        let ponentes = [];
        let ponentesFiltrados = [];

        obtenerPonentes();

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
            console.log(ponentes);
        }
    }

    
})(); // En JavaScript, es importante finalizar el bloque de un IIFE con ";" para que no rompa la compilación en un archivo común (/public/build/js/bundle.min.js) (VIDEO 735)