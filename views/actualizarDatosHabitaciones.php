<?php
$listaPosiciones = myDB::obtenerPosicionesHabitaciones(2);
$listaTiposHabitaciones = myDB::obtenerTiposHabitaciones();
$datosHabitacionActual = myDB::obtenerDatosHabitacion($_GET["id"]);
// $listaPisos=myDB::obtenerListaPisos();
tools::mostrarVariableConsolaJs($datosHabitacionActual);

$positionX = null;
$positionY = null;

$piso = 1;
?>

<div class="setup-container">
    <div class="container_in-list-main-title">
        <h1 class="in-list-main-title">Configuración de Habitación</h1>
    </div>

    <div class="config-card">
        <form action="controllers/formularioControllers.php" method="POST" onsubmit="return comprobarFomulario()">

            <div class="form-row">
                <div class="field-group">
                    <label for="nombre">Nombre / Número</label>
                    <input type="text" id="nombre" name="nombre" value="<?= $datosHabitacionActual["nombre"] ?>" required>
                </div>

                <div class="field-group">
                    <label for="tipo">Tipo de Habitación</label>
                    <select id="tipo" name="tipo">

                        <?php foreach ($listaTiposHabitaciones as $tipo) { ?>
                            <option value=<?= $tipo["id"] ?>><?= $tipo["nombre"] ?></option>
                        <?php } ?>
                    </select>
                </div>


            </div>

            <span class="label grid-label">Selecciona la posición  de la habitacion</span>
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

                        $selecionado = "";


                        foreach ($listaPosiciones as $element) {
                            $posX = $element["posicion_x"] + 0;
                            $posY = $element["posicion_y"] + 0;
                            if ($posX == $i && $posY == $j) {
                                $classExtra = "cuaddroHabitacionDesocupado";

                                $claseFondo = "";
                                $tooltips = "Establece la fecha del sistema";
                                $tipo = $element["tipo"];
                                $texto = $element["nombre"];
                                if ($texto == $datosHabitacionActual["nombre"]) {


                                    $positionX = $posX;
                                    $positionY = $posY;
                                    $selecionado = "selected";
                                    $classExtra = "grid-slot-select ";
                                }
                            }
                        }

                        $id = $i . "" . $j;
                        echo '
                        <td class="containerCuadroHabitacion ' . $claseFondo . '">
                            <a 
                         
        
                            class="contenedor-cuadro "> <div 
                                class="cuaddroHabitacion grid-slot ' . $selecionado . '  ' . $classExtra . '" id="' . $id . '">'
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
            <input type="number" name="id" id="id" value=<?= $datosHabitacionActual["id"] ?> hidden>

            <button type="submit" name="submit" value="submit_actualizarDatosHabitacion" class="btn-save">
                Registrar Habitación
            </button>

        </form>
    </div>
</div>

<script>
    const slots = document.querySelectorAll('.grid-slot-select');
    // const inputPos = document.getElementById('selectedPosition');

    const positionX = document.getElementById("positionX");
    const positionY = document.getElementById("positionY");

    positionX.setAttribute("value", <?= json_encode($positionX) ?>);
    positionY.setAttribute("value", <?= json_encode($positionY) ?>);




    slots.forEach(slot => {
        slot.addEventListener('click', () => {
            // Quitar clase selected de todos
            slots.forEach(s => s.classList.remove('selected'));
            // Añadir a este
            slot.classList.add('selected');




            positionX.setAttribute("value", Math.floor(slot.id / 10));
            positionY.setAttribute("value", (slot.id % 10).toFixed(0));

            console.log(Math.floor(slot.id / 10));
            //  console.log(positionY.value);
        });

    });

    function comprobarFomulario() {

        if (positionX.value != 0 && positionY.value != 0) return true;
        else return false;
    }
</script>