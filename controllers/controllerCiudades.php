<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../models/ciudadesModel.php');

function getAllCities() {
    $ciudadModel = new Ciudades();
    $ciudades = $ciudadModel->getAllCities();
    return json_encode($ciudades);
}

function getCityById($id) {
    $ciudadModel = new Ciudades();
    $ciudades = $ciudadModel->getCityById($id);
    return json_encode($ciudades);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // $ciudadModel = new Ciudades();
    // $ciudadModel->deleteById($id);
    
    header("Location: ../views/dashboard.php?deleteCity=1");
    exit();
}


