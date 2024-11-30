<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="cart.css">
</head>
<body>
<header class="text-white text-center py-3">
    <?php include('../Navbar.php'); ?> <!-- Include the navbar -->
</header>

<div class="container mt-4">
    <h2>Your Cart</h2>
    <ul id="cartList"></ul> <!-- Cart items will be loaded here by JS -->
</div>

<footer class="bg-light text-center py-3">
    <p>&copy; 2024 FreshMart Inventory System. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="products.js"></script> <!-- Load cart using products.js -->
</body>
</html>
