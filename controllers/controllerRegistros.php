<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../models/registrosModel.php');


if (isset($_POST['action']) && $_POST['action'] === 'add' && isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $tipoRegistro = isset($_POST['tipoRegistro']) ? $_POST['tipoRegistro'] : 0;
    date_default_timezone_set('America/Costa_Rica');
    $horaRegistro = isset($_POST['horaRegistro']) ? $_POST['horaRegistro'] : date('Y-m-d H:i:s');

    $registroModel = new Registros();
    $registroModel->setIdUsuario($id_usuario);
    $registroModel->setTipo($tipoRegistro);
    $registroModel->setHora($horaRegistro);

    if($tipoRegistro == 0){
        header("Location: ../views/dashboard.php?noTypeAvailable=0");
        exit();
    }

    if (empty($id_usuario)) {
        header("Location: ../views/dashboard.php?noSession=1");
        exit();
    }else{
        if ($registroModel->addRecordByIdUser()) {
            header("Location: ../views/dashboard.php?addRecord=1");
            exit();
        } else {
            header("Location: ../views/dashboard.php?addRecord=0");
            exit();
        }
    }   
}

function getAllTimesByUserId($idUsuario) {
    $recordModel = new Registros();
    $recordModel->setIdUsuario($idUsuario);
    $record = $recordModel->getAllTimesByUserId();
    return json_encode($record);
}



