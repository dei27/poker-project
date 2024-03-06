<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/tiposFaltasHorariosModel.php');

function getAllTiposFaltasHorarios() {
    $tipoFalta = new TiposFaltasHorarios();
    $tiposFaltas = $tipoFalta->getAllTiposFaltasHorarios();
    return json_encode($tiposFaltas);
}








