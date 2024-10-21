<?php
include '../db.php';
include 'AdminNavBar.php';
// Handle Create/Update Supplier
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
            // If a new supplier is added, redirect with a 'new' status
            $new_supplier_id = $conn->insert_id; // Gets the last inserted ID
            header("Location: suppliers.php?status=created");
        } else {
            // Redirect with a success status when updated
            header("Location: suppliers.php?status=updated");
        }
        exit(); // Ensure the script stops after redirection
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
        // Redirect with a delete status after successful deletion
        header("Location: suppliers.php?status=deleted");
        exit(); // Ensure the script stops after redirection
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Suppliers Management</h2>
        <!-- Bootstrap Alert Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabel">Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="statusMessage" class="alert alert-success" role="alert">
          <!-- Status message will go here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSupplierModalLabel">Update Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editSupplierForm" method="post" action="suppliers.php">
          <input type="hidden" name="supplier_id" id="supplier_id">
          <div class="mb-3">
            <label for="supplier_name" class="form-label">Supplier Name</label>
            <input type="text" class="form-control" id="supplier_name" name="supplier_name" required>
          </div>
          <div class="mb-3">
            <label for="contact_info" class="form-label">Contact Info</label>
            <input type="text" class="form-control" id="contact_info" name="contact_info" required>
          </div>
          <div class="mb-3">
            <label for="payment_terms" class="form-label">Payment Terms</label>
            <input type="text" class="form-control" id="payment_terms" name="payment_terms" required>
          </div>
          <button type="submit" class="btn btn-primary">Update Supplier</button>
        </form>
      </div>
    </div>
  </div>
</div>

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
 <!-- Updated Edit Button -->
 <a href="#" class="btn btn-primary btn-sm editBtn" 
                           data-bs-toggle="modal" 
                           data-bs-target="#editSupplierModal"
                           data-id="<?php echo $row['id']; ?>"
                           data-name="<?php echo $row['supplier_name']; ?>"
                           data-contact="<?php echo $row['contact_info']; ?>"
                           data-terms="<?php echo $row['payment_terms']; ?>">
                           Edit
                        </a>      
                        
                    <!-- Delete Button with Confirmation -->
                     <!-- Delete Button with Confirmation -->
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
    <button class="btn btn-secondary mt-3" onclick="window.location.href='admin.php'">Back to Admin</button>

</body>

</html>
<script src="script.js"></script>

<?php
// Close connection
$conn->close();
?>
