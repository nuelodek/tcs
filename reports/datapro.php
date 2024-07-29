<?php
include '../layouts/session.php'; 
include '../layouts/main.php'; 

// Include the database connection file
require_once '../layouts/config.php';
require_once('tcpdf/tcpdf/tcpdf.php');

// include 'db.php';

$company_id = $_SESSION['company_id'];

// Example of setting a dynamic name (you might get this from session, POST data, etc.)
$dynamic_name = $_SESSION['user_name'] ?? 'Default User'; // Replace with your method of getting the dynamic name

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category'])) {
    $selectedCategories = $_POST['category'];
    
    // Prepare a statement to prevent SQL injection
    $placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
    $sql = "SELECT categories_of_data_subject, activity_name, activity_description FROM ropa WHERE company_id = ? AND categories_of_data_subject IN ($placeholders)";
    
    if ($stmt = $link->prepare($sql)) {
        $types = 's' . str_repeat('s', count($selectedCategories)); // 's' for string type
        $params = array_merge([$company_id], $selectedCategories);
        $stmt->bind_param($types, ...$params);
        
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
                $html .= '<td>' . htmlspecialchars($row["categories_of_data_subject"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["activity_name"]) . '</td>';
                $html .= '<td>' . htmlspecialchars($row["activity_description"]) . '</td>';
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

$link->close();
?>

<!DOCTYPE html>
<html lang="en">
<?php includeFileWithVariables('../layouts/title-meta.php', array('title' => 'Data Processing Activities by Categories of Data Subject')); ?>
<?php include '../layouts/head-css.php'; ?>
<link rel="icon" type="image/x-icon" href="../../../img/core-img/icon--with-bgpng.png">
</head>
<body>

<!-- Begin page -->
<div id="layout-wrapper">
  <?php include '../layouts/menu.php'; ?>
  <!-- ============================================================== -->
  <!-- Start right Content here -->
  <!-- ============================================================== -->
  <div class="main-content">
    <div class="page-content">
      <div class="container-fluid">
        <?php includeFileWithVariables('../layouts/page-title.php', array('pagetitle' => 'REPORTS', 'title' => 'RoPA by Categories of Data Subject')); ?>
         <div class="row g-4 mb-3 align-items-center" style="padding-left:60px; padding-right: 80px ">

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
<!--end of insertable content area-->
        </div>
        <!--end row-->
      </div>
      <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <?php include '../layouts/footer.php'; ?>
  </div>
  <!-- end main content-->
</div>
<!-- END layout-wrapper -->
<?php include '../layouts/vendor-scripts.php'; ?>
<!-- App js -->
<script src="../assets/js/app.js"></script>
</body>

</html>
