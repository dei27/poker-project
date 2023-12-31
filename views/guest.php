<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/guest.css">
    <title>Inicio</title>
</head>
<body>
    <header>
    <?php
        include('nav.php');
    ?>
    </header>
    <main>
    <div class="container mt-5 vh-100 p-5">
    <div class="row justify-content-center">
        <div class="col-md-6 falling-letters align-self-center justify-items-center mb-5 p-1">
            <div class="row text-center justify-content-around p-3">
                <div class="col-4">
                    <a href="lacalleja.php"><img src="../assets/images/lacalleja.jpg" alt="lacalleja-logo" class="img-fluid rounded-circle"></a>
                </div>
                <div class="col-4">
                    <a href="clubgg.php"><img src="../assets/images/logo.jpg" alt="lacalleja-logo" class="img-fluid rounded-circle"></a>
                </div>
                <div class="col-4">
                    <a href="pokerdtv.php"><img src="../assets/images/lacalleja.jpg" alt="lacalleja-logo" class="img-fluid rounded-circle"></a>
                </div>
            </div>
        </div>
        <div class="col-md-6 falling-letters">
            <div class="card border-0 row text-center">
                <div class="card-header text-bg-dark py-3"> ¡UN EVENTO NUNCA ANTES VISTO!</div>
                <h5 class="card-title mt-3">TORNEO NAVIDEÑO DE OCCIDENTE</h5>
                <p class="card-text"><i class="bi bi-geo-alt-fill mx-3"></i></i>PLAZA GASTRONÓMICA LA CALLEJA</p>
                <p class="card-text"><i class="bi bi-piggy-bank mx-3"></i>ESTIMADO: +¢700,000 </p>
                <p class="card-text"><i class="bi bi-calendar3 mx-3"></i>17 diciembre 2023</p>
                <p class="card-text"><i class="bi bi-alarm mx-3"></i>1:00 pm</p>
                <p class="card-text"><i class="bi bi-coin mx-3"></i>BUY-IN: ¢23,000</p>
                <div class="col-md-12 my-3">
                    <a href="pokerNavidad.php" class="btn btnGray w-50 p-3 text-white">Ver más</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    </main>
    <footer>

    </footer>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
