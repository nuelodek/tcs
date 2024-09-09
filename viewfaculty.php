<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files and start session
include "db.php";

// Get admin faculty from session
$school = htmlspecialchars($_SESSION['school'], ENT_QUOTES, 'UTF-8');
$faculty = htmlspecialchars($_SESSION['faculty'], ENT_QUOTES, 'UTF-8');
  
// Get all id of everyone in users table with role_id 2 and 3 with same school_id as admin, grouped by role_id and faculty_id
$query = "SELECT role_id, faculty_id, first_Name, last_Name, school_id FROM users WHERE role_id IN (2, 3) AND school_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $school);
$stmt->execute();
$result = $stmt->get_result();
  
$coordinators = array();
$professors = array();
$faculties = array();
$schools = array();
while ($row = $result->fetch_assoc()) {
    $fullname = $row['first_Name'] . ' ' . $row['last_Name'];
    if ($row['role_id'] == 2) {
        if (!isset($coordinators[$row['faculty_id']])) {
            $coordinators[$row['faculty_id']] = array();
        }
        $coordinators[$row['faculty_id']][] = array(
            'name' => $fullname,
            'school_id' => $row['school_id']
        );
    } else if ($row['role_id'] == 3) {
        if (!isset($professors[$row['faculty_id']])) {
            $professors[$row['faculty_id']] = array();
        }
        $professors[$row['faculty_id']][] = array(
            'name' => $fullname,
            'school_id' => $row['school_id']
        );
    }
    if (!in_array($row['faculty_id'], $faculties)) {
        $faculties[] = $row['faculty_id'];
    }
    if (!in_array($row['school_id'], $schools)) {
        $schools[] = $row['school_id'];
    }
}
  
$stmt->close();

// Get faculty categories
$query = "SELECT id, name FROM faculties WHERE id IN (" . implode(',', $faculties) . ")";
$result = $conn->query($query);
$faculty_categories = array();
while ($row = $result->fetch_assoc()) {
    $faculty_categories[$row['id']] = $row['name'];
}

// Get school names
$query = "SELECT id, name FROM schools WHERE id IN (" . implode(',', $schools) . ")";
$result = $conn->query($query);
$school_names = array();
while ($row = $result->fetch_assoc()) {
    $school_names[$row['id']] = $row['name'];
}

// Fetch categories that match the faculties represented
$query = "SELECT id, name, status, facultyid FROM categories WHERE facultyid IN (" . implode(',', $faculties) . ")";
$result = $conn->query($query);
$categories = array();
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo "<h2>School, Faculty, Category, and Course Overview</h2>";

echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
echo "<tr style='background-color: #f2f2f2;'>";
echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>School</th>";
echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Faculty</th>";
echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Categories</th>";
echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Courses</th>";
echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Coordinators</th>";
echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Professors</th>";
echo "</tr>";

foreach ($school_names as $school_id => $school_name) {
    foreach ($faculty_categories as $faculty_id => $faculty_name) {
        $rowspan = 0;
        foreach ($categories as $category) {
            if ($category['facultyid'] == $faculty_id) {
                $rowspan++;
            }
        }
        
        $first_category = true;
        foreach ($categories as $category) {
            if ($category['facultyid'] == $faculty_id) {
                echo "<tr style='border-bottom: 1px solid #ddd;'>";
                if ($first_category) {
                    echo "<td style='padding: 12px;' rowspan='" . $rowspan . "'>" . $school_name . "</td>";
                    echo "<td style='padding: 12px;' rowspan='" . $rowspan . "'>" . $faculty_name . "</td>";
                    $first_category = false;
                }
                echo "<td style='padding: 12px;'>" . $category['name'] . " (Status: " . $category['status'] . ")</td>";
                echo "<td style='padding: 12px;'>";
                
                // Fetch courses for each category
                $courseQuery = "SELECT c.*, t.Name AS TypeName, t.Field AS TypeField, p.Name AS PeriodName
                                FROM courses c
                                LEFT JOIN types_of_courses t ON c.Type_of_Course_Id = t.Id
                                LEFT JOIN periods p ON c.Period_Id = p.Id
                                WHERE c.category_id = ? 
                                ORDER BY c.name ASC";
                $courseStmt = $conn->prepare($courseQuery);
                $courseStmt->bind_param("i", $category['id']);
                $courseStmt->execute();
                $courseResult = $courseStmt->get_result();
                
                while ($course = $courseResult->fetch_assoc()) {
                    echo "<strong>Course ID: " . $course['Id'] . "</strong><br>";
                    echo "Name: " . $course['Name'] . "<br>";
                    echo "Period: " . $course['PeriodName'] . "<br>";
                    echo "Type: " . $course['TypeName'] . " (" . $course['TypeField'] . ")<br>";
                    echo "Descriptive Synthesis: " . $course['Descriptive_Synthesis'] . "<br>";
                    echo "Development Competencies: " . $course['Development_Competencies'] . "<br>";
                    echo "Content Structure: " . $course['Content_Structure'] . "<br>";
                    echo "Teaching Strategies: " . $course['Teaching_Strategies'] . "<br>";
                    echo "Technology Tools: " . $course['Technology_Tools'] . "<br>";
                    echo "Assessment Strategies: " . $course['Assessment_Strategies'] . "<br>";
                    echo "Programmatic Synopsis: " . $course['Programmatic_Synopsis'] . "<br><br>";
                }
                
                $courseStmt->close();
                
                echo "</td>";
                echo "<td style='padding: 12px;'>";
                if (isset($coordinators[$faculty_id])) {
                    foreach ($coordinators[$faculty_id] as $coordinator) {
                        if ($coordinator['school_id'] == $school_id) {
                            echo $coordinator['name'] . "<br>";
                        }
                    }
                }
                echo "</td>";
                echo "<td style='padding: 12px;'>";
                if (isset($professors[$faculty_id])) {
                    foreach ($professors[$faculty_id] as $professor) {
                        if ($professor['school_id'] == $school_id) {
                            echo $professor['name'] . "<br>";
                        }
                    }
                }
                echo "</td>";
                echo "</tr>";
            }
        }
    }
}

echo "</table>";
