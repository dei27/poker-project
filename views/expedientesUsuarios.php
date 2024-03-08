<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../controllers/controllerUsuarios.php');
include('../controllers/controlleRegistrosFaltas.php');

$usuario = null;

if (isset($_GET['user'])) {
    $usuario = $_GET['user'];
    $usuarioModel = json_decode(getUsuarioById($usuario), true);
    $usuarioFaltasModel = json_decode(getAllRegistrosFaltasHorariosByIdUsuario($usuario), true);
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Expedientes</title>
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
            <input type="hidden" name="idUsuarioInput" id="idUsuarioInput" value="<?php echo $usuarioModel["id"];?>">
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
        <div class="row row-cols-1 row-cols-md-3 g-4 calendario-container">
            <!-- Aquí se generan las cards del calendario -->
            <!-- Puedes rellenar estas cards con los datos de tu horario -->
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

    window.onload = function() {
        var mesInput = document.getElementById('mesInput');
        var anioInput = document.getElementById('anioInput');
        var idUsuarioInput = document.getElementById('idUsuarioInput').value;

        // Llenar el select de mes con los nombres de los meses y seleccionar el mes actual
        var mesActual = new Date().getMonth();
        for (var i = 0; i < 12; i++) {
            var option = document.createElement('option');
            option.value = i;
            option.textContent = obtenerNombreMes(i);
            mesInput.appendChild(option);
        }
        mesInput.value = mesActual;

        // Llenar el select de año desde el año actual hasta el 2034 y seleccionar el año actual
        var anioActual = new Date().getFullYear();
        for (var i = anioActual; i <= 2030; i++) {
            var option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            anioInput.appendChild(option);
        }
        anioInput.value = anioActual;

        // Llamar a la función generarCalendario con el mes y año seleccionados
        generarCalendario(mesActual, anioActual, idUsuarioInput );

        // Llamar a la función generarCalendario cada vez que cambie el mes o el año seleccionado
        mesInput.addEventListener('change', function() {
            var mesSeleccionado = parseInt(this.value);
            var anioSeleccionado = parseInt(anioInput.value);
            generarCalendario(mesSeleccionado, anioSeleccionado);
        });

        anioInput.addEventListener('change', function() {
            var mesSeleccionado = parseInt(mesInput.value);
            var anioSeleccionado = parseInt(this.value);
            generarCalendario(mesSeleccionado, anioSeleccionado);
        });
    };

    // Función para obtener el nombre del mes a partir de su número
    function obtenerNombreMes(numeroMes) {
        var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return meses[numeroMes];
    }

    function generarCalendario(mes, anio, idUsuarioInput) {
        var container = document.querySelector('.calendario-container');
        container.innerHTML = ''; // Limpiar el contenido actual del contenedor

        // Array con los nombres de los días de la semana
        var diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        // Obtener el primer día del mes y el último día del mes
        var primerDia = new Date(anio, mes, 1);
        var ultimoDia = new Date(anio, mes + 1, 0);

        // Generar las cards del calendario
        var diaActual = new Date(primerDia);
        while (diaActual <= ultimoDia) {
            var card = document.createElement('div');
            card.classList.add('col', 'day-cell');
            card.setAttribute('data-day', diaActual.getDate());

            var registroDelMes = [];
            var arrayHorarios = <?php echo json_encode($todosLosRegistros); ?>;
            var arrayHorariosFaltas = <?php echo json_encode($usuarioFaltasModel); ?>;

            arrayHorariosFaltas.forEach(function(falta) {
                falta.fecha_inicio = new Date(falta.fecha_inicio);
                falta.fecha_fin = new Date(falta.fecha_fin);
                falta.fecha_fin.setDate(falta.fecha_fin.getDate() + 1);
            });

            var faltasDelDia = arrayHorariosFaltas.filter(function(falta) {
                return diaActual >= falta.fecha_inicio && diaActual <= falta.fecha_fin;
            });

            var faltasContent = faltasDelDia.map(function(falta) {
                return falta.notas.trim() ? `<li>${falta.nombre_tipo} - ${falta.notas}</li>` : `<li>${falta.nombre_tipo}</li>`;
            }).join('');


            if (typeof arrayHorarios !== 'undefined') {
                registroDelMes = arrayHorarios.filter(function(registro) {
                    var fechaRegistro = new Date(registro.hora);
                    var anioRegistro = fechaRegistro.getFullYear();
                    var mesRegistro = fechaRegistro.getMonth() + 1;
                    var diaRegistro = fechaRegistro.getDate();

                    return mesRegistro === mes + 1 && anioRegistro === anio && diaRegistro === diaActual.getDate();
                });
            }

            var cardContent = `
                <div class="card h-100">
                    <div class="card-header">
                        ${diasSemana[diaActual.getDay()]} ${diaActual.getDate()}
                    </div>
                    <div class="card-body align-items-center">
                        <ul class="list-unstyled">
                            ${registroDelMes.length > 0 ? registroDelMes.map(registro => `<li>${registro.nombre_tipo} - ${new Date(registro.hora).toLocaleTimeString()}</li>`).join('') : '<li>Sin ingresos</li>'}
                            ${faltasContent}
                        </ul>
                    </div>
                    <div class="card-footer text-muted text-end">
                        <p>${obtenerNombreMes(mes)} ${anio}</p>
                    </div>
                </div>
            `;

            card.innerHTML = cardContent;

            container.appendChild(card);

            diaActual.setDate(diaActual.getDate() + 1);
        }
    }


</script>
</body>
</html>
