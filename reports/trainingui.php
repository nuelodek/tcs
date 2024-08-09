<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            
        }
        h1, h2, h3 {
            color: #333;
        }
        label {
            display: inline-block;
            margin-bottom: 5px;
        }
        select, input[type="date"], input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .report-section {
            display: none;
        }
    </style>
</head>
<body>
    <form>
        <h2>Training Reports</h2>
        <h3>Report Details</h3>
        <p>Select the type of report you want to generate:</p>
        <select id="report-type" name="report-type" onchange="showReportSection()">
            <option value="" selected>Select report type</option>
            <option value="training-course-summary">Training Course Summary Report</option>
            <option value="phishing-course-report">Phishing Course Report</option>
            <option value="phishing-report-by-department">Phishing Report by Department</option>
            <option value="training-audit">Training Audit (Uninvited Users)</option>
            <option value="training-stats-per-department">Training Stats per Department</option>
        </select>
        <br>
        <div id="department-selection" class="report-section">
            <h3>Department Selection</h3>
            <p>Select the department(s) for the report:</p>
            <label for="departments">Departments:</label>
            <select id="departments" name="departments" multiple>
            <option value="select-one" disabled>Select One or More</option>

                <?php
                // Enable error reporting
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);

                // Custom error handler function
                function customErrorHandler($errno, $errstr, $errfile, $errline) {
                    echo "<div style='color: red; background-color: #ffeeee; border: 1px solid #ff0000; padding: 10px; margin: 10px 0;'>";
                    echo "<strong>Error:</strong> [$errno] $errstr<br>";
                    echo "Error on line $errline in file $errfile";
                    echo "</div>";
                }

                // Set custom error handler
                set_error_handler("customErrorHandler");

                // Try-catch block for exception handling
                try {
                    // Assuming you have a database connection established
                    include '../db.php';

                    if ($conn->connect_error) {
                        throw new Exception("Connection failed: " . $conn->connect_error);
                    }
                    
                    $sql = "SELECT DISTINCT department FROM sub_users ORDER BY department";
                    $result = $conn->query($sql);
                    
                    if ($result === false) {
                        throw new Exception("Query failed: " . $conn->error);
                    }
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row["department"]) . "'>" . htmlspecialchars($row["department"]) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No departments found</option>";
                    }
                    
                    $conn->close();
                } catch (Exception $e) {
                    echo "<option value=''>Error: " . htmlspecialchars($e->getMessage()) . "</option>";
                }
                ?>

            </select>
        </div>
        <div id="date-range" class="report-section">
            <label for="start-date">Start Date:</label>
            <input type="date" id="start-date" name="start-date" value="2023-05-07">
            <br>
            <label for="end-date">End Date:</label>
            <input type="date" id="end-date" name="end-date" value="2023-05-07">
        </div>
        <div id="training-courses" class="report-section">
            <h3>Training Courses</h3>
            <p>Select the training course(s) for the report:</p>
            <label for="courses">Courses:</label>
            <select id="courses" name="courses" multiple>
                <option value="select-one" disabled>Select One or More</option>
                <?php
                try {
                    include '../db.php';

                    if ($conn->connect_error) {
                        throw new Exception("Connection failed: " . $conn->connect_error);
                    }
                    
                    $sql = "SELECT DISTINCT course_name FROM training_courses ORDER BY course_name";
                    $result = $conn->query($sql);
                    
                    if ($result === false) {
                        throw new Exception("Query failed: " . $conn->error);
                    }
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row["course_name"]) . "'>" . htmlspecialchars($row["course_name"]) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No courses found</option>";
                    }
                    
                    $conn->close();
                } catch (Exception $e) {
                    echo "<option value=''>Error: " . htmlspecialchars($e->getMessage()) . "</option>";
                }
                ?>
            </select>
        </div>
        <br>
        <input type="submit" value="Generate Report">
    </form>

    <script>
        function showReportSection() {
            var reportType = document.getElementById("report-type").value;
            var sections = document.getElementsByClassName("report-section");
            
            for (var i = 0; i < sections.length; i++) {
                sections[i].style.display = "none";
            }

            switch(reportType) {
                case "training-course-summary":
                case "phishing-course-report":
                    document.getElementById("date-range").style.display = "block";
                    break;
                case "phishing-report-by-department":
                case "training-stats-per-department":
                    document.getElementById("department-selection").style.display = "block";
                    document.getElementById("date-range").style.display = "block";
                    break;
                case "training-audit":
                    document.getElementById("date-range").style.display = "block";
                    document.getElementById("training-courses").style.display = "block";
                    break;
            }
        }

        // Call the function on page load to set initial state
        showReportSection();
    </script>
</body>
</html>