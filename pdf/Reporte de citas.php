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
        // Mover a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(30, 10, 'Reporte de Citas', 0, 1, 'C');
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
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Cargar datos de la tabla de citas con nombres de pacientes
function LoadData($conn)
{
    $sql = "SELECT c.id, p.nombre, p.apellido, c.fecha_cita, c.estado, c.notas 
            FROM citas c 
            INNER JOIN pacientes p ON c.paciente_id = p.id";
    $result = $conn->query($sql);
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}


   // Tabla de citas con columnas ajustadas y color
function BasicTable($header, $data)
{
    // Anchos de las columnas (ajusta según necesites)
    $w = array(8, 60, 40, 30, 60); // Anchos para ID, Paciente (Nombre y Apellido), Fecha, Estado y Notas
    
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
        $this->Cell($w[0], 6, $row['id'], 1, 0, 'C', $fill);
        $this->Cell($w[1], 6, $row['nombre'] . ' ' . $row['apellido'], 1, 0, 'L', $fill); // Nombre y apellido del paciente
        $this->Cell($w[2], 6, $row['fecha_cita'], 1, 0, 'C', $fill);
        $this->Cell($w[3], 6, $row['estado'], 1, 0, 'C', $fill);
        $this->Cell($w[4], 6, $row['notas'], 1, 0, 'L', $fill);
        $this->Ln();
        $fill = !$fill; // Alternar color de fondo de las filas
    }
}

}

// Crear el objeto PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Títulos de las columnas
// Títulos de las columnas
$header = array('ID', 'Paciente', 'Fecha de Cita', 'Estado', 'Notas');


// Cargar los datos
$data = $pdf->LoadData($conn);

// Imprimir la tabla
$pdf->BasicTable($header, $data);

// Salida del PDF
$pdf->Output('D', 'Reporte de citas.pdf');

// Cerrar la conexión a la base de datos
$conn->close();
?>