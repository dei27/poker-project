<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../controllers/controllerTorneos.php');
include('../controllers/controllerLugares.php');
include('../controllers/controllerRegistros.php');
include('../controllers/controllerJugadores.php');

$torneosData = getAll();
$torneos = json_decode($torneosData, true);

$lugaresData = getAllPlaces();
$lugares = json_decode($lugaresData, true);
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
            echo 
            '<nav class="navbar sticky-top">
                <a href="./dashboard.php" class="text-decoration-none text-dark navbar-brand"><i class="bi bi-arrow-left-circle-fill text-white fs-3 px-3"></i></a>
                <div class="navbar-brand">
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
            <h4 class="card-header mb-3 py-3">Torneos actuales</h4>
            <?php
            if(isset($_SESSION["user"])){
            echo '<h5 class="card-text">
                    <a href="#" class="text-decoration-none text-danger" data-bs-toggle="modal" data-bs-target="#addTournament">
                    <img src="../assets/images/poker-chip.png" alt="Crear torneo" class="img-fluid"> Crear torneo
                    </a>
                </h5>';
            }
            ?>
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
                                echo '<th>Agregar</th><th>Acciones</th>';
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
                            <td>
                            <?php
                                $horaFormateada = date('H:i', strtotime($torneo['hora'])); 
                                echo  $horaFormateada; ?>
                            </td>
                            <td><?php echo $torneo['entrada']; ?></td>
                            <td><?php echo $torneo['recompra']; ?></td>
                            <td><?php echo $torneo['add_on']; ?></td>

                            <?php 
                                $registroData = countPlayers($torneo['id']);
                                $registro = json_decode($registroData, true);
                                $jugadoresData = getAllRecordsArray($torneo['id']);
                                $jugadores = json_decode($jugadoresData, true);

                                $jugadoresDataAll = getAllPlayersNotInRecords($torneo['id']);
                                $jugadoresAll = json_decode($jugadoresDataAll, true);
                            ?>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#jugadoresModal<?php echo $torneo['id']?>" class="text-decoration-none text-white">
                                    <?php echo isset($registro) ? $registro : 'Sin jugadores'; ?>
                                </a>
                                <div class="modal fade" id="jugadoresModal<?php echo $torneo['id']?>" tabindex="-1" aria-labelledby="jugadoresModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark">
                                                <h5 class="modal-title">Jugadores Inscritos</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-dark">
                                                <h2 class="fs-5"><?php echo $torneo['nombre']; ?></h2>
                                                <table class="table table-dark table-striped table-hover jugadoresTorneo">
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
                                                        echo "<td>{$jugador['nickname']}</td>";

                                                        if(isset($_SESSION["user"])){
                                                            echo '<td>
                                                                    <a href="../controllers/controllerRegistros.php?action=delete&id=' . $jugador['jugador_id'] . '&idTournament='.$torneo['id'].'" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este jugador del torneo?\')">
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
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#jugadoresModalAgregar' . $torneo['id'] . '" class="text-decoration-none text-white">
                                            <i class="bi bi-person-plus-fill"></i>
                                        </a>
                                        <div class="modal fade" id="jugadoresModalAgregar' . $torneo['id'] . '" tabindex="-1" aria-labelledby="jugadoresModalAgregarLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark">
                                                        <h5 class="modal-title">Inscribir jugador</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-dark">
                                                        <h2 class="fs-5">' . $torneo['nombre'] . '</h2>
                                                        <table class="table table-dark table-striped table-hover jugadoresTorneo">
                                                            <thead class="table-warning">
                                                                <tr>
                                                                    <th>Nickname</th>';
                                    if (isset($_SESSION["user"])) {
                                        echo '<th>Acciones</th>';
                                    }
                                    echo '</tr>
                                                            </thead>
                                                            <tbody>';
                                    foreach ($jugadoresAll as $jugador) {
                                        echo "<tr>";
                                        echo "<td>{$jugador['nickname']}</td>";

                                        if (isset($_SESSION["user"])) {
                                            echo '<td>
                                                    <a href="../controllers/controllerRegistros.php?action=add&idPlayer=' . $jugador['id'] . '&idTournament=' . $torneo['id'] . '" onclick="return confirm(\'¿Estás seguro deseas agregar a este jugador del torneo?\')">
                                                        <i class="bi bi-plus-circle-fill text-white"></i>
                                                    </a>
                                                </td>';
                                        }

                                        echo "</tr>";
                                    }
                                    echo '</tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>';
                                }
                                ?>

                            <?php 
                                if (isset($_SESSION["user"])) {
                                    $horaFormateada = date('H:i', strtotime($torneo['hora']));
                                    echo '<td>
                                        <a href="../controllers/controllerTorneos.php?action=delete&id=' . $torneo['id'] . '" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este torneo?\')" class="text-decoration-none text-white mx-3">
                                            <i class="bi bi-trash-fill text-white"></i>
                                        </a>
                                        <a href="#" class="text-decoration-none text-white" data-bs-toggle="modal" data-bs-target="#editModal' . $torneo['id'] . '">
                                            <i class="bi bi-pencil-fill text-white"></i>
                                        </a>
                                    </td>';

                                    echo '
                                    <div class="modal fade" id="editModal' . $torneo['id'] . '" tabindex="-1" aria-labelledby="editModalLabel' . $torneo['id'] . '" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header text-bg-dark">
                                                    <h5 class="modal-title" id="editModalLabel' . $torneo['id'] . '">Editar Torneo</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="../controllers/controllerTorneos.php" method="post" class="form-floating">
                                                    <input type="hidden" name="id" value="'.$torneo['id'].'">
                                                    <input type="hidden" name="action" value="edit">
                                                    <div class="form-group mb-3">
                                                        <label for="torneoNombre">Nombre</label>
                                                        <input class="form-control" id="torneoNombre" name="torneoNombre" placeholder="Torneo..." value="'.$torneo['nombre'].'">
                                                    </div>
                                                        
                                                    
                                                    <div class="form-group mb-3">
                                                        <label for="torneoFecha">Fecha</label>
                                                        <input type="date" class="form-control" id="torneoFecha" name="torneoFecha" placeholder="Fecha..." value="'.$torneo['fecha'].'">
                                                    </div>
                                                        

                                                    <div class="form-group mb-3">
                                                        <label for="torneoHora">Hora</label>
                                                        <input type="time" class="form-control" id="torneoHora" name="torneoHora" placeholder="Hora..." value="'.$horaFormateada.'" step="60">
                                                    </div>


                                                        

                                                    <div class="form-group mb-3">
                                                        <label for="torneoEntrada">Entrada</label>
                                                        <input type="number" class="form-control" id="torneoEntrada" name="torneoEntrada" placeholder="Entrada..." value="'.$torneo['entrada'].'">
                                                    </div>
                                                        

                                                    <div class="form-group mb-3">
                                                        <label for="torneoRecompra">Recompra</label>
                                                        <input type="number" class="form-control" id="torneoRecompra" name="torneoRecompra" placeholder="Recompra..." value="'.$torneo['recompra'].'">
                                                    </div>
                                                        

                                                    <div class="form-group mb-3">
                                                        <label for="torneoAddOn">Add On</label>
                                                        <input type="number" class="form-control" id="torneoAddOn" name="torneoAddOn" placeholder="Add-On..." value="'.$torneo['add_on'].'">
                                                    </div>
                                                        
                                                    
                                                    <div class="form-group mb-3 text-end">
                                                        <input type="submit" class="btn btn-primary" value="Guardar">
                                                    </div>

                                                    </form>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>';
                                }
                            ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <!-- modal agregar torneo -->
    <div class="modal fade" id="addTournament" tabindex="-1" aria-labelledby="addTournamentLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header text-bg-dark">
                <h5 class="modal-title" id="addTournament">Agregar Torneo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controllers/controllerTorneos.php" method="post" class="form-floating">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group mb-3">
                        <label for="torneoNombreAdd">Nombre</label>
                        <input class="form-control" id="torneoNombreAdd" name="torneoNombreAdd" placeholder="Torneo..." value="" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="lugarAdd" class="form-label">Lugar</label>
                        <select class="form-select" name="lugarAdd" id="lugarAdd">
                            <?php foreach ($lugares as $lugar): ?>
                                <option value="<?php echo $lugar['id']; ?>">
                                    <?php echo $lugar['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="torneoFechaAdd">Fecha</label>
                                <input type="date" class="form-control" id="torneoFechaAdd" name="torneoFechaAdd" placeholder="Fecha..." value="" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="torneoHoraAdd">Hora</label>
                                <input type="time" class="form-control" id="torneoHoraAdd" name="torneoHoraAdd" placeholder="Hora..." value="" step="60" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="torneoEntradaAdd">Entrada</label>
                                <input type="number" class="form-control" id="torneoEntradaAdd" name="torneoEntradaAdd" placeholder="Entrada..." value="" required min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="torneoRecompraAdd">Recompra</label>
                                <input type="number" class="form-control" id="torneoRecompraAdd" name="torneoRecompraAdd" placeholder="Recompra..." value="" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="torneoAddOnAdd">Add On</label>
                                <input type="number" class="form-control" id="torneoAddOnAdd" name="torneoAddOnAdd" placeholder="Add-On..." value="" min="0">
                            </div>
                        </div>
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

<?php
    if (isset($_GET['updatedTournament']) && $_GET['updatedTournament'] == 1) {
        // Mostrar la alerta de eliminación exitosa con SweetAlert2
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: "success",
                title: "Actualizado con éxito",
                timer: 2500,
                text: "Torneo actualizado con éxito.",
                showConfirmButton: false
            });
        </script>
        ';
    }elseif(isset($_GET['updatedTournament']) && $_GET['updatedTournament'] == 0){
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: "error",
                title: "Notificación",
                timer: 2500,
                text: "El torneo no se ha podido actualizar",
                showConfirmButton: false
            });
        </script>
        ';
    }
?>

<?php
    if (isset($_GET['addPlayer']) && $_GET['addPlayer'] == 1) {
        // Mostrar la alerta de eliminación exitosa con SweetAlert2
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: "success",
                title: "Agregado con éxito",
                timer: 2500,
                text: "El jugador se agregó al torneo.",
                showConfirmButton: false
            });
        </script>
        ';
    }elseif(isset($_GET['addPlayer']) && $_GET['addPlayer'] == 0){
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: "error",
                title: "Notificación",
                timer: 2500,
                text: "No se ha podido agregar el jugador al torneo",
                showConfirmButton: false
            });
        </script>
        ';
    }
?>

<?php
    if (isset($_GET['insertedTournament']) && $_GET['insertedTournament'] == 1) {
        // Mostrar la alerta de eliminación exitosa con SweetAlert2
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: "success",
                title: "Agregado con éxito",
                timer: 2500,
                text: "Torneo creado.",
                showConfirmButton: false
            });
        </script>
        ';
    }elseif(isset($_GET['insertedTournament']) && $_GET['insertedTournament'] == 0){
        echo '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: "error",
                title: "Notificación",
                timer: 2500,
                text: "No se pudo crear el torneo, intente de nuevo",
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
        $('.jugadoresTorneo').DataTable({
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
