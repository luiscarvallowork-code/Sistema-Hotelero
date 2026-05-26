<?php
$numeroRegistrosMostrados = 999999;
$idInicio = myDB::obtenerNumeroClientes();
if (isset($_GET["textoBusqueda"])) {
    $listaClientes = myDB::obtenerListaClientes($idInicio, $numeroRegistrosMostrados, $_GET["textoBusqueda"]);
} else {
    $listaClientes = myDB::obtenerListaClientes($idInicio, $numeroRegistrosMostrados);
}

tools::mostrarVariableConsolaJs($listaClientes);

?>

<h1>hola</h1>



<script src="resources\librerias\xlsx.full.min.js"></script>
<script>
    const datos = [

        <?php
        foreach ($listaClientes as $cliente) {
            $fecha = new DateTime($cliente["ultimaFechaEntrada"]);
            echo '
        {
            "Nombre": "' . $cliente["nombre"] . '",
            "Cedula":  "' . $cliente["ci"] . '",
            "Telefono":  "' . $cliente["numeroTelefono"] . '",
            "Ultima Renta":  "' . $fecha->format("d/m/Y") . '",
            "Ciudad":  "' . $cliente["ciudad"] . '",
            "Empresa":  "' . $cliente["empresa"] . '",
        },
    ';
        }
        ?>


    ];
    // 2. Convertir el objeto JSON (datos) a una hoja de cálculo
    const hoja = XLSX.utils.json_to_sheet(datos);


    const anchosColumnas = [{
            wch: 30
        }, // Columna nombre
        {
            wch: 20
        }, // Columna ci
        {
            wch: 20
        }, // Columna tlf 
        {
            wch: 15
        }, // Columna ult renta
        {
            wch: 30
        }, // Columna ciudad
        {
            wch: 35
        } // Columna Empresa
    ];


    hoja['!cols'] = anchosColumnas;
    // 3. Crear un nuevo Libro de Trabajo (Workbook) vacío
    const libro = XLSX.utils.book_new();

    // 4. Añadir la hoja al libro y ponerle un nombre a la pestaña
    XLSX.utils.book_append_sheet(libro, hoja, "Lista de clientes");


    // 5. Generar el archivo final y forzar la descarga en el navegador
    XLSX.writeFile(libro, "Lista de Huespedes.xlsx");


    const text = '<?php
                    if ((isset($_GET["textoBusqueda"]))) {
                        echo "&textoBusqueda=" . $_GET["textoBusqueda"];
                    } else echo "";
                    ?>
    ';
    window.location.href = "controllers/router.php?code=listaClientes" + text;
</script>