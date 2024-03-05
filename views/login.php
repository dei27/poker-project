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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Login</title>
</head>
<body>
    <header>
    <nav class="navbar sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fs-3 text-dark" href="../index.php"><i class="bi bi-house-door-fill fs-2"></i></a>
        <a class="navbar-brand fs-3 text-dark" href="../index.php"><i class="bi bi-arrow-left-circle-fill fs-2"></i></a>
        </div>
    </div>
    </nav>
    </header>

<div class="container mt-5 p-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0">
                <h4 class="card-header text-bg-dark p-3">Acceso al sistema</h4>
                <div class="card-body">
                    <form action="../controllers/controller.php" method="post">
                        <input type="hidden" name="action" value="login">
                        <input type="hidden" name="type" value="admin">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Nickname</span>
                            <input type="text" class="form-control" id="nickname" name="nickname" required placeholder="Ingrese su nickname..."aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Contraseña</span>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Ingrese su contraseña..." aria-label="Contraseña" aria-describedby="basic-addon1">
                            <button class="btn btn-dark" type="button" id="togglePassword"><i class="bi bi-eye-slash"></i></button>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 p-3 mt-3"><i class="bi bi-door-open-fill me-3"></i>Iniciar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
        if (isset($_GET['credenciales']) && $_GET['credenciales'] == 0) {
            // Mostrar la alerta de eliminación exitosa con SweetAlert2
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Inicio no valido",
                    timer: 3000,
                    text: "Credenciales incorrectas, intente de nuevo.",
                    showConfirmButton: false
                });
            </script>
            ';
        }
    ?>
</div>
<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $("#togglePassword").click(function() {
        var passwordInput = $("#password");
        var type = passwordInput.attr("type") === "password" ? "text" : "password";
        passwordInput.attr("type", type);
        $(this).html(type === "password" ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>');
    });
});
</script>


</body>
</html>
