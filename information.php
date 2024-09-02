                <?php
                // Enable error reporting
                error_reporting(E_ALL);
                ini_set('display_errors', 1);

                // Database connection
               
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Display user information
                echo "<h2>User Information</h2>";
                echo "<p><strong>Name:</strong> $firstname $lastname</p>";
                echo "<p><strong>Username:</strong> $username</p>";
                echo "<p><strong>Email:</strong> $email</p>";
                echo "<p><strong>Institutional Email:</strong> $institutional_email</p>";
                echo "<p><strong>Identification Number:</strong> $identification_number</p>";
                echo "<p><strong>Faculty:</strong> " . getFacultyName($faculty) . "</p>";
                echo "<p><strong>School:</strong> " . getSchoolName($school) . "</p>";

                function getFacultyName($faculty) {
                    global $conn;
                    $query = "SELECT name FROM faculties WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $faculty);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    return $row['name'] ?? 'Unknown';
                }

                function getSchoolName($school) {
                    global $conn;
                    $query = "SELECT name FROM schools WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $school);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    return $row['name'] ?? 'Unknown';
                }

                echo "<p><strong>Phone:</strong> $phone</p>";
                echo "<p><strong>Moodle ID:</strong> $moodle_id</p>";
                echo "<p><strong>Role:</strong> " . ($role == 2 ? "Coordinator" : "Teacher") . "</p>";
                echo "<p><strong>Account Created:</strong> $created_at</p>";
                echo "<p><strong>Last Updated:</strong> $updated_at</p>";

                // Display validation status
                echo "<p><strong>Validation Status:</strong> " . ($isValidated ? "Validated" : "Not Validated") . "</p>";

                // If the user is a coordinator, display additional information
                if ($role == 2) {
                    echo "<h3>Coordinator Specific Information</h3>";
                    echo "<p>As a coordinator, you have additional responsibilities:</p>";
                    echo "<ul>";
                    echo "<li>Review and approve course solicitations</li>";
                    echo "<li>Manage teachers in your faculty</li>";
                    echo "<li>Generate reports on course activities</li>";

                        // Display user permissions
                        echo "<h3>User Permissions</h3>";
                        echo "<p>Your assigned permissions:</p>";
                        echo "<ul>";

                        // Function to get user permissions
                        function getUserPermissions($user_id) {
                            global $conn;
                            $query = "SELECT permission, created_at, updated_at FROM user_permissions WHERE user_id = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $permissions = [];
                            while ($row = $result->fetch_assoc()) {
                                $permissions[] = $row;
                            }
                            return $permissions;
                        }

                        // Get and display user permissions
                        $user_permissions = getUserPermissions($user_id);
                        foreach ($user_permissions as $permission) {
                            echo "<li>{$permission['permission']} (Created: {$permission['created_at']}, Updated: {$permission['updated_at']})</li>";
                        }

                        echo "</ul>";



                    echo "</ul>";
                }
                if ($role == 3) {
                    echo "<h3>Teacher Specific Information</h3>";
                    echo "<p>As a teacher, you can:</p>";
                    echo "<ul>";
                    echo "<li>Submit course solicitations</li>";
                    echo "<li>View your approved and rejected courses</li>";
                    echo "<li>Update your course materials</li>";
                    echo "</ul>";
                }

                // Close the database connection
                $conn->close();
                ?>
