<?php
require_once('tcpdf/tcpdf/tcpdf.php');
session_start();

$company_id = $_SESSION['company_id'] ?? null;

// Example of setting a dynamic name (you might get this from session, POST data, etc.)
$dynamic_name = $_SESSION['user_name'] ?? 'Default User'; // Replace with your method of getting the dynamic name

require_once('db.php'); // Ensure your db.php file is correctly set up for database connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category'])) {
    $selectedCategories = $_POST['category'];
    
    // Prepare a statement to prevent SQL injection
    $placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
    $sql = "SELECT category, activity, description FROM dataprocessing WHERE category IN ($placeholders) AND company_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $types = str_repeat('s', count($selectedCategories)); // 's' for string type
        $stmt->bind_param($types, ...$selectedCategories);
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            
            // Set dynamic header
            $pdf->SetHeaderData('images/logi.jpeg', 0, 'Data Processing Activities Report', 'By ' . $dynamic_name);

            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor($dynamic_name); // You can also use dynamic name here if needed
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
            $pdf->SetFont('helvetica', '', 12);
            
            $html = '<h2>Data Processing Activities by Categories of Data Subject</h2>';
            $html .= '<table border="1" cellpadding="4">';
            $html .= '<thead><tr><th>Category</th><th>Activity</th><th>Description</th></tr></thead>';
            $html .= '<tbody>';
            
            while ($row = $result->fetch_assoc()) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($row["category"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["activity"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["description"]) . '</td>';
                $html .= '</tr>';
            }
            
            $html .= '</tbody></table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            $pdf->Output('data_processing_report.pdf', 'I');
        } else {
            echo "0 results";
        }
        
        $stmt->close();
    } else {
        echo "Error preparing the SQL statement.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Processing Activities Report</title>
</head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <h2>Data Processing Activities by Categories of Data Subject</h2>
    <h3>Report Details</h3>
    <p>How would you like to filter the report?</p>
    <h4>Categories of Data Subject</h4>
    <label><input type="checkbox" name="category[]" value="Current Employees"> Current Employees</label><br>
    <label><input type="checkbox" name="category[]" value="Customers"> Customers</label><br>
    <label><input type="checkbox" name="category[]" value="Former Employees"> Former Employees</label><br>
    <label><input type="checkbox" name="category[]" value="Job Candidates"> Job Candidates</label><br>
    <label><input type="checkbox" name="category[]" value="Shift Workers"> Shift Workers</label><br>
    <label><input type="checkbox" name="category[]" value="Students"> Students</label><br>
    <label><input type="checkbox" name="category[]" value="Suppliers"> Suppliers</label><br>
    <br>
    <input type="submit" value="Preview">
</form>
</body>
</html>
