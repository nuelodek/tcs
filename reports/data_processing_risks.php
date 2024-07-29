<?php
// include '../layouts/session.php'; 
// include '../layouts/main.php'; 

// Include the database connection file
// require_once '../layouts/config.php';
include 'db.php';
require_once('tcpdf/tcpdf/tcpdf.php');

$company_id = 'YNT3574'; // change back to session id 

// Example of setting a dynamic name (you might get this from session, POST data, etc.)
$dynamic_name = $_SESSION['user_name'] ?? 'Default User'; // Replace with your method of getting the dynamic name

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get counts for suggested and created risks
$suggested_risk_count = 0;
$created_risk_count = 0;
$activity_count = 0;

$count_sql = "SELECT COUNT(*) AS suggested_count FROM risk_identifiable WHERE company_id = ?";
if ($count_stmt = $conn->prepare($count_sql)) {
    $count_stmt->bind_param("s", $company_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    if ($count_row = $count_result->fetch_assoc()) {
        $suggested_risk_count = $count_row['suggested_count'];
    }
    $count_stmt->close();
}

$count_sql = "SELECT COUNT(*) AS created_count FROM risk_identifiable WHERE creation_status = 'Raised' AND company_id = ?";
if ($count_stmt = $conn->prepare($count_sql)) {
    $count_stmt->bind_param("s", $company_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    if ($count_row = $count_result->fetch_assoc()) {
        $created_risk_count = $count_row['created_count'];
    }
    $count_stmt->close();
}

// Count unique reference numbers
$count_sql = "SELECT COUNT(DISTINCT reference_number) AS activity_count FROM risk_identifiable WHERE company_id = ?";
if ($count_stmt = $conn->prepare($count_sql)) {
    $count_stmt->bind_param("s", $company_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    if ($count_row = $count_result->fetch_assoc()) {
        $activity_count = $count_row['activity_count'];
    }
    $count_stmt->close();
}

// Prepare a statement to prevent SQL injection
$sql = "SELECT reference_number, category_of_risk, name_of_risk, risk_value, creation_status FROM risk_identifiable WHERE company_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set dynamic header
        $pdf->SetHeaderData('images/logi.jpeg', 0, 'Data Processing Activities - Suggested vs Created Risks', 'By ' . $dynamic_name);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($dynamic_name);
        $pdf->SetTitle('Data Processing Activities Report');
        $pdf->SetSubject('Report');
        $pdf->SetKeywords('TCPDF, PDF, report, data processing');

        // Set default header data with custom logo
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // Add a page
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $date_report_generated = date('Y-m-d H:i:s');
    $html = '<p><strong>Date Report Generated: ' . htmlspecialchars($date_report_generated) . '</strong></p>';
    $html .= '<p><strong>Activity Count: ' . htmlspecialchars($activity_count) . '</strong></p>';
    $html .= '<p><strong>Suggested Risk Count: ' . htmlspecialchars($suggested_risk_count) . '</strong></p>';
    $html .= '<p><strong>Created Risk Count: ' . htmlspecialchars($created_risk_count) . '</strong></p>';
    $html .= '<table border="1" cellpadding="4" cellspacing="0" style="width: 100%;">';
    $html .= '<thead><tr style="background-color: #00008B; color: white;"><th style="width: 20%;"><strong>Reference Number</strong></th><th style="width: 40%;"><strong>Suggested Risk </strong></th><th style="width: 20%;"><strong>Category</strong></th><th style="width: 20%;"><strong>Created Risks</strong></th></tr></thead>';        
    $html .= '<tbody>';

    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td style="width: 20%;">' . htmlspecialchars($row["reference_number"]) . '</td>';
        $html .= '<td style="width: 40%;">' . htmlspecialchars($row["risk_value"]) . '</td>';
        $html .= '<td style="width: 20%;">' . htmlspecialchars($row["category_of_risk"]) . '</td>';
        $html .= '<td style="width: 20%;">' . htmlspecialchars($row["creation_status"]) . '</td>';
        $html .= '</tr>';
    }
        
    $html .= '</tbody></table>';        
        $pdf->writeHTML($html, true, false, true, false, '');
        
        $pdf->Output('data_processing_activities_suggested_risks_report.pdf', 'I');
    } else {
        echo "<script>alert('You don\'t have any Data Processing Activities - Suggested vs Created Risks logs. Please check back later.');</script>";
    }
    
    $stmt->close();
} else {
    echo "Error preparing the SQL statement.";
}

$conn->close();
?>
