<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])) {
    $search_query = $_GET['query'];

    // Fetch products from the database based on the search query
    $sql = "SELECT * FROM add_to_cart WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $product_id = $row['id'];
            $product_name = $row['name'];
            $product_price = $row['price'];
            $product_description = $row['description'];
            $product_image = $row['product_image'];

            // Display product details along with the image and add-to-cart button
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
