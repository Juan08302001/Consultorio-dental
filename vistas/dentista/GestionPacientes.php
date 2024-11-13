<?php
session_start();
$id = $_SESSION['username'];
$mensajeBienvenida = "¡Bienvenido, " . $id . "!";
if (!isset($id)) {
    header("location: index.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pacientes - DentalCare</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
     .navbar {
            background-color: #00796b;
        }

        .navbar-brand img {
            width: 40px;
            height: 40px;
        }

        .navbar-nav .nav-link {
            color: #ffffff !important;
        }

        .navbar-nav .nav-link:hover {
            color: #b2dfdb !important;
        }

        .navbar-nav {
            margin-left: auto;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e0f7fa;
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
                    <a class="nav-link" href="./GestionPacientes.php"><i class="fas fa-user-injured"></i> Gestión de Pacientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Citas.php"><i class="fas fa-calendar-day"></i> Gestión de Citas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Recetas.php"><i class="fas fa-prescription"></i> Gestión de Recetas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Tratamientos.php"><i class="fas fa-tooth"></i> Historial de Tratamientos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Notificaciones.php"><i class="fas fa-bell"></i> Notificaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Reportes.php"><i class="fas fa-chart-line"></i> Informes de Actividad</a>
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
        <!-- Sección para Añadir Paciente -->
        <div class="card mb-4">
            <div class="card-header">
                Añadir Paciente
            </div>
            <div class="card-body">
                <form action="/ConsultorioDental/controlador/Dentista/ControladorPacientes.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre del Paciente</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido del Paciente</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>
                    <div class="form-group">
                        <label for="historial_medico">Historial Médico</label>
                        <textarea class="form-control" id="historial_medico" name="historial_medico" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-custom">Añadir Paciente</button>
                </form>
            </div>
        </div>

        <!-- Sección para Listar Pacientes -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Listar Pacientes</span>
                <a href="/ConsultorioDental/pdf/Reporte de pacientes.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-file-pdf"></i> Generar Reporte en PDF
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered responsive">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Historial Médico</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Incluir el archivo de configuración
                            include __DIR__ . '/../../config/config.php';

                            // Consultar los pacientes
                            $sql = "SELECT id, nombre, apellido, fecha_nacimiento, email, telefono, direccion, historial_medico FROM pacientes";
                            $result = $conn->query($sql);

                            // Mostrar los pacientes en la tabla
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . $row["id"] . "</td>
                                        <td>" . $row["nombre"] . "</td>
                                        <td>" . $row["apellido"] . "</td>
                                        <td>" . $row["fecha_nacimiento"] . "</td>
                                        <td>" . $row["email"] . "</td>
                                        <td>" . $row["telefono"] . "</td>
                                        <td>" . $row["direccion"] . "</td>
                                        <td>" . $row["historial_medico"] . "</td>
                                        <td>
                                            <button class='btn btn-warning btn-sm btn-edit' 
                                                data-id='" . $row["id"] . "' 
                                                data-nombre='" . $row["nombre"] . "' 
                                                data-apellido='" . $row["apellido"] . "' 
                                                data-fecha='" . $row["fecha_nacimiento"] . "' 
                                                data-email='" . $row["email"] . "' 
                                                data-telefono='" . $row["telefono"] . "' 
                                                data-direccion='" . $row["direccion"] . "' 
                                                data-historial='" . $row["historial_medico"] . "' 
                                                data-toggle='modal' 
                                                data-target='#editPatientModal'>
                                                <i class='fas fa-edit'></i> Editar
                                            </button>

                                            <form action='/ConsultorioDental/controlador/Dentista/ControladorPacientes.php' method='POST' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este paciente?\");' style='display:inline;'>
                                                <input type='hidden' name='deleteId' value='" . $row["id"] . "'>
                                                <button type='submit' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i> Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>No se encontraron pacientes.</td></tr>";
                            }

                            // Cerrar la conexión
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal para Editar Paciente -->
        <div class="modal fade" id="editPatientModal" tabindex="-1" role="dialog" aria-labelledby="editPatientModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPatientModalLabel">Editar Paciente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/ConsultorioDental/controlador/Dentista/ControladorPacientes.php" method="POST">
                            <input type="hidden" id="patientId" name="id">
                            <div class="form-group">
                                <label for="patientNombre">Nombre</label>
                                <input type="text" class="form-control" id="patientNombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="patientApellido">Apellido</label>
                                <input type="text" class="form-control" id="patientApellido" name="apellido" required>
                            </div>
                            <div class="form-group">
                                <label for="patientFecha">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="patientFecha" name="fecha_nacimiento" required>
                            </div>
                            <div class="form-group">
                                <label for="patientEmail">Email</label>
                                <input type="email" class="form-control" id="patientEmail" name="email">
                            </div>
                            <div class="form-group">
                                <label for="patientTelefono">Teléfono</label>
                                <input type="tel" class="form-control" id="patientTelefono" name="telefono">
                            </div>
                            <div class="form-group">
                                <label for="patientDireccion">Dirección</label>
                                <input type="text" class="form-control" id="patientDireccion" name="direccion">
                            </div>
                            <div class="form-group">
                                <label for="patientHistorial">Historial Médico</label>
                                <textarea class="form-control" id="patientHistorial" name="historial_medico" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-custom">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).on("click", ".btn-edit", function () {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');
            var apellido = $(this).data('apellido');
            var fecha_nacimiento = $(this).data('fecha');
            var email = $(this).data('email');
            var telefono = $(this).data('telefono');
            var direccion = $(this).data('direccion');
            var historial_medico = $(this).data('historial');

            $("#patientId").val(id);
            $("#patientNombre").val(nombre);
            $("#patientApellido").val(apellido);
            $("#patientFecha").val(fecha_nacimiento);
            $("#patientEmail").val(email);
            $("#patientTelefono").val(telefono);
            $("#patientDireccion").val(direccion);
            $("#patientHistorial").val(historial_medico);
        });
    </script>

</body>
</html>
