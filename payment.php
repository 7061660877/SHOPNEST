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

// Retrieve the user's email from the session
$user_email = $_SESSION['email'];

// Fetch the user's ID from the users table
$sql_user_id = "SELECT id FROM users WHERE email = '$user_email'";
$result_user_id = $conn->query($sql_user_id);

if ($result_user_id->num_rows > 0) {
    // Fetch the user ID
    $row_user_id = $result_user_id->fetch_assoc();
    $user_id = $row_user_id['id'];
} else {
    // Redirect with an error if user ID not found
    header("Location: payment.php?error=user_id_not_found");
    exit();
}

// Fetch the total amount from the cart_total table
$sql_total = "SELECT total_amount FROM cart_total WHERE user_email = '$user_email'";
$result_total = $conn->query($sql_total);

if ($result_total->num_rows > 0) {
    // Fetch the total amount
    $row_total = $result_total->fetch_assoc();
    $total_amount = $row_total['total_amount'];
} else {
    // Set total amount to 0 if not found
    $total_amount = 0;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve payment information from the form
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Perform payment verification logic here
    // For demonstration purposes, let's assume payment is successful if the total amount is greater than 0
    if ($total_amount > 0) {
        // Insert payment information into the database
        $sql_payment = "INSERT INTO confirm_order (user_id, total_amount) VALUES ('$user_id', $total_amount)";
        if ($conn->query($sql_payment) === TRUE) {
            // Payment successful, redirect to order confirmation page
            header("Location: confirmation.php");
            exit();
        } else {
            // Error occurred while inserting payment information
            echo "Error: " . $sql_payment . "<br>" . $conn->error;
        }
    } else {
        // Payment failed, redirect back to payment page with error message
        header("Location: payment.php?error=payment_failed");
        exit();
    }
}

$conn->close(); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="payment.css">
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label {
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            width: 100%;
        }
        button[type="submit"] {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .total-amount {
            margin-bottom: 20px;
            font-size: 18px;
            color: green;
        }
        @media screen and (max-width: 600px) {
            .container {
                max-width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment</h2>
        <p>Please enter your payment details:</p>
        <!-- Display the total amount -->
        <div class="total-amount">Total Amount: <?php echo $total_amount; ?></div>
        <form action="payment.php" method="post">
            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" required>

            <label for="expiry_date">Expiry Date:</label>
            <input type="text" id="expiry_date" name="expiry_date" required>

            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" required>

            <button type="submit" name="submit">Pay Total Amount</button>
        </form>
    </div>
</body>
</html>

