<?php
session_start();
$id = $_SESSION['username'];
$mensajeBienvenida = "¡Bienvenido, " . $id . "!";
if (!isset($id)) {
    header("location: index.php");
}

include __DIR__ . '/../../config/config.php';
 // Incluir el archivo de configuración

// Consultas para obtener datos
$totalPacientes = $conn->query("SELECT COUNT(*) AS total FROM pacientes")->fetch_assoc()['total'];
$totalCitas = $conn->query("SELECT COUNT(*) AS total FROM citas")->fetch_assoc()['total'];
$totalTratamientos = $conn->query("SELECT COUNT(*) AS total FROM recetas")->fetch_assoc()['total'];

// Detalle de actividad reciente
$actividadReciente = $conn->query("
    SELECT c.fecha_cita, p.nombre AS paciente, r.medicamento AS tratamiento, p2.monto AS costo
    FROM citas c
    JOIN pacientes p ON c.paciente_id = p.id
    LEFT JOIN recetas r ON c.id = r.cita_id
    LEFT JOIN pagos p2 ON c.id = p2.cita_id
    ORDER BY c.fecha_cita DESC
    LIMIT 10
");

// Cerrar conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes de Actividad - DentalCare</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e0f7fa; /* Fondo azul claro */
        }
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
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 900px;
            margin-top: 2rem;
        }

        .card {
            margin-bottom: 1rem;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #00796b;
            color: #ffffff;
            font-size: 18px;
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            background-color: #e0f7fa;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
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
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="./GestionPacientes.php">
                    <i class="fas fa-user-injured"></i> Gestión de Pacientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./Citas.php">
                    <i class="fas fa-calendar-day"></i> Gestión de Citas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./Recetas.php">
                    <i class="fas fa-prescription"></i> Gestión de Recetas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./Tratamientos.php">
                    <i class="fas fa-tooth"></i> Historial de Tratamientos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./Notificaciones.php">
                    <i class="fas fa-bell"></i> Notificaciones
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./Reportes.php">
                    <i class="fas fa-chart-line"></i> Informes de Actividad
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="logout-link" href="#">
                    <i class="fas fa-door-closed"></i> Cerrar sesión
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
   <!-- <h1><?php echo $mensajeBienvenida; ?></h1>
    <p>Aquí puedes ver un resumen de la actividad del consultorio.</p>
    -->
    <!-- Card for Activity Reports -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-chart-bar"></i> Resumen de Actividad
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h5>Total de Pacientes Atendidos</h5>
                    <p><strong><?php echo $totalPacientes; ?></strong></p>
                </div>
                <div class="col-md-4">
                    <h5>Total de Citas Programadas</h5>
                    <p><strong><?php echo $totalCitas; ?></strong></p>
                </div>
                <div class="col-md-4">
                    <h5>Tratamientos Realizados</h5>
                    <p><strong><?php echo $totalTratamientos; ?></strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table for Detailed Reports -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-list"></i> Detalle de Actividad Reciente
        </div>
        <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha de Cita</th>
                        <th>Paciente</th>
                        <th>Tratamiento</th>
                        <th>Costo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $actividadReciente->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_cita'])); ?></td>
                            <td><?php echo htmlspecialchars($row['paciente']); ?></td>
                            <td><?php echo htmlspecialchars($row['tratamiento']); ?></td>
                            <td><?php echo number_format($row['costo'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
                    </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.getElementById('logout-link').addEventListener('click', function(event) {
        event.preventDefault();
        var confirmLogout = confirm('¿Estás seguro de que quieres cerrar sesión?');
        if (confirmLogout) {
            window.location.href = '/ConsultorioDental/controlador/cerrarSesion.php';
        }
    });
</script>
</body>
</html>
