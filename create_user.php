<?php

require 'PHPMailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include 'db.php';



// Retrieve parameters from POST
$id = $_POST['id'];
$username = $_POST['username'];
$password = $_POST['password'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$institutional_email = $_POST['institutional_email'];
$identification_number = $_POST['identification_number'];
$faculty_id = $_POST['faculty_id'];
$created_at = $_POST['created_at'];
$role_id = $_POST['role_id'];
$school_id = $_POST['school_id'];
$phone = $_POST['phone'];

$adminmail ='seducv.seducv@gmail.com';
$mailpassword = 'kvps rjys jafc eodp';

// Set updated_at to current time
$updated_at = date('Y-m-d H:i:s');

// Password validation function
function validatePassword($password) {
    $min_length = 8; // Minimum length
    $has_uppercase = preg_match('/[A-Z]/', $password);
    $has_number = preg_match('/\d/', $password);
    $has_special_char = preg_match('/[\W_]/', $password);

    if (strlen($password) < $min_length) {
        return 'Password must be at least ' . $min_length . ' characters long.';
    } elseif (!$has_uppercase) {
        return 'Password must contain at least one uppercase letter.';
    } elseif (!$has_number) {
        return 'Password must contain at least one number.';
    } elseif (!$has_special_char) {
        return 'Password must contain at least one special character.';
    }
    return null; // Password is valid
}

// Check password validity
$password_error = validatePassword($password);
if ($password_error) {
    echo "<script>alert('$password_error'); window.location.href = 'admin.php';</script>";
    exit();
}

// Moodle API configuration
$moodle_url = 'https://informaticajv.net/prueba/webservice/rest/server.php';
$token = 'aaa9b3ecc791044b0bd74c009882b074';
$function = 'core_user_create_users';

// Prepare the user data for Moodle API
$user = [
    'username' => $username,
    'password' => $password,
    'firstname' => $first_name,
    'lastname' => $last_name,
    'email' => $email,
    'auth' => 'manual',
    'idnumber' => $identification_number,
    'phone1' => $phone,
    'city' => 'YourCity', // Comment out if optional
    'country' => 'YourCountry' // Comment out if optional
];


$params = [
    'wstoken' => $token,
    'wsfunction' => $function,
    'moodlewsrestformat' => 'json',
    'users' => [$user]
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
echo "<script>console.log(" . json_encode($response_data) . ");</script>";
if (isset($response_data['exception'])) {
    echo "<script>alert('Error: " . addslashes($response_data['message']) . "');</script>";
    echo "<script>console.log(" . json_encode($response_data) . ");</script>";
} else {
    echo "<script>console.log(" . json_encode($response_data) . ");</script>";

    // Extract Moodle user ID from response
    $moodle_user_id = $response_data[0]['id'] ?? null;

    // Update validation status, updated_at, and moodle_id in the database
    if ($moodle_user_id) {
        $update_query = "UPDATE users SET Validation = 'validated', Updated_At = '$updated_at', Moodle_Id = '$moodle_user_id' WHERE Id = $id";
        
        $insert_log_query = "INSERT INTO logs (Action, Date, User_Id) VALUES ('Create User', CURRENT_TIMESTAMP, $id)";
       
        if (mysqli_query($conn, $update_query) && mysqli_query($conn, $insert_log_query)) {
            // Use SMTP to send email
           
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = $adminmail; // Replace with your SMTP username
                $mail->Password   = $mailpassword; // Replace with your SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom($adminmail, 'Admin Team');
                $mail->addAddress($user['email'], $user['firstname']);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Account verified successfully';
                
                $message = "Your account has been verified successfully. Your Professor account have been created in the Moodle app. You can now login at <a href='tcs_login.php'>tcs_login.php</a>";
                $mail->Body    = nl2br($message);                
                $mail->AltBody = $message;

                $mail->send();
                echo "<script>alert('Validation email sent successfully using SMTP.');</script>";
            } catch (Exception $e) {
                echo "<script>alert('Failed to send validation email using SMTP. Error: {$mail->ErrorInfo}');</script>";
            }
            echo "<script>alert('User created successfully!'); window.location.href = 'admin.php';</script>";

            exit();
        } else {
            echo "Error updating validation status, update time, and Moodle ID: " . mysqli_error($conn);
        }
    } else {
        echo "Moodle user ID was not returned in the response.";
    }
}

