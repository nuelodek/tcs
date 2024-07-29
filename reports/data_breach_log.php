<?php
ob_start(); // Start output buffering
include 'db.php';
require_once('tcpdf/tcpdf/tcpdf.php');

$company_id = 'YNT3574'; // change back to session id 

// Example of setting a dynamic name (you might get this from session, POST data, etc.)
$dynamic_name = $_SESSION['user_name'] ?? 'Default User'; // Replace with your method of getting the dynamic name

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get counts for data breaches
$data_breach_count = 0;
$count_sql = "SELECT COUNT(*) AS breach_count FROM databreachlog WHERE company_id = ?";
if ($count_stmt = $conn->prepare($count_sql)) {
    $count_stmt->bind_param("s", $company_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    if ($count_row = $count_result->fetch_assoc()) {
        $data_breach_count = $count_row['breach_count'];
    }
    $count_stmt->close();
}

// Prepare a statement to prevent SQL injection
$sql = "SELECT incident_type, incident_source, nature_of_data_breach, date_of_breach, time_of_breach, date_of_breach_notification, time_of_breach_notification, number_of_data_subjects_affected FROM databreachlog WHERE company_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set dynamic header
        $pdf->SetHeaderData('images/logi.jpeg', 0, 'Data Breach Logs', 'By ' . $dynamic_name);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($dynamic_name);
        $pdf->SetTitle('Data Breach Logs Report');
        $pdf->SetSubject('Report');
        $pdf->SetKeywords('TCPDF, PDF, report, data breach');

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
        $html .= '<p><strong>Data Breach Logs Count: ' . htmlspecialchars($data_breach_count) . '</strong></p>';
        $html .= '<table border="1" cellpadding="4" cellspacing="0" style="width: 100%;">';
        $html .= '<thead><tr style="background-color: #00008B; color: white;">
                    <th><strong>Incident Type</strong></th>
                    <th><strong>Source</strong></th>
                    <th><strong>Description</strong></th>
                    <th><strong>Discovered</strong></th>
                    <th><strong>Deadline</strong></th>
                    <th><strong>Data Subjects</strong></th>
                  </tr></thead>';        
        $html .= '<tbody>';

        while ($row = $result->fetch_assoc()) {
            $discovered = $row["date_of_breach"] . ' ' . $row["time_of_breach"];
            $deadline = date('d M Y H:i', strtotime($discovered . ' + 3 days'));
            $discovered = date('d M Y H:i', strtotime($discovered));

            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row["incident_type"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["incident_source"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["nature_of_data_breach"]) . '</td>';
            $html .= '<td>' . $discovered . '</td>';
            $html .= '<td>' . $deadline . '</td>';
            $html .= '<td>' . htmlspecialchars($row["number_of_data_subjects_affected"]) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';        
        $pdf->writeHTML($html, true, false, true, false, '');

        ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering
        $pdf->Output('data_breach_logs_report.pdf', 'I');
        exit; // Ensure no further output is sent
    } else {
        echo "<script>alert('You don\'t have any Data Breach Logs. Please check back later.');</script>";
    }

    $stmt->close();
} else {
    echo "Error preparing the SQL statement.";
}

$conn->close();
?>
