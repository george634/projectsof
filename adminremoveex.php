<?php
session_start();
include 'db_connection.php';
$con = OpenCon(); // Open database connection
$user = $_SESSION['adminaddexforuser']; // The user whose exercise is being managed

if (isset($_POST['exerciseId']) && isset($_POST['day'])) {
    $exerciseId = $_POST['exerciseId'];
    $day = $_POST['day'];

    // Prepare the DELETE statement for the specific user
    $stmt = $con->prepare("DELETE FROM weeklyexercise WHERE exercise = ? AND username = ? AND day = ?");
    $stmt->bind_param("sss", $exerciseId, $user, $day);

    // Execute the statement
    if ($stmt->execute()) {
        // Close the statement
        $stmt->close();
        CloseCon($con); // Close database connection

        // Redirect back to workoutforusers.php with the username, success status, and toggle parameter
        header("Location: http://localhost/labs/workoutforusers.php?username=$user&status=success&toggle=true");
        exit;
    } else {
        // Capture error
        $error = $stmt->error;
        
        // Close the statement
        $stmt->close();
        CloseCon($con); // Close database connection

        // Redirect back to workoutforusers.php with the username, error message, and toggle parameter
        header("Location: http://localhost/labs/workoutforusers.php?username=$user&status=error&toggle=true&message=" . urlencode($error));
        exit;
    }
} else {
    CloseCon($con); // Close database connection
    
    // Redirect back to workoutforusers.php with the username, error message, and toggle parameter
    header("Location: http://localhost/labs/workoutforusers.php?username=$user&status=error&toggle=true&message=" . urlencode("No exercise ID or day provided."));
    exit;
}
?>
