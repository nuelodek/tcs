<?php
session_start();
include 'db.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    // Moodle API credentials
    $moodle_url = 'https://informaticajv.net/prueba/webservice/rest/server.php';
    $moodle_token = 'aaa9b3ecc791044b0bd74c009882b074';

    // Fetch form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $institutional_email = $_POST['institutional_email'];
    $identification_number = $_POST['identification_number'];
    $faculty = $_POST['faculty'];
    $role = $_POST['role'];
    $school = $_POST['school'];
    $phone = $_POST['telephone'];
    
    // Set default values for moodle_id, token, and updated_at
    $moodle_id = null;
    $token = null;
    $updated_at = null;

    // Check if user exists in Moodle
    $moodle_check_url = $moodle_url . '?wstoken=' . $moodle_token . '&wsfunction=core_user_get_users&moodlewsrestformat=json&criteria[0][key]=username&criteria[0][value]=' . urlencode($username);
    $response = file_get_contents($moodle_check_url);
    $user_data = json_decode($response, true);

    if (!(isset($user_data['users']) && count($user_data['users']) > 0)) {
        // User does not exist in Moodle, proceed to insert into database
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (Username, Password, First_Name, Last_Name, Email, Institutional_Email, Moodle_Id, Identification_Number, Faculty_Id, Created_At, Role_Id, Token, Updated_At, School_Id, Phone, Validation) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, 'not_validated')";
        
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bind_param("sssssssisisiss", $username, $password_hash, $firstname, $lastname, $email, $institutional_email, $moodle_id, $identification_number, $faculty, $role, $token, $updated_at, $school, $phone);
            $stmt->execute();
            echo "<script>alert('User successfully registered.'); setTimeout(function() { window.location.href = 'tcs_login.php'; }, 5000);</script>";
        } catch (mysqli_sql_exception $e) {
            if ($stmt->errno === 1062) {
                echo "<script>alert('Error: Duplicate entry. User with this username, email, or identification number already exists.');</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
        }
        
        $stmt->close();
    } else {
        // User exists in Moodle
        echo "<script>alert('User with username \"$username\" already exists in Moodle.');</script>";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .form-group {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #4cae4c;
        }

        #password-validation {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
        }

        option {
            padding: 10px;
        }
    </style><script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: 'getfal.php',
            method: 'GET',
            success: function(data) {
                // Log the raw response data
                console.log("Raw data received: ", data);
                
                // Check if data is already an object
                if (typeof data === 'object') {
                    populateFaculties(data);
                } else {
                    try {
                        // Attempt to parse data if it's a string
                        var faculties = JSON.parse(data);
                        populateFaculties(faculties);
                    } catch (e) {
                        console.error("Failed to parse JSON response: ", e);
                        console.log("Response: ", data);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Failed to fetch faculties: ", error);
                alert('Failed to fetch faculties.');
            }
        });

        function populateFaculties(faculties) {
            var facultySelect = $('#faculty');

            if (faculties.length > 0) {
                faculties.forEach(function(faculty) {
                    facultySelect.append('<option value="' + faculty.Id + '">' + faculty.Name + '</option>');
                });
            } else {
                facultySelect.append('<option value="">No faculties available</option>');
            }
        }
    });
</script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: 'getrawroles.php',
            method: 'GET',
            success: function(data) {
                // Log the raw response data
                console.log("Raw data received: ", data);
                
                // Check if data is already an object
                if (typeof data === 'object') {
                    populateRoles(data);
                } else {
                    try {
                        // Attempt to parse data if it's a string
                        var roles = JSON.parse(data);
                        populateRoles(roles);
                    } catch (e) {
                        console.error("Failed to parse JSON response: ", e);
                        console.log("Response: ", data);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Failed to fetch roles: ", error);
                alert('Failed to fetch roles.');
            }
        });

        function populateRoles(roles) {
            var roleSelect = $('#role');

            if (roles.length > 0) {
                roles.forEach(function(role) {
                    roleSelect.append('<option value="' + role.Id + '">' + role.Role + '</option>');
                });
            } else {
                roleSelect.append('<option value="">No roles available</option>');
            }
        }
    });
</script>

  <script>
      $(document).ready(function() {
          $.ajax({
              url: 'getschool.php',
              method: 'GET',
              success: function(data) {
                  // Log the raw response data
                  console.log("Raw data received: ", data);
                
                  // Check if data is already an object
                  if (typeof data === 'object') {
                      populateSchools(data);
                  } else {
                      try {
                          // Attempt to parse data if it's a string
                          var schools = JSON.parse(data);
                          populateSchools(schools);
                      } catch (e) {
                          console.error("Failed to parse JSON response: ", e);
                          console.log("Response: ", data);
                      }
                  }
              },
              error: function(xhr, status, error) {
                  console.error("Failed to fetch schools: ", error);
                  alert('Failed to fetch schools.');
              }
          });

          function populateSchools(schools) {
              var schoolSelect = $('#school');

              if (schools.length > 0) {
                  schools.forEach(function(school) {
                      schoolSelect.append('<option value="' + school.Id + '">' + school.Name + '</option>');
                  });
              } else {
                  schoolSelect.append('<option value="">No schools available</option>');
              }
          }
      });
  </script>
<!-- 
<script>
    $(document) .ready(function() {
        $.ajax({
            url: 'getroles.php', // Update to point to your PHP script for roles
            method: 'GET',
            success: function(data) {
                var roles = JSON.parse(data);
                var roleSelect = $('#role'); // Assuming you have an element with id="role"

                if (roles.length > 0) {
                    roles.forEach(function(role) {
                        roleSelect.append('<option value="' + role.roleid + '">' + role.name + '</option>');
                    });
                } else {
                    roleSelect.append('<option value="">No roles available</option>');
                }
            },
            error: function() {
                alert('Failed to fetch roles.');
            }
        });
    });
</script> -->
</head>
<body>
    <header>
        <div class="logo">
            <img src="https://t3.ftcdn.net/jpg/04/91/76/62/360_F_491766294_h4j7LbW2YgfbNHhq7F8GboIc1XyBSEY5.jpg" alt="University Logo" width="60" height="60">
        </div>
        <nav class="social-media">
            <a href="https://www.facebook.com/youruniversity" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.twitter.com/youruniversity" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="https://www.linkedin.com/school/youruniversity" target="_blank"><i class="fab fa-linkedin-in"></i></a>
            <a href="https://www.instagram.com/youruniversity" target="_blank"><i class="fab fa-instagram"></i></a>
        </nav>
    </header>
    <style>
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #f8f8f8;
        }
        .logo img {
            max-width: 100%;
            height: auto;
        }
        .social-media a {
            margin-left: 15px;
            color: #333;
            font-size: 20px;
            text-decoration: none;
        }
        .social-media a:hover {
            color: #0056b3;
        }
    </style>
    <!-- Font Awesome for social media icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <div class="form-group">
        <form id="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateForm()">
            <h2>Teacher Signup</h2>

            <?php
            $csrf_token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $csrf_token;
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Username" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Password" required>

            <label for="firstname">Firstname</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="lastname">Lastname</label>
            <input type="text" id="lastname" name="lastname" required>

            <label for="identification_number">Identification Number</label>
            <input type="text" id="identification_number" name="identification_number" required>


            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="telephone">Telephone (Phone)</label>
            <input type="tel" id="telephone" name="telephone" required>

            <label for="schools">Alternative Schools</label>
            <select id="school" name="school" required>
                <option value="" disabled>Select School</option>
                <!-- Add options dynamically using PHP or JavaScript -->
            </select>


            <label for="faculty">Faculty</label>
            <select id="faculty" name="faculty"  required>
                <option value="" disabled>Select Faculty</option>
            </select>

            <label for="role">Role at University</label>
            <select id="role" name="role" required>
            <option value="" disabled>Select Role</option>
            </select>

            <label for="institutional_email">Institutional Email</label>
            <input type="email" id="institutional_email" name="institutional_email" required>

            <div class="validations" id="password-validation">
                <!-- Password validation messages will be displayed here -->
            </div>

            <input type="submit" name="submit" value="Signup">
        </form>
    </div>

    <script>
        function validateForm() {
            var password = document.getElementById('password').value;
            var validationMessage = '';

            if (password.length < 8) {
                validationMessage += 'Password must have at least 8 characters.<br>';
            }
            if (!/[0-9]/.test(password)) {
                validationMessage += 'Password must have at least 1 digit.<br>';
            }
            if (!/[a-z]/.test(password)) {
                validationMessage += 'Password must have at least 1 lowercase letter.<br>';
            }
            if (!/[A-Z]/.test(password)) {
                validationMessage += 'Password must have at least 1 uppercase letter.<br>';
            }
            if (!/[\W_]/.test(password)) {
                validationMessage += 'Password must have at least 1 special character (e.g. *, -, or #).<br>';
            }

            document.getElementById('password-validation').innerHTML = validationMessage;

            return validationMessage === '';
        }
    </script>
</body>
</html>
