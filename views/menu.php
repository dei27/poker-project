<?php
include ("../controllers/controllerRegistros.php");

if (isset($_SESSION["userId"])) {
    $recordsData = getAllTimesByUserId($_SESSION["userId"]);
    $times = json_decode($recordsData, true);
    $myTimes = json_decode(getTimesByUserId($_SESSION["userId"]), true);
}

$currentPage = basename($_SERVER['PHP_SELF']);
    switch ($currentPage) {
        case "dashboard.php":
            $link = "../index.php";
            break;
        case "tiposOrdenes.php":
            $link = "ordenes.php";
            break;
        case "actualizarOrden.php":
            $link = "ordenes.php";
            break;
        case "nuevaOrden.php":
        case "nuevaOrdenExpress.php":
            $link = "tiposOrdenes.php";
            break;
        default:
            $link = "dashboard.php";
            break;
    }
?>

<nav class="navbar sticky-top">
    <a href="<?php echo $link; ?>" class="text-decoration-none text-dark navbar-brand"><i class="bi bi-arrow-left-circle-fill text-dark fs-3 px-3"></i></a>
        <div class="navbar-brand">
            <a class="" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="bi bi-list text-dark fs-2 px-3"></i></a>
            <div class="w-50 offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel">Menú</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                <form action="../controllers/controller.php" method="post">
                    <input type="hidden" name="action" value="logout">
                    <div class="row">
                    <div class="btn-group mb-3">
                            <a href="dashboard.php" class="btn btn-secondary p-3">Inicio</a>        
                        </div>
                        <div class="btn-group mb-3">
                            <a href="#" class="btn btn-success p-3" data-bs-toggle="modal" data-bs-target="#registroHorarioModal">Crear Registro</a>        
                        </div>
                        <div class="btn-group mb-3">
                            <a class="btn btn-info text-white p-3" href="#" data-bs-toggle="modal" data-bs-target="#verRegistroModal">Mis registros</a>      
                        </div>
                        <div class="btn-group mb-3">
                            <button type="submit navbar-brand" class="btn btn-danger p-3">Cerrar sesión</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
</nav>

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
                <h5 class="modal-title" id="registroHorarioModalLabel">Registro de hoy <?php echo date('d-m-Y'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="table-responsive">
                <table id="detallesHorarios" class="table table-dark table-striped table-hover w-100">
                    <thead>
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
                                <td><?php echo date('d-m-Y h:i:s A', strtotime($time['hora'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>