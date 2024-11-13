<?php
// Incluir el archivo de configuración para la conexión a la base de datos
include __DIR__ . '/../config/config.php';


// Verificar la acción solicitada (insertar, modificar o eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['userId'])) {
        // Modificar usuario existente
        $id = $_POST['userId'];
        $email = $_POST['userEmail'];
        $contrasena = $_POST['userPassword'];
        $rol = $_POST['userRole'];

        // Si la contraseña no está vacía, actualízala; de lo contrario, mantén la existente
        if (!empty($contrasena)) {
            $contrasena_hashed = hash('sha256', $contrasena); // Hash de la contraseña utilizando SHA-256
            $sql = "UPDATE usuarios SET email=?, contrasena=?, rol=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $email, $contrasena_hashed, $rol, $id);
        } else {
            $sql = "UPDATE usuarios SET email=?, rol=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $email, $rol, $id);
        }

        if ($stmt->execute()) {
            echo '<script>
            alert("Usuario modificado correctamente.");
            window.location.href = "/ConsultorioDental/vistas/admin/gestionUsu.php";
        </script>';
        } else {
            echo "Error al modificar el usuario: " . $conn->error;
        }

        $stmt->close();
    }
 else if (isset($_POST['nombre_usuario'])) {
        // Insertar nuevo usuario
        $nombre_usuario = $_POST['nombre_usuario'];
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];
        $rol = $_POST['rol'];
    
        // Hash de la contraseña utilizando SHA-256
        $contrasena_hashed = hash('sha256', $contrasena);
    
        $sql = "INSERT INTO usuarios (nombre_usuario, email, contrasena, rol) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre_usuario, $email, $contrasena_hashed, $rol);
    
        if ($stmt->execute()) {
            echo '<script>
            alert("Usuario añadido correctamente.");
            window.location.href = "/ConsultorioDental/vistas/admin/gestionUsu.php";
        </script>';
            
        } else {
            echo "Error al añadir el usuario: " . $conn->error;
        }
    
        $stmt->close();
    
    
    } else if (isset($_POST['deleteId'])) {
        // Eliminar usuario existente
        $id = $_POST['deleteId'];

        $sql = "DELETE FROM usuarios WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo '<script>
            alert("Usuario eliminado correctamente.");
            window.location.href = "/ConsultorioDental/vistas/admin/gestionUsu.php";
        </script>';
        } else {
            echo "Error al eliminar el usuario: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
