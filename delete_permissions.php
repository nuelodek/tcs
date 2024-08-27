<?php
// Connect to the database
require 'PHPMailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include 'db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id']) && isset($_POST['delete_permission'])) {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $permission = mysqli_real_escape_string($conn, $_POST['delete_permission']);
        
        $sql = "DELETE FROM user_permissions WHERE user_id = '$user_id' AND permission = '$permission'";
        
        if (mysqli_query($conn, $sql)) {
            echo "Permission deleted successfully.";
            
                        // Get user email
                        $user_query = "SELECT email FROM users WHERE id = '$user_id'";
                        $user_result = mysqli_query($conn, $user_query);
                        $user_data = mysqli_fetch_assoc($user_result);
                        $user_email = $user_data['email'];
            
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
                            $mail->Subject = 'Permission Deleted';
                            
                            $message = "A permission has been deleted for user ID: $user_id<br><br>";
                            $message .= "Deleted Permission: $permission<br><br>";
                            $message .= "This permission has been successfully removed from the user's account.";
                            
                            $mail->Body    = nl2br($message);                
                            $mail->AltBody = strip_tags($message); 
            
                            $mail->send();
                            echo "<script>alert('Permission deletion notification email sent successfully using SMTP.');</script>";
                        } catch (Exception $e) {
                            echo "<script>alert('Failed to send permission deletion notification email using SMTP. Error: {$mail->ErrorInfo}');</script>";
                        }
            
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "No permission was selected.";
    }
} else {
    echo "Invalid request method.";
}

mysqli_close($conn);

// Redirect back to managepermissions.php after 3 seconds
header("refresh:3;url=admin.php");
?>
