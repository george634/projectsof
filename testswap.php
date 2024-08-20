<?php
session_start();
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Option 1: Change Time for a Day
    if (isset($_POST['change_time'])) {
        $day1 = $_POST['day1'];
        $newTime1 = $_POST['time1'];

        // Update workouttime and weeklyexercise tables
        if (isset($schedule[$day1])) {
            $updateQuery1 = "UPDATE workouttime SET time = '$newTime1' WHERE username = '$username' AND day = '$day1'";
        }

        mysqli_query($conn, $updateQuery1);

        header("Location: changejustthetime.php");
        exit;
    }

    // Option 2: Swap Days
    if (isset($_POST['swap_days'])) {
        $day2 = $_POST['day2'];
        $day3 = $_POST['day3'];
        $newTime3 = $_POST['time3'];

        // Check if the days selected are different
        if ($day2 !== $day3) {
            // Case 1: Both days exist in workouttime (perform a swap)
            if (isset($schedule[$day2]) && isset($schedule[$day3])) {
                // Temporarily hold the time for day2
                $tempTime = $schedule[$day2];
            
                // Swap the times (days remain the same)
                $updateQuery2 = "UPDATE workouttime SET time = '{$schedule[$day3]}' WHERE username = '$username' AND day = '$day2'";
                $updateQuery3 = "UPDATE workouttime SET time = '$tempTime' WHERE username = '$username' AND day = '$day3'";
            
                mysqli_query($conn, $updateQuery2);
                mysqli_query($conn, $updateQuery3);

                // Swap in weeklyexercise table (swap the exercises between the two days)
                $tempDay = 'TempDay';

                // Step 1: Change day2 to the temporary placeholder
                $updateWeeklyExerciseQueryTemp = "UPDATE weeklyexercise SET day = '$tempDay' WHERE username = '$username' AND day = '$day2'";
                mysqli_query($conn, $updateWeeklyExerciseQueryTemp);

                // Step 2: Change day3 to day2
                $updateWeeklyExerciseQuery2 = "UPDATE weeklyexercise SET day = '$day2' WHERE username = '$username' AND day = '$day3'";
                mysqli_query($conn, $updateWeeklyExerciseQuery2);

                // Step 3: Change the temporary placeholder to day3
                $updateWeeklyExerciseQuery3 = "UPDATE weeklyexercise SET day = '$day3' WHERE username = '$username' AND day = '$tempDay'";
                mysqli_query($conn, $updateWeeklyExerciseQuery3);

            } else {
                // Case 2: One of the days does not exist in workouttime (just update the existing day)
                if (isset($schedule[$day2])) {
                    // Update day2 to day3
                    $updateQuery = "UPDATE workouttime SET day = '$day3', time = '$newTime3' WHERE username = '$username' AND day = '$day2'";
                } elseif (isset($schedule[$day3])) {
                    // Update day3 to day2
                    $updateQuery = "UPDATE workouttime SET day = '$day2', time = '$schedule[$day3]' WHERE username = '$username' AND day = '$day3'";
                }

                mysqli_query($conn, $updateQuery);

                // Also update the weeklyexercise table accordingly
                $updateWeeklyExerciseQuery = "UPDATE weeklyexercise SET day = IF(day = '$day2', '$day3', '$day2') WHERE username = '$username' AND (day = '$day2' OR day = '$day3')";
                mysqli_query($conn, $updateWeeklyExerciseQuery);
            }

            header("Location: changejustthetime.php");
            exit;
        } else {
            header("Location: changejustthetime.php");
            exit;
        }
    }
}
?>
