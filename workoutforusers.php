<?php
session_start(); // Start or resume the session

include 'db_connection.php';
$con = OpenCon(); // Open database connection

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Retrieve the username from the URL
$user = isset($_GET['username']) ? $_GET['username'] : '';
$_SESSION['adminaddexforuser'] = $user;

$workoutTime = [];

if ($user) {
    // Fetch the workout time for the selected user
    $sql = "SELECT day, time FROM workouttime WHERE username = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $workoutTime[$row['day']] = $row['time'];
    }

    $stmt->close();
}

$sql = "SELECT day, musclename, musclepart, exercise, sets, exvedio FROM weeklyexercise WHERE username='$user'"; 
$result = $con->query($sql);
$con->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Workout Plan</title>
    <style>
        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container .button {
            display: inline-block;
            color: white;
            background: green;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .button-container .button:hover {
            background: #35424a;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        header {
            background: #35424a;
            color: #ffffff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #e8491d 3px solid;
        }

        header a {
            color: #ffffff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }

        header ul {
            padding: 0;
            list-style: none;
        }

        header li {
            float: left;
            display: inline;
            padding: 0 20px 0 20px;
        }

        header #branding {
            float: left;
        }

        header #branding h1 {
            margin: 0;
            cursor: pointer;
        }

        header nav {
            float: right;
            margin-top: 10px;
        }

        h1, h2 {
            color: #35424a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #35424a;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        a.button {
            display: inline-block;
            color: white;
            background: #e8491d;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }

        a.button:hover {
            background: #35424a;
        }

        .disabled {
            background: #ddd;
            color: #aaa;
            pointer-events: none;
        }

        .message {
            margin: 20px 0;
            padding: 10px;
            background-color: #e8491d;
            color: white;
            border-radius: 5px;
            text-align: center;
        }

        #workoutDetails {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
            z-index: 9999;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            width: 80%;
            max-height: 80vh;
            overflow-y: auto;
        }

        #workoutDetails h2 {
            margin-top: 0;
            padding-bottom: 10px;
        }

        #workoutDetails button {
            margin-top: 10px;
            background-color: #e8491d;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        #workoutDetails button:hover {
            background-color: #35424a;
        }

        @media screen and (max-width: 768px) {
            #workoutDetails {
                width: 95%;
            }

            header, .container {
                width: 100%;
                padding: 0 10px;
            }

            table {
                font-size: 14px;
            }

            header nav ul {
                text-align: center;
                padding: 0;
            }

            header li {
                display: block;
                padding: 10px 0;
            }

            #workoutDetails button {
                width: 100%;
            }
        }

        #workoutDetails::-webkit-scrollbar {
            width: 10px;
        }

        #workoutDetails::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 10px;
        }

        #workoutDetails::-webkit-scrollbar-thumb:hover {
            background-color: #bbb;
        }

        #workoutDetails::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }

        .centered-title {
            text-align: center;
            margin-top: 20px;
        }
    </style>
    <script>
        function toggleWorkoutDetails() {
    var workoutDetails = document.getElementById("workoutDetails");
    if (workoutDetails.style.display === "none" || workoutDetails.style.display === "") {
        workoutDetails.style.display = "block";
    } else {
        workoutDetails.style.display = "none";
    }
}

// Check if the toggle parameter is present in the URL
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('toggle') === 'true') {
        toggleWorkoutDetails();
    }
};
    </script>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1 style="color: white;" onclick="toggleWorkoutDetails()">Workout Plan for <?PHP echo $user;?> till now</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="makeexforusers.php">Back to Workout Plan</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1 class="centered-title">Weekly Workout Plan for <?php echo htmlspecialchars($user); ?></h1>

        <form method="POST" action="save_workout_schedule.php">
            <table>
                <tr>
                    <th>Day</th>
                    <th>Chest</th>
                    <th>Back</th>
                    <th>Biceps</th>
                    <th>Triceps</th>
                    <th>Shoulders</th>
                    <th>Legs</th>
                    <th>Abs</th>
                    <th>Time</th>
                </tr>
                <?php 
                $daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                $muscleGroups = ["Chest", "Back", "Biceps", "Triceps", "Shoulders", "Legs", "Abs"];
                
                foreach ($daysOfWeek as $day) {
                    echo "<tr>";
                    echo "<td>$day</td>";

                    // Check if time is set for the day
                    $time = isset($workoutTime[$day]) ? $workoutTime[$day] : '';
                    $disabled = empty($time) ? 'class="button disabled" style="pointer-events: none; opacity: 0.6;"' : 'class="button"';

                    foreach ($muscleGroups as $muscle) {
                        echo "<td><a href='muscle_exirse.php?day=$day&muscle=$muscle' $disabled>$muscle</a></td>";
                    }

                    echo "<td>$time</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <br>
            <div class="button-container">
                <button type="submit" class="button">Save Workout Schedule</button>
            </div>
            <br> 
        </form>

        <div id="workoutDetails">
            <h2>Workout Details</h2>
            <button onclick="toggleWorkoutDetails()">Close</button>
            <table>
                <tr>
                    <th>Day</th>
                    <th>Muscle Name</th>
                    <th>Muscle Part</th>
                    <th>Exercise</th>
                    <th>Sets</th>
                    <th>Exvedio</th>
                    <th>Remove</th> <!-- Added Remove header -->
                </tr>
                
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['day']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['musclename']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['musclepart']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['exercise']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['sets']) . "</td>";
                        echo "<td><a href='" . htmlspecialchars($row['exvedio']) . "' target='_blank'>View Video</a></td>";
                        echo "<td>";
                        echo "<form action='adminremoveex.php' method='post' style='display:inline;'>";
                        echo "<input type='hidden' name='exerciseId' value='{$row['exercise']}'>";
                        echo "<input type='hidden' name='day' value='{$row['day']}'>";
                        echo '<input style="background-color: red; color: white;" type="submit" value="Remove">';
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No workout details available</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
