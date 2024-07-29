<?php
// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf/tcpdf.php');

// Create new PDF document.
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information.
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Emmanuel Odekunle');
$pdf->SetTitle('TCPDF Example');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Set default header data with logo
$logo = 'https://cdn.sstatic.net/Img/home/illo-home-hero.png?v=4718e8f857c5'; // Change this to the path of your default logo
$logoWidth = 30; // Adjust logo width as needed
$pdf->SetHeaderData($logo, $logoWidth, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Set header and footer fonts.
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font.
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins.
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks.
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor.
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page.
$pdf->AddPage();

// Set font.
$pdf->SetFont('helvetica', '', 12);

// Add a cell.
$pdf->Cell(0, 10, 'Hello World', 0, 1, 'C');

// Output the PDF.
$pdf->Output('example.pdf', 'I');
?>
