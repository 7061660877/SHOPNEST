<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if the email already exists in the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Email already exists
        echo json_encode(array('exists' => true));
    } else {
        // Email does not exist
        echo json_encode(array('exists' => false));
    }
}
$conn->close();
?>
