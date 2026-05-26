<?php



$urlRouter="controllers/router.php?code=";


?>


<nav class="nav-sys-container">
    <ul class="nav-sys-list">
        <li class="containerLink"><a href="<?= $urlRouter."home"?>" class="nav-sys-link">Inicio</a></li>
        <li class="containerLink"><a href="<?= $urlRouter."ingresoHabitacion"?>" class="nav-sys-link">Ingreso Habitacion</a></li>
        <li class="containerLink"><a href="<?= $urlRouter."estadoHabitaciones"?>" class="nav-sys-link">Estado Habitaciones</a></li>
       
        
    
        <li class="nav-sys-dropdown">
            <a href="#" class="nav-sys-link nav-sys-trigger containerLink">Base de Datos ▾</a>
            <ul class="nav-sys-submenu ">
                <li><a href="<?= $urlRouter."listaReservacion"?>">Historial de  Reservaciones</a></li>
                <li><a href="<?= $urlRouter."listaIngresosHabitacion"?>">Historial de Rentas</a></li>
                <li><a href="<?= $urlRouter."listaClientes"?>">Lista de Clientes</a></li>
                <li><a href="<?= $urlRouter."listaHabitaciones"?>">Lista de Habitaciones</a></li>
                <li><a href="<?= $urlRouter."listaPagos"?>">Historial de Pagos</a></li>
                <li><a href="<?= $urlRouter."listaMantenimiento"?>">Lista de Reparaciones</a></li>
                <li><a href="<?= $urlRouter."listaTasas"?>">Historial tasa cambiaria</a></li>
            </ul>
        </li>

         <li class="containerLink"><a href="<?= $urlRouter."informe"?>" class="nav-sys-link">Informe</a></li>
        <li class="containerLink"><a href="<?= $urlRouter."configuraciones"?>" class="nav-sys-link">Configuraciones</a></li>
       
    </ul>
</nav>


