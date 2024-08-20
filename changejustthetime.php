<?php
include 'navbar.footer.php';

include 'db_connection.php';
$conn = OpenCon();
$username = $_SESSION["Username"];

// Retrieve user's current workout time schedule
$query = "SELECT day, time FROM workouttime WHERE username = '$username'";
$result = mysqli_query($conn, $query);

// Store the schedule in an array
$schedule = [];
while ($row = mysqli_fetch_assoc($result)) {
    $schedule[$row['day']] = $row['time'];
}

// Define all possible days of the week
$allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Workout Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 130vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            margin-top:10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        h2, h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 20px;
            color: red;
        }

        .option-container {
            margin-bottom: 40px;
        }

        .option-container:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Workout Schedule</h2>

    <!-- Option 1: Change Time for a Day -->
    <div class="option-container">
    <h3>Change Time for a Day</h3>
    <form action="testswap.php" method="post">
        <div>
            <label for="day1">Select the day:</label>
            <select name="day1">
                <?php
                foreach ($allDays as $day) {
                    if (isset($schedule[$day])) {
                        $time = $schedule[$day];
                        echo "<option value='$day'>$day ($time)</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div>
            <label for="time1">Select new time:</label>
            <select name="time1">
                <option value="morning">Morning</option>
                <option value="evening">Evening</option>
            </select>
        </div>

        <input type="submit" name="change_time" value="Change Time">
    </form>
</div>

    <!-- Option 2: Swap Days -->
    <div class="option-container">
        <h3>Swap Days</h3>
        <form action="testswap.php" method="post">
            <div>
                <label for="day2">Select the first day to swap:</label>
                <select name="day2">
                    <?php
                    foreach ($allDays as $day) {
                        $time = isset($schedule[$day]) ? $schedule[$day] : 'No workout';
                        echo "<option value='$day'>$day ($time)</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="day3">Select another day to swap with:</label>
                <select name="day3">
                    <?php
                    foreach ($allDays as $day) {
                        $time = isset($schedule[$day]) ? $schedule[$day] : 'No workout';
                        echo "<option value='$day'>$day ($time)</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="time3">Select new time for the second day:</label>
                <select name="time3">
                    <option value="morning">Morning</option>
                    <option value="evening">Evening</option>
                </select>
            </div>

            <input type="submit" name="swap_days" value="Swap Days">
        </form>
    </div>

   
</div>

</body>
</html>
