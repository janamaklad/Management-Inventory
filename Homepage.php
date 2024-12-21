<?php
session_start();
$error_message = ""; // Initialize the error message variable

// Check if the error query parameter exists and set the error message
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshMart Inventory</title>
    <link rel="stylesheet" href="Design.css"> 
    <link rel="stylesheet" href="navbar.css">
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="/Management-Inventory/Cart/cart.php">Cart</a></li>

                <?php 
                // If the user is logged in
                if (!empty($_SESSION['id'])) {
                    echo "<li><a href='view.php'>View Profile</a></li>";
                    echo "<li><a href='Admin/edituser.php'>Edit Profile</a></li>";
                   echo" <li><a href='/Management-Inventory/Aboutus.php'>About</a></li>";
                    echo"<li><a href='/Management-Inventory/Contact.php'class='button'>Contact us</a></li>";
                    echo "<li><a href='Admin/logout.php'>Log Out</a></li>";
                } else {
                    // Add `onclick` for showing an alert message
                    echo "<li><a href='#' onclick=\"showAlert('You must log in or sign up to access View Profile')\">View Profile</a></li>";
                    echo "<li><a href='#' onclick=\"showAlert('You must log in or sign up to access Edit Profile')\">Edit Profile</a></li>";
                    echo "<li><a href='/Management-Inventory/Aboutus.php'>About</a></li>";
                   echo " <li><a href='/Management-Inventory/Contact.php' class='button'>Contact us</a></li>";
                    echo "<li><a href='verify/login.php'>Login</a></li>";
                    echo "<li><a href='verify/register.php'>Signup</a></li>";
                }
                ?>

             
            </ul>
        </nav>
    </header>

    <main>
        <div class="content">
            <?php 
            // Display the error message if it exists
            if (!empty($error_message)) {
                echo "<p style='color: red; font-weight: bold;'>$error_message</p>";
            }

            // Welcome message for logged-in users
            if (!empty($_SESSION['id'])) {
                echo "<h3>Welcome " . htmlspecialchars($_SESSION['name']) . "</h3>";
            }
            ?>
            <h1>FreshMart Inventory</h1>
            <p>Welcome to FreshMart – Freshness delivered daily!</p>
            <a href="user/products.php" class="discover-btn">Discover more</a>
        </div>
    </main>
</body>
</html>
