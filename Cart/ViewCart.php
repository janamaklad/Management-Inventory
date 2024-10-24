<?php
session_start();

echo '<h1>Your Cart</h1>';

if (!empty($_SESSION['cart'])) {
    echo '<table>';
    echo '<tr><th>Product</th><th>Price</th><th>Quantity</th></tr>';

    $total = 0;

    foreach ($_SESSION['cart'] as $item) {
        echo '<tr>';
        echo '<td>' . $item['name'] . '</td>';
        echo '<td>$' . $item['price'] . '</td>';
        echo '<td>' . $item['quantity'] . '</td>';
        echo '</tr>';

        $total += $item['price'] * $item['quantity'];
    }

    echo '<tr><td colspan="2">Total</td><td>$' . $total . '</td></tr>';
    echo '</table>';
} else {
    echo 'Your cart is empty.';
}
?>
