<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve payment information from the form
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Retrieve total amount from the session cart
    $total_amount = isset($_SESSION['total_amount']) ? $_SESSION['total_amount'] : 0;

    // Perform payment verification logic here
    // For demonstration purposes, let's assume payment is successful if the total amount is greater than 0
    if ($total_amount > 0) {
        // Insert payment information into the database
        $user_id = 1; // Example user ID (modify as per your logic)
        $sql = "INSERT INTO confirm_order (user_id, total_amount) VALUES ($user_id, $total_amount)";
        if ($conn->query($sql) === TRUE) {
            // Payment successful, redirect to order confirmation page
            header("Location: confirmation.php");
            exit();
        } else {
            // Error occurred while inserting payment information
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Payment failed, redirect back to payment page with error message
        header("Location: payment.php?error=payment_failed");
        exit();
    }
} else {
    // If the form is not submitted properly, redirect back to payment page
    header("Location: payment.php");
    exit();
}

// Close database connection
$conn->close();
?>
