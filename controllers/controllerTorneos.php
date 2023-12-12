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
    
    header("Location: ../views/torneos.php?delete=1");
    exit();
}

if (isset($_POST['id'], $_POST['action']) && $_POST['action'] === 'edit') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nombre = isset($_POST['torneoNombre']) ? htmlspecialchars($_POST['torneoNombre'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $fecha = isset($_POST['torneoFecha']) ? htmlspecialchars($_POST['torneoFecha'], ENT_QUOTES, 'UTF-8') : date('Y-m-d');
    $hora = isset($_POST['torneoHora']) ? htmlspecialchars($_POST['torneoHora'], ENT_QUOTES, 'UTF-8') : date('H:i:s');

    // Ajustes para entrada, recompra, addon
    $entrada = isset($_POST['torneoEntrada']) ? filter_input(INPUT_POST, 'torneoEntrada', FILTER_SANITIZE_NUMBER_INT) : 0;
    $recompra = isset($_POST['torneoRecompra']) ? filter_input(INPUT_POST, 'torneoRecompra', FILTER_SANITIZE_NUMBER_INT) : 0;
    $addon = isset($_POST['torneoAddOn']) ? filter_input(INPUT_POST, 'torneoAddOn', FILTER_SANITIZE_NUMBER_INT) : 0;

    if ($id !== null && !empty($id)) {
        $torneoModel = new Torneo();
        $torneoModel->setId($id);
        $torneoModel->setNombre($nombre);
        $torneoModel->setFecha($fecha);
        $torneoModel->setHora($hora);
        $torneoModel->setEntrada($entrada);
        $torneoModel->setRecompra($recompra);
        $torneoModel->setAddOn($addon);

        $torneoModel->updateTorneo();

        header("Location: ../views/torneos.php?updatedTournament=1");
        exit();
    } else {        header("Location: ../views/torneos.php?updatedTournament=0");
        exit();
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $nombre = isset($_POST['torneoNombreAdd']) ? htmlspecialchars($_POST['torneoNombreAdd'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $lugar = isset($_POST['lugarAdd']) ? htmlspecialchars($_POST['lugarAdd'], ENT_QUOTES, 'UTF-8') : "1";
    $fecha = isset($_POST['torneoFechaAdd']) ? htmlspecialchars($_POST['torneoFechaAdd'], ENT_QUOTES, 'UTF-8') : date('Y-m-d');
    $hora = isset($_POST['torneoHoraAdd']) ? htmlspecialchars($_POST['torneoHoraAdd'], ENT_QUOTES, 'UTF-8') : date('H:i:s');

    // Ajustes para entrada, recompra, addon
    $entrada = isset($_POST['torneoEntradaAdd']) ? filter_input(INPUT_POST, 'torneoEntradaAdd', FILTER_SANITIZE_NUMBER_INT) : 0;
    $recompra = isset($_POST['torneoRecompraAdd']) ? filter_input(INPUT_POST, 'torneoRecompraAdd', FILTER_SANITIZE_NUMBER_INT) : 0;
    $addon = isset($_POST['torneoAddOnAdd']) ? filter_input(INPUT_POST, 'torneoAddOnAdd', FILTER_SANITIZE_NUMBER_INT) : 0;

    if ($nombre !== null && !empty($nombre)) {
        $torneoModel = new Torneo();
        $torneoModel->setNombre($nombre);
        $torneoModel->setLugar($lugar);
        $torneoModel->setFecha($fecha);
        $torneoModel->setHora($hora);
        $torneoModel->setEntrada($entrada);
        $torneoModel->setRecompra($recompra);
        $torneoModel->setAddOn($addon);

        $torneoModel->insertTournament();

        header("Location: ../views/torneos.php?insertedTournament=1");
        exit();
    } else {
        header("Location: ../views/torneos.php?insertedTournament=0");
        exit();
    }
}







