<?php
// db.php should include database connection
include 'db.php';

header('Content-Type: application/json');

$sql = "SELECT Id, Name FROM schools";
$result = $conn->query($sql);

$roles = array();
while($row = $result->fetch_assoc()) {
    // Check if the role is neither 'Admin' nor 'Coordinator'
        $schools[] = $row;
    }


echo json_encode($schools);

$conn->close();
