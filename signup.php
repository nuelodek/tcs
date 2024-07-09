<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['csrf_token'], $_SESSION['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        if (isset($_POST['username'], $_POST['password'])) {
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = $_POST['password'];

            // Password validation
            if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Save to database (add your own database logic here)
                // $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                // $stmt->bind_param("ss", $username, $hashed_password);
                // $stmt->execute();

                echo "Signup successful!";
            } else {
                echo "Password does not meet the required criteria.";
            }
        }
    } else {
        echo "Invalid CSRF token.";
    }
}
?>
<!Doctype html>
<html lang="en">
<head>
    <title>Teacher Signup</title>
    <style>
        .form-group {
            max-width: 300px;
            margin: auto;
        }
        label {
            display: block;
            margin: 5px 0 2px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }
        .validations {
            color: red;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
    </style>
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
