
        <h2>Update Profile</h2>
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

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <br>
            <button type="submit">Update Profile</button>
        </form>
