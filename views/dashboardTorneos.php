<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../controllers/controllerTorneos.php');

$torneosData = getAll();
$torneos = json_decode($torneosData, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="El mejor poker en Occidente, Costa Rica. Descubre una experiencia única y emocionante.">
    <meta name="keywords" content="poker, casino, juegos de azar, Occidente, Costa Rica">
    <meta name="author" content="Deivi Campos">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Admin</title>
</head>
<body>
    <header>
    <nav class="navbar sticky-top">
        <div class="container-fluid justify-content-end">
            <form action="../controllers/controller.php" method="post">
                <input type="hidden" name="action" value="logout">
                <button type="submit navbar-brand" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </nav>
    </header>

    <div class="container-fluid mt-5 vh-100 p-5">
        <div class="table-responsive card p-3">
            <div class="card-header mb-3">Mis torneos</div>
            <table id="example" class="table table-dark table-striped table-hover">
                <thead class="table-warning">
                    <tr>
                        <th>Torneo</th>
                        <th>Lugar</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Entrada</th>
                        <th>Recompra</th>
                        <th>Add-On</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($torneos as $torneo):?>
                        
                        <tr>
                            <td><?php echo $torneo['nombre']; ?></td>
                            <td><?php echo $torneo['lugar']; ?></td>
                            <td><?php echo $torneo['fecha']; ?></td>
                            <td><?php echo $torneo['hora']; ?></td>
                            <td><?php echo $torneo['entrada']; ?></td>
                            <td><?php echo $torneo['recompra']; ?></td>
                            <td><?php echo $torneo['add_on']; ?></td>
                            <td>
                            <a href="../controllers/controllerTorneos.php?action=delete&id=<?php echo $torneo['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este torneo?')"><i class="bi bi-trash-fill text-white"></i></a>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
        if (isset($_GET['delete']) && $_GET['delete'] == 1) {
            // Mostrar la alerta de eliminación exitosa con SweetAlert2
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Eliminado con éxito",
                    timer: 2500,
                    text: "El torneo ha sido eliminado con éxito.",
                    showConfirmButton: false
                });
            </script>
            ';
        }
    ?>

</div>

<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            lengthChange: false,
            pageLength: 10,
            info: false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        });
    });
</script>
</body>
</html>
