<?php

if (isset($_GET["fecha"])) {
    $listaIngresos = myDB::obtenerListaIngresosTotales($_GET["fecha"]);

    $fecha = new DateTime($_GET["fecha"]);
} else {
    $fecha = new DateTime();
    $listaIngresos = myDB::obtenerListaIngresosTotales($fecha->format("Y-m-d"));
}

$reservaciones = myDB::obtenerListaReservaciones();

$listaIngresosAux = [];



foreach ($listaIngresos  as $index => $ingreso) {
    $van = true;
    foreach ($reservaciones  as $index2 => $reservacion) {
        if ($ingreso["id"] == $reservacion["idRenta"]) {
            if ($reservacion["estado"] == "1" || $reservacion["estado"] == "0") {
                $van = false;
            }
        }
    }
    if ($van) $listaIngresosAux[] = $ingreso;
}



$listaIngresos=$listaIngresosAux;



$listaIngresosAux=[];
?>

<div class="in-list-container">
    <h1 class="in-list-main-title">Lista ingresos de habitación</h1>

    <div class="in-list-month-picker">
        <button type="button" class="in-list-nav-btn" id="prevMonth" onclick="modificar(-1)">
            <i>&#10094;</i> </button>

        <div class="in-list-current-date">
            <span class="month-text"><?= tools::obtenerMesEspaniol($fecha->format("m")) ?></span>
            <span class="year-text"><?= $fecha->format("Y") ?></span>
        </div>

        <button type="button" class="in-list-nav-btn" id="nextMonth" onclick="modificar(1)">
            <i>&#10095;</i> </button>
    </div>

    <div class="in-list-header-row">
        <span>Num Hab</span>
        <span>Cliente</span>
        <span>Fecha Entrada</span>
        <span>Fecha Salida</span>
        <span>Estado Pago</span>
    </div>

    <?php
    foreach ($listaIngresos as $ingreso) {

        if ($ingreso["estadoPago"] != null) {
            $texto = "Pagado";
            $clase = "in-list-badge-paid";
        } else {
            $texto = "Pendiente";
            $clase = "in-list-badge-pending ";
        }

        $fechaEntrada = new DateTime($ingreso["fechaEntrada"]);
        $fechaEntrada = $fechaEntrada->format("d-m-Y");
        $fechaSalida = new DateTime($ingreso["fechaSalida"]);
        $fechaSalida = $fechaSalida->format("d-m-Y");

    ?>
        <div class="in-list-item-card">
            <div class="in-list-data-group">
                <!-- <span class="in-list-small-label">Habitación</span> -->
                <span class="in-list-room-box"><?= $ingreso["nombre"] ?></span>
            </div>
            <div class="in-list-data-group">
                <!-- <span class="in-list-small-label">Huésped</span> -->
                <span class="in-list-value"><?= $ingreso["cliente"] ?></span>
            </div>
            <div class="in-list-data-group">
                <!-- <span class="in-list-small-label">Entrada</span> -->
                <span class="in-list-value"><?= $fechaEntrada ?></span>
            </div>
            <div class="in-list-data-group">
                <!-- <span class="in-list-small-label">Salida</span> -->
                <span class="in-list-value"><?= $fechaSalida ?></span>
            </div>
            <div>
                <span class="in-list-badge <?= $clase ?>"><?= $texto ?></span>
            </div>
        </div>
    <?php
    }
    ?>


</div>


<script>
    fechaObjetivo = new Date(" <?= $fecha->format("Y/m/d") ?>");



    function modificar(num) {
        fechaObjetivo.setMonth(fechaObjetivo.getMonth() + num);
        fechaTexto = fechaObjetivo.getDate() + "-" + (fechaObjetivo.getMonth() + 1) + "-" + fechaObjetivo.getFullYear();
        // console.log(fechaTexto);
        window.location.href = "controllers/router.php?code=listaIngresosHabitacion&fecha=" + fechaTexto;
    }
</script>