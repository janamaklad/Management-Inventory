<?php
// Include the navbar
include('navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - FreshMart Inventory</title>
    <link rel="stylesheet" href="contact.css"> <!-- Link to your CSS file -->
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <div class="container">
        <h1>Contact Us</h1>
        <form action="send_contact.php" method="POST"> <!-- Action for form submission -->
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>