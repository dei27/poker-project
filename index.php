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
    <meta name="description" content="Explora la auténtica experiencia de sabores moncheños en nuestra microempresa familiar. Desde generaciones, hemos cultivado tradiciones ramonenses que se reflejan en cada producto que ofrecemos. Descubre la esencia de nuestras raíces a través de delicias cuidadosamente elaboradas, donde cada bocado es un viaje a la rica herencia de las tradiciones moncheñas. ¡Bienvenido a un mundo donde la vida tiene el sabor tan bueno como Monchister!">
    <meta name="keywords" content="microempresa familiar, tradiciones ramonenses, sabores moncheños, productos artesanales, herencia culinaria, delicias familiares, monchister, autenticidad, experiencias gastronómicas">
    <meta name="author" content="Deivi Campos">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
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
                    <img src="./assets/images/monchisterLogo.jpeg" alt="logo-clubgg" class="w-50 rounded-circle" >
                </div>
                <div class="col-md-12 mt-5">
                    <a href="views/login.php" class="btn btnRed w-50 p-3 text-white">Administrador</a>
                </div>
                <!-- <div class="col-md-12 my-3">
                    <a href="views/guest.php" class="btn btnGray w-50 p-3 text-white">Cliente</a>
                </div> -->
            </div>
        </div>
    </div>
</div>
<script src="./assets/js/main.js"></script>
</div>

</body>
</html>
