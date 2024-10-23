<?php
require 'db.php'; // Include database connection
require 'User.php'; // Include User class

// Create an instance of the User class
$user = new User($db);

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Automatically assign a usertype_id
    $usertype_id = 1; // Assuming '1' is the default user type for normal users

    $message = $user->register($name, $email, $password, $usertype_id);
    echo $message;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Inventory Management System</title>
    <link rel="stylesheet" href="register.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="header">
        <img src="/Management-Inventory/images/logo.png" alt="Logo" class="logo">
        <button class="back-home" onclick="window.location.href='../Homepage.php'">Back to Home</button>
    </div>

    <div class="container">
        <h1>Register</h1>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <span class="toggle-btn" onclick="togglePassword('password')">Show</span>
            </div>
            <div class="form-group">
                <button type="submit" name="register">Register</button>
            </div>
        </form>

        <div class="toggle-container">
            <span>Already have an account?</span>
            <a href="login.php" class="toggle-btn">Login here</a>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            var input = document.getElementById(id);
            input.type = (input.type === "password") ? "text" : "password";
        }
    </script>
</body>
</html>
