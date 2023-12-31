<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/UnidadMedidaModel.php');

function getAllUnidades() {
    $unidadModel = new UnidadMedida();
    $unidades = $unidadModel->getAllUnidadesMedidas();
    return json_encode($unidades);
}

if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $nombre = isset($_POST['nombreUnidadaMedida']) ? htmlspecialchars($_POST['nombreUnidadaMedida'], ENT_QUOTES, 'UTF-8') : "Sin nombre";

    if ($nombre !== null && !empty($nombre)) {
        $unidadModel = new UnidadMedida();
        $unidadModel->setNombre($nombre);
        $unidadModel->newUnidadMedida();

        header("Location: ../views/unidadesMedida.php?insertedUnidad=1");
        exit();
    } else {
        header("Location: ../views/unidadesMedida.php?Unidad=0");
        exit();
    }
}

function getNameUnidadMedida($id) {
    $unidadModel = new UnidadMedida();
    
    $unidad = $unidadModel->getNombreUnidadByIdUnidad($id);

    if ($unidad) {
        return json_encode(['nombre_unidad' => $unidad['nombre_unidad']]);
    } else {
        return json_encode(['error' => 'Unidad de medida no encontrada']);
    }
}







