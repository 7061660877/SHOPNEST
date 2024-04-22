<?php
// Start the session
session_start();

// Include the database configuration file
include 'config.php';

// Check if the user is not logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}

// Get the email of the logged-in user
$user_email = $_SESSION['email'];

// Fetch user information from the database
$sql = "SELECT * FROM users WHERE email = '$user_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch user details
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
    $user_name = $row['name'];
    $user_email = $row['email'];
    $user_phone = $row['mobile'];
} else {
    // No user found with the provided email
    echo "User not found.";
    exit();
}

// Check if the update form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Retrieve form data
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['phone'];
    $new_password = $_POST['password'];

    // Update user details in the database
    $sql = "UPDATE users SET name = '$new_name', email = '$new_email', mobile = '$new_phone' WHERE id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        // Update successful
        echo "User details updated successfully.";
    } else {
        // Error occurred while updating
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #a6d6ff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <center> <h2>Update User Details</h2></center>
    <div class="container">
        <form method="post">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" value="<?php echo $user_name; ?>"><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo $user_email; ?>"><br>
            <label for="phone">Phone:</label><br>
            <input type="text" id="phone" name="phone" value="<?php echo $user_phone; ?>"><br>
            <label for="password">New Password:</label><br>
            <input type="password" id="password" name="password"><br>
            <input type="submit" name="update" value="Update">
        </form>
    </div>
    <center><div class="store-link">
                <a href="index.php">Back to store</a>
            </div>
    </center>
</body>
