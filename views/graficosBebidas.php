<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../controllers/controllerPagosPersonas.php');
$pagos = json_decode( getAllPagos(),true);
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
    <title>Estadisticas</title>
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

<?php
if (isset($_SESSION["user"]) && (isset($_SESSION['role']) && $_SESSION['role'] === 1)) {
?>
    <div class="container-fluid p-5">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="mesInput">Mes</label>
                    <select class="form-select" id="mesInput">
                        <!-- Options se generan dinámicamente al cargar la página -->
                    </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="anioInput">Año</label>
                    <select class="form-select" id="anioInput">
                        <!-- Options se generan dinámicamente al cargar la página -->
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card py-5 mb-3">
                    <!-- Contenedor para el gráfico de pastel -->
                    <div class="card-body">
                        <h5 class="card-tile text-center mb-3">Recetas por producto en el mes</h5>
                        <div id="pieChartContainer" class="text-center"></div>
                    </div>  
                </div>
            </div>
            <div class="col">
                <div class="card py-5 mb-3">
                    <div class="card-body">
                        <h5 class="card-tile text-center mb-3">Recetas por producto en el año</h5>
                        <div id="pieChartContainerYear" class="text-center"></div>
                    </div>  
                </div>
            </div>
        </div>
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

<?php
if (isset($_GET['deleteCategory'])) {
    $updatedCategoryStatus = $_GET['deleteCategory'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Eliminada con éxito',
            'text' => 'Categoría eliminada.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al eliminar',
            'text' => 'No se pudo eliminar la categoría.',
        ];

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "' . $messageConfig['icon'] . '",
            title: "' . $messageConfig['title'] . '",
            timer: 2500,
            text: "' . $messageConfig['text'] . '",
            showConfirmButton: false
        });
    </script>
    ';
}
?>

<?php
if (isset($_GET['updatedCategory'])) {
    $updatedCategoryStatus = $_GET['updatedCategory'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Actualizado con éxito',
            'text' => 'Categoría actualizada.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al actualizar',
            'text' => 'No se pudo actualizar la categoría.',
        ];

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "' . $messageConfig['icon'] . '",
            title: "' . $messageConfig['title'] . '",
            timer: 2500,
            text: "' . $messageConfig['text'] . '",
            showConfirmButton: false
        });
    </script>
    ';
}

if (isset($_GET['insertedCategory'])) {
    $updatedCategoryStatus = $_GET['insertedCategory'];
    
    $messageConfig = ($updatedCategoryStatus == 1)
        ? [
            'icon' => 'success',
            'title' => 'Creada con éxito',
            'text' => 'Categoría creada.',
        ]
        : [
            'icon' => 'error',
            'title' => 'Error al crear',
            'text' => 'No se pudo crear la categoría.',
        ];

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "' . $messageConfig['icon'] . '",
            title: "' . $messageConfig['title'] . '",
            timer: 2500,
            text: "' . $messageConfig['text'] . '",
            showConfirmButton: false
        });
    </script>
    ';
}
?>
<!-- Move these script tags to the end of the body -->
<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    var coloresGenerados = [];


    $(document).ready(function() {

        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        $('#example').DataTable({
            lengthChange: false,
            pageLength: 5,
            info: false,
            responsive: true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            initComplete: function(settings, json) {
                $(".dataTables_filter label").addClass("text-dark");
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

    window.onload = function() {
        var anioInput = document.getElementById('anioInput');
        var mesInput = document.getElementById('mesInput');

        var mesActual = new Date().getMonth();
        for (var i = 0; i < 12; i++) {
            var option = document.createElement('option');
            option.value = i + 1; // Asumiendo que el mes en tu backend está basado en 1-12
            option.textContent = obtenerNombreMes(i);
            mesInput.appendChild(option);
        }
        mesInput.value = mesActual + 1; // +1 para coincidir con el valor esperado por tu backend

        var anioActual = new Date().getFullYear();
        for (var i = anioActual; i <= 2030; i++) {
            var option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            anioInput.appendChild(option);
        }
        anioInput.value = anioActual;

        // Llama a generarPieChart aquí para inicializar el gráfico al cargar la página
        generarPieChart('pieChart', mesInput.value, anioInput.value, obtenerNombreMes(mesActual));
        generarPieChartYear('pieChartYear', anioInput.value);

        anioInput.addEventListener('change', function() {
            // Corrección: se debe pasar el mes y año seleccionado correctamente
            generarPieChart('pieChart', mesInput.value, this.value, obtenerNombreMes(mesInput.selectedIndex));
            generarPieChartYear('pieChartYear', anioInput.value);
        });

        mesInput.addEventListener('change', function() {
            // Corrección: se debe pasar el mes y año seleccionado correctamente
            generarPieChart('pieChart', this.value, anioInput.value, obtenerNombreMes(this.selectedIndex));
        });
    };

    function obtenerNombreMes(indice) {
        var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return meses[indice];
    }

    function generarPieChart(chartId, mes, anio, nombreMes) {
        var container = document.getElementById('pieChartContainer');
        container.innerHTML = ''; // Limpiar el contenedor
        var canvas = document.createElement('canvas');
        canvas.id = chartId;
        container.appendChild(canvas);

        var ctx = canvas.getContext('2d');

        var arrayRecetasObtenidas = <?php echo json_encode($pagos); ?>;

        var recetasFiltradas = [];

        if (typeof arrayRecetasObtenidas !== 'undefined') {
            recetasFiltradas = arrayRecetasObtenidas.filter(function(receta) {
                var fechaReceta = new Date(receta.fecha_pago);
                return fechaReceta.getMonth() + 1 == mes && fechaReceta.getFullYear() == anio && receta.tipo_producto == 1;
            });
        }

        var labels = [];
        var values = [];
        var backgroundColors = [];

        if (recetasFiltradas.length > 0) {
            var recetasAgrupadas = {}; // Objeto para almacenar las recetas agrupadas por nombre_producto

            recetasFiltradas.forEach(function(receta) {
                var nombreProducto = receta.nombre_producto;
                var cantidad = receta.cantidad;

                if (!recetasAgrupadas[nombreProducto]) {
                    recetasAgrupadas[nombreProducto] = cantidad; // Inicializar la cantidad si es la primera vez que se encuentra el nombre_producto
                } else {
                    recetasAgrupadas[nombreProducto] += cantidad; // Sumar la cantidad si el nombre_producto ya existe en el objeto
                }
            });

            // Procesar los datos agrupados para el gráfico
            labels = Object.keys(recetasAgrupadas);
            values = Object.values(recetasAgrupadas);

            // Generar colores aleatorios
            backgroundColors = labels.map(function() {
                return generarColorAleatorio();
            });
        }else {
            // Si no hay datos, establecer el label y el color de fondo predeterminados
            labels.push('Sin datos');
            values.push(1); // Asegúrate de tener al menos un valor
            backgroundColors.push('#CCCCCC');
        }

        // Configuración del gráfico
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors.map(function(color) {
                        return color.replace('0.9)', '1)'); // Reemplaza la transparencia por opacidad completa
                    }),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    }

    function generarPieChartYear(chartId, anio) {
        var container = document.getElementById('pieChartContainerYear');
        container.innerHTML = ''; // Limpiar el contenedor
        var canvas = document.createElement('canvas');
        canvas.id = chartId;
        container.appendChild(canvas);

        var ctx = canvas.getContext('2d');

        var arrayRecetasObtenidas = <?php echo json_encode($pagos); ?>;

        var recetasFiltradas = [];

        if (typeof arrayRecetasObtenidas !== 'undefined') {
            recetasFiltradas = arrayRecetasObtenidas.filter(function(receta) {
                var fechaReceta = new Date(receta.fecha_pago);
                return fechaReceta.getFullYear() == anio && receta.tipo_producto == 1;
            });
        }

        var labels = [];
        var values = [];
        var backgroundColors = [];

        if (recetasFiltradas.length > 0) {
            var recetasAgrupadas = {}; // Objeto para almacenar las recetas agrupadas por nombre_producto

            recetasFiltradas.forEach(function(receta) {
                var nombreProducto = receta.nombre_producto;
                var cantidad = receta.cantidad;

                if (!recetasAgrupadas[nombreProducto]) {
                    recetasAgrupadas[nombreProducto] = cantidad; // Inicializar la cantidad si es la primera vez que se encuentra el nombre_producto
                } else {
                    recetasAgrupadas[nombreProducto] += cantidad; // Sumar la cantidad si el nombre_producto ya existe en el objeto
                }
            });

            // Procesar los datos agrupados para el gráfico
            labels = Object.keys(recetasAgrupadas);
            values = Object.values(recetasAgrupadas);

            // Generar colores aleatorios
            backgroundColors = labels.map(function() {
                return generarColorAleatorio();
            });
        } else {
            // Si no hay datos, establecer el label y el color de fondo predeterminados
            labels.push('Sin datos');
            values.push(1); // Asegúrate de tener al menos un valor
            backgroundColors.push('#CCCCCC');
        }

        // Configuración del gráfico
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors.map(function(color) {
                        return color.replace('0.9)', '1)'); // Reemplaza la transparencia por opacidad completa
                    }),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    }


    function generarColorAleatorio() {
        var r, g, b;
        var color;

        do {
            r = Math.floor(Math.random() * 256); // Componente rojo
            g = Math.floor(Math.random() * 256); // Componente verde
            b = Math.floor(Math.random() * 256); // Componente azul
            color = 'rgba(' + r + ', ' + g + ', ' + b + ', 0.9)'; // Color generado
        } while (coloresGenerados.includes(color)); // Verificar si el color generado ya está en la lista de colores

        coloresGenerados.push(color); // Agregar el nuevo color a la lista de colores generados
        return color;
    }
</script>
</body>
</html>
