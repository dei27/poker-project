<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/PedidosModel.php');

function getAllPedidos() {
    $pedidosModel = new PedidosModel();
    $pedidos = $pedidosModel->getAllPedidos();
    return json_encode($pedidos);
}

function getPedidoById($id_pedido){
    $pedidosModel = new PedidosModel();
    $pedido = $pedidosModel->getPedidosById($id_pedido);
    return $pedido; 
}


if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $pedidosModel = new PedidosModel();
    $pedidosModel->setIdPedido($id);
    $result = $pedidosModel->deletePedidoById();

    if($result){
        header("Location: ../views/ordenes.php?deletePedido=1");
        exit();
    }else{
        header("Location: ../views/ordenes.php?deletePedido=0");
        exit();
    }
    
}

if (isset($_POST['id'], $_POST['action']) && $_POST['action'] === 'edit') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nombre = isset($_POST['nombreCliente']) ? htmlspecialchars($_POST['nombreCliente'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $telefono = isset($_POST['telefonoCliente']) ? htmlspecialchars($_POST['telefonoCliente'], ENT_QUOTES, 'UTF-8') : "Sin telefono";
    $direccion = isset($_POST['direccionCliente']) ? htmlspecialchars($_POST['direccionCliente'], ENT_QUOTES, 'UTF-8') : "Sin dirección";
    $estado = isset($_POST['estadoPedido']) ? htmlspecialchars($_POST['estadoPedido'], ENT_QUOTES, 'UTF-8') : "Sin estado";

    if ($id !== null && !empty($id)) {
        $pedidosModel = new PedidosModel();
        $pedidosModel->setIdPedido($id);
        $pedidosModel->setNombreCliente($nombre);
        $pedidosModel->setTelefonoCliente($telefono);
        $pedidosModel->setDireccionCliente($direccion);
        $pedidosModel->setEstadoPedido($estado);

        $result = $pedidosModel->updatePedidoById();

        if($result){
            header("Location: ../views/ordenes.php?updatedPedido=1");
            exit();
        }else{
            header("Location: ../views/ordenes.php?updatedPedido=0");
            exit();
        }
    }else{        
        header("Location: ../views/ordenes.php?updatedPedido=0");
        exit();
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $nombre = isset($_POST['nombreProductoI']) ? htmlspecialchars($_POST['nombreProductoI'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $descripcion = isset($_POST['descripcionProductoI']) ? htmlspecialchars($_POST['descripcionProductoI'], ENT_QUOTES, 'UTF-8') : null;
    $precio = isset($_POST['precioProductoI']) ? $_POST['precioProductoI'] : 0.0;
    $precio = filter_var($precio, FILTER_VALIDATE_FLOAT);
    $categoria = isset($_POST['categoriaProductoI']) ? filter_input(INPUT_POST, 'categoriaProductoI', FILTER_SANITIZE_NUMBER_INT) : 0;
    $unidad = isset($_POST['unidadProductoI']) ? filter_input(INPUT_POST, 'unidadProductoI', FILTER_SANITIZE_NUMBER_INT) : 0;

    if ($nombre !== null && !empty($nombre)) {
        $productModel = new Producto();
        $productModel->setNombre($nombre);
        $productModel->setDescripcion($descripcion);
        $productModel->setPrecio($precio);
        $productModel->setCategoria($categoria);
        $productModel->setUnidad($unidad);

        $productModel->newProduct();

        header("Location: ../views/productos.php?insertedProduct=1");
        exit();
    } else {
        header("Location: ../views/productos.php?insertedProduct=0");
        exit();
    }
}







