<?php
session_start();

// Simulate fetching product details from the database
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $productName = $input['productName'];

    // Fetch product details by name
    $sql = "SELECT * FROM products WHERE ProductName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Initialize cart session if not already set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add product to cart
        if (!isset($_SESSION['cart'][$product['ID']])) {
            $_SESSION['cart'][$product['ID']] = [
                'id' => $product['ID'],
                'name' => $product['ProductName'],
                'price' => $product['Price'],
                'quantity' => 1, // Initial quantity
            ];
        } else {
            $_SESSION['cart'][$product['ID']]['quantity'] += 1;
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
