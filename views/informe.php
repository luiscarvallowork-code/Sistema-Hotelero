<?php

if (isset($_GET["fecha"])) {
    $listaIngresos = myDB::obtenerListaIngresosTotales($_GET["fecha"]);
    $fecha = new DateTime($_GET["fecha"]);
} else {
    $fecha = new DateTime();
    $listaIngresos = myDB::obtenerListaIngresosTotales($fecha->format("Y-m-d"));
}

$listaReservaciones = myDB::obtenerListaReservaciones();

$listaIngresosFinal = [];
$listaReservacionesFinal = [];
$listaPagosFinal = [];

foreach ($listaReservaciones as $index => $item) {
    $fechaEntrada = new  DateTime($item["fechaEntrada"]);
    $fechaSalida = new  DateTime($item["fechaSalida"]);

    if ($fecha >=  $fechaEntrada && $fecha < $fechaSalida  && $item["estado"] != "2") {
        $listaReservacionesFinal[] = $item;
    }
}
foreach ($listaIngresos as $index => $item) {
    $fechaEntrada = new  DateTime($item["fechaEntrada"]);
    $fechaSalida = new  DateTime($item["fechaSalida"]);

    if ($fecha >=  $fechaEntrada && $fecha < $fechaSalida) {
        $van = true;
        foreach ($listaReservaciones as $index => $reservacion) {
            if ($reservacion["idRenta"] == $item["id"]) {
                if ($reservacion["estado"] != "2") {
                    $van = false;
                }
            };
        }
        if ($van) $listaIngresosFinal[] = $item;
    }
}


$listaPagos = myDB::obtenerListaDatosPagos();
foreach ($listaPagos as $index => $item) {
    $fechaPago = new  DateTime($item["fecha"]);

    if ($fecha->format("Y-m-d") ==  $fechaPago->format("Y-m-d")) {
        $listaPagosFinal[] = $item;
    }
}


?>


<div class="it-container">
    <header class="it-header">
        <h1>INFORME DE TURNO - <?= $fecha->format("d/m/Y") ?> </h1>
    </header>
    <div class="contenedor-fecha" id="contenedor-fecha">
        <button type="button" id="button_anterior_dia" class="boton-fecha" onclick="cambiarFecha(-1)">
            &lt;
        </button>
        <!-- <input id="fechaSeleccion" type="date" value="<?= $fecha->format("Y/m/d") ?>" readonly> -->
        <input id="fechaSeleccion" type="date" value="<?= $fecha->format("Y-m-d") ?>" readonly>
        <button type="button" id="button_siguiente_dia" class="boton-fecha" onclick="cambiarFecha(1)">
            &gt;
        </button>
    </div>

    <form class="it-form-body">

        <div class="it-section-full it-secction-name">
            <label class="it-label">NOMBRE DEL RECEPCIONISTA:</label>
            <input type="text" class="it-input" placeholder="Escribe el nombre aquí...">
        </div>

        <div class="it-grid-row">
            <div class="it-status-container">
                <div class="it-table-header">HABITACIONES OCUPADAS</div>
                <div class="it-rooms-box">

                    <?php foreach ($listaIngresosFinal as $ocupada) {  ?>
                        <div class="it-room-rect cuaddroHabitacion cuaddroHabitacionOcupacion">
                            <?= $ocupada["nombre"] ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="it-status-container">
                <div class="it-table-header">HABITACIONES RESERVADAS</div>
                <div class="it-rooms-box">

                    <?php foreach ($listaReservacionesFinal as $reservada) {  ?>
                        <div class="it-room-rect cuaddroHabitacion cuaddroHabitacionRervado">
                            <?= $reservada["hab"] ?>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>




        <div class="it-section-full">
            <div class="it-table-header">DETALLE DE PAGOS</div>
            <table class="it-table">
                <thead>
                    <tr>
                        <th>HAB</th>
                        <th>HUESPED</th>
                        <th>CANTIDAD</th>
                        <th>TIPO</th>
                        <th>REF.</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($listaPagosFinal as $pago) {  ?>
                        <tr>
                            <td><?= $pago["nombre"] ?> </td>
                            <td><?= $pago["cliente"] ?> </td>
                            <td class="it-container-cantidad-divisa"><?= $pago["cantidad"] ?> <?= $pago["codigo"] ?></td>

                            <td><?= $pago["tipo"] ?></td>
                            <td><?= $pago["referencia"] ?></td>
                        </tr>
                    <?php } ?>


                </tbody>
            </table>
        </div>

        <div class="it-grid-row">
            <div class="it-table-container">
                <div class="it-table-header">
                    <span>FACTURAS RECIBIDAS</span>
                    <button type="button" class="it-btn-inline" onclick="agregarFilaFactura()">+</button>
                </div>
                <table class="it-table" id="it-facturas-table">
                    <thead>
                        <tr>
                            <th>RAZON SOCIAL</th>
                            <th>MONTO</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                            <td><input type="text" class="it-input-table" placeholder="..."></td>
                            <td><input type="text" class="it-input-table" placeholder="0.00 BS"></td>
                            <td><input type="text" class="it-input-table" placeholder="DD/MM"></td>
                        </tr> -->
                    </tbody>
                </table>
            </div>

            <div class="it-table-container">
                <div class="it-table-header">
                    <span>SALIDAS DE INVENTARIO</span>
                    <button type="button" class="it-btn-inline" onclick="agregarFilaInventario()">+</button>
                </div>
                <table class="it-table" id="it-inventory-table">
                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>CANTIDAD</th>
                            <th>NOTA</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="it-section-full">
            <label class="it-label">INCIDENCIAS DEL DÍA:</label>
            <textarea id="it-incidencias-input" class="it-textarea" placeholder="Escribe aquí las incidencias..."></textarea>
            <div id="it-incidencias-espejo" class="it-textarea-print" style="display: none;"></div>
        </div>

        <div class="it-footer">
            <button type="button" onclick="descargarPDF()" class="it-btn-submit">GENERAR INFORME</button>
        </div>
    </form>
</div>



<script src="resources\librerias\html2pdf.js-main\html2pdf.js-main\dist\html2pdf.bundle.min.js"></script>

<script>
    function ejecutarCapturaPDF() {
        const elemento = document.querySelector(".it-container"); // El contenedor que creamos
        const fecha = "<?= $fecha->format("d-m-Y") ?>"

        const opciones = {
            margin: 10,
            filename: 'Informe_Turno ' + fecha + '.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2,
                logging: false,
                width: 800, // Forzamos el ancho de captura
                useCORS: true
            },
            pagebreak: {
                mode: ['avoid-all', 'css', 'legacy']
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait'
            }
        };
        return html2pdf().set(opciones).from(elemento).save();
    }

    async function descargarPDF() {

        const styleTag = document.createElement('style');
        styleTag.id = "estilo-temporal-pdf";

        // ESTILOS TEMPORALES PARA EL PDF
        styleTag.innerHTML = `
            .it-room-rect{color: black !important; }
            .nav-sys-container, 
            .it-footer, .contenedor-fecha{display: none !important;}
            .it-table-header, .it-rooms-box, .it-table tr, .it-table th,.it-room-rect   {
                background-color: #ffffff !important;}
            .it-btn-inline{display: none !important;}
            .it-room-rect {font-size: 15px !important; width: 50px !important;height:20px !important;}
            .it-rooms-box {border: none !important;  padding: 2px !important; min-height: none !important;}
            .it-container {padding: 0px !important;}
            .it-section-full{    margin-bottom: 10px !important; margin-top: 10px !important;}
            .it-textarea-print { display: block !important; border: 1px solid #ccc !important; }
            .it-container {display: block !important;}
      
              `;


        document.head.appendChild(styleTag);



        const textarea = document.getElementById('it-incidencias-input');
        const espejo = document.getElementById('it-incidencias-espejo');
        espejo.innerText = textarea.value;
        textarea.style.display = 'none';
        espejo.style.display = 'block';

        try {
            await ejecutarCapturaPDF();

        } catch (error) {
            console.error("Error al generar el PDF:", error);
        } finally {
            // 3. Borramos los estilos (esto solo pasará DESPUÉS de que termine el PDF)

            textarea.style.display = 'block';
            espejo.style.display = 'none';

            const temporal = document.getElementById("estilo-temporal-pdf");

        }

        document.getElementById("estilo-temporal-pdf").remove();
    }

    function agregarFilaFactura() {
        const tabla = document.getElementById("it-facturas-table").getElementsByTagName('tbody')[0];
        const nuevaFila = tabla.insertRow();
        nuevaFila.innerHTML = `
        <td><input type="text" class="it-input-table" placeholder="..."></td>
        <td><input type="text" class="it-input-table" placeholder="0.00 BS"></td>
        <td><input type="text" class="it-input-table" placeholder="01/01/1999"></td>
    `;
    }

    function agregarFilaInventario() {
        const tabla = document.getElementById("it-inventory-table").getElementsByTagName('tbody')[0];
        const nuevaFila = tabla.insertRow();
        nuevaFila.innerHTML = `
        <td><input type="text" class="it-input-table" placeholder="..."></td>
        <td><input type="number" class="it-input-table" placeholder="0"></td>
        <td><input type="text" class="it-input-table" placeholder="..."></td>
    `;
    }

    const fechaObjetivo = new Date(" <?= $fecha->format("Y/m/d") ?>");

    function cambiarFecha(num) {
        fechaObjetivo.getDate(fechaObjetivo.getDate() + num);
        const otroDia = new Date(fechaObjetivo);
        otroDia.setDate(fechaObjetivo.getDate() + num);

        fechaTexto = otroDia.getDate() + "-" + (otroDia.getMonth() + 1) + "-" + otroDia.getFullYear();
        // console.log(fechaTexto);
        window.location.href = "controllers/router.php?code=informe&fecha=" + fechaTexto;

    }
</script>