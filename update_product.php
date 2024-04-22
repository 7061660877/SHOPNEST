<?php
session_start();

// Check if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Include database connection
include 'config.php';

// Initialize variables to store errors and success messages
$errors = [];
$success_message = "";

// Check if update_id parameter is set in the URL
if(isset($_GET['update_id'])) {
    $update_id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // Fetch product details from the database
    $sql = "SELECT * FROM product WHERE id = '$update_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc();

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize input data
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $price = mysqli_real_escape_string($conn, $_POST['price']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);

            // Validate input
            if (empty($name)) {
                $errors[] = "Name is required";
            }

            if (empty($price)) {
                $errors[] = "Price is required";
            }

            if (empty($description)) {
                $errors[] = "Description is required";
            }

            // If no errors, update product in the database
            if (empty($errors)) {
                // Check if a file was uploaded
                if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['product_image']['tmp_name'];
                    $fileName = $_FILES['product_image']['name'];
                    $fileSize = $_FILES['product_image']['size'];
                    $fileType = $_FILES['product_image']['type'];
                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));

                    // Allow certain file formats
                    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
                    if (in_array($fileExtension, $allowedExtensions)) {
                        // Directory where the uploaded file will be moved
                        $uploadDirectory = 'uploads/';

                        // Move the uploaded file to the specified directory
                        $destPath = $uploadDirectory . $fileName;
                        if (move_uploaded_file($fileTmpPath, $destPath)) {
                            // Update the product image path in the database
                            $product_image = file_get_contents($destPath); // Read image data
                            $sql_update_image = "UPDATE product SET product_image=? WHERE id=?";
                            $stmt = $conn->prepare($sql_update_image);
                            $stmt->bind_param("si", $product_image, $update_id);
                            if ($stmt->execute()) {
                                unlink($destPath); // Delete temporary file
                            } else {
                                $errors[] = "Error updating product image: " . $conn->error;
                            }
                        } else {
                            $errors[] = "Error uploading product image";
                        }
                    } else {
                        $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
                    }
                }

                // Update other product details in the database
                $sql_update = "UPDATE product SET name=?, price=?, description=? WHERE id=?";
                $stmt = $conn->prepare($sql_update);
                $stmt->bind_param("sdsi", $name, $price, $description, $update_id);
                if ($stmt->execute()) {
                    $success_message = "Product updated successfully.";
                    // Redirect to view_products.php after successful update
                    header("Location: view_products.php");
                    exit();
                } else {
                    $errors[] = "Error updating product: " . $conn->error;
                }
            }
        }
    } else {
        // Product not found in the database
        $errors[] = "Product not found.";
    }
} else {
    // Redirect to view_products.php if update_id parameter is not set
    header("Location: view_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    
    <style>
      body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #a6d6ff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
            height: 150px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
        .success-message {
            color: green;
            margin-bottom: 15px;
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 9999;
            animation: slideInRight 0.5s forwards, fadeOut 5s forwards;
            display: none;
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
        .back-link {
            display: block;
            text-align: center;
            color: #007bff;
            text-decoration: none;
            margin-top: 10px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Product</h1>
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>"><br><br>
            <label for="price">Price:</label><br>
            <input type="text" id="price" name="price" value="<?php echo $product['price']; ?>"><br><br>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description"><?php echo $product['description']; ?></textarea><br><br>
            <label for="product_image">Product Image:</label><br>
            <input type="file" id="product_image" name="product_image"><br><br>
            <input type="submit" value="Update Product">
        </form>
        <a href="view_products.php" class="back-link">Back to View Products</a>
    </div>
</body>
</html>
