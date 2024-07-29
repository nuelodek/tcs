<?php
require_once('tcpdf/tcpdf/tcpdf.php');

class CustomTCPDF extends TCPDF {
    public function Header() {
        // Set header image
        $image_file = 'images/logi.jpeg'; // Path to your image
        $this->Image($image_file, 10, 10, 30, '', 'JPEG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        // Set header title
        $this->SetFont('helvetica', 'B', 12);
        $this->SetY(15); // Position the title a bit lower than the image
        $this->Cell(0, 15, 'Data Processing Activities Report', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    public function Footer() {
        // Set footer text
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
?>
