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
        $this->Cell(120, 10, 'Reporte Mensual', 0, 1, 'C');
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
        $sql = "
        SELECT c.id AS cita_id, p.nombre, p.apellido, c.fecha_cita, c.estado, c.notas
        FROM citas c
        JOIN pacientes p ON c.paciente_id = p.id
        WHERE MONTH(c.fecha_cita) = MONTH(CURDATE()) AND YEAR(c.fecha_cita) = YEAR(CURDATE())
        ";
        $result = $conn->query($sql);
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
        $w = array(20, 50, 50, 40, 30, 50);
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
            $this->Cell($w[0], 6, $row['cita_id'], 1, 0, 'C', $fill);
            $this->Cell($w[1], 6, $row['nombre'], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['apellido'], 1, 0, 'L', $fill);
            $this->Cell($w[3], 6, $row['fecha_cita'], 1, 0, 'C', $fill);
            $this->Cell($w[4], 6, $row['estado'], 1, 0, 'L', $fill);
            $this->Cell($w[5], 6, $row['notas'], 1, 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
        }
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L');

$header = array('Cita ID', 'Nombre', 'Apellido', 'Fecha Cita', 'Estado', 'Notas');
$data = $pdf->LoadData($conn);

$pdf->BasicTable($header, $data);

$pdf->Output('D', 'Reporte_Mensual.pdf');

$conn->close();
?>
