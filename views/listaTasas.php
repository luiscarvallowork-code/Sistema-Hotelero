<?php 
$listaTasas=myDB::obtenerListaTasas();

// tools::mostrarVariableConsolaJs($listaTasas);

?>



<div class="gen-list-container">
        <h1 class="gen-list-title">Registro de Tasas Cambiarias</h1>

        <div class="gen-list-header cols-tasas">
            <span>Tipo de Tasa</span>
            <span>Valor (Bs.)</span>
            <span>Fecha de Registro</span>
        </div>

        <?php 
        foreach($listaTasas as $tasa){
            $fechaAux=new DateTime($tasa["fecha"]);
        ?>
        <div class="gen-list-card cols-tasas">
            <div class="gen-list-group">
        
                <span class="gen-list-value"><?= $tasa["nombre"] ?></span>
            </div>
            <div class="gen-list-group">
        
                <span class="gen-list-value" style="color: #27ae60;"><?= $tasa["tasa"] ?> BS</span>
            </div>
            <div class="gen-list-group">
              
                <span class="gen-list-value"><?= $fechaAux->format("d-m-Y") ?></span>
            </div>
        </div>

        <?php }?>

      

    </div>