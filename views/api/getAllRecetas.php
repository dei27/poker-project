<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../../controllers/controllerRecetas.php');

// Permitir solicitudes desde cualquier origen (cambia '*' a tu dominio local en producción)
header('Access-Control-Allow-Origin: *');

// Permitir métodos GET, POST y OPTIONS
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Permitir el tipo de contenido JSON
header('Content-Type: application/json');


$recetas = json_decode(getAllRecetasWeb(), true);

// Imprimir el JSON
echo json_encode($recetas, JSON_PRETTY_PRINT);


exit();

