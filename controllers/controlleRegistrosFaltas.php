<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/registrosHorariosFaltaModel.php');


function getAllRegistrosFaltasHorarios() {
    $registroFaltaHorario = new RegistrosFaltasHorarios();
    $registrosFaltasHorarios = $registroFaltaHorario->getAllRegistrosFaltasHorarios();
    return json_encode($registrosFaltasHorarios);
}

if (isset($_POST['action']) && $_POST['action'] === 'addRegistroFalta') {
    $tipoRegistroFalta = isset($_POST['tipoRegistroFalta']) ? htmlspecialchars($_POST['tipoRegistroFalta'], ENT_QUOTES, 'UTF-8') : null;
    $usuarioRegistroFalta = isset($_POST['usuarioRegistroFalta']) ? htmlspecialchars($_POST['usuarioRegistroFalta'], ENT_QUOTES, 'UTF-8') : null;
    $fechaInicioRegistroFalta = isset($_POST['fechaInicioRegistroFalta']) ? htmlspecialchars($_POST['fechaInicioRegistroFalta'], ENT_QUOTES, 'UTF-8') : null;
    $fechaFinRegistroFalta = isset($_POST['fechaFinRegistroFalta']) ? htmlspecialchars($_POST['fechaFinRegistroFalta'], ENT_QUOTES, 'UTF-8') : null;
    $notasRegistroFalta = isset($_POST['notasRegistroFalta']) ? htmlspecialchars($_POST['notasRegistroFalta'], ENT_QUOTES, 'UTF-8') : null;

    $nuevoRegistroFalta = new RegistrosFaltasHorarios();
    $nuevoRegistroFalta->setIdTipoFalta($tipoRegistroFalta);
    $nuevoRegistroFalta->setIdUsuario($usuarioRegistroFalta);
    $nuevoRegistroFalta->setFechaInicio($fechaInicioRegistroFalta);
    $nuevoRegistroFalta->setFechaFin($fechaFinRegistroFalta);
    $nuevoRegistroFalta->setNotas($notasRegistroFalta);

    $insertedRegistro = $nuevoRegistroFalta->newRegistroFalta();    

    if($insertedRegistro){
        header("Location: ../views/actualizarHorarios.php?insertedRegistro=1");
        exit();
    }else{
        header("Location: ../views/actualizarHorarios.php?insertedRegistro=0");
        exit();
    }

    
}







