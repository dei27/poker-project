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
if (isset($_SESSION["user"])) {
?>
    <div class="container-fluid p-5">
        <div class="card p-3">
            <h4 class="card-header mb-3 py-3">Mis Recetas</h4>
            <h6 class="modal-title mb-3">
                <a href="#" class="text-decoration-none text-info" data-bs-toggle="modal" data-bs-target="#addTournament">
                    Agregar una nueva receta.
                </a>
            </h6>
            <h6 class="modal-title mb-3">
                <a href="#" class="text-decoration-none text-info" data-bs-toggle="modal" data-bs-target="#addTournament">
                    Agregar recetas combinadas.
                </a>
            </h6>
            <div class="table-responsive">
            <table id="example" class="table table-dark table-striped table-hover">
                <thead class="table-warning">
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>
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
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark">
                                                <h5 class="modal-title" id="modalRecetaLabel">Receta: <?php echo $receta['nombre_receta']; ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body bg-light">
                                                <h6 class="modal-title mb-3">
                                                    <a href="#" class="text-decoration-none text-info" data-bs-toggle="modal" data-bs-target="#agregarIngredientes<?php echo $receta['id_receta']; ?>">
                                                        Agregar ingredientes.
                                                    </a>
                                                </h6>
                                                <form action="../controllers/controllerIngredientesRecetas.php" method="post" class="form-floating">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="idRecetaDelete" value="<?php echo $receta['id_receta'];?>">
                                                    <div class="table-responsive">
                                                        <table id="recetas" class="table table-dark table-striped table-hover">
                                                            <thead class="table-warning">
                                                                <tr>
                                                                    <th>Check</th>
                                                                    <th>Nombre</th>
                                                                    <th>Cantidad</th>
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
                                                                    echo '<td>' . $ingrediente['nombre_unidad'] . '</td>';
                                                                    echo '</tr>';
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="form-group mt-3 text-center">
                                                        <div class="col-md-12">
                                                            <input type="submit" class="btn btn-info text-white w-50 p-3" value="Eliminar seleccionados">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal agregar ingredientes -->
                                <div class="modal fade" id="agregarIngredientes<?php echo $receta['id_receta']; ?>" tabindex="-1" aria-labelledby="agregarIngredientesLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header text-bg-dark">
                                                <h6 class="modal-title" id="agregarIngredientesLabel">Agregar Ingredientes</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../controllers/controllerIngredientesRecetas.php" method="post" class="form-floating">
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
                                                                    echo '<td><input class="form-input" type="number" step="any" name="cantidades[' . $producto['id_producto'] . ']" placeholder="Ingrese cantidad" min="0" value="0" required></td>';
                                                                    echo '<td>' . $producto['nombre_unidad'] . '</td>';
                                                                    echo '<input type="hidden" name="unidades[' . $producto['id_producto'] . ']" value="' . $producto['id_unidad'] . '">';
                                                                    echo '</tr>';
                                                                }
                                                                ?>
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                    
                                                    <div class="form-group mt-3 text-center">
                                                        <div class="col-md-12">
                                                            <input type="submit" class="btn btn-info text-white w-50 p-3" value="Guardar">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo $receta['precio']; ?></td>
                            <td><?php echo $receta['tiempo_preparacion']; ?></td>
                            <td><?php echo $receta['nombre_tipo']; ?></td>
                            <td class="text-center">
                                <a href="../controllers/controllerRecetas.php?action=delete&id=<?php echo $receta['id_receta']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar esta receta?')" class="text-decoration-none">
                                    <i class="bi bi-trash-fill text-white mx-3"></i>
                                </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $receta['id_receta']?>" class="text-decoration-none">
                                    <i class="bi bi-pencil-square text-white"></i>
                                </a>

                                <!-- Editar receta -->
                                <div class="modal fade" id="editModal<?php echo $receta['id_receta']?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $receta['id_receta']?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header bg-dark">
                                        <h5 class="modal-title" id="editModalLabel<?php echo $receta['id_receta']?>">Editar receta</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                            <div class="form-group mb-3 text-start">
                                            <div class="form-group mb-3 text-start">
                                                <h6>Tipo de Receta</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="isPrincipal" name="isPrincipal" value="1" <?php echo ($receta['principal'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="isPrincipal">
                                                        Combinada
                                                    </label>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="isComplementaria" name="isComplementaria" value="1" <?php echo ($receta['complementaria'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="isComplementaria">
                                                        Complementaria
                                                    </label>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="isEspecial" name="isEspecial" value="1" <?php echo ($receta['especial'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="isEspecial">
                                                        Especial
                                                    </label>
                                                </div>
                                            </div>

                                            </div>

                                            <div class="form-group mt-3 text-center">
                                                <div class="col-md-12">
                                                    <input type="submit" class="btn btn-info text-white w-50 p-3" value="Guardar">
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header text-bg-dark">
            <h6 class="modal-title" id="addTournament">Agregar Nueva Receta</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <div class="form-group mb-3 text-start">
                        <h6>Tipo de Receta</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isPrincipalI" name="isPrincipalI" value="1">
                            <label class="form-check-label" for="isPrincipalI">
                                Combinada
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isComplementariaI" name="isComplementariaI">
                            <label class="form-check-label" for="isComplementariaI">
                                Complementaria
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isEspecialI" name="isEspecialI" value="1">
                            <label class="form-check-label" for="isEspecialI">
                                Especial
                            </label>
                        </div>
                    </div>
                    <div class="form-group mt-3 text-center">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-info text-white w-50 p-3" value="Guardar">
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
        $('#recetas, #example').DataTable({
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
    });
</script>



</body>
</html>
