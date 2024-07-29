<?php
session_start(); // Start the session

// Check the role before destroying the session
$roleId = isset($_SESSION['role']) ? $_SESSION['role'] : null;

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

// Redirect based on the role
if ($roleId == 1) {
    header("Location: loginadmin.php");
} else {
    header("Location: tcs_login.php");
}
exit();

