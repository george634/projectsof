<?php
session_start();
include 'db_connection.php';
$con = OpenCon(); // Open database connection
$username = $_SESSION['Username'];

// Check if the user exists in any of the relevant tables
$query = "
    SELECT 1 FROM weeklyexercise WHERE username = '$username'
    UNION
    SELECT 1 FROM workouttime WHERE username = '$username'
    UNION
    SELECT 1 FROM exerciserequest WHERE username = '$username'
";

$result = mysqli_query($con, $query);
$exists = mysqli_num_rows($result) > 0;

CloseCon($con); // Close database connection

echo json_encode(['exists' => $exists]);
?>