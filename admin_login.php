<?php
session_start();

// Check if admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Include database connection
include 'config.php';

// Check login credentials
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id FROM admins WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['admin_id'] = $row['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password"; // Set error message
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            background-color:  #7d7574;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            position: relative;
        }
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #f44336;
            color: white;
            padding: 15px;
            border-radius: 10px;
            animation: slideInRight 0.5s forwards, fadeOut 5s forwards;
            display: none;
        }
        @keyframes slideInRight {
            0% {
                right: -200px;
            }
            100% {
                right: 20px;
            }
        }
        @keyframes fadeOut {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }
        form {
            background-color:  #a6d6ff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: 200px;
            width: 300px;
            color: blue;
            margin: 0 auto;
            margin-top: 100px;
        }
        form label {
            display: block;
            margin-bottom: 10px;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-align: center;
            margin-top: 20px;
        }
        ul li {
            display: inline-block;
            margin-right: 10px;
        }
        ul li a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="alert" id="alert">Invalid username or password</div>
<center>
    <h1 style="color: white">ADMIN LOGIN</h1>
    <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">USERNAME</label>
        <input type="text" id="username" name="username" placeholder="Username" required>
        <label for="password">PASSWORD</label>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <ul>
        <li><a href="mainpage.php">Back to store</a></li>
    </ul>
</center>

<script>
    // Function to show alert
    function showAlert() {
        var alertDiv = document.getElementById('alert');
        alertDiv.style.display = 'block';
        setTimeout(function() {
            alertDiv.style.display = 'none';
        }, 5000); // Hide after 5 seconds
    }

    // Check if there's an error message to display
    <?php if (isset($error)) { ?>
        showAlert();
    <?php } ?>
</script>
</body>
</html>
