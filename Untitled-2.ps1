// Send verification email or SMS
$to = $email; // or $phoneNumber
$subject = "Verification Code";
$message = "Your verification code is: " . generateVerificationCode();
$headers = "From: noreply@example.com";

if (isEmail($to)) {
    mail($to, $subject, $message, $headers);
    echo "A verification email has been sent to $to";
} else {
    sendSMS($to, $message);
    echo "A verification code has been sent to $to";
}

function generateVerificationCode() {
    // Generate a random 6-digit code
    return str_pad(rand(0, 999999), 6, "0", STR_PAD_LEFT);
}

function isEmail($email) {
    // Basic email validation
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function sendSMS($phoneNumber, $message) {
    // Use a third-party SMS service to send the message
    // ...
}
