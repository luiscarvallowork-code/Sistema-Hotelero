
    let pisoActual=1;

    // estructurar de tal forma en que se pueda cambiar de pisos para hoteles con distintos tamaños

    const fechaObjetivo = document.getElementById("fechaSeleccion");

    fechaObjetivo.value = obtenerFechaFormatoInput(new Date());


    const botonSiguienteDia = document.getElementById("button_siguiente_dia");
    const botonAnteriorDia = document.getElementById("button_anterior_dia"); // ID corregido

    const tablaEstadoHabitaciones = document.getElementById("listaPosicionesHabitaciones");
    const celdasTablaEstados = tablaEstadoHabitaciones.querySelectorAll("td");


    function obtenerFechaFormatoInput(date) {
        // Asegura que siempre tengamos un objeto Date válido
        if (!(date instanceof Date) || isNaN(date)) {
            date = new Date();
        }

        // Obtiene los componentes de la fecha
        const year = date.getFullYear();
        // Los meses son base 0 (Enero es 0), por eso se suma 1
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    function manipularFecha(dias) {

        let fechaActual = fechaObjetivo.valueAsDate;


        if (!fechaActual) {
            fechaActual = new Date();
        }
        fechaActual.setDate(fechaActual.getDate() + dias);
        fechaObjetivo.value = obtenerFechaFormatoInput(fechaActual);
    }


    async function cargarEstadoHabitaciones() {
        try {
            const fechaObjetivo_ = fechaObjetivo.value + " 00:00:00";
            const datosEnvio = {
                fechaActual: fechaObjetivo_
            }

            const respuesta = await respuesta_servidor.consultaServidor("obtenerEstadoHabitaciones", datosEnvio);

            console.log(respuesta);
            const listaEstadosHabitaciones = respuesta[0];
            const listaEstadosHabitacionesMantenimiento = respuesta[1];


            celdasTablaEstados.forEach(element => {


                // El DIV sigue siendo la referencia
                const cuadroHabitacion = element.querySelector(".cuaddroHabitacion");

                const linea1 = "Habitacion: " + cuadroHabitacion.dataset.tipohabitacion;

                cuadroHabitacion.setAttribute('data-tooltip', linea1);


                if (cuadroHabitacion.classList.length > 1) {
                    cuadroHabitacion.className = "";
                    cuadroHabitacion.classList.add("cuaddroHabitacion");
                    cuadroHabitacion.classList.add("cuaddroHabitacionDesocupado");
                }

            });



            listaEstadosHabitaciones.forEach(habitacion => {
        
                const nombre = habitacion["nombre"];
                const fechaAux = new Date(habitacion["fechaSalida"]);
                let fechaSalida = fechaAux.getDay();
                fechaSalida = fechaSalida + "/" + fechaAux.getMonth();
                fechaSalida = fechaSalida + "/" + fechaAux.getFullYear();
                const cliente = habitacion["cliente"];


                const estadoHabitacion = document.getElementById(nombre);


                const linea1 = estadoHabitacion.dataset.tooltip;
                let linea2 = cliente;
                const linea3 = "hasta el " + fechaSalida;

                estadoHabitacion.classList.remove("cuaddroHabitacionDesocupado");
                if (habitacion["activo"] == 1) {
                    estadoHabitacion.classList.add("cuaddroHabitacionOcupacion");
                    linea2 = "Ocupada por " + linea2;
                } else {
                    estadoHabitacion.classList.add("cuaddroHabitacionRervado");
                    linea2 = "Reservada para " + linea2;
                }


                estadoHabitacion.setAttribute('data-tooltip', linea1 + "\n" + linea2 + "\n" + linea3 + "\n");

                botonAuxiliar= estadoHabitacion.parentElement;
                const linkAuxiliar= "controllers/router.php?code=datosHabitacion&idRenta="+habitacion["id"];
               
               botonAuxiliar.href=linkAuxiliar;
                // estadoHabitacion.setAttribute("href", linkAuxiliar);




            });

            listaEstadosHabitacionesMantenimiento.forEach(habitacion => {
                console.log(habitacion);
                const nombre = habitacion["nombre"];
                const fechaAux = new Date(habitacion["fecha_inicio"]);
                let fechaInicial = fechaAux.getDay();
                fechaInicial = fechaInicial + "/" + fechaAux.getMonth();
                fechaInicial = fechaInicial + "/" + fechaAux.getFullYear();







                const estadoHabitacion = document.getElementById(nombre);

                const linea1 = estadoHabitacion.dataset.tooltip;
                const linea2 = "Se encuentra en mantenimiento desde: " + fechaInicial;

                estadoHabitacion.classList.remove("cuaddroHabitacionDesocupado");

                estadoHabitacion.classList.add("cuaddroHabitacionMantenimiento");

                estadoHabitacion.setAttribute('data-tooltip', linea1 + "\n" + linea2);


                  botonAuxiliar= estadoHabitacion.parentElement;
                const linkAuxiliar= "controllers/router.php?code=estadoMantenimientoHabitacion&idMan="+habitacion["id"];
                // falta una vista para mostrar las habitaciones en mantenimiento
               botonAuxiliar.href=linkAuxiliar;

            });

        } catch (error) {
            console.error('Error:', error);
        }



    }


    document.getElementById("button_anterior_dia").addEventListener("click", () => {
        manipularFecha(0); // Corregido: para ir al día anterior, debe ser -1
        cargarEstadoHabitaciones();
    });

    // 3. Asigna la función para sumar 1 día
    document.getElementById("button_siguiente_dia").addEventListener("click", () => {
        manipularFecha(2); // Corregido: para ir al día siguiente, debe ser +1
        cargarEstadoHabitaciones();
    });

    fechaObjetivo.addEventListener("change", () => {
        cargarEstadoHabitaciones();
    });


    cargarEstadoHabitaciones();
