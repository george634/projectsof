<?php
session_start();
include 'db_connection.php';

$con = OpenCon();
$username = $_SESSION['Username'];

// Update saveschdule to NULL
$query = "UPDATE users SET savescdhulle = NULL WHERE username = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $username);

if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'error';
}

$stmt->close();
$con->close();
?>
