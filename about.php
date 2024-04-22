<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Your E-Commerce Store</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Link custom CSS -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #343a40;
            color: #fff;
            padding: 20px 0;
        }
        header h1 {
            margin-bottom: 0;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
        }
        main {
            padding: 40px 20px;
        }
        section {
            margin-bottom: 40px;
        }
        section h2 {
            color: #343a40;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header class="text-center">
        <h1>About Us</h1>
        <!-- Navigation menu -->
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <div class="container">
            <section>
                <h2>Our Story</h2>
                <p>Welcome to Your E-Commerce Store! We are committed to providing you with the best online shopping experience.</p>
                <p>Our journey began with a simple idea: to create a platform where customers can easily find and purchase high-quality products from the comfort of their homes.</p>
                <p>With a wide range of products, seamless checkout process, and excellent customer service, we strive to make your shopping experience enjoyable and hassle-free.</p>
            </section>

            <section>
                <h2>Our Mission</h2>
                <p>Our mission is to connect customers with the products they love, while providing exceptional service and support.</p>
                <p>We are dedicated to offering a diverse selection of products, maintaining competitive prices, and ensuring fast and reliable delivery.</p>
                <p>At Your E-Commerce Store, we value customer satisfaction above all else, and we are constantly working to improve and enhance your shopping experience.</p>
            </section>
            
            <section>
                <h2>Meet the Team</h2>
                <p>Our team is comprised of dedicated individuals who are passionate about e-commerce and customer satisfaction.</p>
                <p>From our developers and designers to our customer support specialists, each member plays a vital role in delivering the best possible service to our customers.</p>
                <p>Together, we work tirelessly to ensure that Your E-Commerce Store remains a trusted destination for online shopping.</p>
            </section>
        </div>
    </main>

    <footer class="text-center">
        <p>&copy; <?php echo date("Y"); ?> Your E-Commerce Store. All rights reserved.</p>
    </footer>
</body>
</html>
