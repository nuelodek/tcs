<?php
// Connect to the database
include 'db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id']) && isset($_POST['new_permission'])) {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $permission = mysqli_real_escape_string($conn, $_POST['new_permission']);
        
        $sql = "INSERT INTO user_permissions (user_id, permission) VALUES ('$user_id', '$permission')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Permission added successfully.";
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
header("refresh:3;url=managepermissions.php");
?>
