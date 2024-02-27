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

function getAllIdsRecetasCompuestasByIdReceta($idReceta) {
    $recetaIngredienteModel = new RecetaIngredienteModel();
    $recetaIngredienteModel->setIdReceta($idReceta);
    $recetasCombinadas = $recetaIngredienteModel->getAllIdsRecetasCompuestasByIdReceta($idReceta);
    $misIds = array();

    foreach ($recetasCombinadas as $receta) {
        $nuevaReceta = array(
            "id_receta_compuesta" => $receta["id_receta_compuesta"],
            "cantidad_receta_compuesta" => $receta["cantidad_receta_compuesta"]
        );
        
        $misIds[] = $nuevaReceta;
    }
    
    return $misIds;
}



if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $id_receta = filter_input(INPUT_POST, 'idReceta', FILTER_SANITIZE_NUMBER_INT);
    $ingredientes_nuevos = isset($_POST['ingredientes_nuevos']) ? $_POST['ingredientes_nuevos'] : [];
    $resultQuery = false;

    if ($id_receta !== null && !empty($id_receta) && !empty($ingredientes_nuevos)) {
        $recetaIngredienteModel = new RecetaIngredienteModel();

        // Iterar sobre los ingredientes seleccionados y realizar la inserción
        foreach ($ingredientes_nuevos as $id_producto) {
            // Obtener cantidad
            $cantidad = isset($_POST['cantidades'][$id_producto]) ? filter_var($_POST['cantidades'][$id_producto], FILTER_VALIDATE_FLOAT) : 0.0;

            // Obtener unidad
            $unidad = isset($_POST['unidades'][$id_producto]) ? filter_var($_POST['unidades'][$id_producto], FILTER_SANITIZE_NUMBER_INT) : 1;

            $recetaIngredienteModel->setIdReceta($id_receta);
            $recetaIngredienteModel->setIdIngrediente($id_producto);
            $recetaIngredienteModel->setCantidad($cantidad);
            $recetaIngredienteModel->setUnidadMedida($unidad);
            $resultQuery = $recetaIngredienteModel->newRecetaIngredientes();
        }

        if ($resultQuery) {
            header("Location: ../views/recetas.php?insertedIngredientes=1");
            exit();
        } else {
            header("Location: ../views/recetas.php?insertedIngredientes=0");
            exit();
        }
    } else {
        header("Location: ../views/recetas.php?insertedIngredientes=0");
        exit();
    }
}


if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id_receta = filter_input(INPUT_POST, 'idRecetaDelete', FILTER_SANITIZE_NUMBER_INT);
    $ingredientes_nuevos = isset($_POST['ingredientes_seleccionados']) ? $_POST['ingredientes_seleccionados'] : [];
    $resultQuery = false;

    if(!empty($ingredientes_nuevos)){

        if ($id_receta !== null && !empty($id_receta) && !empty($ingredientes_nuevos)) {
            $recetaIngredienteModel = new RecetaIngredienteModel();
            $recetaIngredienteModel->setIdReceta($id_receta);

            // Iterar sobre los ingredientes seleccionados y realizar la inserción
            foreach ($ingredientes_nuevos as $id_ingrediente) {
                $recetaIngredienteModel->setIdIngrediente($id_ingrediente);
                $resultQuery = $recetaIngredienteModel->deleteIngredientesByIdReceta();
            }

            if($resultQuery){
                header("Location: ../views/recetas.php?deletedIngredientes=1");
                exit();
            }else {
                header("Location: ../views/recetas.php?deletedIngredientes=0");
                exit();
            }
        }
    }else {
        header("Location: ../views/recetas.php?deletedIngredientes=0");
        exit();
    }
}


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







