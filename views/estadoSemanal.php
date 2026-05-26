<?php
$listaPosiciones = myDB::obtenerPosicionesHabitaciones(2);

// $listaPisos=myDB::obtenerListaPisos();

// tools::mostrarVariableConsolaJs($listaPisos);



?>

<div class="room-manager-container">
    <a class="gen-list-btn boton-flotante-estado-habitacion" href="controllers/router.php?code=estadoHabitaciones">Estado Diario</a>


    <div class="contenedor-fecha">
        <button type="button" id="button_anterior_semana" class="boton-fecha">
            &lt;
        </button>
        <input id="fechaSeleccion" type="text" value="SEMANA 1 02/02 | 08/02 ">
        <button type="button" id="button_siguiente_semana" class="boton-fecha">
            &gt;
        </button>
    </div>

    <main class="room-manager-calendar-grid">
        <?php ?>



        <?php ?>


        <section class="room-manager-day-col listaDia">
            <!-- <h3 class="room-manager-day-title">Domingo</h3>
            <div class="room-manager-card room-status-ok">106</div> -->
        </section>

        <section class="room-manager-day-col listaDia">
            <!-- <h3 class="room-manager-day-title">Lunes</h3>
            <div class="room-manager-card room-status-ok">101</div> -->
        </section>

        <section class="room-manager-day-col listaDia">
            <!-- <h3 class="room-manager-day-title">Martes</h3>
            <div class="room-manager-card room-status-ok">105</div> -->
        </section>

        <section class="room-manager-day-col listaDia">
            <!-- <h3 class="room-manager-day-title">Miércoles</h3>
            <div class="room-manager-card room-status-ok">106</div> -->
        </section>

        <section class="room-manager-day-col listaDia">
            <!-- <h3 class="room-manager-day-title">Jueves</h3>
            <div class="room-manager-card room-status-ok">106</div> -->
        </section>


        <section class="room-manager-day-col listaDia">
            <!-- <h3 class="room-manager-day-title">Viernes</h3>
            <div class="room-manager-card room-status-ok">106</div> -->
        </section>

        <section class="room-manager-day-col listaDia">
            <!-- <h3 class="room-manager-day-title">Sabado</h3> -->
            <!-- <div class="room-manager-card room-status-ok">106</div> -->
            <!-- <div class="room-manager-card">106</div> -->
        </section>





    </main>

    <footer class="room-manager-maintenance-footer" id="room-manager-maintenance-footer">
        <h3 class="room-manager-maint-title">HABITACIONES EN REPARACION</h3>
        <div class="room-manager-maint-list" id="room-manager-maint-list">

        </div>
    </footer>
</div>

<script src="resources\js\respuestasServidor.js"></script>

<script>
    //Constantes
    const textRangoFechas = document.getElementById("fechaSeleccion");
    const listaContainerDias = document.getElementsByClassName("listaDia");
    const diasTexto = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
    const contenedorCuadrosReparacion = document.getElementById("room-manager-maint-list");






    function obtenerListaFechasSemana(fechaActual) {
        let semana = [];
        const dia = fechaActual.getDay();
        // domingo - 0
        // luines - 1
        // martes- 2
        // miercoles - 3
        // jueves - 4
        // viernes - 5
        // sabado - 6
        const fechaDia = fechaActual.getDate();
        for (i = dia; i > 0; i--) {
            const auxFecha = new Date(fechaActual);
            auxFecha.setDate(fechaActual.getDate() - i)
            const dia2 = String(auxFecha.getDate()).padStart(2, "0");
            const mes = String(auxFecha.getMonth() + 1).padStart(2, "0");
            const anio = auxFecha.getFullYear();
            const text = dia2 + "/" + mes + "/" + anio;
            semana.push(text);
        }
        if (semana) {
            const auxFecha = fechaActual;
            const dia2 = String(auxFecha.getDate()).padStart(2, "0");
            const mes = String(auxFecha.getMonth() + 1).padStart(2, "0");
            const anio = auxFecha.getFullYear();
            const text = dia2 + "/" + mes + "/" + anio;
            semana.push(text);
        }
        contAux = 1;
        for (i = dia + 1; i < 7; i++) {
            const auxFecha = new Date(fechaActual);
            auxFecha.setDate(fechaActual.getDate() + contAux);
            const dia2 = String(auxFecha.getDate()).padStart(2, "0");
            const mes = String(auxFecha.getMonth() + 1).padStart(2, "0");
            const anio = auxFecha.getFullYear();
            const text = dia2 + "/" + mes + "/" + anio;
            semana.push(text);
            contAux++;
        }
        return semana;
    }

    function cambiarTextoRangoFechas(arraySemana) {
        primeraFecha = arraySemana[0].slice(0, 5);
        ultimaFecha = "";
        arraySemana.forEach(element => {
            ultimaFecha = element.slice(0, 5);
        });
        return primeraFecha + " " + ultimaFecha;
    }

    async function cargarEstadoSemanal(semanaObjetivo, listaContainerDias) {
        const datosEnvio = {
            semana: semanaObjetivo
        }

        const respuesta = await respuesta_servidor.consultaServidor("obtenerEstadoHabitacionesSemanal", datosEnvio);
        // console.log(respuesta);


        const datosSemana = respuesta[0];
        let habitacionesReparacion = [];
        for (i = 0; i < 7; i++) {
            const textDia = semanaObjetivo[i].slice(0, 2);
            // insertar titulo
            const containerTitulo = document.createElement("div");
            containerTitulo.classList.add("room-manager-day-title-container");

            const titulo = document.createElement("h3");
            titulo.classList.add("room-manager-day-title");
            titulo.innerHTML = diasTexto[i] + "  " + textDia;
            containerTitulo.appendChild(titulo);
            listaContainerDias[i].innerHTML = "";
            listaContainerDias[i].appendChild(containerTitulo);


            datosDia = datosSemana[i];
            // console.log(datosDia);


            const ocupadas = datosDia[0];
            const reparacion = datosDia[1];



            ocupadas.forEach(element2 => {
                // console.log(element2);
                const containerLink = document.createElement("a");
                containerLink.classList.add("contenedor-cuadro");

                const linkAuxiliar = "controllers/router.php?code=datosHabitacion&idRenta=" + element2["id"];

                containerLink.href = linkAuxiliar;



                const containerFecha = document.createElement("div");
                containerFecha.classList.add("room-manager-card");

                const claseAux = element2["activo"] == 1 ? "cuaddroHabitacionOcupacion" : "cuaddroHabitacionRervado";
                containerFecha.classList.add(claseAux);
                containerFecha.innerHTML = element2["nombre"];

                containerLink.appendChild(containerFecha);
                listaContainerDias[i].appendChild(containerLink);

                //

            });

            reparacion.forEach(element2 => {


                let guardar = true;

                habitacionesReparacion.forEach(registro => {

                    if (registro["nombre"] == element2["nombre"] &&
                        registro["fecha_inicio"] == element2["fecha_inicio"] &&
                        registro["fecha_final"] == element2["fecha_final"]
                    ) guardar = false;

                });


                if (guardar == true) {
                    habitacionesReparacion.push(element2);
                }

            });







        }
        // console.log(habitacionesReparacion);

        contenedorCuadrosReparacion.innerHTML = "";
        habitacionesReparacion.forEach(registro => {

            const containerCuadro = document.createElement("div");
            containerCuadro.classList.add("room-manager-maintenance-footer-section");

            const containerLink = document.createElement("a");
            containerLink.classList.add("contenedor-cuadro");



            const linkAuxiliar = "controllers/router.php?code=estadoMantenimientoHabitacion&idMan=" + registro["id"];

            containerLink.href = linkAuxiliar;

            const containerFecha = document.createElement("div");
            containerFecha.classList.add("room-manager-card");
            containerFecha.classList.add("cuaddroHabitacionMantenimiento");
            containerFecha.innerHTML = registro["nombre"];


            containerLink.appendChild(containerFecha);

            containerCuadro.appendChild(containerLink);


            contenedorCuadrosReparacion.appendChild(containerCuadro);

            // 
        });

    }




    let fechaObjetivo = new Date();
    let semana = obtenerListaFechasSemana(fechaObjetivo);




    textRangoFechas.value = cambiarTextoRangoFechas(semana);





    cargarEstadoSemanal(semana, listaContainerDias);


    // eventos

    document.getElementById("button_anterior_semana").addEventListener("click", () => {
        const auxFecha = new Date(fechaObjetivo);
        auxFecha.setDate(fechaObjetivo.getDate() - 7);
        fechaObjetivo = auxFecha;
        semana = obtenerListaFechasSemana(auxFecha);
        textRangoFechas.value = cambiarTextoRangoFechas(semana);
        cargarEstadoSemanal(semana, listaContainerDias);

    });


    document.getElementById("button_siguiente_semana").addEventListener("click", () => {
        const auxFecha = new Date(fechaObjetivo);
        auxFecha.setDate(fechaObjetivo.getDate() + 7);
        fechaObjetivo = auxFecha;
        semana = obtenerListaFechasSemana(auxFecha);
        textRangoFechas.value = cambiarTextoRangoFechas(semana);
        cargarEstadoSemanal(semana, listaContainerDias);
    });
</script>