<?php


include_once "model/CRUD.php";


include_once "controllers/tools.php";


// session_start();


if (isset($_SESSION["user"])) {
    // echo "hola";
}
function callTemplate(string $url)
{



    include_once "template/head.php";

    include_once "template/nav.php";


    include_once "views/" . $url . ".php";


    include_once "template/main.php";
}

function inicializarSistema()
{
    $_SESSION["inicio"] = true;


    $fecha = new DateTime();
    $fechaTEXTO=$fecha->format("Y-m-d");
    $ultimosMensajes=myDB::obtenerListaMensajesLogSystem();

    $ejecutarRegistroMensaje=true;
    foreach($ultimosMensajes as $mensaje){
        if($mensaje["fecha"]==$fechaTEXTO){
            $ejecutarRegistroMensaje=false;
        }
    }
    if($ejecutarRegistroMensaje){
        myDB::registrarLogSystem($fecha->format("Y-m-d"));
        myDB::actualizarDatosReservaciones($fecha->format("Y-m-d"));
    }
    
  
}

function autoLoad()
{

    session_start();

//     if(isset($_SESSION["contador"])){
        
//    echo  $_SESSION["contador"];
//     }
//     else {
//         $_SESSION["contador"]=0;
//     }



    if (isset($_SESSION["inicio"])) {
         $_SESSION["inicio"] = true;
    } else {
        inicializarSistema();
    }

    $page = isset($_GET['page']) ? $_GET['page'] : 'home';






    callTemplate($page);


    // $acceso=new Table("accesos", BD::conecction());

}
