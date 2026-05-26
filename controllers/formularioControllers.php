<?php
// require_once  'C:\xampp\htdocs\sistemaHotelero\www\controllers\controller.php';
require_once __DIR__ . '/../model/CRUD.php';
include_once "tools.php";
// date_default_timezone_set('America/Caracas');
if ($_POST) {

    // $_POST["submit"];
    // tools::mostrarVariableConsolaJs($_POST);

    if (isset($_POST["submit"])) {
        $respuesta = $_POST["submit"];

        if ($respuesta === "submit_ingresarHabitacion") {


            $datosCliente = [
                "nombre" =>   $_POST["nombre"],
                "telefono" =>   $_POST["telefono"],
                "cedula" =>   $_POST["cedula"],
                "ciudad" =>   $_POST["ciudad"],
                "empresa" => $_POST["empresa"]
            ];

            $idCliente = myDB::verificarCliente($datosCliente);


            $idHabitacion = $_POST["habitacion"];
            $activo = $_POST["tipoFormularioEnviado"];
            $fechaEntrada = tools::fechaFormatoYMDHMS($_POST["fechaEntrada"]);
            $fechaSalida = tools::fechaFormatoYMDHMS($_POST["fechaSalida"]);

            $datosHabitacion =
                [
                    "habitacion" => $idHabitacion,
                    "idCliente" => $idCliente,
                    "fechaEntrada" => $fechaEntrada,
                    "fechaSalida" => $fechaSalida,
                    "activo" => $activo
                ];

            $idRentaHabitacion =  myDB::ingresarRentaHabitacion($datosHabitacion);



            if ($activo == 0) {
                myDB::ingresarReservacion($idRentaHabitacion);
                $fecha = new DateTime();
                myDB::actualizarDatosReservaciones($fecha->format("Y-m-d"));
            }


            // $idPago = null;

            $numeroElementos = count($_POST["tipoPago"]);


            // tools::mostrarVariableConsolaJs(count($_POST["tipoPago"]));

            // $_POST["estadoPago"] = 0;
            if ($_POST["estadoPago"] == 1) {
                for ($i = 0; $i < $numeroElementos; $i++) {

                    $tipoPago = (int) $_POST["tipoPago"][$i];
                    $cantidad = $_POST["monto"][$i];
                    $cantidadBs = $_POST["montoBs"][$i];
                    $referencia = $_POST["referenciaPago"][$i];
                    $fecha = new DateTime();
                    $fecha = $fecha->format("Y-m-d H:m:s");



                    // arrregkar para que automaticamente derecte las monedas
                    if ($tipoPago == 0 || $tipoPago == 1 || $tipoPago == 2) {
                        $cantidad = $cantidadBs;
                    } else {
                        $cantidad =  $cantidad;
                    }




                    $datosPago = [
                        "tipoPago" => $tipoPago,
                        "cantidad" => $cantidad,
                        "referenciaPago" => $referencia,
                        "fecha" =>  $fecha,
                        "idRentaHabitacion" => $idRentaHabitacion
                    ];


                    // tools::mostrarVariableConsolaJs($datosPago);
                    myDB::ingresarPago($datosPago);
                }
            }



            header("Location: router.php?code=estadoHabitaciones");
            exit();
        }

        if ($respuesta == "submit_renta_actualizarDatosPago") {
            $largoArray = count($_POST["pago_tipo_seleccion"]);
            $numeroPagosOriginales = $_POST["numPagosOriginales"];
            for ($i = 0; $i < $largoArray; $i++) {
                $data = [
                    "id" => $_POST["id"][$i],
                    "ref" => $_POST["referenciaPago"][$i],
                    "tipo" => $_POST["pago_tipo_seleccion"][$i],
                    "cantidad" => $_POST["pago_cantidad"][$i],
                ];


                if ($i < $numeroPagosOriginales) {
                    // tools::mostrarVariableConsolaJs("actualizar");
                    myDB::actualizarDatosPago($data);
                } else {
                    if ($data["cantidad"] > 0) {

                        $fecha = new DateTime();
                        $fecha = $fecha->format("Y-m-d H:m:s");
                        $datosPago = [
                            "tipoPago" => $data["tipo"],
                            "cantidad" => $data["cantidad"],
                            "referenciaPago" => $data["ref"],
                            "fecha" =>  $fecha,
                            "idRentaHabitacion" => $_POST["id_rentaHabitacion"]
                        ];
                        myDB::ingresarPago($datosPago);
                        // tools::mostrarVariableConsolaJs("ingreser nuevo");
                    }
                }
            }


            $url = "Location: router.php?code=datosHabitacion&idRenta=" . $_POST["id_rentaHabitacion"];
            header($url);
            exit();
        }
        if ($respuesta == "submit_renta_actualizarDatosCliente") {
            $data = [
                "id_rentaHabitacion" => $_POST["id_rentaHabitacion"],
                "cedula" => $_POST["cliente_cedula"],
                "ciudad" => $_POST["cliente_ciudad"],
                "empresa" => $_POST["cliente_empresa"],
                "nombre" => $_POST["cliente_nombre"],
                "telefono" => $_POST["cliente_telefono"],
                "id" => $_POST["id"]

            ];


            myDB::actualizarDatosCliente($data);

            $url = "Location: router.php?code=datosHabitacion&idRenta=" . $_POST["id_rentaHabitacion"];
            header($url);
            exit();
        }
        if ($respuesta == "submit_renta_actualizarDatosPlazo") {

            $data = [
                "id" => $_POST["id_rentaHabitacion"],
                "fechaEntrada" => tools::fechaFormatoYMDHMS($_POST["fecha_entrada"]),
                "fechaSalida" => tools::fechaFormatoYMDHMS($_POST["fecha_salida"])
            ];


            myDB::actualizarDatosPlazo($data);

            $url = "Location: router.php?code=datosHabitacion&idRenta=" . $_POST["id_rentaHabitacion"];
            header($url);
            exit();
        }
        if ($respuesta == "submit_renta_cambiar_habitacion") {

            $data = [
                "id_rentaHabitacion" => $_POST["id_rentaHabitacion"],
                "idNuevaHabitacion" => $_POST["habitacionesDisponibles"]
            ];



            myDB::actualizarHabitacionRenta($data);
            // tools::mostrarVariableConsolaJs($_POST);

            $url = "Location: router.php?code=datosHabitacion&idRenta=" . $_POST["id_rentaHabitacion"];
            header($url);
            exit();
        }


        if ($respuesta == "submit_borrado_registroRenta") {
            myDB::borrarRegistroRenta($_POST["id_rentaHabitacion"]);
            $url = "Location: router.php?code=estadoHabitaciones";
            header($url);
            exit();
        }
        if ($respuesta == "submit_cancelar_borrado_registroRenta") {
            $url = "Location: router.php?code=datosHabitacion&idRenta=" . $_POST["id_rentaHabitacion"];
            header($url);
            exit();
        }

        if ($respuesta == "submit_convertirReservacionIngreso") {

            if ($_POST["id_reservacion"] == 0) {
                $url = "Location: router.php?code=datosHabitacion&idRenta=" . $_POST["id_renta"];
            } else {
                $url = "Location: router.php?code=listaReservacion";
            }


            myDB::confirmarReservacion($_POST["id_reservacion"], $_POST["id_renta"]);
            //    tools::mostrarVariable($url);

            header($url);
            exit();
        }

        if ($respuesta == "actualizarTasaBcv") {


            $url = "Location: router.php?code=home";

            $fechaActual = new DateTime();

            $fechaActual = tools::fechaFormatoYMDHMS($fechaActual->format("Y-m-d"));



            if($_POST["cantidad"]>0){
                myDB::actualizarTasaBcv($fechaActual, $_POST["cantidad"]);
            }
           
            header($url);
            exit();
        }

        if ($respuesta == "submit_actualizarDatosCliente") {
            // echo "hola";
            $data = [

                "cedula" => $_POST["cedula"],
                "ciudad" => $_POST["ciudad"],
                "empresa" => $_POST["empresa"],
                "nombre" => $_POST["nombre"],
                "telefono" => $_POST["telefono"],
                "id" => $_POST["id"]

            ];


            myDB::actualizarDatosCliente($data);

            $url = "Location: router.php?code=listaClientes";
            header($url);
            exit();
        }
        if ($respuesta == "submit_ingresarHabitacionMantenimiento") {

            $fechaActual = new DateTime();

            $fechaActual = tools::fechaFormatoYMDHMS($fechaActual->format("Y-m-d"));
            $data = [
                "descripcion" => $_POST["descripcion"],
                "idHabitacion" => $_POST["idHabitacion"],
                "fechaInicio" => $fechaActual

            ];
            // tools::mostrarVariable($fechaActual);

            $van = myDB::cambiarListaReservacionesMantenimientoHabitacion($_POST["idHabitacion"]);
            if (empty($van)) {
                $url = "Location: router.php?code=habMantenimiento&error=sinHabitacionCambio";
            } else {
                $url = "Location: router.php?code=listaMantenimiento";

                $res = myDB::ingresarHabitacionMantenimiento($data);
            }

            header($url);
            exit();
        }
        if ($respuesta == "submit_terminar_mantenimiento") {
            // tools::mostrarVariable($_POST);
            myDB::finalizarMantenimiento($_POST["id_hab"]);
            $url = "Location: router.php?code=estadoHabitaciones";
            header($url);
            exit();
        }

        if ($respuesta == "submit_registrarNuevaHabitacion") {
            // tools::mostrarVariable($_POST);
            $data = [
                "nombre" => $_POST["nombre"],
                "tipo" => $_POST["tipo"],
                "positionX" => $_POST["positionX"],
                "positionY" => $_POST["positionY"],
                "piso" => $_POST["piso"],
            ];
            myDB::registrarNuevaHabitacion($data);

            $url = "Location: router.php?code=estadoHabitaciones";
            header($url);
            exit();
        }

        if ($respuesta == "submit_actualizarPrecio") {




            $res =   myDB::actualizarPrecioHabitacion($_POST);

            $url = "Location: router.php?code=home";
            header($url);
            exit();
        }

        if ($respuesta == "submit_actualizarDatosHabitacion") {
            // tools::mostrarVariable($_POST);


            myDB::actualizarDatosHabitaciones($_POST);


            $url = "Location: router.php?code=home";
            header($url);
            exit();
        }
        // if ($respuesta == "submit_renta_registrarPago") {


        //     $fecha = new DateTime();
        //     $fecha = tools::fechaFormatoYMDHMS($fecha->format("Y-m-d"));

        //     $referencia = $_POST["referenciaPago"] == "" ? null : $_POST["referenciaPago"];
        //     $datosPago = [
        //         "tipoPago" => $_POST["pago_tipo_seleccion"],
        //         "cantidad" => $_POST["pago_cantidad"],
        //         "referenciaPago" => $referencia,
        //         "fecha" =>  $fecha
        //     ];

        //     // tools::mostrarVariableConsolaJs($datosPago);
        //     $id =  myDB::ingresarPago($datosPago);
        //     $var = myDB::registrarPagoHabitacion($_POST["id_rentaHabitacion"]);

        //     // tools::mostrarVariableConsolaJs($var);

        //     $url = "Location: router.php?code=datosHabitacion&idRenta=" . $_POST["id_rentaHabitacion"];
        //     header($url);
        //     exit();
        // }
    }
}
