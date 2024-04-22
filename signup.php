<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Insert user data into the database
    $sql = "INSERT INTO users(name, mobile, email, address, password) VALUES ('$name', '$mobile', '$email', '$address', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Signup successful";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:  #7d7574;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background-color: #a6d6ff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 100px;
            height: auto;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        form {
            width: 100%;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        input[type="text"],
        input[type="password"],
        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .login-link,
        .store-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link a,
        .store-link a {
            text-decoration: none;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <img src="images/add-user.png" alt="Logo">
            </div>
            <div class="title">CREATE AN ACCOUNT TO SHOP</div>
            <form action="signup.php" method="post" id="signupForm">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
                <label for="mobile">Mobile No</label>
                <input type="text" id="mobile" name="mobile" placeholder="Enter your mobile number" required>
                <label for="email">Email ID</label>
                <input type="text" id="email" name="email" placeholder="Enter your email address" required>
                <label for="address">Address</label>
                <input type="text" id="address" name="address" placeholder="Enter your address" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <button type="submit">Submit</button>
            </form>
            <div class="login-link">
                Already have an account? <a href="login.php">Login</a>
            </div>
            <div class="store-link">
                <a href="mainpage.php">Back to store</a>
            </div>
        </div>
    </div>
    <div id="notification" class="notification"></div>
    <script>
        document.getElementById('signupForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            fetch('check_email.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    showNotification('This email is already used.');
                } else {
                    this.submit();
                }
            })
            .catch(error => console.error('Error:', error));
        });

        function showNotification(message) {
            var notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.display = 'block';
            setTimeout(function() {
                notification.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>
