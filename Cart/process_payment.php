<?php
session_start();
include '../db.php'; // Adjust the path to match your project structure

// Validate session data
if (!isset($_SESSION['id'])) {
    echo "Session data: ";
    print_r($_SESSION); // Debug output
    die("Error: User is not logged in. Please log in to continue.");
}

$userId = $_SESSION['id']; // Use the correct key from session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input
    $cardName = htmlspecialchars($_POST['card_name']);
    $cardNumber = htmlspecialchars($_POST['card_number']);
    $expiryDate = htmlspecialchars($_POST['expiry_date']);
    $cvv = htmlspecialchars($_POST['cvv']);

    // Validate card number and CVV
    if (strlen($cardNumber) != 16 || !ctype_digit($cardNumber)) {
        die("Error: Invalid card number. Please enter a 16-digit card number.");
    }
    if (strlen($cvv) != 3 || !ctype_digit($cvv)) {
        die("Error: Invalid CVV. Please enter a 3-digit CVV.");
    }

    // Simulate payment processing
    $paymentSuccess = true; // Replace with real payment gateway logic.

    if ($paymentSuccess) {
        // Retrieve cart items for the logged-in user
        $cartQuery = $conn->prepare("SELECT product_id, supplier_id, quantity FROM cart WHERE user_id = ?");
        $cartQuery->bind_param("i", $userId);
        $cartQuery->execute();
        $cartItems = $cartQuery->get_result();

        if ($cartItems->num_rows > 0) {
            $orderDate = date('Y-m-d H:i:s');
            $status = 'Pending';

            // Process each item in the cart
            while ($item = $cartItems->fetch_assoc()) {
                $productId = $item['product_id'];
                $supplierId = $item['supplier_id'];
                $quantity = $item['quantity'];

                // Insert into orders table
                $stmt = $conn->prepare("INSERT INTO orders (product_id, supplier_id, quantity, order_date, status) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiss", $productId, $supplierId, $quantity, $orderDate, $status);
                $stmt->execute();
                $stmt->close();
            }

            // Clear the cart for the user
            $deleteCartQuery = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $deleteCartQuery->bind_param("i", $userId);
            $deleteCartQuery->execute();
            $deleteCartQuery->close();

            echo "<h2>Payment Successful!</h2>";
            echo "<p>Your order has been placed successfully. Thank you, $cardName!</p>";
        } else {
            die("Error: Your cart is empty. Please add items to proceed.");
        }

        $cartQuery->close();
    } else {
        echo "<h2>Payment Failed!</h2>";
        echo "<p>Sorry, we were unable to process your payment. Please try again.</p>";
    }
}
?>
