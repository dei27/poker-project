<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../models/torneoModel.php');

function getAll() {
    $torneoModel = new Torneo();
    $torneos = $torneoModel->getAll();
    return json_encode($torneos);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $torneoModel = new Torneo();
    $torneoModel->deleteById($id);
    
    header("Location: ../views/dashboardTorneos.php?delete=1");
    exit();
}


