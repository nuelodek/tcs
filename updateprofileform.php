        
        <h2>Update Profile</h2>
        <form action="updateprofile.php" method="post">

            <label for="user_id">User ID:</label>

            <input type="" id="user_id" name="user_id" value="<?php echo $user_id; ?>">
        
                    <label for="moodle_id">Moodle ID:</label>
                    <input type="text" id="moodle_id" name="moodle_id" value="<?php echo $moodle_id; ?>" required readonly>
                    <br>
        
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $username;  ?>" readonly required>
            <br>
            <label for="institutional_email">Institutional Email:</label>
            <input type="email" id="institutional_email" name="institutional_email" value="<?php echo $institutional_email; ?>" required>
            <br>
            <label for="identification_number">Identification Number:</label>
            <input type="text" id="identification_number" name="identification_number" value="<?php echo $identification_number; ?>" required>
            <br>
            <label for="school">School:</label>
            <?php
            include 'db.php';
            // Assuming you have a database connection established
            $school_id = $school; // The value 1 is stored in $school
            
            // Prepare and execute the query to fetch the school name
            $schoolquery = "SELECT name FROM schools WHERE id = ?";
            $schoolstmt = $conn->prepare($schoolquery);
            $schoolstmt->bind_param("i", $school_id);
            $schoolstmt->execute();
            $result = $schoolstmt->get_result();
            
            // Fetch the school name
            if ($row = $result->fetch_assoc()) {
                $school_name = $row['name'];
            } else {
                $school_name = "Unknown School";
            }
            
            // Close the statement
            $schoolstmt->close();
            ?>
            <input type="text" value="<?php echo htmlspecialchars($school_name); ?>" readonly>

            <label for="school">Faculty:</label>
            <?php
            // Assuming you have a database connection established
            $faculty_id = $faculty; // The value stored in $faculty
            
            // Prepare and execute the query to fetch the faculty name
            $facultyquery = "SELECT name FROM faculties WHERE id = ?";
            $facultystmt = $conn->prepare($facultyquery);
            $facultystmt->bind_param("i", $faculty_id);
            $facultystmt->execute();
            $result = $facultystmt->get_result();
            
            // Fetch the faculty name
            if ($row = $result->fetch_assoc()) {
                $faculty_name = $row['name'];
            } else {
                $faculty_name = "Unknown Faculty";
            }
            
            // Close the statement
            $facultystmt->close();
            ?>
            <input type="text" value="<?php echo htmlspecialchars($faculty_name); ?>" readonly>
             <label for="role">Role at University:</label>
             <?php
             // Assuming you have a database connection established
             $role_id = $role; // The value stored in $role
            
             // Prepare and execute the query to fetch the role name
             $rolequery = "SELECT Role FROM roles WHERE id = ?";
             $rolestmt = $conn->prepare($rolequery);
             $rolestmt->bind_param("i", $role_id);
             $rolestmt->execute();
             $result = $rolestmt->get_result();
            
             // Fetch the role name
             if ($row = $result->fetch_assoc()) {
                 $role_name = $row['Role'];
             } else {
                 $role_name = "Unknown Role";
             }
            
             // Close the statement
             $rolestmt->close();
             ?>
             <input type="text" value="<?php echo htmlspecialchars($role_name); ?>" readonly>
           
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

                    <label for="password">Password (at least 10 characters, 1 uppercase letter, 1 number, and 1 special character):</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" required pattern="(?=.*\d)(?=.*[A-Z])(?=.*[!@#$%^&*]).{10,}" title="Must contain at least 10 characters, 1 uppercase letter, 1 number, and 1 special character">
                        <span id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            <i class="fas fa-eye-slash"></i>
                        </span>
                    </div>
                    <script>
                        const togglePassword = document.querySelector('#togglePassword');
                        const password = document.querySelector('#password');
                
                        togglePassword.addEventListener('click', function (e) {
                            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                            password.setAttribute('type', type);
                            this.querySelector('i').classList.toggle('fa-eye');
                            this.querySelector('i').classList.toggle('fa-eye-slash');
                        });
                    </script>     
                    <style>
                        #togglePassword {
                            margin-top: -4px;
                            margin-right: 10px;
                        }
                    </style>

                    <br>
            <button type="submit">Update Profile</button>
        </form>
