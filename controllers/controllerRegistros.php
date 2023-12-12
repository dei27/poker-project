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

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) && isset($_GET['idTournament'])) {
    $id = $_GET['id'];
    $torneoId = $_GET['idTournament'];
    $registroModel = new Registros();
    $registroModel->deletePlayerRecordById($id, $torneoId );
    
    header("Location: ../views/torneos.php?deletePlayer=1");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['idPlayer']) && isset($_GET['idTournament'])) {
    $torneoId = $_GET['idTournament'];
    $jugadorId = $_GET['idPlayer'];

    $registroModel = new Registros();
    $registroModel->setTorneoId($torneoId);
    $registroModel->setJugadorId($jugadorId);

    if ($registroModel->addPlayerRecordById()) {
        header("Location: ../views/torneos.php?addPlayer=1");
        exit();
    } else {
        header("Location: ../views/torneos.php?addPlayer=0");
        exit();
    }
}



