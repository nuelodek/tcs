
<?php
include 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['Id'];
    $name = $_POST['Name'];
    $category_name = $_POST['Category_Name'];
    $period_name = $_POST['Period_Name'];
    $type_of_course_name = $_POST['Type_of_Course_Name'];
    $descriptive_synthesis = $_POST['Descriptive_Synthesis'];
    $development_competencies = $_POST['Development_Competencies'];
    $content_structure = $_POST['Content_Structure'];
    $teaching_strategies = $_POST['Teaching_Strategies'];
    $technology_tools = $_POST['Technology_Tools'];
    $assessment_strategies = $_POST['Assessment_Strategies'];
    $programmatic_synopsis = $_POST['Programmatic_Synopsis'];

    // Update courses table
    $update_course_sql = "UPDATE courses SET 
        Name = ?, 
        Descriptive_Synthesis = ?, 
        Development_Competencies = ?, 
        Content_Structure = ?, 
        Teaching_Strategies = ?, 
        Technology_Tools = ?, 
        Assessment_Strategies = ?, 
        Programmatic_Synopsis = ? 
        WHERE Id = ?";

    $stmt = $conn->prepare($update_course_sql);
    $stmt->bind_param("ssssssssi", $name, $descriptive_synthesis, $development_competencies, $content_structure, $teaching_strategies, $technology_tools, $assessment_strategies, $programmatic_synopsis, $course_id);
    $stmt->execute();

    // Update requests table
    $update_request_sql = "UPDATE requests SET 
        Status_Id = 1, 
        rejection_reason = 'Appealed', 
        Date_Update = NOW() 
        WHERE Course_Id = ? AND Status_Id = 3";

    $stmt = $conn->prepare($update_request_sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Course appeal submitted successfully.";
    } else {
        echo "Error submitting appeal. Please try again.";
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
