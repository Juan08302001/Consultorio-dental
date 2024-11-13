<?php
// Incluir el archivo de configuración para la conexión a la base de datos
include __DIR__ . '/../config/config.php';

// Verificar si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Comprobar si se está realizando una modificación de un pago existente
    if (isset($_POST['edit_payment_id'])) {
        $payment_id = $_POST['edit_payment_id'];
        $monto = $_POST['edit_monto'];
        $fecha_pago = $_POST['edit_fecha_pago'];
        $metodo = $_POST['edit_metodo'];
        $estado = $_POST['edit_estado'];

        // Preparar la consulta SQL para actualizar el pago existente
        $sql = "UPDATE pagos SET monto=?, fecha_pago=?, metodo=?, estado=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dsssi", $monto, $fecha_pago, $metodo, $estado, $payment_id);
        

        // Ejecutar la consulta y verificar si se actualizó correctamente
        if ($stmt->execute()) {
            echo '<script>
                alert("Pago modificado correctamente.");
                window.location.href = "/ConsultorioDental/vistas/admin/Pagos.php";
            </script>';
        } else {
            echo "Error al modificar el pago: " . $conn->error;
        }

        $stmt->close();

    // Comprobar si se está realizando una inserción de un nuevo pago
} else if (isset($_POST['paciente_id'])) {
    $paciente_id = $_POST['paciente_id'];
    $cita_id = $_POST['cita_id'];
    $monto = $_POST['monto'];
    $fecha_pago = $_POST['fecha_pago'];
    $metodo = $_POST['metodo'];
    $estado = isset($_POST['edit_estado']) ? $_POST['edit_estado'] : null;

    // Preparar la consulta SQL para insertar el nuevo pago
    $sql = "INSERT INTO pagos (paciente_id, cita_id, monto, fecha_pago, metodo, estado) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $paciente_id, $cita_id, $monto, $fecha_pago, $metodo, $estado);

    // Ejecutar la consulta y verificar si se insertó correctamente
    if ($stmt->execute()) {
        echo '<script>
            alert("Pago añadido correctamente.");
            window.location.href = "/ConsultorioDental/vistas/admin/Pagos.php";
        </script>';
    } else {
        echo "Error al añadir el pago: " . $conn->error;
    }

    $stmt->close();


    // Comprobar si se está realizando una eliminación de un pago existente
    } else if (isset($_POST['delete_payment_id'])) {
        $payment_id = $_POST['delete_payment_id'];

        // Preparar la consulta SQL para eliminar el pago existente
        $sql = "DELETE FROM pagos WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $payment_id);

        // Ejecutar la consulta y verificar si se eliminó correctamente
        if ($stmt->execute()) {
            echo '<script>
                alert("Pago eliminado correctamente.");
                window.location.href = "/ConsultorioDental/vistas/admin/Pagos.php";
            </script>';
        } else {
            echo "Error al eliminar el pago: " . $conn->error;
        }

        $stmt->close();
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
