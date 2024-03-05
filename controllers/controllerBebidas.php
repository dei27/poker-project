<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/BebidaModel.php');

function getAllBebidas() {
    $bebidasModel = new BebidaModel();
    $bebidas = $bebidasModel->getAllBebidas();
    return json_encode($bebidas);
}


if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $bebidasModel = new BebidaModel();
    $bebidasModel->setId($id);
    $result = $bebidasModel->deleteBebidaById();
    
    if($result){
        header("Location: ../views/bebidas.php?deleteBebida=1");
        exit();
    }else{
        header("Location: ../views/bebidas.php?deleteBebida=0");
        exit();
    }  
}

if (isset($_POST['id'], $_POST['action']) && $_POST['action'] === 'edit') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nombre = isset($_POST['nombreBebida']) ? htmlspecialchars($_POST['nombreBebida'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $precio = isset($_POST['precioBebida']) ? $_POST['precioBebida'] : 0.0;
    $precio = filter_var($precio, FILTER_VALIDATE_FLOAT);
    $cantidadBebida = isset($_POST['cantidadBebida']) ? $_POST['cantidadBebida'] : 0.0;
    $cantidadBebida = filter_var($cantidadBebida, FILTER_VALIDATE_FLOAT);

    $bebidasModel = new BebidaModel();
    $bebidasModel->setId($id);
    $bebidasModel->setNombre($nombre);
    $bebidasModel->setPrecio($precio);
    $bebidasModel->setCantidad($cantidadBebida);
    $result = $bebidasModel->updateBebidaById();

    if($result){
        header("Location: ../views/bebidas.php?updatedBebida=1");
        exit();
    }else{
        header("Location: ../views/bebidas.php?updatedBebida=1");
        exit();
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $nombre = isset($_POST['nombreBebidaI']) ? htmlspecialchars($_POST['nombreBebidaI'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $precio = isset($_POST['precioBebidaI']) ? $_POST['precioBebidaI'] : 0.0;
    $precio = filter_var($precio, FILTER_VALIDATE_FLOAT);
    $cantidadBebida = isset($_POST['cantidadBebidaI']) ? $_POST['cantidadBebidaI'] : 0.0;
    $cantidadBebida = filter_var($cantidadBebida, FILTER_VALIDATE_FLOAT);

    $bebidasModel = new BebidaModel();
    $bebidasModel->setNombre($nombre);
    $bebidasModel->setPrecio($precio);
    $bebidasModel->setCantidad($cantidadBebida);
    $result = $bebidasModel->newBebida();

    if($result){
        header("Location: ../views/bebidas.php?insertedBebida=1");
        exit();
    }else{
        header("Location: ../views/bebidas.php?insertedBebida=0");
        exit();
    }  
}







