<?php
include '../db.php';
include 'AdminNavBar.php';
include '../classes/Product.php'; // Assuming you have a Product class
include '../classes/Order.php';   // Assuming you have an Order class

try {
    // Assuming you have a valid database connection stored in $conn
    $productId = 1; // Replace with the actual product ID you need
    $product = new Product($conn, $productId);

    // Now you can get product details or reduce stock
    echo "Product Name: " . $product->getName();
    echo "Current Stock: " . $product->getStock();

    // Reduce stock if an order is made
    $orderQuantity = 5; // Example order quantity
    $product->reduceStock($orderQuantity);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Handle form submission for creating a new order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_order'])) {
    $product_id = $_POST['product_id'];
    $supplier_id = $_POST['supplier_id'];
    $quantity = $_POST['quantity'];

    // Create an instance of the Order class
    $order = new Order($conn);
    
    // Start a transaction
    $conn->begin_transaction();
    try {
        // Create the order
        $order->createOrder($product_id, $supplier_id, $quantity);
        
        // Create an instance of the Product class to update stock
        $product = new Product($conn, $product_id); // Pass both parameters
        $product->reduceStock($quantity); // Decrease stock
        
        // Commit the transaction
        $conn->commit();
        echo "<div class='alert alert-success'>Order created and stock updated successfully!</div>";
    } catch (Exception $e) {
        // Rollback if an error occurs
        $conn->rollback();
        echo "<div class='alert alert-danger'>Transaction failed: " . $e->getMessage() . "</div>";
    }
}

// Handle form submission for updating an order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];

    // Make sure the SQL query is correct and status is passed correctly
    $order = new Order($conn);
    $order->updateOrder($order_id, $quantity, $status);
}

// Handle order deletion
if (isset($_GET['delete_order'])) {
    $order_id = $_GET['delete_order'];
    $order = new Order($conn);
    $order->deleteOrder($order_id);
}

// Fetch orders to display in the table
$order = new Order($conn);
$result = $order->fetchOrders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Admin.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
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
                    <th>Actions</th>
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
                    <td>
                        <button class='btn btn-primary' data-toggle='modal' data-target='#editOrderModal{$row['id']}'>Edit</button>
                        <a href='?delete_order={$row['id']}' class='btn btn-danger'>Delete</a>
                    </td>
                </tr>";
            ?>

            <!-- Modal for editing order -->
            <div class='modal fade' id='editOrderModal<?php echo $row['id']; ?>' tabindex='-1' role='dialog' aria-labelledby='editOrderModalLabel' aria-hidden='true'>
                <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='editOrderModalLabel'>Edit Order</h5>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <form action='' method='POST'>
                            <div class='modal-body'>
                                <div class='form-group'>
                                    <label for='quantity'>Quantity</label>
                                    <input type='number' name='quantity' value='<?php echo $row['quantity']; ?>' class='form-control' required>
                                </div>
                                <div class='form-group'>
                                    <label for='status'>Status</label>
                                    <select name='status' class='form-control'>
                                        <option value='Pending' <?php echo ($row['status'] == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value='Shipped' <?php echo ($row['status'] == 'Shipped' ? 'selected' : ''); ?>>Shipped</option>
                                        <option value='Delivered' <?php echo ($row['status'] == 'Delivered' ? 'selected' : ''); ?>>Delivered</option>
                                    </select>
                                </div>
                                <input type='hidden' name='order_id' value='<?php echo $row['id']; ?>'>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                <button type='submit' name='update_order' class='btn btn-primary'>Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php
        }
    } else {
        echo "<tr><td colspan='7'>No orders found</td></tr>";
    }
    ?>
            </tbody>
        </table>

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
            <button type="submit" name="create_order" class="btn btn-primary">Create Order</button>
        </form>
    </div>
</body>
</html>
