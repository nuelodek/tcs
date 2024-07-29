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

// Prepare a statement to prevent SQL injection
$sql = "SELECT id, useremail, username, first_name, last_name, phone_number, active, role, created_at, updated_at
        FROM users
        WHERE company_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set dynamic header
        $pdf->SetHeaderData('images/logi.jpeg', 0, 'User Report', 'By ' . $dynamic_name);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($dynamic_name);
        $pdf->SetTitle('User Report');
        $pdf->SetSubject('Report');
        $pdf->SetKeywords('TCPDF, PDF, report, user');

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
                    <th><strong>ID</strong></th>
                    <th><strong>Email</strong></th>
                    <th><strong>Username</strong></th>
                    <th><strong>First Name</strong></th>
                    <th><strong>Last Name</strong></th>
                    <th><strong>Phone Number</strong></th>
                    <th><strong>Active</strong></th>
                    <th><strong>Role</strong></th>
                    <th><strong>Created At</strong></th>
                    <th><strong>Updated At</strong></th>
                  </tr></thead>';        
        $html .= '<tbody>';

        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row["id"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["useremail"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["username"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["first_name"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["last_name"]) . '</td>';
            $html .= '<td>' . htmlspecialchars($row["phone_number"]) . '</td>';
            $html .= '<td>' . ($row["active"] ? 'Active' : 'Inactive') . '</td>';
            $html .= '<td>' . htmlspecialchars($row["role"]) . '</td>';
            $html .= '<td>' . date('d M Y H:i', strtotime($row["created_at"])) . '</td>';
            $html .= '<td>' . date('d M Y H:i', strtotime($row["updated_at"])) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';        
        $pdf->writeHTML($html, true, false, true, false, '');

        ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering
        $pdf->Output('user_report.pdf', 'I');
        exit; // Ensure no further output is sent
    } else {
        echo "<script>alert('No user records found.');</script>";
    }

    $stmt->close();
} else {
    echo "Error preparing the SQL statement.";
}

$conn->close();
?>
