<?php
// Include database connection
include('../db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="products.css">
</head>
<body>
    <header class="text-white text-center py-3">
        <?php include('../navbar.php'); ?> <!-- Include the navbar -->
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
                    <option value="groceries">Groceries</option>
                    <option value="bakery">Bakery</option>
                    <option value="drinks">Drinks</option>
                </select>
            </div>
        </div>

        <div class="row" id="productContainer">
            <?php
            // Fetch products from the database
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            // Check if there are products in the database
            if ($result->num_rows > 0) {
                // Loop through and display products
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-3">';
                    echo '  <div class="card mb-4">';
                    // Directly use the image URL from the database for the image source
                    echo '      <img src="' . $row["image_url"] . '" class="card-img-top" alt="Product Image">';
                    echo '      <div class="card-body">';
                    echo '          <h5 class="card-title">' . $row["ProductName"] . '</h5>';
                    echo '          <p class="card-text">Price: $' . $row["Price"] . '</p>'; // Display price
                    echo '          <p class="card-text">Seller: ' . $row["SellerName"] . '</p>';
                    echo '          <p class="card-text">Available Quantity: ' . $row["Quantity"] . '</p>'; // Show available quantity
                    echo '          <button class="btn btn-success" onclick="addToCart(\'' . $row["ProductName"] . '\')">Add to Cart</button>'; // Updated to green button
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
    <script src="products.js"></script>
</body>
</html>
