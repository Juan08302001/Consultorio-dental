<?php
require('fpdf/fpdf.php');
include __DIR__ . '/../config/config.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../img/logo.jpg', 10, 6, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Reporte Completo', 0, 1, 'C');
        $this->Ln(5);
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
        SELECT p.id AS paciente_id, p.nombre, p.apellido, p.fecha_nacimiento, p.email, p.telefono, p.direccion, p.historial_medico,
               c.id AS cita_id, c.fecha_cita, c.estado AS cita_estado, c.notas,
               r.id AS receta_id, r.medicamento, r.dosis, r.instrucciones, r.fecha_recetada,
               pa.id AS pago_id, pa.monto, pa.fecha_pago, pa.metodo, pa.estado AS pago_estado
        FROM pacientes p
        LEFT JOIN citas c ON p.id = c.paciente_id
        LEFT JOIN recetas r ON p.id = r.paciente_id AND c.id = r.cita_id
        LEFT JOIN pagos pa ON p.id = pa.paciente_id AND c.id = pa.cita_id
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

    function PrintData($data)
    {
        $this->SetFont('Arial', '', 10);
        foreach ($data as $row) {
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(0, 6, "Paciente: " . $row['nombre'] . " " . $row['apellido'], 0, 1);
            $this->SetFont('Arial', '', 10);
            $this->MultiCell(0, 5, "Fecha de Nacimiento: " . $row['fecha_nacimiento'] . "\n" .
                                   "Email: " . $row['email'] . "\n" .
                                   "Teléfono: " . $row['telefono'] . "\n" .
                                   "Dirección: " . $row['direccion'] . "\n" .
                                   "Historial Médico: " . $row['historial_medico'] . "\n" .
                                   "Fecha de Cita: " . $row['fecha_cita'] . "\n" .
                                   "Estado de Cita: " . $row['cita_estado'] . "\n" .
                                   "Notas: " . $row['notas'] . "\n" .
                                   "Medicamento: " . $row['medicamento'] . "\n" .
                                   "Dosis: " . $row['dosis'] . "\n" .
                                   "Instrucciones: " . $row['instrucciones'] . "\n" .
                                   "Fecha Recetada: " . $row['fecha_recetada'] . "\n" .
                                   "Monto de Pago: " . $row['monto'] . "\n" .
                                   "Fecha de Pago: " . $row['fecha_pago'] . "\n" .
                                   "Método de Pago: " . $row['metodo'] . "\n" .
                                   "Estado de Pago: " . $row['pago_estado'], 0);
            $this->Ln(10);
        }
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$data = $pdf->LoadData($conn);

$pdf->PrintData($data);

$pdf->Output('D', 'Reporte_Completo.pdf');

$conn->close();
?>