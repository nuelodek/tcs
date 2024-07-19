<?php
// db.php should include database connection
include 'db.php';

header('Content-Type: application/json');

$sql = "SELECT Id, Role FROM roles";
$result = $conn->query($sql);

$roles = array();
while($row = $result->fetch_assoc()) {
    // Check if the role is neither 'Admin' nor 'Coordinator'
    if ($row['Role'] !== 'Admin' && $row['Role'] !== 'Coordinator') {
        $roles[] = $row;
    }
}

echo json_encode($roles);

$conn->close();
?>
