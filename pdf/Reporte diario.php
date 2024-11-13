<?php
require('fpdf/fpdf.php');
include __DIR__ . '/../config/config.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../img/logo.jpg', 10, 6, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(80);
        $this->Cell(30, 10, 'Reporte Diario', 0, 1, 'C');
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
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function LoadData($conn)
    {
        // Modificamos la consulta para incluir el nombre y apellido del paciente
        $sql = "SELECT citas.id, CONCAT(pacientes.nombre, ' ', pacientes.apellido) AS paciente_nombre, citas.fecha_cita, citas.estado, citas.notas 
                FROM citas 
                JOIN pacientes ON citas.paciente_id = pacientes.id 
                WHERE DATE(citas.fecha_cita) = CURDATE()"; // Reporte del día actual
        
        $result = $conn->query($sql);

        if ($result === false) {
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

    function BasicTable($header, $data)
    {
        $w = array(20, 70, 40, 30, 30);
        $this->SetFillColor(0, 121, 107);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);

        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 10);

        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row['id'], 1, 0, 'C', $fill);
            $this->Cell($w[1], 6, $row['paciente_nombre'], 1, 0, 'L', $fill); // Mostramos el nombre y apellido del paciente
            $this->Cell($w[2], 6, $row['fecha_cita'], 1, 0, 'C', $fill);
            $this->Cell($w[3], 6, $row['estado'], 1, 0, 'L', $fill);
            $this->Cell($w[4], 6, $row['notas'], 1, 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        }
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$header = array('ID', 'Paciente', 'Fecha', 'Estado', 'Notas');
$data = $pdf->LoadData($conn);

$pdf->BasicTable($header, $data);

$pdf->Output('D', 'Reporte_Diario.pdf');

$conn->close();
?>
