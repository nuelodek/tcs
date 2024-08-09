<?php
// Assuming you have already established a database connection
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_id = $_POST['user_id'];
    $new_role_id = $_POST['new_role_id'];
    
    $update_sql = "UPDATE Users SET Role_Id = ? WHERE Id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $new_role_id, $user_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Role updated successfully'); window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('Error updating role: " . $conn->error . "'); window.location.href = 'admin.php';</script>";
    }
    $stmt->close();
}

$conn->close();

