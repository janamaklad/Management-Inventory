<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Encryption key (store securely)
    $encryption_key = "your_secure_encryption_key"; // Replace with a strong key
    $cipher_method = "AES-256-CBC";
    $iv_length = openssl_cipher_iv_length($cipher_method);

    // Generate IV and store it in a variable
    $iv = openssl_random_pseudo_bytes($iv_length);

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

    // Encrypt sensitive data
    $encryptedCardNumber = openssl_encrypt($cardNumber, $cipher_method, $encryption_key, 0, $iv);
    $encryptedCVV = openssl_encrypt($cvv, $cipher_method, $encryption_key, 0, $iv);

    // Simulate payment processing
    $paymentSuccess = true; // Replace with real payment gateway logic.

    if ($paymentSuccess) {
        // Clear the cart session after payment
        if (isset($_SESSION['cart'])) {
            unset($_SESSION['cart']); // Clear the cart
        }

        // Store payment data securely in the database
        include '../db.php';
        $stmt = $conn->prepare("INSERT INTO payments (card_name, card_number, expiry_date, cvv, iv) VALUES (?, ?, ?, ?, ?)");
        $encodedIv = base64_encode($iv);
        $stmt->bind_param("sssss", $cardName, $encryptedCardNumber, $expiryDate, $encryptedCVV, $encodedIv);

        // Execute query and check for success
        if ($stmt->execute()) {
            // Success message
            echo '<div class="payment-success-container">';
            echo "<h2>Payment Successful!</h2>";
            echo "<p>Thank you, $cardName. Your payment has been processed successfully.</p>";
            echo '<a href="cart.php" class="btn btn-primary">Return to Cart</a>';
            echo '</div>';
        } else {
            echo "<h2>Database Error!</h2>";
            echo "<p>Could not store payment information. Please try again.</p>";
        }

        // Close database connection
        $stmt->close();
        $conn->close();
    } else {
        echo "<h2>Payment Failed!</h2>";
        echo "<p>Sorry, we were unable to process your payment. Please try again.</p>";
        echo '<a href="checkout.php" class="btn btn-danger">Retry Payment</a>';
    }
} else {
    // Redirect to checkout if accessed directly
    header("Location: checkout.php");
    exit();
}
?>
