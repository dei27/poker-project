<?php
session_start();
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
    <title>Admin</title>
</head>
<body>

<div class="container mt-5 vh-100 p-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-header">Admin</div>
                <div class="card-body">
                    <?php if (isset($_SESSION['user'])) : ?>
                        <p>Welcome, <?php echo $_SESSION['user']['nickname']; ?>!</p>
                        <form action="../controllers/controller.php" method="post">
                            <input type="hidden" name="action" value="logout">
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    <?php else : ?>
                        <p>You are not logged in.</p>
                        <!-- Puedes redirigir al usuario a la página de inicio de sesión si lo prefieres -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../assets/js/main.js"></script>
</body>
</html>
