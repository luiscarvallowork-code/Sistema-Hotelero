<?php


$listaMamtenimiento = myDB::obtenerListaMantenimiento();
// tools::mostrarVariableConsolaJs($listaMamtenimiento);
?>

<div class="gen-list-container">
    <h1 class="gen-list-title">Habitaciones en Mantenimiento</h1>

    <div class="gen-list-header cols-mantenimiento">
        <span>Num Hab</span>
        <span>Resumen del Problema</span>
        <span>Fecha de Inicio</span>
        <span> </span>
    </div>

    <?php foreach ($listaMamtenimiento as $mantenimiento) { 
        $fecha=new DateTime( $mantenimiento["fecha_inicio"]);
        $fecha=$fecha->format("d/m/Y");
        $fechaFinal=new DateTime( $mantenimiento["fecha_final"]);
       if($fechaFinal->format("Y")=="2250"){
        $textoEstado="En Proceso";
       }
       else{
        $textoEstado="Completado";
       }
    

       ?>

        <div class="gen-list-card cols-mantenimiento">
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Habitación</span> -->
                <span class="gen-list-value" style="color: #e67e22; font-weight: 800;"><?= $mantenimiento["nombre"] ?></span>
            </div>
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Detalle</span> -->
                <span class="gen-list-value" style="white-space: normal;"><?= $mantenimiento["descripcion"] ?></span>
            </div>
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Ingreso</span> -->
                <span class="gen-list-value"><?=$fecha ?></span>
            </div>

            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Estado</span> -->
                <span class="gen-list-value"><?=$textoEstado ?></span>
            </div>

            <div class="gen-list-group">
                <a href="controllers/router.php?code=estadoMantenimientoHabitacion&id=<?= $mantenimiento["id"] ?>"
                class="gen-list-btn">ver informacion detallada</a>
            </div>
        </div>



    <?php } ?>


</div>