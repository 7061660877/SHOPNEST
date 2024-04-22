<?php
session_start();

// Check if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Include database connection
include 'config.php';

// Delete user functionality
if (isset($_GET['delete_id'])) {
    // Sanitize the input to prevent SQL injection
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);

    // Delete the user from the database
    $sql = "DELETE FROM users WHERE id = '$delete_id'";
    if ($conn->query($sql) === TRUE) {
        // User deleted successfully
        $delete_message = "User deleted successfully.";
    } else {
        // Error occurred while deleting the user
        $delete_error = "Error deleting user: " . $conn->error;
    }
}

// Retrieve users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Initialize an empty array to store users
$users = [];

// Check if there are any users
if ($result->num_rows > 0) {
    // Loop through each row and store user details in the array
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        td:last-child {
            text-align: center;
        }

        .delete-btn {
            background-color: #ff6347;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }

        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            animation: slideInRight 0.5s ease-in-out forwards;
            z-index: 9999;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>View Users</h1>
    <?php if (isset($delete_error)): ?>
        <p class="error"><?php echo $delete_error; ?></p>
    <?php elseif (isset($delete_message)): ?>
        <div class="success-message"><?php echo $delete_message; ?></div>
        <script>
            setTimeout(function () {
                var successMessage = document.querySelector('.success-message');
                successMessage.parentNode.removeChild(successMessage);
            }, 5000);
        </script>
    <?php endif; ?>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Address</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['mobile']; ?></td>
                <td><?php echo $user['address']; ?></td>
                <td>
                    <button class="delete-btn" onclick="confirmDelete(<?php echo $user['id']; ?>)">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function confirmDelete(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            window.location.href = 'view_users.php?delete_id=' + userId;
        }
    }
</script>
<h1><a href="dashboard.php">Back to Dashboard</a></h1>
</body>
</html>
