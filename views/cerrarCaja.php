<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../controllers/controllerPagosPersonas.php');
// include('../controllers/controllerCierresCajas.php');
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
    <title>Cerrar Caja</title>
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
    $facturasEfectivo = json_decode(getAllPagosByPersonas(1), true);
    $facturasTarjeta = json_decode(getAllPagosByPersonas(2), true);
    $facturasSinpe = json_decode(getAllPagosByPersonas(3), true);
    
    $granTotal = 0;

    foreach ($facturasEfectivo as $item) {
        $granTotal += $item['monto'];
    } 
    
    foreach ($facturasTarjeta as $item) {
        $granTotal += $item['monto'];
    } 
    
    foreach ($facturasSinpe as $item) {
        $granTotal += $item['monto'];
    }

    date_default_timezone_set('America/Costa_Rica');
    $dia = date('d-m-Y');
    
    $total = 0;
    $total01 = 0;
    $total02 = 0;
    
?>
    <div class="container-fluid p-5">

        <form action="../controllers/controllerCierresCajas.php" method="post">
            <input type="hidden" name="action" value="nuevaCajaCierre">
            <div class="table-responsive card p-3">
                <div class="card-header mb-5 py-3"><h4>Cierre de Caja <?php echo $dia; ?></h4></div>
                <div class="row">

                    <div class="row align-items-center mb-4">
                        <div class="col mb-3">
                            <?php if ($granTotal > 0): ?>
                                <h5>El día de hoy se ha facturado un total de: ₡<?php echo number_format($granTotal, 2, '.', ',');?></h5>
                                <input type="hidden" name="totalCajaDia" id="totalCajaDia" value="<?php echo $granTotal; ?>" step="any">
                            <?php else: ?>
                                <h5>El día de hoy aún no se ha facturado.</h5>
                                <input type="hidden" name="totalCajaDia" id="totalCajaDia" value="0" step="any">
                            <?php endif; ?>
                        </div>
                        <div class="col  mb-3">
                            <button type="submit" class="btn btn-info p-3 w-100 text-white">
                                <i class="bi bi-cursor-fill text-white me-3"></i>Cerrar Día
                            </button>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-4 mb-3">
                        <table id="table-Efectivo" class="table table-dark table-striped table-hover caption-top">
                            <caption>Efectivo</caption>
                            <thead class="table-warning">
                                <tr>
                                    <th>Factura</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach ($facturasEfectivo as $item):
                                    $total += $item['monto'];
                                ?>
                                    <tr>
                                        <td><?php echo $item['id_factura']; ?></td>
                                        <td><?php echo number_format($item['monto'], 2, '.', ','); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong><?php echo number_format($total, 2, '.', ','); ?></strong></td>
                                    <input type="hidden" name="efectivoCaja" id="efectivoCaja" value="<?php echo $total; ?>" step="any">
                                </tr>
                            </tbody>
                        </table>


                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-4 mb-3">
                        <table id="table-Tarjeta" class="table table-dark table-striped table-hover caption-top">
                            <caption>Tarjeta</caption>
                            <thead class="table-warning">
                                <tr>
                                    <th>Factura</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach ($facturasTarjeta as $item):
                                    $total01 += $item['monto'];
                                ?>
                                    <tr>
                                        <td><?php echo $item['id_factura']; ?></td>
                                        <td><?php echo number_format($item['monto'], 2, '.', ','); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong><?php echo number_format($total01, 2, '.', ','); ?></strong></td> 
                                    <input type="hidden" name="tarjetaCaja" id="tarjetaCaja" value="<?php echo $total01; ?>" step="any">
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-4 mb-3">
                        <table id="table-Sinpe" class="table table-dark table-striped table-hover caption-top">
                            <caption>Sinpe</caption>
                            <thead class="table-warning">
                                <tr>
                                    <th>Factura</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach ($facturasSinpe as $item):
                                    $total02 += $item['monto'];
                                ?>
                                    <tr>
                                        <td><?php echo $item['id_factura']; ?></td>
                                        <td><?php echo number_format($item['monto'], 2, '.', ','); ?></td> 
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong><?php echo number_format($total02, 2, '.', ','); ?></strong></td>
                                    <input type="hidden" name="sinpeCaja" id="sinpeCaja" value="<?php echo $total02; ?>" step="any">
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>  
            </div>
        </form>
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
if (isset($_GET['addCaja'])) {
    $updatedCategoryStatus = $_GET['addCaja'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Actualizado con éxito',
            'text' => 'Cierre de día actualizado.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al actualizar',
            'text' => 'No se pudo actualizar el cierre.',
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

<!-- Move these script tags to the end of the body -->
<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table-Efectivo, #table-Tarjeta, #table-Sinpe').DataTable({
            paging: false,
            searching: false,
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


    });

    
</script>
</body>
</html>
