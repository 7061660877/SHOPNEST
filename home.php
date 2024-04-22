<?php
session_start();

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

// Handle adding products to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    // Check if user is logged in
    if (!isset($_SESSION['email'])) {
        // Redirect to login page with message
        header("Location: login.php?message=Please%20login%20first%20to%20shop");
        exit();
    } else {
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
        /* Responsive layout - makes the product cards stack on top of each other on small screens */
        @media (max-width: 600px) {
            .product-container {
                flex-direction: column;
                align-items: center;
            }

            .product-item {
                width: 80%;
                margin-bottom: 20px;
            }

            .search-container input[type=text] {
                width: 80%;
            }
        }

        /* Remaining CSS remains the same */
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <ul>
            <li><a href="about.php">ABOUT US</a></li>
            <li><a href="contact.php">CONTACT US</a></li>
            <!-- Display total items in cart on the cart link -->
            <li><a href="cart.php">CART <?php echo $total_items > 0 ? "($total_items)" : ""; ?></a></li>
            <!-- Check if user is logged in -->
            <?php if (!isset($_SESSION['email'])): ?>
                <!-- If not logged in, show login and signup links -->
                <li><a href="login.php">LOGIN</a></li>
                <li><a href="signup.php">SIGNUP</a></li>
            <?php else: ?>
                <!-- If logged in, show logout link -->
                <li><a href="logout.php">LOGOUT</a></li>
            <?php endif; ?>
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
    <!-- Display login message if user needs to login -->
    <?php if (isset($_GET['message'])): ?>
        <div class="login-message"><?php echo $_GET['message']; ?></div>
        <script>
            // Remove login message after 5 seconds
            setTimeout(function() {
                var loginMessage = document.querySelector('.login-message');
                loginMessage.parentNode.removeChild(loginMessage);
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
