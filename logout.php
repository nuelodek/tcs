<?php
session_start(); // Start the session

// Destroy all session variables
$_SESSION = array();

// If using cookies, delete the session cookie as well
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
              $params["path"], $params["domain"], 
              $params["secure"], $params["httponly"]);
}

// Destroy the session
session_destroy();

// Redirect to the login page or another page
header("Location: tcs_login.php");
exit();
?>
