        <?php

        include 'db.php';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database connection

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $applicant_id = $_POST['applicant_id'];
            $request_id = $_POST['request_id'];
            $action = $_POST['action'];
            $request_name = "Course Request Update";
            $rejection_reason = $_POST['rejection_reason'];
            $new_status_id = ($action == 'approve') ? 2 : 3;

            // Check if rejection_reason column exists
            $check_column = "SHOW COLUMNS FROM requests LIKE 'rejection_reason'";
            $result = $conn->query($check_column);
        
            if ($result->num_rows == 0 && $action == 'reject') {
                // Add rejection_reason column if it doesn't exist
                $add_column = "ALTER TABLE requests ADD COLUMN rejection_reason TEXT";
                if (!$conn->query($add_column)) {
                    echo "Error adding rejection_reason column: " . $conn->error;
                    $conn->close();
                    exit;
                }
            }

            // Update the status in the requests table
            $sql = "UPDATE requests SET Name = ?, Status_Id = ?, Date_Update = NOW()";
            $params = array($request_name, $new_status_id);
            $types = "si";

            if ($action == 'reject') {
                $sql .= ", rejection_reason = ?";
                $params[] = $rejection_reason;
                $types .= "s";
            }

            $sql .= " WHERE Id = ?";
            $params[] = $request_id;
            $types .= "i";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                echo "Request " . ($action == 'approve' ? "approved" : "rejected") . " successfully.";
                
                // Log the action in the logs table
                $log_action = ($action == 'approve') ? "Approved Course Request" : "Request Denied";
                $log_sql = "INSERT INTO logs (Action, Date, User_Id) VALUES (?, NOW(), ?)";
                $log_stmt = $conn->prepare($log_sql);
                $log_stmt->bind_param("si", $log_action, $applicant_id);
                
                if ($log_stmt->execute()) {
                    echo " Action logged successfully.";
                } else {
                    echo " Error logging action: " . $conn->error;
                }
                
                $log_stmt->close();
            } else {
                echo "Error updating status: " . $conn->error;
            }

            $stmt->close();
            $conn->close();
        }
        ?>
