    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Programme of Work Reports</title>
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
            <h2>Programme of Work Reports</h2>
            <h3>Report Details</h3>
            <p>Select the type of report you want to generate:</p>
            <select id="report-type" name="report-type" onchange="showReportSection()">
                <option value="" selected>Select report type</option>
                <option value="summary-report">Summary Report</option>
                <option value="pow-tasks-all">Programme of Work - Tasks (all)</option>
                <option value="pow-tasks-filtered">Programme of Work - Tasks (filtered)</option>
                <option value="tasks-completed">Tasks completed between dates</option>
                <option value="tasks-overdue">Tasks overdue</option>
                <option value="tasks-due">Tasks due for completion between Due Dates</option>
            </select>
            <br>
            <div id="pow-tasks-all" class="report-section">
                <h3>Programme of Work - Tasks (all)</h3>
                <p>How would you like to filter the report?</p>
                <label for="programme">Programme:</label>
                <select id="programme" name="programme">
                    <option value="select-one" selected disabled>Select One</option>
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
                
                        $sql = "SELECT DISTINCT programme FROM tasks ORDER BY programme";
                        $result = $conn->query($sql);
                
                        if ($result === false) {
                            throw new Exception("Query failed: " . $conn->error);
                        }
                
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($row["programme"]) . "'>" . htmlspecialchars($row["programme"]) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No programmes found</option>";
                        }
                
                        $conn->close();
                    } catch (Exception $e) {
                        echo "<option value=''>Error: " . htmlspecialchars($e->getMessage()) . "</option>";
                    }
                    ?>
                </select>
                <br>
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="select-one" selected disabled>Select One</option>
                    <option value="Not Started">Not Started</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="On Hold">On Hold</option>
                </select>
            </div>
            <div id="pow-tasks-filtered" class="report-section">
                <h3>Programme of Work - Tasks (filtered)</h3>
                <p>How would you like to filter the report?</p>
                <label for="programmes">Programmes:</label>
                <select id="programmes" name="programmes" multiple>
                <option value="select-one" disabled>Select One or More</option>
                    <?php
                    // Reuse the same code to populate programmes
                    try {
                        include '../db.php';

                        if ($conn->connect_error) {
                            throw new Exception("Connection failed: " . $conn->connect_error);
                        }
                
                        $sql = "SELECT DISTINCT programme FROM tasks ORDER BY programme";
                        $result = $conn->query($sql);
                
                        if ($result === false) {
                            throw new Exception("Query failed: " . $conn->error);
                        }
                
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($row["programme"]) . "'>" . htmlspecialchars($row["programme"]) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No programmes found</option>";
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
                    case "summary-report":
                        // No additional fields needed
                        break;
                    case "pow-tasks-all":
                        document.getElementById("pow-tasks-all").style.display = "block";
                        break;
                    case "pow-tasks-filtered":
                        document.getElementById("pow-tasks-filtered").style.display = "block";
                        break;
                    case "tasks-completed":
                    case "tasks-due":
                        document.getElementById("date-range").style.display = "block";
                        break;
                    case "tasks-overdue":
                        // No additional fields needed
                        break;
                }
            }

            // Call the function on page load to set initial state
            showReportSection();
        </script>
    </body>
    </html>