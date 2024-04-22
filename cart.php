<?php
session_start();

// Include database connection
include 'config.php';

// Check if the user is not logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}

// User is logged in, continue displaying the page

// Handle removing a product from the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_product'])) {
    $index = $_POST['index'];

    // Retrieve cart items from session
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

    // Check if the index exists in the cart array
    if (array_key_exists($index, $cart)) {
        // Delete the corresponding row from the usecart table
        $user_email = $_SESSION['email']; // Assuming user_email is set in session after login
        $product_id = $cart[$index]['id'];
        $sql_delete = "DELETE FROM usecart WHERE user_email = '$user_email' AND product_id = $product_id";
        if ($conn->query($sql_delete) === TRUE) {
            // Remove the product from the cart array
            unset($cart[$index]);

            // Reset array keys to avoid gaps in numeric keys
            $cart = array_values($cart);

            // Update the cart session variable
            $_SESSION['cart'] = $cart;

            // Calculate total amount
            $total_amount = 0;
            foreach ($cart as $item) {
                $total_amount += $item['price'];
            }

            // Update or insert the total amount in the cart_total table
            $sql_total = "SELECT * FROM cart_total WHERE user_email = '$user_email'";
            $result_total = $conn->query($sql_total);

            if ($result_total->num_rows > 0) {
                // Update existing total amount
                $sql_update_total = "UPDATE cart_total SET total_amount = $total_amount WHERE user_email = '$user_email'";
                if ($conn->query($sql_update_total) === FALSE) {
                    echo "Error updating total amount: " . $conn->error;
                }
            } else {
                // Insert new total amount
                $sql_insert_total = "INSERT INTO cart_total (user_email, total_amount) VALUES ('$user_email', $total_amount)";
                if ($conn->query($sql_insert_total) === FALSE) {
                    echo "Error inserting total amount: " . $conn->error;
                }
            }

            // Refresh the page to reflect the changes
            header("Location: cart.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}

$user_email = $_SESSION['email']; // Assuming user_email is set in session after login
$sql = "SELECT * FROM usecart WHERE user_email = '$user_email'";
$result = $conn->query($sql);

$cart = array(); // Initialize cart array

if ($result !== false && $result->num_rows > 0) {
    // Fetch and store cart items
    while ($row = $result->fetch_assoc()) {
        $cart[] = array(
            'id' => $row['product_id'],
            'name' => $row['product_name'],
            'price' => $row['product_price'],
            'description' => $row['product_description']
        );
    }
}

// Calculate total amount
$total_amount = 0;
foreach ($cart as $item) {
    $total_amount += $item['price'];
}

// Update or insert the total amount in the cart_total table
$sql_total = "SELECT * FROM cart_total WHERE user_email = '$user_email'";
$result_total = $conn->query($sql_total);

if ($result_total->num_rows > 0) {
    // Update existing total amount
    $sql_update_total = "UPDATE cart_total SET total_amount = $total_amount WHERE user_email = '$user_email'";
    if ($conn->query($sql_update_total) === FALSE) {
        echo "Error updating total amount: " . $conn->error;
    }
} else {
    // Insert new total amount
    $sql_insert_total = "INSERT INTO cart_total (user_email, total_amount) VALUES ('$user_email', $total_amount)";
    if ($conn->query($sql_insert_total) === FALSE) {
        echo "Error inserting total amount: " . $conn->error;
    }
}

$conn->close(); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your head content here -->
</head>
<link rel="stylesheet" href="cart.css"> 
<body>
    <!-- Your HTML content here -->
    <div class="container">
        <?php if (!empty($cart)): ?>
            <h2>Shopping Cart</h2>
            <table>
                <!-- Table header -->
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                <!-- Table rows for cart items -->
                <?php foreach ($cart as $index => $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['description']; ?></td>
                        <td><?php echo $item['price']; ?></td>
                        <!-- Form for removing a product -->
                        <td>
                            <form method="post">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <button type="submit" name="remove_product">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <!-- Table row for total amount -->
                <tr>
                    <td colspan="3">Total Amount</td>
                    <td><?php echo $total_amount; ?></td>
                    <td></td> <!-- Empty column for action -->
                </tr>
            </table>
            <!-- Place Order Button -->
            <form action="shipping.php" method="post">
                <button type="submit" name="place_order">Place Order</button>
            </form>
            <!-- Back Button -->
            <form action="index.php" method="get">
                <button type="submit">Back to Shop</button>
            </form>
        <?php else: ?>
            <p>Your shopping cart is empty.</p>
            <a href="index.php">Back to Shop</a>
        <?php endif; ?>
    </div>
</body>
</html>