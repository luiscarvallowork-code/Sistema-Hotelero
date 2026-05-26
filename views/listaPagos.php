<?php
$listaPagos = myDB::obtenerListaDatosPagos();
$moneda = null;
if (isset($_GET["moneda"])) {
    $moneda = $_GET["moneda"];
}


tools::mostrarVariableConsolaJs($listaPagos);
// tools::mostrarVariableConsolaJs($moneda);
$url = "";
$classAux = "";
?>

<div>

    <div class="gen-list-container">
        <h1 class="gen-list-title">Historial de Pagos</h1>

        <div class="container-boton-monedas">

            <?php
            // diseñar despues paraque muestre todos los botones de las posibles monedas 
            if ($moneda != "BS") {
                $url = "controllers/router.php?code=listaPagos&moneda=BS";
                $classAux = "cambio-moneda-btn";
            } else {
                $url = "";
                $classAux = "cambio-moneda-btn cambio-moneda-desactivado-btn";
            }
            ?>

            <a href="<?= $url ?>" class="<?= $classAux ?>">Bs</a>


            <?php
            // diseñar despues paraque muestre todos los botones de las posibles monedas 
            if ($moneda != "USD") {
                $url = "controllers/router.php?code=listaPagos&moneda=USD";
                $classAux = "cambio-moneda-btn";
            } else {
                $url = "";
                $classAux = "cambio-moneda-btn cambio-moneda-desactivado-btn";
            }
            ?>

            <a href="<?= $url ?>" class="<?= $classAux ?>">USD</a>




        </div>

        <div class="gen-list-header cols-pagos">
            <span>Huesped</span>
            <span>Fecha de pago</span>
            <span>Monto</span>
            <span>T. Pago</span>
            <span>Tasa</span>
            <span>Referencia</span>
     
            <span>Habitación</span>
        </div>

        <?php
        foreach ($listaPagos as $pago) {
            $fecha = new DateTime($pago["fecha"]);
            $fechaTasa = new DateTime($pago["fechaTasa"]);

            if ($moneda == null || $pago["codigo"] == $moneda) {
        ?>

                <div class="gen-list-card cols-pagos">
                    <div class="gen-list-group">

                        <span class="gen-list-value"><?= $pago["cliente"]  ?></span>
                    </div>

                    <div class="gen-list-group">

                        <span class="gen-list-value"><?= $fecha->format("d/m/Y")  ?></span>
                    </div>
                    <div class="gen-list-group">

                        <span class="gen-list-value"><?= $pago["cantidad"]  ?> <?= $pago["codigo"] ?></span>
                    </div>

                    <div class="gen-list-group">

                        <span class="gen-list-value"> <?= $pago["tipo"] ?></span>
                    </div>


                    <div class="gen-list-group">
                        <span class="gen-list-label">Tasa BCV <?= $fechaTasa->format("d/m/Y") ?></span>
                        <span class="gen-list-value"><?= $pago["tasa"]  ?> BS</span>
                    </div>
                    <div class="gen-list-group">

                        <span class="gen-list-value"><?= $pago["referencia"]  ?></span>
                    </div>
                   
                    <div class="gen-list-group">

                        <span class="gen-list-value" style="color: #3498db; font-weight: 800;">
                            <?= $pago["nombre"]  ?>
                        </span>
                    </div>
                </div>

        <?php
            }
        }
        ?>

    </div>