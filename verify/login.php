<?php
require 'db.php'; // Include database connection
require 'User.php'; // Include User class

// Create an instance of the User class
$user = new User($db);

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $message = $user->login($name, $password);
    echo "<p>$message</p>"; // Display message
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory Management System</title>
    <link rel="stylesheet" href="login.css"> <!-- Ensure the path is correct -->
</head>
<body>
    <div class="header">
        <img src="/Management-Inventory/images/logo.png" alt="Logo" class="logo"> <!-- Update path to your logo image -->
        <button class="back-home" onclick="window.location.href='../Homepage.php'">Back to Home</button>
    </div>

    <div class="container">
        <h1>Login</h1>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <span class="toggle-btn" onclick="togglePassword('password')">Show</span>
            </div>
            <div class="form-group">
                <button type="submit" name="login">Login</button>
            </div>
        </form>

        <div class="toggle-container">
            <span>Don't have an account?</span>
            <a href="register.php" class="toggle-btn">Register here</a>
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
