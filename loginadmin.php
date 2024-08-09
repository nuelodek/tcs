<?php
session_start();
include 'db.php'; // Include your database connection script

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    // Sanitize input
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Check if the user exists in the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $password === $user['Password']) {
        // Password is correct, start a session
        session_regenerate_id(true);

        $_SESSION['username'] = $user['Username'];
        $_SESSION['firstname'] = $user['First_Name'];
        $_SESSION['lastname'] = $user['Last_Name'];
        $_SESSION['email'] = $user['Email'];
        $_SESSION['institutional_email'] = $user['Institutional_Email'];
        $_SESSION['identification_number'] = $user['Identification_Number'];
        $_SESSION['faculty'] = $user['Faculty_Id'];
        $_SESSION['role'] = $user['Role_Id'];
        $_SESSION['school'] = $user['School_Id'];
        $_SESSION['phone'] = $user['Phone'];
        $_SESSION['moodle_id'] = $user['Moodle_Id'];
        $_SESSION['token'] = $user['Token'];
        $_SESSION['updated_at'] = $user['Updated_At'];
        $_SESSION['created_at'] = $user['Created_At'];
        $_SESSION['id'] = $user['Id'];
        $_SESSION['validation'] = $user['Validation'];
        
        header("Location: admin.php");
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid username or password.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>


    <link rel="stylesheet" href="style.css">

    <!-- Font Awesome for social media icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

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
        input[type="password"] {
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
    </style>
</head>
<body>


<?php
require 'header.php';
?>


    <div class="form-group">
        <form id="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
           
        
        <h2>Login</h2>

            <?php
            $csrf_token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $csrf_token;
            ?>

            <!-- Add hidden input for role -->
            
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="password" required>

            <input type="submit" name="submit" value="Login">
        </form>

        <p>Not an admin, go to <a href="tcs_login.php" style="color: grey;">Teacher's Login</a></p>

    </div>
</body>
</html>
