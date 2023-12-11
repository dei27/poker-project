<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../controllers/controllerTorneos.php');
include('../controllers/controllerCiudades.php');
include('../controllers/controllerJugadores.php');

$torneosData = getAll();
$torneos = json_decode($torneosData, true);

$ciudadesData = getAllCities();
$ciudades = json_decode($ciudadesData, true);

$jugadoresData = getAllPlayers();
$jugadores = json_decode($jugadoresData, true);
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

    <?php
    if (isset($_SESSION['user'])) {
        echo "<div class='container-sm mt-5 vh-100 p-5'>
                <div class='card p-3 border-0 bg-transparent'>
                    <div class='row'>
                        <div class='col-6'>
                            <a href='torneos.php' class='col-6 text-center w-100 my-5 p-1 rounded-pill border-0 btn btn-light py-3' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Ver torneos'><i class='bi bi-trophy-fill fs-1 text-dark'></i></a>
                            <a href='ciudades.php' class='col-6 text-center w-100 my-5 p-1 rounded-pill border-0 btn btn-light py-3' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Ver Ciudades'><i class='bi bi-compass-fill fs-1 text-dark'></i></a>
                        </div>
                        <div class='col-6'>
                            <a href='jugadores.php' class='col-6 text-center w-100 my-5 p-1 rounded-pill border-0 btn btn-light py-3' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Ver jugadores'><i class='bi bi-people-fill fs-1 text-dark'></i></a>
                            <a href='lugares.php' class='col-6 text-center w-100 my-5 p-1 rounded-pill border-0 btn btn-light py-3' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' data-bs-title='Ver lugares'><i class='bi bi-pin-map-fill fs-1 text-dark'></i></a>
                        </div>
                    </div>
                </div>
            </div>";
    } else {
        echo '<div class="container-fluid mt-5 vh-100 p-5">
                <div class="card p-3">
                <p class="card-text">-----> No tienes el suficiente poder para estar acá.</p>
                </div>
            </div>';
    }

    if (isset($_GET['deleteCity']) && $_GET['deleteCity'] == 1) {
        // Mostrar la alerta de eliminación exitosa con SweetAlert2
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: "success",
                title: "Eliminada con éxito",
                timer: 2500,
                text: "La ciudad ha sido eliminada con éxito.",
                showConfirmButton: false
            });
        </script>
        ';
    }
?>

<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>

</body>
</html>
