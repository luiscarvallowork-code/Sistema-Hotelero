<?php 



?>



<div class="card card-middle" style="margin-top: 30px;">
    <form action="controllers\formularioControllers.php" method="post" >
        <h3>ESTA SEGURO DE BORRAR PERMANENTEMENTE EL REGISTRO DE RENTA NUMERO  : <?=$_GET["idRenta"]?></h3>

        <input type="number" name="id_rentaHabitacion" id="" value="<?= $_GET["idRenta"]?>" hidden>

        <button type="submit" name="submit" value="submit_borrado_registroRenta" class="btn-primary">Confirmar borrado </button>
        <button type="submit" name="submit" id="boton_cancelar_borrado" value="submit_cancelar_borrado_registroRenta" class="btn-danger">Cancelar</button>
    </form>
</div>

