<?php
session_start();

if (!empty($_SESSION['cart'])) {
    // Process order, save to database, etc.

    // Empty cart
    $_SESSION['cart'] = [];

    echo 'Checkout complete. Thank you for your purchase!';
} else {
    echo 'Your cart is empty.';
}
?>
