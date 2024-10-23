<?php
include 'AdminNavBar.php'; 
session_start();
include '../db.php';

// Fetch user data based on user ID
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT id, name, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user data
    $name = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['password'];

    // Prepare the update statement
    if (!empty($new_password)) {
        // Hash the new password if provided
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $hashed_password, $user_id);
    } else {
        // If no new password is provided, do not update the password field
        $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $email, $user_id);
    }

    if ($stmt->execute()) {
        // Redirect back to user management page
        header("Location: ../Admin/Admin.php");
        exit();
    } else {
        echo "Error updating user!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="edit.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?id=" . $user['id']; ?>">
            <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <input type="password" class="form-control" name="password" placeholder="New Password (leave blank to keep current)">
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

