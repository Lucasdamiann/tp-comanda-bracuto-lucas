<?php
// require_once '../../../vendor/setasign/fpdf/fpdf.php';   //no funciona
// use setasign\fpdf\Fpdf;   //no funciona
// use Fpdf\Fpdf;    //no funciona
require_once 'D:\Instalaciones\Programas\xampp\htdocs\Clases\TP\vendor\setasign\fpdf\fpdf.php';
class PDFManager extends Fpdf
{
    public function Header()
    {
        $logoPath = 'D:\Instalaciones\Programas\xampp\htdocs\Clases\TP\vendor\setasign\fpdf\tutorial\logo.png';
        $this->Image($logoPath, 10, 8, 33);       
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(40, 10, 'PRODUCTOS', 1, 0, 'C');
        $this->Ln(30);
        $this->Cell(10);
        $this->Cell(20, 10, 'ID', 1, 0, 'C');
        $this->Cell(40, 10, 'Tipo', 1, 0, 'C');
        $this->Cell(40, 10, 'Sector', 1, 0, 'C');
        $this->Cell(30, 10, 'Precio', 1, 0, 'C');
        $this->Cell(40, 10, 'Estado', 1, 0, 'C');
        // Salto de línea
        $this->Ln(20);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    public function mostrarDatos($datos)
    {
        $this->SetFont('Arial', '', 12);
        foreach ($datos as $fila) {
            $this->Cell(10);
            $this->Cell(20, 10, $fila->id, 1, 0, 'C');
            $this->Cell(40, 10, $fila->tipo, 1, 0, 'C');
            $this->Cell(40, 10, $fila->sector, 1, 0, 'C');
            $this->Cell(30, 10, $fila->precio, 1, 0, 'C');
            $this->Cell(40, 10, $fila->estado, 1, 0, 'C');
            $this->Ln();
        }
    }
}
