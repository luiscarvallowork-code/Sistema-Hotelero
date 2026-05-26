        <?php

                    // if ($datosRentaHabitacion["id_pago"] == null) {
                    if ($datosRentaHabitacion["id_pago"] != null) {
                    ?>

                        <input type="number" name="id"
                            value=<?= $datosRentaHabitacion["id_pago"] ?> hidden>
                        <input type="number" name="id_rentaHabitacion"
                            value=<?= $datosRentaHabitacion["id"] ?> hidden>

                        <div style="display: flex;">
                            <p class="info-hab-text-paid">PAGADO</p>
                        </div>
                        <div class="info-hab-input-item">
                            <label class="info-hab-card-label">TIPO de PAGO:</label>
                            <input id="pago_tipo" type="text" name="pago_tipo" class="info-hab-input-readonly"
                                value="<?= $datosPago["nombre"] ?>"

                                readonly>
                            <select name="pago_tipo_seleccion" id="pago_tipo_seleccion" hidden>

                                <?php
                                foreach ($opcionesPago as $opcion) {
                                    echo '<option value="' . $opcion["id"] . '">' . $opcion["nombre"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="info-hab-input-item">
                            <label class="info-hab-card-label">Cantidad:</label>



                            <input id="pago_cantidad" type="number" name="pago_cantidad" step="any"
                                class="info-hab-input-readonly" readonly
                                value=<?= $datosPago["cantidad"] ?>>
                        </div>

                        <div class="info-hab-input-item">
                            <label class="info-hab-card-label">REFERENCIA:</label>
                            <input id="referenciaPago" type="text" name="referenciaPago"
                                class="info-hab-input-readonly" readonly
                                value=<?= $datosPago["referencia"] ?>>
                        </div>

                        <button type="button" class="info-hab-btn-edit" id="boton_activar_edicion_pago">Editar Pago</button>
                        <button type="submit" name="submit" class="info-hab-btn-edit" id="boton_editar_pago" value="submit_renta_actualizarDatosPago" hidden>Actualizar Datos</button>

                    <?php } else {


                    ?>

                        <input type="number" name="id_rentaHabitacion"
                            value=<?= $datosRentaHabitacion["id"] ?> hidden>

                        <div style="display: flex;">
                            <p class="info-hab-text-nopaid">PENDIENTE</p>
                        </div>
                        <div class="info-hab-input-item">
                            <label class="info-hab-card-label">TIPO de PAGO:</label>
                            <input id="pago_tipo" hidden type="text" name="pago_tipo" class="info-hab-input-readonly">
                            <button id="boton_activar_edicion_pago" hidden></button>
                            <select name="pago_tipo_seleccion" id="pago_tipo_seleccion">

                                <?php
                                foreach ($opcionesPago as $opcion) {
                                    echo '<option value="' . $opcion["id"] . '">' . $opcion["nombre"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="info-hab-input-item">
                            <label class="info-hab-card-label">Cantidad:</label>
                            <input id="pago_cantidad" step="any" type="number" name="pago_cantidad" class="info-hab-input-readonly">
                        </div>

                        <div class="info-hab-input-item">
                            <label class="info-hab-card-label">REFERENCIA:</label>
                            <input id="referenciaPago" type="text" name="referenciaPago"
                                class="info-hab-input-readonly">
                        </div>




                        <button type="submit" name="submit" class="info-hab-btn-edit" id="boton_enviar_pago" value="submit_renta_registrarPago">
                            Registrar Pago
                        </button>

                    <?php } ?>
