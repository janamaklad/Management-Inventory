<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Admin.css"> <!-- Link to the CSS file -->
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'AdminNavBar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-light">Menu</h4>
        <a href="index.php">Dashboard</a>
        <a href="#">Stock Management</a>
        <a href="Suppliers.php">Suppliers</a>
        <a href="Admin/report.php">Reports</a>
        <a href="orders.php">Orders</a>
        <a href="#">Settings</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Dashboard</h2>
        <div class="row">
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

        <!-- Add New Supplier Form -->
        <h3>Add New Supplier</h3>
        <form>
            <input type="text" class="form-control" placeholder="Supplier Name" required>
            <input type="text" class="form-control" placeholder="Contact Info" required>
            <input type="text" class="form-control" placeholder="Payment Terms" required>
            <button type="submit" class="btn btn-primary">Add Supplier</button>
        </form>

        <!-- User Management Section -->
        <h3 class="mt-5">User Management</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>admin</td>
                    <td>Administrator</td>
                    <td>
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>johndoe</td>
                    <td>User</td>
                    <td>
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Add New User Form -->
        <h3>Add New User</h3>
        <form>
            <input type="text" class="form-control" placeholder="Username" required>
            <input type="password" class="form-control" placeholder="Password" required>
            <select class="form-control" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <button type="submit" class="btn btn-primary">Add User</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
