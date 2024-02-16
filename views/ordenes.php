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
            <h4 class="card-header mb-3 py-3">Mis órdenes</h4>
            <?php
            if(isset($_SESSION["user"])){
            echo '<h5 class="modal-title mb-3">
                    <a href="tiposOrdenes.php" class="text-decoration-none text-info">Agregar nueva orden.</a>
                </h5>';
            }
            ?>
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
                            <td><?php echo ($pedido['mesa'] !== null) ? $pedido['mesa'] : 'Express'; ?></td>
                            <!-- <td><?php echo $pedido['telefono_cliente']; ?></td>
                            <td><?php echo $pedido['direccion_cliente']; ?></td> -->
                            <td><?php echo date('h:i:s A', strtotime($pedido['fecha_pedido'])); ?></td>
                            <td><?php echo $pedido['estado_pedido']; ?></td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalDetalles<?php echo $pedido['id_pedido']; ?>">
                                    <i class="bi bi-cart-fill text-white" data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Ver Productos'></i>
                                </a>
                                <!-- Modal -->
                                <div class="modal fade" id="modalDetalles<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header text-bg-dark">
                                                <h5 class="modal-title" id="modalDetallesLabel">Productos de la orden</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h5 class="modal-title mb-3">
                                                    <a href="actualizarOrden.php?idPedido=<?php echo $pedido['id_pedido']; ?>" class="text-decoration-none text-info">Editar orden</a>
                                                </h5>
                                                <div class="table-responsive">
                                                    <table id="detallesProductos" class="table table-dark table-striped table-hover w-100">
                                                        <thead>
                                                            <tr>
                                                                <th>ID Detalle</th>
                                                                <th>ID Pedido</th>
                                                                <th>Productos</th>
                                                                <th>Cantidad</th>
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
                                                                    <td>₡<?php echo $detalle['precio']; ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>


                            <td><?php echo $pedido['total_pedido']; ?></td>

                                <?php

                                if (isset($_SESSION["user"])) :
                                ?>
                                <td>
                                    <a href="#" class="text-decoration-none text-white mx-3" data-bs-toggle="modal" data-bs-target="#comandasModal<?php echo $pedido['id_pedido']; ?>">
                                        <i class="bi bi-ticket-perforated-fill text-white" data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Crear Comanda'></i>
                                    </a>

                                    <a href="#" class="text-decoration-none text-white mx-3" data-bs-toggle="modal" data-bs-target="#eliminarModal<?php echo $pedido['id_pedido']; ?>">
                                        <i class="bi bi-trash-fill text-white" data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Eliminar Orden'></i>
                                    </a>

                                    <a href="#" class="text-decoration-none text-white mx-3" data-bs-toggle="modal" data-bs-target="#confirmModal<?php echo $pedido['id_pedido']; ?>">
                                        <i class="bi bi-piggy-bank-fill text-white" data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Cancelar Orden'></i>
                                    </a>

                                    <!-- Modal comanda-->
                                    <div class="modal fade" id="comandasModal<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="comandasModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header text-bg-dark">
                                                    <h5 class="modal-title" id="eliminarModalLabel">Confirmar Acción</h5>
                                                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                    <div class="modal-body text-dark">
                                                        <p>¿Estás seguro de que quieres crear la comanda de la orden?</p>
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <a href="crearComanda.php?id=<?php echo $pedido['id_pedido']; ?>" class="btn btn-primary cerrarModal">Sí, crear comanda.</a>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal eliminar-->
                                    <div class="modal fade" id="eliminarModal<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header text-bg-dark">
                                                    <h5 class="modal-title" id="eliminarModalLabel">Confirmar Acción</h5>
                                                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                    <div class="modal-body text-dark">
                                                        <p>¿Estás seguro de que quieres eliminar este pedido?</p>
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <a href="../controllers/controllerPedidos.php?action=delete&id=<?php echo $pedido['id_pedido']; ?>" class="btn btn-primary">Sí, eliminar.</a>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal facturar-->
                                    <div class="modal fade" id="confirmModal<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header text-bg-dark">
                                                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Acción</h5>
                                                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="facturar.php" method="POST">
                                                    <div class="modal-body text-dark">
                                                        <h6 class="mb-4 mt-1">
                                                            <a href="facturarSeparado.php?idPedido=<?php echo $pedido['id_pedido']; ?>" class="text-decoration-none"><i class="bi bi-coin me-3"></i>Cancelar por separado<i class="bi bi-coin ms-3"></i></a>
                                                        </h6>      
                                                        <p>Si desea dividir la cuenta en partes iguales, indique la cantidad de personas por favor:</p>
                                                        <input type="number" name="cantidadPersonas" id="cantidadPersonas" class="form-control" placeholder="Cantidad personas..." min="1" value="1" required>
                                                        <input type="hidden" name="idFactura" value="<?php echo $pedido['id_pedido']; ?>">
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <input type="submit" class="btn btn-primary cerrarModal" value="Cancelar cuenta">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- <a href="#" class="text-decoration-none text-white" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $pedido['id_pedido']; ?>">
                                        <i class="bi bi-pencil-fill text-white"></i>
                                    </a> -->
                                </td>

                                <!-- <div class="modal fade" id="editModal<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $pedido['id_pedido']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header text-bg-dark">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $pedido['id_pedido']; ?>">Editar Orden</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../controllers/controllerPedidos.php" method="post" class="form-floating">
                                                    <input type="hidden" name="id" value="<?php echo $pedido['id_pedido']; ?>">
                                                    <input type="hidden" name="action" value="edit">

                                                    <div class="form-group mb-3">
                                                        <label for="nombreCliente">Cliente</label>
                                                        <input class="form-control" id="nombreCliente" name="nombreCliente" placeholder="Nombre..." value="<?php echo $pedido['nombre_cliente']; ?>" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="mesaCliente">Mesa</label>
                                                        <input type="number" class="form-control" id="mesaCliente" name="mesaCliente" placeholder="Número de mesa..." value="<?php echo $pedido['mesa']; ?>" required min=1 max=10>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="telefonoCliente">Teléfono</label>
                                                        <input class="form-control" id="telefonoCliente" name="telefonoCliente" placeholder="Teléfono..." value="<?php echo $pedido['telefono_cliente']; ?>" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="direccionCliente" class="form-label">Dirección</label>
                                                        <textarea class="form-control" id="direccionCliente" name="direccionCliente" placeholder="Dirección..."><?php echo htmlspecialchars($pedido['direccion_cliente']); ?></textarea>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="estadoPedido">Estado</label>
                                                        <select class="form-select" id="estadoPedido" name="estadoPedido" required readonly>
                                                            <option value="Pendiente" <?php if($pedido['estado_pedido'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                                                            <option value="Cancelado" <?php if($pedido['estado_pedido'] == 'Cancelado') echo 'selected'; ?>>Cancelado</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3 text-end">
                                                        <input type="submit" class="btn btn-primary" value="Guardar">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
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

        $('.cerrarModal').on('click', function() {
            $('.modal').modal('hide');
        });

        $('#detallesProductos th:nth-child(1), #detallesProductos td:nth-child(1), #detallesProductos th:nth-child(2), #detallesProductos td:nth-child(2)').css('display', 'none'); 
    }); 
</script>
</body>
</html>
