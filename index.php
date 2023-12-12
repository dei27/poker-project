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
    <link rel="stylesheet" href="./node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Inicio</title>
</head>
<body>

<div class="container mt-5 vh-100">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 row text-center bg-transparent">
                <div class="col-md-12">
                    <img src="./assets/images/logo.jpg" alt="" class="w-50 rounded-circle" >
                </div>
                <div class="col-md-12 mt-5">
                    <a href="views/login.php" class="btn btnRed w-50 p-3 text-white">Login</a>
                </div>
                <div class="col-md-12 my-3">
                    <a href="views/guest.php" class="btn btnGray w-50 p-3 text-white">Invitado</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./assets/js/main.js"></script>
</div>

</body>
</html>
