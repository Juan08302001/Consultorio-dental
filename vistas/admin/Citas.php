<?php
session_start();
$id = $_SESSION['username'];
$mensajeBienvenida = "¡Bienvenido, " . $id . "!";
if(!isset($id)){
    header("location: index.php");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas - DentalCare</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .navbar {
            background-color: #00796b; /* Azul oscuro */
        }

        .navbar-brand img {
            width: 40px; /* Imagen más pequeña */
            height: 40px;
        }

        .navbar-nav .nav-link {
            color: #ffffff !important;
        }

        .navbar-nav .nav-link:hover {
            color: #b2dfdb !important;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e0f7fa; /* Fondo azul claro */
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }

        .card-header {
            background-color: #00796b;
            color: #ffffff;
            font-size: 1.25rem;
            text-align: center;
            padding: 1rem;
        }

        .btn-custom {
            background-color: #00796b;
            color: #ffffff;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
            transition: background-color 0.2s;
        }

        .btn-custom:hover {
            background-color: #004d40;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="./index.php">
            <img src="https://cdn-icons-png.flaticon.com/512/1077/1077012.png" alt="Dental Icon"> DentalCare
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="./gestionUsu.php"><i class="fas fa-users"></i> Gestión de Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./GestionPacientes.php"><i class="fas fa-user-injured"></i> Gestión de Pacientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Citas.php"><i class="fas fa-calendar-day"></i> Gestión de Citas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Recetas.php"><i class="fas fa-prescription"></i> Gestión de Recetas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Pagos.php"><i class="fas fa-credit-card"></i> Gestión de Pagos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Analisis.php"><i class="fas fa-chart-line"></i> Reportes y Análisis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Soporte.php"><i class="fas fa-life-ring"></i> Soporte y Ayuda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="logout-link" href=""><i class="fas fa-door-closed"></i> Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <script>
        document.getElementById('logout-link').addEventListener('click', function(event) {
            event.preventDefault();
            var confirmLogout = confirm('¿Estás seguro de que quieres cerrar sesión?');
            if (confirmLogout) {
                window.location.href = '/ConsultorioDental/controlador/cerrarSesion.php';
            }
        });
    </script>


    <div class="container">
        <!-- Sección para Añadir Cita -->
        <div class="card mb-4">
            <div class="card-header">
                Añadir Cita
            </div>
            <div class="card-body">
                <form action="/ConsultorioDental/controlador/ControladorCitas.php" method="POST">
                    <div class="form-group">
                        <label for="paciente_id">Paciente</label>
                        <select class="form-control" id="paciente_id" name="paciente_id" required>
                            <?php
                            // Obtener lista de pacientes
                            include __DIR__ . '/../../config/config.php';
                            $sql = "SELECT id, nombre FROM pacientes";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha_cita">Fecha y Hora</label>
                        <input type="datetime-local" class="form-control" id="fecha_cita" name="fecha_cita" required>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notas">Notas</label>
                        <textarea class="form-control" id="notas" name="notas"></textarea>
                    </div>
                    <button type="submit" class="btn btn-custom">Añadir Cita</button>
                </form>
            </div>
        </div>

        <!-- Sección para Listar Citas -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Listar Citas</span>
                <a href="/ConsultorioDental/pdf/Reporte de citas.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-file-pdf"></i> Generar Reporte en PDF
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!-- Tabla de Citas -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Paciente</th>
                                <th>Fecha y Hora</th>
                                <th>Estado</th>
                                <th>Notas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Incluir el archivo de configuración
                            include __DIR__ . '/../../config/config.php';

                            // Consultar las citas
                            $sql = "SELECT citas.id, pacientes.nombre AS paciente, citas.fecha_cita, citas.estado, citas.notas 
                                    FROM citas 
                                    JOIN pacientes ON citas.paciente_id = pacientes.id";
                            $result = $conn->query($sql);

                            // Mostrar las citas en la tabla
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . $row["id"] . "</td>
                                        <td>" . $row["paciente"] . "</td>
                                        <td>" . $row["fecha_cita"] . "</td>
                                        <td>" . $row["estado"] . "</td>
                                        <td>" . $row["notas"] . "</td>
                                        <td>
                                            <button class='btn btn-warning btn-sm btn-edit' 
                                                data-id='" . $row["id"] . "' 
                                                data-paciente='" . $row["paciente"] . "' 
                                                data-fecha='" . $row["fecha_cita"] . "' 
                                                data-estado='" . $row["estado"] . "' 
                                                data-notas='" . $row["notas"] . "' 
                                                data-toggle='modal' 
                                                data-target='#editCitaModal'>
                                                <i class='fas fa-edit'></i> Editar
                                            </button>
                                            <form action='/ConsultorioDental/controlador/ControladorCitas.php' method='POST' class='d-inline' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este paciente?\");' style='display:inline;'>
    <input type='hidden' name='deleteId' value='" . $row["id"] . "'>
    <button type='submit' class='btn btn-danger btn-sm'>
        <i class='fas fa-trash'></i> Eliminar
    </button>
</form>

                                        </td>
                                    </tr>";
                                }
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Cita -->
    <div class="modal fade" id="editCitaModal" tabindex="-1" role="dialog" aria-labelledby="editCitaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCitaModalLabel">Editar Cita</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCitaForm" action="/ConsultorioDental/controlador/ControladorCitas.php" method="POST">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            
                        </div>
                        <div class="form-group">
                            <label for="edit_fecha_cita">Fecha y Hora</label>
                            <input type="datetime-local" class="form-control" id="edit_fecha_cita" name="fecha_cita" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_estado">Estado</label>
                            <select class="form-control" id="edit_estado" name="estado" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_notas">Notas</label>
                            <textarea class="form-control" id="edit_notas" name="notas"></textarea>
                        </div>
                        <button type="submit" class="btn btn-custom">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-edit').on('click', function() {
                var id = $(this).data('id');
                var paciente = $(this).data('paciente');
                var fecha = $(this).data('fecha');
                var estado = $(this).data('estado');
                var notas = $(this).data('notas');
                
                $('#edit_id').val(id);
                $('#edit_paciente_id').val(paciente);
                $('#edit_fecha_cita').val(fecha);
                $('#edit_estado').val(estado);
                $('#edit_notas').val(notas);
            });
        });
    </script>
</body>
</html>
