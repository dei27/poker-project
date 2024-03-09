<?php
include ("../controllers/controllerRegistros.php");

if (isset($_SESSION["userId"])) {
    $recordsData = getAllTimesByUserId($_SESSION["userId"]);
    $times = json_decode($recordsData, true);
    $myTimes = json_decode(getTimesByUserId($_SESSION["userId"]), true);
    $myTimeTotal = json_decode(getTimesByDay($_SESSION["userId"]), true);
    $dataWeek = json_decode(getTimeByWeek($_SESSION["userId"]), true);
    $myWeek = json_decode(getAllTimeWeeks($_SESSION["userId"]), true);
    $todosHorarios = json_decode(getAllHorariosUsuarios(), true);

    
    if (isset($_GET['user'])) {
        $usuario = $_GET['user'];
        $todosLosRegistros = json_decode(getTimesByUsuarioIdAndYearMonthDay($usuario), true); 
    }


    if(!empty($myTimeTotal)){

        foreach ($myTimeTotal as $time){
            $tiempoAlmuerzo =  $time["tiempo_almuerzo"];
            $horasTrabajadas = $time["total_horas_trabajadas"];
        }
    }

    if(!empty($dataWeek)){

        foreach ($dataWeek as $week){
            $tiempoAlmuerzoWeek =  $week["total_tiempo_almuerzo"];
            $horasTrabajadasWeek = $week["total_horas_trabajadas"];
        }
    }

}

$currentPage = basename($_SERVER['PHP_SELF']);
    switch ($currentPage) {
        case "dashboard.php":
            $link = "../index.php";
            break;
        case "graficosPedidos.php":
            $link = "ordenes.php";
            break;
        case "graficosCajas.php":
            $link = "cerrarCaja.php";
            break;
        case "graficosRecetas.php":
            $link = "recetas.php";
            break;
        case "graficosBebidas.php":
            $link = "bebidas.php";
            break;
                
        case "expedientesUsuarios.php":
            $link = "usuarios.php";
            break;
            
        case "actualizarHorarios.php":
            $link = "horarios.php";
            break;
        case "tiposOrdenes.php":
            $link = "ordenes.php";
            break;
        case "actualizarOrden.php":
        case "facturarSeparado.php":
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
                <div class="offcanvas-header text-bg-dark">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel">Menú</h5>
                    <a type="button" class="btn-close bg-light" data-bs-dismiss="offcanvas" aria-label="Close"></a>
                </div>
                <div class="offcanvas-body">
                <form action="../controllers/controller.php" method="post">
                    <input type="hidden" name="action" value="logout">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <a href="dashboard.php" class="text-dark text-decoration-none text-center btn btn-light w-100 p-3 mb-3"><i class="bi bi-house-heart-fill mx-1"></i>Inicio</a> 
                        </div>
                        <div class="col-12">
                            <a href="#" class="text-dark text-decoration-none text-center btn btn-light w-100 p-3 mb-3" data-bs-toggle="modal" data-bs-target="#registroHorarioModal"><i class="bi bi-stopwatch-fill mx-1"></i>Crear Registro</a>        
                        </div>
                        <div class="col-12">
                            <a class="text-dark text-decoration-none text-center btn btn-light w-100 p-3 mb-3" href="#" data-bs-toggle="modal" data-bs-target="#verRegistroModal"><i class="bi bi-calendar2-week-fill mx-1"></i>Mis registros</a>      
                        </div>

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 1): ?>
                        <div class="col-12">
                            <a href="cerrarCaja.php" class="text-dark text-decoration-none text-center btn btn-light w-100 p-3 mb-3"><i class="bi bi-bank2 mx-1"></i>Cierre de caja</a>
                        </div>
                        <div class="col-12">
                            <a href="usuarios.php" class="text-dark text-decoration-none text-center btn btn-light w-100 p-3 mb-3"><i class="bi bi-person-badge-fill mx-1"></i>Usuarios</a>
                        </div>
                        <?php endif; ?>
                        <div class="col-12">
                            <button type="submit" class="text-dark text-decoration-none text-center btn btn-light w-100 p-3 mb-3"><i class="bi bi-door-closed-fill mx-1"></i>Cerrar sesión</button>
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
            <div class="modal-header text-bg-dark">
                <h5 class="modal-title" id="registroHorarioModalLabel">Registro de Horarios</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button type="submit" class="btn btn-primary w-100 py-3"><i class="bi bi-cursor-fill text-white me-3"></i>Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Registro de Horarios -->
<div class="modal fade" id="verRegistroModal" tabindex="-1" aria-labelledby="registroHorarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-bg-dark">
                <h5 class="modal-title" id="registroHorarioModalLabel">Registro de hoy <?php echo date('d-m-Y'); ?></h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="table-responsive">
                <table id="detallesHorarios" class="table table-dark table-striped table-hover">
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