<?php 

class tools{

    static function mostrarVariable($variable){

              echo "<pre>";
        var_dump($variable);
    echo "</pre>";
    }


    static function mostrarVariableConsolaJs($variable){

        $json_data = json_encode($variable);
        echo "<script>";
            echo 'console.log("Valor de la variable: ",'.$json_data.')';
        echo "</script>";

    }

    public static function fechaFormatoYMDHMS($fecha){

  
        return $fecha." 00:00:00";
    }

    public static function fechaDesFormatoYMDHMS($fecha){
        return substr($fecha, 0, 10);
    }

    public static function fechaFormato_dmyy($fecha){
        $fechaSalidaAuxiliar=new DateTime($fecha);
        return    $fechaSalidaAuxiliar=$fechaSalidaAuxiliar->format("d/m/Y");

    }

    
    public static function fechaFormato_Ymd($fecha){
        $fechaSalidaAuxiliar=new DateTime($fecha);
        return    $fechaSalidaAuxiliar=$fechaSalidaAuxiliar->format("Y-m-d");

    }

    public static function obtenerMesEspaniol($mes){
        if($mes=="01")return "Enero";
        if($mes=="02")return "Febrero";
        if($mes=="03")return "Marzo";
        if($mes=="04")return "Abril";
        if($mes=="05")return "Mayo";
        if($mes=="06")return "Junio";
        if($mes=="07")return "Julio";
        if($mes=="08")return "Agosto";
        if($mes=="09")return "Septiembre";
        if($mes=="10")return "Octubre";
        if($mes=="11")return "Noviembre";
        if($mes=="12")return "Diciembre";
    }
}
?>