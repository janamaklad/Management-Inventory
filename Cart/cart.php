<?php
session_start();

// Check if the cart exists in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle AJAX requests to update quantities or delete items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['status' => 'error'];
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update') {
            $productName = $_POST['product_name'];
            $quantity = max(0, (int)$_POST['quantity']);
            if (isset($_SESSION['cart'][$productName])) {
                if ($quantity === 0) {
                    unset($_SESSION['cart'][$productName]);
                } else {
                    $_SESSION['cart'][$productName]['quantity'] = $quantity;
                }
                $response = ['status' => 'success', 'total' => calculateCartTotal()];
            }
        } elseif ($_POST['action'] === 'delete') {
            $productName = $_POST['product_name'];
            if (isset($_SESSION['cart'][$productName])) {
                unset($_SESSION['cart'][$productName]);
                $response = ['status' => 'success', 'total' => calculateCartTotal()];
            }
        }
    }
    echo json_encode($response);
    exit;
}

// Function to calculate total cart price
function calculateCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return number_format($total, 2);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<header class="text-white text-center py-3">
    <?php include('../Navbar.php'); ?> <!-- Include the navbar -->
</header>

    <div class="container mt-4">
        <h2>Your Cart</h2>

<footer class="bg-light text-center py-3">
    <p>&copy; 2024 FreshMart Inventory System. All rights reserved.</p>
</footer>

    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
    </footer>

   <script src="cart.js"></script>
</body>
</html>
