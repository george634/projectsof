<?php
include 'db_connection.php';
$con = OpenCon(); // Open database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $gender = $_POST['gender'];

    // Determine the type based on the selected gender
    $type = ($gender === 'male') ? 'Male' : 'Female';

    // Update the type in the users table
    $query = "UPDATE users SET type = ? WHERE username = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $type, $username);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
}

CloseCon($con); // Close database connection
?>
