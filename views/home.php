<?php
$fechaActual = new DateTime();
$mesActual = intval($fechaActual->format("m"));
$data = myDB::obtenerDatosDashboar();
// tools::mostrarVariableConsolaJs($data);



?>


<main class="main-container">
    <!-- <header style="margin-bottom: 30px; text-align: center;">
        <h1 style="letter-spacing: 1px; color: #555;">RESUMEN GENERAL</h1>
    </header> -->

    <div class="dashboard-grid">
        <div class="card" style="border-left: 4px solid var(--success);">
            <span class="label">Tasa de cambio (BCV) | fecha: <?= $data["tasaFecha"] ?></span>
            <div class="value"><?= $data["tasa"] ?> Bs</div>
            <div class="input-group">

                <form id="formulario" action="controllers\formularioControllers.php" method="post" style="display:none">
                    <input type="number" placeholder="0.00" id="new-rate" step="any" name="cantidad" value=0>
                    <button id="botonConfirmarEnvio" type="submit" class="btn-primary" name="submit" value="actualizarTasaBcv">
                        confirmar
                    </button>
                </form>

                <button id="botonActualizar" class="btn-primary" onclick="cambiarVisibilidad()">Actualizar</button>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid var(--success);">
            <span class="label">Habitaciones Ocupadas</span>
            <div class="value"><?= $data["NumHabOcupadas"] ?> <span style="font-size: 1rem; color: var(--text-muted);">/ <?= $data["NumHabTotales"] ?></span></div>
            <div style="height: 8px; background: #eee; border-radius: 4px; margin-top: 15px;">
                <div style="width:  <?= ($data["NumHabOcupadas"] * 100) / $data["NumHabTotales"] ?>%; height: 100%; background: var(--success); border-radius: 4px;"></div>

            </div>
        </div>

        <div class="card" style="border-left: 4px solid var(--success);">
            <span class="label">Facturado este Mes (Est.)</span>
            <div class="value" style="color: var(--success);"><?= $data["facturacionTotal"] ?> USD</div>
            <!-- <span class="label" style="font-size: 0.7rem;">+12% respecto al mes anterior</span> -->
        </div>
    </div>

    <div style="display: flex; justify-content: space-between;margin-top: 15px;">
        <div class="card card_lista_precios ">
            <div style="margin-bottom: 20px;">
                <h3 style="margin: 0;">Tarifas por Categoría</h3>
                <p style="font-size: 0.8rem; color: #888; margin: 5px 0 0 0;">Precios base configurados para la venta directa</p>
            </div>
            <div class="home-prices-grid">
                <?php
                foreach ($data["listaPrecios"] as $registro) {

                    $cantidaCamas = $registro["nombre"];

                    if ($cantidaCamas == "Matrimonial") $cantidaCamas = "1 cama matrimonial";
                    else if ($cantidaCamas == "Doble") $cantidaCamas = "2 camas matrimonial";
                    else if ($cantidaCamas == "Triple") $cantidaCamas = "3 camas individuales";
                    else if ($cantidaCamas == "Suit") $cantidaCamas = "1 cama matrimonial";
                    else if ($cantidaCamas == "ApartaHotel") $cantidaCamas = "1 cama matrimonial";
                    if ($cantidaCamas == "Sala de Conferencia") $cantidaCamas = "Mesa y sillas para conferencias";
                ?>

                    <div class="home-prices-item">
                        <div class="home-prices-info">
                            <span class="home-prices-type"><?= $registro["nombre"] ?></span>
                            <span class="home-prices-desc"><?= $cantidaCamas ?></span>
                        </div>
                        <div class="home-prices-value"> <?= $registro["cantidad"] ?> USD</div>

                    </div>
                <?php
                }
                ?>

            </div>
        </div>

        <div class="card card_lista_reservaciones" id="cartaReservaciones">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0;">Reservaciones Recientes</h3>
         
            </div>

            <table class="table-container">
                <thead>

                    <tr>
                        <th>Hab.</th>
                        <th>Nombre del Cliente</th>
                        <th>Fecha de Reservacion</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data["reservacionesRecientes"] as $registro) {
                        $fecha=new DateTime( $registro["fechaEntrada"]);
                        $textoEmpresa = "";
                        if ($registro["empresa"] != "") {
                            $textoEmpresa = "(" . $registro["empresa"] . ")";
                        }

                        if ($registro["estado"] == 1) {
                            $colorBotonReservacion = "bg-warning";
                            $textoRes = "Activa";
                        } else if( ($registro["estado"] == 0) ){
                            $colorBotonReservacion = "bg-danger";
                            $textoRes = "Caducada";
                        }
                        
                        else{
                            $colorBotonReservacion = "bg-success";
                            $textoRes = "Completada";
                        }




                    ?>
                        <tr>
                            <td><strong><?= $registro["hab"] ?></strong></td>
                            <td><?= $registro["cliente"] ?> <?= $textoEmpresa ?></td>
                            <td><?= $fecha->format("d/m/Y")?></td>
                            <td>
                                <span class="badge <?= $colorBotonReservacion ?>"><?= $textoRes ?></span>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</main>



<script>
    const botonActualizar = document.getElementById("botonActualizar");
    const formulario = document.getElementById("formulario");

    function cambiarVisibilidad() {
        if (botonActualizar.textContent != "Cancelar") {
            botonActualizar.textContent = "Cancelar";
            botonActualizar.classList.remove("btn-primary");
            botonActualizar.classList.add("btn-danger")

            formulario.style.display = "block";
        } else {
            botonActualizar.textContent = "Cancelar";
            botonActualizar.textContent = "Actualizar";
            botonActualizar.classList.remove("btn-danger");
            botonActualizar.classList.add("btn-primary")
            formulario.style.display = "none";
        }
    }
</script>