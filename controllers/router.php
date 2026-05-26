<?php
$code = isset($_GET['code']) ? $_GET['code'] : 'nonoPage';
session_start();

if($code=="nonoPage"){
    header("Location: index.php?page=home");
}

$requireAccess=match($code){
    "admin", "logout" => true,
    default =>false
};

if($requireAccess){
   
    if(!isset($_SESSION["user"])){

     header("Location: ../index.php?page=error");
        exit;
      
    }
}


$queryString = "";

// 1. Iterar sobre todos los parámetros recibidos por GET
foreach ($_GET as $key => $value) {
    // 2. Omitir el parámetro 'code' porque será reemplazado por 'page'
    if ($key !== 'code') {
        // 3. Codificar la clave y el valor para que sean seguros en la URL
        $encodedKey = urlencode($key);
        $encodedValue = urlencode($value);
        
        // 4. Agregar el par clave=valor al string de consulta
        // Usamos el ampersand (&) como separador
        $queryString .= "&" . $encodedKey . "=" . $encodedValue;
    }
}

// 5. Construir la URL final
// La URL siempre empieza con el parámetro 'page' (que viene de $code)
// y luego añadimos los demás parámetros capturados ($queryString)
$redirectURL = "../index.php?page=" . urlencode($code) . $queryString;


// 6. Redirigir a la URL completa
header("Location: " . $redirectURL);
exit; // Es crucial usar exit después de header('Location')
?>