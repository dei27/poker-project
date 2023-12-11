<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../controllers/controllerTorneos.php');
include('../controllers/controllerLugares.php');
include('../controllers/controllerRegistros.php');

$torneosData = getAll();
$torneos = json_decode($torneosData, true);
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Admin</title>
</head>
<body>
    <header>
    <?php 
        if(isset($_SESSION["user"])){
            echo '<nav class="navbar sticky-top">
            <div class="container-fluid justify-content-end">
                <form action="../controllers/controller.php" method="post">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit navbar-brand" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </nav>';
        }else{
            include('nav.php');
        }
    ?>
    </header>

    <div class="container-fluid mt-5 vh-100 p-5">
        <div class="table-responsive card p-3">
            <h4 class="card-header mb-3">Torneos actuales</h4>
            <table id="example" class="table table-dark table-striped table-hover">
                <thead class="table-warning">
                    <tr>
                        <th>Torneo</th>
                        <th>Lugar</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Entrada</th>
                        <th>Recompra</th>
                        <th>Add-On</th>
                        <th>Jugadores</th>
                        <?php 
                            if(isset($_SESSION["user"])){
                                echo '<th>Acciones</th>';
                            }
                        ?>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($torneos as $torneo):?>
                        <?php 
                            $lugarData = getPlaceById($torneo['lugar']);
                            $lugarArray = json_decode($lugarData, true);

                            if (is_array($lugarArray) && isset($lugarArray['nombre'])) {
                                $lugarNombre = $lugarArray['nombre'];
                            } else {
                                $lugarNombre = 'Sin lugar';
                            }
                        ?>
                        <tr>
                            <td><?php echo $torneo['nombre']; ?></td>
                            <td><?php echo $lugarNombre; ?></td>
                            <?php
                            $fecha = new DateTime($torneo['fecha']);
                            $fechaFormateada = $fecha->format('d-m-Y');
                            ?>

                            <td><?php echo $fechaFormateada; ?></td>
                            <td><?php echo $torneo['hora']; ?></td>
                            <td><?php echo $torneo['entrada']; ?></td>
                            <td><?php echo $torneo['recompra']; ?></td>
                            <td><?php echo $torneo['add_on']; ?></td>

                            <?php 
                                $registroData = countPlayers($torneo['id']);
                                $registro = json_decode($registroData, true);
                                $jugadoresData = getAllRecordsArray($torneo['id']);
                                $jugadores = json_decode($jugadoresData, true);
                            ?>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#jugadoresModal">
                                    <?php echo isset($registro) ? $registro : 'N/A'; ?>
                                </a>
                                <div class="modal fade" id="jugadoresModal" tabindex="-1" aria-labelledby="jugadoresModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark">
                                                <h5 class="modal-title">Jugadores Inscritos</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table id="jugadoresTorneo" class="table table-dark table-striped table-hover">
                                                    <thead class="table-warning">
                                                    <tr>
                                                        <th>Nickname</th>
                                                        <?php
                                                        if(isset($_SESSION["user"])){
                                                            echo "<th>Acciones</th>";
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tbody>
                                                    <?php
                                                    foreach ($jugadores as $jugador) {
                                                        echo "<tr>";
                                                        // Crea un td para el nickname
                                                        echo "<td>{$jugador['nickname']}</td>";

                                                        // Agrega las acciones solo si está configurada la sesión del usuario
                                                        if(isset($_SESSION["user"])){
                                                            echo '<td>
                                                                    <a href="../controllers/controllerRegistros.php?action=delete&id=' . $jugador['jugador_id'] . '" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este jugador del torneo?\')">
                                                                    <i class="bi bi-trash-fill text-white"></i>
                                                                    </a>
                                                                </td>';
                                                        }

                                                        echo "</tr>";
                                                    }
                                                    ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>


                            <?php 
                                if (isset($_SESSION["user"])) {
                                    echo '<td>
                                        <a href="../controllers/controllerTorneos.php?action=delete&id=' . $torneo['id'] . '" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este torneo?\')">
                                        <i class="bi bi-trash-fill text-white"></i>
                                        </a>
                                    </td>';
                                }
                            ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
        if (isset($_GET['delete']) && $_GET['delete'] == 1) {
            // Mostrar la alerta de eliminación exitosa con SweetAlert2
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Eliminado con éxito",
                    timer: 2500,
                    text: "El torneo ha sido eliminado con éxito.",
                    showConfirmButton: false
                });
            </script>
            ';
        }
    ?>

<?php
        if (isset($_GET['deletePlayer']) && $_GET['deletePlayer'] == 1) {
            // Mostrar la alerta de eliminación exitosa con SweetAlert2
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Eliminado con éxito",
                    timer: 2500,
                    text: "El jugador ha sido eliminado del torneo con éxito.",
                    showConfirmButton: false
                });
            </script>
            ';
        }
    ?>

</div>

<!-- Modal de jugadores -->


<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            lengthChange: false,
            pageLength: 10,
            info: false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        });
    });

    $(document).ready(function() {
        $('#jugadoresTorneo').DataTable({
            lengthChange: false,
            pageLength: 10,
            info: false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        });
    });

    
</script>
</body>
</html>
