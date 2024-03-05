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


// function updateInventarioByIdReceta($id_producto, $cantidad, $nombre_tabla){
//     $productModel = new Producto();
//     $productModel->setid($id_producto);
//     $productModel->setCantidad($cantidad);
//     $productModel->updateInventarioByIdReceta($nombre_tabla);
// }

if (isset($_POST['id'], $_POST['action']) && $_POST['action'] === 'edit') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nombre = isset($_POST['nombreProducto']) ? htmlspecialchars($_POST['nombreProducto'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $cantidad = isset($_POST['cantidadProducto']) ? floatval($_POST['cantidadProducto']) : 0.0;
    $cantidadOriginal = isset($_POST['cantidadProductoOriginal']) ? floatval($_POST['cantidadProductoOriginal']) : 0.0;
    $precio = isset($_POST['precioProducto']) ? $_POST['precioProducto'] : 0.0;
    $precio = filter_var($precio, FILTER_VALIDATE_FLOAT);
    $categoria = isset($_POST['categoriaProducto']) ? filter_input(INPUT_POST, 'categoriaProducto', FILTER_SANITIZE_NUMBER_INT) : 0;
    $unidad = isset($_POST['unidadProducto']) ? filter_input(INPUT_POST, 'unidadProducto', FILTER_SANITIZE_NUMBER_INT) : 0;
    $result = false;

    if ($id !== null && !empty($id)) {
        $productModel = new Producto();
        $productModel->setId($id);
        $productModel->setNombre($nombre);
        $productModel->setCantidad($cantidad);
        $productModel->setPrecio($precio);
        $productModel->setCategoria($categoria);
        $productModel->setUnidad($unidad);

        if ($cantidad != $cantidadOriginal) {
            date_default_timezone_set('America/Costa_Rica');
            $fechaIngreso = date('Y-m-d H:i:s');
            $productModel->setFechaIngreso($fechaIngreso);
            $result = $productModel->updateProductByIdAndFechaIngreso();  
        } else {
            $result = $productModel->updateProductById();
        }

        if($result){
            header("Location: ../views/productos.php?updatedProduct=1");
            exit();
        }else{
            header("Location: ../views/productos.php?updatedProduct=0");
            exit();
        }
    } else {        
        header("Location: ../views/productos.php?updatedProduct=0");
        exit();
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $nombre = isset($_POST['nombreProductoI']) ? htmlspecialchars($_POST['nombreProductoI'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $cantidad = isset($_POST['cantidadProductoI']) ? floatval($_POST['cantidadProductoI']) : 0.0;
    $precio = isset($_POST['precioProductoI']) ? $_POST['precioProductoI'] : 0.0;
    $precio = filter_var($precio, FILTER_VALIDATE_FLOAT);
    $categoria = isset($_POST['categoriaProductoI']) ? filter_input(INPUT_POST, 'categoriaProductoI', FILTER_SANITIZE_NUMBER_INT) : 0;
    $unidad = isset($_POST['unidadProductoI']) ? floatval($_POST['unidadProductoI']) : 0.0;
    $unidadMedida = $unidad;

    if($unidad != 1){
        $unidadMedida = 1000;
    }

    $total = $cantidad * $unidadMedida;

    if ($nombre !== null && !empty($nombre)) {
        $productModel = new Producto();
        $productModel->setNombre($nombre);
        $productModel->setCantidad($cantidad);
        $productModel->setPrecio($precio);
        $productModel->setCategoria($categoria);
        $productModel->setUnidad($unidad);
        $productModel->setTotalCantidadProducto($total);

        $productModel->newProduct();

        header("Location: ../views/productos.php?insertedProduct=1");
        exit();
    } else {
        header("Location: ../views/productos.php?insertedProduct=0");
        exit();
    }
}







