<?php
// Include database connection
include('../db.php');

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Inventory Management System</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">IMS</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Signup</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container mt-4">
        <h2 class="text-center">Available Products</h2>

        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchInput" placeholder="Search products...">
            </div>
            <div class="col-md-4">
                <select class="form-control" id="filterSelect">
                    <option value="category">Filter by category</option>
                    <option value="sweets">Sweets</option>
                    <option value="coffee and tea">Coffee and Tea</option>
                    <option value="Groceries">Groceries</option>
                    <option value="Bakery">Bakery</option>
                    <option value="Meat and Fish">Meat and Fish</option>
                    <option value="Sauces">Sauces</option>
                    <option value="Drinks">Drinks</option>
                </select>
            </div>
        </div>

        <div class="row" id="productContainer">
            <?php
            // Check if there are products in the database
            if ($result->num_rows > 0) {
                // Loop through and display products
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-3">';
                    echo '  <div class="card mb-4">';
                    // Link to the view_image.php script with product ID
                    echo '      <img src="ImageView.php?id=' . $row["ID"] . '" class="card-img-top" alt="Product Image">';
                    echo '      <div class="card-body">';
                    echo '          <h5 class="card-title">' . $row["ProductName"] . '</h5>';
                    echo '          <p class="card-text">Price: $' . $row["Price"] . '</p>';
                    echo '          <p class="card-text">Seller: ' . $row["SellerName"] . '</p>';
                    echo '          <p class="card-text">Quantity: ' . $row["Quantity"] . '</p>';
                    echo '          <button class="btn btn-primary" onclick="addToCart(\'' . $row["ProductName"] . '\')">Add to Cart</button>';
                    echo '      </div>';
                    echo '  </div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No products available.</p>';
            }
            ?>
        </div>
    </div>

    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="user.js"></script>
</body>
</html>
