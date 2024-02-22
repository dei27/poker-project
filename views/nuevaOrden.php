<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../controllers/controllerProducts.php');
include('../controllers/controllerDetallesPedidos.php');

$products = json_decode(getAll(),true);
$bebidasPlatillos = json_decode(getAllBebidasAndPlatillos(),true);
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
            include ("menu.php");
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
        <div class="row">
            <div class="col-sm-12 col-md-6 mb-3">
                <div class="card p-3">
                    <h4 class="card-header mb-3 py-3">Comidas y Bebidas</h4>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="productosOrden" class="table table-dark table-striped table-hover">
                                <thead class="table-warning">
                                    <tr>
                                        <th>Id</th>  
                                        <th>Precio</th>  
                                        <th>Nombre</th> 
                                        <th>Tipo</th> 
                                        <?php 
                                            if(isset($_SESSION["user"])){
                                                echo '<th class="text-center">Acciones</th>';
                                            }
                                        ?>
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
                </div>
            </div>
            <div class="col-sm-12 col-md-6 mb-3">
                <div class="card p-3">
                    <h4 class="card-header mb-3 py-3">Orden nueva</h4>
                    <div class="card-body">
                        <form action="../controllers/controllerPedidos.php" method="post">
                            <input type="hidden" name="action" value="addOrden">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="nombreClienteOrden" class="form-label">Nombre del Cliente</label>
                                    <input type="text" class="form-control" id="nombreClienteOrden" name="nombreClienteOrden" required placeholder="Nombre...">
                                </div>
                                <div class="col">
                                    <label for="telefonoClienteOrden" class="form-label">Teléfono del Cliente</label>
                                    <input type="tel" class="form-control" id="telefonoClienteOrden" name="telefonoClienteOrden" required placeholder="Teléfono...">
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col">
                                    <label for="mesaOrden" class="form-label">Mesa</label>
                                    <input type="number" class="form-control" id="mesaOrden" name="mesaOrden" required placeholder="Número de mesa..." min=1 max=10>
                                </div>
                                <div class="col">
                                    <label for="servicioOrden" class="form-label">Servicio</label>
                                    <select class="form-select" id="servicioOrden" name="servicioOrden">
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
                                                    <th>Cantidad</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 p-3">Procesar Orden</button>
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
            let productPrice = $(this).closest("tr").find("td:eq(1)").text();
            let productName = $(this).closest("tr").find("td:eq(2)").text();
            let cantidad = 1;
            let tableRow = "<tr><td>" + productName + "</td><td>" + productPrice + "</td><td><input type='number' class='form-control cantidad' name='cantidad[" + productId + "]' value='" + cantidad + "' min=1 required></td><td><span class='btn remove-button'><i class='bi bi-cart-x-fill'></i></span></td></tr>";

            $("#tablaProductosTable tbody").append(tableRow);
        });

        $(document).on("click", ".remove-button", function(){
            $(this).closest("tr").remove();
        });

    }); 
    
    
</script>
</body>
</html>
