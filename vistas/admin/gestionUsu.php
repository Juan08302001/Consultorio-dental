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
    <title>Gestión de Usuarios - DentalCare</title>
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
                    <a class="nav-link" href="" id="logout-link"><i class="fas fa-door-closed"></i> Cerrar sesion</a>
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
        <!-- Sección para Añadir Usuario -->
        <div class="card mb-4">
            <div class="card-header">
                Añadir Usuario
            </div>
            <div class="card-body">
                <form action="/ConsultorioDental/controlador/ControladorUsuarios.php" method="POST">
                    <div class="form-group">
                        <label for="nombre_usuario">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <select class="form-control" id="rol" name="rol" required>
                            <option value="admin">Administrador</option>
                            <option value="dentista">Dentista</option>
                            <option value="asistente">Asistente</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-custom">Añadir Usuario</button>
                </form>
            </div>
        </div>

 <!-- Sección para Listar Usuarios -->
 <div class="card mb-4">
 <div class="card-header d-flex justify-content-between align-items-center">
        <span>Listar Usuarios</span>
        <a href="/ConsultorioDental/pdf/Reporte de usuarios.php" class="btn btn-primary btn-sm">
            <i class="fas fa-file-pdf"></i> Generar Reporte en PDF
        </a>
    </div>
            <div class="card-body">
        <div class="table-responsive">
           
                <!-- Tabla de Usuarios -->
                <table class="table table-bordered responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Incluir el archivo de configuración
                        include __DIR__ . '/../../config/config.php';

                        

                        // Consultar los usuarios
                        $sql = "SELECT id, nombre_usuario, email, rol FROM usuarios";
                        $result = $conn->query($sql);

                        // Mostrar los usuarios en la tabla
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
    <td>" . $row["id"] . "</td>
    <td>" . $row["nombre_usuario"] . "</td>
    <td>" . $row["email"] . "</td>
    <td>" . $row["rol"] . "</td>
    <td>
        <button class='btn btn-warning btn-sm btn-edit' 
            data-id='" . $row["id"] . "' 
            data-email='" . $row["email"] . "' 
            data-rol='" . $row["rol"] . "' 
            data-toggle='modal' 
            data-target='#editUserModal'>
            <i class='fas fa-edit'></i> Editar
        </button>

        <form action='/ConsultorioDental/controlador/ControladorUsuarios.php' method='POST' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este usuario?\");' style='display:inline;'>
            <input type='hidden' name='deleteId' value='" . $row["id"] . "'>
            <button type='submit' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i> Eliminar</button>
        </form>
    </td>
</tr>";

                            }
                        } else {
                            echo "<tr><td colspan='5'>No se encontraron usuarios.</td></tr>";
                        }

                        // Cerrar la conexión
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
                    </div>
        </div>
<!-- Modal para Editar Usuario -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editUserForm" action="/ConsultorioDental/controlador/ControladorUsuarios.php" method="POST">
          <input type="hidden" id="userId" name="userId">
          <div class="form-group">
            <label for="userEmail">Email</label>
            <input type="email" class="form-control" id="userEmail" name="userEmail" required>
          </div>
          <div class="form-group">
            <label for="userPassword">Contraseña</label>
            <input type="password" class="form-control" id="userPassword" name="userPassword">
          </div>
          <div class="form-group">
            <label for="userRole">Rol</label>
            <select class="form-control" id="userRole" name="userRole" required>
              <option value="admin">Admin</option>
              <option value="dentista">Dentista</option>
              <option value="asistente">Asistente</option>
            </select>
          </div>
          <button type="submit" class="btn btn-custom">Guardar Cambios</button>
        </form>
      </div>
    </div>
  </div>
</div>


    </div>


    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Manejar clic en el botón de editar
    document.querySelectorAll('.btn-edit').forEach(function (button) {
        button.addEventListener('click', function () {
            // Obtener datos del usuario desde los atributos del botón
            var userId = this.getAttribute('data-id');
            var userEmail = this.getAttribute('data-email');
            var userRole = this.getAttribute('data-rol');

            // Mostrar datos en el modal
            document.getElementById('userId').value = userId;
            document.getElementById('userEmail').value = userEmail;
            document.getElementById('userRole').value = userRole;
        });
    });
});
</script>

    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
