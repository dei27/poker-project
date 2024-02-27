<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/recetasCombinadas.php');


if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $id_receta = filter_input(INPUT_POST, 'idReceta', FILTER_SANITIZE_NUMBER_INT);
    $recetas_nuevas = isset($_POST['recetas_nuevas']) ? $_POST['recetas_nuevas'] : [];
    $resultQuery = false;

    if ($id_receta !== null && !empty($id_receta) && !empty($recetas_nuevas)) {

        $recetaCombinada = new RecetaCombinada();

        foreach ($recetas_nuevas as $id_producto) {

            $cantidad = isset($_POST['cantidades'][$id_producto]) ? filter_var($_POST['cantidades'][$id_producto], FILTER_VALIDATE_FLOAT) : 0.0;

            $recetaCombinada->setPrincipal($id_receta);
            $recetaCombinada->setCompuesta($id_producto);
            $recetaCombinada->setCantidadCompuesta($cantidad);
            $resultQuery = $recetaCombinada->newRecetaCombinada();
        }

        if ($resultQuery) {
            header("Location: ../views/recetas.php?insertedRecetas=1");
            exit();
        } else {
            header("Location: ../views/recetas.php?insertedRecetas=0");
            exit();
        }
    } else {
        header("Location: ../views/recetas.php?insertedRecetas=0");
        exit();
    }
}







