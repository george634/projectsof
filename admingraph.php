<?php
include 'db_connection.php';
include 'navbar.footer.php';

$con = OpenCon(); // Open database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .button-container {
            text-align: center;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 15px 30px;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }
        button:hover {
            background-color: #218838;
        }
        .secondary-button {
            background-color: #007bff;
        }
        .secondary-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="button-container">
    <button onclick="location.href='adminusersgraph.php'">Go to Admin Users Graph</button>
    <button class="secondary-button" onclick="location.href='ordersgraph.php'">Go to Orders Graph</button>
</div>

</body>
</html>
