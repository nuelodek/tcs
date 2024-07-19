<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: tcs_login.php"); // Redirect to login page if not logged in
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
</head>
<body>
    <?php if ($isValidated): ?>
        <!-- User is validated, display the dashboard -->
        <h1>Welcome to Your Dashboard, <?php echo $firstname; ?>!</h1>
        
        <h2>Solicit for Courses</h2>
        <form action="solicit_course.php" method="post">
        
            <label for="category_id">Category ID:</label>
            <input type="text" id="category_id" name="category_id" required>
            <br>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="period_id">Period ID:</label>
            <input type="text" id="period_id" name="period_id" required>
            <br>
            <label for="type_of_course_id">Type of Course ID:</label>
            <input type="text" id="type_of_course_id" name="type_of_course_id" required>
            <br>
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
            <br>
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

    <?php else: ?>
        <!-- User is not validated, display the validation request form -->
        <h1>Your account is not validated</h1>
        <p>Please submit a request for validation:</p>
        <form action="request_validation.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo $id; ?>">
            <button type="submit">Request Validation</button>
        </form>
    <?php endif; ?>







</body>
</html>
