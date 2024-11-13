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
        $this->Cell(30, 10, 'Reporte de Usuarios', 0, 1, 'C');
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

    // Cargar datos de la tabla de usuarios
    function LoadData($conn)
    {
        $sql = "SELECT id, nombre_usuario, email, rol FROM usuarios";
        $result = $conn->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Tabla de usuarios con columnas ajustadas y color
    function BasicTable($header, $data)
    {
        // Anchos de las columnas (ajusta según necesites)
        $w = array(20, 50, 90, 30); // Anchos para ID, Nombre, Email y Rol
        
        // Colores de los encabezados
        $this->SetFillColor(0, 121, 107); // Azul claro
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
            $this->Cell($w[1], 6, $row['nombre_usuario'], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['email'], 1, 0, 'L', $fill);
            $this->Cell($w[3], 6, $row['rol'], 1, 0, 'C', $fill);
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
$header = array('ID', 'Nombre de Usuario', 'Email', 'Rol');

// Cargar los datos
$data = $pdf->LoadData($conn);

// Imprimir la tabla
$pdf->BasicTable($header, $data);

// Salida del PDF
$pdf->Output('D', 'Reporte de usuarios.pdf');

// Cerrar la conexión a la base de datos
$conn->close();
?>
