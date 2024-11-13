<?php
require('fpdf/fpdf.php');
// Incluir el archivo de configuración para la conexión a la base de datos
include __DIR__ . '/../config/config.php';

class PDF extends FPDF
{
    // Encabezado
    function Header()
    {
        // Logo
        $this->Image('../img/logo.jpg', 10, 6, 30); // Cambia la ruta del logo según sea necesario
        // Fuente
        $this->SetFont('Arial', 'B', 12);
        // Mover a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(120, 10, 'Reporte de Pagos', 0, 1, 'C');
        $this->Ln(10);
        
        // Información del consultorio
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, utf8_decode('Dirección del Consultorio: Calle Ficticia 123, Ciudad, País'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('Teléfono: +52 123 456 7890'), 0, 1, 'C');
        $this->Cell(0, 10, 'Correo: consultorio@correo.com', 0, 1, 'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        // Fuente
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Cargar datos de la tabla de pagos
    function LoadData($conn)
    {
        $sql = "SELECT p.id, CONCAT(pa.nombre, ' ', pa.apellido) AS paciente, c.fecha_cita, p.monto, p.fecha_pago, p.metodo, p.estado 
                FROM pagos p
                JOIN citas c ON p.cita_id = c.id
                JOIN pacientes pa ON p.paciente_id = pa.id";
        $result = $conn->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Tabla de pagos con columnas ajustadas y color
    function BasicTable($header, $data)
    {
        // Anchos de las columnas (ajusta según necesites)
        $w = array(10, 60, 45, 22, 40, 25, 35); // Anchos para ID, Paciente, Fecha de Cita, Monto, Fecha de Pago, Método y Estado
        
        // Colores de los encabezados
        $this->SetFillColor(0, 121, 107); // Verde oscuro
        $this->SetTextColor(255, 255, 255); // Blanco
        $this->SetFont('Arial', 'B', 10);

        // Encabezado
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        
        // Restaurar colores y fuente para los datos
        $this->SetFillColor(224, 235, 255); // Color de fondo de las filas
        $this->SetTextColor(0, 0, 0); // Negro
        $this->SetFont('Arial', '', 10);

        // Datos
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, isset($row['id']) ? $row['id'] : '', 1, 0, 'C', $fill);
            $this->Cell($w[1], 6, isset($row['paciente']) ? $row['paciente'] : '', 1, 0, 'C', $fill);
            $this->Cell($w[2], 6, isset($row['fecha_cita']) ? $row['fecha_cita'] : '', 1, 0, 'C', $fill);
            $this->Cell($w[3], 6, isset($row['monto']) ? number_format($row['monto'], 2) : '', 1, 0, 'R', $fill);
            $this->Cell($w[4], 6, isset($row['fecha_pago']) ? $row['fecha_pago'] : '', 1, 0, 'C', $fill);
            $this->Cell($w[5], 6, isset($row['metodo']) ? $row['metodo'] : '', 1, 0, 'C', $fill);
            $this->Cell($w[6], 6, isset($row['estado']) ? $row['estado'] : '', 1, 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill; // Alternar color de fondo de las filas
        }
    }
}

// Crear el objeto PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L');

// Títulos de las columnas
// Títulos de las columnas
$header = array(
    utf8_decode('ID'), 
    utf8_decode('Paciente'), 
    utf8_decode('Fecha de Cita'), 
    utf8_decode('Monto'), 
    utf8_decode('Fecha de Pago'), 
    utf8_decode('Método'), 
    utf8_decode('Estado')
);


// Cargar los datos
$data = $pdf->LoadData($conn);

// Imprimir la tabla
$pdf->BasicTable($header, $data);

// Salida del PDF
ob_end_clean(); // Limpia el buffer de salida antes de enviar el PDF
$pdf->Output('D', 'Reporte_de_Pagos.pdf');

// Cerrar la conexión a la base de datos
$conn->close();
?>
