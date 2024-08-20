<?php
session_start();
include 'db_connection.php';
include 'navbar.footer.php';

$con = OpenCon(); // Open database connection

$currentYear = date('Y');
$currentMonth = date('m');
$today = date('Y-m-d');

$username = $_SESSION['Username'];

// Initialize the selected year, month, total points, days, and average points
$selectedYear = isset($_POST['year']) ? (int)$_POST['year'] : $currentYear;
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : $currentMonth;
$totalPoints = 0;
$totalDays = 0;
$averagePoints = 0;

// Get user ID from the username
$userQuery = "SELECT id FROM users WHERE username = '$username'";
$userResult = mysqli_query($con, $userQuery);

if (mysqli_num_rows($userResult) > 0) {
    $userData = mysqli_fetch_assoc($userResult);
    $userId = $userData['id'];

    // Check if the user's ID exists in the manager table
    $managerQuery = "SELECT * FROM manager WHERE manager_id = '$userId'";
    $managerResult = mysqli_query($con, $managerQuery);

    // Check if the user's ID exists in the admin table
    $adminQuery = "SELECT * FROM admin WHERE id = '$userId'";
    $adminResult = mysqli_query($con, $adminQuery);

    if (mysqli_num_rows($managerResult) > 0) {
        echo "<h2>Manager Stats</h2>";

        // First dropdown for selecting between "All Users" or "My Members"
        echo '<form method="post" class="manager-stats-form">';
        echo '<label for="userType">Select User Type:</label>';
        echo '<select name="userType" id="userType" class="form-select" onchange="updateUserDropdown()">';
        echo '<option value="all">All Users </option>';
        echo '<option value="members">My Members</option>';
        echo '</select>';

        // Second dropdown for selecting the actual user
        echo '<label for="selectedUser">Select User:</label>';
        echo '<select name="selectedUser" id="selectedUser" class="form-select">';
        echo '<option value="">Select a user</option>';
        echo '</select>';

        // Year and month dropdowns
        echo '<label for="year">Year:</label>';
        echo '<select name="year" id="year" class="form-select">';
        for ($i = $currentYear - 5; $i <= $currentYear; $i++):
            $selected = ($i == $selectedYear) ? 'selected' : '';
            echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
        endfor;
        echo '</select>';

        echo '<label for="month">Month:</label>';
        echo '<select name="month" id="month" class="form-select">';
        echo '<option value="all" '.(($selectedMonth == 'all') ? 'selected' : '').'>All Months</option>';
        for ($i = 1; $i <= 12; $i++) {
            $selected = ($i == $selectedMonth) ? 'selected' : '';
            echo '<option value="'.$i.'" '.$selected.'>'.date('F', mktime(0, 0, 0, $i, 10)).'</option>';
        }
        echo '</select>';

        echo '<button type="submit" class="form-button"><i class="fas fa-chart-bar"></i> View Stats</button>';
        echo '</form>';

        // Fetching data for the dropdowns
        $allUsersQuery = "
            SELECT u.username FROM users u
            LEFT JOIN manager m ON u.id = m.manager_id
            LEFT JOIN admin a ON u.id = a.id
            WHERE m.manager_id IS NULL AND a.id IS NULL
        ";

        $membersQuery = "
            SELECT u.username FROM users u
            INNER JOIN traners t ON u.username = t.member
            WHERE t.traner = '$username'
        ";

        // Execute the queries and store the results in arrays
        $allUsersResult = mysqli_query($con, $allUsersQuery);
        $membersResult = mysqli_query($con, $membersQuery);

        $allUsers = [];
        $members = [];

        while ($row = mysqli_fetch_assoc($allUsersResult)) {
            $allUsers[] = $row['username'];
        }

        while ($row = mysqli_fetch_assoc($membersResult)) {
            $members[] = $row['username'];
        }

        // Do not close the connection here

    } elseif (mysqli_num_rows($adminResult) > 0) {
        echo "<script>
        alert('all good.');
 window.location.href = 'admingraph.php';
    </script>";
    } else {
        echo "<h2>Your Stats</h2>";

        // Form for users to select year and month
        echo '<form method="post" class="stats-form">';
        echo '<label for="year">Year:</label>';
        echo '<select name="year" id="year" class="form-select">';
        for ($i = $currentYear - 5; $i <= $currentYear; $i++):
            $selected = ($i == $selectedYear) ? 'selected' : '';
            echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
        endfor;
        echo '</select>';

        echo '<label for="month">Month:</label>';
        echo '<select name="month" id="month" class="form-select">';
        echo '<option value="all" '.(($selectedMonth == 'all') ? 'selected' : '').'>All Months</option>';
        for ($i = 1; $i <= 12; $i++) {
            $selected = ($i == $selectedMonth) ? 'selected' : '';
            echo '<option value="'.$i.'" '.$selected.'>'.date('F', mktime(0, 0, 0, $i, 10)).'</option>';
        }
        echo '</select>';

        echo '<button type="submit" class="form-button"><i class="fas fa-chart-bar"></i> View Stats</button>';
        echo '</form>';
    }

    if (isset($_POST['selectedUser']) || !empty($username)) {
        // Check if a user was selected or if it's a regular user viewing their own stats
        $username = isset($_POST['selectedUser']) ? $_POST['selectedUser'] : $username;

        // Query and calculate stats based on selected month/year
        if ($selectedMonth == 'all') {
            $query = "SELECT date, points FROM fainalstats WHERE username = ? AND YEAR(date) = ? AND date <= ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("sis", $username, $selectedYear, $today);
        } else {
            $selectedMonth = (int)$selectedMonth;
            if ($selectedYear == $currentYear && $selectedMonth == $currentMonth) {
                $query = "SELECT date, points FROM fainalstats WHERE username = ? AND YEAR(date) = ? AND MONTH(date) = ? AND date <= ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("siis", $username, $selectedYear, $selectedMonth, $today);
            } else {
                $query = "SELECT date, points FROM fainalstats WHERE username = ? AND YEAR(date) = ? AND MONTH(date) = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("sii", $username, $selectedYear, $selectedMonth);
            }
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
            $totalPoints += $row['points'];
            $totalDays++;
        }

        $averagePoints = $totalDays > 0 ? $totalPoints / $totalDays : 0;

        $stmt->close();
    } else {
        echo "<p>No user selected.</p>";
    }
} else {
    echo "<p>User not found.</p>";
}

// Now close the connection after all database operations are done
CloseCon($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Stats Graph</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            color: #555;
        }

        p {
            text-align: center;
            font-size: 18px;
            margin: 5px 0;
        }

        #statsChart {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        canvas {
            width: 1000px !important;
            height: 600px !important;
        }

        footer {
            background-color: #222;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: sticky;
            bottom: 0;
            width: 100%;
        }

        .stats-form {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        gap: 10px;
        flex-wrap: wrap;
    }

    .stats-form label {
        font-weight: bold;
        margin-right: 10px;
    }

    .form-select {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
        background-color: #fff;
        color: #333;
    }

    .form-button {
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        font-size: 16px;
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-button:hover {
        background-color: #45a049;
    }

    @media (max-width: 768px) {
        .stats-form {
            flex-direction: column;
        }

        .form-select, .form-button {
            width: 100%;
            max-width: 300px;
        }
    }
    .manager-stats-form {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        gap: 10px;
        flex-wrap: wrap;
    }

    .manager-stats-form label {
        font-weight: bold;
        margin-right: 10px;
    }

    .form-select {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
        background-color: #fff;
        color: #333;
    }

    .form-button {
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        font-size: 16px;
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-button:hover {
        background-color: #45a049;
    }

    @media (max-width: 768px) {
        .manager-stats-form {
            flex-direction: column;
        }

        .form-select, .form-button {
            width: 100%;
            max-width: 300px;
        }
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <div class="content">
            <h1>Your Stats for 
            <?php 
            if ($selectedMonth == 'all') {
                echo "All Months $selectedYear";
            } else {
                echo date('F Y', strtotime("$selectedYear-$selectedMonth-01")); 
            }
            ?>
            </h1>

            <h2>Total Stats</h2>
            <p>Total Points: <?php echo $totalPoints; ?></p>
            <p>Total Days: <?php echo $totalDays; ?></p>
            <p>Average Points per Day: <?php echo number_format($averagePoints, 2); ?></p>

            <div id="statsChart">
                <canvas id="chartCanvas"></canvas>
            </div>
        </div>
        
        <footer>
            &copy; 2024 My Gym. All rights reserved.
        </footer>
    </div>

    <script>
        const ctx = document.getElementById('chartCanvas').getContext('2d');
        const chartData = {
            labels: <?php echo json_encode(array_map(function($date) {
                return date('Y-m-d', strtotime($date));
            }, array_column($data, 'date'))); ?>,
            datasets: [{
                label: 'Points',
                data: <?php echo json_encode(array_column($data, 'points')); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        const config = {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        new Chart(ctx, config);
    </script>
    <script>
    const allUsers = <?php echo json_encode($allUsers); ?>;
    const members = <?php echo json_encode($members); ?>;

    function updateUserDropdown() {
        const userType = document.getElementById('userType').value;
        const userDropdown = document.getElementById('selectedUser');

        // Clear current options
        userDropdown.innerHTML = '';

        // Add default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Select a user';
        userDropdown.appendChild(defaultOption);

        let userList = [];

        // Populate the dropdown based on the selected user type
        if (userType === 'all') {
            userList = allUsers;
        } else if (userType === 'members') {
            userList = members;
        }

        // Add users to the dropdown
        userList.forEach(function(user) {
            const option = document.createElement('option');
            option.value = user;
            option.text = user;
            userDropdown.appendChild(option);
        });
    }
</script>
</body>
</html>
