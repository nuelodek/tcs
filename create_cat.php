<?php

require 'PHPMailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include 'db.php';

// Retrieve parameters from POST
$category_idnumber = $_POST['category_id'] ?? '';
$category_name = $_POST['category_name'] ??'';
$parent_category_id = $_POST['parent_category_id'] ?? 0;
$applicant_email = $_POST['applicant_email'];
$coordinator_email = $_POST['coordinator_email'];
// Retrieve parameters from POST

$adminmail = 'seducv.seducv@gmail.com';
$mailpassword = 'kvps rjys jafc eodp';

// Moodle API configuration
$moodle_url = 'https://informaticajv.net/prueba/webservice/rest/server.php';
$token = 'aaa9b3ecc791044b0bd74c009882b074';
$function = 'core_course_create_categories';

$categories = array(
    array(
        'name' => $category_name,
        'idnumber' => $category_idnumber,
        'parent' => $parent_category_id, // 0 for a top-level category
        'description' => 'Category description', // Optional
        'descriptionformat' => 1 // 1 for HTML, 0 for plain text
    ),
);

$params = [
    'wstoken' => $token,
    'wsfunction' => $function,
    'moodlewsrestformat' => 'json',
    'categories' => $categories
];

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

    // Extract Moodle category ID from response
    $moodle_category_id = $response_data[0]['id'] ?? null;

    // Update category status in the database
    if ($moodle_category_id) {
        $update_query = "UPDATE categories SET status = 'approved' WHERE id = '$category_idnumber'";
       
        if (mysqli_query($conn, $update_query)) {
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
                $mail->Subject = 'Category Status Updated';
                
                $message = "A category status has been updated: $category_name<br><br>";
                $message .= "Category ID Number: $category_idnumber<br>";
                $message .= "Parent Category ID: $parent_category_id<br><br>";
                $message .= "The category status has been updated to 'pending approved' in the database.";
                
                $mail->Body    = nl2br($message);                
                $mail->AltBody = strip_tags($message); 

                $mail->send();
                echo "<script>alert('Category status update notification email sent successfully to admin using SMTP.');</script>";
            } catch (Exception $e) {
                echo "<script>alert('Failed to send category status update notification email using SMTP. Error: {$mail->ErrorInfo}');</script>";
            }
            echo "<script>alert('Category status updated successfully!'); window.location.href = 'admin.php';</script>";

            exit();
        } else {
            echo "Error updating category status: " . mysqli_error($conn);
        }
    } else {
        echo "Moodle category ID was not returned in the response.";
    }
}
?>
