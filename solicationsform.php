            <h2>Solicit for Courses</h2>
            <form action="solicit_course.php" method="post">
        
            <input type="hidden" name="applicant_id" value="<?php echo $id; ?>" readonly>

            <label for="courdinator__id">Coordinator:</label>
            <select id="courdinator__id" name="cordinator_id" required>
            <option value="" disabled selected>Select Coordinator</option>
    <?php
        require 'db.php';
        
        
        // Merged database queries
        $queries = [
            "SELECT Id, First_Name, Last_Name FROM Users WHERE Role_Id = 2 AND Id != ?",
            "SELECT id, name FROM categories WHERE status = 'approved'",
            "SELECT Id, Name FROM periods",
            "SELECT Id, Name, Field FROM types_of_courses"
        ];

        $results = [];

        foreach ($queries as $index => $query) {
            $stmt = $conn->prepare($query);
            if ($index === 0) {
                $stmt->bind_param("i", $user_id);
            }
            $stmt->execute();
            $results[$index] = $stmt->get_result();
        }

        // Coordinators
        if ($results[0]->num_rows > 0) {
            while($row = $results[0]->fetch_assoc()) {
                echo "<option value=\"" . $row["Id"] . "\">" . $row["First_Name"] . " " . $row["Last_Name"] . "</option>";
            }
        } else {
            echo "<option value=\"\">No coordinators found</option>";
        }
    ?>
    </select>
        
                <label for="category_id"> Course Category: </label>
                <select id="category_id" name="category_id" required onchange="checkOthers(this)">
                    <option value="" disabled selected>Select Category</option>
                    <?php
                    // Categories
                    if ($results[1]->num_rows > 0) {
                        while($row = $results[1]->fetch_assoc()) {
                            echo "<option value=\"" . $row["id"] . "\">" . $row["name"] . "</option>";
                        }
                    } else {
                        echo "<option value=\"\">No approved categories found</option>";
                    }
                    ?>
                    <option value="others">Others</option>
                </select>

                <div class="latestcategory" id="latestcategory"  style="display:none;">
                <label> New Category: </label>
                <input type="text" id="otherCategory" name="otherCategory" placeholder="Enter new category">
                        </div>
                <script>
                function checkOthers(select) {
                    if (select.value === 'others') {
                        document.getElementById('latestcategory').style.display = 'block';
                    } else {
                        document.getElementById('latestcategory').style.display = 'none';
                    }
                }
                </script>
        
            
            
                <br>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <br>
                        <label for="period_id">Period</label>
                        <select id="period" name="period" required>
                        <?php
                        // Periods
                        while($row = $results[2]->fetch_assoc()) {
                            echo "<option value='" . $row['Id'] . "'>" . $row['Name'] . "</option>";
                        }
                        ?>
                        </select>
                 
                            <label for="type_of_course_id">Type of Course</label>
                            <select id="type_of_course_id" name="type_of_course_id" required>
                            <?php
                            // Types of courses
                            while($row = $results[3]->fetch_assoc()) {
                                echo "<option value='" . $row['Id'] . "'>" . $row['Name'] . " - " . $row['Field'] . "</option>";
                            }
                            ?>
                            </select>                  
                                    
                                        <br />
                                                              
                                                              
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
                <br><br><br>

                <button type="submit">Solicit Course</button>

            </form>       
