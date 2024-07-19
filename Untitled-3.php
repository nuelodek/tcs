<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'database.php'; // Ensure your database connection is properly included

// // Check if user is already logged in
// if (isset($_SESSION['username'])) {
//     echo "<script>window.location.href = 'dashboard.php';</script>";
//     exit;
// }

// Process signup form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function generateUsername($identifier, $conn) {
        $username = explode('@', $identifier)[0]; // Get the part before the '@' symbol if email
        $username = preg_replace('/[^a-zA-Z0-9]/', '', $username); // Remove non-alphanumeric characters

        // Check if the username already exists in the learners table
        $stmt = $conn->prepare("SELECT COUNT(*) FROM learners WHERE username = ?");
        if (!$stmt) {
            die('Prepare statement failed: ' . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            // Username already exists in learners table, try educators table
            $stmt = $conn->prepare("SELECT COUNT(*) FROM educators WHERE username = ?");
            if (!$stmt) {
                die('Prepare statement failed: ' . $conn->error);
            }
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
        if (!$stmt) {
            die('Prepare statement failed: ' . $conn->error);
        }
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $error = "This account already exists in learners table";
        } else {
            // Check if identifier already exists in educators table
            $stmt = $conn->prepare("SELECT COUNT(*) FROM educators WHERE email = ? OR phone = ?");
            if (!$stmt) {
                die('Prepare statement failed: ' . $conn->error);
            }
            $stmt->bind_param("ss", $email, $phone);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $error = "This account already exists in educators table";
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
                    if (!$stmt) {
                        die('Prepare statement failed: ' . $conn->error);
                    }
                    $stmt->bind_param("ssssssss", $firstName, $lastName, $username, $email, $phone, $hashedPassword, $userType, $token);
                    if (!$stmt->execute()) {
                        die('Execute statement failed: ' . $stmt->error);
                    }
                    $stmt->close();

                    // Insert user data into users table
                    $stmt = $conn->prepare("INSERT INTO users (identifier, userType) VALUES (?, ?)");
                    if (!$stmt) {
                        die('Prepare statement failed: ' . $conn->error);
                    }
                    $stmt->bind_param("ss", $identifier, $userType);
                    if (!$stmt->execute()) {
                        die('Execute statement failed: ' . $stmt->error);
                    }
                    $user_id = $stmt->insert_id; // Get the auto-generated user_id
                    $stmt->close();

                    // Send email with token (code for mailing)


















                    // Set session and redirect to verification page
                    $_SESSION['username'] = $username;
                    $conn->close();
                    echo "<script>window.location.href = 'verify.php';</script>";
                    exit;
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    $conn->close();
                }
            }
        }
    } else {
        $error = "Please fill in all fields";
    }
}
?>
