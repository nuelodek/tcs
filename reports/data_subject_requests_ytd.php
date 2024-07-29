<?php
include 'db.php';

// Fetch data subject requests from the database
$company_id = 'YNT3574'; // Replace with session id if needed

$sql = "SELECT reference_number, received_date, type_of_request 
        FROM datasubjectrequest
        WHERE company_id = ? AND YEAR(received_date) = YEAR(CURDATE())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $company_id);
$stmt->execute();
$result = $stmt->get_result();

// Process data to group by month
$data_by_month = array_fill(1, 12, 0); // Initialize an array with 12 zeros for each month

while ($row = $result->fetch_assoc()) {
    $month = (int)date('m', strtotime($row['received_date']));
    $data_by_month[$month]++;
}

$stmt->close();
$conn->close();

$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

// Prepare data for Chart.js
$labels = json_encode($months);
$data = json_encode(array_values($data_by_month));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Subject Requests Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        #downloadBtn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        #downloadBtn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Data Subject Requests Report (Year - to - Date)</h1>
                <p><strong>Dated: <?php echo date('Y-m-d'); ?></strong></p>
            </div>
            <button id="downloadBtn">Download as PDF</button>
        </div>
        <canvas id="myChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $labels; ?>,
                datasets: [{
                    label: 'Number of Requests',
                    data: <?php echo $data; ?>,
                    backgroundColor: 'rgba(135, 206, 250, 0.8)',
                    borderColor: 'rgba(0, 0, 128, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Requests'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });

        document.getElementById('downloadBtn').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            doc.text('Data Subject Requests Report', 105, 15, null, null, 'center');
            doc.setFontSize(12);
            doc.text('Dated: <?php echo date('Y-m-d'); ?>', 105, 22, null, null, 'center');
            doc.setFontSize(16);
            
            const canvas = document.getElementById('myChart');
            const imgData = canvas.toDataURL('image/png');
            doc.addImage(imgData, 'PNG', 10, 30, 190, 100);
            
            doc.save('data_subject_requests_report.pdf');
        });
    </script>
</body>
</html>