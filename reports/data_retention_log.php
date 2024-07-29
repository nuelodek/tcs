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

// Get counts for data retention logs
$data_retention_count = 0;

$count_sql = "SELECT COUNT(*) AS retention_count FROM data_retention_log WHERE company_id = ?";
if ($count_stmt = $conn->prepare($count_sql)) {
    $count_stmt->bind_param("s", $company_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    if ($count_row = $count_result->fetch_assoc()) {
        $data_retention_count = $count_row['retention_count'];
    }
    $count_stmt->close();
}

// Prepare a statement to prevent SQL injection
$sql = "SELECT reference_number, country_or_nigeria_state, country, nigeria_state, category_of_record, sub_category_of_record, record_type, what_to_store, who_should_store_the_data, retention_period, period, retention_starts_from, final_disposition, associated_data_processing_activities, next_review_date, additional_notes, created_at FROM data_retention_log WHERE company_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set dynamic header
        $pdf->SetHeaderData('images/logi.jpeg', 0, 'Data Retention Logs', 'By ' . $dynamic_name);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($dynamic_name);
        $pdf->SetTitle('Data Retention Logs Report');
        $pdf->SetSubject('Report');
        $pdf->SetKeywords('TCPDF, PDF, report, data retention');

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
        $html .= '<p><strong>Data Retention Logs Count: ' . htmlspecialchars($data_retention_count) . '</strong></p>';
        $html .= '<table border="1" cellpadding="4" cellspacing="0" style="width: 100%;">';
        $html .= '<thead><tr style="background-color: #00008B; color: white;">
                    <th><strong>Reference Number</strong></th>
                    <th><strong>Country/State</strong></th>
                    <th><strong>Category</strong></th>
                    <th><strong>Sub-Category</strong></th>
                    <th><strong>Record Type</strong></th>
                    <th><strong>What to Store</strong></th>
                    <th><strong>Who Should Store</strong></th>
                    <th><strong>Retention Period</strong></th>
                    <th><strong>Retention Starts From</strong></th>
                    <th><strong>Final Disposition</strong></th>
                    <th><strong>Associated Activities</strong></th>
                    <th><strong>Next Review Date</strong></th>
                    <th><strong>Additional Notes</strong></th>
                    <th><strong>Created At</strong></th>
                  </tr></thead>';        
        $html .= '<tbody>';

        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row["reference_number"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["country_or_nigeria_state"] == 'Country' ? $row["country"] : $row["nigeria_state"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["category_of_record"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["sub_category_of_record"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["record_type"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["what_to_store"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["who_should_store_the_data"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["retention_period"] . ' ' . $row["period"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["retention_starts_from"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["final_disposition"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["associated_data_processing_activities"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["next_review_date"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["additional_notes"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["created_at"]) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';        
        $pdf->writeHTML($html, true, false, true, false, '');

        ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering
        $pdf->Output('data_retention_logs_report.pdf', 'I');
        exit; // Ensure no further output is sent
    } else {
        echo "<script>alert('You don\'t have any Data Retention Logs. Please check back later.');</script>";
    }

    $stmt->close();
} else {
    echo "Error preparing the SQL statement.";
}

$conn->close();
?>
