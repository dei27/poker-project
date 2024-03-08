<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();

include('../controllers/controllerUsuarios.php');
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
if (isset($_SESSION["user"]) && (isset($_SESSION['role']) && $_SESSION['role'] === 1)) {
?>
    <div class="container-fluid p-5">
        <div class="table-responsive card p-3">
            <h4 class="card-header mb-3 py-3">Horarios</h4>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 mb-3">
                    <div class="table-responsive">
                        <table id="todosLosHorarios" class="table table-dark table-striped table-hover caption-top">
                            <caption>Registros de esta semana</caption>
                            <thead class="table-warning">
                                <tr>
                                    <th>Usuario</th>
                                    <th>Tipo</th>
                                    <th>Hora</th>
                                    <th>Motivo de cambio</th>
                                    <th>Realizado Por</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($todosHorarios as $time): 
                                    $cambiosData = json_decode(getMotivoCambio($time['id_registro']),true);
                                ?>

                                    
                                    <tr>
                                        <td><?php echo $time['nickname']; ?></td>
                                        <td><?php echo $time['nombre_tipo']; ?></td>
                                        <td><?php echo $time['hora']; ?></td>
                                        <td>
                                            <?php 
                                            if ($cambiosData && isset($cambiosData[0]["motivo_cambio"]) && $cambiosData[0]["motivo_cambio"] !== null) {
                                                echo $cambiosData[0]["motivo_cambio"];
                                            } else {
                                                echo "Sin cambios";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if ($cambiosData && isset($cambiosData[0]["usuario"]) && $cambiosData[0]["usuario"] !== null) {
                                                echo $cambiosData[0]["usuario"];
                                            } else {
                                                echo "Sin cambios";
                                            }
                                            ?>
                                        </td>


                                    <td>
                                        <a href="#" class="text-decoration-none text-white mx-3" data-bs-toggle="modal" data-bs-target="#editarModal<?php echo $time['id_registro']; ?>">
                                        <i class="bi bi-pencil-fill text-white" data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Editar registro'></i>
                                        </a>

                                        <!-- Modal editar-->
                                        <div class="modal fade" id="editarModal<?php echo $time['id_registro']; ?>" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header text-bg-dark">
                                                        <h5 class="modal-title" id="editarModalLabel">Editar horario</h5>
                                                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-dark">
                                                        <form action="../controllers/controllerRegistros.php" method="post" class="form-floating">
                                                            <input type="hidden" name="id_registro" value="<?php echo $time['id_registro']; ?>">
                                                            <input type="hidden" name="action" value="udpateHorario">

                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="form-group mb-3">
                                                                    <label for="nombreUsuario">Nickname</label>
                                                                    <input value="<?php echo $time['nickname'];?>" readonly class="form-control" name="nombreUsuario" id="nombreUsuario">
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                <div class="form-group mb-3">
                                                                    <label for="registroTipoUsuario">Registro</label>
                                                                    <input value="<?php echo $time['nombre_tipo'];?>" readonly class="form-control" name="registroTipoUsuario" id="registroTipoUsuario">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="form-group mb-3">
                                                                        <label for="fechaRegistroUsuario">Fecha</label>
                                                                        <input type="date" name="fechaRegistroUsuario" id="fechaRegistroUsuario" class="form-control"  value="<?php echo date('Y-m-d', strtotime($time['hora'])); ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="form-group mb-3">
                                                                        <label for="horaRegistroUsuario">Hora</label>
                                                                        <input type="time" name="horaRegistroUsuario" id="horaRegistroUsuario" class="form-control" min="11:00" max="21:00" required value="<?php echo date('H:i', strtotime($time['hora'])); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="form-group mb-3">
                                                                    <label for="exampleDataList" class="form-label">Seleccione o escriba el motivo del cambio</label>
                                                                    <input class="form-control" list="datalistOptions" id="exampleDataList" name="motivo_cambio" placeholder="Escriba para buscar..." required>
                                                                    <datalist id="datalistOptions">
                                                                        <option value="Olvidó registrar">
                                                                        <option value="No había sistema">
                                                                    </datalist>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group mt-3 mb-1">
                                                                    <input type="submit" class="btn btn-primary w-100 py-3" value="Guardar">
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
if (isset($_GET['updateRecord'])) {
    $updatedCategoryStatus = $_GET['updateRecord'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Actualizado con éxito',
            'text' => 'Registro actualizado.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al actualizar',
            'text' => 'No se pudo actualizar el registro.',
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

        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
        
        $('#todosLosHorarios').DataTable({
            lengthChange: false,
            pageLength: 4,
            info: false,
            responsive: true,
            "order": [[2, 'asc']],
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
