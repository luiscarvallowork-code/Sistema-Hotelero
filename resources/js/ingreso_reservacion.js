
// constantes de elemento
const input_fechaSalida = document.getElementById("input_fechaSalida");
const input_fechaEntrada = document.getElementById("input_fechaEntrada");
const input_tipoHabitacion = document.getElementById("input_tipoHabitacion");



const tasa_dia = document.getElementById("tasa_del_dia");
const fecha_tasa = document.getElementById("fecha_tasa");




const cartel = document.getElementById("mensajeFlotante");
const mensajeError = document.getElementById("mensajeFlotante-mensajeError");


const selectorHabitaciones = document.getElementById("habitaciones");
selectorHabitaciones.style.display = "none";

const input_monto = document.getElementById("input_monto");




const input_montoBs = document.getElementById("montoBs");
const button_precioEspecial = document.getElementById("button_precioEspecial");

input_monto.addEventListener('input', function (event) {
    tasaBs = input_monto.value * tasaDia;
    input_montoBs.value = tasaBs.toFixed(2);
});

input_montoBs.addEventListener('input', function (event) {
    cantidad = input_montoBs.value / tasaDia;
    input_monto.value = cantidad.toFixed(2);
});

let listaPagos=[];

const regex = /^\d{4}-\d{2}-\d{2}$/;



// variables glovales

let tasaDia = 0;


// FUNCIONES



async function inicializar() {
    tasaActual = await respuesta_servidor.consultaServidor("obtenerTasaBCV", body = ["hola"]);
    fechaTasaActual = new Date(tasaActual["fecha"]);
    let textoAuxiliar = " " + tasaActual["tasa"] + "&nbsp;BS";
    tasa_dia.innerHTML = textoAuxiliar;
    tasaDia = tasaActual["tasa"];
    fecha_tasa.innerHTML = "TASA BCV FECHA:   " +
        fechaTasaActual.getDate() + "/" + (fechaTasaActual.getMonth() + 1) + "/" + fechaTasaActual.getFullYear();

}

inicializar();


function comprobarFomulario() {
    // codigo comprobacion 
    return validacion //true o false
}

function mostrarMensaje(mensaje) {
    cartel.style.display = "flex";
    mensajeError.textContent = mensaje
}

function cerrarVentana() {
    cartel.style.display = "none";
}

function cargarOpcionesHabitacion(listaHabitaciones) {
    selectorHabitaciones.innerHTML = "";
    first = true;
    listaHabitaciones.forEach(numeroHab => {
        const nuevaOpcion = document.createElement("option");

        nuevaOpcion.value = numeroHab[0];
        nuevaOpcion.textContent = numeroHab[0];
        if (first == true) {
            first = false;
            nuevaOpcion.selected = true;
        }
        selectorHabitaciones.appendChild(nuevaOpcion);
    });
}







async function consultarHabitacionesDisponibles() {
    if (!regex.test(input_fechaEntrada.value) || !regex.test(input_fechaSalida.value)) {
        // mostrarMensaje("Formato de fecha invalido");
        return
    }

    const d1 = new Date(input_fechaEntrada.value);
    const d2 = new Date(input_fechaSalida.value);

    if (d2 <= d1) {

        return
    }

    const datosEnvio = {
        fechaEntrada: input_fechaEntrada.value,
        fechaSalida: input_fechaSalida.value,
        tipoHabitacion: input_tipoHabitacion.value,
    }

    const data = await respuesta_servidor.consultaServidor("obtenerListaHabitacionesDisponibles", datosEnvio);


    if (selectorHabitaciones.style.display == "none") {
        selectorHabitaciones.style.display = "inline";
    }

    // console.log(data);

    cargarOpcionesHabitacion(data)
    cargarPrecio();
}




async function cargarPrecio() {
    const datosEnvio = {
        fechaEntrada: input_fechaEntrada.value,
        fechaSalida: input_fechaSalida.value,
        nombre: selectorHabitaciones.value,
    }
    const data = await respuesta_servidor.consultaServidor("obtenerPrecioHabitacion", datosEnvio);
    const valor = selectorHabitaciones.value;
    let monto;
    tasaBs = data * tasaDia;
    input_montoBs.value = tasaBs.toFixed(2);
    input_monto.value = data;
}

function liberarPrecio() {

    console.log("hola");
    if (input_monto.value == "") {
        return;
    }
    if (button_precioEspecial.value == "ingresar") {
        button_precioEspecial.value = "regresar";
        button_precioEspecial.textContent = "Cargar Precio Estandar";
        input_monto.removeAttribute("readonly");
        input_montoBs.removeAttribute("readonly");


    } else {
        button_precioEspecial.value = "ingresar";
        button_precioEspecial.textContent = "ingresar Precio Especial";
        input_monto.setAttribute("readonly", 'true')
        input_montoBs.setAttribute("readonly", 'true')
        cargarPrecio();
    }
}



//inicializar 



// EVENTOS
input_fechaSalida.addEventListener("input", function (event) {
    consultarHabitacionesDisponibles();
});

input_fechaEntrada.addEventListener("input", function (event) {
    consultarHabitacionesDisponibles();
});

input_tipoHabitacion.addEventListener("change", function (event) {
    consultarHabitacionesDisponibles();
});

selectorHabitaciones.addEventListener("change", function (event) {
    cargarPrecio();
});


document.addEventListener('DOMContentLoaded', function () {
    // 1. Obtener referencias a los elementos
    const radioIngreso = document.getElementById('notif_si');
    const radioReservacion = document.getElementById('notif_no');
    const inputOculto = document.getElementById('tipoFormularioEnviado');

    // Función que actualiza el valor del input oculto
    function actualizarTipoFormulario() {
        console.log(inputOculto.value);
        if (inputOculto.value == "1") {
            inputOculto.value = "0";
        } else {
            // Si el radio de RESERVACIÓN (valor "0") está marcado
            inputOculto.value = "1"; // Debería ser '0'
        }
    }

    // 2. Asignar el evento 'change' a ambos radios
    // El evento 'change' se dispara cuando el valor del elemento es modificado.

    radioIngreso.addEventListener('change', actualizarTipoFormulario);
    radioReservacion.addEventListener('change', actualizarTipoFormulario);

    // Opcional: Ejecutar la función una vez al cargar la página para asegurar que
    // el valor inicial del input oculto (si no tiene valor por defecto) sea correcto.
    // Aunque en tu HTML ya tiene value="1", esto es una buena práctica.
    // actualizarTipoFormulario(); 



    window.addEventListener("libreriasListas", () => {
        // respuesta_servidor.fechaFormato(["input_fechaEntrada", "input_fechaSalida"]);
    });
    // 
});