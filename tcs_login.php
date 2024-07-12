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

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: profile.php");
    exit;
}

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'], $_POST['password'])) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = $_POST['password'];

        // Validate username and password locally
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Password correct locally, now authenticate against Moodle
                $apiUrl = 'https://informaticajv.net/prueba/webservice/rest/server.php';
                $token = '414bb1e4f9b439c396b298d4f2e97463'; // Your Moodle API token

                $params = [
                    'wstoken' => $token,
                    'wsfunction' => 'core_authenticate_user_login',
                    'moodlewsrestformat' => 'json',
                    'username' => $username,
                    'password' => $password
                ];

                // Debugging: Output the API request details
                echo "API Request: " . $apiUrl . '?' . http_build_query($params) . "<br>";

                $authResult = call_moodle_api($apiUrl, $params);

                // Debugging: Output the API response
                echo "API Response: ";
                print_r($authResult);
                echo "<br>";

                if (!empty($authResult['token'])) {
                    // Authentication successful, start session and redirect to profile
                    $_SESSION['username'] = $username;
                    $_SESSION['moodle_token'] = $authResult['token'];
                    header("Location: profile.php");
                    exit;
                } else {
                    echo "Moodle Authentication Error: " . $authResult['error']['message'];
                }
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        /* Your CSS styles here */
    </style>
</head>
<body>
    <div class="form-group">
        <form id="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>Login</h2>
            
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="password" required>

            <input type="submit" name="submit" value="Login">
        </form>
    </div>
</body>
</html>
