<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../models/registrosModel.php');

function getAllRecordsArray($torneoId) {
    $registroModel = new Registros();
    $registros = $registroModel->getAllRecords($torneoId);
    return json_encode($registros, true);
}


function countPlayers($idTorneo){
    $registroModel = new Registros();
    $totalJugadores = $registroModel->CountPlayersByIdTournament($idTorneo);
    return json_encode($totalJugadores);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $registroModel = new Registros();
    $registroModel->deletePlayerRecordById($id);
    
    header("Location: ../views/torneos.php?deletePlayer=1");
    exit();
}


