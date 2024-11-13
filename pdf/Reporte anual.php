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
        $this->Cell(30, 10, 'Reporte Anual', 0, 1, 'C');
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
        $sql = "SELECT c.id, CONCAT(p.nombre, ' ', p.apellido) AS paciente, TIME(c.fecha_cita) AS hora, c.notas AS tratamiento, c.estado AS status 
                FROM citas c 
                INNER JOIN pacientes p ON c.paciente_id = p.id 
                WHERE YEAR(c.fecha_cita) = YEAR(CURDATE())";
        
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

    function BasicTable($header, $data)
    {
        $w = array(20, 60, 30, 40, 30);
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
            $this->Cell($w[1], 6, $row['paciente'], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['hora'], 1, 0, 'C', $fill);
            $this->Cell($w[3], 6, $row['tratamiento'], 1, 0, 'L', $fill);
            $this->Cell($w[4], 6, $row['status'], 1, 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        }
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$header = array('ID', 'Paciente', 'Hora', 'Tratamiento', 'Status');
$data = $pdf->LoadData($conn);

$pdf->BasicTable($header, $data);

$pdf->Output('D', 'Reporte_Anual.pdf');

$conn->close();

?>