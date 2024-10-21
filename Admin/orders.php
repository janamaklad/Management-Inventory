<?php
// Database connection
$host = 'localhost'; // Your database host
$db = 'project'; // Your database name
$user = 'root'; // Your database username
$pass = ''; // Your database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully!"; // For testing purposes, you can remove it later
}

// Handle form submission for creating a new order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $supplier_id = $_POST['supplier_id'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO orders (product_id, supplier_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iii", $product_id, $supplier_id, $quantity);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Order created successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "Failed to prepare statement: " . $conn->error;
    }
}

// Fetch orders to display in the table
$sql = "SELECT o.id, p.ProductName AS ProductName, s.supplier_name AS supplier_name, o.quantity, o.order_date, o.status
        FROM orders o
        JOIN products p ON o.product_id = p.id
        JOIN suppliers s ON o.supplier_id = s.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Orders</title>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Orders</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Supplier Name</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['ProductName']}</td>
                                <td>{$row['supplier_name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>{$row['order_date']}</td>
                                <td>{$row['status']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <td>
    <form action="update_order_status.php" method="POST">
        <select name="status" class="form-control">
            <option value="Pending">Pending</option>
            <option value="Shipped">Shipped</option>
            <option value="Delivered">Delivered</option>
        </select>
        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</td>

        <h2 class="my-4">Create Order</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="product_id">Product</label>
                <select name="product_id" id="product_id" class="form-control" required>
                    <?php
                    // Fetch products for the dropdown
                    $productSql = "SELECT id, ProductName FROM products";
                    $productResult = $conn->query($productSql);
                    while ($product = $productResult->fetch_assoc()) {
                        echo "<option value='{$product['id']}'>{$product['ProductName']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="supplier_id">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                    <?php
                    // Fetch suppliers for the dropdown
                    $supplierSql = "SELECT id, supplier_name FROM suppliers";
                    $supplierResult = $conn->query($supplierSql);
                    while ($supplier = $supplierResult->fetch_assoc()) {
                        echo "<option value='{$supplier['id']}'>{$supplier['supplier_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Order</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
