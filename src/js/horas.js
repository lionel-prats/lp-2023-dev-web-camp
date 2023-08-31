// IIFE -> Inmediately Invoke Function Expression vvv
(function() {

    const horas = document.querySelector("#horas")
    
    if(horas) {

        // <select name="categoria_id"> -> select de categorías para la creación de un evento
        const categoria = document.querySelector("[name='categoria_id']") 
        
        // selecciono los elementos HTML con name="dia" (los <inputs type="radio">) del <form> de registro de eventos
        const dias = document.querySelectorAll("[name='dia']") 

        const inputHiddenDia = document.querySelector("[name='dia_id']") 
        const inputHiddenHora = document.querySelector("[name='hora_id']") 

        categoria.addEventListener("change", terminoBusqueda) // escucho por cambios en el select de categorias
        dias.forEach( dia => dia.addEventListener("change", terminoBusqueda)) // escucho por cambios en los inputs de día del evento

        const detallePrevioEvento = {
            categoria_id: categoria.value || "", 
            dia: inputHiddenDia.value || "",
            hora: inputHiddenHora.value || ""
        }
        // objeto en memoria -> va a contener el tipo de evento seleccionado (<select name="categoria_id">) y el día seleccionado (<input name="dia"> x 2) en el <form> de registro de eventos 
        // Para esto, las claves del objeto coinciden con los atributos name de los elementos HTML
        let busqueda = { 
            // || -> equivalente al ?? de php (VIDEO 752)
            // +... -> convierte un numero en formato string (int o float), en formato numero (int o float) (VIDEO 752)
            categoria_id: +categoria.value || "", 
            dia: +inputHiddenDia.value || ""
        }

        // Bloque que se ejecutará solo cuando se accede al form de edición de un evento (ya que en el form de creacion, busqueda inicia vacío y se llena con la interacción del usuario en el select de categorias e inputs de dia) (VIDEO 752)
        // forma de verificar si un objeto está completo
        if(!Object.values(busqueda).includes("")){
            async function iniciarApp() {
                // LO QUE ENTIENDO: el await está indicando que tiene que terminar de ejecutarse buscarEventos (y todo su desencadenamiento de funciones) para que luego se ejecute el codigo a continuacion dentro de esta misma funcion (el bloque para quitarle la clase "horas__hora--deshabilitada" al tag de hora asociado horiginalmente al evento a editar) (VIDEO 752)
                await buscarEventos();

                // BLOQUE para quitarle la clase "horas__hora--deshabilitada" al tag de hora asociado horiginalmente al evento a editar vvv
                // id de la hora seleccionada originalmente del evento a editar 
                // identifico la hora seleccionada originalmente en el panel de horas del form
                const horaSeleccionada = document.querySelector(`[data-hora-id="${inputHiddenHora.value}"]`);
                // le quito la clase que deshabilita visualmente un tag de hora al li con la hora originalmente seleccionada porque tengo la intención de mostrarla como seleccionada
                horaSeleccionada.classList.remove("horas__hora--deshabilitada");
                horaSeleccionada.classList.add("horas__hora--seleccionada");

                horaSeleccionada.onclick = seleccionarHora;
            }
            iniciarApp();
        } 

        // función para cargar el objeto global busqueda con lo que el admin carga tanto en el <select> de categoria como en los inputs de selección del día del evento (función reutilizable)
        function terminoBusqueda(e) {
            
            busqueda[e.target.name] = e.target.value // reescribo el valor que corresponda en el objeto global busqueda (categoria_id o dia)

            // reinicio los campos ocultos de dia y hora y los tags de horas ante cada cambio en el select de categorias o en los input de dia
            inputHiddenHora.value = ""
            inputHiddenDia.value = ""
            
            // desmarco la hora previamente seleccionada (si la hay)
            const horaPrevia = document.querySelector(".horas__hora--seleccionada")
            if(horaPrevia) {
                horaPrevia.classList.remove("horas__hora--seleccionada")
            }

            // si el objeto global busqueda está inclompleto salgo de la función
            // Genero un array con los valores del objeto y verifico si alguno es ""
            if(Object.values(busqueda).includes("")){
                return
            } 
            buscarEventos(); // peticion a nuestra API por los eventos de la categoría y día seleccionados por el admin para saber que horarios hay disponibles (se va a ejecutar solo si el objeto global busqueda está completo)
        }

        async function buscarEventos() { // Petición a nuestra API
            const { dia, categoria_id } = busqueda
            // try {
                const url = `http://localhost:3000/api/eventos-horario?dia_id=${dia}&categoria_id=${categoria_id}`
                const resultado = await fetch(url)
                const eventos = await resultado.json()
                // console.log(`consulta a la API: ${eventos.length} registros`);
                obtenerHorasDisponibles(eventos); 

            // } catch (error) {
            //     console.log(error);
            // }
        }

        // para determinar visualmente en el DOM los <li> con las horas habilitadas para seleccionar y quedar escuchando por un click
        function obtenerHorasDisponibles(eventos){
            
            
            // NodeList con todos los <li> hijos del <element id="horas"> (es decir, todos los <li> hijos de <div id="horas">)
            const listadoHoras = document.querySelectorAll("#horas li")

            // por default deshabilito visualmente todos los tags de horas cada vez que se haga una nueva petición a la API por las combinaciones que pueda ir haciendo el admin en el front (en el cliente, antes del submit). El código que sigue removerá esta clase habilitando las que correspondan según la combinación de categoría y día seleccionada por el admin
            // forEach es array method pero se puede usar sin problema en un NodeList
            listadoHoras.forEach( li => {
                li.classList.add("horas__hora--deshabilitada") 

                // dejo de escuchar momentaneamente por el click en todos los tags de hora para evitar errores en caso de que el admin cambie momentaneamente los parametros de busqueda (que implica una nueva peticion a la API) para que en ningun caso se puedan seleccionar horas deshabilitadas
                li.removeEventListener('click', seleccionarHora)

            })

            // con un .map armo un array con los valores hora_id (referencia a la tabla horas) de cada evento iterado (eventos es una consulta via fetch a nuestra API, a la tabla eventos) para saber que horas NO están disponibles para la combinación de categoría y día seleccionado por el admin antes del submit
            // será un array de tipo ["2", 4, "1"] donde cada elemento es el hora_id de cada evento registrado para la combinación categoría-día que hizo el admin previo al submit (a su vez, son referencia a la tabla horas) 
            const horasTomadas = eventos.map( evento => evento.hora_id)
            
            // convierto el NodeList anterior en array de nodos para poder operarlo como array y usar los métodos nativos de JS (VIDEO 742)
            const listadoHorasArray = Array.from(listadoHoras);
            
            // genero un array de nodos compuesto por los <li> de horas para seleccionar en el form de creacion de eventos, cuyos data-hora-id (que son los id correspondientes en la tabla horas) no estén incluidos en el array horasTomadas (que es el array de horas ya asignadas a eventos en la categoría y día seleccionados previamente por el admin para este nuevo evento)
            const resultado = listadoHorasArray.filter( li => !horasTomadas.includes(li.dataset.horaId) )
            
            // itero el array de nodos de horas disponibles para sacarle la clase que deshabilita visualmente el tag de hora para que el usuario sepa cuales son las horas disponibles y no disponibles
            // reflección: resultado es una variable distinta a la que originalmente capturó los <li> del DOM (listadoHoras). Sin embargo sirve para manipular los elementos capturados en el DOM (en este caso quitarles una clase)
            resultado.forEach( li => li.classList.remove("horas__hora--deshabilitada") )

            // selecciono todos los <li> hijos del <element id="horas"> (es decir, todos los <li> hijos de <div id="horas">) excepto los que tengan la clase "horas__hora--deshabilitada" para ejecutar un listener en ellos (VIDEO 742)
            const horasDisponibles = document.querySelectorAll("#horas li:not(.horas__hora--deshabilitada)")

            compararSelecciones();
            
            horasDisponibles.forEach( hora => hora.addEventListener("click", seleccionarHora))
        }

        // funcion para habilitar y seleccionar el horario elegido previamente si la combinacion original categoria|dia coincide con la combinacion previa al submit del form de edicion de evento
        function compararSelecciones() {
            const categoriaPrevia = +detallePrevioEvento.categoria_id;
            const diaPrevio = +detallePrevioEvento.dia;
            const horaPrevia = detallePrevioEvento.hora;

            const categoriaActual = +busqueda.categoria_id;
            const diaActual = +busqueda.dia;

            if(categoriaPrevia === categoriaActual && diaPrevio === diaActual) {
                document.querySelector(`[data-hora-id="${horaPrevia}"]`).classList.remove("horas__hora--deshabilitada");
                document.querySelector(`[data-hora-id="${horaPrevia}"]`).classList.add("horas__hora--seleccionada");
                inputHiddenHora.value = horaPrevia;
            }
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
            
            // cargo en el input:hidden "de hora" el id de la hora seleccionada
            inputHiddenHora.value = e.target.dataset.horaId
            
            // cargo en el input:hidden "de día" el id del día seleccionado
            inputHiddenDia.value = document.querySelector("[name='dia']:checked").value
        }
    
    }

})(); // En JavaScript, es importante finalizar el bloque de un IIFE con ";" para que no rompa la compilación en un archivo común (/public/build/js/bundle.min.js) (VIDEO 735)