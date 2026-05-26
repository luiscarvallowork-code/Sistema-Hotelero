<?php

if ($_GET) {



    $idRentaHabitacion = $_GET["idRenta"];

    $datos = myDB::obtenerDataReservacion($idRentaHabitacion);
    $datosRentaHabitacion = $datos["datos_renta"];
    $datosCliente = $datos["datos_cliente"];
    $datosPago = $datos["datos_pago"];
    $datosHabitacion = $datos["datos_habitacion"];
    $opcionesPago = myDB::obtenerOpcionesPago();
    $listaHabitacionesDisponibles = myDB::obtenerListaHabitacionesDisponibles($datosRentaHabitacion["fechaEntrada"], $datosRentaHabitacion["fechaEntrada"], 0);


    tools::mostrarVariableConsolaJs($datosPago);
    // tools::mostrarVariableConsolaJs($listaHabitacionesDisponibles);
}
?>



<main class="info-hab-main-container">


    <h1 class="info-hab-text-center info-hab-main-title">INFORMACIÓN DE HABITACIÓN</h1>

    <section class="info-hab-section-header">
        <h2 class="info-hab-section-title">DETALLES DE LA HABITACIÓN</h2>

        <div class="info-hab-top-grid">
            <div class="info-hab-field-group">
                <label class="info-hab-label">TIPO DE HABITACIÓN:</label>
                <input type="text" name="tipo_habitacion" class="info-hab-input-heavy"
                    value="<?= $datosHabitacion["tipo"]; ?>" readonly>
            </div>

            <div class="info-hab-field-group">
                <label class="info-hab-label">NÚMERO:</label>
                <input type="text" name="numero_habitacion" class="info-hab-input-heavy"
                    value="<?= $datosHabitacion["nombre"]; ?>" readonly>
            </div>

            <div class="info-hab-field-group">
                <span class="info-hab-label">ESTADO ACTUAL:</span>
                <div class="info-hab-status-row">
                    <?php

                    $textoAux = "";
                    $fechaSalidaAuxiliar = tools::fechaFormato_dmyy($datosRentaHabitacion["fechaSalida"]);

                    if ($datosRentaHabitacion["activo"] == 1) {
                        $textoAux = '
                             <span class="info-hab-badge-green">OCUPADA</span>';
                    } else {
                        $textoAux = '
                             <span class="info-hab-badge-red">RESERVADA</span>';
                    }

                    echo
                    $textoAux;
                    ?>





                </div>
            </div>
        </div>
    </section>

    <hr class="info-hab-divider">

    <section class="info-hab-section-details">
        <h2 class="info-hab-section-title">DETALLES DE LA INSTANCIA / RESERVACIÓN</h2>

        <div class="info-hab-cards-flex">
            <article class="info-hab-detail-card">
                <form method="post" action="controllers\formularioControllers.php">
                    <h3 class="info-hab-card-header">DATOS DEL CLIENTE</h3>
                    <div class="info-hab-input-item">
                        <input hidden type="number" name="id" value="<?= $datosCliente["id"] ?>">
                        <input type="number" name="id_rentaHabitacion"
                            value=<?= $datosRentaHabitacion["id"] ?> hidden>
                        <label class="info-hab-card-label">Nombre:</label>
                        <input id="cliente_nombre" type="text" name="cliente_nombre" class="info-hab-input-readonly"
                            value="<?= $datosCliente["nombre"] ?>" readonly>
                    </div>
                    <div class="info-hab-input-item">
                        <label class="info-hab-card-label">Cédula:</label>
                        <input id="cliente_cedula" type="text" name="cliente_cedula" class="info-hab-input-readonly"
                            value="<?= $datosCliente["ci"] ?>" readonly>
                    </div>
                    <div class="info-hab-input-item">
                        <label class="info-hab-card-label">Teléfono:</label>
                        <input id="cliente_telefono" type="text" name="cliente_telefono" class="info-hab-input-readonly"
                            value="<?= $datosCliente["numeroTelefono"] ?>" readonly>
                    </div>

                    <div class="info-hab-input-item">
                        <label class="info-hab-card-label">Empresa:</label>
                        <input id="cliente_empresa" type="text" name="cliente_empresa" class="info-hab-input-readonly"
                            value="<?= $datosCliente["empresa"] ?>" readonly>
                    </div>

                    <div class="info-hab-input-item">
                        <label class="info-hab-card-label">Ciudad:</label>
                        <input id="cliente_ciudad" type="text" name="cliente_ciudad" class="info-hab-input-readonly"
                            value="<?= $datosCliente["ciudad"] ?>" readonly>
                    </div>

                    <button type="button" class="info-hab-btn-edit" id="boton_activar_edicion_cliente">Editar Cliente</button>
                    <button type="submit" name="submit" class="info-hab-btn-edit" id="boton_editar_cliente" value="submit_renta_actualizarDatosCliente" hidden>Actualizar datos</button>
                </form>
            </article>

            <article class="info-hab-detail-card">
                <h3 class="info-hab-card-header">PLAZO DE LA RESERVACIÓN</h3>
                <form action="controllers\formularioControllers.php" method="post" onsubmit="return comprobarFechas()">
                    <div class="info-hab-input-item">

                        <input type="number" name="id_rentaHabitacion"
                            value=<?= $datosRentaHabitacion["id"] ?> hidden>
                        <label class="info-hab-card-label">FECHA DE ENTRADA:</label>
                        <input type="date" id="fecha_entrada" name="fecha_entrada" class="info-hab-input-readonly"
                            value="<?= tools::fechaFormato_Ymd($datosRentaHabitacion["fechaEntrada"]); ?>"
                            readonly>
                    </div>
                    <div class="info-hab-input-item">
                        <label class="info-hab-card-label">FECHA DE SALIDA:</label>
                        <input type="date" id="fecha_salida" name="fecha_salida" class="info-hab-input-readonly"
                            value="<?= tools::fechaFormato_Ymd($datosRentaHabitacion["fechaSalida"]); ?>"
                            readonly>
                    </div>

                    <button type="button" class="info-hab-btn-edit" id="boton_activar_edicion_fecha">Editar Plazo</button>
                    <button type="submit" name="submit" class="info-hab-btn-edit" id="boton_editar_fecha" value="submit_renta_actualizarDatosPlazo" hidden>Actualizar datos</button>
                </form>

                <!-- <h3 class="info-hab-card-header">Cambiar de habitacion</h3> -->
                <form action="controllers\formularioControllers.php" method="post">
                    <br>

                    <input type="number" name="id_rentaHabitacion"
                        value=<?= $datosRentaHabitacion["id"] ?> hidden>

                    <select name="habitacionesDisponibles" id="select_habitacion_disponible" style="display: none">


                        <?php
                        foreach ($listaHabitacionesDisponibles as $numHabitacion) {
                            echo  '<option value="' . $numHabitacion[1] . '">' . $numHabitacion[0] . '</option>';
                        }
                        ?>
                    </select>
                    <button type="submit" name="submit" class="info-hab-btn-edit"
                        id="boton_cambiar_habitacion" value="submit_renta_cambiar_habitacion" style="display: none">Actualizar datos</button>

                    <br>
                    <button type="button" class="info-hab-btn-edit"
                        id="boton_activar_edicion_numHabitacion">Cambiar de habitacion</button>

                </form>
            </article>

            <article class="info-hab-detail-card" style="position:relative;">
                <div class="container-botonesPago">
                    <button hidden id="botonAtras" type="button" onclick="modificarPagoMostrado(-1)">⬅</button>
                    <button type="button" class="boton-agregarPago" onclick="agregarPago()">➕</button>
                    <button hidden id="botonSiguiente" type="button" onclick="modificarPagoMostrado(1)">➡</button>
                </div>


                <form method="post" action="controllers\formularioControllers.php">

                    <div class="contenedorDeslizante" id="contenedorDeslizante">

                        <input type="number" name="id_rentaHabitacion"
                            value=<?= $datosRentaHabitacion["id"] ?> hidden>
                        <input type="number" name="numPagosOriginales" value=<?= count($datosPago) ?> hidden>
                        <?php
                        foreach ($datosPago as $index => $pago) {
                        ?>

                            <div id="contenedorPago" class="columna-box columna-pago" hidden>


                                <div class="info-hab-card-header-container">
                                    <h3 class="info-hab-card-header info-hab-card-header-pago">ESTADO DEL PAGO <?= ($index + 1) ?></h3>
                                    <input type="number" name="id[]"
                                        value=<?= $pago["id"] ?> hidden>
                                    <div style="display: flex;">
                                        <p class="info-hab-text-paid info-hab-text-const">PAGADO</p>
                                    </div>
                                </div>
                                <div class="info-hab-input-item">
                                    <label class="info-hab-card-label">TIPO de PAGO:</label>
                                    <input id="pago_tipo" type="text" name="pago_tipo[]" class="info-hab-input-readonly info-hab-input-tipo-text"
                                        value="<?= $pago["tipoNombre"] ?>"

                                        readonly>
                                    <select name="pago_tipo_seleccion[]" id="pago_tipo_seleccion" class="info-hab-input-tipo" hidden>

                                        <?php
                                        foreach ($opcionesPago as $indexAux => $opcion) {
                                            $textAux = ($indexAux + 1) == $pago["tipoId"] ? "selected" : "";
                                        ?>


                                            <option value="<?= $opcion["id"] ?>" <?= $textAux ?>> <?= $opcion["nombre"]  ?></option>
                                        <?php   } ?>
                                    </select>
                                </div>

                                <div class="info-hab-input-item">
                                    <label class="info-hab-card-label">Cantidad:</label>



                                    <input id="pago_cantidad" type="number" name="pago_cantidad[]" step="any"
                                        class="info-hab-input-readonly info-hab-input-cantidad" readonly
                                        value=<?= $pago["cantidad"] ?>>
                                </div>

                                <div class="info-hab-input-item">
                                    <label class="info-hab-card-label">REFERENCIA:</label>
                                    <input id="referenciaPago" type="text" name="referenciaPago[]"
                                        class="info-hab-input-readonly info-hab-input-referncia" readonly
                                        value=<?= $pago["referencia"] ?>>
                                </div>





                            </div>
                        <?php       } ?>

                        <?php
                        if (count($datosPago) == 0) {
                        ?>
                            <div id="contenedorPago" class="columna-box columna-pago" hidden>
  
                          
                                <div class="info-hab-card-header-container">
                                    <h3 class="info-hab-card-header info-hab-card-header-pago">REGISTRO PAGO 1</h3>
                                    <input type="number" name="id[]" hidden>
                                    <div style="display: flex;">
                                        <p class="info-hab-text-nopaid info-hab-text-const">PENDIENTE</p>
                                    </div>
                                </div>


                                <div class="info-hab-input-item">
                                    <label class="info-hab-card-label">TIPO de PAGO:</label>

                                    <select name="pago_tipo_seleccion[]" id="pago_tipo_seleccion" class="info-hab-input-tipo">


                                        <?php
                                        foreach ($opcionesPago as $indexAux => $opcion) {
                                            $textAux =  "";
                                        ?>


                                            <option value="<?= $opcion["id"] ?>" <?= $textAux ?>> <?= $opcion["nombre"]  ?></option>
                                        <?php   } ?>
                                    </select>
                                </div>

                                <div class="info-hab-input-item">
                                    <label class="info-hab-card-label">Cantidad:</label>



                                    <input id="pago_cantidad" type="number" name="pago_cantidad[]" step="any"
                                        class="info-hab-input-readonly info-hab-input-cantidad">
                                </div>

                                <div class="info-hab-input-item">
                                    <label class="info-hab-card-label">REFERENCIA:</label>
                                    <input id="referenciaPago" type="text" name="referenciaPago[]"
                                        class="info-hab-input-readonly info-hab-input-referncia">
                                </div>
                            </div>

                        <?php } ?>


                    </div>


                    <?php if (count($datosPago) == 0) { ?>
                        <button type="submit" name="submit" class="info-hab-btn-edit" id="boton_enviar_pago" value="submit_renta_actualizarDatosPago">
                            Registrar Pagos
                        </button>
                    <?php } else { ?>
                        <button type="button" class="info-hab-btn-edit" id="boton_activar_edicion_pago">Editar Pago</button>
                        <button type="submit" name="submit" class="info-hab-btn-edit" id="boton_editar_pago" value="submit_renta_actualizarDatosPago"
                            hidden>Actualizar Datos</button>
                    <?php } ?>

                    <!--  -->


                </form>



            </article>
        </div>
    </section>

    <div class="info-hab-footer-actions">
        <button type="button" id="boton_eliminar_registro" class="info-hab-btn info-hab-btn-red ">ELIMINAR REGISTRO DE RENTA</button>
        <?php
        if ($datosRentaHabitacion["activo"] == 0) {


        ?>


            <form action="controllers\formularioControllers.php" method="post">
                <input type="number" name="id_reservacion" value=0 hidden>
                <input type="number" name="id_renta" value="<?= $datosRentaHabitacion["id"] ?>" hidden>
                <button type="submit" name="submit" value="submit_convertirReservacionIngreso"
                    class="info-hab-btn info-hab-btn-green">Confirmar Ingreso</button>
            </form>

        <?php
        }
        ?>
    </div>

    </form>
</main>

<script src="resources\js\respuestasServidor.js"></script>
<script src="resources\js\datosHabitacion.js"></script>


<script>
    // funciones asincronas
    boton_elimina_registro.addEventListener("click", function() {

        window.location.replace("controllers/router.php?code=confirmarBorradoRegistro&idRenta=<?= $datosRentaHabitacion["id"] ?> ");
        // Guardamos la respuesta en una variable

    });

    // EVENTOS
</script>