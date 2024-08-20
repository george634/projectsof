<?php
include 'db_connection.php';
include 'navbar.footer.php';

$con = OpenCon(); // Open database connection

// Check if a specific month is selected
$selected_month = isset($_GET['month']) ? $_GET['month'] : null;

if ($selected_month) {
    $query = "SELECT traner, COUNT(member) AS user_count 
              FROM traners 
              WHERE MONTH(date) = ? 
              GROUP BY traner";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $selected_month);
} else {
    $query = "SELECT traner, COUNT(member) AS user_count 
              FROM traners 
              GROUP BY traner";
    $stmt = $con->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

$traner_data = [];
$user_counts = [];

while ($row = $result->fetch_assoc()) {
    $traner_data[] = $row['traner'];
    $user_counts[] = $row['user_count'];
}

$stmt->close();
CloseCon($con); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Bar Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }
        h1 {
            color: #333;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }
        form {
            margin-bottom: 20px;
        }
        select, button {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        canvas {
            max-width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Number of Users by Trainer</h1>
    <form method="GET">
        <label for="month">Choose a month:</label>
        <select name="month" id="month">
            <option value="">All</option>
            <!-- Add options for each month -->
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <canvas id="userBarGraph"></canvas>
</div>

<script>
    const ctx = document.getElementById('userBarGraph').getContext('2d');
    const userBarGraph = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($traner_data); ?>,
            datasets: [{
                label: 'Number of Users',
                data: <?php echo json_encode($user_counts); ?>,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(255, 205, 86, 0.7)',
                    'rgba(201, 203, 207, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(201, 203, 207, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
