<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../controllers/controllerPedidos.php');
include('../controllers/controllerDetallesPedidos.php');

$pedidosData = getAllPedidos();
$pedidos = json_decode($pedidosData, true);

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
    <title>Ordenes</title>
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

<?php if (isset($_SESSION["user"])) { ?>
    <div class="container-fluid p-5">
        <div class="card p-3">
            <h4 class="card-header mb-3 py-3">Órdenes de hoy <?php echo date('d/m/Y'); ?></h4>
            <h5 class="modal-title mb-3">
                <a href="tiposOrdenes.php" class="text-decoration-none text-info">
                    <img src="../assets/images/pedidos.png" alt="Crear torneo" class="img-fluid me-2">Agregar nueva orden.
                </a>
            </h5>
            
            <div class="table-responsive">
            <table id="example" class="table table-dark table-striped table-hover">
                <thead class="table-warning">
                    <tr>
                        <th>Mesa</th>
                        <!-- <th>Teléfono</th>
                        <th>Dirección</th> -->
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Detalles</th>
                        <th>Total</th>
                        <th>Restante</th>

                        <?php 
                            if(isset($_SESSION["user"])){
                                echo '<th>Acciones</th>';
                            }
                        ?>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    date_default_timezone_set('America/Costa_Rica');
                    $fecha_actual = date("Y-m-d");

                    foreach ($pedidos as $pedido):
                        $fecha_pedido = substr($pedido['fecha_pedido'], 0, 10);

                        if ($fecha_pedido === $fecha_actual):
                    ?>
                        <tr>
                            <td <?php echo ($pedido["web"] == 1) ? 'class="table-danger"' : ''; ?>>
                                <?php echo ($pedido['mesa'] !== null) ? $pedido['mesa'] : 'Express'; ?>
                            </td>
                            <td <?php echo ($pedido["web"] == 1) ? 'class="table-danger"' : ''; ?>>
                                <?php echo date('H:i:s', strtotime($pedido['fecha_pedido'])); ?>
                            </td>
                            <td <?php echo ($pedido["web"] == 1) ? 'class="table-danger"' : ''; ?>>
                                <?php echo $pedido['estado_pedido']; ?>
                            </td>

                            <td <?php echo ($pedido["web"] == 1) ? 'class="table-danger"' : ''; ?>>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalDetalles<?php echo $pedido['id_pedido']; ?>">
                                    <i class="bi bi-cart-fill text-white" data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Ver Productos'></i>
                                </a>
                                <!-- Modal -->
                                <div class="modal fade" id="modalDetalles<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered ">
                                        <div class="modal-content">
                                            <div class="modal-header text-bg-dark">
                                                <h5 class="modal-title" id="modalDetallesLabel">Productos de la orden</h5>
                                                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-dark">

                                            <?php if ($pedido["estado_pedido"] == "Pendiente"): ?>
                                                <h5 class="modal-title mb-3">
                                                    <a href="actualizarOrden.php?idPedido=<?php echo $pedido['id_pedido']; ?>" class="text-decoration-none text-info">Editar orden</a>
                                                </h5>
                                            <?php endif; ?>

                                            <?php if ($pedido["direccion_cliente"] !== null): ?>
                                                <p>Dirección del express: <?php echo $pedido['direccion_cliente']; ?> ...</p>
                                            <?php endif; ?>

                                                <div class="table-responsive">
                                                    <table id="detallesProductos" class="table table-dark table-striped table-hover w-100">
                                                        <thead class="table-warning">
                                                            <tr>
                                                                <th>ID Detalle</th>
                                                                <th>ID Pedido</th>
                                                                <th>Productos</th>
                                                                <th>Cantidad</th>
                                                                <th>Guía</th>
                                                                <th>Precio unidad</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $detalles = json_decode(getAllDetalles($pedido['id_pedido']), true);
                                                            foreach ($detalles as $detalle): ?>
                                                                <tr>
                                                                    <td><?php echo $detalle['id_detalle']; ?></td>
                                                                    <td><?php echo $detalle['id_pedido']; ?></td>
                                                                    <td><?php echo $detalle['producto']; ?></td>
                                                                    <td><?php echo $detalle['cantidad']; ?></td>
                                                                    <?php if($detalle['imagen'] !== null): ?>
                                                                        <td>
                                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#fullScreenModal<?php echo $detalle['id_receta']; ?>">
                                                                                <i class="bi bi-image-fill text-white"></i>
                                                                            </a>
                                                                        </td>
                                                                    <?php else: ?>
                                                                        <td>Sin imagen</td>
                                                                    <?php endif; ?>
                                                                    <td><?php echo $detalle['precio']; ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Modal de pantalla completa -->
                            <div class="modal fade" id="fullScreenModal<?php echo $detalle['id_receta']; ?>" tabindex="-1" aria-labelledby="fullScreenModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-fullscreen">
                                    <div class="modal-content">
                                        <div class="modal-header bg-dark">
                                            <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <?php if(isset($detalle["imagen"])): ?>
                                                <img src="<?php echo $detalle["imagen"]; ?>" class="img-fluid" alt="Imagen de receta">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <td <?php echo ($pedido["web"] == 1) ? 'class="table-danger"' : ''; ?>>
                                <?php echo $pedido['total_pedido']; ?>
                            </td>
                            <td <?php echo ($pedido["web"] == 1) ? 'class="table-danger"' : ''; ?>>
                            <?php echo $pedido['total_restante']; ?>
                            </td>

                                <?php

                                if (isset($_SESSION["user"])) :
                                ?>
                                <td <?php echo ($pedido["web"] == 1) ? 'class="table-danger"' : ''; ?>>

                                    <a href="#" class="text-decoration-none text-white mx-3" data-bs-toggle="modal" data-bs-target="#comandasModal<?php echo $pedido['id_pedido']; ?>">
                                        <i class="bi bi-ticket-perforated-fill text-white" data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Crear Comanda'></i>
                                    </a>

                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 1): ?>
                                        <a href="#" class="text-decoration-none text-white mx-3" data-bs-toggle="modal" data-bs-target="#eliminarModal<?php echo $pedido['id_pedido']; ?>">
                                        <i class="bi bi-trash-fill text-white" data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Eliminar Orden'></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="#" class="text-decoration-none text-white mx-3" data-bs-toggle="modal" data-bs-target="#confirmModal<?php echo $pedido['id_pedido']; ?>">
                                        <i class="bi bi-piggy-bank-fill text-white" data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Cancelar Orden'></i>
                                    </a>

                                    <!-- Modal comanda-->
                                    <div class="modal fade" id="comandasModal<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="comandasModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header text-bg-dark">
                                                    <h5 class="modal-title" id="eliminarModalLabel">Confirmar Acción</h5>
                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-dark">
                                                    <?php if ($pedido["estado_pedido"] == "Pendiente"): ?>
                                                        <p>¿Estás seguro de que quieres crear la comanda de la orden?</p>
                                                    <?php else: ?>
                                                        <!-- Aquí puedes colocar el HTML alternativo si el pedido no está pendiente -->
                                                        <p>Este pedido ya fue cancelado, no se puede crear la comanda.</p>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ($pedido["estado_pedido"] == "Pendiente"): ?>
                                                <div class="form-group mb-3 text-start px-3">
                                                    <a href="crearComanda.php?id=<?php echo $pedido['id_pedido']; ?>" class="btn btn-primary cerrarModal w-100 py-3"><i class="bi bi-cursor-fill text-white me-3"></i>Sí, crear comanda.</a>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal eliminar-->
                                    <div class="modal fade" id="eliminarModal<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header text-bg-dark">
                                                    <h5 class="modal-title" id="eliminarModalLabel">Confirmar Acción</h5>
                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-dark">
                                                    <?php if ($pedido["estado_pedido"] == "Pendiente"): ?>
                                                        <p>¿Estás seguro de que quieres eliminar este pedido?</p>
                                                    <?php else: ?>
                                                        <p>Este pedido ya fue cancelado, no se puede eliminar.</p>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ($pedido["estado_pedido"] == "Pendiente"): ?>
                                                    <div class="form-group mb-3 text-start px-3">
                                                        <a href="../controllers/controllerPedidos.php?action=delete&id=<?php echo $pedido['id_pedido']; ?>" class="btn btn-danger w-100 py-3"><i class="bi bi-cursor-fill text-white me-3"></i>Sí, eliminar.</a>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>


                                    <!-- Modal facturar-->
                                    <div class="modal fade" id="confirmModal<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header text-bg-dark">
                                                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Acción</h5>
                                                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="../controllers/controllerPedidos.php" method="POST">
                                                    <input type="hidden" name="action" value="facturarPago">
                                                    <div class="modal-body text-dark">
                                                        <?php if ($pedido["estado_pedido"] == "Pendiente" && $pedido["mesa"] !== null): ?>
                                                            <input type="hidden" name="mesaPedido" value="<?php echo $pedido['mesa']; ?>">
                                                            <h6 class="mb-4 mt-1">
                                                                <a href="facturarSeparado.php?idPedido=<?php echo $pedido['id_pedido']; ?>" class="text-decoration-none">
                                                                    <i class="bi bi-coin me-3"></i>Cancelar por separado<i class="bi bi-coin ms-3"></i>
                                                                </a>
                                                            </h6>      
                                                            <p>Si desea dividir la cuenta en partes iguales, indique la cantidad de personas por favor:</p>
                                                            <div class="row my-1">
                                                                <div class="col my-1">
                                                                    <label for="cantidadPersonas" class="form-label">Cantidad</label>
                                                                    <input type="number" name="cantidadPersonas" id="cantidadPersonas" class="form-control" placeholder="Cantidad personas..." min="1" value="1" required>
                                                                    <input type="hidden" name="idFactura" value="<?php echo $pedido['id_pedido']; ?>">
                                                                </div>
                                                                <div class="col my-1">
                                                                    <label for="metodoPago" class="form-label">Método de Pago</label>
                                                                    <select class="form-select" id="metodoPago" name="metodoPago" required>
                                                                        <option value="">Seleccionar</option>
                                                                        <option value="1">Efectivo</option>
                                                                        <option value="2">Tarjeta</option>
                                                                        <option value="3">Sinpe</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary w-100 py-3 mt-3">
                                                                <i class="bi bi-cursor-fill text-white me-3"></i>
                                                                Facturar Pago
                                                            </button>


                                                        <?php elseif ($pedido["estado_pedido"] == "Pendiente" && $pedido["mesa"] == null): ?>
                                                            <input type="hidden" name="mesaPedido" value="<?php echo empty($pedido['mesa']) ? null : $pedido['mesa']; ?>">
                                                            <input type="hidden" name="cantidadPersonas" id="cantidadPersonas" class="form-control" placeholder="Cantidad personas..." min="1" value="1" required>
                                                            <input type="hidden" name="idFactura" value="<?php echo $pedido['id_pedido']; ?>">
                                                            <div class="col-sm-12 col-md-12 col-lg-12 mb-3">
                                                                <label for="metodoPago" class="form-label">Método de Pago</label>
                                                                <select class="form-select" id="metodoPago" name="metodoPago" required>
                                                                    <option value="">Seleccionar</option>
                                                                    <option value="1">Efectivo</option>
                                                                    <option value="2">Tarjeta</option>
                                                                    <option value="3">Sinpe</option>
                                                                </select>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary w-100 py-3 mt-3">
                                                                <i class="bi bi-cursor-fill text-white me-3"></i>
                                                                Facturar Pago
                                                            </button>
                                                        <?php else: ?>
                                                            <!-- Aquí puedes colocar el HTML alternativo si el pedido no está pendiente -->
                                                            <p>Este pedido ya fue cancelado.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                        </tr>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <?php
        if (isset($_GET['updatedPedido'])) {
            $status = $_GET['updatedPedido'];
            
            $messageConfig = ($status == 1)
                ? [
                    'icon' => 'success',
                    'title' => 'Actualizado con éxito',
                    'text' => 'Orden actualizada.',
                ]
                : [
                    'icon' => 'error',
                    'title' => 'Error al actualizar',
                    'text' => 'No se pudo actualizar la orden.',
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

        

        if (isset($_GET['updatedOrden'])) {
            $status = $_GET['updatedOrden'];
            
            $messageConfig = ($status == 1)
                ? [
                    'icon' => 'success',
                    'title' => 'Modicada con éxito',
                    'text' => 'Orden actualizada.',
                ]
                : [
                    'icon' => 'error',
                    'title' => 'Error al actualizar',
                    'text' => 'No se pudo modificar la orden.',
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

        if (isset($_GET['orderAdd'])) {
            $status = $_GET['orderAdd'];
            
            $messageConfig = ($status == 1)
                ? [
                    'icon' => 'success',
                    'title' => 'Añadida con éxito',
                    'text' => 'Orden agregada.',
                ]
                : [
                    'icon' => 'error',
                    'title' => 'Error al añadir',
                    'text' => 'No se pudo agregar la orden.',
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


        

        if (isset($_GET['deletePedido'])) {
            $status = $_GET['deletePedido'];
            
            $messageConfig = ($status == 1)
                ? [
                    'icon' => 'success',
                    'title' => 'Eliminado con éxito',
                    'text' => 'Orden eliminada.',
                ]
                : [
                    'icon' => 'error',
                    'title' => 'Error al eliminar',
                    'text' => 'No se pudo eliminar la orden.',
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

        if (isset($_GET['facturada'])) {
            $status = $_GET['facturada'];
            $id_factura = isset($_GET['i']) ? $_GET['i'] : null;
            $cantidadPersonas = isset($_GET['c']) ? $_GET['c'] : null;
            $min = isset($_GET['min']) ? $_GET['min'] : 0;
            $max = isset($_GET['max']) ? $_GET['max'] : 0;
            $url = null;

            if($id_factura !== null && $cantidadPersonas !== null && $min !== 0 && $max !== 0){
                $url = "facturar.php?idFactura=" . $id_factura . "&cantidadPersonas=" . $cantidadPersonas . "&min=" . $min. "&max=" . $max;
            }elseif($min !== 0 && $max !== 0 && $id_factura === null && $cantidadPersonas === null){
                $url = "facturar.php?min=" . $min  . "&max=" . $max;
            }           
            
            $messageConfig = ($status == 1)
                ? [
                    'icon' => 'success',
                    'title' => 'Actualizado con éxito',
                    'text' => 'El pago individual se ha realizado con éxito.',
                    'showButton' => true 
                ]
                : [
                    'icon' => 'error',
                    'title' => 'Inconveniente al realizar el pago.',
                    'text' => 'No se pudo realizar.',
                    'showButton' => false
                ];
        
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "' . $messageConfig['icon'] . '",
                    title: "' . $messageConfig['title'] . '",
                    text: "' . $messageConfig['text'] . '",
                    showCloseButton: true,
                    allowOutsideClick: false,
                    confirmButtonColor: "#0dcaf0",
                    showConfirmButton: ' . ($messageConfig['showButton'] ? 'true' : 'false') . ',
                    ' . ($messageConfig['showButton'] ? 'confirmButtonText: "Crear Factura Individual",' : '') . '
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "'. $url .'";
                    }
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
            "order": [[1, 'asc']],
            initComplete: function(settings, json) {
                $(".dataTables_filter label").addClass("text-dark");
            }
        });

        $('#detallesHorarios, #detallesProductos').DataTable({
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

        $('.cerrarModal').on('click', function() {
            $('.modal').modal('hide');
        });

        $('#detallesProductos th:nth-child(1), #detallesProductos td:nth-child(1), #detallesProductos th:nth-child(2), #detallesProductos td:nth-child(2)').css('display', 'none'); 
    }); 
</script>
</body>
</html>
