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
    <title>Panel del Dentista - DentalCare</title>
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
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 800px;
            margin-top: 2rem;
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
                <a class="nav-link" id="logout-link" href="">
                    <i class="fas fa-door-closed"></i> Cerrar sesión
                </a>
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
        <h1><?php echo $mensajeBienvenida; ?></h1>
        <p>Este es tu panel principal donde puedes gestionar pacientes, citas y tratamientos.</p>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
