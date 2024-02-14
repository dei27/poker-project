<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../controllers/controllerCategories.php');

$categoriesData = getAllCategories();
$categories = json_decode($categoriesData, true);
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
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Categorías</title>
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
        <div class="table-responsive card p-3">
            <div class="card-header mb-3 py-3">Categorías registradas</div>
            <h5 class="card-text">
                <a href="#" class="text-decoration-none text-info" data-bs-toggle="modal" data-bs-target="#addTournament">
                <img src="../assets/images/addProduct.png" alt="Crear torneo" class="img-fluid">Nueva categoría.
                </a>
            </h5>
            <table id="example" class="table table-dark table-striped table-hover">
                <thead class="table-warning">
                    <tr>
                        <th>Nombre</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category):?>
                        <tr>
                            <td><?php echo $category['nombre_categoria']; ?></td>
                            <td class="text-center">
                                <a href="../controllers/controllerCategories.php?action=delete&id=<?php echo $category['id_categoria']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar esta categoría?')" class="text-decoration-none">
                                    <i class="bi bi-trash-fill text-white mx-3"></i>
                                </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $category['id_categoria']?>" class="text-decoration-none">
                                    <i class="bi bi-pencil-square text-white"></i>
                                </a>

                                <!-- Editar categoria -->
                                <div class="modal fade" id="editModal<?php echo $category['id_categoria']?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $category['id_categoria']?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header bg-dark">
                                        <h5 class="modal-title" id="editModalLabel<?php echo $category['id_categoria']?>">Editar Categoría</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-dark">
                                        <!-- Edit Form -->
                                        <form action="../controllers/controllerCategories.php" method="post">
                                            <input type="hidden" name="action" value="editCategory">
                                            <input type="hidden" name="id_categoria" value="<?php echo $category['id_categoria']?>">
                                            <div class="form-group mb-3">
                                                <label for="nombreCategoria" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="nombreCategoria" name="nombreCategoria" placeholder="Nombre Categoria..." value="<?php echo $category['nombre_categoria']?>" required>
                                            </div>
                                            <div class="form-group mb-3 text-end">
                                                <input type="submit" class="btn btn-primary" value="Guardar">
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
<!-- modal agregar categoria -->
<div class="modal fade" id="addTournament" tabindex="-1" aria-labelledby="addTournamentLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header text-bg-dark">
                    <h5 class="modal-title" id="addTournament">Agregar Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                        <form action="../controllers/controllerCategories.php" method="post" class="form-floating">
                            <input type="hidden" name="action" value="add">
                            <div class="form-group mb-3">
                                <label for="nombreCategoriaI">Nombre</label>
                                <input class="form-control" id="nombreCategoriaI" name="nombreCategoriaI" placeholder="Nombre categoría..." value="" required>
                            </div>
                            <div class="form-group mt-3 text-end">
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
if (isset($_GET['deleteCategory'])) {
    $updatedCategoryStatus = $_GET['deleteCategory'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Eliminada con éxito',
            'text' => 'Categoría eliminada.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al eliminar',
            'text' => 'No se pudo eliminar la categoría.',
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
if (isset($_GET['updatedCategory'])) {
    $updatedCategoryStatus = $_GET['updatedCategory'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Actualizado con éxito',
            'text' => 'Categoría actualizada.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al actualizar',
            'text' => 'No se pudo actualizar la categoría.',
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

if (isset($_GET['insertedCategory'])) {
    $updatedCategoryStatus = $_GET['insertedCategory'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Creada con éxito',
            'text' => 'Categoría creada.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al crear',
            'text' => 'No se pudo crear la categoría.',
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

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            lengthChange: false,
            pageLength: 5,
            info: false,
            responsive: true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
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
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            "order": [[1, 'asc']],
            initComplete: function(settings, json) {
                $(".dataTables_filter label").addClass("text-dark");
            }
        });


    });

    
</script>
</body>
</html>
