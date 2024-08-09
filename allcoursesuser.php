<?php
include 'db.php';


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filter values
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT c.Id, c.Name, cat.Name AS Category_Name, p.Name AS Period_Name, 
            CONCAT(toc.Name, ' - ', toc.Field) AS Type_of_Course_Name, 
            c.Descriptive_Synthesis, c.Development_Competencies, c.Content_Structure, 
            c.Teaching_Strategies, c.Technology_Tools, c.Assessment_Strategies, 
            c.Programmatic_Synopsis, r.Status_Id, r.Applicant_Id, r.Coordinator_Id
            FROM courses c
            JOIN categories cat ON c.Category_Id = cat.Id
            JOIN periods p ON c.Period_Id = p.Id
            JOIN types_of_courses toc ON c.Type_of_Course_Id = toc.Id
            LEFT JOIN requests r ON c.Id = r.Course_Id
            WHERE r.Applicant_Id = ?";

$params = array($user_id);

if (!empty($category_filter)) {
        $sql .= " AND cat.Id = ?";
        $params[] = $category_filter;
}

if (!empty($status_filter)) {
        $sql .= " AND r.Status_Id = ?";
        $params[] = $status_filter;
}

if (!empty($search_query)) {
        $sql .= " AND (c.Name LIKE ? OR c.Descriptive_Synthesis LIKE ?)";
        $search_param = "%$search_query%";
        $params[] = $search_param;
        $params[] = $search_param;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();
$courses = $result->fetch_all(MYSQLI_ASSOC);

// Fetch categories for the filter dropdown
$categories_sql = "SELECT Id, Name FROM categories";
$categories_result = $conn->query($categories_sql);

echo "<h2>My Courses</h2>";

// Filter form
echo "<form method='get'>";
echo "<label for='category'>Filter by Category:</label>";
echo "<select name='category' id='category'>";
echo "<option value=''>All Categories</option>";
while ($category = $categories_result->fetch_assoc()) {
        $selected = ($category['Id'] == $category_filter) ? 'selected' : '';
        echo "<option value='" . $category['Id'] . "' $selected>" . $category['Name'] . "</option>";
}
echo "</select>";

echo "<label for='status'>Filter by Status:</label>";
echo "<select name='status' id='status'>";
echo "<option value=''>All Statuses</option>";
echo "<option value='1' " . ($status_filter == '1' ? 'selected' : '') . ">Request Submitted</option>";
echo "<option value='2' " . ($status_filter == '2' ? 'selected' : '') . ">Request Approved</option>";
echo "<option value='3' " . ($status_filter == '3' ? 'selected' : '') . ">Request Rejected</option>";
echo "<option value='4' " . ($status_filter == '4' ? 'selected' : '') . ">Request Created</option>";
echo "</select>";

echo "<label for='search'>Search:</label>";
echo "<input type='text' name='search' id='search' value='" . htmlspecialchars($search_query) . "'>";

echo "<input type='submit' value='Filter'>";
echo "</form>";

if (count($courses) > 0) {
        echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
        echo "<thead>";
        echo "<tr style='background-color: #f2f2f2;'>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>ID</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Name</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Category</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Period</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Type of Course</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Descriptive Synthesis</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Development Competencies</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Content Structure</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Teaching Strategies</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Technology Tools</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Assessment Strategies</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Programmatic Synopsis</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Status</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($courses as $course) {
            echo "<tr style='border-bottom: 1px solid #ddd;'>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Id"]) . "</td>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Name"]) . "</td>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Category_Name"]) . "</td>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Period_Name"]) . "</td>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Type_of_Course_Name"]) . "</td>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Descriptive_Synthesis"]) . "</td>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Development_Competencies"]) . "</td>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Content_Structure"]) . "</td>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Teaching_Strategies"]) . "</td>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Technology_Tools"]) . "</td>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Assessment_Strategies"]) . "</td>";
            echo "<td style='padding: 12px;'>" . htmlspecialchars($course["Programmatic_Synopsis"]) . "</td>";
            echo "<td style='padding: 12px;'>";
            switch ($course["Status_Id"]) {
                case 1:
                    echo "Request Submitted";
                    break;
                case 2:
                    echo "Request Approved";
                    break;
                case 3:
                    echo "Request Rejected";
                    break;
                case 4:
                    echo "Request Created";
                    break;
                default:
                    echo "Unknown";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
} else {
        echo "<p>No courses found.</p>";
}
?>