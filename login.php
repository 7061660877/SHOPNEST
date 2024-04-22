<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user data from the database
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Set session variable for successful login
        $_SESSION['login_result'] = 'success';
        
        // Set session variables
        $_SESSION['email'] = $email;
        
        // Redirect to index.html upon successful login
        header("Location: index.php");
        exit();
    } else {
        // Set session variable for unsuccessful login
        $_SESSION['login_result'] = 'failure';
        
        // Redirect to login.php to display alert
        header("Location: login.php");
        exit();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #7d7574;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        #login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #login-box {
            background-color: #a6d6ff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 300px;
            width: 100%;
        }
        .user-icon {
            width: 60px;
            height: auto;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="password"],
        button {
            width: calc(100% - 40px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .signup-link,
        .store-link {
            text-align: center;
            margin-top: 15px;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        // JavaScript for displaying toast notifications
        document.addEventListener("DOMContentLoaded", function() {
            var loginResult = "<?php echo isset($_SESSION['login_result']) ? $_SESSION['login_result'] : '' ?>";
            if (loginResult === 'success') {
                showToast('Login successful!', 'green');
            } else if (loginResult === 'failure') {
                showToast('Incorrect email or password', 'red');
            }

            function showToast(message, bgColor) {
                var toast = document.createElement('div');
                toast.textContent = message;
                toast.style.backgroundColor = bgColor;
                toast.style.color = 'white';
                toast.style.position = 'fixed';
                toast.style.top = '20px';
                toast.style.right = '20px';
                toast.style.padding = '10px 20px';
                toast.style.borderRadius = '5px';
                toast.style.zIndex = '9999';
                document.body.appendChild(toast);
                setTimeout(function() {
                    document.body.removeChild(toast);
                }, 5000);
            }
        });
    </script>
</head>
<body>

    <h1 style="text-align: center; color: white;">LOGIN HERE</h1>
    <div id="login-container">
        <div id="login-box">
            <img src="images/user2.PNG" class="user-icon" alt="User Icon">
            <form action="login.php" method="post">
                <input type="text" id="email" name="email" placeholder="Enter your email" required>
                <br>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <br>
                <button type="submit">Login</button>
            </form>
            <div class="signup-link">
                <a href="signup.php">Sign Up</a>
            </div>
            <div class="store-link">
                <a href="mainpage.php">Back to Store</a>
            </div>
        </div>
    </div>
    
</body>
</html>
