<?php
session_start(); // Start the session
require 'db.php';
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
         <a href="#" class="check-solicitations" style="margin-right: 15px; text-decoration: none;" onclick="toggleSolicitations()">Accept Solicitations</a>
         <a href="#" class="create-course" style="margin-right: 15px; text-decoration: none;" onclick="toggleCreateCourse()">Create Course</a>
         <a href="#" class="create-category" style="margin-right: 15px; text-decoration: none;" onclick="toggleCreateCategory()">Create Category</a>
         <a href="#" class="assign-roles" style="margin-right: 15px; text-decoration: none;" onclick="toggleAssignRoles()">Assign Roles</a>
         <a href="#" class="modify-roles" style="margin-right: 15px; text-decoration: none;" onclick="toggleModifyData()">Modify Data</a>
         <a href="#" class="assign-tasks" style="margin-right: 15px; text-decoration: none;" onclick="toggleAssignTasks()">Assign Tasks</a>        
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
        $query = "SELECT * FROM users WHERE validation = 'not_validated' AND role_id = 2";
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
        $sql = "SELECT Id, Category_Id, Name, Period_Id, Type_of_Course_Id, Descriptive_Synthesis, Development_Competencies, Content_Structure, Teaching_Strategies, Technology_Tools, Assessment_Strategies, Programmatic_Synopsis FROM courses";
      
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
                        <button type='submit' class='createuserbutton'>Create Course</button>
                    </form>
                </td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "0 results";
        }
      
        $conn->close();
        ?>

      </div>   

    </div> <!-- main div container -->

    
    <script>
    function toggleSolicitations() {
      document.querySelector(".solicitationscheck").style.display = "block";
      document.querySelector(".validationscheck").style.display = "none";
      document.querySelector(".coursescheck").style.display = "none";

    }

    function toggleUsercreate() {
      document.querySelector(".solicitationscheck").style.display = "none";
      document.querySelector(".validationscheck").style.display = "block";
      document.querySelector(".coursescheck").style.display = "none";

    }

    function toggleCreateCourse () {
        document.querySelector(".solicitationscheck").style.display = "none";
        document.querySelector(".validationscheck").style.display = "none";
        document.querySelector(".coursescheck").style.display = "block";

    }


</script>
</body>
</html>
