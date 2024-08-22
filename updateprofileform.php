        
        <h2>Update Profile</h2>
        <form action="updateprofile.php" method="post">



            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            <br>
            <label for="institutional_email">Institutional Email:</label>
            <input type="email" id="institutional_email" name="institutional_email" value="<?php echo $institutional_email; ?>" required>
            <br>
            <label for="identification_number">Identification Number:</label>
            <input type="text" id="identification_number" name="identification_number" value="<?php echo $identification_number; ?>" required>
            <br>
            <label for="faculty">Faculty:</label>
            <select id="faculty" name="faculty" required>
                <option value="" disabled>Select Faculty</option>
                <?php
                // Fetch and populate faculty options
                $faculties = json_decode(file_get_contents('getfal.php'), true);
                foreach ($faculties as $faculty) {
                    $selected = ($faculty['Id'] == $user_faculty) ? 'selected' : '';
                    echo "<option value='{$faculty['Id']}' {$selected}>{$faculty['Name']}</option>";
                }
                ?>
            </select>
            <br>
            <label for="role">Role at University:</label>
            <select id="role" name="role" required>
                <option value="" disabled>Select Role</option>
                <?php
                // Fetch and populate role options
                $roles = json_decode(file_get_contents('getrawroles.php'), true);
                foreach ($roles as $role) {
                    $selected = ($role['Id'] == $user_role) ? 'selected' : '';
                    echo "<option value='{$role['Id']}' {$selected}>{$role['Role']}</option>";
                }
                ?>
            </select>
            <br>
            <label for="school">Alternative Schools:</label>
            <select id="school" name="school" required>
                <option value="" disabled>Select School</option>
                <?php
                // Fetch and populate school options
                $schools = json_decode(file_get_contents('getschool.php'), true);
                foreach ($schools as $school) {
                    $selected = ($school['Id'] == $user_school) ? 'selected' : '';
                    echo "<option value='{$school['Id']}' {$selected}>{$school['Name']}</option>";
                }
                ?>
            </select>
            <br>

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
