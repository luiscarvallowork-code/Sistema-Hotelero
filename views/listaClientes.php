<?php
$numeroRegistrosMostrados = 10;
$num = 0;
$totalClientes = myDB::obtenerNumeroClientes();
$idInicio = $totalClientes;
$banIzq = false;
$banDerecha = true;
if (isset($_GET["num"])) {
    $banIzq = true;
    $num = $_GET["num"];
    $idInicio = $totalClientes + ($numeroRegistrosMostrados * $num);
    if ($idInicio <= 0) {
        $idInicio = $numeroRegistrosMostrados;
        $banDerecha = false;
        $num++;
    } else if ($idInicio > $totalClientes) {
        $idInicio = $totalClientes;
        $banIzq = false;
        $num--;
    }
    $listaClientes = myDB::obtenerListaClientes($idInicio, $numeroRegistrosMostrados);
} elseif (isset($_GET["textoBusqueda"])) {
    $numeroRegistrosMostrados = 999999;
    $banIzq = false;
    $banDerecha = false;
    $listaClientes = myDB::obtenerListaClientes($totalClientes, $numeroRegistrosMostrados, $_GET["textoBusqueda"]);
} else {
    $listaClientes = myDB::obtenerListaClientes($totalClientes, $numeroRegistrosMostrados);
}


// tools::mostrarVariableConsolaJs($listaClientes);

?>

<div class="gen-list-container">
    <h1 class="gen-list-title">Lista de Clientes</h1>

    <div class="in-list-month-picker">

        <?php if ($banIzq) { ?>
            <button type="button" class="in-list-nav-btn" id="registrosAnteriores"

                onclick="modificar(1)"

                <i>&#10094;</i>
            </button>
        <?php } ?>

        <div class="in-list-false-space">
            <input type="text" class="input-texto-busqueda" id="textoBusqueda">
            <button class="boton-icono" onclick="busquedaTexto()">🔍</button>

            <?php if ((isset($_GET["textoBusqueda"]))) { ?>
                <button class="boton-icono" onclick="cancelarBusqueda()">❌</button>
            <?php } ?>

        </div>

        <?php if ($banDerecha) { ?>
            <button type="button" class="in-list-nav-btn" id="registrosSiguientes"


                onclick="modificar(-1)"

                <i>&#10095;</i>
            </button>
        <?php } ?>

        <button class="cambio-moneda-btn" onclick="exportarExcel()">Importar Excel</button>
    </div>




    <div class="gen-list-header cols-clientes">
        <span>Nombre</span>
        <span>Cédula</span>
        <span>Teléfono</span>
        <span>Ciudad</span>
        <span>Compañía</span>
        <span>Últ. Reserva</span>
        <span>Acción</span>
    </div>


    <?php
    foreach ($listaClientes as $cliente) {
        $fechaAux = new DateTime($cliente["ultimaFechaEntrada"]);

    ?>
        <div class="gen-list-card cols-clientes">
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Cliente</span> -->
                <span class="gen-list-value"><?= $cliente["nombre"] ?></span>
            </div>
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Identificación</span> -->
                <span class="gen-list-value"><?= $cliente["ci"] ?></span>
            </div>
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Teléfono</span> -->
                <span class="gen-list-value"><?= $cliente["numeroTelefono"] ?></span>
            </div>
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Ciudad</span> -->
                <span class="gen-list-value"><?= $cliente["ciudad"] ?></span>
            </div>
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Empresa</span> -->
                <span class="gen-list-value"><?= $cliente["empresa"] ?></span>
            </div>
            <div class="gen-list-group">
                <!-- <span class="gen-list-label">Fecha</span> -->
                <span class="gen-list-value"><?= $fechaAux->format("d/m/yy")  ?></span>
            </div>
            <a href="controllers/router.php?code=editarDatosCliente&id=<?= $cliente["id"] ?>" class="gen-list-btn">Editar</a>
        </div>

    <?php  } ?>

</div>


<script>
    const num = <?= $num ?>;

    function modificar(nuevo) {
        idInicio = num + nuevo;
        window.location.href = "controllers/router.php?code=listaClientes&num=" + idInicio;
    }

    function busquedaTexto() {
        const textoBusqueda = document.getElementById("textoBusqueda");
        window.location.href = "controllers/router.php?code=listaClientes&textoBusqueda=" + textoBusqueda.value;
    }

    function cancelarBusqueda() {
        window.location.href = "controllers/router.php?code=listaClientes";
    }


    function exportarExcel() {
        const text = '<?php
                        if ((isset($_GET["textoBusqueda"]))) {
                            echo "&textoBusqueda=" . $_GET["textoBusqueda"];
                        } else echo "";
                        ?>
        ';

        window.location.href = "controllers/router.php?code=generarArchivoExcelClientes" + text;
    };
</script>