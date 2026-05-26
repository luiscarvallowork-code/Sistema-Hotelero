<?php
$listaPosiciones = myDB::obtenerPosicionesHabitaciones(2);

// $listaPisos=myDB::obtenerListaPisos();

// tools::mostrarVariableConsolaJs($listaPisos);



?>


<script>
    const listaPosiciones = <?php echo json_encode($listaPosiciones) ?>
</script>

<div class="vista-estado-habitaciones">

    <div class="contenedor-principal">
        <a class="gen-list-btn boton-flotante-estado-habitacion" href="controllers/router.php?code=estadoSemanal">Estado Semanal</a>
        <h1>ESTADO DE HABITACIONES</h1>

        <div class="contenedor-fecha">
            <button type="button" id="button_anterior_dia" class="boton-fecha">
                &lt; 
            </button>
            <input id="fechaSeleccion" type="date" value="" readonly>
            <button type="button" id="button_siguiente_dia" class="boton-fecha">
              &gt;
            </button>
        </div>

        <div class="leyenda-colores">
       
            <div class="leyenda-item leyenda-reserva">
                <span class="leyenda-circulo"></span>
                <p>Reservado</p>
                <span class="etiquetaReserva etiqueta-conteo" name="etiquetaReserva">11</span>
            </div>

            <div class="leyenda-item leyenda-ocupada">
                <span class="leyenda-circulo"></span>
                 <p>Ocupado </p>
                <span class="etiquetaOcupada etiqueta-conteo" name="etiquetaOcupada">11</span>
            </div>

            <div class="leyenda-item leyenda-desocupada">
                <span class="leyenda-circulo"></span>
              <p>   Desocupado </p>
                <span class="etiquetaDesocupada etiqueta-conteo" name="etiquetaDesocupada">11</span>
            </div>

            <div class="leyenda-item leyenda-mantenimiento">
                <span class="leyenda-circulo"></span>
              <p>Reparacion</p>
                <span class="etiquetaMantenimiento etiqueta-conteo" name="etiquetaMantenimiento">11</span>
            </div>
        </div>

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

                echo "<tr>";


                for ($j = 1; $j <= 8; $j++) {

                    $classExtra = " ";
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
                        <td class="containerCuadroHabitacion' . $claseFondo . '">
                            <a 
                         
        
                            class="contenedor-cuadro"> <div 
                                data-tipoHabitacion="' . $tipo . '"
                                data-cliente=" "
                                data-fecha=" "

                                data-tooltip="' . $tooltips . '" 
                                id="' . $texto . '" 
                                class="cuaddroHabitacion ' . $classExtra . '">'
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
    </div>
</div>


<script src="resources\js\respuestasServidor.js"></script>
<script src="resources\js\estadoHabitacion.js"></script>
