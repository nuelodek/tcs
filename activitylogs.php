<?php
include 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Id, Action, DATE, User_Id
        FROM logs
        WHERE User_Id = ?
        ORDER BY DATE DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$logs = $result->fetch_all(MYSQLI_ASSOC);

echo "<h2>Activity Logs</h2>";

if (count($logs) > 0) {
    echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
    echo "<thead>";
    echo "<tr style='background-color: #f2f2f2;'>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>ID</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Action</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Date</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($logs as $log) {
        echo "<tr style='border-bottom: 1px solid #ddd;'>";
        echo "<td style='padding: 12px;'>" . htmlspecialchars($log["Id"]) . "</td>";
        echo "<td style='padding: 12px;'>" . htmlspecialchars($log["Action"]) . "</td>";
        echo "<td style='padding: 12px;'>" . htmlspecialchars($log["DATE"]) . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No activity logs found.</p>";
}

$stmt->close();
$conn->close();

