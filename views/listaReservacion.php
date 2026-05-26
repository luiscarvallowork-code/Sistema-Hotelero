<?php

$listaReservacionesActivas = myDB::obtenerListaReservaciones(1);
$listaReservaciones = myDB::obtenerListaReservaciones();


tools::mostrarVariableConsolaJs($listaReservacionesActivas);
// tools::mostrarVariableConsolaJs($listaReservaciones);

?>
<main class="res-list-main-container">
    <h1 class="res-list-main-title">Lista de Reservaciones</h1>

    <div class="res-list-header-row">
        <span>Cliente</span>
        <span>Habitación</span>
        <span>Check-In</span>
        <span>Check-out</span>
        <span>Estado Pago</span>
        <span>Estado Res.</span>
        <span> </span>
    </div>

    <?php

    foreach ($listaReservacionesActivas as $res) {

        $fechaEntradaAux = tools::fechaFormato_dmyy($res["fechaEntrada"]);
        $fechaSalidaAux = tools::fechaFormato_dmyy($res["fechaSalida"]);
        $pago = "";
        $clasePago = "";
        $estadoReservacion = "";
        $claseEstadoReservacion = "";

        if ($res["estadoPago"]) {

            $pago = "Pagado";
            $clasePago = "res-list-status-success";
        } else {
            $pago = "Pendiente";
            $clasePago = "res-list-status-warning";
        }

        if ($res["estado"] == 0) {
            $estadoReservacion = "Caducada";
            $claseEstadoReservacion = "res-list-status-warning";
        } else if ($res["estado"] == 1) {
            $estadoReservacion = "Activa";
            $claseEstadoReservacion = "res-list-status-success";
        } else {
            $estadoReservacion = "Completada";
            $claseEstadoReservacion = "res-list-status-success";
        }

    ?>
        <div class="res-list-item-card">
            <div class="res-list-group">
                <span class="res-list-value-text"><?= $res["cliente"] ?></span>
            </div>
            <div class="res-list-group">

                <span class="res-list-value-text res-list-room-number"><?= $res["hab"] ?></span>
            </div>
            <div class="res-list-group">
                <span class="res-list-value-text"><?= $fechaEntradaAux ?> </span>
            </div>
                  <div class="res-list-group">
                <span class="res-list-value-text"><?= $fechaSalidaAux ?></span>
            </div>


            <div class="res-list-group">
                <span class="res-list-status-badge <?= $clasePago ?>"><?= $pago ?></span>
            </div>

            <div class="res-list-group">
                <span class="res-list-status-badge <?= $claseEstadoReservacion ?> "><?= $estadoReservacion ?></span>
            </div>


            <div class="res-list-group">
                <span class="res-list-status-badge ">
                    <form  action="controllers\formularioControllers.php" method="post">
                        <input type="number" value="<?= $res["id"] ?>" name="id_reservacion" hidden>
                        <input type="number" value="<?= $res["idRenta"] ?>" name="id_renta" hidden>
                        <button type="submit" name="submit" value="submit_convertirReservacionIngreso"
                            class="boton-accion-azul">Confirmar Ingreso</button>
                    </form>
                </span>
            </div>
        </div>

    <?php
    }
    ?>





</main>