<?php
include '../db.php';
include 'AdminNavBar.php';
// Handle Create/Update Supplier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_name = $_POST['supplier_name'];
    $contact_info = $_POST['contact_info'];
    $payment_terms = $_POST['payment_terms'];

    // Check if it's an update operation
    if (isset($_POST['supplier_id']) && !empty($_POST['supplier_id'])) {
        $supplier_id = $_POST['supplier_id'];
        $stmt = $conn->prepare("UPDATE suppliers SET supplier_name=?, contact_info=?, payment_terms=? WHERE id=?");
        $stmt->bind_param("sssi", $supplier_name, $contact_info, $payment_terms, $supplier_id);
    } else {
        // Create new supplier
        $stmt = $conn->prepare("INSERT INTO suppliers (supplier_name, contact_info, payment_terms) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $supplier_name, $contact_info, $payment_terms);
    }

    if ($stmt->execute()) {
        if (empty($_POST['supplier_id'])) {
            $new_supplier_id = $conn->insert_id; // Gets the last inserted ID
            echo "New supplier added successfully with ID: " . $new_supplier_id;
        } else {
            echo "Supplier updated successfully.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Delete Supplier
if (isset($_GET['delete'])) {
    $supplier_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM suppliers WHERE id=?");
    $stmt->bind_param("i", $supplier_id);

    if ($stmt->execute()) {
        echo "Supplier deleted successfully.";
    } else {
        echo "Error deleting supplier: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all suppliers
$sql = "SELECT * FROM suppliers";
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
                                <a href="suppliers.php?edit=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="suppliers.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
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
        <?php
        // If editing, fetch the existing supplier data
        if (isset($_GET['edit'])) {
            $supplier_id = $_GET['edit'];
            $stmt = $conn->prepare("SELECT * FROM suppliers WHERE id=?");
            $stmt->bind_param("i", $supplier_id);
            $stmt->execute();
            $edit_result = $stmt->get_result();
            $supplier = $edit_result->fetch_assoc();
            $stmt->close();
        }
        ?>
        <form method="post" action="suppliers.php">
            <input type="hidden" name="supplier_id" value="<?php echo isset($supplier['id']) ? $supplier['id'] : ''; ?>">
            <input type="text" class="form-control" name="supplier_name" placeholder="Supplier Name" value="<?php echo isset($supplier['supplier_name']) ? $supplier['supplier_name'] : ''; ?>" required>
            <input type="text" class="form-control" name="contact_info" placeholder="Contact Info" value="<?php echo isset($supplier['contact_info']) ? $supplier['contact_info'] : ''; ?>" required>
            <input type="text" class="form-control" name="payment_terms" placeholder="Payment Terms" value="<?php echo isset($supplier['payment_terms']) ? $supplier['payment_terms'] : ''; ?>" required>
            <button type="submit" class="btn btn-primary mt-2"><?php echo isset($_GET['edit']) ? 'Update Supplier' : 'Add Supplier'; ?></button>
        </form>
    </div>
</body>

</html>

<?php
// Close connection
$conn->close();
?>
