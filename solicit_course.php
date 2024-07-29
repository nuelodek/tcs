<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $period = $_POST['period'];
    $type_of_course_id = $_POST['type_of_course_id'];
    $descriptive_synthesis = $_POST['descriptive_synthesis'];
    $development_competencies = $_POST['development_competencies'];
    $content_structure = $_POST['content_structure'];
    $teaching_strategies = $_POST['teaching_strategies'];
    $technology_tools = $_POST['technology_tools'];
    $assessment_strategies = $_POST['assessment_strategies'];
    $programmatic_synopsis = $_POST['programmatic_synopsis'];
        // Handle the 'Others' category
        if ($category_id == 'others') {
            $new_category = $_POST['otherCategory'];
        
            // Insert the new category into the categories table
            $insert_category_sql = "INSERT INTO categories (name, status) VALUES (?, 'pending')";
            $insert_category_stmt = $conn->prepare($insert_category_sql);
            $insert_category_stmt->bind_param("s", $new_category);
        
            if ($insert_category_stmt->execute()) {
                $category_id = $conn->insert_id;
            } else {
                echo "Error inserting new category: " . $insert_category_stmt->error;
                exit();
            }
        
            $insert_category_stmt->close();
        }

    $sql = "INSERT INTO courses (category_id, name, period_id, type_of_course_id, descriptive_synthesis, development_competencies, content_structure, teaching_strategies, technology_tools, assessment_strategies, programmatic_synopsis) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiisssssss", $category_id, $name, $period, $type_of_course_id, $descriptive_synthesis, $development_competencies, $content_structure, $teaching_strategies, $technology_tools, $assessment_strategies, $programmatic_synopsis);

    if ($stmt->execute()) {
        echo "New course solicited successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method";
}
?>
