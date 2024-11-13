<?php
require('fpdf/fpdf.php');
include __DIR__ . '/../config/config.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../img/logo.jpg', 10, 6, 30); // Ajusta la ruta a tu logo
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'RECETA MEDICA', 0, 1, 'C');
        $this->Ln(10);
        
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, utf8_decode('Dirección del Consultorio: Calle Ficticia 123, Ciudad, País'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('Teléfono: +52 123 456 7890'), 0, 1, 'C');
        $this->Cell(0, 10, 'Correo: consultorio@correo.com', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function LoadData($conn, $paciente_id)
    {
        $sql = "SELECT p.nombre, p.apellido, r.medicamento, r.dosis, r.instrucciones, r.fecha_recetada 
                FROM recetas r
                INNER JOIN pacientes p ON r.paciente_id = p.id
                WHERE r.paciente_id = $paciente_id";
        
        $result = $conn->query($sql);
        
        if (!$result) {
            die("Error en la consulta SQL: " . $conn->error);
        }

        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    function BasicContent($data)
    {
        if (empty($data)) {
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, 'No se encontraron recetas para el paciente.', 0, 1);
            return;
        }

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Paciente: ' . $data[0]['nombre'] . ' ' . $data[0]['apellido'], 0, 1);
        $this->Ln(5);

        $this->SetFont('Arial', '', 12);
        
        foreach ($data as $row) {
            $this->Cell(0, 10, 'Medicamento: ' . $row['medicamento'], 0, 1);
            $this->Cell(0, 10, 'Dosis: ' . $row['dosis'], 0, 1);
            $this->Cell(0, 10, 'Instrucciones: ' . $row['instrucciones'], 0, 1);
            $this->Ln(5);
        }
        
        // Fecha recetada al final
        $this->Cell(0, 10, 'Fecha Recetada: ' . date('d/m/Y', strtotime($data[0]['fecha_recetada'])), 0, 1);
    }
}

// Crear instancia de PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Obtener el ID del paciente del parámetro GET
$paciente_id = isset($_GET['paciente_id_pdf']) ? intval($_GET['paciente_id_pdf']) : 0;
$data = $pdf->LoadData($conn, $paciente_id);

// Generar el contenido del PDF
$pdf->BasicContent($data);

// Forzar la descarga del archivo PDF
$pdf->Output('D', 'Receta_Medica.pdf');

$conn->close();
?>
