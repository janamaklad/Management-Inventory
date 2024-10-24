<?php
include '../db.php'; // Include the database connection
include '../classes/Product.php'; // Include the Product class
include '../classes/Order.php'; // Include the Order class

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['product_id'];  // ID of the product ordered
    $quantity = $_POST['quantity'];  // Quantity of the product ordered

    try {
        $order = new Order($conn, $productId, $quantity);  // Create an Order instance
        $message = $order->placeOrder();  // Place the order

        echo json_encode(['success' => true, 'message' => $message]);  // Success message
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);  // Error message
    }
}
?>
