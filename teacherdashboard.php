







<?php 
session_start();
include 'header.php'; 



?>


request_submitted 
request_approved 
request_rejected
request_created






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .dashboard {
            /* max-width: 800px; */
            margin: 20px auto;
            padding: 20px;
            background-color:white;
            border-radius: 5px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .user-profile {
            display: flex;
            align-items: center;
        }
        .user-profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .buttons {
            display: flex;
            gap: 10px;
        }
        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .logout {
            background-color: #ff4444;
            color: white;
        }
        .solicit-course {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

<?php
require 'header.php';
?>

    <div class="dashboard">
        <div class="header">





            <div class="user-profile">
                <img src="https://via.placeholder.com/50" alt="User Avatar">
                <span><?php echo isset($firstname) ? $firstname : 'John Doe'; ?></span>            </div>
            <div class="buttons">
                <button class="button solicit-course">Solicit Course</button>
                <button class="button logout">Logout</button>
            </div>
        </div>
        <div class="content">
            <h2>Welcome to your Dashboard</h2>
            <p>Here you can manage your courses and account settings.</p>
        </div>
    </div>
    <script>
        document.querySelector('.logout').addEventListener('click', function() {
            alert('Logout functionality to be implemented');
        });
        document.querySelector('.solicit-course').addEventListener('click', function() {
            alert('Course solicitation functionality to be implemented');
        });
    </script>
</body>
</html>
