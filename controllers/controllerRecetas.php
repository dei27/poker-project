<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/recetasModel.php');

function getAllRecetas() {
    $recetaModel = new RecetaModel();
    $recetas = $recetaModel->getAllRecetas();
    return json_encode($recetas);
}

function getCostoRecetaById($id_receta) {
    $recetaModel = new RecetaModel();
    $recetaModel->setId($id_receta);
    $recetas = $recetaModel->getCostoRecetaById();
    return json_encode($recetas);
}

function getAllRecetasComplementarias($id_receta) {
    $recetaModel = new RecetaModel();
    $recetaModel->setId($id_receta);
    $recetas = $recetaModel->getAllRecetasComplementarias();
    return json_encode($recetas);
}

function getRecetasByCondicion($condicion, $valor) {
    $recetaModel = new RecetaModel();
    $recetas = $recetaModel->getRecetasByCondicion($condicion, $valor);
    return json_encode($recetas);
}

if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $nombre = isset($_POST['nombreRecetaI']) ? htmlspecialchars($_POST['nombreRecetaI'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $precio = isset($_POST['precioRecetaI']) ? $_POST['precioRecetaI'] : 0.0;
    $precio = filter_var($precio, FILTER_VALIDATE_FLOAT);
    $tiempo = isset($_POST['tiempoRecetaI']) ? filter_input(INPUT_POST, 'tiempoRecetaI', FILTER_SANITIZE_NUMBER_INT) : 0;
    $complementaria = isset($_POST['isComplementariaI']) ? filter_input(INPUT_POST, 'isComplementariaI', FILTER_SANITIZE_NUMBER_INT) : 0;
    $especial = isset($_POST['isEspecialI']) ? filter_input(INPUT_POST, 'isEspecialI', FILTER_SANITIZE_NUMBER_INT) : 0;
    $tipo = isset($_POST['tipoRecetaI']) ? htmlspecialchars($_POST['tipoRecetaI'], ENT_QUOTES, 'UTF-8') : 1;
    $principal = 0;


    if ($nombre !== null && !empty($nombre)) {
        $recetaModel = new RecetaModel();
        $recetaModel->setNombre($nombre);
        $recetaModel->setPrecio($precio);
        $recetaModel->setTiempo($tiempo);

        if($complementaria === 0 && $especial === 0){
            $principal = 1;
        }

        $recetaModel->setPrincipal($principal);
        $recetaModel->setComplementaria($complementaria);
        $recetaModel->setEspecial($especial);
        $recetaModel->setTipo($tipo);

        $resultQuery = $recetaModel->newReceta();

        if($resultQuery){
            header("Location: ../views/recetas.php?insertedReceta=1");
            exit();
        }else {
            header("Location: ../views/recetas.php?insertedReceta=0");
            exit();
        }
    } else {
        header("Location: ../views/recetas.php?insertedReceta=0");
        exit();
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = filter_input(INPUT_POST, 'id_receta', FILTER_SANITIZE_NUMBER_INT);
    $nombre = isset($_POST['nombreReceta']) ? htmlspecialchars($_POST['nombreReceta'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $precio = isset($_POST['precioReceta']) ? $_POST['precioReceta'] : 0.0;
    $precio = filter_var($precio, FILTER_VALIDATE_FLOAT);
    $tiempo = isset($_POST['tiempoReceta']) ? filter_input(INPUT_POST, 'tiempoReceta', FILTER_SANITIZE_NUMBER_INT) : 0;
    $complementaria = isset($_POST['isComplementaria']) ? filter_input(INPUT_POST, 'isComplementaria', FILTER_SANITIZE_NUMBER_INT) : 0;
    $especial = isset($_POST['isEspecial']) ? filter_input(INPUT_POST, 'isEspecial', FILTER_SANITIZE_NUMBER_INT) : 0;
    $tipo = isset($_POST['tipoReceta']) ? htmlspecialchars($_POST['tipoReceta'], ENT_QUOTES, 'UTF-8') : 1;
    $principal = 0;
    $mostrarEnWeb = isset($_POST['mostrarEnWeb']) ? htmlspecialchars($_POST['mostrarEnWeb'], ENT_QUOTES, 'UTF-8') : 0;


    if ($id !== null && !empty($id)) {
        $recetaModel = new RecetaModel();
        $recetaModel->setId($id);
        $recetaModel->setNombre($nombre);
        $recetaModel->setPrecio($precio);
        $recetaModel->setTiempo($tiempo);

        if($complementaria === 0 && $especial === 0){
            $principal = 1;
        }

        $recetaModel->setPrincipal($principal);
        $recetaModel->setComplementaria($complementaria);
        $recetaModel->setEspecial($especial);
        $recetaModel->setTipo($tipo);
        $recetaModel->setDisponibleWeb($mostrarEnWeb);

        $resultQuery = $recetaModel->updateRecipeById();

        if($resultQuery){
            header("Location: ../views/recetas.php?updatedReceta=1");
            exit();
        }else {
            header("Location: ../views/recetas.php?updatedReceta=0");
            exit();
        }

    } else {
        header("Location: ../views/recetas.php?updatedReceta=0");
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $recetaModel = new RecetaModel();
    $recetaModel->setId($id);
    $resultQuery = $recetaModel->deleteRecipeById($id);

    if($resultQuery){
        header("Location: ../views/recetas.php?deletedReceta=1");
        exit();
    }else {
        header("Location: ../views/recetas.php?deletedReceta=0");
        exit();
    }
    
    header("Location: ../views/recetas.php?deletedReceta=1");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'addImage') {
    $id = filter_input(INPUT_POST, 'idReceta', FILTER_SANITIZE_NUMBER_INT);

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $uploadDirectory = "../assets/images/recetas/";
        $fileName = basename($_FILES['imagen']['name']);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $uniqueFileName = $id . '.' . $fileExtension;
        $uploadFilePath = $uploadDirectory . $uniqueFileName;

        $url_image = "../assets/images/recetas/" . $uniqueFileName;

        if (file_exists($uploadFilePath)) {
            unlink($uploadFilePath);
        }

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFilePath)) {

            $newReceta = new RecetaModel();
            $newReceta->setId($id);
            $newReceta->setImagen($url_image);

            $result = $newReceta->updateImageUrlById();

            if($result){
                header("Location: ../views/recetas.php?uploadedImage=1");
                exit();
            }else{
                header("Location: ../views/recetas.php?uploadedImage=0");
                exit();
            }

            
        } else {
            header("Location: ../views/recetas.php?uploadedImage=0");
            exit();
        }
    } else {
        header("Location: ../views/recetas.php?uploadedImage=0");
        exit();
    }
}







