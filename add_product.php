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
$name = $price = $description = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // File upload handling
    $target_directory = "uploads/";
    if (!file_exists($target_directory)) {
        mkdir($target_directory, 0777, true); // Create the directory if it doesn't exist
    }
    $target_file = $target_directory . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $error = "File is not an image.";
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        $error = "Sorry, your file is too large.";
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }

    // Check if $error is still empty, then proceed with inserting data into database
    if (empty($error)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // File uploaded successfully, insert data into database
            $product_image = addslashes(file_get_contents($target_file)); // Get and escape image data
            $sql = "INSERT INTO product (name, price, description, product_image) VALUES ('$name', '$price', '$description', '$product_image')";
            if ($conn->query($sql) === TRUE) {
                $success_message = "Product uploaded successfully.";
            } else {
                $error = "Error: " . $sql . "<br>" . $conn->error;
            }
            unlink($target_file); // Delete the uploaded image file after inserting data into the database
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            background-color: #a6d6ff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .container h1 {
            text-align: center;
        }
        .container form {
            margin-top: 20px;
        }
        .container form label {
            display: block;
            margin-bottom: 5px;
        }
        .container form input[type="text"],
        .container form input[type="number"],
        .container form textarea,
        .container form input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .container form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .container form button:hover {
            background-color: #0056b3;
        }
        .container form a {
            display: block;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            color: #007bff;
        }
        /* Animation for success message */
        .success-message {
            position: fixed;
            top: 50px;
            right: 50px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 9999;
            animation: slideInRight 0.5s forwards, fadeOut 5s forwards;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Product</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <br>
            <input type="text" name="name" id="name" required>
            <br>
            <label for="price">Price:</label>
            <br>
            <input type="number" name="price" id="price" min="0" step="0.01" required>
            <br>
            <label for="description">Description:</label>
            <br>
            <textarea name="description" id="description" rows="5" required></textarea>
            <label for="image">Image:</label>
            <br>
            <input type="file" name="image" id="image" accept="image/*" required>
            <br>
            <button type="submit">Upload</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
