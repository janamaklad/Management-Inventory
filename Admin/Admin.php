<?php
include '../db.php';
require_once '../classes/Product.php';
require_once '../classes/DashboardUpdater.php'; // Include the DashboardUpdater observer
require_once '../classes/ReorderNotifier.php'; // Include the ReorderNotifier observer

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (empty($_SESSION['id']) || $_SESSION['usertypeid'] != 1) {
    // Redirect to a login page with an error message
    header("Location: ./Admin.php?error=access_denied");
    exit();
}

$sql = "SELECT id, name, email, password, usertype_id FROM users WHERE usertype_id != 2";

$result = $conn->query($sql);



// Check if the request is to reduce stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['reduce_quantity'])) {
    $productId = $_POST['product_id'];
    $reduceQuantity = $_POST['reduce_quantity'];

    try {
        // Create a product instance
        $product = new Product($conn, $productId);

        // Create and attach observers
        $reorderNotifier = new ReorderNotifier();
        $dashboardUpdater = new DashboardUpdater($conn);
        $product->attachObserver($reorderNotifier);
        $product->attachObserver($dashboardUpdater);

        // Reduce stock and trigger observers
        $product->reduceStock($reduceQuantity);

        echo "Stock updated successfully.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Validate admin session
if (empty($_SESSION['id']) || $_SESSION['usertypeid'] != 1) {
    header("Location: ./Admin.php?error=access_denied");
    exit();
}

// Fetch product and supplier data for the dashboard
$productSql = "SELECT id, ProductName, Quantity FROM products";
$productResult = $conn->query($productSql);
$totalProducts = $productResult->num_rows;

$lowStockCount = 0;
$lowStockThreshold = 10; // Define the threshold for low stock
while ($row = $productResult->fetch_assoc()) {
    if ($row['Quantity'] < $lowStockThreshold) {
        $lowStockCount++;
    }
}

$supplierSql = "SELECT COUNT(*) as totalSuppliers FROM suppliers";
$supplierResult = $conn->query($supplierSql);
$totalSuppliers = $supplierResult->fetch_assoc()['totalSuppliers'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshMart Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css"> 
</head>
<body>
    <?php include 'AdminNavbar.php'; ?>

    <div class="sidebar">
        <h4 class="text-light">Menu</h4>
        <a href="#">Stock Management</a>
        <a href="report.php">Reports</a>
        <a href="Suppliers.php">Suppliers</a>
        <a href="orders.php">Orders</a>
        <a href="#">Settings</a>
        <a href="products.php">Products</a>
        <a href="../managenavbar.php">Manage Navbar Buttons</a>
        <a href="../verify/factory/testfactory.php">Factory Options</a> <!-- New Factory Options Button -->

    </div>

    <div class="main-content">
        <h2>Dashboard</h2>
        <div class="row">
            <div class="col-md-4"><div class="card"><div class="card-body"><h5>Total Products</h5><p><?= $totalProducts ?></p></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><h5>Low Stock Items</h5><p><?= $lowStockCount ?></p></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><h5>Total Suppliers</h5><p><?= $totalSuppliers ?></p></div></div></div>
        </div>

        <h3>Stock </h3>
        <table class="table">
            <thead><tr><th>Product Name</th><th>Stock Level</th></thead>
            <tbody>
                <?php
                $productResult = $conn->query($productSql);
                while ($row = $productResult->fetch_assoc()) {
                    echo "<tr><td>{$row['ProductName']}</td><td>{$row['Quantity']}</td>";
                }
                ?>
            </tbody>
        </table>
        <!-- User Management Section -->
<h3 class="mt-5">User Management</h3>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Filter out users with usertypeid = 2 (if necessary)
            if ($row['usertype_id'] == 2) {
                continue;
            }

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td>" . ($row["usertype_id"] == 1 ? "Admin" : "User") . "</td>";
            echo "<td>";
            
            // Edit and Delete buttons
            echo "<a href='edituser.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm mx-2'>Edit</a>";
            echo "<form method='post' action='deleteuser.php' style='display:inline-block'>"; 
            echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
            echo "<button type='submit' class='btn btn-danger btn-sm mx-2'>Delete</button>";
            echo "</form>";

            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No users found.</td></tr>";
    }
    ?>
    </tbody>
</table>

<!-- Add User Button -->
<a href="adduser.php" class="btn btn-success btn-sm mt-3">Add User</a>

<?php
$conn->close();
//session_destroy();
?> 
    </div>
</body>
</html>

<?php $conn->close(); ?>

