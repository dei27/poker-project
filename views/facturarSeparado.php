<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../controllers/controllerProducts.php');
include('../controllers/controllerPedidos.php');
include('../controllers/controllerDetallesPedidos.php');

$products = json_decode(getAll(),true);

if (isset($_GET['idPedido'])) {
    $id_pedido = $_GET['idPedido'];
    $pedido = getPedidoById($id_pedido);
    $detallesPedidos =  json_decode(getAllDetallesMontos($id_pedido),true);
    $bebidasPlatillos = json_decode(getAllBebidasAndPlatillos(),true);
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
    <title>Ordenes</title>
</head>
<body>
    <header>
    <?php 
        if(isset($_SESSION["user"])){
            include("menu.php");
        }else{
            echo 
            '<nav class="navbar sticky-top">
                <a href="../index.php" class="text-decoration-none text-dark navbar-brand"><i class="bi bi-arrow-left-circle-fill text-dark fs-3 px-3"></i></a>
            </nav>';
        }
    ?>
    </header>

<?php if (isset($_SESSION["user"])) { 
    ?>
    <div class="container-fluid p-5">
        <div class="row">
            <div class="col-sm-12 col-md-12 mb-3">
                <div class="card p-3">
                    <h4 class="card-header mb-3 py-3">Orden #<?php echo $id_pedido; ?></h4>
                    <div class="card-body">
                        <form action="../controllers/controllerPedidos.php" method="post">
                            <input type="hidden" name="action" value="facturarSeparado">
                            <input type="hidden" name="idOrden" value="<?php echo $id_pedido; ?>">
                            <div class="row mb-3">
                                <div class="col-sm-6 col-md-6 col-lg-3 mb-1">
                                    <label for="nombreClienteProcesarSeparado" class="form-label">Nombre del Cliente</label>
                                    <input type="text" class="form-control" id="nombreClienteProcesarSeparado" name="nombreClienteProcesarSeparado" required value="<?php echo isset($pedido['nombre_cliente']) && !empty($pedido['nombre_cliente']) ? $pedido['nombre_cliente'] : ''; ?>" placeholder="Nombre...">
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-3 mb-1">
                                    <label for="telefonoProcesarSeparado" class="form-label">Teléfono del Cliente</label>
                                    <input type="tel" class="form-control" id="telefonoProcesarSeparado" name="telefonoProcesarSeparado" required value="<?php echo isset($pedido['telefono_cliente']) && !empty($pedido['telefono_cliente']) ? $pedido['telefono_cliente'] : ''; ?>">
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-3 mb-1">
                                    <label for="mesaOrdenProcesarSeparado" class="form-label">Mesa</label>
                                    <input type="number" class="form-control" id="mesaOrdenProcesarSeparado" name="mesaOrdenProcesarSeparado" placeholder="Número de mesa..." min="1" max="10" value="<?php echo $pedido['mesa']; ?>" readonly>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-3 mb-1">
                                    <label for="servicioOrdenProcesarSeparado" class="form-label">Servicio</label>
                                    <select class="form-select" id="servicioOrdenProcesarSeparado" name="servicioOrdenProcesarSeparado" disabled>
                                        <option value="0">0%</option>
                                        <option value="10" selected>10%</option>
                                    </select>
                                </div>
                                
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <h6>Productos de la orden:</h6>
                                    <div id="tablaProductos" class="table-responsive">
                                        <table id="tablaProductosTable" class="table table-light table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Precio</th>
                                                    <th>Tipo</th>
                                                    <th>Cantidad</th>
                                                    <th>Pagar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php if (!empty($detallesPedidos)) { ?>
                                                <?php foreach ($detallesPedidos as $detalle) {
                                                    $maxRango = $detalle['cantidad'];
                                                    if ($maxRango != 0) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $detalle['producto']; ?></td>
                                                        <td>₡<?php echo $detalle['precio']; ?></td>
                                                        <td>
                                                            <?php
                                                            switch ($detalle['tipo']) {
                                                                case null:
                                                                    echo "Bebida";
                                                                    break;
                                                                case 1:
                                                                    echo "Entradas";
                                                                    break;
                                                                case 2:
                                                                    echo "Platillos Fuertes";
                                                                    break;
                                                                case 3:
                                                                    echo "Postres";
                                                                    break;
                                                                case 4:
                                                                    echo "Extras";
                                                                    break;
                                                                default:
                                                                    echo "Desconocido";
                                                                    break;
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><input type="number" class="form-control cantidad" name="cantidadSeparado[<?php echo $detalle['product_id']; ?>]" value="<?php echo $detalle['cantidad']; ?>" min=1 required max=<?php echo $maxRango; ?>></td>
                                                        <td><input type="number" class="form-control cantidad" name="cantidadPagar[<?php echo $detalle['product_id']; ?>]" value="0" min=0 required max=<?php echo $maxRango; ?>></td>
                                                        <!-- Campo oculto para el precio -->
                                                        <input type="hidden" name="preciosSeparado[<?php echo $detalle['product_id']; ?>]" value="<?php echo $detalle['precio']; ?>">
                                                    </tr>
                                                <?php 
                                                    }
                                                } ?>
                                            <?php } ?>
                                        </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <button type="submit" class="btn btn-primary w-100 p-3" name="action" value="procesarPago">Procesar pago</button>
                                </div>
                                <div class="col mb-3">
                                    <button type="submit" class="btn btn-info w-100 p-3 text-white" name="action" value="crearFacturaIndividual">Crear factura individual</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
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

        if (isset($_GET['emptyProducts']) && $_GET['emptyProducts'] == 0) {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "No se puedo procesar",
                    timer: 2500,
                    text: "No se agregaron productos a la orden.",
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

        $('#tablaProductosTable').DataTable({
            lengthChange: false,
            pageLength: 10,
            info: false,
            responsive: true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
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

        $('#productosOrden th:first-child, #productosOrden td:first-child, #productosOrden th:nth-child(2), #productosOrden td:nth-child(2)').css('display', 'none');

        $(document).on("click", ".remove-button", function(){
            $(this).closest("tr").remove();
        });

    });  
</script>

</body>
</html>
