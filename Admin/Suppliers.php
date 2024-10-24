<?php
include '../db.php';
include 'AdminNavBar.php';

// Handle Create/Update Supplier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_name = $_POST['supplier_name']; 
    $contact_info = $_POST['contact_info'];
    $payment_terms = $_POST['payment_terms'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Server-side Email Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format'); window.location.href = 'suppliers.php';</script>";
        exit();
    }

    // Server-side Password Validation
    if (!empty($password) && strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long'); window.location.href = 'suppliers.php';</script>";
        exit();
    }

    // Hash the password only if it's provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    } else {
        $hashed_password = null;
    }

    // Check if it's an update operation
    if (isset($_POST['supplier_id']) && !empty($_POST['supplier_id'])) {
        $supplier_id = $_POST['supplier_id'];

        // Update user details in the users table (update both Name and Email)
        if ($hashed_password) {
            $stmt_user = $conn->prepare("UPDATE users SET Name=?, Email=?, Password=? WHERE id=(SELECT user_id FROM suppliers WHERE id=?)");
            $stmt_user->bind_param("sssi", $supplier_name, $email, $hashed_password, $supplier_id);
        } else {
            $stmt_user = $conn->prepare("UPDATE users SET Name=?, Email=? WHERE id=(SELECT user_id FROM suppliers WHERE id=?)");
            $stmt_user->bind_param("ssi", $supplier_name, $email, $supplier_id);
        }
        $stmt_user->execute();
        $stmt_user->close();

        // Update supplier details in the suppliers table
        $stmt_supplier = $conn->prepare("UPDATE suppliers SET supplier_name=?, contact_info=?, payment_terms=? WHERE id=?");
        $stmt_supplier->bind_param("sssi", $supplier_name, $contact_info, $payment_terms, $supplier_id);
        $stmt_supplier->execute();
        $stmt_supplier->close();
        
        header("Location: suppliers.php?status=updated");
        exit(); 
    } else {
        // Create new user
        $stmt_user = $conn->prepare("INSERT INTO users (Name, Email, Password, `Usertype_id`) VALUES (?, ?, ?, ?)");
        $usertype_id = 2; // Assuming '2' represents supplier
        $stmt_user->bind_param("sssi", $supplier_name, $email, $hashed_password, $usertype_id);
        $stmt_user->execute();

        // Get the last inserted user ID to associate it with the supplier
        $new_user_id = $conn->insert_id;

        // Create new supplier and associate with user
        $stmt_supplier = $conn->prepare("INSERT INTO suppliers (id, supplier_name, contact_info, payment_terms) VALUES (?, ?, ?, ?)");
        $stmt_supplier->bind_param("isss", $new_user_id, $supplier_name, $contact_info, $payment_terms);
        $stmt_supplier->execute();
        $stmt_supplier->close();
        
        header("Location: suppliers.php?status=created");
        exit(); 
    }
}

// Handle Delete Supplier (Permanent Delete)
if (isset($_GET['delete'])) {
  $supplier_id = $_GET['delete'];

  // Fetch the user_id associated with the supplier before deletion
  $stmt = $conn->prepare("SELECT user_id FROM suppliers WHERE id=?");
  $stmt->bind_param("i", $supplier_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $user_id = $row['user_id'];

      // Now delete the supplier
      $stmt_supplier = $conn->prepare("DELETE FROM suppliers WHERE id=?");
      $stmt_supplier->bind_param("i", $supplier_id);
      $stmt_supplier->execute();
      $stmt_supplier->close();

      // Then delete the user
      $stmt_user = $conn->prepare("DELETE FROM users WHERE id=?");
      $stmt_user->bind_param("i", $user_id);
      $stmt_user->execute();
      $stmt_user->close();

      // Redirect with a delete status after successful deletion
      header("Location: suppliers.php?status=deleted");
      exit();
  } else {
      echo "Supplier not found.";
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

<!-- Add Supplier Button -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#editSupplierModal" onclick="prepareAddSupplier()">
    Add New Supplier
</button>

<!-- Edit/Add Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSupplierModalLabel">Add Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add/Edit Supplier Form -->
                <form method="post" action="suppliers.php" onsubmit="return validateForm()">
                    <input type="hidden" id="supplier_id" name="supplier_id"> <!-- Hidden field for supplier ID -->
                    
                    <input type="text" class="form-control mb-2" id="supplier_name" name="supplier_name" placeholder="Name" required>
                    <input type="email" class="form-control mb-2" id="email" name="email" placeholder="Email" required>
                    <input type="password" class="form-control mb-2" id="password" name="password" placeholder="Password" minlength="8"> <!-- Password can be optional for updates -->
                    <input type="text" class="form-control mb-2" id="contact_info" name="contact_info" placeholder="Contact Info" required>
                    <input type="text" class="form-control mb-2" id="payment_terms" name="payment_terms" placeholder="Payment Terms" required>
                    <button type="submit" class="btn btn-primary mt-2">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function validateForm() {
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/; // Basic email format validation
    
    if (!email.match(emailPattern)) {
        alert("Please enter a valid email address");
        return false;
    }

    if (password && password.length < 8) {
        alert("Password must be at least 8 characters long");
        return false;
    }

    return true;
}

document.addEventListener('DOMContentLoaded', function () {
    // Add event listeners for the Edit buttons
    var editButtons = document.querySelectorAll('.editBtn');
    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            // Get the supplier details from data attributes
            var supplierId = this.getAttribute('data-id');
            var supplierName = this.getAttribute('data-name');
            var contactInfo = this.getAttribute('data-contact');
            var paymentTerms = this.getAttribute('data-terms');

            // Set the form values
            document.getElementById('supplier_id').value = supplierId;
            document.getElementById('supplier_name').value = supplierName;
            document.getElementById('contact_info').value = contactInfo;
            document.getElementById('payment_terms').value = paymentTerms;

            // Set modal title for editing
            document.getElementById('editSupplierModalLabel').textContent = "Edit Supplier";

            // Password can be optional in case of updates
            document.getElementById('password').removeAttribute('required');
        });
    });
});

// Prepare modal for adding new supplier
function prepareAddSupplier() {
    // Clear form fields
    document.getElementById('supplier_id').value = "";
    document.getElementById('supplier_name').value = "";
    document.getElementById('email').value = "";
    document.getElementById('password').value = "";
    document.getElementById('contact_info').value = "";
    document.getElementById('payment_terms').value = "";

    // Set modal title for adding
    document.getElementById('editSupplierModalLabel').textContent = "Add Supplier";
    
    // Ensure password field is required
    document.getElementById('password').setAttribute('required', 'required');
}
</script>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
