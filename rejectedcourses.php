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
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Action</th>";
  
  
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
                echo "<button onclick='openAppealModal(" . json_encode($course) . ")' style='padding: 6px 12px; background-color: #4CAF50; color: white; border: none; cursor: pointer;'>Appeal</button>";
                echo "</td>";

// Add this at the end of the file, after the table
echo "<div id='appealModal' class='modal' style='display:none; position:fixed; z-index:1; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.4);'>";
echo "<div class='modal-content' style='background-color:#fefefe; margin:15% auto; padding:20px; border:1px solid #888; width:80%;'>";
echo "<span class='close' onclick='closeAppealModal()' style='color:#aaa; float:right; font-size:28px; font-weight:bold; cursor:pointer;'>×</span>";
echo "<h2>Appeal Course Rejection</h2>";
echo "<form id='appealForm' method='post' action='appealcourses.php'>";
echo "<input type='hidden' id='appeal_course_id' name='Id'>";
echo "<label for='Name'>Course Name:</label>";
echo "<input type='text' id='Name' name='Name'><br><br>";
echo "<label for='Category_Name'>Category:</label>";
echo "<input type='text' id='Category_Name' name='Category_Name'><br><br>";
echo "<label for='Period_Name'>Period:</label>";
echo "<input type='text' id='Period_Name' name='Period_Name'><br><br>";
echo "<label for='Type_of_Course_Name'>Type of Course:</label>";
echo "<input type='text' id='Type_of_Course_Name' name='Type_of_Course_Name'><br><br>";
echo "<label for='Descriptive_Synthesis'>Descriptive Synthesis:</label>";
echo "<input type='text' id='Descriptive_Synthesis' name='Descriptive_Synthesis'><br><br>";
echo "<label for='Development_Competencies'>Development Competencies:</label>";
echo "<input type='text' id='Development_Competencies' name='Development_Competencies'><br><br>";
echo "<label for='Content_Structure'>Content Structure:</label>";
echo "<input type='text' id='Content_Structure' name='Content_Structure'><br><br>";
echo "<label for='Teaching_Strategies'>Teaching Strategies:</label>";
echo "<input type='text' id='Teaching_Strategies' name='Teaching_Strategies'><br><br>";
echo "<label for='Technology_Tools'>Technology Tools:</label>";
echo "<input type='text' id='Technology_Tools' name='Technology_Tools'><br><br>";
echo "<label for='Assessment_Strategies'>Assessment Strategies:</label>";
echo "<input type='text' id='Assessment_Strategies' name='Assessment_Strategies'><br><br>";
echo "<label for='Programmatic_Synopsis'>Programmatic Synopsis:</label>";
echo "<input type='text' id='Programmatic_Synopsis' name='Programmatic_Synopsis'><br><br>";
echo "<button type='submit' style='margin-top:10px; padding:6px 12px; background-color:#4CAF50; color:white; border:none; cursor:pointer;'>Submit Appeal</button>";
echo "</form>";
echo "</div>";
echo "</div>";

echo "<script>
function openAppealModal(course) {
        document.getElementById('appealModal').style.display = 'block';
        document.getElementById('appeal_course_id').value = course.Id;
        document.getElementById('Name').value = course.Name;
        document.getElementById('Category_Name').value = course.Category_Name;
        document.getElementById('Period_Name').value = course.Period_Name;
        document.getElementById('Type_of_Course_Name').value = course.Type_of_Course_Name;
        document.getElementById('Descriptive_Synthesis').value = course.Descriptive_Synthesis;
        document.getElementById('Development_Competencies').value = course.Development_Competencies;
        document.getElementById('Content_Structure').value = course.Content_Structure;
        document.getElementById('Teaching_Strategies').value = course.Teaching_Strategies;
        document.getElementById('Technology_Tools').value = course.Technology_Tools;
        document.getElementById('Assessment_Strategies').value = course.Assessment_Strategies;
        document.getElementById('Programmatic_Synopsis').value = course.Programmatic_Synopsis;
}

function closeAppealModal() {
        document.getElementById('appealModal').style.display = 'none';
}
</script>";
        
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
