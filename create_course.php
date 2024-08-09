<?php

require 'PHPMailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include 'db.php';

// Retrieve parameters from POST
$applicant_id = isset($_POST["applicant_id"]) ? $_POST["applicant_id"] : null;
$request_id = $_POST["request_id"];
$category_name = $_POST["Category_Name"];
$period_name = $_POST["Period_Name"];
$coursename_type = $_POST["name_course"];
$applicant_email = $_POST['applicant_email'];
$coordinator_email = $_POST['coordinator_email'];
$id = $_POST['id'];
$name = $_POST['name'];
$category_id = $_POST['category_id'];
$period_id = $_POST['period_id'];
$type_of_course_id = $_POST['type_of_course_id'];
$descriptive_synthesis = $_POST['descriptive_synthesis'];
$development_competencies = $_POST['development_competencies'];
$content_structure = $_POST['content_structure'];
$teaching_strategies = $_POST['teaching_strategies'];
$technology_tools = $_POST['technology_tools'];
$assessment_strategies = $_POST['assessment_strategies'];
$programmatic_synopsis = $_POST['programmatic_synopsis'];

$adminmail = 'seducv.seducv@gmail.com';
$mailpassword = 'kvps rjys jafc eodp';

// Set created_at to current time
$created_at = date('Y-m-d H:i:s');

// Moodle API configuration
$moodle_url = 'https://informaticajv.net/prueba/webservice/rest/server.php';
$token = 'aaa9b3ecc791044b0bd74c009882b074';
$function = 'core_course_create_courses';

function convert_to_timestamp($date) {
    return strtotime($date);
}

$course = array(
    array(
        'fullname' => $name,
        'shortname' => $name,
        'categoryid' => $category_id,
        'startdate' => convert_to_timestamp(date('Y-m-d')),
        'enddate' => convert_to_timestamp(date('Y-m-d', strtotime('+6 months'))),
        'idnumber' => $id,
        'summary' => $descriptive_synthesis,
        'format' => 'topics',
        'numsections' => 4,
    ),
);

// courses[0][fullname]= string
// courses[0][shortname]= string
// courses[0][categoryid]= int
// courses[0][idnumber]= string
// courses[0][summary]= string
// courses[0][summaryformat]= int
// courses[0][format]= string
// courses[0][showgrades]= int
// courses[0][newsitems]= int
// courses[0][startdate]= int
// courses[0][enddate]= int
// courses[0][numsections]= int
// courses[0][maxbytes]= int
// courses[0][showreports]= int
// courses[0][visible]= int
// courses[0][hiddensections]= int
// courses[0][groupmode]= int
// courses[0][groupmodeforce]= int
// courses[0][defaultgroupingid]= int
// courses[0][enablecompletion]= int
// courses[0][completionnotify]= int
// courses[0][lang]= string
// courses[0][forcetheme]= string
// courses[0][courseformatoptions][0][name]= string
// courses[0][courseformatoptions][0][value]= string
// courses[0][customfields][0][shortname]= string
// courses[0][customfields][0][value]= string

$params = [
    'wstoken' => $token,
    'wsfunction' => $function,
    'moodlewsrestformat' => 'json'
];

foreach ($course as $index => $course) {
    foreach ($course as $key => $value) {
        $params["courses[$index][$key]"] = $value;
    }
}

// Debugging: Output parameters
echo "<pre>";
print_r($params);
echo "</pre>";

// Make the API request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $moodle_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Decode the response
$response_data = json_decode($response, true);

if (isset($response_data['exception'])) {
    echo "<script>alert('Error: " . addslashes($response_data['message']) . "');</script>";
} else {
    echo "<script>console.log(" . json_encode($response_data) . ");</script>";

    // Extract Moodle course ID from response
    $moodle_course_id = $response_data[0]['id'] ?? null;

    // Insert course into the database
    if ($moodle_course_id) {
        $update_request_query = "UPDATE requests SET Status_Id = 4 WHERE id = $request_id";
        
        $insert_log_query = "INSERT INTO logs (Action, Date, User_Id) VALUES ('Course created by admin', CURRENT_TIMESTAMP, " . ($applicant_id ? $applicant_id : 'NULL') . ")";
       
        if (mysqli_query($conn, $update_request_query) && mysqli_query($conn, $insert_log_query)) {
            // Use SMTP to send email
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = $adminmail;
                $mail->Password   = $mailpassword;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom($adminmail, 'Admin Team');
                $mail->addAddress($adminmail, 'Admin');
                $mail->addAddress($coordinator_email, 'Coordinator');
                $mail->addAddress($applicant_email, 'Applicant');

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'New Course Created';
                
                $message = "A new course has been created: $name<br><br>";
                $message .= "Category Name: $category_name<br>";
                $message .= "Period Name: $period_name<br>";
                $message .= "Course Name Type: $coursename_type<br><br>";
                $message .= "The approved category name has been successfully created.";
                
                $mail->Body    = nl2br($message);                
                $mail->AltBody = strip_tags($message); 

                $mail->send();
                echo "<script>alert('Course creation notification email sent successfully to admin, coordinator, and applicant using SMTP.');</script>";
            } catch (Exception $e) {
                echo "<script>alert('Failed to send course creation notification email using SMTP. Error: {$mail->ErrorInfo}');</script>";
            }
            echo "<script>alert('Course created successfully!'); window.location.href = 'admin.php';</script>";

            exit();
        } else {
            echo "Error updating request or inserting log: " . mysqli_error($conn);
        }
    } else {
        echo "Moodle course ID was not returned in the response.";
    }
}
?>
