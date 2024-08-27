            <h2>Add New Role</h2>
  
          <form method="post" action="addarole.php">
              <label for="Role">Role:</label>
              <input type="text" id="Role" name="Role" required>
              <input type="submit" value="Add Role">
          </form>



  
            <h2>Existing Roles</h2>
    
            <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
              <thead>
                <tr style='background-color: #f2f2f2;'>
                  <th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Id</th>
                  <th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Role</th>
                  <th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Actions</th>
                </tr>
              </thead>
              <tbody>
              <?php

              include 'db.php';
              // Assuming you have a database connection established
              $query = "SELECT * FROM roles";
              $result = mysqli_query($conn, $query);
  
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr style='border-bottom: 1px solid #ddd;'>";
                echo "<td style='padding: 12px;'>" . $row['Id'] . "</td>";
                echo "<td style='padding: 12px;'>" . $row['Role'] . "</td>";
                echo "<td style='padding: 12px;'>
                        <a href='deleteandeditrole.php?action=edit&id=" . $row['Id'] . "'>Edit</a> | 
                        <a href='deleteandeditrole.php?action=delete&id=" . $row['Id'] . "' onclick='return confirm(\"Are you sure you want to delete this role?\")'>Delete</a>
                      </td>";
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>