<?php 

$datosClientes=myDB::obtenerDatosClientes($_GET["id"]);
// $datosClientes=$datosClientes[0];
tools::mostrarVariableConsolaJs($datosClientes);

?>


<div class="edit-cli-body">

    <h1 class="edit-cli-title">Editar Datos del Cliente</h1>

    <div class="edit-cli-card">
        <form action="controllers/formularioControllers.php" method="POST" class="edit-cli-form">
            
        <input type="number" name="id" value="<?= $datosClientes["id"] ?>">
            <div class="edit-cli-group">
                <label class="edit-cli-label">Nombre Completo</label>
                <input type="text" name="nombre" class="edit-cli-input"
                 value="<?= $datosClientes["nombre"] ?>">
            </div>

            <div class="edit-cli-group">
                <label class="edit-cli-label">Cédula / ID</label>
                <input type="text" name="cedula" class="edit-cli-input" value="<?= $datosClientes["ci"] ?>">
            </div>

            <div class="edit-cli-group">
                <label class="edit-cli-label">Teléfono</label>
                <input type="tel" name="telefono" class="edit-cli-input" value="<?= $datosClientes["numeroTelefono"] ?>">
            </div>

            <div class="edit-cli-group">
                <label class="edit-cli-label">Ciudad</label>
                <input type="text" name="ciudad" class="edit-cli-input" value="<?= $datosClientes["ciudad"] ?>">
            </div>

            <div class="edit-cli-group">
                <label class="edit-cli-label">Compañía</label>
                <input type="text" name="empresa" class="edit-cli-input" value="<?= $datosClientes["empresa"] ?>">
            </div>

            <div class="edit-cli-actions">
            <button type="submit" name="submit" value="submit_actualizarDatosCliente" class="edit-cli-btn-save">
                    Actualizar Datos</button>
                <a href="controllers/router.php?code=configuraciones" class="edit-cli-btn-cancel">Cancelar</a>
            </div>

        </form>
    </div>

</div>