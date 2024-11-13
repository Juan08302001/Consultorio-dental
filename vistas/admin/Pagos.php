<?php
session_start();

// Comprobar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['username'];
$mensajeBienvenida = "¡Bienvenido, " . htmlspecialchars($id) . "!";

// Incluir el archivo de conexión a la base de datos
include("C:\Apache24\htdocs\ConsultorioDental\config\config.php");

// Consultar los pacientes desde la base de datos
$queryPacientes = "SELECT id, nombre, apellido FROM pacientes";
$resultPacientes = mysqli_query($conn, $queryPacientes);

// Verificar si la consulta se ejecutó correctamente
if (!$resultPacientes) {
    die("Error en la consulta de pacientes: " . mysqli_error($conn));
}

// Generar las opciones del menú desplegable para pacientes
$optionsPacientes = "";
while ($row = mysqli_fetch_assoc($resultPacientes)) {
    $id = htmlspecialchars($row['id']);
    $nombre = htmlspecialchars($row['nombre']);
    $apellidos = htmlspecialchars($row['apellido']);
    $optionsPacientes .= "<option value=\"$id\">$nombre $apellidos</option>";
}

// Consultar las citas desde la base de datos
$queryCitas = "SELECT id, paciente_id, fecha_cita FROM citas";
$resultCitas = mysqli_query($conn, $queryCitas);

// Verificar si la consulta se ejecutó correctamente
if (!$resultCitas) {
    die("Error en la consulta de citas: " . mysqli_error($conn));
}

// Preparar los datos de las citas por paciente en formato JSON
$citasPorPaciente = [];
while ($row = mysqli_fetch_assoc($resultCitas)) {
    $paciente_id = $row['paciente_id'];
    $cita = [
        'id' => $row['id'],
        'fecha_cita' => $row['fecha_cita']
    ];

    if (!isset($citasPorPaciente[$paciente_id])) {
        $citasPorPaciente[$paciente_id] = [];
    }
    $citasPorPaciente[$paciente_id][] = $cita;
}

// Convertir los datos de las citas a JSON para usar en JavaScript
$citasPorPacienteJson = json_encode($citasPorPaciente);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pagos - DentalCare</title>
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
                <!-- Menú de navegación -->
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
        <!-- Sección para Añadir Pago -->
        <div class="card mb-4">
            <div class="card-header">
                Añadir Pago
            </div>
            <div class="card-body">
                <form action="/ConsultorioDental/controlador/ControladorPagos.php" method="POST">
                <div class="mb-3">
                        <label for="paciente_id" class="form-label">Paciente</label>
                        <select class="form-control" id="paciente_id" name="paciente_id" required>
                            <!-- Opciones generadas dinámicamente -->
                            <option value="" disabled selected>Seleccione un paciente</option>
                            <?php echo $optionsPacientes; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cita_id" class="form-label">Cita</label>
                        <select class="form-control" id="cita_id" name="cita_id" required>
                            <!-- Opciones generadas dinámicamente -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="monto">Monto</label>
                        <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_pago">Fecha de Pago</label>
                        <input type="datetime-local" class="form-control" id="fecha_pago" name="fecha_pago" required>
                    </div>
                    <div class="form-group">
                        <label for="metodo">Método de Pago</label>
                        <input type="text" class="form-control" id="metodo" name="metodo" required>
                    </div>
                    <div class="form-group">
    <label for="edit_estado">Estado</label><br>
    <input type="radio" id="estado_completado" name="edit_estado" value="Completado" required>
    <label for="estado_completado">Completado</label><br>
    <input type="radio" id="estado_pendiente" name="edit_estado" value="Pendiente">
    <label for="estado_pendiente">Pendiente</label><br>
    <input type="radio" id="estado_cancelado" name="edit_estado" value="Cancelado">
    <label for="estado_cancelado">Cancelado</label><br>
    <input type="radio" id="estado_reembolsado" name="edit_estado" value="Reembolsado">
    <label for="estado_reembolsado">Reembolsado</label>
</div>

                    <button type="submit" class="btn btn-custom">Añadir Pago</button>
                </form>
            </div>
        </div>

        <!-- Sección para Listar Pagos -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Listar Pagos</span>
                <a href="/ConsultorioDental/pdf/Reporte de Pagos.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-file-pdf"></i> Generar Reporte en PDF
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered responsive">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Paciente</th>
                                <th>Cita</th>
                                <th>Monto</th>
                                <th>Fecha de Pago</th>
                                <th>Método de Pago</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Incluir el archivo de configuración
                            include __DIR__ . '/../../config/config.php';

                            // Consultar los pagos
                            $sql = "SELECT
            p.id ,
            CONCAT(pa.nombre, ' ', pa.apellido) AS 'Paciente',
            c.fecha_cita AS 'Fecha_Cita',
            p.monto,
            p.fecha_pago,
            p.metodo,
            p.estado
        FROM pagos p
        JOIN pacientes pa ON p.paciente_id = pa.id
        JOIN citas c ON p.cita_id = c.id";
                            $result = $conn->query($sql);

                            // Mostrar los pagos en la tabla
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . $row["id"] . "</td>
                                        <td>" . $row["Paciente"] . "</td>
                                        <td>" . $row["Fecha_Cita"] . "</td>
                                        <td>" . $row["monto"] . "</td>
                                        <td>" . $row["fecha_pago"] . "</td>
                                        <td>" . $row["metodo"] . "</td>
                                        <td>" . $row["estado"] . "</td>
                                        <td>
                                            <button class='btn btn-warning btn-sm btn-edit' 
                                                data-id='" . $row["id"] . "' 
                                                data-paciente='" . $row["Paciente"] . "' 
                                                data-cita='" . $row["Fecha_Cita"] . "' 
                                                data-monto='" . $row["monto"] . "' 
                                                data-fecha='" . $row["fecha_pago"] . "' 
                                                data-metodo='" . $row["metodo"] . "' 
                                                data-estado='" . $row["estado"] . "' 
                                                data-toggle='modal' 
                                                data-target='#editPaymentModal'>
                                                <i class='fas fa-edit'></i> Editar
                                            </button>

                                            <form action='/ConsultorioDental/controlador/ControladorPagos.php' method='POST' onsubmit='return confirm(\"¿Seguro que deseas eliminar este pago?\");'>
                                                <input type='hidden' name='delete_payment_id' value='" . $row["id"] . "'>
                                                <button type='submit' class='btn btn-danger btn-sm'>
                                                    <i class='fas fa-trash-alt'></i> Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>No se encontraron pagos.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal para Editar Pago -->
        <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPaymentModalLabel">Editar Pago</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editPaymentForm" action="/ConsultorioDental/controlador/ControladorPagos.php" method="POST">
                            <input type="hidden" id="edit_payment_id" name="edit_payment_id">

                            <div class="form-group">
                                <label for="edit_monto">Monto</label>
                                <input type="number" step="0.01" class="form-control" id="edit_monto" name="edit_monto" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_fecha_pago">Fecha de Pago</label>
                                <input type="datetime-local" class="form-control" id="edit_fecha_pago" name="edit_fecha_pago" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_metodo">Método de Pago</label>
                                <input type="text" class="form-control" id="edit_metodo" name="edit_metodo" required>
                            </div>
                            <div class="form-group">
    <label for="edit_estado">Estado</label>
    <select class="form-control" id="edit_estado" name="edit_estado" required>
        <option value="Completado">Completado</option>
        <option value="Pendiente">Pendiente</option>
        <option value="Cancelado">Cancelado</option>
        <option value="Reembolsado">Reembolsado</option>
    </select>
</div>

                            <button type="submit" class="btn btn-custom">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Llenar el formulario de edición con los datos del pago seleccionado
        $(document).on('click', '.btn-edit', function() {
            var paymentId = $(this).data('id');
            var pacienteId = $(this).data('paciente');
            var citaId = $(this).data('cita');
            var monto = $(this).data('monto');
            var fechaPago = $(this).data('fecha');
            var metodo = $(this).data('metodo');
            var estado = $(this).data('estado');

            $('#edit_payment_id').val(paymentId);
            $('#edit_paciente_id').val(pacienteId);
            $('#edit_cita_id').val(citaId);
            $('#edit_monto').val(monto);
            $('#edit_fecha_pago').val(fechaPago);
            $('#edit_metodo').val(metodo);
            $('#edit_estado').val(estado);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const citasPorPaciente = <?php echo $citasPorPacienteJson; ?>;
            const pacienteSelect = document.getElementById('paciente_id');
            const citaSelect = document.getElementById('cita_id');

            pacienteSelect.addEventListener('change', function() {
                const pacienteId = this.value;
                citaSelect.innerHTML = '<option value="" disabled selected>Seleccione una cita</option>';
                if (citasPorPaciente[pacienteId]) {
                    citasPorPaciente[pacienteId].forEach(cita => {
                        const option = document.createElement('option');
                        option.value = cita.id;
                        option.textContent = cita.fecha_cita;
                        citaSelect.appendChild(option);
                    });
                }
            });
        });
    </script>
</body>
</html>
