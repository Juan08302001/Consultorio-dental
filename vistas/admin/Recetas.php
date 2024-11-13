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

// Consultar las recetas desde la base de datos
$queryRecetas = "SELECT r.id, p.nombre, p.apellido, r.medicamento, r.dosis, r.instrucciones, r.fecha_recetada
                 FROM recetas r
                 JOIN pacientes p ON r.paciente_id = p.id";
$resultRecetas = mysqli_query($conn, $queryRecetas);

// Verificar si la consulta se ejecutó correctamente
if (!$resultRecetas) {
    die("Error en la consulta de recetas: " . mysqli_error($conn));
}

// Cerrar la conexión
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Recetas - DentalCare</title>
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

        .form-select {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
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
                    <a class="nav-link" id="logout-link" href=""><i class="fas fa-door-closed"></i> Cerrar sesion</a>
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
        <!-- Sección para Añadir Receta -->
        <div class="card mb-4">
            <div class="card-header">
                Añadir Receta
            </div>
            <div class="card-body">
                <form action="/ConsultorioDental/controlador/ControladorRecetas.php" method="post">
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
                    <div class="mb-3">
                        <label for="medicamento" class="form-label">Medicamento</label>
                        <input type="text" class="form-control" id="medicamento" name="medicamento" required>
                    </div>
                    <div class="mb-3">
                        <label for="dosis" class="form-label">Dosis</label>
                        <input type="text" class="form-control" id="dosis" name="dosis" required>
                    </div>
                    <div class="mb-3">
                        <label for="instrucciones" class="form-label">Instrucciones</label>
                        <textarea class="form-control" id="instrucciones" name="instrucciones" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_recetada" class="form-label">Fecha Recetada</label>
                        <input type="datetime-local" class="form-control" id="fecha_recetada" name="fecha_recetada" required>
                    </div>
                    <button type="submit" class="btn btn-custom">Generar Receta</button>
                </form>
            </div>
        </div>

    <!-- Sección para Listar Recetas -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Listar Recetas</span>
        <a href="/ConsultorioDental/pdf/Reporte de recetas.php" class="btn btn-primary btn-sm">
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
                        <th>Medicamento</th>
                        <th>Dosis</th>
                        <th>Instrucciones</th>
                        <th>Fecha Recetada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($resultRecetas) > 0) : ?>
                        <?php while ($row = mysqli_fetch_assoc($resultRecetas)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($row['medicamento']); ?></td>
                                <td><?php echo htmlspecialchars($row['dosis']); ?></td>
                                <td><?php echo htmlspecialchars($row['instrucciones']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_recetada']); ?></td>
                                <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalModificarReceta" data-id="<?php echo htmlspecialchars($row['id']); ?>" data-medicamento="<?php echo htmlspecialchars($row['medicamento']); ?>" data-dosis="<?php echo htmlspecialchars($row['dosis']); ?>" data-instrucciones="<?php echo htmlspecialchars($row['instrucciones']); ?>" data-fecha_recetada="<?php echo htmlspecialchars($row['fecha_recetada']); ?>">
                                                <i class="fas fa-edit"></i> Modificar
                                            </button>
                                    <form action="/ConsultorioDental/controlador/ControladorRecetas.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta receta?');" style="display:inline;">
                                        <input type="hidden" name="deleteId" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay recetas para mostrar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


 <!-- Modal para Modificar Receta -->
 <div class="modal fade" id="modalModificarReceta" tabindex="-1" role="dialog" aria-labelledby="modalModificarRecetaLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalModificarRecetaLabel">Modificar Receta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formModificarReceta" action="/ConsultorioDental/controlador/ControladorRecetas.php" method="post">
                            <input type="hidden" id="receta_id" name="receta_id">
                            <div class="mb-3">
                                <label for="modal_medicamento" class="form-label">Medicamento</label>
                                <input type="text" class="form-control" id="modal_medicamento" name="medicamento" required>
                            </div>
                            <div class="mb-3">
                                <label for="modal_dosis" class="form-label">Dosis</label>
                                <input type="text" class="form-control" id="modal_dosis" name="dosis" required>
                            </div>
                            <div class="mb-3">
                                <label for="modal_instrucciones" class="form-label">Instrucciones</label>
                                <textarea class="form-control" id="modal_instrucciones" name="instrucciones" rows="3"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#modalModificarReceta').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            var medicamento = button.data('medicamento');
            var dosis = button.data('dosis');
            var instrucciones = button.data('instrucciones');
            var fecha_recetada = button.data('fecha_recetada');

            var modal = $(this);
            modal.find('#receta_id').val(id);
            modal.find('#modal_medicamento').val(medicamento);
            modal.find('#modal_dosis').val(dosis);
            modal.find('#modal_instrucciones').val(instrucciones);
            modal.find('#modal_fecha_recetada').val(fecha_recetada);
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
