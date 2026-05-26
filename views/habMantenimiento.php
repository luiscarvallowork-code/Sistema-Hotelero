<?php

if (isset($_GET["error"])) {

$error = 1;

    if ($_GET["error"] == "sinHabitacionCambio") {
        $errorText = "ADVERTENCIA: no existen habitaciones disponible de 
        ese tipo para cambiar a los huespedes";
    }
} else {
    $error = 0;
    $errorText = "";
}


$listaHabitaciones = myDB::obtenerListaDatosHabitacionesDisponiblesMantenimiento();


tools::mostrarVariableConsolaJs($listaHabitaciones);
?>


<div class="maint-form-page-wrapper">
    <h1 class="maint-form-main-title">Ingreso de habitacion para Reparaciones</h1>

    <div class="maint-form-container-card">
        <form method="POST" class="maint-form-element" action="controllers\formularioControllers.php">

            <!-- <div class="maint-form-input-group">
                <label class="maint-form-label" for="maint-date">Fecha de Inicio</label>

            </div> -->
            <input type="date" id="maint-date" name="fechaInicio" class="maint-form-field" hidden>
            <div class="maint-form-input-group">
                <label class="maint-form-label" for="maint-room">Seleccionar Habitación</label>
                <select id="maint-room" name="idHabitacion" class="maint-form-field" required value="null">
                    <!-- -->

                    <?php foreach ($listaHabitaciones as $habitacion) { ?>
                        <option value="<?= $habitacion["id"] ?>" selected><?= $habitacion["nombre"] ?></option>
                    <?php } ?>
                    <option selected value="null" disabled>Elija una habitacion</option>
                </select>
            </div>

            <div class="maint-form-input-group">
                <label class="maint-form-label" for="maint-desc">Descripción del Problema</label>
                <textarea id="maint-desc" name="descripcion" class="maint-form-field maint-form-field-area" placeholder="Ej: Fuga de agua en el baño..."></textarea>
            </div>

               <div class="maint-form-input-group">

               </div>
            <button type="submit" name="submit" value="submit_ingresarHabitacionMantenimiento"
                class="maint-form-submit-btn"> Iniciar Reparacion de  habitación
            </button>
            <!-- <button type="submit" class="maint-form-submit-btn">
                Ingresar habitación en mantenimiento
            </button> -->

        </form>
    </div>

</div>

<script>
    const error = <?= json_encode($error) ?>;
    console.log(error);
    if (error == 1) {
        alert(<?= json_encode($errorText) ?>);
    }
</script>