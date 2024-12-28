<?php
session_start();

// Ensure cart is initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to calculate total cart price
function calculateCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return number_format($total, 2);
}

// Handle quantity updates via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'], $_POST['product_name'])) {
        $productName = $_POST['product_name'];

        // Check if the product exists in the cart
        if (isset($_SESSION['cart'][$productName])) {
            if ($_POST['action'] === 'increment') {
                $_SESSION['cart'][$productName]['quantity']++;
            } elseif ($_POST['action'] === 'decrement' && $_SESSION['cart'][$productName]['quantity'] > 1) {
                $_SESSION['cart'][$productName]['quantity']--;
            }
        }
    }

    // Redirect back to the cart page to prevent resubmission
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header class="text-white text-center py-3">
        <?php include('../navbar.php'); ?>
    </header>

    <div class="container mt-4">
        <h2>Your Cart</h2>

        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $productName => $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        $total += $itemTotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>
                                <form method="POST" action="cart.php" class="d-inline">
                                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($productName) ?>">
                                    <button class="btn btn-sm btn-secondary" type="submit" name="action" value="decrement">-</button>
                                    <span class="mx-2"><?= $item['quantity'] ?></span>
                                    <button class="btn btn-sm btn-secondary" type="submit" name="action" value="increment">+</button>
                                </form>
                            </td>
                            <td>$<?= number_format($itemTotal, 2) ?></td>
                            <td>
                                <form method="POST" action="cart_handler.php" style="display:inline;">
                                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($productName) ?>">
                                    <button class="btn btn-danger btn-sm" type="submit" name="action" value="delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Total: $<?= number_format($total, 2) ?></h3>
            <a href="checkout.php" class="btn btn-primary">Proceed to Payment</a>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
    </footer>
</body>
</html>
