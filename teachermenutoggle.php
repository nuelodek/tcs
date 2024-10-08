          <h2> Hi <?php echo $firstname; ?>, what would you like to do today?</h2>
        
          <?php if ($role == 2): ?>
        
          <a href="#" class="pendingcourses" style="margin-right: 15px; text-decoration: none;" onclick="togglePendingCourse()">Check Course Status </a>

          <?php endif; ?>
            <?php

            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            require_once 'db.php';
            $user_id = $id;
            $sql = "SELECT permission FROM user_permissions WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $permissions = $row['permission'];
            } else {
                $permissions = '';
            }
            ?>

                <?php if ($role == 3 || ($role == 2 && strpos($permissions, 'solicit') !== false)): ?>

                <a href="#" class="checkusers" style="margin-right: 15px; text-decoration: none;" onclick="togglesolicitCourseUser()">Solicit Course</a>

                <a href="#" class="check-all-courses" style="margin-right: 15px; text-decoration: none;" onclick="toggleAllCoursesUser()">All Courses (<?php
                    require_once 'db.php';
                    $user_id = $id;
                    $sql = "SELECT COUNT(*) as count FROM Requests WHERE Applicant_Id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    echo $row['count'];
                    ?>)</a>

                <a href="#" class="check-approved-courses" style="margin-right: 15px; text-decoration: none;" onclick="toggleApprovedCoursesUser()"> Approved Courses (<?php
                    require_once 'db.php';
                    $user_id = $id;
                    $sql = "SELECT COUNT(*) as count FROM Requests WHERE Status_Id = 2 AND Applicant_Id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    echo $row['count'];
                    ?>)</a>

                <a href="#" class="check-solicitations" style="margin-right: 15px; text-decoration: none;" onclick="toggleRejectedCoursesUser()">Rejected Courses (<?php
                    require_once 'db.php';
                    $user_id = $id;
                    $sql = "SELECT COUNT(*) as count FROM Requests WHERE Status_Id = 3 AND Applicant_Id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    echo $row['count'];
                    ?>)</a>
       

                <a href="#" class="check-activitylog" style="margin-right: 15px; text-decoration: none;" onclick="toggleActivityLogUser()">Activity Log (<?php
                    require_once 'db.php';
                    $user_id = $id;
                    $sql = "SELECT COUNT(*) as count FROM logs WHERE User_Id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    echo $row['count'];
                    ?>)</a> 
        
              
                <?php endif; ?>
                <a href="#" class="check-userprofile" style="margin-right: 15px; text-decoration: none;" onclick="toggleupdateProfileUser()">Update Profile (<?php
                    require_once 'db.php';
                    $sql = "SELECT Role FROM Roles WHERE Id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $role);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    echo $row['Role'];
                ?>)</a>

                
                <a href="#" class="check-info" style="margin-right: 15px; text-decoration: none;" onclick="toggleInformationTab()">Information Tab</a>
                                
                
                <a href="logout.php" class="logout-button" style="text-decoration: none;">Logout</a>
                <script>
        function toggleElement(elementClass) {
            const elements = [
                ".solicitcoursecheck",
                ".allcoursescheck",
                ".approvedcoursescheck",
                ".rejectedcoursescheck",
                ".activitylogcheck",
                ".updateprofilecheck",
                ".coursescheck",
                ".informationcheck"
            ];

            elements.forEach(el => {
                const element = document.querySelector(el);
                if (element) {
                    if (el === elementClass) {
                        element.style.display = "block";
                        element.style.transform = "translateX(0)";
                        element.style.opacity = "1";
                    } else {
                        element.style.transform = "translateX(-100%)";
                        element.style.opacity = "0";
                        setTimeout(() => {
                            element.style.display = "none";
                        }, 300);
                    }
                }
            });

            const menuItems = document.querySelectorAll('a[onclick^="toggle"]');
            menuItems.forEach(item => {
                item.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        function togglesolicitCourseUser() {
            toggleElement(".solicitcoursecheck");
        }

        function toggleAllCoursesUser() {
            toggleElement(".allcoursescheck");
        }

        function toggleApprovedCoursesUser() {
            toggleElement(".approvedcoursescheck");
        }

        function toggleRejectedCoursesUser() {
            toggleElement(".rejectedcoursescheck");
        }

        function toggleActivityLogUser() {
            toggleElement(".activitylogcheck");
        }

        function toggleupdateProfileUser() {
            toggleElement(".updateprofilecheck");
        }

        function togglePendingCourse() {
            toggleElement(".coursescheck");
        }

        function toggleInformationTab() {
            toggleElement(".informationcheck");
        }

        </script>
        <style>
        a[onclick^="toggle"].active {
            font-weight: bold;
        }
        .solicitcoursecheck,
        .allcoursescheck,
        .approvedcoursescheck,
        .rejectedcoursescheck,
        .activitylogcheck,
        .updateprofilecheck, 
        .coursescheck,
        .informationcheck {
            transition: all 0.3s ease-in-out;
            transform: translateX(-100%);
            opacity: 0;
        }
        </style>
