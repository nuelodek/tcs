<?php
include 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


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
            WHERE r.Status_Id = 3 AND r.Applicant_Id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$courses = $result->fetch_all(MYSQLI_ASSOC);

echo "<h2>Rejected Courses</h2>";

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
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No approved courses found.</p>";
}

$stmt->close();
$conn->close();
?>
