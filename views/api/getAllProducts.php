<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../../controllers/controllerProducts.php');


$torneosData = getall();
$torneos = json_decode($torneosData, true);

// Establecer el encabezado para indicar que la salida es JSON
header('Content-Type: application/json');

// Imprimir el JSON directamente
echo json_encode($torneos, JSON_PRETTY_PRINT);

// Salir para evitar que se envíe contenido HTML después del JSON
exit();
?>
