<?php
if(isset($_GET["id"])){$id=$_GET["id"];}

if(isset($_GET["idMan"])){
    // $id=myDB::obtenerIdMantenimiento($_GET["id"]);
    $id=$_GET["idMan"];
}
// tools::mostrarVariable($id);
$data=myDB::obtenerDataMantenimiento($id);

$desactivarBoton="";
$claseBoton="maint-status-resolve-btn";
$textoBoton=" Terminar Reparacion";


$fechaFinal=new dateTime($data["fecha_final"]);
if($fechaFinal->format("Y")!="2250")
{
    $desactivarBoton="disabled";
    $claseBoton="maint-status-disabled-btn";
    $textoBoton="Mantenimiento finalizado el ".$fechaFinal->format("d/m/Y");
}

$fecha=new DateTime($data["fecha_inicio"]);


// .

?>


<div class="maint-status-body">

    <div class="maint-status-container">
        <h1 class="maint-status-title">Estado de Reparacion</h1>

        <div class="maint-status-card">
            <div class="maint-status-header">
                <div class="maint-status-number-circle">
                    <span>Hab.</span>
                    <strong><?= $data["nombre"] ?></strong>
                </div>
                <div class="maint-status-info-main">
                    <h2>Habitación  <?= $data["tipo"] ?></h2>
               
                </div>
            </div>

            <div class="maint-status-detail-group">
                <span class="maint-status-label">Fuera de servicio desde</span>
                <span class="maint-status-value">
                    <?= $fecha->format("d")  ?>  de 
                    <?= tools::obtenerMesEspaniol($fecha->format("m")) ?> 
                    del <?= $fecha->format("Y") ?> 
            </span>
            </div>

            <div class="maint-status-detail-group">
                <span class="maint-status-label">Razón de la Reparacion</span>
                <span class="maint-status-value">
                    <?= $data["descripcion"] ?>
                </span>
            </div>

            <form action="controllers\formularioControllers.php" method="POST">
                <input type="hidden" name="id_hab" value="<?= $data["id"] ?>">
                <button  type="submit" <?= $desactivarBoton ?> class="<?= $claseBoton ?>" name="submit" value="submit_terminar_mantenimiento">
                   <?= $textoBoton ?>
                </button>
            </form>
        </div>
    </div>

</div>
