<nav class="navbar sticky-top">
    <a href="./dashboard.php" class="text-decoration-none text-dark navbar-brand"><i class="bi bi-arrow-left-circle-fill text-dark fs-3 px-3"></i></a>
    <div class="navbar-brand">
        <form action="../controllers/controller.php" method="post">
            <input type="hidden" name="action" value="logout">
            <button type="submit navbar-brand" class="btn btn-danger">Cerrar sesión</button>
        </form>
    </div>
</nav>