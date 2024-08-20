<?php
// Database connection
session_start();
include 'navbar.footer.php';
include 'db_connection.php';
$con = OpenCon(); // Open database connection
// Check connection


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exname = $_POST['exname'];
    $muscle = $_POST['muscle'];
    $musclepart = $_POST['musclepart'];
    $gender = $_POST['gender'];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["exvedio"]["name"]);
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is a valid video type
    $valid_types = array("mp4", "avi", "mov", "wmv");
    if (!in_array($videoFileType, $valid_types)) {
        echo "Sorry, only MP4, AVI, MOV, and WMV files are allowed.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (5MB max)
    if ($_FILES["exvedio"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // If everything is ok, try to upload file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["exvedio"]["tmp_name"], $target_file)) {
            echo "The file ". basename($_FILES["exvedio"]["name"]). " has been uploaded.";
            $exvedio = basename($_FILES["exvedio"]["name"]);

            // Insert data into the database
            $sql = "INSERT INTO exercises (exname, exvedio, muscle, musclepart, gender) 
                    VALUES ('$exname', '$exvedio', '$muscle', '$musclepart', '$gender')";

            if ($conn->query($sql) === TRUE) {
                echo "New exercise added successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Close connection
$conn->close();
?>
