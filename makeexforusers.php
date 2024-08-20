<?php
session_start();
include 'db_connection.php';
$con = OpenCon(); // Open database connection

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch all exercise requests
$requestQuery = "SELECT username FROM exerciserequest";
$requestResult = mysqli_query($con, $requestQuery);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Workout Plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        header {
            background: #35424a;
            color: #ffffff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #e8491d 3px solid;
        }

        header a {
            color: #ffffff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }

        header ul {
            padding: 0;
            list-style: none;
        }

        header li {
            float: left;
            display: inline;
            padding: 0 20px 0 20px;
        }

        header #branding {
            float: left;
        }

        header #branding h1 {
            margin: 0;
        }

        header nav {
            float: right;
            margin-top: 10px;
        }

        h1, h2 {
            color: #35424a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #35424a;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        a.button {
            display: inline-block;
            color: white;
            background: #e8491d;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }

        a.button:hover {
            background: #35424a;
        }

        .disabled {
            background: #ddd;
            color: #aaa;
            pointer-events: none;
        }

        .message {
            margin: 20px 0;
            padding: 10px;
            background-color: #e8491d;
            color: white;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Workout Plan</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="userdata.php">Back to Dashboard</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Exercise Requests</h2>
        <table>
            <tr>
                <th>Username</th>
                <th>Status</th>
            </tr>
            <?php
            if (mysqli_num_rows($requestResult) > 0) {
                while ($row = mysqli_fetch_assoc($requestResult)) {
                    $username = $row['username'];
            
                    // Check if admincheck is 'yes' in users table
                    $userQuery = "SELECT admincheck FROM users WHERE username = '$username'";
                    $userResult = mysqli_query($con, $userQuery);
                    $userRow = mysqli_fetch_assoc($userResult);
            
                    // Check if admincheck is NULL or an empty string
                    if (is_null($userRow['admincheck']) || $userRow['admincheck'] === '') {
                        $status = 'Not Saved Schedule';
                    } else {
                        $status = ''; // If it's not NULL or empty, no need to display the message
                    }
            
                    echo "<tr>";
                    echo "<td><a href='workoutforusers.php?username=" . $username . "' class='button'>" . $username . "</a></td>";
                    echo "<td>" . $status . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No requests found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
