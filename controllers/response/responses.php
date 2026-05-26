<?php

header('Content-Type: application/json');
date_default_timezone_set('America/Caracas');

require_once __DIR__ . '/../../model/CRUD.php';

$respuesta = "error";


if ($_SERVER["REQUEST_METHOD"] === "POST") {




  $json_data = file_get_contents("php://input");

  $allData = json_decode($json_data, true);
  $datos_recividos =  $allData[1];
  $action =  $allData[0];

  

  if ($action == "obtenerEstadoHabitaciones") {
    $fechaActual = $datos_recividos["fechaActual"];
    $respuesta = myDB::obtenerHabitacionesDisponiblesSegunFecha($fechaActual);
  }





  if ($action == "obtenerListaHabitacionesDisponibles") {

    $fechaEntrada = $datos_recividos["fechaEntrada"];
    $fechaSalida = $datos_recividos["fechaSalida"];
    $tipoHabitacion = $datos_recividos["tipoHabitacion"];

    $respuesta = myDB::obtenerListaHabitacionesDisponibles($fechaEntrada, $fechaSalida, $tipoHabitacion);
  }


  if ($action == "obtenerEstadoHabitacionesSemanal") {
    $respuesta = myDB::obtenerEstadoSemana($datos_recividos["semana"]);
  }


  if ($action == "obtenerTasaBCV") {
    $respuesta = myDB::obtenerTasaBcv();
  }


  if ($action == "obtenerPrecioHabitacion") {

    $fechaEntrada = new DateTime($datos_recividos["fechaEntrada"]);
    $fechaSalida = new DateTime($datos_recividos["fechaSalida"]);

    $intervaloDias = $fechaEntrada->diff($fechaSalida);
    $cantidadDias = (int) $intervaloDias->format("%a");
    $nombre = $datos_recividos["nombre"];

    $precio =  myDB::obtenerPrecioHabitacion($nombre);
    $precio = (int) $precio["cantidad"];

    $respuesta = $precio * $cantidadDias;





    // $respuesta="hola";
  }
}



echo json_encode($respuesta);
