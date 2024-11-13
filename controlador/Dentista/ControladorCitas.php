<?php
// Incluir el archivo de configuración para la conexión a la base de datos
include __DIR__ . '/../../config/config.php';

// Verificar la acción solicitada (insertar, modificar o eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        // Modificar cita existente
        $id = $_POST['id'];
        $fecha_cita = $_POST['fecha_cita']; // Solo actualizar el campo que desees modificar
        $estado = $_POST['estado'];
        $notas = $_POST['notas'];

        $sql = "UPDATE citas SET fecha_cita=?, estado=?, notas=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $fecha_cita, $estado, $notas, $id);

        if ($stmt->execute()) {
            echo '<script>
            alert("Cita modificada correctamente.");
                        window.location.href = "/ConsultorioDental/vistas/dentista/Citas.php";

        </script>';
        } else {
            echo "Error al modificar la cita: " . $conn->error;
        }

        $stmt->close();

    } else if (isset($_POST['paciente_id'])) {
        // Insertar nueva cita
        $paciente_id = $_POST['paciente_id'];
        $fecha_cita = $_POST['fecha_cita'];
        $estado = $_POST['estado'];
        $notas = $_POST['notas'];

        $sql = "INSERT INTO citas (paciente_id, fecha_cita, estado, notas) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $paciente_id, $fecha_cita, $estado, $notas);

        if ($stmt->execute()) {
            echo '<script>
            alert("Cita añadida correctamente.");
                        window.location.href = "/ConsultorioDental/vistas/dentista/Citas.php";

        </script>';
        } else {
            echo "Error al añadir la cita: " . $conn->error;
        }

        $stmt->close();

    } else if (isset($_POST['deleteId'])) {
        // Eliminar cita existente
        $id = $_POST['deleteId'];

        $sql = "DELETE FROM citas WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo '<script>
            alert("Cita eliminada correctamente.");
            window.location.href = "/ConsultorioDental/vistas/dentista/Citas.php";
        </script>';
        } else {
            echo "Error al eliminar la cita: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
