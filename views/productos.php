<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../controllers/controllerProducts.php');
include('../controllers/controllerCategories.php');
include('../controllers/controllerUnidadMedida.php');

$prodcutsData = getAll();
$products = json_decode($prodcutsData, true);


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
    <title>Inventario</title>
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

<?php if (isset($_SESSION["user"])) { ?>
    <div class="container-fluid p-5">
        <div class="card p-3">
            <h4 class="card-header mb-3 py-3">Inventario</h4>
            <?php
            if(isset($_SESSION["user"])){
            echo '<h5 class="card-text">
                    <a href="#" class="text-decoration-none text-info" data-bs-toggle="modal" data-bs-target="#addTournament">
                    <img src="../assets/images/addProduct.png" alt="Crear torneo" class="img-fluid"> Agregar nuevo producto.
                    </a>
                </h5>';
            }
            ?>
            <div class="table-responsive">
            <table id="example" class="table table-dark table-striped table-hover">
                <thead class="table-warning">
                    <tr>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Medida</th>
                        
                        <?php 
                            if(isset($_SESSION["user"])){
                                echo '<th>Acciones</th>';
                            }
                        ?>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product):?>
                        <tr>
                            <td><?php echo $product['nombre']; ?></td>
                            <td><?php echo empty($product['cantidad']) ? 'Sin cantidad' : $product['cantidad']; ?></td>
                            <td><?php echo $product['precio']; ?></td>
                            <td>
                            <?php
                                $categoryInfo = getNameCategoryById($product['id_categoria']);
                                $categoryName = json_decode($categoryInfo, true);

                                if (isset($categoryName['nombre_categoria'])) {
                                    echo $categoryName['nombre_categoria'];
                                } else {
                                    echo 'Categoría no encontrada';
                                }
                            ?>
                            </td>
                            <td>
                            <?php
                                $unindadInfo = getNameUnidadMedida($product['id_unidad']);
                                $unidadName = json_decode($unindadInfo, true);

                                if (isset($unidadName['nombre_unidad'])) {
                                    echo $unidadName['nombre_unidad'];
                                } else {
                                    echo 'Unidad de medida no encontrada';
                                }
                            ?>
                            </td>
                                <?php
                                $categories = json_decode(getAllCategories(), true);
                                $selectedCategoryId = $product['id_categoria'];

                                $unidades = json_decode(getAllUnidades(), true);
                                $selectedUnidadId = $product['id_unidad'];

                                if (isset($_SESSION["user"])) :
                                ?>
                                <td>
                                    <a href="../controllers/controllerProducts.php?action=delete&id=<?php echo $product['id_producto']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')" class="text-decoration-none text-white mx-3">
                                        <i class="bi bi-trash-fill text-white"></i>
                                    </a>
                                    <a href="#" class="text-decoration-none text-white" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $product['id_producto']; ?>">
                                        <i class="bi bi-pencil-fill text-white"></i>
                                    </a>
                                </td>

                                <div class="modal fade" id="editModal<?php echo $product['id_producto']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $product['id_producto']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header text-bg-dark">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $product['id_producto']; ?>">Editar Producto</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../controllers/controllerProducts.php" method="post" class="form-floating">
                                                    <input type="hidden" name="id" value="<?php echo $product['id_producto']; ?>">
                                                    <input type="hidden" name="action" value="edit">

                                                    <div class="form-group mb-3">
                                                        <label for="nombreProducto">Nombre</label>
                                                        <input class="form-control" id="nombreProducto" name="nombreProducto" placeholder="Nombre..." value="<?php echo $product['nombre']; ?>" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="cantidadProducto" class="form-label">Cantidad</label>
                                                        <input type="number" class="form-control" id="cantidadProducto" name="cantidadProducto" placeholder="Cantidad..." step="any" value="<?php echo htmlspecialchars($product['cantidad']); ?>" min=1 required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="precioProducto" class="form-label">Precio</label>
                                                        <input type="number" class="form-control" id="precioProducto" name="precioProducto" placeholder="Precio..." step="any" value="<?php echo htmlspecialchars($product['precio']); ?>" min=1 required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="categoriaProducto" class="form-label">Categoría</label>
                                                        <select class="form-select" id="categoriaProducto" name="categoriaProducto" placeholder="Categoría..." required>
                                                            <?php foreach ($categories as $category) : ?>
                                                                <?php
                                                                $categoryId = $category['id_categoria'];
                                                                $categoryName = $category['nombre_categoria'];
                                                                $selected = ($categoryId == $selectedCategoryId) ? 'selected' : '';
                                                                ?>
                                                                <option value="<?php echo $categoryId; ?>" <?php echo $selected; ?>><?php echo $categoryName; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="unidadProducto" class="form-label">Unidad de medida</label>
                                                        <select class="form-select" id="unidadProducto" name="unidadProducto" placeholder="Unidad..." required>
                                                            <?php foreach ($unidades as $unidad) : ?>
                                                                <?php
                                                                $id_unidad = $unidad['id_unidad'];
                                                                $unidadName = $unidad['nombre_unidad'];
                                                                $selected = ($id_unidad == $selectedUnidadId) ? 'selected' : '';
                                                                ?>
                                                                <option value="<?php echo $id_unidad; ?>" <?php echo $selected; ?>><?php echo $unidadName; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3 text-end">
                                                        <input type="submit" class="btn btn-primary" value="Guardar">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>

        <!-- modal agregar producto -->
        <div class="modal fade" id="addTournament" tabindex="-1" aria-labelledby="addTournamentLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header text-bg-dark">
                    <h5 class="modal-title" id="addTournament">Agregar Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                        <form action="../controllers/controllerProducts.php" method="post" class="form-floating">
                            <input type="hidden" name="action" value="add">
                            <div class="form-group mb-3">
                                <label for="nombreProductoI">Nombre</label>
                                <input class="form-control" id="nombreProductoI" name="nombreProductoI" placeholder="Nombre..." value="" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="cantidadProductoI">Cantidad</label>
                                <input type="number" class="form-control" id="cantidadProductoI" name="cantidadProductoI" placeholder="Precio..." step="any" required min=1>
                            </div>

                            <div class="form-group mb-3">
                                <label for="precioProducto">Precio</label>
                                <input type="number" class="form-control" id="precioProductoI" name="precioProductoI" placeholder="Precio..." step="any" required min=1>
                            </div>

                            <div class="form-group mb-3">
                                <label for="categoriaProductoI">Categoría</label>
                                <select class="form-select" id="categoriaProductoI" name="categoriaProductoI" placeholder="Categoría...">
                                    <?php foreach ($categories as $category) : ?>
                                        <?php
                                        $categoryId = $category['id_categoria'];
                                        $categoryName = $category['nombre_categoria'];
                                        ?>
                                        <option value="<?php echo $categoryId; ?>"><?php echo $categoryName; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="unidadProductoI">Unidad de medida</label>
                                <select class="form-select" id="unidadProductoI" name="unidadProductoI" placeholder="Categoría...">
                                    <?php foreach ($unidades as $unidad) : ?>
                                        <?php
                                        $unidadId = $unidad['id_unidad'];
                                        $unidadName = $unidad['nombre_unidad'];
                                        ?>
                                        <option value="<?php echo $unidadId; ?>"><?php echo $unidadName; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group mt-3 text-end">
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-info text-white w-50 p-3" value="Guardar">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        if (isset($_GET['delete']) && $_GET['delete'] == 1) {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Eliminado con éxito",
                    timer: 2500,
                    text: "El producto ha sido eliminado.",
                    showConfirmButton: false
                });
            </script>
            ';
        }
    ?>

    <?php
        if (isset($_GET['updatedProduct']) && $_GET['updatedProduct'] == 1) {
            // Mostrar la alerta de eliminación exitosa con SweetAlert2
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Actualizado con éxito",
                    timer: 2500,
                    text: "Producto actualizado.",
                    showConfirmButton: false
                });
            </script>
            ';
        }elseif(isset($_GET['updatedProduct']) && $_GET['updatedProduct'] == 0){
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Notificación",
                    timer: 2500,
                    text: "El producto no se ha podido actualizar",
                    showConfirmButton: false
                });
            </script>
            ';
        }
    ?>

    <?php
        if (isset($_GET['insertedProduct']) && $_GET['insertedProduct'] == 1) {
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Agregado con éxito",
                    timer: 2500,
                    text: "Producto creado.",
                    showConfirmButton: false
                });
            </script>
            ';
        }elseif(isset($_GET['insertedProduct']) && $_GET['insertedProduct'] == 0){
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Notificación",
                    timer: 2500,
                    text: "No se pudo crear el producto, intente de nuevo",
                    showConfirmButton: false
                });
            </script>
            ';
        }
    ?>

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

<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            lengthChange: false,
            pageLength: 5,
            info: false,
            responsive: true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
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
