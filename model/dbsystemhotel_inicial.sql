

-------------------------------------------------------------
-----------CODIGO PARA BORRAR LAS TABLAS---------------------
-------------------------------------------------------------

-- 1. Tablas que no son referencia de nadie (Hijas finales)
DROP TABLE IF EXISTS "reservacion";
DROP TABLE IF EXISTS "mantenimiento";
DROP TABLE IF EXISTS "posicionHabitaciones";
DROP TABLE IF EXISTS "tasas_cambio";
DROP TABLE IF EXISTS "precios";
DROP TABLE IF EXISTS "system_log";

-- 2. Tablas intermedias
DROP TABLE IF EXISTS "pagos";
DROP TABLE IF EXISTS "rentaHabitacion";


-- 3. Tablas maestras o principales (Padres)
DROP TABLE IF EXISTS "tipoPago";
DROP TABLE IF EXISTS "habitaciones";
DROP TABLE IF EXISTS "clientes";
DROP TABLE IF EXISTS "tiposTasa";
DROP TABLE IF EXISTS "monedas";
DROP TABLE IF EXISTS "tipo_habitacion";
DROP TABLE IF EXISTS "pisos";

-- ESTE ES UN CODIGO SQLLITE NO MYSQL

-- PRAGMA foreign_keys = ON;

CREATE TABLE "system_log"(
     "id" INTEGER PRIMARY KEY AUTOINCREMENT, 
     "fecha" DATE NOT NULL,
     "message"  TEXT NOT NULL
);

CREATE TABLE "tipo_habitacion"(
    "id" INTEGER PRIMARY KEY AUTOINCREMENT, 
    "nombre" TEXT NOT NULL,
    "descripcion" TEXT
);


CREATE TABLE "habitaciones"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "nombre" TEXT NOT NULL,
  "id_tipoHabitacion" INTEGER NOT NULL,

  FOREIGN key("id_tipoHabitacion")
  REFERENCES "tipo_habitacion"("id")

  ON DELETE CASCADE
  ON UPDATE CASCADE
);


CREATE TABLE "clientes" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "nombre" TEXT NOT NULL,
    "numeroTelefono" TEXT,
    "ci" TEXT UNIQUE, -- Cédula de Identidad debe ser única
    "ciudad" TEXT,
    -- 'empresa' es opcional (puede ser NULL)
    "empresa" TEXT
);

CREATE TABLE "monedas" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "nombre" TEXT NOT NULL UNIQUE,
    "codigo" TEXT NOT NULL UNIQUE, -- Código ISO de la moneda (USD, EUR, VES, etc.)
    "simbolo" TEXT NOT NULL UNIQUE, -- Código ISO de la moneda (USD, EUR, VES, etc.)
    -- Campo booleano para identificar la moneda base (1=True, 0=False)
    "base" INTEGER NOT NULL DEFAULT 0 
);

CREATE TABLE "tiposTasa" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "nombre" TEXT NOT NULL UNIQUE,
    "descripcion" TEXT
);

CREATE TABLE "tasas_cambio" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    -- id_moneda_base y id_moneda_convertida referencian la tabla "monedas"
    "id_moneda_base" INTEGER NOT NULL, 
    "id_moneda_convertida" INTEGER NOT NULL,
    -- id_tipoTasa referencia la tabla "tiposTasa"
    "id_tipoTasa" INTEGER NOT NULL, 
    
    "tasa" REAL NOT NULL, -- Uso de REAL para el valor decimal de la tasa
    "fecha" DATE NOT NULL,
    
    -- Definición de Llaves Foráneas
    FOREIGN KEY("id_moneda_base") 
        REFERENCES "monedas"("id")
        ON DELETE CASCADE,
        
    FOREIGN KEY("id_moneda_convertida") 
        REFERENCES "monedas"("id")
        ON DELETE CASCADE,

    FOREIGN KEY("id_tipoTasa") 
        REFERENCES "tiposTasa"("id")
        ON DELETE CASCADE

);

CREATE TABLE "precios" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "IdTipoHabitacion" INTEGER NOT NULL,
    "cantidad" REAL NOT NULL, -- Asumo que 'Cantidad' es el precio con decimales
    
    -- Definición de Llave Foránea (Asumiendo que existe la tabla "tipo_habitacion")
    FOREIGN KEY("IdTipoHabitacion") 
        REFERENCES "tipo_habitacion"("id")
        ON DELETE CASCADE
);


CREATE TABLE "tipoPago" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "nombre" TEXT NOT NULL,
    "moneda" INTEGER NOT NULL,        -- Llave foránea a la tabla 'monedas'
    FOREIGN KEY("moneda") 
        REFERENCES "monedas"("id")
        ON DELETE RESTRICT
);




CREATE TABLE "rentaHabitacion" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "idCliente" INTEGER NOT NULL,
    "idHabitacion" INTEGER NOT NULL,
    "fechaEntrada" DATETIME NOT NULL,
    "fechaSalida" DATETIME NOT NULL,
    "activo" INTEGER NOT NULL DEFAULT 1,  -- Booleano: 1 (True) para activo, 0 (False) para inactivo
    "nota" TEXT,
    
    FOREIGN KEY("idCliente") 
        REFERENCES "clientes"("id")
        ON DELETE CASCADE,

    FOREIGN KEY("idHabitacion") 
        REFERENCES "habitaciones"("id")
        ON DELETE CASCADE
        
 
);



CREATE TABLE "pagos" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "tipoPago" INTEGER NOT NULL,         -- LLAVE FORANEA
    "idRenta" INTEGER NOT NULL,         -- OTRA LLAVE FORANEA
    "cantidad" REAL NOT NULL,         -- Uso de REAL para el valor decimal
    "fecha" DATETIME NOT NULL,        -- Fecha y hora del pago
    "referencia" TEXT,                -- Puede ser NULL
    FOREIGN KEY("tipoPago") 
        REFERENCES "tipoPago"("id")
        ON DELETE RESTRICT,
      

    FOREIGN KEY("idRenta") 
        REFERENCES "rentaHabitacion"("id")
        ON DELETE RESTRICT
);



CREATE TABLE "reservacion" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "idRentaHabitacion" INTEGER, 
    -- Estado de la reserva: 'activa', 'caducada', 'completada'
    "estado" TEXT NOT NULL, 
    
    FOREIGN KEY("idRentaHabitacion") 
        REFERENCES "rentaHabitacion"("id")
        ON DELETE SET NULL 
);

CREATE TABLE "mantenimiento" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "id_habitacion" INTEGER NOT NULL,
    "fecha_inicio" DATE NOT NULL,
    "fecha_final" DATE NOT NULL,
    "descripcion" TEXT NOT NULL, 

    
    FOREIGN KEY("id_habitacion") 
        REFERENCES "habitaciones"("id")
        ON DELETE CASCADE,
        
    -- Restricción para asegurar que no haya dos estados diferentes para la misma habitación 
    -- en la misma fecha de inicio y final (si el periodo fuera estricto)
    UNIQUE ("id_habitacion", "fecha_inicio", "fecha_final")
);

CREATE TABLE "pisos"(
      "id" INTEGER PRIMARY KEY AUTOINCREMENT,
      "nombre"  TEXT NOT NULL, 
      "descripcion" TEXT

);


CREATE TABLE "posicionHabitaciones" (
    -- La llave primaria será la ID de la habitación, garantizando que cada habitación 
    -- solo tenga una posición.
    "id_habitacion" INTEGER PRIMARY KEY, 
    "posicion_x" INTEGER NOT NULL,
    "posicion_y" INTEGER NOT NULL,
    "piso" INTEGER NOT NULL,

    FOREIGN KEY("id_habitacion") 
        REFERENCES "habitaciones"("id")
        ON DELETE CASCADE
        
    FOREIGN KEY("piso") 
        REFERENCES "pisos"("id")
        ON DELETE CASCADE
);




-- INGRESO DE DATOS para hostal

-- monedas
INSERT INTO "monedas" ("nombre", "codigo", "simbolo", "base") 
        VALUES ("Dolar", "USD", "$", 1);
INSERT INTO "monedas" ("nombre", "codigo", "simbolo", "base") 
        VALUES ("Bolivares", "BS", "VES", 0);
--tipo de pago

INSERT INTO "tipoPago" ("nombre", "moneda") 
        VALUES ("BS", 2);
INSERT INTO "tipoPago" ("nombre", "moneda") 
        VALUES ("Pago Movil", 2);
INSERT INTO "tipoPago" ("nombre", "moneda") 
        VALUES ("Transferencia", 2);
INSERT INTO "tipoPago" ("nombre", "moneda") 
        VALUES ("Zelle", 1);
INSERT INTO "tipoPago" ("nombre", "moneda") 
        VALUES ("Divisas", 1);
--TIPO TASA

INSERT INTO "tiposTasa" ("nombre", "descripcion") 
        VALUES ("Dolar BCV", "Dolar oficial emitido por el banco central de Venezuela");
INSERT INTO "tiposTasa" ("nombre", "descripcion") 
        VALUES ("Dolar_Paralelo", "Dolar de venta en la calle");





-- tipos de habitaciones
INSERT INTO "tipo_habitacion" ("nombre", "descripcion") 
        VALUES ("Matrimonial", "Habitacion sencilla con una cama matrimonial");

INSERT INTO "tipo_habitacion" ("nombre", "descripcion") 
        VALUES ("Doble", "Habitacion  con dos camas matrimoniales");

INSERT INTO "tipo_habitacion" ("nombre", "descripcion") 
        VALUES ("Triple", "Habitacion con tres camas individuales");

INSERT INTO "tipo_habitacion" ("nombre", "descripcion") 
        VALUES ("Suit", "Habitacion  de lujo con mas espacio y un escritorio");

INSERT INTO "tipo_habitacion" ("nombre", "descripcion") 
        VALUES ("ApartaHotel", "Apartament pequeño, equipado con cocina y sala destar");

INSERT INTO "tipo_habitacion" ("nombre", "descripcion") 
        VALUES ("Sala de Conferencia", "Sala de conferencia con 10 sillas y un baño");


--Habitaciones

    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("101", 6);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("102", 1);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("103", 1);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("104", 1);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("105", 2);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("106", 2);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("107", 2);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("108", 1);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("109", 1);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("110", 1);    
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("111", 1);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("112", 1);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("113", 1);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("114", 2);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("115", 4);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("116", 3);
    INSERT INTO "habitaciones" ("nombre", "id_tipoHabitacion") 
        VALUES ("2", 5);

    -- PISOS


    INSERT INTO "pisos" ("nombre",  "descripcion")
    VALUES ("uno", "Primer piso. Habitaciones Hoteleras");

     INSERT INTO "pisos" ("nombre",  "descripcion")
    VALUES ("dos", "Segund piso. Aparta Hoteles");

--pocisiones de habitaciones

-- x es la final
-- y la colummna
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (6, 1,1, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (5, 2,1, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (4, 3,1, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (3, 4,1, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (2, 5,1, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (1, 6,1, 1);


INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y" , "piso")
    VALUES (11, 5,3, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y" , "piso")
    VALUES (10, 4,3, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (9, 3,3, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (8, 2,3, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (7, 1,3, 1);


INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (12, 1, 6, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (13, 2, 6, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (14, 3, 8, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (15, 2, 8, 1);
INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (16, 1, 8, 1);

    INSERT INTO "posicionHabitaciones" ( "id_habitacion",  "posicion_x",  "posicion_y", "piso")
    VALUES (17, 8, 8, 2);


--precios base

INSERT INTO precios (IdTipoHabitacion, cantidad) 
	VALUES (1, 10);
INSERT INTO precios (IdTipoHabitacion, cantidad) 
	VALUES (2, 20);
INSERT INTO precios (IdTipoHabitacion, cantidad) 
	VALUES (3, 30);
INSERT INTO precios (IdTipoHabitacion, cantidad) 
	VALUES (4, 40);
INSERT INTO precios (IdTipoHabitacion, cantidad) 
	VALUES (5, 50);
INSERT INTO precios (IdTipoHabitacion, cantidad) 
	VALUES (6, 60);


--monto de base de la tasa
    INSERT INTO "tasas_cambio" ("id_moneda_base", "id_moneda_convertida",  "id_tipoTasa", "tasa", "fecha") 
        VALUES (1, 2, 1, 250, "2020-01-01 00:00:00");

