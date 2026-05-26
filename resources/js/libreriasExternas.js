import flatpickr from "https://cdn.jsdelivr.net/npm/flatpickr/+esm";
// console.log("hola");
//   flatpickr("#input_fechaEntrada", {
//     dateFormat: "d/m/Y", 
//   });

window.flatpickr = flatpickr;

const evento = new CustomEvent("libreriasListas");
window.dispatchEvent(evento);