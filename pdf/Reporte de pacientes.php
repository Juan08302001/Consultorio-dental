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
        $this->Image('../img/logo.jpg', 10, 6, 30); // Cambia la ruta del logo
        // Fuente
        $this->SetFont('Arial', 'B', 12);
        // Ancho total de la página menos márgenes
        $pageWidth = $this->GetPageWidth();
        $leftMargin = $this->lMargin;
        $rightMargin = $this->rMargin;
        $textWidth = $this->GetStringWidth('Reporte de Pacientes');
        $xPosition = ($pageWidth - $leftMargin - $rightMargin - $textWidth) / 2;
        // Mover a la posición X calculada
        $this->SetX($xPosition);
        // Título
        $this->Cell($textWidth, 10, 'Reporte de Pacientes', 0, 1, 'C');
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

    // Cargar datos de la tabla de pacientes
    function LoadData($conn)
    {
        $sql = "SELECT id, nombre, apellido, fecha_nacimiento, email, telefono, direccion FROM pacientes";
        $result = $conn->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Tabla de pacientes con columnas ajustadas y color
    function BasicTable($header, $data)
    {
        // Anchos de las columnas (ajusta según necesites)
        $w = array(10, 40, 40, 40, 50, 40, 55); // Anchos para ID, Nombre, Apellido, Fecha de Nacimiento, Email, Teléfono, Dirección
        
        // Colores de los encabezados
        $this->SetFillColor(0, 121, 107); // Azul claro
        $this->SetTextColor(255, 255, 255); // Blanco
        $this->SetFont('Arial', 'B', 10);

        // Encabezado
        $this->SetX(10); // Alineación inicial
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        }
        $this->Ln();

        // Restaurar colores y fuente para los datos
        $this->SetFillColor(224, 235, 255); // Color de fondo de las filas
        $this->SetTextColor(0, 0, 0); // Negro
        $this->SetFont('Arial', '', 10);

        // Datos
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row['id'], 1, 0, 'C', $fill);
            $this->Cell($w[1], 6, utf8_decode($row['nombre']), 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, utf8_decode($row['apellido']), 1, 0, 'L', $fill);
            $this->Cell($w[3], 6, $row['fecha_nacimiento'], 1, 0, 'C', $fill);
            $this->Cell($w[4], 6, utf8_decode($row['email']), 1, 0, 'L', $fill);
            $this->Cell($w[5], 6, utf8_decode($row['telefono']), 1, 0, 'L', $fill);
            $this->Cell($w[6], 6, utf8_decode($row['direccion']), 1, 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill; // Alternar color de fondo de las filas
        }
    }
}

// Crear el objeto PDF
$pdf = new PDF('L', 'mm', 'A4'); // 'L' para horizontal, 'mm' para milímetros, 'A4' para tamaño de página
$pdf->AliasNbPages();
$pdf->AddPage();

// Títulos de las columnas
$header = array('ID', 'Nombre', 'Apellido', 'Fecha de Nacimiento', 'Email', 'Teléfono', 'Dirección');

// Cargar los datos
$data = $pdf->LoadData($conn);

// Imprimir la tabla centrada
$pdf->SetX(10); // Margen izquierdo
$pdf->BasicTable($header, $data);

// Salida del PDF
$pdf->Output('D', 'Reporte de Pacientes.pdf');

// Cerrar la conexión a la base de datos
$conn->close();
?>
