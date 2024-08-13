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
    if (isset($_POST['user_id']) && isset($_POST['delete_permission'])) {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $permission = mysqli_real_escape_string($conn, $_POST['delete_permission']);
        
        $sql = "DELETE FROM user_permissions WHERE user_id = '$user_id' AND permission = '$permission'";
        
        if (mysqli_query($conn, $sql)) {
            echo "Permission deleted successfully.";
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
