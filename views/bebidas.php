<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../controllers/controllerBebidas.php');

$bebidas = json_decode(getAllBebidas(), true);
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
    <title>Bebidas</title>
</head>
<body>
    <header>
    <?php 
        if(isset($_SESSION["user"])){
            include('nav_admin.php');
        }else{
            echo 
            '<nav class="navbar sticky-top">
                <a href="../index.php" class="text-decoration-none text-dark navbar-brand"><i class="bi bi-arrow-left-circle-fill text-dark fs-3 px-3"></i></a>
            </nav>';
        }
    ?>
    </header>

<?php if (isset($_SESSION["user"])) { ?>
    <div class="container-fluid mt-5 p-5">
        <div class="card p-3">
            <h4 class="card-header mb-3 py-3">Bebidas</h4>
            <?php
            if(isset($_SESSION["user"])){
            echo '<h5 class="card-text">
                    <a href="#" class="text-decoration-none text-info" data-bs-toggle="modal" data-bs-target="#addTournament">
                    <img src="../assets/images/bebidas.png" alt="Crear torneo" class="img-fluid"> Agregar nuevo bebida.
                    </a>
                </h5>';
            }
            ?>
            <div class="table-responsive">
            <table id="example" class="table table-dark table-striped table-hover">
                <thead class="table-warning">
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>     
                        <?php 
                            if(isset($_SESSION["user"])){
                                echo '<th>Acciones</th>';
                            }
                        ?>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bebidas as $bebida):?>
                        <tr>
                            <td><?php echo empty($bebida['nombre_bebida']) ? 'Sin nombre' : $bebida['nombre_bebida']; ?></td>
                            <td><?php echo empty($bebida['precio_bebida']) ? 'Sin precio' : $bebida['precio_bebida']; ?></td>
                            <td>
                                <a href="../controllers/controllerBebidas.php?action=delete&id=<?php echo $bebida['id_bebida']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar esta bebida?')" class="text-decoration-none text-white mx-3">
                                    <i class="bi bi-trash-fill text-white"></i>
                                </a>
                                <a href="#" class="text-decoration-none text-white" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $bebida['id_bebida']; ?>">
                                    <i class="bi bi-pencil-fill text-white"></i>
                                </a>
                            </td>

                                <div class="modal fade" id="editModal<?php echo $bebida['id_bebida']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $bebida['id_bebida']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header text-bg-dark">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $bebida['id_bebida']; ?>">Editar Bebida</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../controllers/controllerBebidas.php" method="post" class="form-floating">
                                                    <input type="hidden" name="id" value="<?php echo $bebida['id_bebida']; ?>">
                                                    <input type="hidden" name="action" value="edit">

                                                    <div class="form-group mb-3">
                                                        <label for="nombreBebida">Nombre</label>
                                                        <input class="form-control" id="nombreBebida" name="nombreBebida" placeholder="Nombre..." value="<?php echo $bebida['nombre_bebida']; ?>" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="precioBebida" class="form-label">Precio</label>
                                                        <input type="number" class="form-control" id="precioBebida" name="precioBebida" placeholder="Precio..." step="any" value="<?php echo htmlspecialchars($bebida['precio_bebida']); ?>" min=1 required>
                                                    </div>
                                                    <div class="form-group mb-3 text-end">
                                                        <input type="submit" class="btn btn-primary" value="Guardar">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>

        <!-- modal agregar producto -->
        <div class="modal fade" id="addTournament" tabindex="-1" aria-labelledby="addTournamentLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header text-bg-dark">
                    <h5 class="modal-title" id="addTournament">Agregar Nueva Bebida</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                        <form action="../controllers/controllerBebidas.php" method="post" class="form-floating">
                            <input type="hidden" name="action" value="add">
                            <div class="form-group mb-3">
                                <label for="nombreBebidaI">Nombre</label>
                                <input class="form-control" id="nombreBebidaI" name="nombreBebidaI" placeholder="Nombre..." value="" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="precioBebidaI">Precio</label>
                                <input type="number" class="form-control" id="precioBebidaI" name="precioBebidaI" placeholder="Precio..." step="any" required min=1>
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
    </div>

    <?php
        if (isset($_GET['updatedBebida'])) {
            $status = $_GET['updatedBebida'];
            
            $messageConfig = ($status == 1)
                ? [
                    'icon' => 'success',
                    'title' => 'Actualizado con éxito',
                    'text' => 'Bebida actualizada.',
                ]
                : [
                    'icon' => 'error',
                    'title' => 'Error al actualizar',
                    'text' => 'No se pudo actualizar la bebida.',
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

        if (isset($_GET['deleteBebida'])) {
            $status = $_GET['deleteBebida'];
            
            $messageConfig = ($status == 1)
                ? [
                    'icon' => 'success',
                    'title' => 'Eliminado con éxito',
                    'text' => 'Bebida eliminada.',
                ]
                : [
                    'icon' => 'error',
                    'title' => 'Error al eliminar',
                    'text' => 'No se pudo eliminar la bebida.',
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

        if (isset($_GET['insertedBebida'])) {
            $status = $_GET['insertedBebida'];
            
            $messageConfig = ($status == 1)
                ? [
                    'icon' => 'success',
                    'title' => 'Agregada con éxito',
                    'text' => 'Bebida agrega.',
                ]
                : [
                    'icon' => 'error',
                    'title' => 'Error al eliminar',
                    'text' => 'No se pudo agregar la bebida.',
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

<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            lengthChange: false,
            pageLength: 10,
            info: false,
            responsive: true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        });
    });  
</script>
</body>
</html>
