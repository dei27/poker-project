<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../models/categoryModel.php');

function getAllCategories() {
    $categoryModel = new Category();
    $categories = $categoryModel->getAllCategories();
    return json_encode($categories);
}

function getNameCategoryById($id) {
    $categoryModel = new Category();
    
    $category = $categoryModel->getCategoryById($id);

    if ($category) {
        return json_encode(['nombre_categoria' => $category['nombre_categoria']]);
    } else {
        return json_encode(['error' => 'Categoría no encontrada']);
    }
}


if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $categoryModel = new Category();
    $deleted = $categoryModel->deleteCategoryById($id);

    if($deleted){
        header("Location: ../views/categorias.php?deleteCategory=1");
        exit();
    }else{
        header("Location: ../views/categorias.php?deleteCategory=0");
        exit();
    }
    
    
}

if (isset($_POST['action']) && $_POST['action'] === 'editCategory' && isset($_POST['id_categoria'])) {
    $id = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT);
    $nombreCategoria = isset($_POST['nombreCategoria']) ? htmlspecialchars($_POST['nombreCategoria'], ENT_QUOTES, 'UTF-8') : 'Sin nombre';

    $categoryModel = new Category();

    $categoryModel->setId($id);
    $categoryModel->setNombreCategoria($nombreCategoria);

    $affectedRows = $categoryModel->updateCategoryById();

    if($affectedRows != 0){
        header("Location: ../views/categorias.php?updatedCategory=1");
        exit();
    }else{
        header("Location: ../views/categorias.php?updatedCategory=0");
        exit();
    }

}

if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $nombre = isset($_POST['nombreCategoriaI']) ? htmlspecialchars($_POST['nombreCategoriaI'], ENT_QUOTES, 'UTF-8') : "Sin nombre";

    if ($nombre !== null && !empty($nombre)) {
        $categoryModel = new Category();
        $categoryModel->setNombreCategoria($nombre);

        $categoryModel->newCategory();

        header("Location: ../views/categorias.php?insertedCategory=1");
        exit();
    } else {
        header("Location: ../views/categorias.php?insertedCategory=0");
        exit();
    }
}



