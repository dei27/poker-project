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
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Login</title>
</head>
<body>

<div class="container mt-5 vh-100 p-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-header text-bg-dark p-3">Ingreso</div>
                <div class="card-body text-center">
                    <form action="../controllers/controller.php" method="post">
                        <input type="hidden" name="action" value="login">
                        <input type="hidden" name="type" value="admin">
                        <div class="mb-3">
                            <label for="nickname" class="form-label">Nickname</label>
                            <input type="text" class="form-control" id="nickname" name="nickname" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-secondary">Iniciar sesión</button>
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
</body>
</html>
