<?php
session_start();
include 'db_connect.php';

$product_id = $_GET['id'];

// Fetch product details
$sql = "SELECT * FROM product WHERE id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();

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

$conn->close();
?>
<script>
function addToCart(productName) {
    // Retrieve cart data from local storage or initialize it
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Find if the product is already in the cart
    let foundProduct = cart.find(item => item.productName === productName);

    if (foundProduct) {
        // If product is already in the cart, increase quantity
        foundProduct.quantity++;
    } else {
        // Add new product to the cart
        cart.push({ productName: productName, quantity: 1 });
    }

    // Save the updated cart to local storage
    localStorage.setItem('cart', JSON.stringify(cart));

    // Alert or update the UI to show that the product has been added
    alert(productName + " added to cart");
}
</script>
