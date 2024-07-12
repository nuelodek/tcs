<?php
session_start();
require_once 'db.php'; // Assuming this file contains your database connection

// Function to call Moodle API
function call_moodle_api($url, $params) {
    // Build URL with parameters
    $url = $url . '?' . http_build_query($params);
    
    // Initialize cURL session
    $ch = curl_init();
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Execute the request
    $response = curl_exec($ch);
    
    // Check if the request was successful
    if ($response === false) {
        // Handle error
        echo 'Error fetching data: ' . curl_error($ch);
        curl_close($ch);
        return false;
    }
    
    // Close cURL session
    curl_close($ch);
    
    // Decode JSON response
    return json_decode($response, true);
}

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (isset($_POST['csrf_token'], $_SESSION['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        // Sanitize and validate username and password
        if (isset($_POST['username'], $_POST['password'])) {
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = $_POST['password'];

            // Call Moodle API to check if user exists
            $apiUrl = 'https://informaticajv.net/prueba/webservice/rest/server.php';
            $token = '414bb1e4f9b439c396b298d4f2e97463'; // Your Moodle API token

            $params = [
                'wstoken' => $token,
                'wsfunction' => 'core_user_get_users_by_field',
                'moodlewsrestformat' => 'json',
                'field' => 'username',
                'values' => [$username]
            ];

            $userDetails = call_moodle_api($apiUrl, $params);

            if (!empty($userDetails)) {
                // User already exists
                // Perform redirect to profile page or handle accordingly
                echo "User already exists in Moodle. Redirecting...";
                header("Location: tcs_login.php");
                exit;
            } else {
                // User does not exist, proceed with signup
                // Password validation
                if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Create user in your local database
                    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                    $stmt->bind_param("ss", $username, $hashed_password);
                    $stmt->execute();

                    echo "Signup successful locally! ";

                    // Create user in Moodle
                    $params = [
                        'wstoken' => $token,
                        'wsfunction' => 'core_user_create_users',
                        'moodlewsrestformat' => 'json',
                        'users' => [
                            [
                                'username' => $username,
                                'password' => $password, // Moodle requires plaintext password here
                                'firstname' => '', // Add first name if needed
                                'lastname' => '', // Add last name if needed
                                'email' => '' // Add email if needed
                            ]
                        ]
                    ];

                    $result = call_moodle_api($apiUrl, $params);

                    if (isset($result['exception'])) {
                        echo "Error creating user in Moodle: " . $result['message'];
                    } else {
                        echo "User created in Moodle successfully!";
                    }

                   header("Location: profile.php");
                    exit;
                } else {
                    echo "Password does not meet the required criteria.";
                }
            }
        }
    } else {
        echo "Invalid CSRF token.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Signup</title>
    <style>
        /* Your CSS styles here */
    </style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'get_faculties.php',
                method: 'GET',
                success: function(data) {
                    var faculties = JSON.parse(data);
                    var facultySelect = $('#faculty');

                    if (faculties.length > 0) {
                        faculties.forEach(function(faculty) {
                            facultySelect.append('<option value="' + faculty.id + '">' + faculty.name + '</option>');
                        });
                    } else {
                        facultySelect.append('<option value="">No faculties available</option>');
                    }
                },
                error: function() {
                    alert('Failed to fetch faculties.');
                }
            });
        });
    </script>

<script>
    $(document).ready(function() {
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
</script>

</head>
<body>
    <div class="form-group">
        <form id="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateForm()">
            <h2>Teacher Signup</h2>

            <?php
            $csrf_token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $csrf_token;
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="password" required>


              <label for="firstname">Firstname:</label>
              <input type="text" id="firstname" name="firstname" required>

              <label for="lastname">Lastname:</label>
              <input type="text" id="lastname" name="lastname" required>

              <label for="email">Email:</label>
              <input type="email" id="email" name="email" required>

              <label for="telephone">Telephone (Phone):</label>
              <input type="tel" id="telephone" name="telephone" required>
              
              <label for="faculty">Faculty</label>
            <select id="faculty" name="faculty" required>
                <option value="">Select Faculty</option>
            </select>


                  
              </select>

              <label for="id">ID:</label>
              <input type="text" id="id" name="id" required>

              <label for="role">Role at University:</label>
              <select id="role" name="role" required>
                  <option value="">Select Role</option>
                  <!-- Add role options here -->
              </select>

              <label for="institutional_email">(Email Institutional):</label>
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
