<?php
session_start();
include 'db_connection.php';

$con = OpenCon(); // Open database connection

$currentDate = date('Y-m-d'); 
$username = $_SESSION['Username'];
$day = date('l');

// Clear all previous entries for the user in finalstats
$clearfstatsQuery = "DELETE FROM fainalstats WHERE username = ? AND date = ?";
$stmt = $con->prepare($clearfstatsQuery);
$stmt->bind_param("ss", $username, $currentDate);
$stmt->execute();
$stmt->close();
echo "<script>
        alert('$currentDate');
        window.location.href = 'userdata.php';
    </script>";
$query = "SELECT totalex, nowex FROM stats WHERE username = ? AND day = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("ss", $username, $day);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalex = $row['totalex'];
    $nowex = $row['nowex'];

    // Calculate percentage
    if ($totalex > 0) {
        $percentage = round(($nowex / $totalex) * 100);
    } else {
        $percentage = 0;
    }
}

$stmt->close();

// Insert the new data into the finalstats table
$insertQuery = "INSERT INTO fainalstats (date, username, points) VALUES (?, ?, ?)";
$stmt = $con->prepare($insertQuery);
$stmt->bind_param("ssi", $currentDate, $username, $percentage);

if ($stmt->execute()) {

    // Clear all data for the user for the current day
    $clearQuery = "DELETE FROM weeklyexercise WHERE username = ? AND day = ?";
    $stmt = $con->prepare($clearQuery);
    $stmt->bind_param("ss", $username, $day);
    $stmt->execute();

    $clearWorkoutTimeQuery = "DELETE FROM workouttime WHERE username = ? AND day = ?";
    $stmt = $con->prepare($clearWorkoutTimeQuery);
    $stmt->bind_param("ss", $username, $day);
    $stmt->execute();

    $clearStatsQuery = "DELETE FROM stats WHERE username = ? AND day = ?";
    $stmt = $con->prepare($clearStatsQuery);
    $stmt->bind_param("ss", $username, $day);
    $stmt->execute();

    echo "<script>
        alert('all good.');
        window.location.href = 'userdata.php';
    </script>";
} else {
    echo "<script>
        alert('Error: " . mysqli_error($con) . "');
        window.location.href = 'userdata.php';
    </script>";
}

$stmt->close();
$con->close();
?>
