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
    <title>Facturar</title>
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

                    <?php if (isset($_GET["idPedido"]) && (!isset($_GET["c"]) || (isset($_GET["c"]) && $_GET["c"] < 2))): ?>
                        <form action="../controllers/controllerPedidos.php" method="post">
                            <input type="hidden" name="action" value="calcularClientes">
                            <input type="hidden" name="idOrden" value="<?php echo $id_pedido; ?>">

                            <div class="input-group">
                                <span class="input-group-text text-bg-secondary py-3">Ingrese la cantidad de clientes</span>
                                <input type="number" class="form-control text-center py-3" required min=2 value="2" name="cantidadClientes">
                                <button type="submit" class="btn btn-primary py-3">
                                    <i class="bi bi-cursor-fill text-white me-3"></i>
                                    Calcular Valores
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                    
                        <form action="../controllers/controllerPedidos.php" method="post">
                            <input type="hidden" name="action" value="facturarSeparado">
                            <input type="hidden" name="idOrden" value="<?php echo $id_pedido; ?>">
                            <input type="hidden" name="cantidadVeces" value="<?= isset($_GET["c"]) ? $_GET["c"] : '' ?>">
                            <?php 
                            if (isset($_GET["idPedido"], $_GET["c"]) && $_GET["c"] >= 2): 
                                $cantidadVeces =  $_GET["c"];
                                for ($i = 1; $i < $cantidadVeces+1; $i++) { 
                            ?>
                            
                            <div class="row mb-3">
                                <h5>Cliente # 0<?php echo $i; ?></h5>
                                <div class="col-sm-12 col-md-12 col-lg-3 mb-1">
                                    <label for="nombreClienteProcesarSeparado_<?php echo $i; ?>" class="form-label">Nombre del Cliente</label>
                                    <input type="text" class="form-control" id="nombreClienteProcesarSeparado_<?php echo $i; ?>" name="form_<?php echo $i; ?>[nombreClienteProcesarSeparado]" required value="<?php echo isset($pedido['nombre_cliente']) && !empty($pedido['nombre_cliente']) ? $pedido['nombre_cliente'] : ''; ?>" placeholder="Nombre...">
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-3 mb-1">
                                    <label for="telefonoProcesarSeparado_<?php echo $i; ?>" class="form-label">Teléfono del Cliente</label>
                                    <input type="tel" class="form-control" id="telefonoProcesarSeparado_<?php echo $i; ?>" name="form_<?php echo $i; ?>[telefonoProcesarSeparado]" required value="<?php echo isset($pedido['telefono_cliente']) && !empty($pedido['telefono_cliente']) ? $pedido['telefono_cliente'] : ''; ?>">
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-2 mb-1">
                                    <label for="mesaOrdenProcesarSeparado_<?php echo $i; ?>" class="form-label">Mesa</label>
                                    <input type="number" class="form-control" id="mesaOrdenProcesarSeparado_<?php echo $i; ?>" name="form_<?php echo $i; ?>[mesaOrdenProcesarSeparado]" placeholder="Número de mesa..." min="1" max="10" value="<?php echo $pedido['mesa']; ?>" readonly>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-2 mb-1">
                                    <label for="servicioOrdenProcesarSeparado_<?php echo $i; ?>" class="form-label">Servicio</label>
                                    <select class="form-select" id="servicioOrdenProcesarSeparado_<?php echo $i; ?>" name="form_<?php echo $i; ?>[servicioOrdenProcesarSeparado]" disabled>
                                        <option value="0">0%</option>
                                        <option value="10" selected>10%</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-2 mb-1">
                                    <label for="metodoPago_<?php echo $i; ?>" class="form-label">Método de Pago</label>
                                    <select class="form-select" id="metodoPago_<?php echo $i; ?>" name="form_<?php echo $i; ?>[metodoPago]" required>
                                        <option value="">Seleccionar</option>
                                        <option value="1">Efectivo</option>
                                        <option value="2">Tarjeta</option>
                                        <option value="3">Sinpe</option>
                                    </select>
                                </div>
                            </div>
                            <?php 
                                } // end for loop
                            endif; 
                            ?>
                            <?php
                            if(isset($_GET["c"])) {
                            ?>
                                <div class="row">
                                    <div class="col mb-3">
                                        <button type="submit" class="btn btn-primary w-100 p-3"><i class="bi bi-cursor-fill text-white me-3"></i>Procesar pago</button>
                                    </div>
                                    <?php
                                    if (isset($_GET['idPedido'], $_GET['min'], $_GET['max'])) {
                                        $id_factura = $_GET['idPedido'];
                                        $min = $_GET['min'];
                                        $max = $_GET['max'];
                                        $url = 'facturarIndividual.php?idFactura=' . $id_factura . '&min=' . $min . '&max=' . $max;
                                    ?>
                                        <div class="col mb-3">
                                            <a href="<?php echo $url; ?>" class="btn btn-info w-100 p-3 text-white">Crear factura individual</a>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            <?php
                            }
                            ?>

                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
</div>


    <?php
        if (isset($_GET['updatedFactura'])) {
            $status = $_GET['updatedFactura'];
            $id_factura = isset($_GET['idPedido']) ? $_GET['idPedido'] : '';
            $min = isset($_GET['min']) ? $_GET['min'] : '';
            $max = isset($_GET['max']) ? $_GET['max'] : '';
            
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
                        window.location.href = "facturarIndividual.php?idFactura=' . $id_factura . '&min=' . $min . '&max=' . $max . '";
                    }
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
                    timer: 3000,
                    text: "No hay productos por cancelar.",
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
