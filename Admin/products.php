<?php
include('../db.php'); // Ensure database connection

// Handle Add, Edit, and Delete functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['addProduct'])) {
        $ProductName = $_POST['ProductName'];
        $Category = $_POST['Category'];
        $Quantity = $_POST['Quantity'];
        $SellerName = $_POST['SellerName'];
        $ImageURL = $_POST['ImageURL'];
        $Price = $_POST['Price']; // Added Price input

        $stmt = $conn->prepare("INSERT INTO products (ProductName, Category, Quantity, SellerName, image_url, Price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissd", $ProductName, $Category, $Quantity, $SellerName, $ImageURL, $Price);
        $stmt->execute();

        header("Location: products.php");
        exit;
    } elseif (isset($_POST['editProduct'])) {
        $id = $_POST['id'];
        $ProductName = $_POST['ProductName'];
        $Category = $_POST['Category'];
        $Quantity = $_POST['Quantity'];
        $SellerName = $_POST['SellerName'];
        $ImageURL = $_POST['ImageURL'];
        $Price = $_POST['Price']; // Added Price input

        $stmt = $conn->prepare("UPDATE products SET ProductName=?, Category=?, Quantity=?, SellerName=?, image_url=?, Price=? WHERE ID=?");
        $stmt->bind_param("ssissdi", $ProductName, $Category, $Quantity, $SellerName, $ImageURL, $Price, $id);
        $stmt->execute();

        header("Location: products.php");
        exit;
    }
}

if (isset($_GET['deleteProduct'])) {
    $id = $_GET['deleteProduct'];

    $checkQuery = $conn->prepare("SELECT * FROM orders WHERE product_id = ?");
    $checkQuery->bind_param("i", $id);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Cannot delete this product because it is associated with existing orders.'); window.location.href = 'products.php';</script>";
    } else {
        $stmt = $conn->prepare("DELETE FROM products WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: products.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Products Management</h2>

        <!-- Add Product Button -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>
        </div>

        <!-- Products Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Seller Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM products");
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($row['image_url']); ?>" alt="<?= htmlspecialchars($row['ProductName']); ?>" width="100"></td>
                        <td><?= htmlspecialchars($row['ProductName']); ?></td>
                        <td><?= htmlspecialchars($row['Category']); ?></td>
                        <td><?= htmlspecialchars($row['Quantity']); ?></td>
                        <td><?= htmlspecialchars($row['SellerName']); ?></td>
                        <td><?= htmlspecialchars(number_format($row['Price'], 2)); ?> USD</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal<?= $row['ID']; ?>">Edit</button>
                            <a href="products.php?deleteProduct=<?= $row['ID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>

                    <!-- Edit Product Modal -->
                    <div class="modal fade" id="editProductModal<?= $row['ID']; ?>" tabindex="-1" aria-labelledby="editProductModalLabel<?= $row['ID']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="products.php">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editProductModalLabel<?= $row['ID']; ?>">Edit Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['ID']; ?>">
                                        <div class="mb-3">
                                            <label for="productName" class="form-label">Product Name</label>
                                            <input type="text" class="form-control" name="ProductName" value="<?= htmlspecialchars($row['ProductName']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="productCategory" class="form-label">Category</label>
                                            <input type="text" class="form-control" name="Category" value="<?= htmlspecialchars($row['Category']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="productQuantity" class="form-label">Quantity</label>
                                            <input type="number" class="form-control" name="Quantity" value="<?= htmlspecialchars($row['Quantity']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sellerName" class="form-label">Seller Name</label>
                                            <input type="text" class="form-control" name="SellerName" value="<?= htmlspecialchars($row['SellerName']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="imageURL" class="form-label">Image URL</label>
                                            <input type="text" class="form-control" name="ImageURL" value="<?= htmlspecialchars($row['image_url']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="number" step="0.01" class="form-control" name="Price" value="<?= htmlspecialchars($row['Price']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="editProduct" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="products.php">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="productName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="productName" name="ProductName" required>
                            </div>
                            <div class="mb-3">
                                <label for="productCategory" class="form-label">Category</label>
                                <input type="text" class="form-control" id="productCategory" name="Category" required>
                            </div>
                            <div class="mb-3">
                                <label for="productQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="productQuantity" name="Quantity" required>
                            </div>
                            <div class="mb-3">
                                <label for="sellerName" class="form-label">Seller Name</label>
                                <input type="text" class="form-control" id="sellerName" name="SellerName" required>
                            </div>
                            <div class="mb-3">
                                <label for="imageURL" class="form-label">Image URL</label>
                                <input type="text" class="form-control" id="imageURL" name="ImageURL" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="Price" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="addProduct" class="btn btn-primary">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
