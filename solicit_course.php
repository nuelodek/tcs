<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];
    $applicant_id = $_POST['applicant_id'];
    $cordinator_id = $_POST['cordinator_id'];
    $status_id = 1;
    $requestname = "Course Enrollment Request";
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

    if ($category_id == 'others') {
        $new_category = $_POST['otherCategory'];
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
        $course_id = $conn->insert_id;
        
        $request_sql = "INSERT INTO requests (Name, Applicant_Id, Coordinator_Id, Status_Id, Course_Id, Date_Request) 
                        VALUES (?, ?, ?, ?, ?, NOW())";
        $request_stmt = $conn->prepare($request_sql);
        $request_stmt->bind_param("siiis", $requestname, $applicant_id, $cordinator_id, $status_id, $course_id);

        if ($request_stmt->execute()) {
            echo "New course solicited and request added successfully";

            $log_sql = "INSERT INTO logs (Action, Date, User_Id) VALUES (?, NOW(), ?)";
            $log_stmt = $conn->prepare($log_sql);
            $action = "course solicited";
            $log_stmt->bind_param("si", $action, $applicant_id);

            if ($log_stmt->execute()) {
                echo "Log entry added successfully";
            } else {
                echo "Error adding log entry: " . $log_stmt->error;
            }

            $log_stmt->close();
        } else {
            echo "Error adding request: " . $request_stmt->error;
        }

        $request_stmt->close();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method";
}
?>
