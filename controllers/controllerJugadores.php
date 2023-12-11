<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../models/jugadoresModel.php');

function getAllPlayers() {
    $jugadorModel = new Jugador();
    $jugadores = $jugadorModel->getAllPlayers();
    return json_encode($jugadores);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $jugadorModel = new Jugador();
    $jugadorModel->deleteById($id);
    
    header("Location: ../views/dashboard.php?deletePlayer=1");
    exit();
}


