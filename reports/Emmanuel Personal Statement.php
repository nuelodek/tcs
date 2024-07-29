<?php
require_once('tcpdf/tcpdf/tcpdf.php');


// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Emmanuel Odekunle');
$pdf->SetTitle('Personal Statement');
$pdf->SetSubject('Personal Statement for MSc Economics and Data Analytics');
$pdf->SetKeywords('TCPDF, PDF, personal statement, economics, data analytics');

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default font
$pdf->SetFont('helvetica', '', 12);

// Add a page
$pdf->AddPage();

// Personal statement content
$personal_statement = <<<EOD
<h1 style="text-align: center;">Personal Statement</h1>
<p><strong>Name:</strong> Emmanuel Odekunle</p>
<p>I am enthusiastic about pursuing the MSc Economics and Data Analytics at the University of Dublin. With a strong foundation in Economics, demonstrated through my Bachelor’s degree, and practical experience in data analysis and programming, I am eager to advance my expertise in this dynamic field.</p>
<p>During my undergraduate studies, I engaged deeply with modules such as Econometrics, Applied Statistics, and various economics courses. These modules not only honed my analytical skills but also fostered a robust ability to interpret complex data. This skill was further enhanced through my role as a programmer, where I built data-driven web platforms.</p>
<p>My professional journey has provided me with hands-on experience using analytical tools and programming languages like PHP, JavaScript, and SQL. This technical background complements my academic knowledge, allowing me to leverage data-driven decisions in business operations and strategic planning.</p>
<p>The MSc Economics and Data Analytics program at the University of Dublin aligns perfectly with my career goals. I am particularly drawn to the program's comprehensive curriculum, which integrates advanced statistical methods with practical data analysis applications. I am excited about the opportunity to collaborate with esteemed faculty and peers, engage in cutting-edge research, and contribute to the field of economics through data analytics.</p>
<p>With my academic background, practical experience, and passion for data analysis, I am confident in my ability to contribute meaningfully to the program and thrive in this intellectually stimulating environment.</p>
EOD;

// Print text using writeHTML()
$pdf->writeHTML($personal_statement, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('personal_statement.pdf', 'I');
?>
