<?php
include 'db.php';

// Fetch data from the database
$company_id = 'YNT3574'; // Replace with session id if needed

$sql = "SELECT reference_number, risk_impact, risk_likelihood 
        FROM risk_info
        WHERE company_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $company_id);
$stmt->execute();
$result = $stmt->get_result();

// Process data
$reference_numbers = [];
$impacts = [];
$likelihoods = [];

while ($row = $result->fetch_assoc()) {
    $reference_numbers[] = $row['reference_number'];
    $impacts[] = (int)$row['risk_impact'];
    $likelihoods[] = (int)$row['risk_likelihood'];
}

$stmt->close();
$conn->close();

// Prepare data for Chart.js
$labels = json_encode($reference_numbers);
$data_impact = json_encode($impacts);
$data_likelihood = json_encode($likelihoods);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Impact and Likelihood Report</title>
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
                <h1>Risk Impact and Likelihood Report</h1>
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
                    label: 'Impact',
                    data: <?php echo $data_impact; ?>,
                    backgroundColor: 'rgba(135, 206, 250, 0.8)',
                    borderColor: 'rgba(0, 0, 128, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Likelihood',
                    data: <?php echo $data_likelihood; ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.8)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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
                            text: 'Value'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Reference Number'
                        }
                    }
                }
            }
        });

        document.getElementById('downloadBtn').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            doc.text('Risk Impact and Likelihood Report', 105, 15, null, null, 'center');
            doc.setFontSize(12);
            doc.text('Dated: <?php echo date('Y-m-d'); ?>', 105, 22, null, null, 'center');
            doc.setFontSize(16);
            
            const canvas = document.getElementById('myChart');
            const imgData = canvas.toDataURL('image/png');
            doc.addImage(imgData, 'PNG', 10, 30, 190, 100);
            
            doc.save('risk_impact_likelihood_report.pdf');
        });
    </script>
</body>
</html>
