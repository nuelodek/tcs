<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Reports</title>
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
        <h2>Risk Reports</h2>
        <h3>Report Details</h3>
        <p>Select the type of report you want to generate:</p>
        <select id="report-type" name="report-type" onchange="showReportSection()">
            <option value=""  selected>Select report type</option>
            <option value="risk-register">Risk Register</option>
            <option value="risk-actions-completed">Risk Actions completed between Dates</option>
            <option value="risk-actions-by-champion">Risk Actions by Data Champion and Completed Dates</option>
            <option value="risks-by-keyword">Risks by keyword</option>
            <option value="risk-actions-not-completed">Risk Actions not completed between dates</option>
            <option value="risks-and-actions">Risks and Actions Report (Departments)</option>
        </select>
        <br>
        <div id="risks-and-actions" class="report-section">
            <h3>Risks and Actions Report</h3>
            <p>How would you like to filter the report?</p>
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
        <div id="keyword" class="report-section">
            <label for="keyword">Keyword (for Risks by keyword):</label>
            <input type="text" id="keyword" name="keyword">
        </div>
        <div id="data-champion" class="report-section">
            <label for="data-champion">Data Champion (for Risk Actions by Data Champion):</label>
            <input type="text" id="data-champion" name="data-champion">
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
                case "risk-register":
                    // No additional fields needed
                    break;
                case "risk-actions-completed":
                case "risk-actions-not-completed":
                    document.getElementById("date-range").style.display = "block";
                    break;
                case "risk-actions-by-champion":
                    document.getElementById("date-range").style.display = "block";
                    document.getElementById("data-champion").style.display = "block";
                    break;
                case "risks-by-keyword":
                    document.getElementById("keyword").style.display = "block";
                    break;
                case "risks-and-actions":
                    document.getElementById("risks-and-actions").style.display = "block";
                    document.getElementById("date-range").style.display = "block";
                    break;
            }
        }

        // Call the function on page load to set initial state
        showReportSection();
    </script>
</body>
</html>