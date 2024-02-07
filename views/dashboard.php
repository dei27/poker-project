<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include ("../controllers/controllerRegistros.php");

if (isset($_SESSION["userId"])) {
    $recordsData = getAllTimesByUserId($_SESSION["userId"]);
    $times = json_decode($recordsData, true);
}

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
    <title>Admin</title>
</head>
<body>
<header>
    <?php 
        if(isset($_SESSION["user"])){
            echo 
            '<nav class="navbar sticky-top">
                <a href="../index.php" class="text-decoration-none text-dark navbar-brand"><i class="bi bi-arrow-left-circle-fill text-dark fs-3 px-3"></i></a>
                <div class="navbar-brand">
                    <form action="../controllers/controller.php" method="post">
                        <input type="hidden" name="action" value="logout">
                        <div class="btn-group">
                            <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Mi horario
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a href="#" class="dropdown-item text-white" data-bs-toggle="modal" data-bs-target="#registroHorarioModal">Registrar</a></li>
                                <li><a class="dropdown-item text-white" href="#" data-bs-toggle="modal" data-bs-target="#verRegistroModal">Ver</a></li>
                            </ul>
                        </div>
                        <button type="submit navbar-brand" class="btn btn-danger">Cerrar sesión</button>
                    </form>
                </div>
            </nav>';
        }else{
            echo 
            '<nav class="navbar sticky-top">
                <a href="../index.php" class="text-decoration-none text-dark navbar-brand"><i class="bi bi-arrow-left-circle-fill text-dark fs-3 px-3"></i></a>
            </nav>';
        }
    ?>
</header>

<main>
    <?php
    if (isset($_SESSION['user'])) {
        
        echo "<div class='container-sm mt-5 p-5'>
                <div class='card p-3 border-0 bg-transparent'>
                    <div class='row'>
                        <div class='col-6'>
                            <a href='ordenes.php' class='col-6 text-center w-100 my-5 p-1 rounded-pill border-0 btn btn-light py-3' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Ver órdenes'><img src='../assets/images/pedidos.png' alt='libro de recetas' class='img-fluid'></a>
                            <a href='productos.php' class='col-6 text-center w-100 my-5 p-1 rounded-pill border-0 btn btn-light py-3' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Ver productos'><img src='../assets/images/ingredientes.png' alt='libro de recetas' class='img-fluid'></a>
                            
                        </div>
                        <div class='col-6'>
                            <a href='recetas.php' class='col-6 text-center w-100 my-5 p-1 rounded-pill border-0 btn btn-light py-3' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Ver recetas'><img src='../assets/images/recetas.png' alt='libro de recetas' class='img-fluid'></a>
                            <a href='categorias.php' class='col-6 text-center w-100 my-5 p-1 rounded-pill border-0 btn btn-light py-3' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Ver categorías'><img src='../assets/images/categorias.png' alt='libro de recetas' class='img-fluid'></a>    
                        </div>
                    </div>
                </div>
            </div>";
    } else { 
        echo '<div class="container-fluid mt-5 vh-100 p-5">
            <div class="card p-3">
                <p class="card-text py-5">No tienes el poder suficiente para poder ver esto. <a href="login.php">Inicia sesión</a>.</p>
            </div>
        </div>';

    }
?>
</main>

<!-- Modal para Registro de Horarios -->
<div class="modal fade" id="registroHorarioModal" tabindex="-1" aria-labelledby="registroHorarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-bg-secondary">
                <h5 class="modal-title" id="registroHorarioModalLabel">Registro de Horarios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <!-- Formulario de Registro de Horarios -->
                <form action="../controllers/controllerRegistros.php" method="post">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3 text-start">
                        <label for="tipoRegistro" class="form-label">Tipo de Registro</label>
                        <select class="form-select" id="tipoRegistro" name="tipoRegistro">
                            <?php
                            if (empty($times)) {
                                echo '<option value="0" disabled selected>Ya no quedan opciones disponibles</option>';
                            } else {
                                foreach ($times as $time) {
                                    echo '<option value="' . $time['id_tipo_registro'] . '">' . $time['nombre_tipo'] . '</option>';
                                }
                            }
                            ?>
                        </select>


                    </div>
                    <input type="hidden" id="horaRegistro" name="horaRegistro" value="<?php date_default_timezone_set('America/Costa_Rica'); echo date('Y-m-d H:i:s'); ?>">
                    <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION['userId']; ?>">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Registro de Horarios -->
<div class="modal fade" id="verRegistroModal" tabindex="-1" aria-labelledby="registroHorarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-bg-secondary">
                <h5 class="modal-title" id="registroHorarioModalLabel">Registro de Horarios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">

            </div>
        </div>
    </div>
</div>

<?php
if (isset($_GET['addRecord'])) {
    $updatedCategoryStatus = $_GET['addRecord'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Agregado con éxito',
            'text' => 'Registro creado.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al agregar',
            'text' => 'No se pudo crear el registro.',
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

if (isset($_GET['noTypeAvailable'])) {
    $updatedCategoryStatus = $_GET['noTypeAvailable'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Agregado con éxito',
            'text' => 'Registro creado.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Ya agregaste todos los tipos de registros disponibles hoy.',
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


<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>

</body>
</html>
