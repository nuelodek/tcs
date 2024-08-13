    <form method="GET" action="" style="margin-bottom: 10px;">
        <label for="status_filter" style="margin-right: 10px;">Filter by Status:</label>
        <select name="status_filter" id="status_filter" onchange="this.form.submit()" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
            <option value="">Select Status</option>
            <option value="1">Request Submitted</option>
            <option value="2">Request Approved</option>
            <option value="3">Request Rejected</option>
            <option value="4">Request Created</option>
        </select>
    </form>

<?php if (isset($_GET['status_filter']) && $_GET['status_filter'] !== ''): ?>
<div class="courses-list">
    <?php
    $status_message = '';
    switch ($_GET['status_filter']) {
        case '1':
            $status_message = 'Submitted Courses';
            break;
        case '2':
            $status_message = 'Approved Courses';
            break;
        case '3':
            $status_message = 'Rejected Courses';
            break;
        case '4':
            $status_message = 'Created Courses';
            break;
    }
    if ($status_message): ?>
        <h2><?php echo $status_message; ?></h2>
    <?php endif; ?>
<?php else: ?>
<div class="courses-list" style="display: none">
<?php endif; ?>
    <?php   
    
    $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to join courses and requests and filter by Status_Id
    $sql = "SELECT c.Id, c.Category_Id, c.Name, c.Period_Id, c.Type_of_Course_Id, c.Descriptive_Synthesis, c.Development_Competencies, c.Content_Structure, c.Teaching_Strategies, c.Technology_Tools, c.Assessment_Strategies, c.Programmatic_Synopsis, r.Status_Id, r.Id AS Request_Id, r.Applicant_Id, r.rejection_reason,
            cat.Name AS Category_Name, CONCAT(toc.Name, ' - ', toc.Field) AS Type_of_Course_Name, p.Name AS Period_Name
            FROM courses c
            JOIN requests r ON c.Id = r.Course_Id
            JOIN categories cat ON c.Category_Id = cat.Id
            JOIN types_of_courses toc ON c.Type_of_Course_Id = toc.Id
            JOIN periods p ON c.Period_Id = p.Id
            WHERE r.Status_Id IN (1, 2, 3, 4) AND r.Coordinator_Id = " . $_SESSION['id'];
    if ($status_filter) {
        $sql .= " AND r.Status_Id = " . intval($status_filter);
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div style='overflow-x: auto;'>";
        echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
        echo "<thead>";
        echo "<tr style='background-color: #f2f2f2;'>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>ID</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Name</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Category ID</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Period ID</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Type of Course ID</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Descriptive Synthesis</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Development Competencies</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Content Structure</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Teaching Strategies</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Technology Tools</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Assessment Strategies</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Programmatic Synopsis</th>";
        echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Status ID</th>";
       

        
        if ($status_filter == '1') {
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>State</th>";
        }

        
        if ($status_filter == '2') {
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>State</th>";
        }


        if ($status_filter == '3') {
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Rejection Reason</th>";
        }

        if ($status_filter == '4') {
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Course approved by admin</th>";
        }
       
       
        if ($status_filter == '1') {
            echo "<th style='padding: 12px; text-align: left; border-bottom: 2px solid #ddd;'>Action</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr style='border-bottom: 1px solid #ddd;'>";
            echo "<td style='padding: 12px;'>" . $row["Id"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Name"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Category_Name"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Period_Name"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Type_of_Course_Name"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Descriptive_Synthesis"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Development_Competencies"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Content_Structure"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Teaching_Strategies"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Technology_Tools"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Assessment_Strategies"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Programmatic_Synopsis"] . "</td>";
            echo "<td style='padding: 12px;'>" . $row["Status_Id"] . "</td>";
            if ($status_filter == '1') {
                echo "<td style='padding: 12px;'>Course waiting for approval</td>";
            }
            
            if ($status_filter == '2') {
                echo "<td style='padding: 12px;'>Course waiting for creation by admin</td>";
            }
            
            if ($status_filter == '3') {
                echo "<td style='padding: 12px;'>" . $row["rejection_reason"] . "</td>";
            }

              if ($status_filter == '4') {
                  echo "<td style='padding: 12px;'>Course created by admin</td>";
              }






            if ($status_filter == '1') {
                echo "<td style='padding: 12px;'>
                    <form action='courseaction.php' method='post'>
                        <input type='hidden' name='request_id' value='" . $row['Request_Id'] . "'>
                        <input type='hidden' name='id' value='" . $row['Id'] . "'>
                        <input type='hidden' name='name' value='" . $row['Name'] . "'>
                        <input type='hidden' name='category_id' value='" . $row['Category_Id'] . "'>
                        <input type='hidden' name='period_id' value='" . $row['Period_Id'] . "'>
                        <input type='hidden' name='type_of_course_id' value='" . $row['Type_of_Course_Id'] . "'>
                        <input type='hidden' name='descriptive_synthesis' value='" . $row['Descriptive_Synthesis'] . "'>
                        <input type='hidden' name='development_competencies' value='" . $row['Development_Competencies'] . "'>
                        <input type='hidden' name='content_structure' value='" . $row['Content_Structure'] . "'>
                        <input type='hidden' name='teaching_strategies' value='" . $row['Teaching_Strategies'] . "'>
                        <input type='hidden' name='technology_tools' value='" . $row['Technology_Tools'] . "'>
                        <input type='hidden' name='assessment_strategies' value='" . $row['Assessment_Strategies'] . "'>
                        <input type='hidden' name='programmatic_synopsis' value='" . $row['Programmatic_Synopsis'] . "'>
                        <input type='hidden' name='status_id' value='" . $row['Status_Id'] . "'>
                        <input type='hidden' name='applicant_id' value='" . $row['Applicant_Id']."'>                        
                       
                                                  <button type='submit' name='action' value='approve' class='approvebutton' onclick='removeRequired()'>Approve Course</button>
                                                  <button type='button' onclick='showRejectionReason(this)' class='rejectbutton'>Reject Course</button>
                                                  <div id='rejectionReasonContainer' style='display:none;'>
                                                      <input type='text' name='rejection_reason' id='rejection_reason' placeholder='Reason for rejection'>
                                                      <button type='submit' name='action' value='reject' onclick='addRequired()'>Reject Course</button>
                                                  </div>
                                                  <script>
                                                      function showRejectionReason(button) {
                                                          button.style.display = 'none';
                                                          document.getElementById('rejectionReasonContainer').style.display = 'block';
                                                      }
                                                      function addRequired() {
                                                          document.getElementById('rejection_reason').setAttribute('required', '');
                                                      }
                                                      function removeRequired() {
                                                          document.getElementById('rejection_reason').removeAttribute('required');
                                                      }
                                                  </script>
                  
                        </form>
                </td>";
            }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        if ($status_filter == '1') {
            echo "No courses have been submitted for your review.<br> Please check back later.";
        } elseif ($status_filter == '2') {
            echo "You are yet to approve any course assigned to you. <br> Please check back later.";
        } elseif ($status_filter == '3') {
            echo "You are yet to reject any course submitted for review. <br> Please check back later.";
        } elseif ($status_filter == '4') {
            echo "Your approved courses are yet to be created by the admin. <br> Please check back later.";
        } else {
            echo "No courses found matching the selected criteria. <br";
        }
    }

    $conn->close();
    ?>
</div>
