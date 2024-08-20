<html>
<body>
<head>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color:black;
        }

        form {
            background-color: black;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color:blanchedalmond;

        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;

        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .log:hover {
            background-color: greenyellow;

        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>











<form method="post" action="projectphp.php">
    <label for="name">name:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="lname">last name:</label>
    <input type="lname" id="lname" name="lname" required><br>

    <label for="email">email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>

   
    <input class="log" type="submit" value="Login" >
</form>
</body>
</html>