<?php
$listaPrecios = myDB::obtenerListaPreciosHabitacion();

tools::mostrarVariableConsolaJs($listaPrecios);
?>

<div class="price-up-body">

    <h1 class="price-up-title">Actualizar Tarifas</h1>

    <div class="price-up-card">
        <form action="controllers\formularioControllers.php" method="POST" class="price-up-form">

            <div class="price-up-group">
                <label class="price-up-label">Tipo de Habitación</label>
                <select name="tipo_habitacion" class="price-up-select" required id="tipo_habitacion">
                    <option value="" disabled selected>Seleccione una categoría</option>
                    <?php
                    foreach ($listaPrecios as $precio) {
                    ?>
                         <option value="<?= $precio["id"] ?>" cantidad=<?= $precio["cantidad"] ?>><?= $precio["nombre"] ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>

            <div class="price-up-group">
                <label class="price-up-label">Nuevo Precio Base (USD)</label>
                <div class="price-up-currency-wrapper">
                    <!-- <span class="price-up-currency-symbol">$</span> -->
                    <input id="nuevo_precio" type="number" step="any"  name="nuevo_precio" class="price-up-input price-up-input-currency" placeholder="0.00"  required>
                </div>
            </div>

           
                    <button type="submit" name="submit" value="submit_actualizarPrecio"
                    class="price-up-btn-submit">
                       Actualizar Precio
                    </button>

        </form>
    </div>

</div>


<script>

    const listaPrecios = <?= json_encode($listaPrecios) ?>;
    const tipo_habitacion= document.getElementById("tipo_habitacion");
    const nuevo_precio= document.getElementById("nuevo_precio");


    tipo_habitacion.addEventListener("change", ()=>{

        listaPrecios.forEach(element => {
           if(element.id== tipo_habitacion.value){
             console.log(element.cantidad);
                //   nuevo_precio.setAttribute("value",element.cantidad);
                nuevo_precio.value=element.cantidad;
           }
        });
      
    });




    // console.log(usuarioJS);
</script>