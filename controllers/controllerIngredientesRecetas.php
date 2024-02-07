<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/recetasIngredientes.php');

function getAllIngredientesByReceta($idReceta) {
    $recetaIngredienteModel = new RecetaIngredienteModel();
    $recetasIngredientes = $recetaIngredienteModel->getIngredientesDeReceta($idReceta);
    return json_encode($recetasIngredientes);
}

// function getRecetasByCondicion($condicion, $valor) {
//     $recetaModel = new RecetaModel();
//     $recetas = $recetaModel->getRecetasByCondicion($condicion, $valor);
//     return json_encode($recetas);
// }

// if (isset($_POST['action']) && $_POST['action'] === 'add') {
//     $nombre = isset($_POST['nombreRecetaI']) ? htmlspecialchars($_POST['nombreRecetaI'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
//     $tiempo = isset($_POST['tiempoRecetaI']) ? filter_input(INPUT_POST, 'tiempoRecetaI', FILTER_SANITIZE_NUMBER_INT) : 0;
//     $principal = isset($_POST['isPrincipalI']) ? filter_input(INPUT_POST, 'isPrincipalI', FILTER_SANITIZE_NUMBER_INT) : 0;
//     $complementaria = isset($_POST['isComplementariaI']) ? filter_input(INPUT_POST, 'isComplementariaI', FILTER_SANITIZE_NUMBER_INT) : 0;
//     $especial = isset($_POST['isEspecialI']) ? filter_input(INPUT_POST, 'isEspecialI', FILTER_SANITIZE_NUMBER_INT) : 0;

//     if ($nombre !== null && !empty($nombre)) {
//         $recetaModel = new RecetaModel();
//         $recetaModel->setNombre($nombre);
//         $recetaModel->setTiempo($tiempo);
//         $recetaModel->setPrincipal($principal);
//         $recetaModel->setComplementaria($complementaria);
//         $recetaModel->setEspecial($especial);
//         $resultQuery = $recetaModel->newReceta();

//         if($resultQuery){
//             header("Location: ../views/recetas.php?insertedReceta=1");
//             exit();
//         }else {
//             header("Location: ../views/recetas.php?insertedReceta=0");
//             exit();
//         }
//     } else {
//         header("Location: ../views/recetas.php?insertedReceta=0");
//         exit();
//     }
// }

// if (isset($_POST['action']) && $_POST['action'] === 'edit') {
//     $id = filter_input(INPUT_POST, 'id_receta', FILTER_SANITIZE_NUMBER_INT);
//     $nombre = isset($_POST['nombreReceta']) ? htmlspecialchars($_POST['nombreReceta'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
//     $instrucciones = isset($_POST['instruccionesReceta']) ? htmlspecialchars($_POST['instruccionesReceta'], ENT_QUOTES, 'UTF-8') : null;
//     $tiempo = isset($_POST['tiempoReceta']) ? filter_input(INPUT_POST, 'tiempoReceta', FILTER_SANITIZE_NUMBER_INT) : 0;
//     $principal = isset($_POST['isPrincipal']) ? filter_input(INPUT_POST, 'isPrincipal', FILTER_SANITIZE_NUMBER_INT) : 0;
//     $complementaria = isset($_POST['isComplementaria']) ? filter_input(INPUT_POST, 'isComplementaria', FILTER_SANITIZE_NUMBER_INT) : 0;
//     $especial = isset($_POST['isEspecial']) ? filter_input(INPUT_POST, 'isEspecial', FILTER_SANITIZE_NUMBER_INT) : 0;

//     if ($id !== null && !empty($id)) {
//         $recetaModel = new RecetaModel();
//         $recetaModel->setId($id);
//         $recetaModel->setNombre($nombre);
//         $recetaModel->setTiempo($tiempo);
//         $recetaModel->setPrincipal($principal);
//         $recetaModel->setComplementaria($complementaria);
//         $recetaModel->setEspecial($especial);

//         $resultQuery = $recetaModel->updateRecipeById();

//         if($resultQuery){
//             header("Location: ../views/recetas.php?updatedReceta=1");
//             exit();
//         }else {
//             header("Location: ../views/recetas.php?updatedReceta=0");
//             exit();
//         }

//     } else {
//         header("Location: ../views/recetas.php?updatedReceta=0");
//         exit();
//     }
// }

// if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
//     $id = $_GET['id'];
//     $recetaModel = new RecetaModel();
//     $recetaModel->setId($id);
//     $resultQuery = $recetaModel->deleteRecipeById($id);

//     if($resultQuery){
//         header("Location: ../views/recetas.php?deletedReceta=1");
//         exit();
//     }else {
//         header("Location: ../views/recetas.php?deletedReceta=0");
//         exit();
//     }
    
//     header("Location: ../views/recetas.php?deletedReceta=1");
//     exit();
// }







