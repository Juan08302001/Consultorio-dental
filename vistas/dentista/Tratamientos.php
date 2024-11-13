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
    <title>Historial de Tratamientos - DentalCare</title>
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
    <!-- Sección para Historial de Tratamientos -->
    <div class="card mb-4">
        <div class="card-header">
            Historial de Tratamientos
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Fecha de Cita</th>
                            <th>Medicamento</th>
                            <th>Dosis</th>
                            <th>Instrucciones</th>
                            <th>Pago</th>
                            <th>Fecha de Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Incluir el archivo de configuración
                        include __DIR__ . '/../../config/config.php';

                        // Consultar los tratamientos
                        $sql = "SELECT 
                                    citas.id AS cita_id,
                                    CONCAT(pacientes.nombre, ' ', pacientes.apellido) AS paciente,
                                    citas.fecha_cita,
                                    recetas.medicamento,
                                    recetas.dosis,
                                    recetas.instrucciones,
                                    pagos.monto,
                                    pagos.fecha_pago
                                FROM citas
                                LEFT JOIN pacientes ON citas.paciente_id = pacientes.id
                                LEFT JOIN recetas ON citas.id = recetas.cita_id
                                LEFT JOIN pagos ON citas.id = pagos.cita_id";

                        $result = $conn->query($sql);

                        // Verificar si la consulta se ejecutó correctamente
                        if ($result === false) {
                            echo "<tr><td colspan='8'>Error al ejecutar la consulta: " . $conn->error . "</td></tr>";
                        } else {
                            // Mostrar los tratamientos en la tabla
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . htmlspecialchars($row["cita_id"]) . "</td>
                                        <td>" . htmlspecialchars($row["paciente"]) . "</td>
                                        <td>" . htmlspecialchars($row["fecha_cita"]) . "</td>
                                        <td>" . htmlspecialchars($row["medicamento"]) . "</td>
                                        <td>" . htmlspecialchars($row["dosis"]) . "</td>
                                        <td>" . htmlspecialchars($row["instrucciones"]) . "</td>
                                        <td>$" . number_format($row["monto"], 2) . "</td>
                                        <td>" . htmlspecialchars($row["fecha_pago"]) . "</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No hay tratamientos registrados.</td></tr>";
                            }
                        }

                        // Cerrar la conexión
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
