

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Example</title>
    <style>
        /* Navbar styles */
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
    height: 80px; /* Added fixed height to restrict navbar size */
}

.navbar .logo img {
    height: 120px; /* Further reduced logo height */
    margin: 0; /* Removed all margins */
    padding: 0; /* Ensured no extra padding */
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
    font-size: 15px; /* Reduced font size */
    transition: color 0.3s ease;
}

.navbar ul li a:hover {
    color: gray;
}

    </style>
</head>
<body>

    <nav class="navbar">
        <!-- Logo section -->
        <div class="logo">
            <a href="\Management-Inventory\Homepage.php"> <!-- Link to homepage -->
                <img src="\Management-Inventory\images\logo.png" alt="FreshMart Logo">
            </a>
        </div>
        
        <!-- Links section -->
        <ul>
            <li><a href="\Management-Inventory\Cart\cart.php">Cart</a></li>
            <li><a href="\Management-Inventory\verify\login.php">Login</a></li>
            <li><a href="\Management-Inventory\verify\register.php">Signup</a></li>
            <li><a href="\Management-Inventory\Aboutus.php">About</a></li>
            <li><a href="\Management-Inventory\Contact.php">Contact</a></li>
        </ul>
    </nav>

</body>
</html>
