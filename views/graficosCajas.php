<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../controllers/controllerCierresCajas.php');
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
            <div class="col-sm-12 col-md-12">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="anioInput">Año</label>
                    <select class="form-select text-center" id="anioInput" >
                        <!-- Options se generan dinámicamente al cargar la página -->
                    </select>
                </div>
            </div>
        </div>

        <div class="card py-5 mb-5">
            <div class="row row-cols-1 row-cols-md-3 g-4 calendario-container-mensual"></div>
            <div class="row">
                <div class="card-body">
                    <h5 class="card-tile text-center mb-3">Montos totales por mes</h5>
                    <div class="col">
                    <canvas id="lineChart"></canvas>
                </div>
                </div>
                
            </div>
        </div>


        <div class="card py-5">
            <div class="row row-cols-1 row-cols-md-3 g-4 calendario-container"></div>
        </div>
    </div>


<!-- modal agregar categoria -->
<div class="modal fade" id="addTournament" tabindex="-1" aria-labelledby="addTournamentLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header text-bg-dark">
                    <h5 class="modal-title" id="addTournament">Agregar Nueva Categoría</h5>
                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                        <form action="../controllers/controllerCategories.php" method="post" class="form-floating">
                            <input type="hidden" name="action" value="add">
                            <div class="form-group mb-3">
                                <label for="nombreCategoriaI">Nombre</label>
                                <input class="form-control" id="nombreCategoriaI" name="nombreCategoriaI" placeholder="Nombre categoría..." value="" required>
                            </div>
                            <div class="form-group mt-3 text-end">
                                <div class="col-md-12">
                                <button type="submit" class="btn btn-primary  text-white w-100 p-3">
                                <i class="bi bi-cursor-fill text-white me-3"></i>
                                Guardar
                                </button>
                                </div>
                            </div>
                        </form>
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

    function generarPieChart(chartId, mes, anio, nombreMes) {
        
        var ctxLine = document.getElementById('lineChart').getContext('2d');

        $.ajax({
            url: '../controllers/controllerCierresCajas.php?action=crearPieMensual&year=' + anio,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                var max = Math.max.apply(Math, data.map(function(obj) {
                    return obj.total_mes;
                }));

                var nombresMeses = ['','Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

                var labels = data.map(function(obj) {
                    return nombresMeses[obj.mes];
                });

                var valores = data.map(function(obj) {
                    return obj.total_mes;
                });

                // Destruir el gráfico existente si existe
                if (typeof barChart !== 'undefined') {
                    barChart.destroy();
                }

                // Crear el nuevo gráfico de barras
                barChart = new Chart(ctxLine, {
                    type: 'bar',
                    data: {
                        labels: labels, // Nombres de los meses
                        datasets: [{
                        label: 'Mes',
                        data: valores, // Valores del total para cada mes
                        backgroundColor: '#36A2EB',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                        },
                        animation: {
                            duration: 3000,
                            easing: 'easeInOutQuart',
                            animateRotate: true,
                            animateScale: true
                        },
                        scales: {
                        x: {
                            grid: {
                            display: false
                            }
                        },
                        y: {
                            min: 0,
                            max: max,
                            ticks: {
                            stepSize: Math.ceil(max / 10)
                            }
                        }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener los datos de la base de datos:', error);
            }
        });




        $.ajax({
            url: '../controllers/controllerCierresCajas.php?action=crearPie&year=' + anio,
            method: 'GET',
            dataType: 'json',
            success: function(dataPie) {
                var ctx = document.getElementById(chartId).getContext('2d');
                var data;

                var datosMes = dataPie.find(item => item.mes === mes);

                if (parseFloat(datosMes.efectivo) === 0 && parseFloat(datosMes.tarjeta) === 0 && parseFloat(datosMes.sinpe) === 0) {
                    data = {
                        labels: ['No hay datos'],
                        datasets: [{
                            data: [0],
                            backgroundColor: ['#CCCCCC']
                        }]
                    };
                } else {
                    data = {
                        labels: ['Efectivo', 'Tarjeta', 'Sinpe'],
                        datasets: [{
                            data: [parseFloat(datosMes.efectivo), parseFloat(datosMes.tarjeta), parseFloat(datosMes.sinpe)],
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                        }]
                    };
                }

                var options = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: nombreMes,
                        },
                        datalabels: {
                            color: '#FFFFFF', // Color del texto de las etiquetas
                            formatter: (value, ctx) => { // Formato de las etiquetas
                                // Obtener el valor y el índice del conjunto de datos
                                let labelIndex = ctx.dataIndex;
                                return data.datasets[0].data[labelIndex] !== 0 ? value : ''; // Mostrar el valor si no es cero, de lo contrario, mostrar cadena vacía
                            }
                        }
                    },
                    animation: {
                            duration: 2000,
                            easing: 'easeInOutQuart',
                            animateRotate: true,
                            animateScale: true
                    }
                };

                new Chart(ctx, {
                    type: 'pie',
                    data: data,
                    options: options
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener los datos de la base de datos:', error);
            }
        });
    }

    window.onload = function() {
        var anioInput = document.getElementById('anioInput');

        // Llenar el select de año desde el año actual hasta el 2030 y seleccionar el año actual
        var anioActual = new Date().getFullYear();
        for (var i = anioActual; i <= 2030; i++) {
            var option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            anioInput.appendChild(option);
        }
        anioInput.value = anioActual;

        // Llamar a la función generarCalendario con el año seleccionado
        generarCalendario(anioActual);

        // Llamar a la función generarCalendario cada vez que cambie el año seleccionado
        anioInput.addEventListener('change', function() {
            var anioSeleccionado = parseInt(this.value);
            generarCalendario(anioSeleccionado);
        });
    };

    function generarCalendario(anio) {
        var container = document.querySelector('.calendario-container');
        container.innerHTML = '';

        // Generar un gráfico de pastel para cada mes del año seleccionado
        var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        for (var mes = 0; mes < 12; mes++) {
            var nombreMes = meses[mes]; // Obtener el nombre del mes
            var pieChartId = 'pieChart' + mes; // Id único para cada gráfico de pastel
            var pieChartContainer = document.createElement('div');
            pieChartContainer.classList.add('col', 'col-md-4');

            // Crear el elemento canvas donde se renderizará el gráfico
            var canvas = document.createElement('canvas');
            canvas.id = pieChartId;
            canvas.width = 300;
            canvas.height = 300;

            pieChartContainer.appendChild(canvas);
            container.appendChild(pieChartContainer);

            // Generar el gráfico de pastel para el mes actual
            generarPieChart(pieChartId, mes+1, anio, nombreMes);
        }
    }
</script>
</body>
</html>
