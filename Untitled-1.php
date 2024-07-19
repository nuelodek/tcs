<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'database.php'; // Ensure your database connection is properly included

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

// Process signup form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function generateUsername($identifier, $conn) {
        $username = explode('@', $identifier)[0]; // Get the part before the '@' symbol if email
        $username = preg_replace('/[^a-zA-Z0-9]/', '', $username); // Remove non-alphanumeric characters

        // Check if the username already exists in the learners table
        $stmt = $conn->prepare("SELECT COUNT(*) FROM learners WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            // Username already exists in learners table, try educators table
            $stmt = $conn->prepare("SELECT COUNT(*) FROM educators WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                // Username already exists in both tables, generate a new one
                $username .= rand(1000, 9999);
            }
        }

        return $username;
    }

    // Function to split name
    function splitName($name) {
        $nameParts = explode(' ', $name);
        $lastName = array_pop($nameParts);
        $firstName = implode(' ', $nameParts);
        return [$firstName, $lastName];
    }

    // Extract form data
    $fullName = $_POST['fullname'];
    list($firstName, $lastName) = splitName($fullName);
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];
    $userType = $_POST['userType']; // Assuming userType is passed from the form

    // Generate token
    $token = bin2hex(random_bytes(32));

    // Validate form data and check if identifier already exists
    if (!empty($firstName) && !empty($lastName) && !empty($identifier) && !empty($password)) {
        // Check if identifier is email or phone number
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $email = $identifier;
            $phone = null;
        } elseif (preg_match('/^[0-9+()\-.\s]{10,20}$/', $identifier)) {
            $email = null;
            $phone = $identifier;
        } else {
            $error = "Invalid identifier format";
            // Handle error condition
            exit;
        }

        // Check if identifier already exists in learners table
        $stmt = $conn->prepare("SELECT COUNT(*) FROM learners WHERE email = ? OR phone = ?");
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $error = "Identifier already exists in learners table";
        } else {
            // Check if identifier already exists in educators table
            $stmt = $conn->prepare("SELECT COUNT(*) FROM educators WHERE email = ? OR phone = ?");
            $stmt->bind_param("ss", $email, $phone);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $error = "Identifier already exists in educators table";
            } else {
                // Hash the password before storing it
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Determine table and insert user data
                if ($userType === 'Learner') {
                    $userTable = 'learners'; // Use lowercase for consistency
                    $role = 'Learner';
                } elseif ($userType === 'Educator') {
                    $userTable = 'educators'; // Use lowercase for consistency
                    $role = 'Educator';
                } else {
                    $error = "Invalid userType";
                    // Handle error condition
                    exit;
                }

                try {
                    // Generate username
                    $username = generateUsername($identifier, $conn);

                    // Save user data to learners or educators table
                    $stmt = $conn->prepare("INSERT INTO $userTable (first_name, last_name, username, email, phone, password, userType, token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssss", $firstName, $lastName, $username, $email, $phone, $hashedPassword, $userType, $token);
                    $stmt->execute();

                    // Insert user data into users table
                    $stmt = $conn->prepare("INSERT INTO users (identifier, userType) VALUES (?, ?)");
                    $stmt->bind_param("ss", $identifier, $userType);
                    $stmt->execute();
                    $user_id = $stmt->insert_id; // Get the auto-generated user_id

                    $stmt->close();

                    // Send email with token (code for mailing)

                    // Set session and redirect to verification page
                    $_SESSION['username'] = $username;
                    header("Location: verify.php");
                    exit;
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
    } else {
        $error = "Please fill in all fields";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="buttonstyles.css">
    <link rel="stylesheet" href="btnstyles.css">
    <link rel="stylesheet" href="signup.css">
    <style>
        .smallman {
            text-align: left;
            font-size: 11px;
        }

        
        select {
            width: calc(100% - 0px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
    </style>
    <title>Signup/Login</title>
    <script>
        function validateForm() {
            var fullName = document.getElementById('full').value;
            var identifier = document.getElementById('identifier').value;
            var password = document.getElementById('password').value;
            var agree = document.getElementById('agree').checked;

            if (fullName === "" || identifier === "" || password === "") {
                alert("All fields are required.");
                return false;
            }
            if (!agree) {
                alert("You must agree to the terms and conditions.");
                return false;
            }
            return true;
        }

        function validateLoginForm() {
            var identifier = document.getElementById('login-identifier').value;
            var password = document.getElementById('login-password').value;

            if (identifier === "" || password === "") {
                alert("All fields are required.");
                return false;
            }
            return true;
        }

        function validateForgotPasswordForm() {
            var identifier = document.getElementById('forgot-identifier').value;

            if (identifier === "") {
                alert("Identifier is required.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="modal">
        <div class="modal-content">
            <div class="modal-left">
                <img src="logos/gegetologo.png" alt="Rocket" class="logo">
            </div>
            <div class="modal-right" id="signup">
                <small style="font-weight:bold;"> Sign up for a Gegeto account</small>
                <button class="close-button" onclick="window.location.href='index.php'">×</button>
                <div class="form-container">
                    <form method="POST" id="signup-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onsubmit="return validateForm()">
                        <select name="userType" class="usertype"> 
                            <option value="" disabled selected>Select your user type</option>
                            <option value="Learner">Learner - You can watch videos</option>
                            <option value="Educator" disabled>Educator - You can post videos</option>
                        </select>
                        <input type="text" id="full" name="fullname" placeholder="Start by typing your fullname" required>
                        <input type="text" id="identifier" name="identifier" placeholder="Enter your email or phone number" required>
                        <input type="password" id="password" name="password" placeholder="Type in a secure password here" required>
                        <button type="submit" class="subscribe-button coolBeans">Create account</button>
                        <div class="social-login">
                            <button type="button" class="google btn-border-drawing">Sign up with Google</button>
                        </div>
                        <br>
                        <div>
                            <input type="checkbox" name="agree" id="agree" required>
                            <label for="agree" class="smallman">I agree to Gegeto's <a href="#" onclick="toggleLogin()">Terms of Service</a> and <a href="#" onclick="toggleLogin()">Privacy Policy</a></label>
                        </div>
                        <div>
                            <small class="smallman">Already have an account? <a href="#" onclick="toggleLogin()">Log in</a></small>
                        </div>
                    </form>
                </div>
            </div>
            <div id="passworddiv" class="modal-right" hidden>
               
                <small style="font-weight:bold;"> Reset your Gegeto account password</small>
                <button class="close-button" onclick="window.location.href='index.php'">×</button>
                <div class="form-container">
                    <form method="POST" id="forgot-password-form" action="forgotpassword.php" onsubmit="return validateForgotPasswordForm()">
                        <input type="text" id="forgot-identifier" name="identifier" placeholder="Enter your email or phone number" required>
                        <button type="submit" class="subscribe-button coolBeans">Reset Password</button>
                    </form>

                    <br>
                    <small class="smallman">Remember your password? <a href="#" onclick="toggleLogin()">Log in</a></small>
                    
                </div>
            </div>
            <div id="login" class="modal-right" hidden>
                <small style="font-weight:bold;"> Login into your Gegeto account</small>
                <button class="close-button" onclick="window.location.href='index.php'">×</button>
                <div class="form-container">
                    <form method="POST" id="login-form" action="login.php" onsubmit="return validateLoginForm()">
                        <input type="text" id="login-identifier" name="identifier" placeholder="Enter your email or phone number or username" required>
                        <input type="password" id="login-password" name="password" placeholder="Enter your password here" required>
                        <button type="submit" class="subscribe-button coolBeans">Log In</button>
                        <div class="social-login">
                            <button type="button" class="google btn-border-drawing">Login with Google</button>
                        </div>
                      
                      <br>
                        <div>
                            
                        <small class="smallman">If you don't have an account?  <a href="#" onclick="toggleSignup()">Get one</a></small> <br>
                        <small class="smallman">Forgot your password? <a href="#" onclick="toggleForget()">Reset It</a> <!-- Link to toggle password reset -->

                        </div>
                    </form>
                </div>
            </div>
            
            
        </div>
    </div>

    <script src="toggleAuthman.js"></script>
</body>
</html>
