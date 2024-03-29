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
    $detallesPedidos =  json_decode(getAllDetallesMontos($id_pedido, 0),true);
    $productosEntregados =  json_decode(getAllDetallesMontos($id_pedido, 1),true);
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
            <div class="col-sm-12 col-md-5 mb-3">
                <div class="card p-3">
                    <h4 class="card-header mb-3 py-3">Comidas y Bebidas</h4>
                    <?php if(empty($bebidasPlatillos)): ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <a href="recetas.php" class="btn btn-primary w-100 p-3">Agregar Platillo</a>
                                </div>
                                <div class="col">
                                    <a href="bebidas.php" class="btn btn-primary w-100 p-3">Agregar Bebida</a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="productosOrden" class="table table-dark table-striped table-hover">
                                    <thead class="table-warning">
                                        <tr>
                                            <th>Id</th>  
                                            <th>Precio</th>  
                                            <th>Nombre</th> 
                                            <th>Tipo</th> 
                                            <?php if(isset($_SESSION["user"])): ?>
                                                <th class="text-center">Acciones</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bebidasPlatillos as $product):?>
                                            <tr>
                                                <td><?php echo $product['id']; ?></td>
                                                <td><?php echo $product['precio']; ?></td>
                                                <td><?php echo $product['producto']; ?></td>
                                                <td>
                                                    <?php
                                                    switch ($product['tipo']) {
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
                                                <td class="text-center">
                                                    <?php if(isset($_SESSION["user"])): ?>
                                                        <span id="cart_<?php echo $product['id']; ?>" class="btn cart-button text-white">
                                                            <i class="bi bi-cart-plus-fill"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-12 col-md-7 mb-3">
                <div class="card p-3">
                    <h4 class="card-header mb-3 py-3">Orden #<?php echo $id_pedido; ?></h4>
                    <div class="card-body">
                        <form action="../controllers/controllerPedidos.php" method="post">
                            <input type="hidden" name="action" value="updateOrden">
                            <input type="hidden" name="idOrden" value="<?php echo $id_pedido; ?>">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="nombreClienteUpdate" class="form-label">Nombre del Cliente</label>
                                    <input type="text" class="form-control" id="nombreClienteUpdate" name="nombreClienteUpdate" required value="<?php echo isset($pedido['nombre_cliente']) && !empty($pedido['nombre_cliente']) ? $pedido['nombre_cliente'] : ''; ?>" placeholder="Nombre...">
                                </div>
                                <div class="col">
                                    <label for="telefonoClienteUpdate" class="form-label">Teléfono del Cliente</label>
                                    <input type="tel" class="form-control" id="telefonoClienteUpdate" name="telefonoClienteUpdate" required value="<?php echo isset($pedido['telefono_cliente']) && !empty($pedido['telefono_cliente']) ? $pedido['telefono_cliente'] : ''; ?>">
                                </div>
                            </div>

                            <?php if ($pedido['mesa'] !== null): ?>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="mesaOrdenUpdate" class="form-label">Mesa</label>
                                        <input type="number" class="form-control" id="mesaOrdenUpdate" name="mesaOrdenUpdate" required placeholder="Número de mesa..." min="1" max="10" value="<?php echo $pedido['mesa']; ?>">
                                    </div>
                                    <div class="col">
                                        <label for="servicioOrdenUpdate" class="form-label">Servicio</label>
                                        <select class="form-select" id="servicioOrdenUpdate" name="servicioOrdenUpdate">
                                            <option value="0">0%</option>
                                            <option value="10" selected>10%</option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($pedido['mesa'] === null): ?>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="direccionClienteUpdate" class="form-label">Dirección del Cliente</label>
                                        <textarea class="form-control" rows="1" id="direccionClienteUpdate" name="direccionClienteUpdate" rows="3" placeholder="Requisito para envíos..." required><?php echo isset($pedido['direccion_cliente']) ? htmlspecialchars($pedido['direccion_cliente'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row mb-3">
                                <div class="col">
                                    <h5 class="card-header mb-3 py-3">Productos de la orden entregados</h5>

                                    <div class="card-body">
                                        <?php if (!empty($productosEntregados)) { ?>
                                            <?php foreach ($productosEntregados as $detalle) { ?>
                                                <ul>
                                                    <li>
                                                        <p><?php echo $detalle['producto']; ?> x<?php echo $detalle['cantidad']; ?></p>
                                                    </li>
                                                </ul>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <p>Aún no se han entregado productos.</p>
                                        <?php } ?>
                                    </div>

                                    <h5 class="card-header mb-3 py-3">Productos por agregar</h5>

                                    <div class="table-responsive">
                                        <table id="tablaProductosTable" class="table table-light table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Precio</th>
                                                    <th>Cantidad</th>
                                                    <th>Entregado</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($detallesPedidos)) { ?>
                                                    <?php foreach ($detallesPedidos as $detalle) { ?>
                                                        <tr>
                                                            <td><?php echo $detalle['producto']; ?></td>
                                                            <td>₡<?php echo $detalle['precio']; ?></td>
                                                            <td><input type="number" class="form-control cantidad" name="cantidad[<?php echo $detalle['product_id']; ?>]" value="<?php echo $detalle['cantidad']; ?>" min=1 required></td>
                                                            <td><div class='form-check form-switch'><input type="checkbox" class="form-check-input" name="productoEntregado[<?php echo $detalle['product_id']; ?>]" value="1"></div></td>
                                                            <td><span class="btn remove-button"><i class="bi bi-cart-x-fill text-danger"></i></span></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 p-3">Actualizar Orden</button>
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


        $('#productosOrden').DataTable({
            lengthChange: false,
            pageLength: 10,
            info: false,
            responsive: true,
            "order": [[2, 'asc']],
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

        $("span.cart-button").click(function(){
            let productId = $(this).closest("tr").find("td:first").text();
            
            // Verificar si el producto ya está en la tabla
            let existingProduct = $("#tablaProductosTable tbody").find("input[name='cantidad[" + productId + "]']").closest("tr");
            
            // Si el producto ya está en la tabla, puedes actualizar su cantidad en lugar de agregarlo nuevamente
            if(existingProduct.length > 0) {
                let currentQuantity = parseInt(existingProduct.find(".cantidad").val());
                existingProduct.find(".cantidad").val(currentQuantity + 1);
            } else {
                let productPrice = $(this).closest("tr").find("td:eq(1)").text();
                let productName = $(this).closest("tr").find("td:eq(2)").text();
                let cantidad = 1;
                let tableRow = "<tr><td>" + productName + "</td><td>" + productPrice + "</td><td><input type='number' class='form-control cantidad' name='cantidad[" + productId + "]' value='" + cantidad + "' min=1 required></td><td><div class='form-check form-switch'><input class='form-check-input' type='checkbox' id='productoEntregado" + productId + "' name='productoEntregado[" + productId + "]' value='1'></div></td><td><span class='btn remove-button'><i class='bi bi-cart-x-fill text-danger'></i></span></td></tr>";
                $("#tablaProductosTable tbody").append(tableRow);

            }
        });

        $(document).on("click", ".remove-button", function(){
            $(this).closest("tr").remove();
        });     
        
    });  
</script>

</body>
</html>
