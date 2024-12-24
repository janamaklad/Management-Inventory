<?php
include 'db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
 
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (empty($_SESSION['id']) ) {
    header("Location: ./managenavbar.php?error=access_denied");
    exit();
}

// Fetch navbar buttons
$sql = "SELECT * FROM navbar_buttons";
$navbarButtons = $conn->query($sql);

// Add a new button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_button'])) {
    $buttonName = $_POST['button_name'];
    $pageLink = $_POST['page_link'];
    $usertypeId = $_POST['usertype_id']; // Get the usertype_id (0 for User)

    $stmt = $conn->prepare("INSERT INTO navbar_buttons (button_name, pagelink, usertype_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $buttonName, $pageLink, $usertypeId);
    $stmt->execute();
    $stmt->close();

    header("Location: manageNavbar.php");
    exit();
}

// Delete a button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_button'])) {
    $buttonId = $_POST['button_id'];

    $stmt = $conn->prepare("DELETE FROM navbar_buttons WHERE id = ?");
    $stmt->bind_param("i", $buttonId);
    $stmt->execute();
    $stmt->close();

    header("Location: manageNavbar.php");
    exit();
}

// Update a button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_button'])) {
    $buttonId = $_POST['button_id'];
    $buttonName = $_POST['edit_button_name'];
    $pageLink = $_POST['edit_page_link'];
    $usertypeId = $_POST['edit_usertype_id'];

    $stmt = $conn->prepare("UPDATE navbar_buttons SET button_name = ?, pagelink = ?, usertype_id = ? WHERE id = ?");
    $stmt->bind_param("ssii", $buttonName, $pageLink, $usertypeId, $buttonId);
    $stmt->execute();
    $stmt->close();

    header("Location: manageNavbar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Navbar Buttons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <a href="Admin/Admin.php" class="btn btn-info btn-sm mb-4">Back</a>
        <h2>Manage Navbar Buttons</h2>

        <!-- Add New Button Form -->
        <form method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="button_name" class="form-control" placeholder="Button Name" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="page_link" class="form-control" placeholder="Page Link" required>
                </div>
                <div class="col-md-2">
                    <select name="usertype_id" class="form-select" required>
                        <option value="1">Admin</option>
                        <option value="2">Supplier</option>
                        <option value="0">User</option> <!-- User option with usertype_id=0 -->
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_button" class="btn btn-success w-100">Add Button</button>
                </div>
            </div>
        </form>

        <!-- Display Navbar Buttons -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Button Name</th>
                    <th>Page Link</th>
                    <th>User Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($navbarButtons->num_rows > 0): ?>
                    <?php while ($button = $navbarButtons->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($button['ID']) ?></td>
                            <td><?= htmlspecialchars($button['button_name']) ?></td>
                            <td><?= htmlspecialchars($button['pagelink']) ?></td>
                            <td><?= $button['usertype_id'] == 1 ? 'Admin' : ($button['usertype_id'] == 2 ? 'Supplier' : 'User') ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $button['ID'] ?>">Edit</button>
                                <form method="POST" style="display: inline-block;">
                                    <input type="hidden" name="button_id" value="<?= $button['ID'] ?>">
                                    <button type="submit" name="delete_button" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $button['ID'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $button['ID'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?= $button['ID'] ?>">Edit Button</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="button_id" value="<?= $button['ID'] ?>">
                                            <div class="mb-3">
                                                <label for="edit_button_name" class="form-label">Button Name</label>
                                                <input type="text" name="edit_button_name" class="form-control" value="<?= htmlspecialchars($button['button_name']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_page_link" class="form-label">Page Link</label>
                                                <input type="text" name="edit_page_link" class="form-control" value="<?= htmlspecialchars($button['pagelink']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_usertype_id" class="form-label">User Type</label>
                                                <select name="edit_usertype_id" class="form-select" required>
                                                    <option value="1" <?= $button['usertype_id'] == 1 ? 'selected' : '' ?>>Admin</option>
                                                    <option value="2" <?= $button['usertype_id'] == 2 ? 'selected' : '' ?>>Supplier</option>
                                                    <option value="0" <?= $button['usertype_id'] == 0 ? 'selected' : '' ?>>User</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="edit_button" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No navbar buttons found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php $conn->close(); ?>
</body>
</html>
