<?php
session_start();

// Check if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Include database connection
include 'config.php';

// Fetch admin's name from the database
$admin_name = "";
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $sql = "SELECT name FROM admins WHERE id = '$admin_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $admin_name = $row['name'];
    }
}

// Logout functionality
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect to the login page
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Dashboard</title>
    <style>
        /* Reset default browser styles */
body, h1, h2, h3, ul {
    margin: 0;
    padding: 0;
}

/* Basic layout styles */
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
}

header {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 20px 0;
}

nav {
    background-color: #444;
    color: #fff;
}

nav ul {
    list-style-type: none;
    padding: 10px;
    display: flex;
    justify-content: center;
}

nav ul li {
    margin-right: 20px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
}

main {
    padding: 20px;
}

footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 10px 0;
}

img.dashboard-image {
    display: block;
    margin: 20px auto;
    max-width: 100%;
    height: auto;
}

span 
{
    color: orange;
    font-size:20px;
}
    </style>

</head>
<body>

<header>
    <h1>E-commerce Dashboard</h1>
</header>
<nav>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="view_products.php">VIEW PRODUCT</a></li>
        <li><a href="add_product.php">ADD PRODUCT</a></li>
        <li><a href="view_users.php">VIEW CUSTOMER</a></li>
        <li><a href="logout.php">LOGOUT</a></li>
        <?php if (!empty($admin_name)) : ?>
            <li><span>Welcome, <?php echo $admin_name; ?></span></li>
        <?php endif; ?>
    </ul>
</nav>
<main>
    <!-- Dashboard content goes here -->
</main>
<img src="images\shopping-bag-cart.jpg" class="dashboard-image">
<footer>
    <p>&copy; 2024 E-commerce Website. All rights reserved.</p>
</footer>

</body>
</html>
