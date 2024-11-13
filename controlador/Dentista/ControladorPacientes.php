<?php
// Incluir el archivo de configuración para la conexión a la base de datos
include __DIR__ . '/../../config/config.php';

// Verificar la acción solicitada (insertar, modificar o eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        // Modificar paciente existente
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $historial_medico = $_POST['historial_medico'];

        $sql = "UPDATE pacientes SET nombre=?, apellido=?, fecha_nacimiento=?, email=?, telefono=?, direccion=?, historial_medico=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $nombre, $apellido, $fecha_nacimiento, $email, $telefono, $direccion, $historial_medico, $id);

        if ($stmt->execute()) {
            echo '<script>
            alert("Paciente modificado correctamente.");
            window.location.href = "/ConsultorioDental/vistas/dentista/GestionPacientes.php";
        </script>';
        } else {
            echo "Error al modificar el paciente: " . $conn->error;
        }

        $stmt->close();

    } else if (isset($_POST['nombre'])) {
        // Insertar nuevo paciente
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $historial_medico = $_POST['historial_medico'];

        $sql = "INSERT INTO pacientes (nombre, apellido, fecha_nacimiento, email, telefono, direccion, historial_medico) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $nombre, $apellido, $fecha_nacimiento, $email, $telefono, $direccion, $historial_medico);

        if ($stmt->execute()) {
            echo '<script>
            alert("Paciente añadido correctamente.");
            window.location.href = "/ConsultorioDental/vistas/dentista/GestionPacientes.php";
        </script>';
        } else {
            echo "Error al añadir el paciente: " . $conn->error;
        }

        $stmt->close();

    } else if (isset($_POST['deleteId'])) {
        // Eliminar paciente existente
        $id = $_POST['deleteId'];

        $sql = "DELETE FROM pacientes WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo '<script>
            alert("Paciente eliminado correctamente.");
            window.location.href = "/ConsultorioDental/vistas/dentista/GestionPacientes.php";
        </script>';
        } else {
            echo "Error al eliminar el paciente: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
