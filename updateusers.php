<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files and initialize database connection
include 'db.php';

// Get the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch data from temp_update table
$query = "SELECT * FROM tempupdate";
$result = mysqli_query($conn, $query);

// Display the table
echo '<h2> Update Users </h2>';

if (mysqli_num_rows($result) > 0) {
    echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
    echo "<thead>";
    echo "<tr style='background-color: #f2f2f2;'>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>ID</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Moodle ID</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>User ID</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Username</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Institutional Email</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Identification Number</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>First Name</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Last Name</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Email</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Phone</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Password</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Action</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while($row = mysqli_fetch_array($result)) {
        echo "<tr style='border-bottom: 1px solid #ddd;'>";
        echo "<td style='padding: 12px;'>" . $row['id'] . "</td>";
        echo "<td style='padding: 12px;'>" . $row['moodle_id'] . "</td>";    
        echo "<td style='padding: 12px;'>" . $row['user_id'] . "</td>";
        echo "<td style='padding: 12px;'>" . $row['username'] . "</td>";
        echo "<td style='padding: 12px;'>" . $row['institutional_email'] . "</td>";
        echo "<td style='padding: 12px;'>" . $row['identification_number'] . "</td>";
        echo "<td style='padding: 12px;'>" . $row['firstname'] . "</td>";
        echo "<td style='padding: 12px;'>" . $row['lastname'] . "</td>";
        echo "<td style='padding: 12px;'>" . $row['email'] . "</td>";
        echo "<td style='padding: 12px;'>" . $row['phone'] . "</td>";
        echo "<td style='padding: 12px;'>" . $row['password'] . "</td>";
        echo "<td style='padding: 12px;'><form method='post' action='process_update.php'>

                <input type='hidden' name='temp_id' value='" . $row['id'] . "'>
                <input type='hidden' name='id' value='" . $row['user_id'] . "'>
                <input type='hidden' name='module_id' value='" . $row['moodle_id'] . "'>
                <input type='hidden' name='username' value='" . $row['username'] . "'>
                <input type='hidden' name='institutional_email' value='" . $row['institutional_email'] . "'>
                <input type='hidden' name='identification_number' value='" . $row['identification_number'] . "'>
                <input type='hidden' name='firstname' value='" . $row['firstname'] . "'>
                <input type='hidden' name='lastname' value='" . $row['lastname'] . "'>
                <input type='hidden' name='email' value='" . $row['email'] . "'>
                <input type='hidden' name='phone' value='" . $row['phone'] . "'>
                <input type='hidden' name='password' value='" . $row['password'] . "'>
                
                <button type='submit' name='update' class='createuserbutton'>Update</button>
            </form></td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No records found.</p>";
}

mysqli_close($conn);

