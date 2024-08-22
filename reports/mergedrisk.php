<?php
ob_start(); // Start output buffering
include 'db.php';
require_once('tcpdf/tcpdf/tcpdf.php');

// Example of dynamic report selection (e.g., from POST/GET)
$report_type = $_GET['report_type'] ?? 'risk_register'; // Default to 'risk_register'

// Define company ID and dynamic name
$company_id = 'YNT3574'; // Change to session-based if needed
$dynamic_name = $_SESSION['user_name'] ?? 'Default User'; // Replace with your method

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set dynamic header
$pdf->SetHeaderData('images/logi.jpeg', 0, 'Risk Report', 'By ' . $dynamic_name);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($dynamic_name);
$pdf->SetTitle('Risk Report');
$pdf->SetSubject('Report');
$pdf->SetKeywords('TCPDF, PDF, report, risk');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// Initialize HTML for the PDF content
$date_report_generated = date('Y-m-d H:i:s');
$html = '<p><strong>Date Report Generated: ' . htmlspecialchars($date_report_generated) . '</strong></p>';

// Switch between different reports based on the type
switch ($report_type) {

    case 'risk_register':
        // Risk Register
        $sql = "
            SELECT 
                r.reference_number AS ref_no,
                r.risk_title AS risk,
                r.risk_association AS associated_item,
                r.created_at AS created,
                r.status AS status,
                r.risk_impact AS impact,
                r.risk_likelihood AS likelihood,
                GROUP_CONCAT(ra.action_status SEPARATOR ', ') AS actions
            FROM risk_info r
            LEFT JOIN risk_action ra ON r.reference_number = ra.reference_number
            WHERE r.company_id = ?
            GROUP BY r.reference_number
        ";
        break;
      case 'risk_actions_completed':
          // Risk Actions Completed Between Dates (Modify the dates dynamically)
          $start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
          $end_date = $_GET['end_date'] ?? date('Y-m-d');      
          $sql = "
              SELECT 
                  r.reference_number AS ref_no,
                  r.risk_title AS risk,
                  ra.action_status AS action_status,
                  ra.updated_at AS completed
              FROM risk_info r
              INNER JOIN risk_action ra ON r.reference_number = ra.reference_number
              WHERE r.company_id = ?
              AND ra.updated_at BETWEEN ? AND ?
              AND ra.action_status = 'Completed'
          ";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("sss", $company_id, $start_date, $end_date);
          break;        
    case 'risk_data_champions':
        // Risk Data Champions
        $assign_to = $_GET['assign_to'] ?? '';        
        $start_date = $_GET['start_date'] ?? '';
        $end_date = $_GET['end_date'] ?? ''; 

        $sql = "
            SELECT 
                ra.id,
                ra.company_id,
                ra.reference_number AS ref_no,
                ra.risk_title AS risk,
                ra.risk_action AS associated_item,
                ra.assign_to AS assigned_to,
                ra.due_date,
                ra.action_status AS status,
                ra.created_at AS created,
                ra.updated_at
            FROM risk_action ra
            WHERE ra.company_id = ?
            AND (? = '' OR ra.assign_to = ?)
            AND DATE(ra.created_at) BETWEEN ? AND ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $company_id, $assign_to, $assign_to, $start_date, $end_date);
        break;
        
    case 'risk_profile_summary':
        // Risk Profile Summary
        $sql = "
            SELECT 
                COUNT(*) AS total_risks,
                SUM(r.risk_impact) AS total_impact,
                AVG(r.risk_likelihood) AS avg_likelihood
            FROM risk_info r
            WHERE r.company_id = ?
        ";
        break;

      case 'risk_profile_detail':
          // Risk Profile Detail
          $start_date = $_GET['start_date'] ?? '';
          $end_date = $_GET['end_date'] ?? '';
          $sql = "
              SELECT 
                  r.reference_number AS ref_no,
                  r.risk_title AS risk,
                  r.risk_association AS associated_item,
                  r.risk_impact AS impact,
                  r.risk_likelihood AS likelihood,
                  r.status AS status,
                  r.created_at AS created,
                  r.updated_at AS last_updated
              FROM risk_info r
              WHERE r.company_id = ?
              AND r.created_at BETWEEN ? AND ?
              ORDER BY r.risk_impact * r.risk_likelihood DESC
          ";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("sss", $company_id, $start_date, $end_date);
          break;

    case 'risk_by_keyword':
        // Risk by Keyword
        $keyword = $_GET['keyword'] ?? '';
        $sql = "
            SELECT 
                r.reference_number AS ref_no,
                r.risk_title AS risk,
                r.risk_association AS associated_item,
                r.created_at AS created,
                r.status AS status,
                r.risk_impact AS impact,
                r.risk_likelihood AS likelihood
            FROM risk_info r
            WHERE r.company_id = ?
            AND (r.risk_title LIKE ? OR r.description LIKE ?)
        ";
        $stmt = $conn->prepare($sql);
        $keyword_param = "%$keyword%";
        $stmt->bind_param("sss", $company_id, $keyword_param, $keyword_param);
        break;

    case 'actions_not_completed':
        // Actions Not Completed
        $sql = "
            SELECT 
                r.reference_number AS ref_no,
                r.risk_title AS risk,
                ra.risk_action AS action,
                ra.due_date AS due_date,
                ra.action_status AS status
            FROM risk_info r
            INNER JOIN risk_action ra ON r.reference_number = ra.reference_number
            WHERE r.company_id = ?
            AND ra.action_status != 'Completed'
            ORDER BY ra.due_date ASC
        ";
        break;

    // Add more cases for other report types as needed

}

// Prepare and execute the SQL statement
if (!isset($stmt)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $company_id);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Add table header based on report type
    $html .= '<table border="1" cellpadding="4" cellspacing="0" style="width: 100%;">';
    $html .= '<thead><tr style="background-color: #00008B; color: white;">';

    // Adjust table columns based on the report
    switch ($report_type) {
        case 'risk_register':
            $html .= '<th><strong>Ref.No</strong></th><th><strong>Risk</strong></th><th><strong>Associated Item</strong></th><th><strong>Created</strong></th><th><strong>Status</strong></th><th><strong>Impact</strong></th><th><strong>Likelihood</strong></th><th><strong>Actions</strong></th>';
            break;
        
        case 'risk_actions_completed':
            $html .= '<th><strong>Ref.No</strong></th><th><strong>Risk</strong></th><th><strong>Action Status</strong></th><th><strong>Completed</strong></th>';
            break;
        
        case 'risk_profile_summary':
            $html .= '<th><strong>Total Risks</strong></th><th><strong>Total Impact</strong></th><th><strong>Average Likelihood</strong></th>';
            break;

     case 'risk_profile_detail':
                $html .= '<th><strong>Ref.No</strong></th><th><strong>Risk</strong></th><th><strong>Associated Item</strong></th><th><strong>Impact</strong></th><th><strong>Likelihood</strong></th><th><strong>Status</strong></th><th><strong>Created</strong></th><th><strong>Last Updated</strong></th>';
                break;
                
        case 'risk_data_champions':
            $html .= '<th><strong>ID</strong></th><th><strong>Company ID</strong></th><th><strong>Ref.No</strong></th><th><strong>Risk</strong></th><th><strong>Associated Item</strong></th><th><strong>Assigned To</strong></th><th><strong>Due Date</strong></th><th><strong>Status</strong></th><th><strong>Created</strong></th><th><strong>Updated At</strong></th>';
            break;

        case 'risk_by_keyword':
            $html .= '<th><strong>Ref.No</strong></th><th><strong>Risk</strong></th><th><strong>Associated Item</strong></th><th><strong>Created</strong></th><th><strong>Status</strong></th><th><strong>Impact</strong></th><th><strong>Likelihood</strong></th>';
            break;

        case 'actions_not_completed':
            $html .= '<th><strong>Ref.No</strong></th><th><strong>Risk</strong></th><th><strong>Action</strong></th><th><strong>Due Date</strong></th><th><strong>Status</strong></th>';
            break;

    }
    $html .= '</tr></thead><tbody>';

    // Fetch and display data rows
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        switch ($report_type) {
            case 'risk_register':
                $html .= '<td>' . htmlspecialchars($row["ref_no"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["risk"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["associated_item"]) . '</td>';
                $html .= '<td>' . date('d M Y', strtotime($row["created"])) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["status"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["impact"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["likelihood"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["actions"]) . '</td>';
                break;

            case 'risk_actions_completed':
                $html .= '<td>' . htmlspecialchars($row["ref_no"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["risk"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["action_status"]) . '</td>';
                $html .= '<td>' . date('d M Y', strtotime($row["completed"])) . '</td>';
                break;

              case 'risk_data_champions':
                  $html .= '<td>' . htmlspecialchars($row["id"]) . '</td>';
                  $html .= '<td>' . htmlspecialchars($row["company_id"]) . '</td>';
                  $html .= '<td>' . htmlspecialchars($row["ref_no"]) . '</td>';
                  $html .= '<td>' . htmlspecialchars($row["risk"]) . '</td>';
                  $html .= '<td>' . htmlspecialchars($row["associated_item"]) . '</td>';
                  $html .= '<td>' . htmlspecialchars($row["assigned_to"]) . '</td>';
                  $html .= '<td>' . htmlspecialchars($row["due_date"]) . '</td>';
                  $html .= '<td>' . htmlspecialchars($row["status"]) . '</td>';
                  $html .= '<td>' . htmlspecialchars($row["created"]) . '</td>';
                  $html .= '<td>' . htmlspecialchars($row["updated_at"]) . '</td>';
                  break;

            case 'risk_profile_summary':
                $html .= '<td>' . htmlspecialchars($row["total_risks"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["total_impact"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["avg_likelihood"]) . '</td>';
                break;
    
            case 'risk_profile_detail':
                $html .= '<td>' . htmlspecialchars($row["ref_no"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["risk"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["associated_item"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["impact"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["likelihood"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["status"]) . '</td>';
                $html .= '<td>' . date('d M Y', strtotime($row["created"])) . '</td>';
                $html .= '<td>' . date('d M Y', strtotime($row["last_updated"])) . '</td>';
                break;

            case 'risk_by_keyword':
                $html .= '<td>' . htmlspecialchars($row["ref_no"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["risk"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["associated_item"]) . '</td>';
                $html .= '<td>' . date('d M Y', strtotime($row["created"])) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["status"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["impact"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["likelihood"]) . '</td>';
                break;

            case 'actions_not_completed':
                $html .= '<td>' . htmlspecialchars($row["ref_no"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["risk"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["action"]) . '</td>';
                $html .= '<td>' . date('d M Y', strtotime($row["due_date"])) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["status"]) . '</td>';
                break;

        }
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';

    // Output HTML content to PDF
    $pdf->writeHTML($html, true, false, true, false, '');
} else {
    $html .= '<table border="1" cellpadding="4" cellspacing="0" style="width: 100%;">';
    $html .= '<tr><td style="text-align: center;">No report found</td></tr>';
    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
}

$stmt->close();
$conn->close();

// Output PDF
ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering
$pdf->Output('risk_report.pdf', 'I');
exit; // Ensure no further output is sent
?>
