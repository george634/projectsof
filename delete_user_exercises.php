<?php
session_start();
include 'db_connection.php';
$con = OpenCon(); // Open database connection
$username = $_SESSION['Username'];

// Delete from the relevant tables
$deleteWeeklyExercise = "DELETE FROM weeklyexercise WHERE username = '$username'";
$deleteWorkoutTime = "DELETE FROM workouttime WHERE username = '$username'";
$deleteExerciseRequest = "DELETE FROM exerciserequest WHERE username = '$username'";
$deleteUserFields = "UPDATE users SET goal = NULL, admincheck = NULL WHERE username = '$username'";

mysqli_query($con, $deleteWeeklyExercise);
mysqli_query($con, $deleteWorkoutTime);
mysqli_query($con, $deleteExerciseRequest);
mysqli_query($con, $deleteUserFields);

CloseCon($con); // Close database connection

echo "success";
?>
