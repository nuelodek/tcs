<?php
ob_start(); // Start output buffering
include 'db.php';
require_once('tcpdf/tcpdf/tcpdf.php');

$company_id = 'YNT3574'; // Change back to session id if needed

// Example of setting a dynamic name (you might get this from session, POST data, etc.)
$dynamic_name = $_SESSION['user_name'] ?? 'Default User'; // Replace with your method of getting the dynamic name

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare a statement to prevent SQL injection and join risk_info with risk_action
$sql = "
    SELECT 
        r.reference_number AS ref_no,
        r.risk_title AS risk,
        r.risk_association AS associated_item,
        r.created_at AS created,
        r.status AS status,
        r.risk_impact AS impact,
        r.risk_likelihood AS likelihood,
        GROUP_CONCAT(a.action_status SEPARATOR ', ') AS actions
    FROM risk_info r
    LEFT JOIN risk_action a ON r.reference_number = a.reference_number
    WHERE r.company_id = ?
    GROUP BY r.reference_number
";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set dynamic header
        $pdf->SetHeaderData('images/logi.jpeg', 0, 'Risk Register Report', 'By ' . $dynamic_name);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($dynamic_name);
        $pdf->SetTitle('Risk Register Report');
        $pdf->SetSubject('Report');
        $pdf->SetKeywords('TCPDF, PDF, report, risk register');

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
        $html .= '<table border="1" cellpadding="4" cellspacing="0" style="width: 100%;">';
        $html .= '<thead><tr style="background-color: #00008B; color: white;">
                    <th><strong>Ref.No</strong></th>
                    <th><strong>Risk</strong></th>
                    <th><strong>Associated Item</strong></th>
                    <th><strong>Created</strong></th>
                    <th><strong>Status</strong></th>
                    <th><strong>Impact</strong></th>
                    <th><strong>Likelihood</strong></th>
                    <th><strong>Actions</strong></th>
                  </tr></thead>';        
        $html .= '<tbody>';

        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row["ref_no"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["risk"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["associated_item"]) . '</td>';
            $html .= '<td>' . date('d M Y', strtotime($row["created"])) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["status"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["impact"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["likelihood"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["actions"]) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';        
        $pdf->writeHTML($html, true, false, true, false, '');

        ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering
        $pdf->Output('risk_register_report.pdf', 'I');
        exit; // Ensure no further output is sent
    } else {
        echo "<script>alert('No records found.');</script>";
    }

    $stmt->close();
} else {
    echo "Error preparing the SQL statement.";
}

$conn->close();
?>
