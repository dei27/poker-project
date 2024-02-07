<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/lugaresModel.php');

function getAllPlaces() {
    $lugarModel = new Lugar();
    $lugares = $lugarModel->getAllPlaces();
    return json_encode($lugares);
}

function getPlaceById($id) {
    $lugarModel = new Lugar();
    $lugares = $lugarModel->getPlaceById($id);
    return json_encode($lugares);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // $ciudadModel = new Ciudades();
    // $ciudadModel->deleteById($id);
    
    header("Location: ../views/dashboard.php?deleteCity=1");
    exit();
}


