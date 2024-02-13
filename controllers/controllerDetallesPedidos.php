<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/DetallesPedidos.php');

function getAllDetalles($id_pedido) {
    $detallesPedidos = new DetallesPedidos();
    $detalles = $detallesPedidos->getAllDetallesPedidos($id_pedido);
    return json_encode($detalles);
}

function getAllDetallesMontos($id_pedido) {
    $detallesPedidos = new DetallesPedidos();
    $detalles = $detallesPedidos->getAllDetallesPedidosAndPrecios($id_pedido);
    return json_encode($detalles);
}

function getAllBebidasAndPlatillos() {
    $detallesPedidos = new DetallesPedidos();
    $detalles = $detallesPedidos->getAllBebidasAndPlatillos();
    return json_encode($detalles);
}












