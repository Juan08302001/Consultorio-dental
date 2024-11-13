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
    <title>Reportes y Análisis - DentalCare</title>
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
        <!-- Sección para Reportes y Análisis -->
        <div class="card mb-4">
            <div class="card-header">
                Reportes y Análisis
            </div>
            <div class="card-body">
                <p>Generar reportes y análisis de datos.</p>
                <div class="mb-3">
                    <a href="/ConsultorioDental/pdf/Reporte completo.php" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Generar Reporte Completo en PDF
                    </a>
                </div>
                <div class="mb-3">
                    <a href="/ConsultorioDental/pdf/Reporte diario.php" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Generar Reporte Diario en PDF
                    </a>
                </div>
                <div class="mb-3">
                    <a href="/ConsultorioDental/pdf/Reporte mensual.php" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Generar Reporte Mensual en PDF
                    </a>
                </div>
                <div class="mb-3">
                    <a href="/ConsultorioDental/pdf/Reporte anual.php" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Generar Reporte Anual en PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
