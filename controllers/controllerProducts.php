<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/ProductoModel.php');

function getAll() {
    $productModel = new Producto();
    $products = $productModel->getAllProdcuts();
    return json_encode($products);
}

function getAllProductsNotIn($id_receta) {
    $productModel = new Producto();
    $products = $productModel->getAllProductsNotIn($id_receta);
    return json_encode($products);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $productModel = new Producto();
    $productModel->deleteProductById($id);
    
    header("Location: ../views/productos.php?delete=1");
    exit();
}

if (isset($_POST['id'], $_POST['action']) && $_POST['action'] === 'edit') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nombre = isset($_POST['nombreProducto']) ? htmlspecialchars($_POST['nombreProducto'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $descripcion = isset($_POST['descripcionProducto']) ? htmlspecialchars($_POST['descripcionProducto'], ENT_QUOTES, 'UTF-8') : "Sin descripcion";
    $precio = isset($_POST['precioProducto']) ? $_POST['precioProducto'] : 0.0;
    $precio = filter_var($precio, FILTER_VALIDATE_FLOAT);
    $categoria = isset($_POST['categoriaProducto']) ? filter_input(INPUT_POST, 'categoriaProducto', FILTER_SANITIZE_NUMBER_INT) : 0;
    $unidad = isset($_POST['unidadProducto']) ? filter_input(INPUT_POST, 'unidadProducto', FILTER_SANITIZE_NUMBER_INT) : 0;

    if ($id !== null && !empty($id)) {
        $productModel = new Producto();
        $productModel->setId($id);
        $productModel->setNombre($nombre);
        $productModel->setDescripcion($descripcion);
        $productModel->setPrecio($precio);
        $productModel->setCategoria($categoria);
        $productModel->setUnidad($unidad);

        $productModel->updateProductById();

        header("Location: ../views/productos.php?updatedProduct=1");
        exit();
    } else {        header("Location: ../views/productos.php?updatedProduct=0");
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







