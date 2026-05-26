<?php 


 $listaHabitaciones=myDB::obtenerListaDatosHabitaciones();

//  tools::mostrarVariableConsolaJs($listaHabitaciones);
?>


    <div class="gen-list-container">
        <h1 class="gen-list-title">Lista de Habitaciones</h1>

        <div class="gen-list-header cols-habitaciones">
            <span>Nombre / Nro</span>
            <span>Tipo de Habitación</span>
            <span>Precio (USD)</span>
            <span>Última Reserva</span>
            <span></span>
        </div>


        <?php 
        foreach($listaHabitaciones as $data){ 
           if($data["ultimaFechaEntrada"]){
                $fecha=new DateTime($data["ultimaFechaEntrada"]);
                $fecha=$fecha->format("d-m-Y");
           }
           else{
            $fecha="Sin Ingresos";
           }
        ?>

        <div class="gen-list-card cols-habitaciones">
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Nro</span> -->
                <span class="gen-list-value"><?= $data["nombre"] ?></span>
            </div>
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Categoría</span> -->
                <span class="gen-list-value"><?= $data["tipo"] ?></span>
            </div>
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Precio</span> -->
                <span class="gen-list-value"><?= $data["cantidad"] ?></span>
            </div>
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Fecha</span> -->
                <span class="gen-list-value"><?= $fecha ?></span>
            </div>

            <!-- proximamente la funcion para modificar habitaciones -->
            <a href="controllers/router.php?code=actualizarDatosHabitaciones&id=<?= $data["id"] ?>" class="gen-list-btn">Actualizar</a>
        </div>

       <?php 
       }
       ?>

    </div>
