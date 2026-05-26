<?php 
$listaPosiciones = myDB::obtenerPosicionesHabitaciones(2);
$listaTiposHabitaciones=myDB::obtenerTiposHabitaciones();
// $listaPisos=myDB::obtenerListaPisos();
tools::mostrarVariableConsolaJs($listaTiposHabitaciones);

$piso=1;
?>

<div class="setup-container">
    <header style="margin-bottom: 25px;">
        <h1 class="in-list-main-title" style="text-align: left;">Configuración de Habitación</h1>
        <p style="color: var(--text-muted);">Define el nombre, tipo y ubicación física en el mapa.</p>
    </header>

    <div class="config-card">
        <form action="controllers/formularioControllers.php" method="POST" onsubmit="return comprobarFomulario()">

            <div class="form-row">
                <div class="field-group">
                    <label for="nombre">Nombre / Número</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: 101" required>
                </div>

                <div class="field-group">
                    <label for="tipo">Tipo de Habitación</label>
                    <select id="tipo" name="tipo">

                    <?php   foreach($listaTiposHabitaciones as $tipo){ ?>
                        <option value=<?= $tipo["id"] ?>><?= $tipo["nombre"] ?></option>
                    <?php } ?>
                       
                    </select>
                </div>

         
            </div>

            <span class="label grid-label">Selecciona la posición de la habitacion</span>
               <table class="listaPosicionesHabitaciones" id="listaPosicionesHabitaciones">
            <?php
            $classExtra;
            $claseFondo;
            $texto;
            $id;
            $posX = null;
            $posY = null;
            $tooltips = "";
            for ($i = 1; $i <= 8; $i++) {

            ?>
            <tr>
            <?php
            
                for ($j = 1; $j <= 8; $j++) {

                    $classExtra = "grid-slot-select ";
                    $texto = " ";
                    $tooltips = "";
                    $claseFondo = "fondoCuaddroHabitacion";
                    $tipo = "";

                
                    foreach ($listaPosiciones as $element) {
                        $posX = $element["posicion_x"] + 0;
                        $posY = $element["posicion_y"] + 0;
                        if ($posX == $i && $posY == $j) {
                            $classExtra = "cuaddroHabitacionDesocupado";
                            $texto = $element["nombre"];
                            $claseFondo = "";
                            $tooltips = "Establece la fecha del sistema";
                            $tipo = $element["tipo"];
                        }
                    }

                    $id = $i . "" . $j;
                    echo '
                        <td class="containerCuadroHabitacion ' . $claseFondo . '">
                            <a 
                         
        
                            class="contenedor-cuadro "> <div 
                                class="cuaddroHabitacion grid-slot  ' . $classExtra . '" id="'.$id.'">'
                        . $texto .
                        '</div>
                            </a>
                        </td>
                        ';
                }

                echo "</tr>";
            }
            ?>

        </table>
      

           <input type="number" name="positionX" id="positionX" value=0 hidden>
            <input type="number" name="positionY" id="positionY" value=0 hidden>
            <input type="number" name="piso" id="piso" value=<?= $piso ?> hidden>

             <button type="submit" name="submit" value="submit_registrarNuevaHabitacion" class="btn-save">
                    Registrar Habitación
            </button>
           
        </form>
    </div>
</div>

<script>
    const slots = document.querySelectorAll('.grid-slot-select');
    // const inputPos = document.getElementById('selectedPosition');

    const positionX=document.getElementById("positionX");
    const positionY=document.getElementById("positionY");

  

    slots.forEach(slot => {
        slot.addEventListener('click', () => {
            // Quitar clase selected de todos
            slots.forEach(s => s.classList.remove('selected'));
            // Añadir a este
            slot.classList.add('selected');



                
             positionX.setAttribute("value", Math.floor(slot.id/10));
             positionY.setAttribute("value", (slot.id%10).toFixed(0));

             console.log(Math.floor(slot.id/10));
            //  console.log(positionY.value);
        });
        
    });
    function comprobarFomulario(){

    if(positionX.value!=0 && positionY.value!=0)return true;
    else return false;
    }
</script>