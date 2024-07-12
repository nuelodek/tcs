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
    console.log(data); // Add this line
    var faculties = JSON.parse(data);
    console.log(faculties); // Add this line
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
</head>
<body>
    <div class="form-group">
        <form id="signup" action="your_signup_script.php" method="post" onsubmit="return validateForm()">
            <h2>Teacher Signup</h2>
            <?php
            session_start();
            $csrf_token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $csrf_token;
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="password" required>

            <label for="faculty">Faculty</label>
            <select id="faculty" name="faculty" required>
                <option value="">Select Faculty</option>
            </select>

            <div class="validations" id="password-validation">
                <!-- Password validation messages will be displayed here -->
            </div>

            <input type="submit" name="submit" value="Signup">
        </form>
    </div>
</body>
</html>
