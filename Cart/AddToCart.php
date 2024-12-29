<?php
session_start();
include '../db.php';  // Make sure this path is correct

// Check if the 'id' parameter is set and is not empty
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = (int)$_GET['id'];  // Cast to an integer to prevent SQL injection

    // Fetch product details
    $sql = "SELECT * FROM product WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Initialize cart session if not already set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add product to session cart
        $_SESSION['cart'][] = array(
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['Price'],
            'quantity' => 1
        );

        echo "Product added to cart!";
    } else {
        echo "Product not found.";
    }
} else {
    echo "Product ID is missing.";
}

$conn->close();
?>