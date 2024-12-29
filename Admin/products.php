<?php
include('../db.php'); // Database connection
include __DIR__ . '/../classes/Product.php';

$productHandler = new Product($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['addProduct'])) {
            $productHandler->addProduct($_POST['ProductName'], $_POST['Category'], $_POST['Quantity'], $_POST['SellerName'], $_POST['ImageURL'], $_POST['Price']);
            header("Location: products.php");
            exit;
        } elseif (isset($_POST['editProduct'])) {
            $productHandler->editProduct($_POST['id'], $_POST['ProductName'], $_POST['Category'], $_POST['Quantity'], $_POST['SellerName'], $_POST['ImageURL'], $_POST['Price']);
            header("Location: products.php");
            exit;
        }
    } catch (Exception $e) {
        echo "<script>alert('{$e->getMessage()}');</script>";
    }
}

if (isset($_GET['deleteProduct'])) {
    try {
        $productHandler->deleteProduct($_GET['deleteProduct']);
        header("Location: products.php");
        exit;
    } catch (Exception $e) {
        echo "<script>alert('{$e->getMessage()}'); window.location.href = 'products.php';</script>";
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
        <a href="Admin.php" class="btn btn-info btn-sm mb-4">Back</a>
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
                $result = $productHandler->getProducts();
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
                                            <label class="form-label">Product Name</label>
                                            <input type="text" class="form-control" name="ProductName" value="<?= htmlspecialchars($row['ProductName']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <input type="text" class="form-control" name="Category" value="<?= htmlspecialchars($row['Category']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control" name="Quantity" value="<?= htmlspecialchars($row['Quantity']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Seller Name</label>
                                            <input type="text" class="form-control" name="SellerName" value="<?= htmlspecialchars($row['SellerName']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Image URL</label>
                                            <input type="text" class="form-control" name="ImageURL" value="<?= htmlspecialchars($row['image_url']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Price</label>
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
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="ProductName" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <input type="text" class="form-control" name="Category" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="Quantity" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Seller Name</label>
                                <input type="text" class="form-control" name="SellerName" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Image URL</label>
                                <input type="text" class="form-control" name="ImageURL" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" name="Price" required>
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
