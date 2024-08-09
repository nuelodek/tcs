<?php
session_start(); // Start the session
include 'header.php';
include 'db.php';
// Check if user is logged in
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 2 && $_SESSION['role'] != 3)) {
    header("Location: tcs_login.php"); // Redirect to login page if not logged in or role is not 2 or 3
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
$user_id = htmlspecialchars($_SESSION['id'], ENT_QUOTES, 'UTF-8');
// Determine if the user is validated
$isValidated = ($validation === 'validated');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            
            overflow: hidden;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #333;
        }
        a {
            color: #1a73e8;
            text-decoration: none;
            margin-right: 15px;
        }
        a:hover {
            text-decoration: underline;
        }
        form {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"] {
            width: 97.5%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        select {
            width: 98.5%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        textarea {

            height: 100px;
            resize: vertical;
            margin-right: 20px;
            width: 98%;

        }
        input[type="submit"] {
            background: #1a73e8;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #135cc5;
        }
        .error {
            color: #d32f2f;
            margin-bottom: 10px;
        }
        .success {
            color: #388e3c;
            margin-bottom: 10px;
        }
        .logout-button {
            background: #d32f2f;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .logout-button:hover {
            background: #b71c1c;
        }
        button {
            display: inline-block;
            background: #1a73e8;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #135cc5;
        }
        .logout-button {
            background: #d32f2f;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .logout-button:hover {
            background: #b71c1c;
        }
    </style>

    
</head>
<body>
<div class="container">

    <?php if ($isValidated): ?>
        <!-- User is validated, display the dashboard -->
        


        <?php if ($role == 3): ?>
            <?php include "teachermenutoggle.php" ?>


<div class="solicitcoursecheck" style="display: none;">
    <?php include "solicationsform.php" ?>
</div>

<div class="allcoursescheck" style="display: none;">
    <?php include "allcoursesuser.php" ?>
</div>

<div class="approvedcoursescheck" style="display: none;">
    <?php include "approvedcourses.php" ?>

</div>

<div class="rejectedcoursescheck" style="display: none;">
<?php include "rejectedcourses.php" ?>

</div>

<div class="activitylogcheck" style="display: none;">
    <?php include "activitylogs.php" ?>
</div>

<div class="updateprofilecheck" style="display: none;">
    <?php include "updateprofileform.php" ?>
</div>






        <?php endif; ?>


        <?php if ($role == 2): ?>
        


        <!-- User is validated, display the dashboard -->
        <h1>Cordinator's Dashboard </a>
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




<div class="solicitationscheck" style="display:none;"> 
         
</div>

      <div class="coursescheck"> 
      <h2>Pending Courses</h2>


        <?php include 'coursecontroller.php'; ?> 



      </div>   


    
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






        <?php endif; ?>

    <?php else: ?>
        <!-- User is not validated, display the validation request form -->
         <div class="form-group" style="margin-left: 20px;">
        <h2 >Hello <?php echo $firstname; ?>! <br> Your account is not yet validated</h2>
                <p>Please wait for the admin to validate you</p>

   
            <button type="submit" class="btn btn-danger" onclick="window.location.href='logout.php'">Logout</button>
        

    </div>
    <?php endif; ?>



    

    </div> <!-- main div container -->


    

</body>
<footer>
    Copyright © <?php echo date("Y"); ?> TCS University
    <a href="/login.php">Visit website</a>
    <div class="additional-links">
        <a href="/about.php">About Us</a>
        <a href="/programs.php">Programs</a>
        <a href="/contact.php">Contact</a>
    </div>
    <div class="social-media">
        <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
        <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
        <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
    </div>
</footer>

</html>
