<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../controllers/controllerUsuarios.php');
include('../controllers/controlleRegistrosFaltas.php');
include('../controllers/controllerTiposFaltas.php');


$usuarios = json_decode(getAllUsuarios(), true);
$tiposFalta = json_decode(getAllTiposFaltasHorarios(), true);
// $usuarios = json_decode(getAllUsuarios(), true);
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
    <title>Usuarios</title>
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
            <h5 class="card-header mb-3 py-3">Usuarios registrados</h5>
            <h5 class="card-text">
                <a href="#" class="text-decoration-none text-info" data-bs-toggle="modal" data-bs-target="#addTournament">
                    <img src="../assets/images/addUser.png" alt="logo usuario" class="img-fluid me-2">Nuevo usuario.
                </a>
            </h5>
            <div class="table-responsive ">
                <table id="example" class="table table-dark table-striped table-hover">
                    <thead class="table-warning">
                        <tr>
                            <th>Nickname</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Expediente</th>
                            <th>Habilitado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario):?>
                            <tr>
                                <td><?php echo $usuario['nickname']; ?></td>
                                <td><?php echo $usuario['email']; ?></td>
                                <td>
                                    <?php 
                                        switch ($usuario['role']) {
                                            case 1:
                                                echo "Administrador";
                                                break;
                                            case 2:
                                                echo "Cocinero";
                                                break;
                                            case 3:
                                                echo "Salonero";
                                                break;
                                            case 4:
                                                echo "Ayudante";
                                                break;
                                            case 5:
                                                echo "Mantenimiento";
                                                break;
                                            default:
                                                echo "Rol desconocido";
                                                break;
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="#" class="text-decoration-none text-info" data-bs-toggle="modal" data-bs-target="#registroFaltasHorarios<?php echo $usuario['id']?>"><i class="bi bi-file-earmark-person-fill text-white"></i></a>
                                </td>
                                <td><?php echo ($usuario['habilitado'] == 1) ? "Sí" : "No"; ?></td>
                                <td class="text-center">

                                <a href="#" class="text-decoration-none text-white mx-3" data-bs-toggle="modal" data-bs-target="#deshabilitarUsuario<?php echo $usuario['id']; ?>">
                                    <i class="bi bi-trash-fill text-white" 
                                    data-bs-toggle='tooltip' 
                                    data-bs-placement='top' 
                                    data-bs-custom-class='custom-tooltip' 
                                    data-bs-title='<?php echo ($usuario["habilitado"] == 1) ? "Deshabilitar usuario" : "Habilitar usuario"; ?>'></i>
                                </a>

                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editarUsuario<?php echo $usuario['id']?>" class="text-decoration-none">
                                        <i class="bi bi-pencil-square text-white" data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Editar usuario'></i>
                                    </a>

                                    <!-- Modal eliminar-->
                                    <div class="modal fade" id="deshabilitarUsuario<?php echo $usuario['id']; ?>" tabindex="-1" aria-labelledby="deshabilitarUsuarioLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header text-bg-dark">
                                                    <h5 class="modal-title" id="deshabilitarUsuarioLabel">Confirmar Acción</h5>
                                                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-dark">
                                                    <p><?php echo ($usuario["habilitado"] == 1) ? "¿Estás seguro de que quieres deshabilitar este usuario?" : "¿Estás seguro de que quieres habilitar este usuario?"; ?></p>
                                                </div>
                                                <div class="form-group mb-3 px-3">
                                                    <a href="../controllers/controllerUsuarios.php?action=<?php echo ($usuario["habilitado"] == 1) ? "delete" : "activate"; ?>&id=<?php echo $usuario['id']; ?>" class="btn btn-<?php echo ($usuario["habilitado"] == 1) ? "danger" : "success"; ?> w-100 py-3">
                                                        <i class="bi bi-cursor-fill text-white me-3"></i>
                                                        <?php echo ($usuario["habilitado"] == 1) ? "Sí, deshabilitar." : "Sí, habilitar."; ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- modal agregar registro falta expediente -->
                                    <div class="modal fade" id="registroFaltasHorarios<?php echo $usuario['id']?>" tabindex="-1" aria-labelledby="registroFaltasHorariosLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                            <div class="modal-header text-bg-dark">
                                                <h5 class="modal-title" id="registroFaltasHorarios<?php echo $usuario['id']?>">Agregar Registro Laboral para <?php echo $usuario['nickname']?></h5>
                                                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                                <div class="modal-body text-dark text-start">

                                                <div class="col text-end mb-3">
                                                    <a href="expedientesUsuarios.php?user=<?php echo $usuario['id']?>" class="btn btn-info p-3 w-50 text-white"><i class="bi bi-cursor-fill text-white me-3"></i>Ver expediente</a>
                                                </div>
                                                    

                                                    <form action="../controllers/controlleRegistrosFaltas.php" method="post" class="form-floating">
                                                        <input type="hidden" name="action" value="addRegistroFalta">
                                                        <input  id="usuarioRegistroFalta" name="usuarioRegistroFalta" value="<?php echo $usuario['id']?>" type="hidden">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group mb-3">
                                                                    <h6>Tipo de registro</h6>
                                                                    <select class="form-select" id="tipoRegistroFalta" name="tipoRegistroFalta" required>
                                                                        <option value="">Seleccionar</option>
                                                                        <?php foreach ($tiposFalta as $tipoFalta): ?>
                                                                            <option value="<?php echo $tipoFalta['id_tipo_falta']; ?>"><?php echo $tipoFalta['nombre_tipo_falta']; ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group mb-3">
                                                                    <label for="fechaInicioRegistroFalta">Inicio</label>
                                                                    <input class="form-control" id="fechaInicioRegistroFalta" name="fechaInicioRegistroFalta" required type="date">
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-group mb-3">
                                                                    <label for="fechaFinRegistroFalta">Final</label>
                                                                    <input class="form-control" id="fechaFinRegistroFalta" name="fechaFinRegistroFalta" required type="date">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="form-floating">
                                                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" id="notasRegistroFalta" name="notasRegistroFalta"></textarea>
                                                            <label for="floatingTextarea2">Notas</label>
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

                                    <!-- Editar usuario -->
                                    <div class="modal fade" id="editarUsuario<?php echo $usuario['id']?>" tabindex="-1" aria-labelledby="editarUsuarioLabel<?php echo $usuario['id']?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                        <div class="modal-header bg-dark">
                                            <h5 class="modal-title" id="editarUsuarioLabel<?php echo $usuario['id']?>">Editar Usuario</h5>
                                            <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-dark text-start">
                                            <!-- Edit Form -->
                                            <form action="../controllers/controllerUsuarios.php" method="post">
                                                <input type="hidden" name="action" value="editUsuario">
                                                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $usuario['id']?>">

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label for="nicknameUsuarioUpdate<?php echo $usuario['id']?>" class="form-label">Nickname</label>
                                                            <input type="text" class="form-control" id="nicknameUsuarioUpdate<?php echo $usuario['id']?>" name="nicknameUsuarioUpdate" placeholder="Nickname ..." value="<?php echo $usuario['nickname']?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label for="emailUsuarioUpdate<?php echo $usuario['id']?>" class="form-label">Correo</label>
                                                            <input type="email" class="form-control" id="emailUsuarioUpdate<?php echo $usuario['id']?>" name="emailUsuarioUpdate" placeholder="Nickname ..." value="<?php echo $usuario['email']?>" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label for="cedulaUsuarioUpdate<?php echo $usuario['id']?>">Cédula</label>
                                                            <input class="form-control" id="cedulaUsuarioUpdate<?php echo $usuario['id']?>" name="cedulaUsuarioUpdate" placeholder="Cédula..." required value="<?php echo $usuario['cedula']?>">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label for="telefono_usuarioUsuarioUpdate<?php echo $usuario['id']?>">Teléfono</label>
                                                            <input class="form-control" id="telefono_usuarioUsuarioUpdate<?php echo $usuario['id']?>" name="telefono_usuarioUsuarioUpdate" placeholder="Celular..." required value="<?php echo $usuario['telefono_usuario']?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label for="nacimientoUsuarioUpdate">Fecha Nacimiento</label>
                                                            <input class="form-control" id="nacimientoUsuarioUpdate" name="nacimientoUsuarioUpdate" required type="date" value="<?php echo $usuario['fecha_nacimiento']?>">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label for="ingresoUsuarioUpdate">Fecha de Ingreso</label>
                                                            <input class="form-control" id="ingresoUsuarioUpdate" name="ingresoUsuarioUpdate" required type="date" value="<?php echo $usuario['fecha_ingreso']?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="passwordUsuario" class="form-label">Actualizar Contraseña</label>
                                                    <input type="text" class="form-control" id="passwordUsuario" name="passwordUsuario" placeholder="Escriba acá la nueva contraseña ..." value="">
                                                </div>
                                                <?php if ($usuario['role'] !== 1): ?>
                                                    <div class="form-group mb-3">
                                                        <h6 class="form-label">Role de Usuario</h6>
                                                        <select class="form-select" name="role" required>
                                                            <option value="">Seleccionar</option>
                                                            <option value="2" <?php if ($usuario['role'] == 2) echo "selected"; ?>>Cocinero</option>
                                                            <option value="3" <?php if ($usuario['role'] == 3) echo "selected"; ?>>Salonero</option>
                                                            <option value="4" <?php if ($usuario['role'] == 4) echo "selected"; ?>>Ayudante</option>
                                                            <option value="5" <?php if ($usuario['role'] == 5) echo "selected"; ?>>Mantenimiento</option>
                                                        </select>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col-md-12">
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

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- modal agregar usuario nuevo -->
<div class="modal fade" id="addTournament" tabindex="-1" aria-labelledby="addTournamentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
        <div class="modal-header text-bg-dark">
            <h5 class="modal-title" id="addTournament">Agregar Nuevo Usuario</h5>
            <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <div class="modal-body">
                <form action="../controllers/controllerUsuarios.php" method="post" class="form-floating">
                    <input type="hidden" name="action" value="addUsuario">

                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-3">
                                <label for="nicknameUsuarioNuevo">Nickname</label>
                                <input class="form-control" id="nicknameUsuarioNuevo" name="nicknameUsuarioNuevo" placeholder="Nickname..." required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-3">
                                <label for="emailUsuarioNuevo">Correo</label>
                                <input type="email" class="form-control" id="emailUsuarioNuevo" name="emailUsuarioNuevo" placeholder="Correo..." required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-3">
                                <label for="cedulaUsuarioNuevo">Cédula</label>
                                <input class="form-control" id="cedulaUsuarioNuevo" name="cedulaUsuarioNuevo" placeholder="Cédula..." required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-3">
                                <label for="telefono_usuarioUsuarioNuevo">Teléfono</label>
                                <input class="form-control" id="telefono_usuarioUsuarioNuevo" name="telefono_usuarioUsuarioNuevo" placeholder="Celular..." required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-3">
                                <label for="nacimientoUsuarioNuevo">Fecha Nacimiento</label>
                                <input class="form-control" id="nacimientoUsuarioNuevo" name="nacimientoUsuarioNuevo" required type="date">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-3">
                                <label for="ingresoUsuarioNuevo">Fecha de Ingreso</label>
                                <input class="form-control" id="ingresoUsuarioNuevo" name="ingresoUsuarioNuevo" required type="date">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="passwordUsuarioNuevo">Contraseña</label>
                        <input class="form-control" id="passwordUsuarioNuevo" name="passwordUsuarioNuevo" placeholder="Contraseña..." required>
                    </div>

                    <div class="row">
                        <div class="form-group mb-3">
                            <h6 class="form-label">Role de Usuario</h6>
                            <select class="form-select" name="roleNuevo" required>
                                <option value="">Seleccionar</option>
                                <option value="2">Cocinero</option>
                                <option value="3">Salonero</option>
                                <option value="4">Ayudante</option>
                                <option value="5">Mantenimiento</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mt-3 text-end">
                        <div class="col-md-12">
                        <button type="submit" class="btn btn-primary  text-white w-100 p-3">
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
if (isset($_GET['insertedUser'])) {
    $updatedCategoryStatus = $_GET['insertedUser'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Agregado con éxito',
            'text' => 'El usuario fue añadido.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al agregar',
            'text' => 'No se pudo añadir el usuario.',
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

if (isset($_GET['insertedRegistro'])) {
    $updatedCategoryStatus = $_GET['insertedRegistro'];
    
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

if (isset($_GET['updatedUser'])) {
    $updatedCategoryStatus = $_GET['updatedUser'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Actualizado con éxito',
            'text' => 'Se realizaron los cambios.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al actualizar',
            'text' => 'No se pudo realizar los cambios.',
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





if (isset($_GET['nickEmail']) && $_GET['nickEmail'] == 1) {
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "error",
            title: "Error al agregar",
            timer: 3000,
            text: "El nickname y el correo están en uso.",
            showConfirmButton: false
        });
    </script>
    ';
}

if (isset($_GET['nickname']) && $_GET['nickname'] == 1) {
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "error",
            title: "Error al agregar",
            timer: 3000,
            text: "El nickname está en uso.",
            showConfirmButton: false
        });
    </script>
    ';
}

if (isset($_GET['email']) && $_GET['email'] == 1) {
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "error",
            title: "Error al agregar",
            timer: 3000,
            text: "El email está en uso.",
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {

        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

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

        function verificarExistencia(campo, valor, id_usuario) {
            $.ajax({
                type: 'POST',
                url: '../controllers/controllerUsuarios.php',
                data: {
                    action: 'validarCamposUsuario',
                    campo: campo,
                    valor: valor
                },
                dataType: 'json',
                success: function(response) {
                    if (response.existe) {
                        let mensaje;
                        switch (campo) {
                            case 'cedula':
                                mensaje = '¡La cédula ingresada ya está en el sistema!';
                                break;
                            case 'nickname':
                            mensaje = '¡El nickname ingresado ya está en el sistema!';
                            break;
                            case 'email':
                            mensaje = '¡El correo ingresado ya está en el sistema!';
                            break;
                            case 'telefono_usuario':
                            mensaje = '¡El teléfono ingresado ya está en el sistema!';
                            break;
                            default:
                                mensaje = '¡Error!';
                        }
                        mostrarSweetAlert(mensaje);
                        $('#' + campo + 'UsuarioNuevo').val('');
                        $('#' + campo + 'UsuarioUpdate' + id_usuario).val('');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al verificar usuario:', error);
                }
            });
        }

        $('#nicknameUsuarioNuevo, #emailUsuarioNuevo, #cedulaUsuarioNuevo, #telefono_usuarioUsuarioNuevo').on('keypress', function(event) {
            // Verificar si la tecla presionada es Enter (código 13)
            if (event.which === 13 || event.keyCode === 13) {
                event.preventDefault(); // Evitar que se envíe el formulario
            }
        });

        $('#nicknameUsuarioNuevo, #emailUsuarioNuevo, #cedulaUsuarioNuevo, #telefono_usuarioUsuarioNuevo').on('blur', function() {
            let nuevo_valor = $(this).val();
            let campo;
            if ($(this).attr('id').includes('nickname')) {
                campo = 'nickname';
            } else if ($(this).attr('id').includes('email')) {
                campo = 'email';
            } else if ($(this).attr('id').includes('cedula')) {
                campo = 'cedula';
            } else if ($(this).attr('id').includes('telefono_usuario')) {
                campo = 'telefono_usuario';
            }
            verificarExistencia(campo, nuevo_valor);
        });


        $('a[data-bs-toggle="modal"]').on('click', function() {
            var modalID = $(this).attr('data-bs-target');
            var id_usuario = modalID.replace('#editarUsuario', ''); // Quitar el prefijo '#editarUsuario' para obtener solo el ID

            $('#cedulaUsuarioUpdate' + id_usuario + ', #nicknameUsuarioUpdate' + id_usuario + ', #emailUsuarioUpdate' + id_usuario + ', #telefono_usuarioUsuarioUpdate' + id_usuario).on('keypress', function(event) {
                // Verificar si la tecla presionada es Enter (código 13)
                if (event.which === 13 || event.keyCode === 13) {
                    event.preventDefault(); // Evitar que se envíe el formulario
                }
            });

            $('#cedulaUsuarioUpdate' + id_usuario + ', #nicknameUsuarioUpdate' + id_usuario + ', #emailUsuarioUpdate' + id_usuario + ', #telefono_usuarioUsuarioUpdate' + id_usuario).on('blur', function() {
                let nuevo_valor = $(this).val();
                let campo;
                if ($(this).attr('id').includes('cedula')) {
                    campo = 'cedula';
                } else if ($(this).attr('id').includes('nickname')) {
                    campo = 'nickname';
                } else if ($(this).attr('id').includes('email')) {
                    campo = 'email';
                } else if ($(this).attr('id').includes('telefono_usuario')) {
                    campo = 'telefono_usuario';
                }
                verificarExistencia(campo, nuevo_valor, id_usuario);
            });

        });

        function mostrarSweetAlert(mensaje) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: mensaje,
                showCloseButton: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false 
            });
        }

    });

    
</script>
</body>
</html>
