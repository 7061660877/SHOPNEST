<?php
session_start();

// Check if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Include database connection
include 'config.php';

// Initialize an empty variable to store the success message
$delete_message = "";

// Check if the delete_id parameter is set in the URL
if(isset($_GET['delete_id'])) {
    // Sanitize the input to prevent SQL injection
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);

    // Delete the product from the database
    $sql = "DELETE FROM product WHERE id = '$delete_id'";
    if ($conn->query($sql) === TRUE) {
        // Product deleted successfully
        $delete_message = "Product deleted successfully.";
    } else {
        // Error occurred while deleting the product
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Retrieve products from the database
$sql = "SELECT * FROM product";
$result = $conn->query($sql);

// Initialize an empty array to store products
$products = [];

// Check if there are any products
if ($result->num_rows > 0) {
    // Loop through each row and store product details in the array
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #a6d6ff;
            margin: 0;
            padding: 0;
            position: relative;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid  #fffbf4;
        }
        th {
            background-color: #f1e87c;
        }
        img {
            max-width: 100px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        @media screen and (max-width: 600px) {
            table {
                font-size: 14px;
            }
            th, td {
                padding: 8px;
            }
            img {
                max-width: 80px;
            }
        }
        /* Animation for success message */
        .success-message {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>View Products</h1>
        <?php if (!empty($delete_message)): ?>
            <div class="success-message"><?php echo $delete_message; ?></div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td><?php echo $product['description']; ?></td>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($product['product_image']); ?>" alt="<?php echo $product['name']; ?>"></td>
                        <td>
                            <a href="view_products.php?delete_id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            <a href="update_product.php?update_id=<?php echo $product['id']; ?>">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>

    <script>
        // Function to show success message
        function showSuccessMessage() {
            var successDiv = document.querySelector('.success-message');
            successDiv.style.display = 'block';
            setTimeout(function() {
                successDiv.style.display = 'none';
            }, 5000); // Hide after 5 seconds
        }

        // Check if there's a success message to display
        <?php if (!empty($delete_message)): ?>
            showSuccessMessage();
        <?php endif; ?>
    </script>
</body>
</html>