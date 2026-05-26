<?php
date_default_timezone_set('America/Caracas');

class myDB extends SQLite3
{
    private static $instance = null;
    private static $db_path = __DIR__ . '/dbSystemHotel.db';

    // Constructor privado para evitar instanciación directa
    private function __construct()
    {
        $this->open(self::$db_path);

        // Verificar que la conexión se estableció correctamente
        if (!$this) {
            throw new Exception("No se pudo abrir la base de datos: " . $this->lastErrorMsg());
        }
    }

    // Método estático para obtener la única instancia
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Evitar la clonación (privado)
    private function __clone() {}

    // __wakeup debe ser público según PHP
    public function __wakeup()
    {
        throw new Exception("No se puede deserializar una instancia de " . __CLASS__);
    }

    // Método auxiliar privado
    private function converSQLsentenceToArray($sql)
    {
        $resultado = $this->query($sql);
        $datos = [];

        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $fila;
        }

        return $datos;
    }

    // ========== MÉTODOS ESTÁTICOS PÚBLICOS ==========




    public static function verClientes()
    {
        $db = self::getInstance();
        $sql = "SELECT id, nombre, numeroTelefono FROM clientes";
        $resultado = $db->query($sql);

        $clientes = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $clientes[] = $fila;
        }

        return $clientes;
    }

    public static function verClienteId($id = false): ?array
    {
        $db = self::getInstance();
        $sql = "SELECT * FROM clientes WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $resultado = $stmt->execute();
        $cliente = $resultado->fetchArray(SQLITE3_ASSOC);

        return $cliente ?: null;
    }

    public static function actualizarTelefono(int $id, string $nuevoTelefono): bool
    {
        $db = self::getInstance();
        $sql = "UPDATE clientes SET numeroTelefono = :telefono WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':telefono', $nuevoTelefono, SQLITE3_TEXT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $ejecucionExitosa = $stmt->execute();
        return (bool)$ejecucionExitosa;
    }

    public static function eliminarCliente(int $id): bool
    {
        $db = self::getInstance();
        $sql = "DELETE FROM clientes WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $ejecucionExitosa = $stmt->execute();
        return (bool)$ejecucionExitosa;
    }

    // Función privada para obtener nombres de tablas
    private static function obtenerNombresDeTablas(): array
    {
        $db = self::getInstance();
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'";

        $resultado = $db->query($sql);
        $nombresTablas = [];

        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $nombresTablas[] = $fila['name'];
        }

        return $nombresTablas;
    }

    public static function obtenerAllData(string $nombreTabla): array
    {
        $db = self::getInstance();

        // 1. Obtener la lista blanca dinámica
        $tablasPermitidas = self::obtenerNombresDeTablas();

        // 2. Validación de seguridad contra la lista dinámica
        if (!in_array($nombreTabla, $tablasPermitidas)) {
            error_log("Intento de acceso a tabla no válida: " . $nombreTabla);
            return [];
        }

        // 3. Concatenación segura y ejecución
        $sql = "SELECT * FROM " . $nombreTabla;
        $resultado = $db->query($sql);

        if (!$resultado) {
            error_log("Error de consulta en tabla $nombreTabla: " . $db->lastErrorMsg());
            return [];
        }

        $datos = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $fila;
        }

        return $datos;
    }

    public static function obtenerPosicionesHabitaciones($id)
    {
        $db = self::getInstance();
        $sql = "SELECT h.nombre, p.posicion_x, p.posicion_y, t.nombre as tipo, p.piso FROM posicionHabitaciones AS p
                INNER JOIN habitaciones as h on p.id_habitacion = h.id
                INNER JOIN tipo_habitacion as t on h.id_tipoHabitacion= t.id
                where piso=$id
                ";

        $sql = "SELECT h.nombre, p.posicion_x, p.posicion_y, t.nombre as tipo, p.piso FROM posicionHabitaciones AS p
                INNER JOIN habitaciones as h on p.id_habitacion = h.id
                INNER JOIN tipo_habitacion as t on h.id_tipoHabitacion= t.id
        
                ";

        return $db->converSQLsentenceToArray($sql);
    }

    public static function obtenerHabitacionesDisponiblesSegunFecha($fecha)
    {
        //aqui ahi un limite de las habitaciones analizadas, las ultimas 150
        $auxFecha = $fecha;
        $db = self::getInstance();
        $sql = "SELECT r.id as id, h.nombre,  r.activo, c.nombre as cliente, r.fechaSalida
        FROM rentaHabitacion  as r 
        INNER JOIN  habitaciones  as h  on r.idHabitacion = h.id
        INNER JOIN  clientes  as c on c.id = r.idCliente
        WHERE (:fecha >= r.fechaEntrada AND :fecha < r.fechaSalida)
        ORDER BY r.id
        limit 150
         ";




        $stmt = $db->prepare($sql);
        $stmt->bindValue(':fecha', $fecha, SQLITE3_TEXT);
        $resultado = $stmt->execute();

        $estadosRervados_ocupados = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $estadosRervados_ocupados[] = $fila;
        }

        //aqui ahi un limite de las habitaciones analizadas, las ultimas 150
        $sql = "SELECT m.id, h.nombre, m.descripcion, m.fecha_inicio, m.fecha_final
        FROM habitaciones  as h 
        INNER JOIN  mantenimiento  as m on m.id_habitacion = h.id
        WHERE :fecha2 >= m.fecha_inicio AND :fecha2 <= m.fecha_final
        ORDER BY m.id
        limit 150
         ";




        $stmt = $db->prepare($sql);
        $stmt->bindValue(':fecha2', $auxFecha, SQLITE3_TEXT);
        $resultado2 = $stmt->execute();

        $estadosMantenimiento = [];
        while ($fila = $resultado2->fetchArray(SQLITE3_ASSOC)) {
            $estadosMantenimiento[] = $fila;
        }

        return [$estadosRervados_ocupados, $estadosMantenimiento];
    }

    public static function obtenerTasaBcv($fecha = "actual")
    {

        $fechaActual = date("Y-m-d");
        if ($fecha == "actual") {
            $fecha = $fechaActual;
        }

        $db = self::getInstance();
        $sql = "SELECT fecha, tasa
        FROM tasas_cambio 
        WHERE :fecha =  strftime('%Y-%m-%d', fecha)
         ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':fecha', $fecha, SQLITE3_TEXT);


        $resultado = $stmt->execute();
        $resultado = $resultado->fetchArray(SQLITE3_ASSOC);

        if (!$resultado) {
            $bs  = myDB::obtenerUltimaTasaDisponible($fechaActual);
        } else {
            $bs =  $resultado;
        }


        return $bs;
        // return    $sql;
    }

    private static function obtenerUltimaTasaDisponible($fechaActual)
    {
        $db = self::getInstance();

        $fechaObjetivo = new DateTime($fechaActual);
        $fechaCercana =  new DateTime("1999-01-01");
        $tasaCercana = 0;


        $sql = "SELECT tasa, fecha  FROM tasas_cambio ORDER BY id DESC LIMIT 80;";

        $stmt = $db->prepare($sql);
        $resultado = $stmt->execute();
        $datos = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $datos[] = $fila;
        }


        foreach ($datos as $element) {
            $fechaAuxiliar = new DateTime($element["fecha"]);

            if ($fechaAuxiliar <= $fechaObjetivo) {
                if ($fechaCercana < $fechaAuxiliar) {
                    $fechaCercana = $fechaAuxiliar;
                    $tasaCercana = $element["tasa"];
                }
            }
        }



        return ["fecha" => $fechaCercana->format("Y-m-d H:i:s"), "tasa" => $tasaCercana];
    }


    public static function obtenerListaHabitacionesDisponibles($fechaEntrada, $fechaSalida, $tipo)
    {
        $db = self::getInstance();

        $fechaEntrada = new DateTime($fechaEntrada);
        $fechaSalida  = new DateTime($fechaSalida);

        $exc = [];


        $sql = "SELECT id, nombre FROM habitaciones";



        if ($tipo != "0") {
            $sql = "SELECT id, nombre FROM habitaciones WHERE id_tipoHabitacion =:tipo";
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':tipo', $tipo, SQLITE3_TEXT);
        } else {
            $stmt = $db->prepare($sql);
        }




        $resultado = $stmt->execute();
        $listaHabitaciones = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $listaHabitaciones[] = $fila;
        }

        //falta mejorar para que solo tome un rango de meses.
        $sql = "SELECT h.nombre, r.fechaEntrada, r.fechaSalida FROM rentaHabitacion as r
                INNER JOIN habitaciones as h 
                on h.id = r.idHabitacion";
        $stmt = $db->prepare($sql);

        $resultado = $stmt->execute();
        $listaRentasHabitaciones = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $listaRentasHabitaciones[] = $fila;

            $fechaEntradaRegistro = new DateTime($fila["fechaEntrada"]);
            $fechaSalidaRegistro = new DateTime($fila["fechaSalida"]);
            // $fechaSalidaRegistro->modify('+1 day');

            // if ($fechaEntradaRegistro <= $fechaSalida && $fechaEntrada <= $fechaSalidaRegistro) {
            if ($fechaEntradaRegistro < $fechaSalida && $fechaEntrada < $fechaSalidaRegistro) {
                $exc[] = $fila["nombre"];
            }
        }


        $sql = "SELECT h.nombre, fecha_inicio as fechaEntrada, fecha_final  as fechaSalida
                FROM mantenimiento as m
                INNER JOIN habitaciones as h 
                on h.id = m.id_habitacion";
        $stmt = $db->prepare($sql);

        $resultado = $stmt->execute();
        $listaRentasHabitaciones = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $listaRentasHabitaciones[] = $fila;

            $fechaEntradaRegistro = new DateTime($fila["fechaEntrada"]);
            $fechaSalidaRegistro = new DateTime($fila["fechaSalida"]);

            if ($fechaEntradaRegistro < $fechaSalida && $fechaEntrada < $fechaSalidaRegistro) {
                // if ($fechaEntradaRegistro <= $fechaSalida && $fechaEntrada <= $fechaSalidaRegistro) {
                $exc[] = $fila["nombre"];
            }
        }



        $listaFinalHabitaciones = [];


        foreach ($listaHabitaciones as $habitacion) {
            $van = true;
            $nombre = $habitacion["nombre"];
            $id = $habitacion["id"];

            foreach ($exc as $excepcion) {
                if ($excepcion == $nombre) {
                    $van = false;
                }
            }


            if ($van) {
                $listaFinalHabitaciones[] = [$nombre, $id];
            }
        }


        return $listaFinalHabitaciones;
    }

    public static function obtenerPrecioHabitacion($habitacion)
    {
        $db = self::getInstance();
        // $resultado="hola";

        $sql = "SELECT p.cantidad from  tipo_habitacion as t
                inner join habitaciones as h
					on t.id= h.id_tipoHabitacion
				INNER JOIN precios as p
					on t.id= p.IdTipoHabitacion
				where h.nombre = :nombre
         ";

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':nombre', $habitacion, SQLITE3_TEXT);

        $resultado = $stmt->execute();
        $resultado = $resultado->fetchArray(SQLITE3_ASSOC);


        return $resultado;
    }

    public static function ingresarRentaHabitacion($data)
    {
        $db = self::getInstance();

        $habitacion = $data["habitacion"];

        $sql = "SELECT id from habitaciones WHERE nombre = :nombreHabitacion";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':nombreHabitacion', $habitacion, SQLITE3_TEXT);
        $resultado = $stmt->execute();
        $resultado = $resultado->fetchArray(SQLITE3_ASSOC);

        $idHabitacion = $resultado["id"];


        $idCliente = $data["idCliente"];
        $fechaEntrada = $data["fechaEntrada"];
        $fechaSalida = $data["fechaSalida"];
        // $idPago = $data["idPago"];
        $activo = $data["activo"];
        // $resultado="hola";

        $sql =
            'INSERT INTO "rentaHabitacion" ("idCliente", "idHabitacion", "fechaEntrada", 
        "fechaSalida", "activo")
        VALUES (:idCliente, :habitacion, :fechaEntrada, :fechaSalida,  :activo)';

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':idCliente', $idCliente, SQLITE3_INTEGER);
        $stmt->bindValue(':habitacion', $idHabitacion, SQLITE3_INTEGER);
        $stmt->bindValue(':fechaEntrada', $fechaEntrada, SQLITE3_TEXT);
        $stmt->bindValue(':fechaSalida', $fechaSalida, SQLITE3_TEXT);
        // $stmt->bindValue(':idPago', $idPago, SQLITE3_INTEGER);
        $stmt->bindValue(':activo', $activo, SQLITE3_INTEGER);

        $resultado = $stmt->execute();

        if ($resultado) {
            return $db->lastInsertRowID();
        } else {
            return false;
        }
    }



    private static function ingresarCliente($datos)
    {
        $db = self::getInstance();

        $nombre = $datos["nombre"];
        $telefono =  $datos["telefono"];
        $cedula = $datos["cedula"];
        $ciudad = $datos["ciudad"];
        $empresa = $datos["empresa"];

        $sql = "INSERT INTO clientes (nombre, numeroTelefono, ci, ciudad, empresa)
                VALUES(:nombre, :telefono, :cedula, :ciudad, :empresa)";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":nombre", $nombre, SQLITE3_TEXT);
        $stmt->bindValue(":telefono", $telefono, SQLITE3_TEXT);
        $stmt->bindValue(":cedula", $cedula, SQLITE3_TEXT);
        $stmt->bindValue(":ciudad", $ciudad, SQLITE3_TEXT);
        $stmt->bindValue(":empresa", $empresa, SQLITE3_TEXT);

        $resultado = $stmt->execute();


        if ($resultado) {
            return $db->lastInsertRowID();
        } else {
            return false;
        }
    }

    public static function verificarCliente($datos)
    {
        $cedula = $datos["cedula"];
        $db = self::getInstance();

        $sql = "SELECT * FROM clientes WHERE ci = :cedula";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':cedula', $cedula, SQLITE3_TEXT);
        $resultado = $stmt->execute();
        $resultado = $resultado->fetchArray(SQLITE3_ASSOC);


        if ($resultado == false) {
            $resultado = myDB::ingresarCliente($datos);
        } else {
            $resultado = $resultado["id"];
        }


        return $resultado;
    }


    public static function ingresarPago($datos)
    {

        $tipoPago = $datos["tipoPago"];
        $cantidad = $datos["cantidad"];
        $fecha = $datos["fecha"];


        // tools::mostrarVariable($_POST);
        $referencia = $datos["referenciaPago"] == null ? " " : $datos["referenciaPago"];
        $idRentaHabitacion = $datos["idRentaHabitacion"];


        $db = self::getInstance();

        $sql = "INSERT INTO pagos (tipoPago, cantidad, fecha, referencia, idRenta)
                VALUES (:tipoPago, :cantidad, :fecha, :referencia, :idRentaHabitacion)";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':tipoPago', $tipoPago, SQLITE3_INTEGER);
        $stmt->bindValue(':cantidad', $cantidad, SQLITE3_FLOAT);
        $stmt->bindValue(':fecha', $fecha, SQLITE3_TEXT);
        $stmt->bindValue(':referencia', $referencia, SQLITE3_TEXT);
        $stmt->bindValue(':idRentaHabitacion', $idRentaHabitacion, SQLITE3_INTEGER);
        $resultado = $stmt->execute();

        return;

        // return $resultado ? $db->lastInsertRowID() : false;
    }

    public static function registrarPagoHabitacion($idRenta)
    {
        $db = self::getInstance();
        $sql = "SELECT id from pagos 
        ORDER BY id DESC
        limit 1";

        $stmt = $db->prepare($sql);
        $resultado = $stmt->execute();
        $resultado = $resultado->fetchArray(SQLITE3_ASSOC);

        $idPago = $resultado["id"];



        $sql = "UPDATE rentaHabitacion SET id_pago = :pago WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':pago', $idPago, SQLITE3_INTEGER);
        $stmt->bindValue(':id', $idRenta, SQLITE3_INTEGER);

        $ejecucionExitosa = $stmt->execute();
        return (bool)$ejecucionExitosa;
    }


    public static function obtenerDataReservacion($idRentaHabitacion)
    {
        $db = self::getInstance();

        // /obtener datos de la renta o RESERVACIÓN 
        $sql = "SELECT * FROM rentaHabitacion WHERE id =:id";
        $consulta = $db->prepare($sql);
        $consulta->bindValue(":id", $idRentaHabitacion, SQLITE3_INTEGER);
        $resultado = $consulta->execute();
        $datosRentaHabitacion = $resultado->fetchArray(SQLITE3_ASSOC);


        // obtener datos del cliente
        $idCliente = $datosRentaHabitacion["idCliente"];
        $sql = "SELECT * FROM clientes WHERE id = $idCliente";
        $consulta = $db->prepare($sql);

        $resultado = $consulta->execute();
        $datosCliente = $resultado->fetchArray(SQLITE3_ASSOC);

        // obtener datos del pago
        // $idPago = $datosRentaHabitacion["id_pago"];
        // $idPago = 3;
        // $datosPago = $idPago;
        if (null) {

            $sql = "
            SELECT 
                p.id, 
                p.cantidad, 
                p.fecha, 
                p.referencia, 
                t.nombre 
            FROM pagos AS p
            LEFT JOIN tipoPago AS t ON p.tipoPago = t.id
            WHERE p.idRenta=$idRentaHabitacion
            ";

            $sql = "
            SELECT * from pagos
            WHERE idRenta=$idRentaHabitacion
            ";
            // $sql = "SELECT p.id as id, p.cantidad, p.fecha, p.referencia, t.nombre
            //         FROM  pagos  as p
            //         INNER JOIN tipoPago as t
            //         on p.tipoPago = t.id
            //         where p.id=$idPago";
            $consulta = $db->prepare($sql);
            $resultado = $consulta->execute();
            $datosPago = $resultado->fetchArray(SQLITE3_ASSOC);
        }

        // $sql = "
        //     SELECT * from pagos
        //     WHERE idRenta=$idRentaHabitacion
        //     ";
        $sql = "SELECT p.id as id, p.cantidad, p.fecha, p.referencia, t.nombre as tipoNombre, t.id  as tipoId
                FROM  pagos  as p
                INNER JOIN tipoPago as t
                on p.tipoPago = t.id
                 WHERE P.idRenta=$idRentaHabitacion";
        $consulta = $db->prepare($sql);
        $resultado = $consulta->execute();
        $datosPago = [];

        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $datosPago[] = $fila;
        }


        // datos de la habitacion


        $idHabitacion = $datosRentaHabitacion["idHabitacion"];

        $sql = "SELECT h.id, h.nombre, t.nombre as tipo FROM  habitaciones as h
                INNER join tipo_habitacion as t 
                on h.id_tipoHabitacion = t.id 
                WHERE h.id = $idHabitacion";
        $consulta = $db->prepare($sql);

        $resultado = $consulta->execute();
        $datosHabitacion = $resultado->fetchArray(SQLITE3_ASSOC);

        $data = [
            "datos_renta" => $datosRentaHabitacion,
            "datos_cliente" => $datosCliente,
            "datos_pago" => $datosPago,
            "datos_habitacion" => $datosHabitacion

        ];

        return $data;
    }



    public static function obtenerOpcionesPago()
    {
        $db = self::getInstance();

        $sql = "SELECT id, nombre FROM tipoPago";
        $consulta = $db->prepare($sql);

        $resultado = $consulta->execute();

        $listaOpcionesPago = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $listaOpcionesPago[] = $fila;
        }
        return $listaOpcionesPago;
    }

    public static function actualizarDatosPago($dataPago)
    {
        $db = self::getInstance();

        $sql = 'UPDATE pagos
                SET `tipoPago`=:tipo,
                `cantidad`=:cantidad,
                `referencia`=:referencia WHERE 
                id= :id';
        $consulta = $db->prepare($sql);
        $consulta->bindValue(":tipo", $dataPago["tipo"], SQLITE3_INTEGER);
        $consulta->bindValue(":cantidad", $dataPago["cantidad"], SQLITE3_INTEGER);
        $consulta->bindValue(":referencia", $dataPago["ref"], SQLITE3_TEXT);
        $consulta->bindValue(":id", $dataPago["id"], SQLITE3_INTEGER);
        $resultado = $consulta->execute();
        // $idTipoPago = $resultado->fetchArray(SQLITE3_ASSOC);


        return $resultado ? true : false;
    }

    public static function actualizarDatosPlazo($datosPlazo)
    {
        $db = self::getInstance();

        $sql = 'update rentaHabitacion 
                SET fechaEntrada = :fechaEntrada,
                    fechaSalida  = :fechaSalida
                WHERE id= :id';
        $consulta = $db->prepare($sql);
        $consulta->bindValue(":fechaEntrada", $datosPlazo["fechaEntrada"], SQLITE3_TEXT);
        $consulta->bindValue(":fechaSalida", $datosPlazo["fechaSalida"], SQLITE3_TEXT);
        $consulta->bindValue(":id", $datosPlazo["id"], SQLITE3_INTEGER);
        $resultado = $consulta->execute();
        // $idTipoPago = $resultado->fetchArray(SQLITE3_ASSOC);


        return $resultado ? true : false;
    }

    public static function actualizarDatosCliente($datosCliente)
    {
        $db = self::getInstance();

        $sql = 'UPDATE clientes
                SET nombre =:nombre,
                    numeroTelefono =:numeroTelefono,
                    ci = :ci,
                    ciudad= :ciudad,
                    empresa= :empresa
                WHERE id= :id';
        $consulta = $db->prepare($sql);
        $consulta->bindValue(":nombre", $datosCliente["nombre"], SQLITE3_TEXT);
        $consulta->bindValue(":numeroTelefono", $datosCliente["telefono"], SQLITE3_TEXT);
        $consulta->bindValue(":ci", $datosCliente["cedula"], SQLITE3_TEXT);
        $consulta->bindValue(":ciudad", $datosCliente["ciudad"], SQLITE3_TEXT);
        $consulta->bindValue(":empresa", $datosCliente["empresa"], SQLITE3_TEXT);
        $consulta->bindValue(":id", $datosCliente["id"], SQLITE3_INTEGER);
        $resultado = $consulta->execute();



        return $resultado ? true : false;
    }

    public static function actualizarHabitacionRenta($datosRenta)
    {
        $db = self::getInstance();

        if (isset($datosRenta["nota"])) {
            $nota = $datosRenta["nota"];
        } else {
            $nota = "";
        }

        $sql = 'update rentaHabitacion 
                    SET idHabitacion = :idHabitacion,   
                         nota =:nota   
                    WHERE id= :id';
        $consulta = $db->prepare($sql);
        $consulta->bindValue(":idHabitacion", $datosRenta["idNuevaHabitacion"], SQLITE3_INTEGER);
        $consulta->bindValue(":nota", $nota, SQLITE3_TEXT);

        $consulta->bindValue(":id", $datosRenta["id_rentaHabitacion"], SQLITE3_INTEGER);
        $resultado = $consulta->execute();



        return $resultado ? true : false;
    }

    public static function borrarRegistroRenta($idRentaHabitacion)
    {
        $db = self::getInstance();
        $sql = "DELETE FROM rentaHabitacion 
                WHERE id = :id";

        $consulta = $db->prepare($sql);
        $consulta->bindValue(":id", $idRentaHabitacion, SQLITE3_INTEGER);


        $resultado = $consulta->execute();

        return $resultado ? true : false;
    }

    public static function registrarLogSystem($fecha)
    {
        $db = self::getInstance();
        $mensaje = "Inicio del sistema sin problemas";

        $sql = "SELECT * FROM system_log 
                ORDER by id DESC
                LIMIT 10";

        $sql = "INSERT INTO  system_log ('fecha', 'message')
                VALUES('$fecha', '$mensaje')";


        $resultado = $db->query($sql);
    }

    public static function obtenerListaMensajesLogSystem()
    {
        $db = self::getInstance();
        $mensaje = "Inicio del sistema sin problemas";

        $sql = "SELECT * FROM system_log 
                ORDER by id DESC
                LIMIT 10";


        $resultado = $db->query($sql);

        $mensajesSistema = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $mensajesSistema[] = $fila;
        }


        return $mensajesSistema;
    }

    public static function caducarReservacion($id)
    {
        $db = self::getInstance();
        $sql = "UPDATE reservacion
                SET estado=0
                WHERE id=:id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $resultado = $stmt->execute();
    }

    public static function actualizarDatosReservaciones($fecha)
    {
        $fechaActual = new DateTime($fecha);
        $db = self::getInstance();
        $sql = "SELECT r.id, ren.fechaSalida FROM reservacion  AS r
                INNER JOIN rentaHabitacion as ren 
                on r.idRentaHabitacion = ren.id
                ORDER by r.id DESC
                LIMIT 20";
        $stmt = $db->prepare($sql);
        $resultado = $stmt->execute();


        $listaReservaciones = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $fecha_aux = new DateTime($fila["fechaSalida"]);

            if ($fechaActual > $fecha_aux) {
                myDB::caducarReservacion($fila["id"]);
            }
            $listaReservaciones[] = $fila;
        }






        return $listaReservaciones;
    }

    public static function ingresarReservacion($idHabitacion)
    {

        $db = self::getInstance();

        $sql = "INSERT INTO reservacion (idRentaHabitacion, estado)
                VALUES (:idReservacion, 1)";

        //0-caduca
        //1-activa
        //2-completada

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idReservacion', $idHabitacion, SQLITE3_INTEGER);

        $resultado = $stmt->execute();
        // $resultado = $resultado->fetchArray(SQLITE3_ASSOC);

        //   session_start();

        // $_SESSION["contador"]++;

        // return $resultado;
    }

    public static function obtenerListaReservaciones($estado = null)
    {
        $limite =25;
        $db = self::getInstance();

        if ($estado != null) {
            $sql = "SELECT * FROM reservacion
            WHERE estado =:estado
            ORDER by id DESC";


            $sql = "SELECT r.id as id, renta.id as idRenta,c.nombre as cliente, 
            c.empresa as empresa, h.nombre as hab,
                    renta.fechaEntrada, renta.fechaSalida, r.estado
                    FROM rentaHabitacion as renta
                    INNER JOIN reservacion as r 
                        on r.idRentaHabitacion = renta.id
                    INNER JOIN clientes as c
                        on renta.idCliente =c.id
                    INNER JOIN habitaciones as h
                        on renta.idHabitacion = h.id
                   WHERE estado =:estado
                    ORDER by id DESC
                      limit $limite";
            $stm = $db->prepare($sql);
            $stm->bindValue(':estado', $estado, SQLITE3_INTEGER);
        } else {
            $sql = "SELECT r.id as id, renta.id as idRenta,c.nombre as cliente, 
             c.empresa as empresa, h.nombre as hab, 
                    renta.fechaEntrada, renta.fechaSalida, r.estado
                    FROM rentaHabitacion as renta
                    INNER JOIN reservacion as r 
                        on r.idRentaHabitacion = renta.id
                    INNER JOIN clientes as c
                        on renta.idCliente =c.id
                    INNER JOIN habitaciones as h
                        on renta.idHabitacion = h.id

                    ORDER by id DESC
                    limit $limite";
            $stm = $db->prepare($sql);
        }


        $resultado = $stm->execute();

        $listaReservaciones = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $fila["estadoPago"] = myDB::comprobarRentaPagado($fila["idRenta"]);
            $listaReservaciones[] = $fila;
        }
        return $listaReservaciones;
    }

    public static function comprobarRentaPagado($id)
    {
        $db = self::getInstance();


        $van = false;

        $sql = "SELECT * FROM pagos 
            WHERE idRenta= :idRenta";


        $stm = $db->prepare($sql);
        $stm->bindValue(':idRenta', $id, SQLITE3_INTEGER);


        $resultado = $stm->execute();

        $cont = 0;
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $cont++;
        }
        $van = $cont > 0 ? true : false;
        return $van;
    }

    public static function confirmarReservacion($idReservacion, $idRenta)
    {

        $db = self::getInstance();

        if ($idReservacion == 0) {
            $sql = "SELECT id from reservacion
                where idRentaHabitacion=:id";
            $consulta = $db->prepare($sql);
            $consulta->bindValue(":id",  $idRenta, SQLITE3_INTEGER);
            $resultado = $consulta->execute();

            $datos = [];
            while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
                $datos[] = $fila;
            }

            $idReservacion = $datos[0]["id"];
        }

        $sql = 'update rentaHabitacion 
                    SET activo = 1
                    WHERE id= :id';
        $consulta = $db->prepare($sql);
        $consulta->bindValue(":id",  $idRenta, SQLITE3_INTEGER);


        $resultado = $consulta->execute();

        $sql = 'update reservacion 
                    SET estado = 2
                    WHERE id= :id';
        $consulta = $db->prepare($sql);
        $consulta->bindValue(":id",  $idReservacion, SQLITE3_INTEGER);


        $resultado = $consulta->execute();


        return $resultado ? true : false;
    }
    public static function obtenerNumerHabitacionesOcupadas()
    {

        $fechaActual = new DateTime();
        $db = self::getInstance();
        $sql = "SELECT * FROM rentaHabitacion
                WHERE activo=1
                ORDER by id DESC
                limit 150";

        $stm = $db->prepare($sql);
        $resultado = $stm->execute();

        $listaHabitacionesOcupadas = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $fechaEntrada = new DateTime($fila["fechaEntrada"]);
            $fechaSalida = new DateTime($fila["fechaSalida"]);

            if ($fechaActual >= $fechaEntrada && $fechaActual < $fechaSalida) {
                $listaHabitacionesOcupadas[] = $fila;
            }
        }

        return count($listaHabitacionesOcupadas);
    }
    public static function obtenerNumeroTotalHabitaciones()
    {

        $fechaActual = new DateTime();
        $db = self::getInstance();
        $sql = "SELECT count(*) as total FROM habitaciones
                ORDER by id DESC
                limit 150";

        $stm = $db->prepare($sql);
        $resultado = $stm->execute();


        return  $resultado->fetchArray(SQLITE3_ASSOC)["total"];
    }

    public static function obtenerListaPreciosHabitacion()
    {

        $fechaActual = new DateTime();
        $db = self::getInstance();
        $sql = "SELECT p.id, h.nombre, p.cantidad from precios as p 
        INNER JOIN tipo_habitacion as h on p.IdTipoHabitacion = h.id";

        $stm = $db->prepare($sql);
        $resultado = $stm->execute();

        $listaPrecios = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {

            $listaPrecios[] = $fila;
        }
        return $listaPrecios;
    }

    public static function obtenerFacturacionTotal()
    {

        $fechaActual = new DateTime();
        $db = self::getInstance();

        $sql = "SELECT p.id, p.fecha, p.cantidad, m.base FROM pagos as p
                INNER JOIN tipoPago as t on p.tipoPago = t.id
                INNER JOIN monedas as m on t.moneda =m.id
                ORDER BY p.id DESC
                LIMIT 250";



        $stm = $db->prepare($sql);
        $resultado = $stm->execute();

        $listaPagos = [];
        $pagoTotal = 0;
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $fechaPago = new DateTime($fila["fecha"]);


            if (
                ($fechaActual->format("m") == $fechaPago->format("m")) &&
                ($fechaActual->format("y") == $fechaPago->format("y"))
            ) {
                // nota: arreglar despues para que pueda calcular la tasa incluso si la 
                // moneda base no es divisa
                $monto = (float)$fila["cantidad"];


                if ($fila["base"] == 0) {

                    $tasaAuxiliar = myDB::obtenerUltimaTasaDisponible($fechaPago->format("Y-m-d"));
                    $monto = $monto / $tasaAuxiliar["tasa"];
                }


                $pagoTotal = $pagoTotal + $monto;
                $listaPagos[] = $fila;
            }
        }

        return round($pagoTotal, 2);
    }

    public static function obtenerDatosDashboar()
    {
        $data = [];

        $fechaActual = new DateTime();
        $tasa = myDB::obtenerUltimaTasaDisponible($fechaActual->format("yy-m-d"));


        $NumHabOcupadas =  myDB::obtenerNumerHabitacionesOcupadas();




        $NumHabTotales = myDB::obtenerNumeroTotalHabitaciones();

        $facturacionTotal = myDB::obtenerFacturacionTotal();

        // $listaPrecios = [
        //     ["nombre" => "doble", "cantidad" => 300],
        //     ["nombre" => "matrimionial", "cantidad" => 200]
        // ];

        $listaPrecios = myDB::obtenerListaPreciosHabitacion();



        $reservacionesRecientes =     myDB::obtenerListaReservaciones();


        $mesActual = intval($fechaActual->format("m"));
        $reservacionesFinal = [];

        //FILTRO PARA SOLO LAS RESERVACIONES RELEVANTES
        foreach ($reservacionesRecientes as $key => $value) {
            $fechaEntrada = new DateTime($value["fechaEntrada"]);
            $fechaSalida = new DateTime($value["fechaSalida"]);

            $mesEntrada = intval($fechaEntrada->format("m"));
            $mesSalida = intval($fechaSalida->format("m"));
            if (
                ($mesActual >= $mesEntrada && $mesActual <= $mesSalida) ||
                ($mesActual - 1 >= $mesEntrada && $mesActual - 1 <= $mesSalida)
            ) {
                $reservacionesFinal[] = $value;
            }
        }

        $data = [
            "tasa" => $tasa["tasa"],
            "tasaFecha" => tools::fechaFormato_dmyy($tasa["fecha"]),
            "NumHabOcupadas" => $NumHabOcupadas,
            "NumHabTotales" => $NumHabTotales,
            "facturacionTotal" => $facturacionTotal,
            "listaPrecios" => $listaPrecios,
            "facturacionTotal" => $facturacionTotal,
            "reservacionesRecientes" => $reservacionesFinal,
        ];
        return $data;
    }

    public static function actualizarTasaBcv($fecha, $cantidad)
    {
        $db = self::getInstance();
        // "2020-01-01 00:00:00"
        $sql = '
        INSERT INTO "tasas_cambio" ("id_moneda_base", 
                    "id_moneda_convertida",  "id_tipoTasa", "tasa", "fecha") 
        VALUES (1, 2, 1, :cantidad, :fecha);
        ';
        $consulta = $db->prepare($sql);
        $consulta->bindValue(":cantidad", $cantidad, SQLITE3_FLOAT);
        $consulta->bindValue(":fecha", $fecha, SQLITE3_TEXT);
        $resultado = $consulta->execute();



        return $resultado ? true : false;
    }

    public static function obtenerListaIngresosTotales($fecha)
    {
        $fechaOBjetivo = new DateTime($fecha);

        $db = self::getInstance();

        $sql = "SELECT r.id, h.nombre, c.nombre as cliente, r.fechaEntrada, r.fechaSalida FROM rentaHabitacion as r
                INNER JOIN habitaciones as h on r.idHabitacion = h.id
                INNER join clientes as c on r.idCliente =c.id
                ORDER by r.id DESC
                ";

        $stm = $db->prepare($sql);
        $resultado = $stm->execute();

        $ingresosHabitaciones = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {

            $fechaAux = new DateTime($fila["fechaEntrada"]);
            if (
                ($fechaOBjetivo->format("m") == $fechaAux->format("m")) &&
                ($fechaOBjetivo->format("Y") == $fechaAux->format("Y"))
            ) {
                $fila["estadoPago"] = myDB::comprobarRentaPagado($fila["id"]);
                $ingresosHabitaciones[] = $fila;
            }
        }
        return $ingresosHabitaciones;
    }


    public static function obtenerNumeroClientes()
    {
        $db = self::getInstance();

        $sql = "SELECT COUNT(*) as total FROM clientes
                ";

        $stm = $db->prepare($sql);
        $resultado = $stm->execute();

        $total = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {


            $total[] = $fila;
        }
        return $total[0]["total"];
    }

    public static function obtenerListaClientes($idCuenta, $numeroRegistros, $texto = "")
    {
        $db = self::getInstance();

        $sql = "SELECT 
                c.id, 
                c.nombre, 
                c.ci, 
                c.numeroTelefono, 
                c.ciudad, 
                c.empresa, 
                MAX(r.fechaEntrada) AS ultimaFechaEntrada -- Aquí aseguramos la última
            FROM (
                SELECT * FROM clientes
                WHERE id <= :idCuenta
                ORDER BY id DESC 
                LIMIT  :numeroRegistros
            ) AS c
            INNER JOIN rentaHabitacion AS r ON c.id = r.idCliente
            GROUP BY c.id
            ORDER BY MAX(r.fechaEntrada) DESC;";


        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idCuenta', $idCuenta, SQLITE3_INTEGER);
        $stmt->bindValue(':numeroRegistros', $numeroRegistros, SQLITE3_INTEGER);
        $resultado = $stmt->execute();

        $listaClientes = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            if ($texto != "") {
                if (str_contains($fila["nombre"], $texto)) {
                    $listaClientes[] = $fila;
                }
            } else {
                $listaClientes[] = $fila;
            }
        }
        return $listaClientes;
    }

    public static function obtenerDatosClientes($id)
    {
        $db = self::getInstance();

        $sql = "SELECT * FROM clientes
                WHERE id = :id
                ";


        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $resultado = $stmt->execute();

        $listaClientes = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            return $fila;


            $listaClientes[] = $fila;
        }
        // return $listaClientes;
    }



    public static function obtenerListaDatosHabitaciones()
    {
        $db = self::getInstance();

        $sql = "SELECT 
             h.id, 
             h.nombre,
			 t.nombre as tipo,
			 p.cantidad,
                MAX(r.fechaEntrada) AS ultimaFechaEntrada -- Aquí aseguramos la última
            FROM (
                SELECT * FROM habitaciones 
             
                ORDER BY id DESC 
           
            ) AS h
            left join rentaHabitacion AS r ON h.id = r.idHabitacion
			INNER JOIN tipo_habitacion as t on h.id_tipoHabitacion = t.id
			INNER JOIN precios as p on p.IdTipoHabitacion = t.id
            GROUP BY h.id
            ORDER BY h.id ASC;";

        $stmt = $db->prepare($sql);


        $resultado = $stmt->execute();

        $listaDatosHabitaciones = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {

            $listaDatosHabitaciones[] = $fila;
        }
        return $listaDatosHabitaciones;
    }

    public static function obtenerListaDatosPagos()
    {
        $db = self::getInstance();

        $sql = "SELECT p.id,  p.cantidad, p.referencia, h.nombre, p.fecha, r.id as idRenta, m.codigo, t.nombre as tipo, cli.nombre as cliente
         from pagos as p
            INNER join rentaHabitacion as r on p.idRenta = r.id
            INNER JOIN habitaciones AS h on h.id = r.idHabitacion
			INNER join tipoPago as t on p.tipoPago = t.id
			INNER JOIN monedas as m on m.id = t.moneda
            INNER JOIN clientes as cli on r.idCliente =  cli.id
            ORDER by p.id DESC";

        $stmt = $db->prepare($sql);


        $resultado = $stmt->execute();

        $listaDatosPagos = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {

            $fecha = new DateTime($fila["fecha"]);
            $tasa = myDB::obtenerUltimaTasaDisponible($fecha->format("Y-m-d"));
            $tasaFecha = $tasa["fecha"];
            $tasa = $tasa["tasa"];

            $listaDatosPagos[] = [
                "id" => $fila["id"],
                "cantidad" => $fila["cantidad"],
                "referencia" => $fila["referencia"],
                "nombre" => $fila["nombre"],
                "fecha" => $fila["fecha"],
                "tasa" => $tasa,
                "fechaTasa" => $tasaFecha,
                "idRenta" => $fila["idRenta"],
                "codigo" => $fila["codigo"],
                "tipo" => $fila["tipo"],
                "cliente" => $fila["cliente"],
            ];
        }
        return $listaDatosPagos;
    }

    public static function obtenerListaMantenimiento()
    {
        $db = self::getInstance();

        $sql = "SELECT m.id, m.descripcion, m.fecha_inicio, m.fecha_final, h.nombre	 
                FROM mantenimiento as m
                INNER join habitaciones as h on m.id_habitacion =h.id
                ORDER BY m.id DESC
                LIMIT 50
                ";

        $stmt = $db->prepare($sql);


        $resultado = $stmt->execute();

        $listaMantenimiento = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {



            $listaMantenimiento[] = $fila;
        }
        return $listaMantenimiento;
    }



    public static function obtenerListaTasas()
    {
        $db = self::getInstance();

        $sql = "SELECT tc.id, t.nombre, tc.tasa, tc.fecha  FROM tasas_cambio as tc
                INNER join tiposTasa as t  on tc.id_tipoTasa = t.id
                ORDER BY tc.id DESC
                limit 35
                ";

        $stmt = $db->prepare($sql);

        $resultado = $stmt->execute();

        $listaTasas = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $listaTasas[] = $fila;
        }
        return $listaTasas;
    }

    public static function ingresarHabitacionMantenimiento($data)
    {
        $db = self::getInstance();
        $sql = "INSERT INTO mantenimiento (id_habitacion, fecha_inicio, fecha_final, descripcion)
        VALUES (:idHabitacion, :fechaInicio, '2250-01-01 00:00:00',  :descripcion)";


        //0-caduca
        //1-activa
        //2-completada

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idHabitacion', $data["idHabitacion"], SQLITE3_INTEGER);
        $stmt->bindValue(':fechaInicio', $data["fechaInicio"], SQLITE3_TEXT);
        $stmt->bindValue(':descripcion', $data["descripcion"], SQLITE3_TEXT);


        $resultado = $stmt->execute();

        return $resultado;
    }

    public static function obtenerListaDatosHabitacionesDisponiblesMantenimiento()
    {
        $db = self::getInstance();


        $sql = "SELECT h.nombre, m.descripcion, m.fecha_inicio, m.fecha_final
        FROM habitaciones  as h 
        INNER JOIN  mantenimiento  as m on m.id_habitacion = h.id
       

        WHERE :fecha2 >= m.fecha_inicio AND :fecha2 <= m.fecha_final
         ";



        $auxFecha = new DateTime();
        $auxFecha = tools::fechaFormatoYMDHMS($auxFecha->format("Y-m-d"));
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':fecha2', $auxFecha, SQLITE3_TEXT);
        $resultado2 = $stmt->execute();

        $estadosMantenimiento = [];
        while ($fila = $resultado2->fetchArray(SQLITE3_ASSOC)) {
            $estadosMantenimiento[] = $fila;
        }

        $sql = "SELECT 
             h.id, 
             h.nombre,
			 t.nombre as tipo,
			 p.cantidad,
                MAX(r.fechaEntrada) AS ultimaFechaEntrada -- Aquí aseguramos la última
            FROM (
                SELECT * FROM habitaciones 
             
                ORDER BY id DESC 
           
            ) AS h
            left join rentaHabitacion AS r ON h.id = r.idHabitacion
			INNER JOIN tipo_habitacion as t on h.id_tipoHabitacion = t.id
			INNER JOIN precios as p on p.IdTipoHabitacion = t.id
            GROUP BY h.id
            ORDER BY h.id ASC;";

        $stmt = $db->prepare($sql);


        $resultado = $stmt->execute();

        $listaDatosHabitaciones = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $van = true;
            foreach ($estadosMantenimiento as $datosMantenimiento) {
                if ($datosMantenimiento["nombre"] == $fila["nombre"]) {
                    $van = false;
                }
            }
            if ($van == true) {
                $listaDatosHabitaciones[] = $fila;
            }
        }



        return $listaDatosHabitaciones;
    }
    public static function cambiarListaReservacionesMantenimientoHabitacion($idHabitacion)
    {
        $db = self::getInstance();

        $fechaAux = new DateTime();
        $fechaAuxTexto = tools::fechaFormatoYMDHMS($fechaAux->format("Y-m-d"));

        $sql = "SELECT id_tipoHabitacion FROM  habitaciones
           where id = :idHabitacion
           ";



        $stmt = $db->prepare($sql);

        $stmt->bindValue(':idHabitacion', $idHabitacion, SQLITE3_INTEGER);

        $resultado = $stmt->execute();

        $tipoHabitacion = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {

            $tipoHabitacion[] = $fila;
        }

        $tipoHabitacion = $tipoHabitacion[0]["id_tipoHabitacion"];
        $sql = "SELECT * FROM rentaHabitacion
                where idHabitacion = :idHabitacion AND
				(:fecha >= fechaEntrada AND :fecha < fechaSalida)";



        $stmt = $db->prepare($sql);

        $stmt->bindValue(':idHabitacion', $idHabitacion, SQLITE3_INTEGER);
        $stmt->bindValue(':fecha', $fechaAuxTexto, SQLITE3_TEXT);
        $resultado = $stmt->execute();

        $listaHabitacionesDisponibles = myDB::obtenerListaHabitacionesDisponibles($fechaAuxTexto, "2250-01-01 00:00:00", $tipoHabitacion);
        if (empty($listaHabitacionesDisponibles)) {
            return $listaHabitacionesDisponibles;
        }

        $primerRegistroAceptable = $listaHabitacionesDisponibles[0];

        $listaIngresos = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {

            $data = [
                "id_rentaHabitacion" => $fila["id"],
                "idNuevaHabitacion" => $primerRegistroAceptable[1],
                "nota" => "Los huespedes de esta habitacion fueron cambiados desde otra habitacion del mismo tipo por mantenimiento"
            ];

            myDB::actualizarHabitacionRenta($data);
            $listaIngresos[] = $fila;
        }

        return $primerRegistroAceptable;
    }

    public static function obtenerDataMantenimiento($id)
    {

        $db = self::getInstance();

        $sql = "SELECT m.id, h.nombre, t.nombre as tipo, m.fecha_inicio, m.fecha_final, m.descripcion  
                FROM mantenimiento as m
                INNER JOIN habitaciones as h on m.id_habitacion=h.id
                INNER join tipo_habitacion as t on t.id = h.id_tipoHabitacion
                WHERE m.id=:id
                ORDER BY m.id DESC
                ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue("id", $id, SQLITE3_INTEGER);
        $resultado = $stmt->execute();

        $dataMantenimiento = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            return $fila;
        }
    }


    public static function finalizarMantenimiento($id)
    {
        $db = self::getInstance();
        $fechaActual = new DateTime();
        $fechaActual = $fechaActual->format("Y-m-d");

        $sql = "UPDATE mantenimiento SET 
                fecha_final =:fecha
                WHERE id =:id
                ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
        $stmt->bindValue(":fecha", $fechaActual, SQLITE3_TEXT);
        $resultado = $stmt->execute();
        return true;
    }

    public static function obtenerIdMantenimiento($numHab)
    {
        $db = self::getInstance();


        $sql = "SELECT id from habitaciones
        where nombre = :nombre
                ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":nombre", $numHab, SQLITE3_TEXT);
        $resultado = $stmt->execute();

        $dataMantenimiento = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $dataMantenimiento = $fila;
        }

        $idHab = $dataMantenimiento["id"];

        // return $idHab;

        $sql = "SELECT id from mantenimiento
        where id_habitacion = :id
                ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $idHab, SQLITE3_INTEGER);
        $resultado = $stmt->execute();

        $dataMantenimiento = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            return $fila["id"];
        }
    }


    public static function obtenerListaPisos()
    {
        $db = self::getInstance();


        $sql = "SELECT * from pisos";

        $stmt = $db->prepare($sql);

        $resultado = $stmt->execute();

        $listaPisos = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $listaPisos[] = $fila;
        }


        return $listaPisos;
    }


    public static function obtenerTiposHabitaciones()
    {
        $db = self::getInstance();


        $sql = "SELECT * from tipo_habitacion";

        $stmt = $db->prepare($sql);

        $resultado = $stmt->execute();

        $tipos = [];
        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $tipos[] = $fila;
        }


        return $tipos;
    }

    public static function registrarNuevaHabitacion($data)
    {
        $van = true;
        $db = self::getInstance();
        $sql = ' INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES (:nombre, :tipo);';


        $stmt = $db->prepare($sql);
        $stmt->bindValue(':nombre', $data["nombre"],  SQLITE3_TEXT);
        $stmt->bindValue(':tipo', $data["tipo"], SQLITE3_INTEGER);



        $resultado = $stmt->execute();

        if (!$resultado) $van = false;


        $idHabitacion =  $db->lastInsertRowID();


        $sql = "INSERT INTO posicionHabitaciones 
        (id_habitacion,  posicion_x,  posicion_y, piso)
        VALUES (:idHabitacion, :positionX , :positionY, :piso)";


        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idHabitacion', $idHabitacion,  SQLITE3_TEXT);
        $stmt->bindValue(':positionX', $data["positionX"], SQLITE3_INTEGER);
        $stmt->bindValue(':positionY', $data["positionY"], SQLITE3_INTEGER);
        $stmt->bindValue(':piso', $data["piso"], SQLITE3_INTEGER);



        $resultado = $stmt->execute();

        if (!$resultado) $van = false;





        return $van;
    }

    public static function actualizarPrecioHabitacion($data)
    {
        $db = self::getInstance();
        $fechaActual = new DateTime();
        $fechaActual = $fechaActual->format("Y-m-d");

        $sql = "UPDATE precios SET 
                cantidad =:cantidad
                WHERE  IdTipoHabitacion= :tipo_habitacion
                ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":tipo_habitacion", $data["tipo_habitacion"], SQLITE3_INTEGER);
        $stmt->bindValue(":cantidad", $data["nuevo_precio"], SQLITE3_FLOAT);
        $resultado = $stmt->execute();
        return true;
    }

    public static function obtenerDatosHabitacion($id)
    {
        $db = self::getInstance();

        $sql = "SELECT * FROM habitaciones 
            
                WHERE id=:id
                ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
        $resultado = $stmt->execute();


        while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
            return $fila;
        }
    }

    public static function actualizarDatosHabitaciones($data)
    {
        $van = true;
        $db = self::getInstance();
        $sql = 'UPDATE habitaciones 
        SET nombre=:nombre,
            id_tipoHabitacion=:tipo 
        WHERE id=:id
        ';


        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $data["id"],  SQLITE3_TEXT);
        $stmt->bindValue(':nombre', $data["nombre"],  SQLITE3_TEXT);
        $stmt->bindValue(':tipo', $data["tipo"], SQLITE3_INTEGER);



        $resultado = $stmt->execute();

        if (!$resultado) $van = false;




        $sql = "UPDATE posicionHabitaciones SET 
          posicion_x=:positionX,  
          posicion_y=:positionY, 
          piso=:piso
          where id_habitacion= :idHabitacion
        ";


        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idHabitacion', $data["id"],  SQLITE3_TEXT);
        $stmt->bindValue(':positionX', $data["positionX"], SQLITE3_INTEGER);
        $stmt->bindValue(':positionY', $data["positionY"], SQLITE3_INTEGER);
        $stmt->bindValue(':piso', $data["piso"], SQLITE3_INTEGER);



        $resultado = $stmt->execute();

        if (!$resultado) $van = false;





        return $van;
    }

    public static function obtenerLista($semana) {}

    public static function obtenerEstadoSemana($semana)
    {
        // 

        // return $semana;
        $nuevaSemana = [];


        foreach ($semana as $dia) {
            $fechaAux = DateTime::createFromFormat('d/m/Y', $dia);
            // $nuevaSemana[]=$fechaAux->format("Y-m-d");
            $text = $fechaAux->format("Y-m-d 00:00:00");
            $nuevaSemana[] = myDB::obtenerHabitacionesDisponiblesSegunFecha($text);
        }



        // foreach($semana as $dia){}



        return [$nuevaSemana, "otroDato"];
    }
    // Método para cerrar la conexión manualmente si es necesario
    public static function closeConnection()
    {
        if (self::$instance !== null) {
            self::$instance->close();
            self::$instance = null;
        }
    }
}
