<?php
// Incluir el archivo de configuración para la conexión a la base de datos
include __DIR__ . '/../../config/config.php';

// Verificar la acción solicitada (insertar, modificar o eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['receta_id'])) {
        // Modificar receta existente
        $id = $_POST['receta_id'];
        $medicamento = $_POST['medicamento']; // Actualizar los campos correspondientes
        $dosis = $_POST['dosis'];
        $instrucciones = $_POST['instrucciones'];

        $sql = "UPDATE recetas SET medicamento=?, dosis=?, instrucciones=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $medicamento, $dosis, $instrucciones, $id);

        if ($stmt->execute()) {
            echo '<script>
            alert("Receta modificada correctamente.");
            window.location.href = "/ConsultorioDental/vistas/asistente/Recetas.php";
        </script>';
        } else {
            echo "Error al modificar la receta: " . $conn->error;
        }

        $stmt->close();

    } else if (isset($_POST['paciente_id']) && isset($_POST['cita_id'])) {
        // Insertar nueva receta
        $paciente_id = $_POST['paciente_id'];
        $cita_id = $_POST['cita_id'];
        $medicamento = $_POST['medicamento'];
        $dosis = $_POST['dosis'];
        $instrucciones = $_POST['instrucciones'];
        $fecha_recetada = date('Y-m-d H:i:s'); // Asignar la fecha y hora actual

        $sql = "INSERT INTO recetas (paciente_id, cita_id, medicamento, dosis, instrucciones, fecha_recetada) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissss", $paciente_id, $cita_id, $medicamento, $dosis, $instrucciones, $fecha_recetada);

        if ($stmt->execute()) {
            echo '<script>
            alert("Receta añadida correctamente.");
            window.location.href = "/ConsultorioDental/vistas/asistente/Recetas.php";
        </script>';
        } else {
            echo "Error al añadir la receta: " . $conn->error;
        }

        $stmt->close();

    } else if (isset($_POST['deleteId'])) {
        // Eliminar receta
        $id = $_POST['deleteId'];

        $sql = "DELETE FROM recetas WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo '<script>
            alert("Receta eliminada correctamente.");
            window.location.href = "/ConsultorioDental/vistas/asistente/Recetas.php";
        </script>';
        } else {
            echo "Error al eliminar la receta: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
