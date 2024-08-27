<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

// Add the delete, edit, and update functionality
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action === 'delete') {
        $deleteQuery = "DELETE FROM roles WHERE Id = ?";
        $stmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<p>Role deleted successfully.</p>";
        } else {
            echo "<p>Error deleting role: " . mysqli_error($conn) . "</p>";
        }
        mysqli_stmt_close($stmt);
    } elseif ($action === 'edit') {
        // Fetch the role details for editing
        $editQuery = "SELECT * FROM roles WHERE Id = ?";
        $stmt = mysqli_prepare($conn, $editQuery);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $roleToEdit = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($roleToEdit) {
            echo "<h2>Edit Role</h2>";
            echo "<form method='post' action='deleteandeditrole.php'>";
            echo "<input type='hidden' name='action' value='update'>";
            echo "<input type='hidden' name='id' value='" . htmlspecialchars($roleToEdit['Id']) . "'>";
            echo "<label for='Role'>Role:</label>";
            echo "<input type='text' id='Role' name='Role' value='" . htmlspecialchars($roleToEdit['Role']) . "' required>";
            echo "<input type='submit' value='Update Role'>";
            echo "</form>";
        } else {
            echo "<p>Role not found.</p>";
        }
    }
}

// Add the update functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id'];
    $role = $_POST['Role'];

    $updateQuery = "UPDATE roles SET Role = ? WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "si", $role, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p>Role updated successfully.</p>";
    } else {
        echo "<p>Error updating role: " . mysqli_error($conn) . "</p>";
    }
    mysqli_stmt_close($stmt);
}
?>
