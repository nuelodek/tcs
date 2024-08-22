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

// Get all coordinators from users table with left join to roles table
$sql = "SELECT u.Id, u.Username, u.First_Name, u.Last_Name, u.Email, u.Moodle_Id, u.Identification_Number, u.Faculty_Id, u.Created_At, u.Role_Id, u.Password, u.Token, u.Updated_At, u.School_Id, u.Phone, u.Validation, r.Role AS role_name 
        FROM users u 
        LEFT JOIN roles r ON u.Role_Id = r.Id 
        WHERE r.Role = 'Coordinator'";
$result = mysqli_query($conn, $sql);

// Check if there are coordinators
if (mysqli_num_rows($result) > 0) {
    echo "<h2>Manage Coordinator Permissions</h2>";
    
    // Permissions table
    echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
    echo "<thead>";
    echo "<tr style='background-color: #f2f2f2;'>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Name</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Username</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Current Permissions</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Add Permission</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Delete Permission</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['Id'];
        $user_name = $row['First_Name'] . ' ' . $row['Last_Name'];
        $username = $row['Username'];

        // Check if the coordinator has permissions
        $permission_sql = "SELECT permission FROM user_permissions WHERE user_id = $user_id";
        $permission_result = mysqli_query($conn, $permission_sql);

        echo "<tr style='border-bottom: 1px solid #ddd;'>";
        echo "<td style='padding: 12px;'>$user_name</td>";
        echo "<td style='padding: 12px;'>$username</td>";
        
        // Current Permissions
        echo "<td style='padding: 12px;'>";
        $current_permissions = array();
        if (mysqli_num_rows($permission_result) > 0) {
            while ($perm_row = mysqli_fetch_assoc($permission_result)) {
                $current_permissions[] = $perm_row['permission'];
                echo ucfirst($perm_row['permission']) . " Courses<br>";
            }
        } else {
            echo "No permissions";
        }
        echo "</td>";
        
        // Add Permission
        echo "<td style='padding: 12px;'>";
        echo "<form action='add_permissions.php' method='post'>";
        echo "<input type='hidden' name='user_id' value='$user_id'>";
        echo "<select name='new_permission'>";
        $permissions = array('solicit', 'view', 'delete');
        foreach ($permissions as $permission) {
            if (!in_array($permission, $current_permissions)) {
                echo "<option value='$permission'>" . ucfirst($permission) . " Courses</option>";
            }
        }
        echo "</select>";
        echo "<input type='submit' value='Add' class='createuserbutton'>";
        echo "</form>";
        echo "</td>";
        
        // Delete Permission
        echo "<td style='padding: 12px;'>";
        echo "<form action='delete_permissions.php' method='post'>";
        echo "<input type='hidden' name='user_id' value='$user_id'>";
        echo "<select name='delete_permission'>";
        foreach ($current_permissions as $permission) {
            echo "<option value='$permission'>" . ucfirst($permission) . " Courses</option>";
        }
        echo "</select>";
        echo "<input type='submit' value='Delete' class='createuserbutton'>";
        echo "</form>";
        echo "</td>";
        
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "No coordinators found.";
}

mysqli_close($conn);
