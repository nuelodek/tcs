<?php
// db.php should include database connection
include 'db.php';

header('Content-Type: application/json');

$sql = "SELECT Id, Name FROM Faculties";
$result = $conn->query($sql);

$faculties = array();
while($row = $result->fetch_assoc()) {
    $faculties[] = $row;
}

echo json_encode($faculties);

$conn->close();
?>
