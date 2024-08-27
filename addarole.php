<?php
// Include database connection
include 'db.php';

// Include PHPMailer
require 'PHPMailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the role from the form
    $role = $_POST['Role'];

    // Prepare an SQL statement
    $sql = "INSERT INTO roles (Role) VALUES (?)";

    // Create a prepared statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("s", $role);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New role added successfully";

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

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'New Role Added';
            
            $message = "A new role has been added:<br><br>";
            $message .= "Role: $role<br><br>";
            $message .= "This role has been successfully added to the system.";
            
            $mail->Body    = nl2br($message);                
            $mail->AltBody = strip_tags($message); 

            $mail->send();
            echo "<script>alert('Role addition notification email sent successfully using SMTP.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Failed to send role addition notification email using SMTP. Error: {$mail->ErrorInfo}');</script>";
        }

        // Redirect to admin.php
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
