<?php
session_start();
include 'db_connection.php';

$con = OpenCon();
$username = $_SESSION['Username'];

$query = "SELECT savescdhulle FROM users WHERE username = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($saveschedule);
$stmt->fetch();
$stmt->close();
$con->close();

echo json_encode(['saveschedule' => $saveschedule]);
?>
