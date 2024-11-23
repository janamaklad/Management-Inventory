<?php

$servername = "localhost"; 
$username = "root";
$password = ""; 
$dbname = "newmanagmentt"; 


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users from the database

$sql = "SELECT id, name, email, password, usertype_id FROM users WHERE usertype_id != 2";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Admin.css"> 
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'AdminNavBar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-light">Menu</h4>
        <a href="#">Stock Management</a>
        <a href="report.php">Reports</a>
        <a href="Suppliers.php">Suppliers</a>
        <a href="orders.php">Orders</a>
        <a href="#">Settings</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Dashboard</h2>
        <div class="row">
            <!-- Cards showing some stats -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text">150</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Low Stock Items</h5>
                        <p class="card-text">12</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Suppliers</h5>
                        <p class="card-text">8</p>
                    </div>
                </div>
            </div>
        </div>
 <!-- Stock Management Table -->
        <h3>Stock Management</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Stock Level</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Product A</td>
                    <td>Category 1</td>
                    <td>25</td>
                    <td>Supplier X</td>
                    <td>
                        <button class="btn btn-primary btn-sm">Edit</button>
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>Product B</td>
                    <td>Category 2</td>
                    <td>5</td>
                    <td>Supplier Y</td>
                    <td>
                        <button class="btn btn-primary btn-sm">Edit</button>
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
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
?>
