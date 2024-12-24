<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Example</title>
    <style>
        /* Navbar styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            padding: 5px 15px; /* Reduced padding around the navbar */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            height: 80px; /* Fixed height */
        }

        .navbar .logo img {
            height: 80px; /* Adjusted logo height */
            margin: 0;
            padding: 0;
        }

        .navbar ul {
            list-style-type: none;
            display: flex;
            gap: 15px;
            margin: 0;
            padding: 0;
        }

        .navbar ul li {
            display: inline;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #333;
            font-size: 15px;
            transition: color 0.3s ease;
        }

        .navbar ul li a:hover {
            color: gray;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
                height: auto;
            }

            .navbar ul {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }

            .navbar ul li {
                width: 100%;
                text-align: left;
            }

            .navbar ul li a {
                padding: 10px;
                display: block;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <!-- Logo section -->
        <div class="logo">
            <a href="/Management-Inventory/Homepage.php"> <!-- Link to homepage -->
                <img src="/Management-Inventory/images/logo.png" alt="FreshMart Logo">
            </a>
        </div>
        
        <!-- Links section -->
        <ul>
            <li><a href="/Management-Inventory/Homepage.php">Home</a></li>
            <?php 
            if (!empty($_SESSION['id'])) {
                echo "<li><a href='/Management-Inventory/Admin/logout.php'>Log Out</a></li>";
                echo "<li><a href='/Management-Inventory/Cart/cart.php'>Cart</a></li>";
            } else {
                echo "<li><a href='#' onclick=\"alert('You must log in or sign up to access the cart')\">Cart</a></li>";
                echo "<li><a href='/Management-Inventory/verify/login.php'>Login</a></li>";
                echo "<li><a href='/Management-Inventory/verify/register.php'>Signup</a></li>";
            }
            ?>
        </ul>
    </nav>

</body>
</html>
