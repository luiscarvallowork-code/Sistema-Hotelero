<?php


// tools::mostrarVariableConsolaJs();

myDB::actualizarDatosReservaciones("2026-01-05")
?>

<div class="mensajeFlotante" id="mensajeFlotante">
    <div class="mensajeFlotante-contenedorExterior">
        <button class="mensajeFlotante-botonCierre" onclick="cerrarVentana()"><span>X</span></button>
        <div class="mensajeFlotante-contenedorMensaje">
            <p id="mensajeFlotante-mensajeError"> </p>
        </div>
    </div>
</div>


<div class="vista-ingreso">

    <h1>Registro de renta de habitacion</h1>

    <div class="encabezado-formulario">

        <div class="switch-container">
            <div class="switch-button">
                <input type="radio" id="notif_si" name="tipoFormulario" value="1" checked>
                <label for="notif_si">INGRESO</label>

                <input type="radio" id="notif_no" name="tipoFormulario" value="0">
                <label for="notif_no">RESERVACIÓN</label>
            </div>
        </div>

        <div class="info-tasa">
            <p id="fecha_tasa" class="fecha_tasa"> </p>
            <p id="tasa_del_dia" class="tasa_del_dia"></p>

        </div>

    </div>
    <div class="contenedor-formulario">

        <form action="controllers\formularioControllers.php" method="post" onsubmit="return comprobarFomulario()">

            <input type="text" hidden name="tipoFormularioEnviado" id="tipoFormularioEnviado" value="1">

            <div class="columna-box columna-cliente">
                <h2>DATOS DEL CLIENTE</h2>

                <label for="nombre">Nombre Completo</label>
                <input type="text" name="nombre" required>

                <label for="cedula">Cédula / Pasaporte</label>
                <input type="number" name="cedula" required>

                <label for="telefono">Teléfono</label>
                <input type="tel" name="telefono" required>

                <label for="ciudad">Ciudad de Origen</label>
                <input type="text" name="ciudad" required>

                <label for="empresa">Empresa (Opcional)</label>
                <input type="text" name="empresa">
            </div>

            <div class="columna-box columna-habitacion">
                <h2>DISPONIBILIDAD DE HABITACIÓN</h2>

                <label for="fechaEntrada">Fecha Entrada</label>
                <input type="date" lang="es-ES" name="fechaEntrada" id="input_fechaEntrada" required placeholder="dd/mm/aa">

                <label for="fechaSalida">Fecha Salida</label>
                <input type="date" name="fechaSalida" id="input_fechaSalida" required placeholder="dd/mm/aa">

                <!-- <label hidden for="habitacion" style="border-bottom: solid 1px rgb(221, 221, 221);">Numero de dias: </label> -->
                <label for="Tipo de habitacion">Tipo de Habitación</label>
                <select name="tipoHabitacion" id="input_tipoHabitacion">
                    <option value="0">Todas</option>
                    <option value="1">Matrimonial</option>
                    <option value="2">Doble</option>
                    <option value="3">Triple</option>
                    <option value="4">Ejecutiva</option>
                    <option value="5">Apartamento</option>
                </select>

                <h2>SELECCIONAR HABITACIÓN</h2>
                <label for="habitacion">Habitación Número:</label>
                <select name="habitacion" id="habitaciones" class="select_habitaciones">
                </select>


       
            </div>


            <div class="contenedorDeslizante" id="contenedorDeslizante">


                <div id="contenedorPago" class="columna-box columna-pago">
                    <div class="container-botonesPago">
                        <button hidden id="botonAtras" type="button" onclick="modificarPagoMostrado(-1)">⬅</button>
                        <button type="button" class="boton-agregarPago" onclick="agregarPago()">➕</button>
                        <button hidden id="botonSiguiente" type="button" onclick="modificarPagoMostrado(1)">➡</button>
                    </div>

                    <h2 class="estado-pago-tituloPrincipal">Estado DEL PAGO</h2>
                    <div class="estado-pago-container">
                        <input type="radio" id="estadoPagoTrue" name="estadoPago" value="1" checked>
                        <label for="estadoPagoTrue">PAGADO</label>

                        <input type="radio" id="estadoPagoFalse" name="estadoPago" value="0">
                        <label for="estadoPagoFalse">PENDIENTE</label>
                    </div>

                    <h2 class="estado-pago-titulo">Detalles del pago 1</h2>

                    <label for="monto">Monto (USD)</label>
                    <div class="monto-container">
                        <input type="number" name="monto[]" class="input_monto" id="input_monto" step="any" readonly required>
                        <button id="button_precioEspecial" class="button_precioEspecial" type="button" value="ingresar" onclick="liberarPrecio()">Ingresar Precio Especial</button>
                    </div>
                    <label for="montoBs[]">Monto en Bs</label>
                    <input class="montoBs" type="number" step="any" name="montoBs[]" id="montoBs" readonly>

                    <label for="tipoPago">Tipo de Pago</label>
                    <select name="tipoPago[]" id="input_tipoPago" class="selectTipoPago">
                        <option value="1">Bs</option>
                        <option value="2">Pago móvil</option>
                        <option value="3">Transferencia</option>
                        <option value="4">Zelle</option>
                        <option value="5">Divisas</option>
                    </select>

                    <label for="referenciaPago">Referencia (Opcional)</label>
                    <input type="text" name="referenciaPago[]" placeholder="Opcional">


                </div>

                <!-- contenedor pago falso -->






            </div>

            <div class="botones-finales">
                <button type="submit" name="submit" value="submit_ingresarHabitacion" class="boton-accion ">CONFIRMAR INGRESO / RESERVACIÓN</button>
            </div>

        </form>
    </div>

</div>

<script type="module" src="resources\js\libreriasExternas.js"></script>

<script src="resources\js\respuestasServidor.js"></script>
<script src="resources\js\ingreso_reservacion.js"></script>
<script>
    const pago = document.getElementById("contenedorPago");
    let indicePago = 0;
    const contenedorDeslizante = document.getElementById("contenedorDeslizante");

    const botonAtras = document.getElementById("botonAtras");
    const botonSiguiente = document.getElementById("botonSiguiente");


    function ocultarPagos(listaPagos, number) {

        listaPagos.forEach(element => {
            element.setAttribute("hidden", true);
        });


        listaPagos[number].hidden = false;
    }

    function modificarPagoMostrado(number) {
        console.log("rastreo error");
        const listaPagos = contenedorDeslizante.querySelectorAll('.columna-pago');

        aux = indicePago + number;


        console.log(listaPagos.length);
        if (aux == listaPagos.length || aux < 0) {
            return;
        }

        indicePago = aux;

        ocultarPagos(listaPagos, indicePago);

    }

    function agregarPago() {

        const listaPagos = contenedorDeslizante.querySelectorAll('.columna-pago');
        if (indicePago == 0) {
            botonAtras.hidden = false;
            botonSiguiente.hidden = false;
        }

        if (listaPagos.length == 4) {
            return;
        }
        indicePago = listaPagos.length;

        // console.log(indicePago);
        const pagoNuevo = pago.cloneNode(true);



        const titulo = pagoNuevo.getElementsByClassName("estado-pago-titulo")[0];
        titulo.textContent = "Detalles del Pago " + (indicePago + 1);

        const tituloBorrar = pagoNuevo.getElementsByClassName("estado-pago-tituloPrincipal")[0];
        tituloBorrar.remove();
        const seccionBorrar = pagoNuevo.getElementsByClassName("estado-pago-container")[0];
        seccionBorrar.remove();


        const containerMonto = pagoNuevo.getElementsByClassName("monto-container")[0];

        const seccionMonto = containerMonto.getElementsByClassName("input_monto")[0];

        seccionMonto.removeAttribute("id");
        seccionMonto.removeAttribute("readonly");
        seccionMonto.value = 0;




        const borrarBoton = containerMonto.getElementsByClassName("button_precioEspecial")[0];

        const seccionMontoBs = pagoNuevo.getElementsByClassName("montoBs")[0];

        seccionMontoBs.removeAttribute("readonly");
        seccionMontoBs.value = 0;


        seccionMontoBs.addEventListener("input", function(event) {
            seccionMonto.value = (seccionMontoBs.value / tasaDia).toFixed(2);
        });

        seccionMonto.addEventListener("input", function(event) {
            seccionMontoBs.value = (seccionMonto.value * tasaDia).toFixed(2);
        });



        borrarBoton.remove();
        // console.log(pagoNuevo);

        contenedorDeslizante.appendChild(pagoNuevo);


        const listaPagosNueva = contenedorDeslizante.querySelectorAll('.columna-pago');
        // console.log(indicePago);
        // console.log(listaPagos);

        ocultarPagos(listaPagosNueva, (indicePago));



    }
</script>