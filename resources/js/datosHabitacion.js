const boton_elimina_registro = document.getElementById("boton_eliminar_registro");


const boton_editar_cliente = document.getElementById("boton_activar_edicion_cliente");
const boton_enviar_cliente = document.getElementById("boton_editar_cliente");


const nombre_cliente = document.getElementById("cliente_nombre");
const cedula_cliente = document.getElementById("cliente_cedula");
const telefono_cliente = document.getElementById("cliente_telefono");
const empresa_cliente = document.getElementById("cliente_empresa");
const ciudad_cliente = document.getElementById("cliente_ciudad");

let nombre_cliente_aux;
let cedula_cliente_aux;
let telefono_cliente_aux;
let empresa_cliente_aux;
let ciudad_cliente_aux;



const boton_editar_numeroHabitacion = document.getElementById("boton_activar_edicion_numHabitacion");

const select_numeroHabitacion = document.getElementById("select_habitacion_disponible");
const boton_enviar_numeroHabitacion = document.getElementById("boton_cambiar_habitacion");



const boton_editar_plazo = document.getElementById("boton_activar_edicion_fecha");

const boton_enviar_plazo = document.getElementById("boton_editar_fecha");

const fecha_entrada = document.getElementById("fecha_entrada");
const fecha_salida = document.getElementById("fecha_salida");


let fecha_entrada_aux;
let fecha_salida_aux;




const boton_editar_pago = document.getElementById("boton_activar_edicion_pago");
const boton_enviar_pago = document.getElementById("boton_editar_pago");


const tipo_pago = document.getElementById("pago_tipo");
const tipo_pago_seleccion = document.getElementById("pago_tipo_seleccion");
const ref = document.getElementById("referenciaPago");
const cantidad_pago = document.getElementById("pago_cantidad");

// let tipo_pago_aux;
let ref_pago_aux;
let cantidad_pago_aux;




// FUNCIONES

function comprobarFechas() {
    const fechaEntrada = new Date(fecha_entrada.value);
    const fechaSalida = new Date(fecha_salida.value);
    return fechaEntrada < fechaSalida;
}

boton_editar_cliente.addEventListener("click", function () {

    if (nombre_cliente.readOnly == true) {
        nombre_cliente.readOnly = false;
        cedula_cliente.readOnly = false;
        telefono_cliente.readOnly = false;
        empresa_cliente.readOnly = false;
        ciudad_cliente.readOnly = false;

        nombre_cliente_aux = nombre_cliente.value;
        cedula_cliente_aux = cedula_cliente.value;
        telefono_cliente_aux = telefono_cliente.value;
        empresa_cliente_aux = empresa_cliente.value;
        ciudad_cliente_aux = ciudad_cliente.value;

        boton_enviar_cliente.hidden = false;
        boton_editar_cliente.innerHTML = "Cancelar";
        cambiarElemento(boton_editar_cliente, true);
    } else {
        nombre_cliente.readOnly = true;
        cedula_cliente.readOnly = true;
        telefono_cliente.readOnly = true;
        empresa_cliente.readOnly = true;
        ciudad_cliente.readOnly = true;

        nombre_cliente.value = nombre_cliente_aux;
        cedula_cliente.value = cedula_cliente_aux
        telefono_cliente.value = telefono_cliente_aux;
        empresa_cliente.value = empresa_cliente_aux;
        ciudad_cliente.value = ciudad_cliente_aux;

        boton_enviar_cliente.hidden = true;
        boton_editar_cliente.innerHTML = "Editar Cliente";
        cambiarElemento(boton_editar_cliente);

    }
});

boton_editar_plazo.addEventListener("click", function () {

    if (fecha_entrada.readOnly == true) {
        fecha_entrada.readOnly = false;
        fecha_salida.readOnly = false;

        fecha_entrada_aux = fecha_entrada.value;
        fecha_salida_aux = fecha_salida.value;


        boton_enviar_plazo.hidden = false;
        boton_editar_plazo.innerHTML = "Cancelar";
        cambiarElemento(boton_editar_plazo, true);

    } else {
        fecha_entrada.readOnly = true;
        fecha_salida.readOnly = true;


        fecha_entrada.value = fecha_entrada_aux;
        fecha_salida.value = fecha_salida_aux;

        boton_enviar_plazo.hidden = true;
        boton_editar_plazo.innerHTML = "Editar Pago";
        cambiarElemento(boton_editar_plazo);
    }
});





boton_editar_numeroHabitacion.addEventListener("click", function () {

    if (boton_enviar_numeroHabitacion.style.display == "none") {
        boton_enviar_numeroHabitacion.style.display = "inline";
        select_numeroHabitacion.style.display = "inline";

        boton_editar_numeroHabitacion.innerHTML = "Cancelar";
        cambiarElemento(boton_editar_numeroHabitacion, true);

    } else {

        boton_enviar_numeroHabitacion.style.display = "none";
        select_numeroHabitacion.style.display = "none";
        boton_editar_numeroHabitacion.innerHTML = "Cambiar de habitacion";
        cambiarElemento(boton_editar_numeroHabitacion);

    }
});

function cambiarElemento(element, van = false) {
    if (van) {
        element.classList.add("info-hab-btn-cancelar");
        element.classList.remove("info-hab-btn-edit");
    }
    else {
        element.classList.add("info-hab-btn-edit");
        element.classList.remove("info-hab-btn-cancelar");
    }

}





const pago = document.getElementById("contenedorPago");
let indicePago = 0;
const contenedorDeslizante = document.getElementById("contenedorDeslizante");

const botonAtras = document.getElementById("botonAtras");
const botonSiguiente = document.getElementById("botonSiguiente");

const pagosAux = contenedorDeslizante.querySelectorAll('.columna-pago');

let first = true;

pagosAux.forEach(pago => {

    if (first) {
        first = false;
    }
    else {
        limpiarElementosId(pago);
    }
});

ocultarPagos(pagosAux, indicePago);

if (pagosAux.length > 1) {
    botonAtras.hidden = false;
    botonSiguiente.hidden = false;
}



function ocultarPagos(listaPagos, number) {

    listaPagos.forEach(element => {
        element.setAttribute("hidden", true);
    });


    listaPagos[number].hidden = false;
}

function modificarPagoMostrado(number) {
    const listaPagos = contenedorDeslizante.querySelectorAll('.columna-pago');

    aux = indicePago + number;

    if (aux == listaPagos.length || aux < 0) {
        return;
    }
    indicePago = aux;
    ocultarPagos(listaPagos, indicePago);
}

function limpiarElementosId(contenedorPago) {
    const pagoRef = contenedorPago.getElementsByClassName("info-hab-input-referncia")[0];
    const pagoCantidad = contenedorPago.getElementsByClassName("info-hab-input-cantidad")[0];
    const pagoTipo = contenedorPago.getElementsByClassName("info-hab-input-tipo-text")[0];
    const pagoTipoSelect = contenedorPago.getElementsByClassName("info-hab-input-tipo")[0];

    pagoRef.id = "";
    pagoCantidad.id = "";
    if (pagoTipo) {
        pagoTipo.id = "";
    }

    pagoTipoSelect.id = "";
}

function agregarPago() {

    const listaPagos = contenedorDeslizante.querySelectorAll('.columna-pago');
    // 
    if (indicePago == 0) {
        botonAtras.hidden = false;
        botonSiguiente.hidden = false;
    }

    if (listaPagos.length == 4) {
        return;
    }
    indicePago = listaPagos.length;



    // console.log(contenedorDeslizante);
    // console.log(indicePago);
    const pagoNuevo = pago.cloneNode(true);


    limpiarElementosId(pagoNuevo);

    const pagoRef = pagoNuevo.getElementsByClassName("info-hab-input-referncia")[0];
    const pagoCantidad = pagoNuevo.getElementsByClassName("info-hab-input-cantidad")[0];
    const pagoTipo = pagoNuevo.getElementsByClassName("info-hab-input-tipo-text")[0];
    const pagoTipoSelect = pagoNuevo.getElementsByClassName("info-hab-input-tipo")[0];
    const tituloPagoBorrar = pagoNuevo.getElementsByClassName("info-hab-text-const")[0];
    const titulo = pagoNuevo.getElementsByClassName("info-hab-card-header")[0];

    // tituloPagoBorrar.remove();
    pagoRef.value = "";
    pagoCantidad.value = 0;
    if(pagoTipo){
        pagoTipo.value = "";
    };
    pagoTipoSelect.value = "";
    titulo.innerHTML = "REGISTRO PAGO " + (listaPagos.length + 1);

    pagoNuevo.setAttribute("nuevo", true);
    contenedorDeslizante.appendChild(pagoNuevo);


    const listaPagosNueva = contenedorDeslizante.querySelectorAll('.columna-pago');

    if (pagoCantidad.readOnly == true) {
        cambiarElementosPago();
    }

    ocultarPagos(listaPagosNueva, (indicePago));




}

function cambiarElementosPago() {
    const listaPagosAux = contenedorDeslizante.querySelectorAll('.columna-pago');

    listaPagosAux.forEach(contenedorPago => {


        const pagoRef = contenedorPago.getElementsByClassName("info-hab-input-referncia")[0];
        const pagoCantidad = contenedorPago.getElementsByClassName("info-hab-input-cantidad")[0];
        const pagoTipo = contenedorPago.getElementsByClassName("info-hab-input-tipo-text")[0];
        const pagoTipoSelect = contenedorPago.getElementsByClassName("info-hab-input-tipo")[0];

        //si el elemento no tiene el atributo nuevo aplica normalmente el reseteo de datos
        if (!contenedorPago.hasAttribute("nuevo")) {
            if (pagoRef.readOnly == true) {
                pagoRef.setAttribute("base", pagoRef.value);
                pagoCantidad.setAttribute("base", pagoCantidad.value);
                pagoTipo.setAttribute("base", pagoTipo.value);
                pagoTipoSelect.setAttribute("base", pagoTipoSelect.value);

                // cambiarBoton
                boton_enviar_pago.hidden = false;
                boton_editar_pago.innerHTML = "Cancelar";
                cambiarElemento(boton_editar_pago, true);
            } else {
                pagoRef.value = pagoRef.getAttribute("base");
                pagoCantidad.value = pagoCantidad.getAttribute("base");
                pagoTipo.value = pagoTipo.getAttribute("base");
                pagoTipoSelect.value = pagoTipoSelect.getAttribute("base");

                // cambiarBoton
                boton_enviar_pago.hidden = true;
                boton_editar_pago.innerHTML = "Editar Pago";
                cambiarElemento(boton_editar_pago);
            }
        }
        else {
            //condicional para que los pagos nuevos muestren la opcion de pago al cancelar la edicion
            if (pagoTipoSelect.children[pagoTipoSelect.value]) {
                const auxElement = pagoTipoSelect.children[(pagoTipoSelect.value - 1)];
                pagoTipo.value = auxElement.textContent;
            }
            //  pagoTipo.value=;

        }


        pagoRef.readOnly = !pagoRef.readOnly;
        pagoCantidad.readOnly = !pagoCantidad.readOnly;
        pagoTipo.hidden = !pagoCantidad.readOnly;
        pagoTipoSelect.hidden = !pagoTipoSelect.hidden;



    });



}
if (boton_editar_pago) {
    boton_editar_pago.addEventListener("click", function () {
        cambiarElementosPago();

    });
}

