<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $message = $_POST['message'];

    // Connect to database
    include 'config.php';

    // Insert contact form data into the database
    $sql = "INSERT INTO contact (name, email, mobile, message) VALUES ('$name', '$email', '$mobile', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Use JavaScript to display success message with animation
        echo "<script>
                window.onload = function() {
                    var successMessage = document.createElement('div');
                    successMessage.textContent = 'Message sent successfully';
                    successMessage.className = 'success-message';
                    document.body.appendChild(successMessage);
                    setTimeout(function() {
                        successMessage.style.opacity = '0';
                        setTimeout(function() {
                            successMessage.remove();
                            document.getElementById('contactForm').submit();
                        }, 1000);
                    }, 5000);
                };
              </script>";
    } else {
        echo "Failed to send message to customer care: " . $conn->error;
    }

    $conn->close();
} 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; /* Updated background color */
        }
        #h3 {
            text-align: center;
            margin-top: 20px;
            color: #333; /* Changed text color */
        }
        #d3 {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 20px;
        }
        #d2 {
            background-color: #fff; /* Changed background color */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 80%; /* Added width */
            max-width: 600px; /* Added max-width for responsiveness */
        }
        .img2 {
            display: block;
            margin: auto;
        }
        .img1 {
            float: left;
            margin-right: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label, input, textarea, button {
            margin-bottom: 10px;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #4CAF50; /* Green background color */
            color: white;
            border-radius: 5px;
            animation: slideIn 0.5s ease-in-out forwards, fadeOut 0.5s ease-in-out 4.5s forwards;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    </style>
</head>
<body>
    <h3 id="h3">CONTACT US</h3>
    <div id="d3">
        
        <div id="d2">
            <img src="images/contact2.PNG" class="img1">
           
            <!-- Form submits data to contact.php -->
            <form action="contact.php" method="post" id="contactForm">
                <label for="name">NAME</label>
                <input type="text" id="name" name="name" placeholder="ENTER YOUR NAME" required>
                
                <label for="email">EMAIL</label>
                <input type="email" id="email" name="email" placeholder="ENTER YOUR EMAIL ID" required>
                
                <label for="mobile">MOBILE NO</label>
                <input type="text" id="mobile" name="mobile" placeholder="ENTER YOUR MOBILE NO" required>
                
                <label for="message">MESSAGE</label>
                <textarea id="message" name="message" placeholder="ENTER YOUR QUERY" required></textarea>
                
                <button type="submit">SUBMIT</button>
            </form>
        </div>
        <a href="index.php">GO TO STORE</a>
    </div> 
</body>
</html>
