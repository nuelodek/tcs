<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $firstname = htmlspecialchars(trim($_POST['firstname']), ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars(trim($_POST['lastname']), ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);
    $password = $_POST['password']; // Password should not be sanitized, but hashed before storage
    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

    // Validate password
    $password_regex = '/^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[0-9])(?=.*[a-z]).{10,}$/';
    if (!preg_match($password_regex, $password)) {
        echo "Password must be at least 10 characters long, contain 1 uppercase letter, 1 number, and 1 special character.";
        exit; 
    }
    // Connect to the database
include 'db.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    // Check if the tempupdate table exists, if not create it
    $check_table_sql = "SHOW TABLES LIKE 'tempupdate'";
    $result = $conn->query($check_table_sql);

    if ($result->num_rows == 0) {
        // Table doesn't exist, create it
        $create_table_sql = "CREATE TABLE tempupdate (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11),
            firstname VARCHAR(255),
            lastname VARCHAR(255),
            email VARCHAR(255),
            phone VARCHAR(20),
            password VARCHAR(255)
        )";
        
        if ($conn->query($create_table_sql) === TRUE) {
            // echo "Table tempupdate created successfully.<br>";
        } else {
            // echo "Error creating table: " . $conn->error . "<br>";
        }
    }

    // Get the user_id (assuming it's stored in the session)
    session_start();
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Prepare and execute the SQL statement with user_id
    $stmt = $conn->prepare("INSERT INTO tempupdate (user_id, firstname, lastname, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $firstname, $lastname, $email, $phone, $password);

    if ($stmt->execute()) {
        echo "Profile update request submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
