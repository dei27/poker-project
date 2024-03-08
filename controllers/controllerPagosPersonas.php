<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../models/PagosPersona.php');

function getAllPagosByPersonas($metodoPago) {
    $pagos = new PagosPersona();
    $pagos->setMetodoPago($metodoPago);
    $pagosPersona = $pagos->getAllPagosPersona();
    return json_encode($pagosPersona);
}

function getAllPagos() {
    $pagoPersona = new PagosPersona();
    $pagos = $pagoPersona->getAllPagos();
    return json_encode($pagos);
}



