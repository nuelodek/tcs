<?php
session_start(); // Start the session
require 'db.php';
require 'triggerfaculties.php';
// Check if user is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 1) {
    header("Location: loginadmin.php"); // Redirect to login page if not logged in or not an admin
    exit();
}


// Access session variables
$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$firstname = htmlspecialchars($_SESSION['firstname'], ENT_QUOTES, 'UTF-8');
$lastname = htmlspecialchars($_SESSION['lastname'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8');
$institutional_email = htmlspecialchars($_SESSION['institutional_email'], ENT_QUOTES, 'UTF-8');
$identification_number = htmlspecialchars($_SESSION['identification_number'], ENT_QUOTES, 'UTF-8');
$faculty = htmlspecialchars($_SESSION['faculty'], ENT_QUOTES, 'UTF-8');
$role = htmlspecialchars($_SESSION['role'], ENT_QUOTES, 'UTF-8');
$school = htmlspecialchars($_SESSION['school'], ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars($_SESSION['phone'], ENT_QUOTES, 'UTF-8');
$moodle_id = htmlspecialchars($_SESSION['moodle_id'], ENT_QUOTES, 'UTF-8');
$token = htmlspecialchars($_SESSION['token'], ENT_QUOTES, 'UTF-8');
$updated_at = htmlspecialchars($_SESSION['updated_at'], ENT_QUOTES, 'UTF-8');
$created_at = htmlspecialchars($_SESSION['created_at'], ENT_QUOTES, 'UTF-8');
$id = htmlspecialchars($_SESSION['id'], ENT_QUOTES, 'UTF-8');
$validation = htmlspecialchars($_SESSION['validation'], ENT_QUOTES, 'UTF-8');

// Determine if the user is validated
$isValidated = ($validation === 'validated');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>



    <style> 
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: white;
        }
        .container {
          width: 90%;
            margin: auto;
            font-size: 10px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
        }

        .createuserbutton {
            background-color: #4CAF50;
            color: white;
            padding: 5px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;

            font-size: 10px;
            margin-top: 10px;
        }

    </style>
</head>
<body>

<div class="container">
        <!-- User is validated, display the dashboard -->
        <h1>Admin Dashboard </a>
        <h2> Hi <?php echo $firstname; ?>, what would you like to do today?</h3>
        
         <a href="#" class="checkusers" style="margin-right: 15px; text-decoration: none;" onclick="toggleUsercreate()">Create User</a>
         <!-- <a href="#" class="check-solicitations" style="margin-right: 15px; text-decoration: none;" onclick="toggleSolicitations()">Accept Solicitations</a> -->
         <a href="#" class="create-course" style="margin-right: 15px; text-decoration: none;" onclick="toggleCreateCourse()">Create Course</a>
         <a href="#" class="create-category" style="margin-right: 15px; text-decoration: none;" onclick="toggleCreateCategory()">Create Category</a>
         <a href="#" class="assign-roles" style="margin-right: 15px; text-decoration: none;" onclick="toggleAssignRoles()">Assign Roles</a>
         <a href="#" class="user-list" style="margin-right: 15px; text-decoration: none;" onclick="toggleUserCheck()">User List</a>
         <a href="#" class="all-courses" style="margin-right: 15px; text-decoration: none;" onclick="toggleViewCourses()">View Courses </a>        
         <a href="#" class="manage-permissions" style="margin-right: 15px; text-decoration: none;" onclick="toggleManagePermissions()">Manage Permissions</a>



     
         <a href="logout.php" class="logout-button" style="text-decoration: none;">Logout</a>

<style>
a:hover {
    text-decoration: underline !important;
}
</style>




<div class="validationscheck"> 
        <h2> Pending Validations</h2>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">User ID</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Username</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">First Name</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Last Name</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Email</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Institutional Email</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Moodle ID</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Identification Number</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Faculty ID</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Created At</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Role ID</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">School ID</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Phone</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Validation</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM users WHERE validation = 'not_validated' AND role_id = 3";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr style='border-bottom: 1px solid #ddd;'>";
                echo "<td style='padding: 12px;'>" . $row['Id'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Username'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['First_Name'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Last_Name'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Email'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Institutional_Email'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Moodle_Id'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Identification_Number'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Faculty_Id'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Created_At'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Role_Id'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['School_Id'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Phone'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Validation'] . "</td>";
                echo "<td style='padding: 12px;'>
                    <form action='create_user.php' method='post'>
                        <input type='hidden' name='id' value='" . $row['Id'] . "'>
                        <input type='hidden' name='username' value='" . $row['Username'] . "'>
                        <input type='hidden' name='password' value='" .  $row['Password'] . "'>
                        <input type='hidden' name='first_name' value='" . $row['First_Name'] . "'>
                        <input type='hidden' name='last_name' value='" . $row['Last_Name'] . "'>
                        <input type='hidden' name='email' value='" . $row['Email'] . "'>
                        <input type='hidden' name='institutional_email' value='" . $row['Institutional_Email'] . "'>
                        <input type='hidden' name='moodle_id' value='" . $row['Moodle_Id'] . "'>
                        <input type='hidden' name='identification_number' value='" . $row['Identification_Number'] . "'>
                        <input type='hidden' name='faculty_id' value='" . $row['Faculty_Id'] . "'>
                        <input type='hidden' name='created_at' value='" . $row['Created_At'] . "'>
                        <input type='hidden' name='role_id' value='" . $row['Role_Id'] . "'>
                        <input type='hidden' name='school_id' value='" . $row['School_Id'] . "'>
                        <input type='hidden' name='phone' value='" . $row['Phone'] . "'>
                        <button type='submit' class='createuserbutton'>Create User</button>
                    </form>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='15' style='text-align: center; padding: 12px;'>No user has requested for validation</td></tr>";
        }
        ?>
    </tbody>
</table>
</div>
         <div class="solicitationscheck" style="display:none;"> 
         <h2>Dummy Table</h2>
         <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
             <thead>
                 <tr style="background-color: #f2f2f2;">
                     <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Column 1</th>
                     <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Column 2</th>
                     <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Column 3</th>
                 </tr>
             </thead>
             <tbody>
                 <tr style="border-bottom: 1px solid #ddd;">
                     <td style="padding: 12px;">Data 1</td>
                     <td style="padding: 12px;">Data 2</td>
                     <td style="padding: 12px;">Data 3</td>
                 </tr>
                 <tr style="border-bottom: 1px solid #ddd;">
                     <td style="padding: 12px;">Data 4</td>
                     <td style="padding: 12px;">Data 5</td>
                     <td style="padding: 12px;">Data 6</td>
                 </tr>
             </tbody>
         </table>


    </div>

      <div class="coursescheck" style="display:none;"> 
      <h2> Pending Courses</h2>

        <?php 
      

        // SQL query to get all course information
        $sql = "SELECT c.Id, c.Category_Id, c.Name, c.Period_Id, c.Type_of_Course_Id, c.Descriptive_Synthesis, c.Development_Competencies, c.Content_Structure, c.Teaching_Strategies, c.Technology_Tools, c.Assessment_Strategies, c.Programmatic_Synopsis, r.Status_Id, r.Id AS Request_Id, r.Applicant_Id, r.Coordinator_Id, r.rejection_reason,
        cat.Name AS Category_Name, CONCAT(toc.Name, ' - ', toc.Field) AS Type_of_Course_Name, p.Name AS Period_Name,
        u_applicant.Email AS Applicant_Email, u_coordinator.Email AS Coordinator_Email
        FROM courses c
        JOIN requests r ON c.Id = r.Course_Id
        JOIN categories cat ON c.Category_Id = cat.Id
        JOIN types_of_courses toc ON c.Type_of_Course_Id = toc.Id
        JOIN periods p ON c.Period_Id = p.Id
        LEFT JOIN users u_applicant ON r.Applicant_Id = u_applicant.Id
        LEFT JOIN users u_coordinator ON r.Coordinator_Id = u_coordinator.Id
        WHERE r.Status_Id = 2";        
        
        $result = $conn->query($sql);
      
        if ($result->num_rows > 0) {
            echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
            echo "<thead>";
            echo "<tr style='background-color: #f2f2f2;'>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>ID</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Name</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Category ID</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Period ID</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Type of Course ID</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Descriptive Synthesis</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Development Competencies</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Content Structure</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Teaching Strategies</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Technology Tools</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Assessment Strategies</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Programmatic Synopsis</th>";
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Action</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr style='border-bottom: 1px solid #ddd;'>";
                echo "<td style='padding: 12px;'>" . $row["Id"] . "</td>";
                echo "<td style='padding: 12px;'>" . $row["Name"] . "</td>";
                echo "<td style='padding: 12px;'>" . $row["Category_Id"] . "</td>";
                echo "<td style='padding: 12px;'>" . $row["Period_Id"] . "</td>";
                echo "<td style='padding: 12px;'>" . $row["Type_of_Course_Id"] . "</td>";
                echo "<td style='padding: 12px;'>" . $row["Descriptive_Synthesis"] . "</td>";
                echo "<td style='padding: 12px;'>" . $row["Development_Competencies"] . "</td>";
                echo "<td style='padding: 12px;'>" . $row["Content_Structure"] . "</td>";
                echo "<td style='padding: 12px;'>" . $row["Teaching_Strategies"] . "</td>";
                echo "<td style='padding: 12px;'>" . $row["Technology_Tools"] . "</td>";
                echo "<td style='padding: 12px;'>" . $row["Assessment_Strategies"] . "</td>";
                echo "<td style='padding: 12px;'>" . $row["Programmatic_Synopsis"] . "</td>";
                echo "<td style='padding: 12px;'>
                    <form action='create_course.php' method='post'>
                        <input type='hidden' name='id' value='" . $row['Id'] . "'>
                        <input type='hidden' name='request_id' value='" . $row['Request_Id'] . "'>
                        <input type='hidden' name='applicat_id' value='" .$row['Applicant_Id'] . "'>
                        <input type='hidden' name='name' value='" . $row['Name'] . "'>
                        <input type='hidden' name='category_id' value='" . $row['Category_Id'] . "'>
                        <input type='hidden' name='period_id' value='" . $row['Period_Id'] . "'>
                        <input type='hidden' name='type_of_course_id' value='" . $row['Type_of_Course_Id'] . "'>
                        <input type='hidden' name='descriptive_synthesis' value='" . $row['Descriptive_Synthesis'] . "'>
                        <input type='hidden' name='development_competencies' value='" . $row['Development_Competencies'] . "'>
                        <input type='hidden' name='content_structure' value='" . $row['Content_Structure'] . "'>
                        <input type='hidden' name='teaching_strategies' value='" . $row['Teaching_Strategies'] . "'>
                        <input type='hidden' name='technology_tools' value='" . $row['Technology_Tools'] . "'>
                        <input type='hidden' name='assessment_strategies' value='" . $row['Assessment_Strategies'] . "'>
                        <input type='hidden' name='programmatic_synopsis' value='" . $row['Programmatic_Synopsis'] . "'>
                        <input type='hidden' name='applicant_email' value='" . $row['Applicant_Email'] . "'>
                        <input type='hidden' name='coordinator_email' value='" . $row['Coordinator_Email'] . "'>
                        <input type='hidden' name='Category_Name' value='" . $row["Category_Name"] . "'>
                        <input type='hidden' name='Period_Name' value='" . $row["Period_Name"] . "'>
                        <input type='' name='name_course' value='" . $row["Type_of_Course_Name"] . "'>

                        <button type='submit' class='createuserbutton'>Create Course</button>
                    </form>
                </td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "No courses pending approval";
        }
      
        $conn->close();
        ?>

      </div>   


      <div class="assignroles" style="display: none;">
        <h2>Assign Roles</h2>

<?php
// Assuming you have already established a database connection
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM Users WHERE Role_Id IN (2, 3)";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table style='border-collapse: collapse; width: 100%; margin: 0 auto;'>";
    echo "<thead><tr style='background-color: #f2f2f2;'><th style='padding: 12px; text-align: left;'>ID</th><th style='padding: 12px; text-align: left;'>Username</th><th style='padding: 12px; text-align: left;'>Fullname</th><th style='padding: 12px; text-align: left;'>Role</th><th style='padding: 12px; text-align: left;'>Action</th></tr></thead>";
    echo "<tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr style='border-bottom: 1px solid #ddd;'>";
        echo "<td style='padding: 12px;'>" . $row["Id"] . "</td>";
        echo "<td>". $row["Username"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["First_Name"] . " " . $row["Last_Name"] . "</td>";
        echo "<td style='padding: 12px;'>";
        if ($row["Role_Id"] == 3) {
            echo "Professor";
        } elseif ($row["Role_Id"] == 2) {
            echo "Coordinator";
        } elseif ($row["Role_Id"] == 1) {
            echo "Admin";
        }
        
      
        echo "</td>";
        echo "<td style='padding: 12px;'>
            <form action='assignroles.php' method='post'>
                <input type='hidden' name='user_id' value='" . $row["Id"] . "'>
                <input type='hidden' name='new_role_id' value='";
                
                if ($row["Role_Id"] == 2) {
                    echo "3'><button type='submit' class='createuserbutton'>Downgrade Coordinator</button>";
                } elseif ($row["Role_Id"] == 3) {
                    echo "2'><button type='submit' class='createuserbutton'>Make Coordinator</button>";
                }
                
            echo "</form>
        </td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "No users found.";
}

$conn->close();
?>


        </div>


<div class="categorycheck" style="display:none;">
    <h2> Pending Category </h2>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM categories WHERE Status = 'pending'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Pending Categories</h2>";
    echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
    echo "<thead>";
    echo "<tr style='background-color: #f2f2f2;'>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>ID</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Name</th>";
    echo "<th style='padding: 12 pixels; text-align: left; border-bottom: 2px solid #ddd;'> Select Category</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Status</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Action</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr style='border-bottom: 1px solid #ddd;'>";
        echo "<td style='padding: 12px;'>" . $row["Id"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["Name"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["status"] . "</td>";



        
$moodle_url = 'https://informaticajv.net/prueba/webservice/rest/server.php';
$token = 'aaa9b3ecc791044b0bd74c009882b074';
$function = 'core_course_get_categories';

// Prepare the request parameters
$params = array(
    'wstoken' => $token,
    'wsfunction' => $function,
    'moodlewsrestformat' => 'json'
);

// Make the API request
$ch = curl_init($moodle_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
$response = curl_exec($ch);
curl_close($ch);

// Decode the JSON response
$categories = json_decode($response, true);

// Generate the select element
echo '<select name="category" id="category" required onchange="document.getElementById(\'selected_category\').value = this.value;">';
echo '<option value ="" selected="" disabled> Select a parent category </option>';

foreach ($categories as $category) {
    echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
}
echo '</select>';
        echo "<td style='padding: 12px;'>
            <form action='create_cat.php' method='post'>
                <input type='hidden' name='category_id' value='" . $row["Id"] . "'>
                <input type='hidden' name='category_name' value='" . $row["Name"] . "'>
                <input type='' name='parent_category_id' id='selected_category' value=''>
                <button type='submit' class='createuserbutton'>Approve Category</button>
            </form>
        </td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No pending categories found.</p>";
}

$conn->close();
?>

</div>

<div class="usercheck" style="display: none;"> 



<?php
include 'db.php';
// Enable error reporting

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT u.*, f.Name AS Faculty_Name, s.Name AS School_Name, r.Role AS Role_Name
        FROM users u
        LEFT JOIN Faculties f ON u.Faculty_Id = f.Id
        LEFT JOIN Schools s ON u.School_Id = s.Id
        LEFT JOIN Roles r ON u.Role_Id = r.Id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>User List</h2>";
    echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
    echo "<thead>";
    echo "<tr style='background-color: #f2f2f2;'>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>ID</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Username</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>First Name</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Last Name</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Email</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Institutional Email</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Moodle ID</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Identification Number</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Faculty</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Role</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>School</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Phone</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Validation</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Created At</th>";
    echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Updated At</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr style='border-bottom: 1px solid #ddd;'>";
        echo "<td style='padding: 12px;'>" . $row["Id"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["Username"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["First_Name"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["Last_Name"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["Email"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["Institutional_Email"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["Moodle_Id"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["Identification_Number"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["Faculty_Name"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["Role_Name"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["School_Name"] . "</td>";       
         echo "<td style='padding: 12px;'>" . $row["Phone"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["Validation"] . "</td>";
        echo "<td style='padding: 12px;'>" . $row["Created_At"] . "</td>";
        echo "<td style= 'padding: 12px;'>" .$row["Updated_At"] . "</`td>";
        
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No users found.</p>";
}

$conn->close();
?>

</div>

<div class="allcoursescheck" style="display: none;">

<?php include 'allcourses.php'; ?>
    </div>

<div class="managepermissions" style="display:none;">

    <?php include 'managepermissions.php'; ?>

</div>


    </div> <!-- main div container -->

    
    <script>
    function toggleSolicitations() {
      document.querySelector(".solicitationscheck").style.display = "block";
      document.querySelector(".validationscheck").style.display = "none";
      document.querySelector(".coursescheck").style.display = "none";
      document.querySelector(".categorycheck").style.display = "none";
      document.querySelector(".usercheck").style.display = "none";
      document.querySelector(".allcoursescheck").style.display = "none";
      document.querySelector(".managepermissions").style.display = "none";

    }

    function toggleUsercreate() {
      document.querySelector(".solicitationscheck").style.display = "none";
      document.querySelector(".validationscheck").style.display = "block";
      document.querySelector(".coursescheck").style.display = "none";
      document.querySelector(".assignroles").style.display = "none";
      document.querySelector(".categorycheck").style.display = "none";
      document.querySelector(".usercheck").style.display = "none";
      document.querySelector(".usercheck").style.display = "none";
      document.querySelector(".allcoursescheck").style.display = "none";
      document.querySelector(".managepermissions").style.display = "none";

    }

    function toggleCreateCourse () {
        document.querySelector(".solicitationscheck").style.display = "none";
        document.querySelector(".validationscheck").style.display = "none";
        document.querySelector(".coursescheck").style.display = "block";
        document.querySelector(".assignroles").style.display = "none";
        document.querySelector(".categorycheck").style.display = "none";
        document.querySelector(".usercheck").style.display = "none";
        document.querySelector(".allcoursescheck").style.display = "none";
        document.querySelector(".managepermissions").style.display = "none";

    }


    function toggleAssignRoles() {
      document.querySelector(".solicitationscheck").style.display = "none";
      document.querySelector(".validationscheck").style.display = "none";
      document.querySelector(".coursescheck").style.display = "none";
      document.querySelector(".assignroles").style.display = "block";
      document.querySelector(".categorycheck").style.display = "none";
      document.querySelector(".usercheck").style.display = "none";
      document.querySelector(".allcoursescheck").style.display = "none";
      document.querySelector(".managepermissions").style.display = "none";

    }


    function toggleCreateCategory() {
      document.querySelector(".solicitationscheck").style.display = "none";
      document.querySelector(".validationscheck").style.display = "none";
      document.querySelector(".coursescheck").style.display = "none";
      document.querySelector(".assignroles").style.display = "none";
      document.querySelector(".categorycheck").style.display = "none";
      document.querySelector(".usercheck").style.display = "none";
      document.querySelector(".allcoursescheck").style.display = "none";
      document.querySelector(".managepermissions").style.display = "none";


    }


    
    function toggleUserCheck() {
      document.querySelector(".solicitationscheck").style.display = "none";
      document.querySelector(".validationscheck").style.display = "none";
      document.querySelector(".coursescheck").style.display = "none";
      document.querySelector(".assignroles").style.display = "none";
      document.querySelector(".categorycheck").style.display = "none";
      document.querySelector(".usercheck").style.display = "none";
      document.querySelector(".allcoursescheck").style.display = "none";
      document.querySelector(".managepermissions").style.display = "none";

    }

    function toggleViewCourses() {
      document.querySelector(".solicitationscheck").style.display = "none";
      document.querySelector(".validationscheck").style.display = "none";
      document.querySelector(".coursescheck").style.display = "none";
      document.querySelector(".assignroles").style.display = "none";
      document.querySelector(".categorycheck").style.display = "none";
 document.querySelector(".allcoursescheck").style.display = "block";
 document.querySelector(".managepermissions").style.display = "none";

    }


    function toggleManagePermissions() {
      document.querySelector(".solicitationscheck").style.display = "none";
      document.querySelector(".validationscheck").style.display = "none";
      document.querySelector(".coursescheck").style.display = "none";
      document.querySelector(".assignroles").style.display = "none";
      document.querySelector(".categorycheck").style.display = "none";
      document.querySelector(".usercheck").style.display = "none";
      document.querySelector(".allcoursescheck").style.display = "none";
      document.querySelector(".managepermissions").style.display = "block";
    }

</script>
</body>
</html>
