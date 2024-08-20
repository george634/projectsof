<?php
session_start();
include 'navbar.footer.php';
include 'db_connection.php';
$con = OpenCon(); // Open database connection

// Handle adding exercise
if (isset($_POST['add_exercise'])) {
    // Collect form data
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
            $exvedio = basename($_FILES["exvedio"]["name"]);

            // Insert data into the database
            $sql = "INSERT INTO exercises (exname, exvedio, muscle, musclepart, gender) 
                    VALUES ('$exname', '$exvedio', '$muscle', '$musclepart', '$gender')";

            if ($con->query($sql) === TRUE) {
                echo "New exercise added successfully<br><br>";
            } else {
                echo "Error: " . $sql . "<br>" . $con->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.<br><br>";
        }
    }
}

echo '<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        padding: 0;
        margin: 0;
    }
    .container {
        max-width: 500px;
        margin: 50px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 8px;
        color: #495057;
        font-weight: 600;
    }
    input[type="text"], input[type="file"], select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 16px;
    }
    input[type="submit"] {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
    }
    input[type="submit"]:hover {
        background-color: #218838;
    }
    .button-container {
        text-align: center;
        margin-bottom: 20px;
    }
    .show-button {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    .show-button:hover {
        background-color: #0056b3;
    }
    /* Modal styles */
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1; 
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto; 
        background-color: rgba(0,0,0,0.5); 
        padding-top: 60px;
    }
    .modal-content {
        background-color: #fff;
        margin: 5% auto; 
        padding: 20px;
        border: 1px solid #888;
        border-radius: 10px;
        width: 80%; 
        max-width: 800px;
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td {
        padding: 12px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
</style>';

// Show All Exercises Button
echo '<div class="button-container">';
echo '<button id="showExercisesBtn" class="show-button">Show All Exercises</button>';
echo '</div>';

// Exercise Form
echo '<div class="container">';
echo '<h2>Add New Exercise</h2>';
echo '<form method="POST" enctype="multipart/form-data">';

echo '<label for="exname">Exercise Name:</label>';
echo '<input type="text" id="exname" name="exname" required>';

echo '<label for="exvedio">Exercise Video (choose file):</label>';
echo '<input type="file" id="exvedio" name="exvedio" accept="video/*" required>';

echo '<label for="muscle">Muscle Group:</label>';
echo '<input type="text" id="muscle" name="muscle" required>';

echo '<label for="musclepart">Muscle Part:</label>';
echo '<input type="text" id="musclepart" name="musclepart" required>';

echo '<label for="gender">Gender:</label>';
echo '<select id="gender" name="gender" required>';
echo '<option value="male">Male</option>';
echo '<option value="female">Female</option>';
echo '</select>';

echo '<input type="submit" name="add_exercise" value="Add Exercise">';

echo '</form>';
echo '</div>';

// The Modal
echo '<div id="myModal" class="modal">';
echo '<div class="modal-content">';
echo '<span class="close">&times;</span>';
echo '<h2>All Exercises</h2>';

// Fetch and display all exercises
$sql = "SELECT * FROM allexercise";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    echo '<table>';
    echo '<tr><th>Exercise Name</th><th>Video</th><th>Muscle Group</th><th>Muscle Part</th><th>Gender</th></tr>';
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row["exname"] . '</td>';
        echo '<td>' . $row["exvedio"] . '</td>';
        echo '<td>' . $row["muscle"] . '</td>';
        echo '<td>' . $row["musclepart"] . '</td>';
        echo '<td>' . $row["gender"] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo 'No exercises found.';
}

echo '</div></div>';

$con->close();
?>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("showExercisesBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
