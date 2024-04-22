<?php
session_start();

// Check if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Include database connection
include 'config.php';

// Initialize variables
$name = $email = $mobile = $address = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if email is already registered
    $check_email_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = $conn->query($check_email_query);
    if ($result->num_rows > 0) {
        $error = "Email is already registered.";
    } else {
        // Insert data into database
        $sql = "INSERT INTO users (name, mobile, email, address) VALUES ('$name', '$mobile', '$email', '$address')";
        if ($conn->query($sql) === TRUE) {
            $success_message = "User added successfully.";
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <style>
        #d1
        {
            height:500px;
            width:400px;
            background-color: red;
            margin-left:600px;
            text-align:center;
        }
        h1
        {
            text-align:center;
        }
        button{
            background-color: orange;
            color:white;
        }
    </style>
   
</head>
<body>
<h1>Add User</h1>
    <div class="container">
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>
    </div>
    <div id="d1">
       <form method="post">
            <label for="name">Name:</label>
            <br>
            <input type="text" name="name" id="name" required>
            <br>
            <label for="mobile">Mobile:</label>
            <br>
            <input type="tel" name="mobile" id="mobile" required>
            <br>
            <label for="email">Email:</label>
            <br>
            <input type="email" name="email" id="email" required>
            <br>
            <label for="address">Address:</label>
            <br>
            <textarea name="address" id="address" rows="5" required></textarea>
            <br>
            <button type="submit">Add User</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
       </div>
</body>
</html>
