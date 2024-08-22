<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Champions Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Data Champions Report</h1>
        <form action="mergedrisk.php" method="GET">
            <input type="hidden" name="report_type" value="risk_data_champions">
            
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
            
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
            
            <div class="form-group">
                <label for="data_champion">Data Champion (optional):</label>
                <select class="form-control" id="form-control" name="assign_to">
                <option value="" disabled>Select data champion</option>
                <?php
                include 'db.php';
                $company_id = 'YNT3574';

                $query = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM users WHERE company_id = ? AND role = 'dpo'";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $company_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                echo "<option value='" .  $row['full_name'] . "'>" . $row['full_name'] . "</option>";
                }
                $stmt->close();
                ?>
                </select>                      
              </div>            
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Set default dates
        document.getElementById('start_date').value = '2017-05-07';
        document.getElementById('end_date').value = '2017-05-07';
    </script>
</body>
</html>
