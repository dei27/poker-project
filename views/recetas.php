<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../controllers/controllerRecetas.php');
include('../controllers/controllerProducts.php');
include('../controllers/controllerIngredientesRecetas.php');
$recetasPrincipales = json_decode(getAllRecetas(),true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Explora la auténtica experiencia de sabores moncheños en nuestra microempresa familiar. Desde generaciones, hemos cultivado tradiciones ramonenses que se reflejan en cada producto que ofrecemos. Descubre la esencia de nuestras raíces a través de delicias cuidadosamente elaboradas, donde cada bocado es un viaje a la rica herencia de las tradiciones moncheñas. ¡Bienvenido a un mundo donde la vida tiene el sabor tan bueno como Monchister!">
    <meta name="keywords" content="microempresa familiar, tradiciones ramonenses, sabores moncheños, productos artesanales, herencia culinaria, delicias familiares, monchister, autenticidad, experiencias gastronómicas">
    <meta name="author" content="Deivi Campos">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.5.5/css/colReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Recetas</title>
</head>
<body>
<header>
    <?php 
        if(isset($_SESSION["user"])){
            include('menu.php');
        }else{
            echo 
            '<nav class="navbar sticky-top">
                <a href="../index.php" class="text-decoration-none text-dark navbar-brand"><i class="bi bi-arrow-left-circle-fill text-dark fs-3 px-3"></i></a>
            </nav>';
        }
    ?>
</header>

<?php
if (isset($_SESSION["user"]) && (isset($_SESSION['role']) && $_SESSION['role'] === 1)) {
?>
    <div class="container-fluid p-5">
        <div class="card p-3">
            <h4 class="card-header mb-3 py-3">Mis Recetas</h4>
            <h5 class="card-text">
                <a href="#" class="text-decoration-none text-info" data-bs-toggle="modal" data-bs-target="#addTournament">
                <img src="../assets/images/recetas.png" alt="Crear torneo" class="img-fluid me-2">Nueva receta.
                </a>
            </h5>

            <div class="table-responsive">
            <table id="misRecetas" class="table table-dark table-striped table-hover">
                <thead class="table-warning">
                    <tr>
                        <th>Nombre</th>
                        <th>Web</th>
                        <th>Precio</th>
                        <th>Costo</th>
                        <th>Tiempo</th>
                        <th>Tipo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recetasPrincipales as $receta):?>
                        <tr>
                            <td>
                                <a href="#" class="text-decoration-none text-white" data-bs-toggle="modal" data-bs-target="#modalReceta<?php echo $receta['id_receta']; ?>">
                                    <?php echo $receta['nombre_receta']; ?>
                                </a>
                                <!-- Modal ingredientes -->
                                <div class="modal fade" id="modalReceta<?php echo $receta['id_receta']; ?>" tabindex="-1" aria-labelledby="modalRecetaLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark">
                                                <h5 class="modal-title" id="modalRecetaLabel">Receta: <?php echo $receta['nombre_receta']; ?></h5>
                                                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-dark">
                                                <h6 class="modal-title mb-3">
                                                    <a href="#" class="text-decoration-none text-primary" data-bs-toggle="modal" data-bs-target="#agregarIngredientes<?php echo $receta['id_receta']; ?>">
                                                    <i class="bi bi-bookmark-star-fill me-1"></i>Agregar ingredientes<i class="bi bi-bookmark-star-fill ms-1"></i>
                                                    </a>
                                                </h6>

                                            <?php if ($receta['principal'] == 1): ?>
                                                <h6 class="modal-title mb-3"> 
                                                    <a href="#" class="text-decoration-none text-primary" data-bs-toggle="modal" data-bs-target="#agregarRecetas<?php echo $receta['id_receta']; ?>">
                                                    <i class="bi bi-bookmark-star-fill me-1"></i>Agregar recetas complementarias<i class="bi bi-bookmark-star-fill ms-1"></i>
                                                    </a>
                                                </h6>
                                                <h6 class="modal-title mb-3"> 
                                                    <a href="#" class="text-decoration-none text-primary" data-bs-toggle="modal" data-bs-target="#agregarImagen<?php echo $receta['id_receta']; ?>">
                                                    <i class="bi bi-bookmark-star-fill me-1"></i>Agregar imagen presentación platillo<i class="bi bi-bookmark-star-fill ms-1"></i>
                                                    </a>
                                                </h6>
                                            <?php endif; ?>

                                                <form action="../controllers/controllerIngredientesRecetas.php" method="post" class="form-floating">
                                                    <div class="row mb-3 align-items-center">
                                                        <div class="col">
                                                            <h6>Seleccione los ingredientes que sea eliminar.</h6>
                                                        </div>
                                                        <div class="col">
                                                            <button type="submit" class="btn btn-danger w-100 p-3">
                                                            <i class="bi bi-cursor-fill text-white me-3"></i>
                                                            Eliminar Seleccionados
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="idRecetaDelete" value="<?php echo $receta['id_receta'];?>">
                                                    <div class="table-responsive">
                                                        <table id="recetasDetalles" class="table table-dark table-striped table-hover">
                                                            <thead class="table-warning">
                                                                <tr>
                                                                    <th>Elegir</th>
                                                                    <th>Nombre</th>
                                                                    <th>Cantidad</th>
                                                                    <th>Precio costo</th>
                                                                    <th>Medida</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $ingredientesReceta = json_decode(getAllIngredientesByReceta($receta['id_receta']), true);
                                                                foreach ($ingredientesReceta as $ingrediente) {
                                                                    echo '<tr>';
                                                                    echo '<td><input type="checkbox" name="ingredientes_seleccionados[]" value="' . $ingrediente['id_ingrediente'] . '"></td>';
                                                                    echo '<td>' . $ingrediente['nombre'] . '</td>';
                                                                    echo '<td>' . $ingrediente['cantidad'] . '</td>';

                                                                    $costoTotalFormateado = number_format($ingrediente['costo_total'], 2, ',', '.');
                                                                    echo '<td>₡' . $costoTotalFormateado . '</td>';

                                                                    echo '<td>' . $ingrediente['nombre_unidad'] . '</td>';
                                                                    echo '</tr>';
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="agregarImagen<?php echo $receta['id_receta']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark">
                                                <h5 class="modal-title" id="exampleModalLabel">Agregar imagen como guía de presentación</h5>
                                                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-dark text-start">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <?php if(isset($receta["img_url"])): ?>
                                                            <figure>
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#fullScreenModal<?php echo $receta['id_receta']; ?>">
                                                                    <img src="<?php echo $receta["img_url"]; ?>" class="img-fluid" alt="Imagen de receta">
                                                                </a>
                                                                <figcaption class="text-center mt-2">Imagen actual de la receta.</figcaption>
                                                            </figure>
                                                        <?php else: ?>
                                                            <ul>
                                                                <li>No hay imagen para esta receta.</li>
                                                                <li>Recomendamos una fotografía ejemplar.</li>
                                                                <li>Entre mayor calidad, mucho mejor.</li>
                                                            </ul>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-6">
                                                        <form action="../controllers/controllerRecetas.php" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="action" value="addImage">
                                                            <input type="hidden" name="idReceta" value="<?php echo $receta['id_receta'];?>">
                                                            <div class="mb-3">
                                                                <label for="imagen" class="form-label">Selecciona una imagen</label>
                                                                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <button type="submit" class="btn btn-primary w-100 p-3">
                                                                    <i class="bi bi-cursor-fill text-white me-3"></i>
                                                                    Subir Imagen
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de pantalla completa -->
                                <div class="modal fade" id="fullScreenModal<?php echo $receta['id_receta']; ?>" tabindex="-1" aria-labelledby="fullScreenModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-fullscreen">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark">
                                                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <?php if(isset($receta["img_url"])): ?>
                                                    <img src="<?php echo $receta["img_url"]; ?>" class="img-fluid" alt="Imagen de receta">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Modal agregar recetas complementarias -->
                                <div class="modal fade" id="agregarRecetas<?php echo $receta['id_receta']; ?>" tabindex="-1" aria-labelledby="agregarRecetasLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark">
                                                <h6 class="modal-title" id="agregarRecetasLabel">Agregar Recetas Complementarias</h6>
                                                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-dark">
                                                <div class="mb-3">
                                                    <h6><i class="bi bi-clipboard2-plus-fill"></i> Seleccione la o las recetas que desea combinar con <?php echo $receta['nombre_receta']; ?>.</h6>
                                                </div>
                                                <form action="../controllers/controllerRecetasCombinadas.php" method="post" class="form-floating">
                                                    <input type="hidden" name="action" value="add">
                                                    <input type="hidden" name="idReceta" value="<?php echo $receta['id_receta'];?>">
                                                    <div class="table-responsive">
                                                        <table id="tablaRecetasComplementarias" class="table table-dark table-striped table-hover">
                                                            <thead class="table-warning">
                                                                <tr>
                                                                    <th>Elegir</th>
                                                                    <th>Receta</th>
                                                                    <th>Tipo</th>
                                                                    <th>Cantidad</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $recetasComplementarias = json_decode(getAllRecetasComplementarias($receta['id_receta']), true);

                                                                foreach ($recetasComplementarias as $recetaComplementaria) {
                                                                    echo '<tr>';
                                                                    echo '<td><input type="checkbox" name="recetas_nuevas[]" value="' . $recetaComplementaria['id_receta'] . '"></td>';
                                                                    echo '<td>' . $recetaComplementaria['nombre_receta'] . '</td>';
                                                                    echo '<td>' . $recetaComplementaria['nombre_tipo'] . '</td>';
                                                                    echo '<td><input class="form-input text-end add_cantidad" type="number" step="any" name="cantidades[' . $recetaComplementaria['id_receta'] . ']" placeholder="Ingrese cantidad" min="1" placeholder="Ingrese la cantidad"></td>';
                                                                    echo '</tr>';
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    
                                                    <div class="col-md-12 mt-3">
                                                        <button type="submit" class="btn btn-primary w-100 p-3">
                                                        <i class="bi bi-cursor-fill text-white me-3"></i>
                                                        Guardar
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Modal agregar ingredientes -->
                                <div class="modal fade" id="agregarIngredientes<?php echo $receta['id_receta']; ?>" tabindex="-1" aria-labelledby="agregarIngredientesLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header text-bg-dark">
                                                <h6 class="modal-title" id="agregarIngredientesLabel">Agregar Ingredientes</h6>
                                                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-dark">
                                                <form action="../controllers/controllerIngredientesRecetas.php" method="post" class="form-floating">
                                                    <div class="row mb-3 align-items-center">
                                                        <div class="col">
                                                            <h6>Seleccione el ingrediente y luego ingrese la cantidad deseada.</h6>
                                                            <h6>La cantidad debe ser en gramos, mililitros o unidad.</h6>
                                                        </div>
                                                        <div class="col">
                                                            <button type="submit" class="btn btn-primary w-100 p-3">
                                                            <i class="bi bi-cursor-fill text-white me-3"></i>
                                                            Guardar
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="action" value="add">
                                                    <input type="hidden" name="idReceta" value="<?php echo $receta['id_receta'];?>">
                                                    <div class="table-responsive">
                                                        <table id="tablaIngredientes" class="table table-dark table-striped table-hover">
                                                            <thead class="table-warning">
                                                                <tr>
                                                                    <th>Elegir</th>
                                                                    <th>Nombre</th>
                                                                    <th>Categoría</th>
                                                                    <th>Cantidad</th>
                                                                    <th>Medida</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $productos = json_decode(getAllProductsNotIn($receta['id_receta']), true);
                                                                foreach ($productos as $producto) {
                                                                    echo '<tr>';
                                                                    echo '<td><input type="checkbox" name="ingredientes_nuevos[]" value="' . $producto['id_producto'] . '"></td>';
                                                                    echo '<td>' . $producto['nombre'] . '</td>';
                                                                    echo '<td>' . $producto['nombre_categoria'] . '</td>';
                                                                    echo '<td><input class="form-input text-end add_cantidad" type="number" step="any" name="cantidades[' . $producto['id_producto'] . ']" placeholder="Ingrese cantidad" min="1" placeholder="Ingrese la  cantidad"></td>';
                                                                    echo '<td>' . $producto['nombre_unidad'] . '</td>';
                                                                    echo '<input type="hidden" name="unidades[' . $producto['id_producto'] . ']" value="' . $producto['id_unidad'] . '">';
                                                                    echo '</tr>';
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo ($receta['web'] == 1) ? 'Sí' : 'No'; ?></td>
                            <td>₡<?php echo $receta['precio']; ?></td>

                            <?php
                                $costosReceta = json_decode(getCostoRecetaById($receta['id_receta']), true);
                                $myIds = getAllIdsRecetasCompuestasByIdReceta($receta['id_receta']);

                                $costoTotalReceta = 0;
                                $costoTotalRecetaFormateado = 0;
                                $totalCostoTodasRecetasCompuestas = 0;
                                $total = 0;

                                if(!empty($myIds)){

                                    foreach ($myIds as $idRecetaCompuesta) {
                                        $costoTotalRecetaCompuesta = json_decode(getCostoRecetaById($idRecetaCompuesta['id_receta_compuesta']), true);
                                        
                                        $cantidad = intval($idRecetaCompuesta["cantidad_receta_compuesta"]);

                                        if(!empty($costoTotalRecetaCompuesta)){

                                            foreach ($costoTotalRecetaCompuesta as $costoRecetaCompuesta) {

                                                if($cantidad > 1){
                                                    $totalCostoTodasRecetasCompuestas += $costoRecetaCompuesta['costo_total'] * $cantidad;
                                                }else{
                                                    $totalCostoTodasRecetasCompuestas += $costoRecetaCompuesta['costo_total'];
                                                }
                                            }
                                        }
                                    }
                                }

                                if(!empty($costosReceta)){
                                    foreach ($costosReceta as $costo) {
                                        $costoTotalReceta += $costo['costo_total'];
                                    }
                                }

                                $total = ($totalCostoTodasRecetasCompuestas !== 0) ? $totalCostoTodasRecetasCompuestas + $costoTotalReceta : $costoTotalReceta;

                                $costoTotalRecetaFormateado = number_format($total, 2, ',', '.');
                            ?>


                            <td>₡<?php echo $costoTotalRecetaFormateado; ?></td> 
                            <td><?php echo $receta['tiempo_preparacion']; ?></td>
                            <td><?php echo $receta['nombre_tipo']; ?></td>
                            <td class="text-center">
                                <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#confirmDeleteRecetaModal<?php echo $receta['id_receta']?>">
                                    <i class="bi bi-trash-fill text-white mx-3"></i>
                                </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $receta['id_receta']?>" class="text-decoration-none">
                                    <i class="bi bi-pencil-square text-white"></i>
                                </a>

                                <!-- Modal de confirmación de eliminación de receta -->
                                <div class="modal fade" id="confirmDeleteRecetaModal<?php echo $receta['id_receta']?>" tabindex="-1" aria-labelledby="confirmDeleteRecetaModalLabel<?php echo $receta['id_receta']?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark">
                                                <h5 class="modal-title" id="confirmDeleteRecetaModalLabel<?php echo $receta['id_receta']?>">Confirmar Eliminación de Receta</h5>
                                                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-dark">
                                                <h6>¿Estás seguro de que quieres eliminar esta receta?</h6>
                                            </div>
                                            <div class="form-group mb-3 text-start px-3">
                                                <a href="../controllers/controllerRecetas.php?action=delete&id=<?php echo $receta['id_receta']; ?>" class="btn btn-danger w-100 py-3">
                                                    <i class="bi bi-cursor-fill text-white me-3"></i>Eliminar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Editar receta -->
                                <div class="modal fade" id="editModal<?php echo $receta['id_receta']?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                        <div class="modal-header bg-dark">
                                            <h5 class="modal-title" id="editModalLabel">Editar receta</h5>
                                            <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-dark">
                                            <!-- Edit Form -->
                                            <form action="../controllers/controllerRecetas.php" method="post">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="id_receta" value="<?php echo $receta['id_receta']?>">

                                                <div class="form-group mb-3 text-start">
                                                    <label for="nombreReceta" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" id="nombreReceta" name="nombreReceta" placeholder="Nombre receta..." value="<?php echo $receta['nombre_receta']?>" required>
                                                </div>
                                                <div class="form-group mb-3 text-start">
                                                    <label for="precioReceta" class="form-label">Precio</label>
                                                    <input type="number" class="form-control" id="precioReceta" name="precioReceta" placeholder="Precio..." step="any" value="<?php echo htmlspecialchars($receta['precio']); ?>" min=1 required>
                                                </div>
                                                <div class="form-group mb-3 text-start">
                                                    <label for="tiempoReceta" class="form-label">Tiempo preparación</label>
                                                    <input type="number" class="form-control" id="tiempoReceta" name="tiempoReceta" placeholder="Tiempo receta..." value="<?php echo $receta['tiempo_preparacion']?>" required min="0">
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group mb-3 text-start">
                                                            <label for="tipoReceta">Tipo de Receta</label>
                                                            <select class="form-select" id="tipoReceta" name="tipoReceta" required>
                                                                <option value="">Seleccione ...</option>
                                                                <option value="1" <?php echo ($receta['tipo'] == 1) ? 'selected' : ''; ?>>Entradas</option>
                                                                <option value="2" <?php echo ($receta['tipo'] == 2) ? 'selected' : ''; ?>>Platillos Fuertes</option>
                                                                <option value="3" <?php echo ($receta['tipo'] == 3) ? 'selected' : ''; ?>>Postres</option>
                                                                <option value="4" <?php echo ($receta['tipo'] == 4) ? 'selected' : ''; ?>>Extras</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php if ($receta["principal"] == 1): ?>
                                                        <div class="col">
                                                            <div class="form-group mb-3 text-start">
                                                                <label for="mostrarEnWeb">Mostrar en web</label>
                                                                <select class="form-select" id="mostrarEnWeb" name="mostrarEnWeb" required>
                                                                    <option value="">Seleccione ...</option>
                                                                    <option value="1" <?php echo ($receta['web'] == 1) ? 'selected' : ''; ?>>Sí</option>
                                                                    <option value="0" <?php echo ($receta['web'] == 0) ? 'selected' : ''; ?>>No</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                </div>
                                                

                                                <div class="form-group my-3 text-center">
                                                    <h6>Modalidad de Receta</h6>
                                                    <div class="form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="isComplementaria" name="isComplementaria" value="1" <?php echo ($receta['complementaria'] == 1) ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="isComplementaria">
                                                            Complementaria
                                                        </label>
                                                    </div>

                                                    <div class="form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="isEspecial" name="isEspecial" value="1" <?php echo ($receta['especial'] == 1) ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="isEspecial">
                                                            Especial
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group mt-3 text-center">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary w-100 p-3">
                                                        <i class="bi bi-cursor-fill text-white me-3"></i>
                                                        Guardar
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
<!-- modal agregar receta -->
<div class="modal fade" id="addTournament" tabindex="-1" aria-labelledby="addTournamentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
        <div class="modal-header text-bg-dark">
            <h6 class="modal-title" id="addTournament">Agregar Nueva Receta</h6>
            <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <div class="modal-body">
                <form action="../controllers/controllerRecetas.php" method="post" class="form-floating">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group mb-3">
                        <label for="nombreRecetaI">Nombre</label>
                        <input class="form-control" id="nombreRecetaI" name="nombreRecetaI" placeholder="Nombre receta..." value="" required>
                    </div>
                    <div class="form-group mb-3 text-start">
                        <label for="precioRecetaI">Precio</label>
                        <input type="number" class="form-control" id="precioRecetaI" name="precioRecetaI" placeholder="Precio..." step="any" required min=1>
                    </div>
                    <div class="form-group mb-3 text-start">
                        <label for="tiempoRecetaI" class="form-label">Tiempo preparación</label>
                        <input type="number" class="form-control" id="tiempoRecetaI" name="tiempoRecetaI" placeholder="Tiempo receta..." required min="1">
                    </div>

                    <div class="form-group mb-3">
                        <label for="tipoRecetaI">Tipo de Receta</label>
                        <select class="form-select" id="tipoRecetaI" name="tipoRecetaI" required>
                            <option value="">Seleccione...</option>
                            <option value="1">Entradas</option>
                            <option value="2">Platillos Fuertes</option>
                            <option value="3">Postres</option>
                            <option value="4">Extras</option>
                        </select>
                    </div>
                    
                    <div class="form-group my-3 text-center">
                        <h6>Modalidad de Receta</h6>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="isComplementariaI" name="isComplementariaI" value="1">
                            <label class="form-check-label" for="isComplementariaI">Complementaria</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="isEspecialI" name="isEspecialI" value="1">
                            <label class="form-check-label" for="isEspecialI">Especial</label>
                        </div>
                    </div>
                    <div class="form-group mt-3 text-center">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 p-3">
                            <i class="bi bi-cursor-fill text-white me-3"></i>
                            Guardar
                        </button>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
}else{
    echo '<div class="container-fluid mt-5 vh-100 p-5">
            <div class="card p-3">
                <p class="card-text py-5">No tienes el poder suficiente para poder ver esto. <a href="login.php">Inicia sesión</a>.</p>
            </div>
        </div>';
}
?>

<?php
if (isset($_GET['deletedReceta'])) {
    $updatedCategoryStatus = $_GET['deletedReceta'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Eliminada con éxito',
            'text' => 'Receta eliminada.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al eliminar',
            'text' => 'No se pudo eliminar la receta.',
        ];

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "' . $messageConfig['icon'] . '",
            title: "' . $messageConfig['title'] . '",
            timer: 2500,
            text: "' . $messageConfig['text'] . '",
            showConfirmButton: false
        });
    </script>
    ';
}
?>

<?php
if (isset($_GET['updatedReceta'])) {
    $updatedCategoryStatus = $_GET['updatedReceta'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Actualizado con éxito',
            'text' => 'Receta actualizada.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al actualizar',
            'text' => 'No se pudo actualizar la receta.',
        ];

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "' . $messageConfig['icon'] . '",
            title: "' . $messageConfig['title'] . '",
            timer: 2500,
            text: "' . $messageConfig['text'] . '",
            showConfirmButton: false
        });
    </script>
    ';
}


if (isset($_GET['uploadedImage'])) {
    $updatedCategoryStatus = $_GET['uploadedImage'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Actualizado con éxito',
            'text' => 'Imagen subida.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al actualizar',
            'text' => 'No se pudo subir la imagen.',
        ];

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "' . $messageConfig['icon'] . '",
            title: "' . $messageConfig['title'] . '",
            timer: 2500,
            text: "' . $messageConfig['text'] . '",
            showConfirmButton: false
        });
    </script>
    ';
}

if (isset($_GET['insertedReceta'])) {
    $updatedCategoryStatus = $_GET['insertedReceta'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Creada con éxito',
            'text' => 'Receta creada.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al crear',
            'text' => 'No se pudo crear la receta.',
        ];

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "' . $messageConfig['icon'] . '",
            title: "' . $messageConfig['title'] . '",
            timer: 2500,
            text: "' . $messageConfig['text'] . '",
            showConfirmButton: false
        });
    </script>
    ';
}

if (isset($_GET['insertedIngredientes'])) {
    $updatedCategoryStatus = $_GET['insertedIngredientes'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Agregado con éxito',
            'text' => 'Ingredientes agregados.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al agregar',
            'text' => 'No se pudo agrear ingredientes.',
        ];

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "' . $messageConfig['icon'] . '",
            title: "' . $messageConfig['title'] . '",
            timer: 2500,
            text: "' . $messageConfig['text'] . '",
            showConfirmButton: false
        });
    </script>
    ';
}

if (isset($_GET['deletedIngredientes'])) {
    $updatedCategoryStatus = $_GET['deletedIngredientes'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Elimnado con éxito',
            'text' => 'Ingredientes eliminados.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al eliminar',
            'text' => 'No se pudo eliminar ingredientes.',
        ];

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "' . $messageConfig['icon'] . '",
            title: "' . $messageConfig['title'] . '",
            timer: 2500,
            text: "' . $messageConfig['text'] . '",
            showConfirmButton: false
        });
    </script>
    ';
}

if (isset($_GET['insertedRecetas'])) {
    $updatedCategoryStatus = $_GET['insertedRecetas'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Agregado con éxito',
            'text' => 'Recetas añadidas.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al añadir',
            'text' => 'No se pudo agregar las recetas.',
        ];

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "' . $messageConfig['icon'] . '",
            title: "' . $messageConfig['title'] . '",
            timer: 2500,
            text: "' . $messageConfig['text'] . '",
            showConfirmButton: false
        });
    </script>
    ';
}



?>

<!-- Move these script tags to the end of the body -->
<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Incluye ColReorder (JavaScript) -->
<script src="https://cdn.datatables.net/colreorder/1.5.5/js/dataTables.colReorder.min.js"></script>

<!-- Incluye DataTables Buttons (JavaScript) -->
<script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>


<script>
    $(document).ready(function() {

        $('input[type="checkbox"]').change(function(){
        var cantidadInput = $(this).closest('tr').find('input[type="number"]');
        
        if($(this).is(':checked')){
            cantidadInput.prop('required', true);
        } else {
            cantidadInput.prop('required', false);
        }
        });

        $('input[name="ingredientes_nuevos[]"]').change(function(){
            var cantidadInput = $(this).closest('tr').find('input[type="number"]');
        
            if($(this).is(':checked')){
                cantidadInput.prop('required', true);
            } else {
                cantidadInput.prop('required', false);
            }
        });

        $('.add_cantidad').change(function(){
            // Obtener el checkbox en la misma fila
            var checkbox = $(this).closest('tr').find('input[type="checkbox"]');
            
            // Marcar el checkbox si se ingresó una cantidad mayor que 0
            if($(this).val() > 0){
                checkbox.prop('checked', true);
            } else {
                checkbox.prop('checked', false);
            }
        });

        $('#tablaRecetasComplementarias').DataTable({
            lengthChange: false,
            pageLength: 5,
            info: false,
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            initComplete: function(settings, json) {
                $(".dataTables_filter label").addClass("text-dark");
            }
        });

        $('#recetasDetalles').DataTable({
            lengthChange: false,
            info: false,
            responsive: true,
            paging: false,
            "order": [[1, 'asc']],
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            initComplete: function(settings, json) {
                $(".dataTables_filter label").addClass("text-dark");
            }
        });

        $('#misRecetas').DataTable({
            lengthChange: false,
            pageLength: 10,
            info: false,
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            initComplete: function(settings, json) {
                $(".dataTables_filter label").addClass("text-dark");
            }
        });

        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        $('#detallesHorarios').DataTable({
            lengthChange: false,
            pageLength: 5,
            info: false,
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            "order": [[1, 'asc']],
            initComplete: function(settings, json) {
                $(".dataTables_filter label").addClass("text-dark");
            }
        });

        $('#tablaIngredientes').DataTable({
            lengthChange: false,
            autoWidth: false,
            info: false,
            paging: false,
            "order": [[1, 'asc']],
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            initComplete: function(settings, json) {
                $(".dataTables_filter label").addClass("text-dark");
            }
        });
    });
</script>



</body>
</html>
