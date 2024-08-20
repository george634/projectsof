<?php
session_start();
include 'db_connection.php';
$con = OpenCon();

$username = $_SESSION['Username'];
$day = $_POST['day'];

// Increment the completedex in the stats table for the current day
$updateStatsQuery = "UPDATE stats SET completedex = completedex + 1 WHERE username = '$username' AND day = '$day'";
mysqli_query($con, $updateStatsQuery);

// Fetch the updated completedex count and compare with totalex
$fetchStatsQuery = "SELECT completedex, totalex FROM stats WHERE username = '$username' AND day = '$day'";
$fetchStatsResult = mysqli_query($con, $fetchStatsQuery);

$completedExercises = 0;
$totalExercises = 0;

if ($row = mysqli_fetch_assoc($fetchStatsResult)) {
    $completedExercises = $row['completedex'];
    $totalExercises = $row['totalex'];
}

// Close the connection
CloseCon($con);

// Return the updated completed exercises count as a JSON response
echo json_encode(['completedExercises' => $completedExercises, 'totalExercises' => $totalExercises]);
?>
