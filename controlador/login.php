<?php
// login.php
include("../config/config.php");
session_start();

// Obtener datos del formulario
$nombre_usuario = mysqli_real_escape_string($conn, $_POST["username"]);
$clave = mysqli_real_escape_string($conn, $_POST["password"]);

// Buscar el usuario en la base de datos
$query = mysqli_query($conn, "SELECT * FROM usuarios WHERE nombre_usuario='$nombre_usuario'");
$nr = mysqli_num_rows($query);

if ($nr == 1) {
    $usuario = mysqli_fetch_assoc($query);

    // Verificar la contraseña
    if (hash('sha256', $clave) === $usuario['contrasena']) {
        // Contraseña correcta
        // Establecer las variables de sesión
        $_SESSION['username'] = $nombre_usuario;
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['rol'] = $usuario['rol'];

        // Redireccionar según el rol del usuario
        switch ($usuario['rol']) {
            case 'admin':
                header("Location: /ConsultorioDental/vistas/admin/index.php");
                break;
            case 'dentista':
                header("Location: /ConsultorioDental/vistas/dentista/index.php");
                break;
            case 'asistente':
                header("Location: /ConsultorioDental/vistas/asistente/index.php");
                break;
            default:
                echo '<script>
                    alert("Rol de usuario desconocido");
                    window.location.href = "../index.php";
                </script>';
                exit();
        }
    } else {
        // Contraseña incorrecta
        echo '<script>
            alert("Contraseña incorrecta");
            window.location.href = "../index.php";
        </script>';
    }
} else {
    // Usuario no encontrado
    echo '<script>
        alert("Usuario no encontrado");
        window.location.href = "../index.php";
    </script>';
}
?>