<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Management Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 500px;
        }
        .dashboard h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .dashboard a {
            display: block;
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 14px 20px;
            margin-bottom: 15px;
            text-align: center;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
        }
        .dashboard a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h1>Risk Management Dashboard</h1>

    <!-- Links to different pages for adding Data -->
    <a href="mergedrisk.php">Risk Register</a>
    <a href="risk_actions_completed_page.php">Risk Actions Completed</a>
    <a href="risk_actions_by_champion_page.php">Risk Actions by Data Champion</a>
    <a href="risks_by_keyword_page.php">Risks by Keyword</a>
    <a href="risk_profile_summary_page.php">Risk Profile Summary</a>
    <a href="risk_profile_detail_page.php">Risk Profile Detail</a>
    <a href="risk_actions_not_completed_page.php">Risk Actions Not Completed</a>
</div>

</body>
</html>
