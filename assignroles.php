<?php
// Assuming you have already established a database connection
require 'PHPMailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $new_role_id = $_POST['new_role_id'];
    $user_email = $_POST['user_email'];

    // Get role name from Roles table
    $role_query = "SELECT Role FROM roles WHERE id = ?";
    $role_stmt = $conn->prepare($role_query);
    $role_stmt->bind_param("i", $new_role_id);
    $role_stmt->execute();
    $role_result = $role_stmt->get_result();
    $role_name = $role_result->fetch_assoc()['Role'];
    $role_stmt->close();

    $update_sql = "UPDATE users SET role_id = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $new_role_id, $user_id);
    
    if ($stmt->execute()) {
        // Send email notification
        $adminmail = 'seducv.seducv@gmail.com';
        $mailpassword = 'kvps rjys jafc eodp';

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
            $mail->addAddress($user_email, 'User');

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Role Updated';
            
            $message = "The role has been updated for user ID: $user_id<br><br>";
            $message .= "New Role: $role_name (ID: $new_role_id)<br><br>";
            $message .= "This role has been successfully updated in the user's account.";
            
            $mail->Body    = nl2br($message);                
            $mail->AltBody = strip_tags($message); 

            $mail->send();
            echo "<script>alert('Role updated successfully and notification email sent using SMTP.'); window.location.href = 'admin.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Role updated successfully but failed to send notification email using SMTP. Error: {$mail->ErrorInfo}'); window.location.href = 'admin.php';</script>";
        }
    } else {
        echo "<script>alert('Error updating role: " . $conn->error . "'); window.location.href = 'admin.php';</script>";
    }
    $stmt->close();
}

$conn->close();
