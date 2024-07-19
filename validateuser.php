<?php
// Assuming you have a database connection established
include 'db.php';

// Fetch unvalidated users from the database and check status table for validation requests
$sql = "SELECT u.*, s.validation_request 
        FROM users u 
        LEFT JOIN status s ON u.Id = s.user_id 
        WHERE u.validation = 'not_validated'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h2>Users Pending Validation</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Validation Request</th><th>Action</th></tr>";
    
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['Id']."</td>";
        echo "<td>".$row['Username']."</td>";
        echo "<td>".$row['First_Name']."</td>";
        echo "<td>".$row['Last_Name']."</td>";
        echo "<td>".$row['Email']."</td>";
        echo "<td>".$row['Institutional_Email']."</td>";
        echo "<td>".$row['Moodle_Id']."</td>";
        echo "<td>".$row['Identification_Number']."</td>";
        echo "<td>".$row['Faculty_Id']."</td>";
        echo "<td>".$row['Created_At']."</td>";
        echo "<td>".$row['Role_Id']."</td>";
        echo "<td>".$row['Password']."</td>";
        echo "<td>".$row['Token']."</td>";
        echo "<td>".$row['Updated_At']."</td>";
        echo "<td>".$row['School_Id']."</td>";
        echo "<td>".$row['Phone']."</td>";
        echo "<td>".$row['Validation']."</td>";
        echo "<td>".($row['validation_request'] ? 'Yes' : 'No')."</td>";
        echo "<td>
                <form action='validate_user.php' method='post'>
                    <input type='hidden' name='user_id' value='".$row['Id']."'>
                    <button type='submit'>Validate</button>
                </form>
              </td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>No users pending validation.</p>";
}

mysqli_close($conn);
?>