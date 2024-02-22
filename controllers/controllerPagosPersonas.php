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



