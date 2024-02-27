<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
    <title>Horarios</title>
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
            <h4 class="card-header mb-3 py-3">Mis horarios</h4>

            <div class="row mb-3">
                <div class="col text-start mb-1">
                    <h5 class="card-text">
                        <a href="#" class="text-decoration-none text-info" data-bs-toggle="modal" data-bs-target="#registroHorarioModalNuevo">
                        <img src="../assets/images/reloj.png" alt="Crear torneo" class="img-fluid"><span class="ms-3">Nuevo Registro</span></a> 
                    </h5>
                </div>
                <div class="col text-end mb-1">
                    <?php
                    if(isset($_SESSION["role"]) && $_SESSION["role"] === 1) {
                    ?>
                        <h5 class="card-text">
                            <a href="actualizarHorarios.php" class="text-decoration-none text-info">
                                <img src="../assets/images/calendario.png" alt="Crear torneo" class="img-fluid">
                                <span class="ms-3">Editar horarios</span>
                            </a> 
                        </h5>
                    <?php
                    }
                    ?>
                </div>
            </div>
            

            

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 mb-3">
                    <ul>
                        <?php if (isset($tiempoAlmuerzoWeek) && !empty($tiempoAlmuerzoWeek)) { ?>
                            <li>
                                <h6>El tiempo total de almuerzo semanal es: <span><?php echo $tiempoAlmuerzoWeek; ?></span></h6>
                            </li>
                        <?php } ?>

                        <?php if (isset($horasTrabajadasWeek) && !empty($horasTrabajadasWeek)) { ?>
                        <li>
                            <h6>El tiempo total trabajado semanal es: <span><?php echo $horasTrabajadasWeek; ?></span></h6>
                        </li>
                        <?php } ?>
                    </ul>
                    <div class="table-responsive">
                        <table id="horarioSemanal" class="table table-dark table-striped table-hover caption-top">
                            <caption>Registros de esta semana</caption>
                            <thead class="table-warning">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($myWeek as $time): ?>
                                    <tr>
                                        <td><?php echo $time['nombre_tipo']; ?></td>
                                        <td><?php echo date('d-m-Y H:i:s', strtotime($time['hora'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-6  mb-3">
                    <ul>
                        <?php if (isset($tiempoAlmuerzo) && !empty($tiempoAlmuerzo)) { ?>
                            <li>
                                <h6>El tiempo total de almuerzo de hoy es: <span><?php echo $tiempoAlmuerzo; ?></span></h6>
                            </li>
                        <?php } ?>

                        <?php if (isset($horasTrabajadas) && !empty($horasTrabajadas)) { ?>
                            <li>
                            <h6>El tiempo total trabajado de hoy es: <span><?php echo $horasTrabajadas; ?></span></h6>
                        </li>
                        <?php } ?>   
                    </ul>
                    
                    <div class="table-responsive">
                        <table id="misHorarios" class="table table-dark table-striped table-hover caption-top">
                            <caption>Registros de hoy <?php echo date('d-m-Y'); ?></caption>
                            <thead class="table-warning">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($myTimes as $time): ?>
                                    <tr>
                                        <td><?php echo $time['nombre_tipo']; ?></td>
                                        <td><?php echo date('d-m-Y H:i:s', strtotime($time['hora'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>

<!-- Modal para Registro de Horarios -->
<div class="modal fade" id="registroHorarioModalNuevo" tabindex="-1" aria-labelledby="registroHorarioModalNuevoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-bg-dark">
                <h5 class="modal-title" id="registroHorarioModalNuevoLabel">Registro de Horarios</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <!-- Formulario de Registro de Horarios -->
                <form action="../controllers/controllerRegistros.php" method="post">
                    <input type="hidden" name="action" value="addHorario">
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
                    <button type="submit" class="btn btn-primary w-100 py-3"><i class="bi bi-cursor-fill text-white me-3"></i>Registrar</button>
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
if (isset($_GET['addRecord'])) {
    $updatedCategoryStatus = $_GET['addRecord'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Creada con éxito',
            'text' => 'Registro creado.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al crear',
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
    $(document).ready(function() {
        $('#misHorarios').DataTable({
            lengthChange: false,
            pageLength: 5,
            info: false,
            responsive: true,
            paging: false,
            "order": [[2, 'asc']],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            "order": [[1, 'asc']],
            initComplete: function(settings, json) {
                $(".dataTables_filter label").addClass("text-dark");
            }
        });

        $('#horarioSemanal').DataTable({
            lengthChange: false,
            pageLength: 8,
            info: false,
            responsive: true,
            order: [[1, 'asc']],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            "order": [[1, 'asc']],
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
