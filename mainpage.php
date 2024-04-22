<?php
session_start();

// User is logged in, continue displaying the page
include 'config.php';

// Get total number of items in the cart
$total_items = 0;
if (isset($_SESSION['cart'])) {
    $total_items = count($_SESSION['cart']);
}

// Initialize variable to store search results
$search_results = '';
// Initialize variable to store success message
$success_message = '';

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])) {
    // Get the search query
    $search_query = $_GET['query'];

    // Escape special characters to prevent SQL injection
    $search_query = mysqli_real_escape_string($conn, $search_query);

    // Fetch products from the database based on the search query
    $sql = "SELECT * FROM product WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['id'];
            $product_name = $row['name'];
            $product_price = $row['price'];
            $product_description = $row['description'];
            $product_image = $row['product_image'];

            // Append each search result to the $search_results variable
            $search_results .= "
                <div class='product-item'>
                    <img src='data:image/png;base64," . base64_encode($product_image) . "' alt='Product Image'>
                    <p><strong>Product ID:</strong> $product_id</p>
                    <p><strong>Name:</strong> $product_name</p>
                    <p><strong>Price:</strong> $product_price</p>
                    <p><strong>Description:</strong> $product_description</p>
                    <form method='post'>
                        <input type='hidden' name='id' value='$product_id'>
                        <input type='hidden' name='name' value='$product_name'>
                        <input type='hidden' name='price' value='$product_price'>
                        <input type='hidden' name='description' value='$product_description'>
                        <button type='submit' name='add_to_cart'>Add to Cart</button>
                    </form>
                </div>
                ";
        }
    } else {
        // If no products found, display a message
        $search_results = "<p>No products found.</p>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    // Check if user is logged in
    if (!isset($_SESSION['email'])) {
        // If user is not logged in, display a message
        echo "<div class='error-message'>Please login to shop.</div>";
        echo "<script>
                setTimeout(function() {
                    var errorMessage = document.querySelector('.error-message');
                    errorMessage.style.display = 'none';
                }, 5000);
              </script>";
    } else {
        // User is logged in, proceed with adding the product to cart
        $product_id = $_POST['id'];
        $product_name = $_POST['name'];
        $product_price = $_POST['price'];
        $product_description = $_POST['description'];

        // Add product to the session cart
        $cart_item = array(
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'description' => $product_description
        );

        // Check if the cart is already set in the session
        if (isset($_SESSION['cart'])) {
            // Append the new item to the existing cart
            $_SESSION['cart'][] = $cart_item;
        } else {
            // Create a new cart array and add the item
            $_SESSION['cart'] = array($cart_item);
        }

        // Insert the product into the usecart table
        $user_email = $_SESSION['email'];
        $sql = "INSERT INTO usecart (user_email, product_id, product_name, product_price, product_description) VALUES ('$user_email', '$product_id', '$product_name', '$product_price', '$product_description')";
        $conn->query($sql);

        // Set success message
        $success_message = "Product added to cart successfully.";

        // Update total items count
        $total_items = count($_SESSION['cart']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="index.css">
    <!-- Your CSS styles here -->
    <style>
        /* Your CSS styles here */
        /* Remaining styles are unchanged */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        nav {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 16px;
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
        }
        .product-item {
            width: 300px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }
        .product-item:hover {
            transform: translateY(-5px);
        }
        .product-item img {
            width: 100%;
            height: auto;
        }
        .product-item p {
            margin: 10px;
            font-size: 16px;
            color: #333;
        }
        .product-item form {
            text-align: center;
        }
        .product-item button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .product-item button:hover {
            background-color: #45a049;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 50px 0;
        }

        .footer-content {
            display: flex;
            justify-content: space-around;
        }

        .footer-column {
            flex: 1;
            padding: 0 20px;
        }

        .footer-column h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .footer-column p {
            margin: 0;
            font-size: 14px;
        }

        .search-container {
            text-align: center;
            margin-top: 20px;
        }

        .search-container input[type=text] {
            padding: 10px;
            margin: 5px;
            width: 50%;
            box-sizing: border-box;
        }

        .search-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        span{
            color:orange;
            font-size: 25px;

        }

        .error-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #FF5733;
            color: white;
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
    <!-- Navbar -->
    <nav>
        <ul>
            <li><a href="mainpage.php">SHOPNEST</a></li>
            <li><a href="login.php">LOGIN</a></li>
            <li><a href="signup.php">SIGNUP</a></li>
            <li><a href="admin_login.php">ADMIN</a></li>
        </ul>
    </nav>
   
    <div class="search-container">
        <form action="" method="GET">
            <!-- Set action attribute to empty string to submit form to the same page -->
            <input type="text" placeholder="Search for products..." name="query">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Product Display -->
    <h2 style="text-align: center; color:orange">Available Products</h2>
    <!-- Display success message if product is added to cart -->
    <?php if (!empty($success_message)): ?>
        <div class="success-message"><?php echo $success_message; ?></div>
        <script>
            // Remove success message after 5 seconds
            setTimeout(function() {
                var successMessage = document.querySelector('.success-message');
                successMessage.parentNode.removeChild(successMessage);
            }, 5000);
        </script>
    <?php endif; ?>
    <div class="product-container">
        <?php
        // If search results are available, display them
        if (!empty($search_results)) {
            echo $search_results;
        } else {
            // Otherwise, display all products
            $sql = "SELECT * FROM product";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $product_id = $row['id'];
                    $product_name = $row['name'];
                    $product_price = $row['price'];
                    $product_description = $row['description'];
                    $product_image = $row['product_image'];

                    // Display each product
                    echo "
                    <div class='product-item'>
                        <img src='data:image/png;base64," . base64_encode($product_image) . "' alt='Product Image'>
                        <p><strong>Product ID:</strong> $product_id</p>
                        <p><strong>Name:</strong> $product_name</p>
                        <p><strong>Price:</strong> $product_price</p>
                        <p><strong>Description:</strong> $product_description</p>
                        <form method='post'>
                            <input type='hidden' name='id' value='$product_id'>
                            <input type='hidden' name='name' value='$product_name'>
                            <input type='hidden' name='price' value='$product_price'>
                            <input type='hidden' name='description' value='$product_description'>
                            <button type='submit' name='add_to_cart'>Add to Cart</button>
                        </form>
                    </div>
                    ";
                }
            } else {
                echo "<p>No products found.</p>";
            }
        }
        ?>
    </div>

    <!-- Footer -->
    <footer>
    <div class="footer-content">
        <div class="footer-column">
            <h3>ABOUT US</h3>
            <p>Raju Kumar Gupta.</p>
            <br>
            <p>Web Developer.</p>
        </div>
        <div class="footer-column">
            <h3>CONTACT US</h3>
            <p>Email: rajukumar200241@.com</p>
            <br>
            <p>Phone: 7061660877</p>
        </div>
        <div class="footer-column">
            <h3>ADDRESS</h3>
            <br>
            <p>Jugsalai</p>
            <br>
            <p>Jamshedpur, India:831006</p>
        </div>
    </div>
    </footer>
</body>
</html>
