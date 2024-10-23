<?php
include '../db.php';
include 'AdminNavBar.php';

// Handle Create/Update Supplier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_name = $_POST['supplier_name']; // Capturing supplier name
    $contact_info = $_POST['contact_info'];
    $payment_terms = $_POST['payment_terms'];
    
    // Capturing user details
    $name = $_POST['name']; 
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password for security

    // Check if it's an update operation
    if (isset($_POST['supplier_id']) && !empty($_POST['supplier_id'])) {
        $supplier_id = $_POST['supplier_id'];

        // Update user details in the users table
        $stmt_user = $conn->prepare("UPDATE users SET Name=?, Email=?, Password=? WHERE id=(SELECT user_id FROM suppliers WHERE id=?)");
        $stmt_user->bind_param("sssi", $name, $email, $password, $supplier_id);
        $stmt_user->execute();
        $stmt_user->close();

        // Update supplier details
        $stmt_supplier = $conn->prepare("UPDATE suppliers SET supplier_name=?, contact_info=?, payment_terms=? WHERE id=?");
        $stmt_supplier->bind_param("sssi", $supplier_name, $contact_info, $payment_terms, $supplier_id);
        $stmt_supplier->execute();
        $stmt_supplier->close();
        
        header("Location: suppliers.php?status=updated");
        exit(); 
    } else {
        // Create new user
        $stmt_user = $conn->prepare("INSERT INTO users (Name, Email, Password, `Usertype-id`) VALUES (?, ?, ?, ?)");
        $usertype_id = 2; // Assuming '2' represents supplier
        $stmt_user->bind_param("sssi", $name, $email, $password, $usertype_id);
        $stmt_user->execute();

        // Get the last inserted user ID to associate it with the supplier
        $new_user_id = $conn->insert_id;

        // Create new supplier and associate with user
        $stmt_supplier = $conn->prepare("INSERT INTO suppliers (user_id, supplier_name, contact_info, payment_terms) VALUES (?, ?, ?, ?)");
        $stmt_supplier->bind_param("isss", $new_user_id, $supplier_name, $contact_info, $payment_terms);
        $stmt_supplier->execute();
        $stmt_supplier->close();
        
        header("Location: suppliers.php?status=created");
        exit(); 
    }
}

// Handle Delete Supplier (Soft Delete)
if (isset($_GET['delete'])) {
    $supplier_id = $_GET['delete'];

    // Soft Delete by updating `is_deleted` field
    $stmt = $conn->prepare("UPDATE suppliers SET is_deleted = 1 WHERE id=?");
    $stmt->bind_param("i", $supplier_id);

    if ($stmt->execute()) {
        // Redirect with a delete status after successful deletion
        header("Location: suppliers.php?status=deleted");
        exit();
    } else {
        echo "Error updating supplier: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all suppliers (excluding deleted ones)
$sql = "SELECT * FROM suppliers WHERE is_deleted = 0";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers Management</title>
    <link rel="stylesheet" href="Admin.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Suppliers Management</h2>

        <!-- Suppliers Table -->
        <h3>Supplier List</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Supplier Name</th>
                    <th>Contact Info</th>
                    <th>Payment Terms</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['supplier_name']; ?></td>
                            <td><?php echo $row['contact_info']; ?></td>
                            <td><?php echo $row['payment_terms']; ?></td>
                            <td>
                                <!-- Edit Button -->
                                <a href="#" class="btn btn-primary btn-sm editBtn" 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#editSupplierModal"
                                   data-id="<?php echo $row['id']; ?>"
                                   data-name="<?php echo $row['supplier_name']; ?>"
                                   data-contact="<?php echo $row['contact_info']; ?>"
                                   data-terms="<?php echo $row['payment_terms']; ?>">
                                   Edit
                                </a>      
                                
                                <!-- Delete Button -->
                                <a href="suppliers.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No suppliers found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Add/Edit Supplier Form -->
        <h3><?php echo isset($_GET['edit']) ? 'Edit Supplier' : 'Add New Supplier'; ?></h3>
        <form method="post" action="suppliers.php">
            <input type="text" class="form-control" name="name" placeholder="User Name" required>
            <input type="email" class="form-control" name="email" placeholder="Email" required>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <input type="text" class="form-control" name="supplier_name" placeholder="Supplier Name" required>
            <input type="text" class="form-control" name="contact_info" placeholder="Contact Info" required>
            <input type="text" class="form-control" name="payment_terms" placeholder="Payment Terms" required>
            <button type="submit" class="btn btn-primary mt-2">Add Supplier</button>
        </form>
    </div>

    <button class="btn btn-secondary mt-3" onclick="window.location.href='admin.php'">Back to Admin</button>

</body>

</html>

<?php
// Close connection
$conn->close();
?>
