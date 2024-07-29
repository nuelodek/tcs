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

    
</head>
<body>
    <?php if ($isValidated): ?>
        <!-- User is validated, display the dashboard -->
        <h1>Welcome to Your Dashboard, <?php echo $firstname; ?>!</h1>
        

        <?php if ($role == 2): ?>


        
        <h2>Solicit for Courses</h2>
        <form action="solicit_course.php" method="post">
        
            <label for="category_id"> Course Category: </label>
            <select id="category_id" name="category_id" required onchange="checkOthers(this)">
                <option value="" disabled selected>Select Category</option>
                <?php
                require_once 'db.php';

                function call_moodle_api($url, $params) {
                    $url = $url . '?' . http_build_query($params);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    if ($response === false) {
                        echo 'Error fetching data: ' . curl_error($ch);
                        curl_close($ch);
                        return false;
                    }
                    curl_close($ch);
                    return json_decode($response, true);
                }

                $apiUrl = 'https://informaticajv.net/prueba/webservice/rest/server.php';
                $token = '414bb1e4f9b439c396b298d4f2e97463';

                $params = [
                    'wstoken' => $token,
                    'wsfunction' => 'core_course_get_categories',
                    'moodlewsrestformat' => 'json'
                ];

                $categories = call_moodle_api($apiUrl, $params);

                if ($categories) {
                    foreach ($categories as $category) {
                        echo "<option value=\"{$category['id']}\">{$category['name']}</option>";
                    }
                }
                ?>
                <option value="others">Others</option>
            </select>

            <div class="latestcategory" id="latestcategory"  style="display:none;">
            <label> New Category: </label>
            <input type="text" id="otherCategory" name="otherCategory" placeholder="Enter new category">
                    </div>
            <script>
            function checkOthers(select) {
                if (select.value === 'others') {
                    document.getElementById('latestcategory').style.display = 'block';
                } else {
                    document.getElementById('latestcategory').style.display = 'none';
                }
            }
            </script>
        
            
            
            <br>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
                    <label for="period_id">Period</label>
                    <select id="period" name="period" required>
                    <?php
                   
                    $sql = "SELECT Id, Name FROM periods";
                    $result = $conn->query($sql);

                    $periods = array();
                    while($row = $result->fetch_assoc()) {
                        $periods[] = $row;
                    }

                    foreach ($periods as $period) {
                        echo "<option value='" . $period['Id'] . "'>" . $period['Name'] . "</option>";
                    }

                    
                    ?>
                    </select>
                 
                        <label for="type_of_course_id">Type of Course</label>
                        <select id="type_of_course_id" name="type_of_course_id" required>
                                    <?php
                   
                                    $sql = "SELECT Id, Name, Field FROM types_of_courses";
                                    $result = $conn->query($sql);

                                    $coursetypes = array();
                                    while($row = $result->fetch_assoc()) {
                                        $coursetypes[] = $row;
                                    }

                                    foreach ($coursetypes as $coursetype) {
                                        echo "<option value='" . $coursetype['Id'] . "'>" . $coursetype['Name'] . " - " . $coursetype['Field'] . "</option>";
                                    }

                                    $conn->close();
                                    ?>
                                    </select>                  
                                    
                                    <br />
                                                              
                                                              
            <label for="descriptive_synthesis">Descriptive Synthesis:</label>
            <textarea id="descriptive_synthesis" name="descriptive_synthesis" required></textarea>
            <br>
            <label for="development_competencies">Development Competencies:</label>
            <textarea id="development_competencies" name="development_competencies" required></textarea>
            <br>
            <label for="content_structure">Content Structure:</label>
            <textarea id="content_structure" name="content_structure" required></textarea>
            <br>
            <label for="teaching_strategies">Teaching Strategies:</label>
            <textarea id="teaching_strategies" name="teaching_strategies" required></textarea>
            <br>
            <label for="technology_tools">Technology Tools:</label>
            <textarea id="technology_tools" name="technology_tools" required></textarea>
            <br>
            <label for="assessment_strategies">Assessment Strategies:</label>
            <textarea id="assessment_strategies" name="assessment_strategies" required></textarea>
            <br>
            <label for="programmatic_synopsis">Programmatic Synopsis:</label>
            <textarea id="programmatic_synopsis" name="programmatic_synopsis" required></textarea>
            <br><br><br>

            <button type="submit">Solicit Course</button>



        </form>       
        
        
        <h2>Your Profile</h2>
        <form action="update_profile.php" method="post">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" required>
            <br>
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            <br>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" required>
            <br>
            <button type="submit">Update Profile</button>
        </form>

        <?php endif; ?>
        <?php if ($role == 3): ?>
        


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
        
        <?php endif; ?>

    <?php else: ?>
        <!-- User is not validated, display the validation request form -->
         <div class="form-group" style="margin-left: 20px;">
        <h2 >Hello <?php echo $firstname; ?>! <br> Your account is not yet validated</h2>
                <p>Please wait for the admin to validate you</p>
        <!-- <form action="request_validation.php" method="post">
            <input type="hidden" name="user_id" value="<?php // echo $id; ?>">
            <button type="submit">Request Validation</button>
        </form> -->

    </div>
    <?php endif; ?>



    



    <?php include 'footer.php'; ?>

</body>
</html>
