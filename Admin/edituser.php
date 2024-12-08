<?php
include 'AdminNavBar.php'; 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../db.php';

$email_err = $password_err = $general_err = "";

// Fetch user data based on user ID
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT id, name, email, usertype_id FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form inputs
    $name = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['password'];
    $role = $_POST['role'];  // Capture the selected role
    $usertypeid = ($role === 'admin') ? 1 : 0;

    // Prepare the update statement
    if (!empty($new_password)) {
        // Hash the new password if provided
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name = ?, email = ?, password = ?, usertype_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $name, $email, $hashed_password, $usertypeid, $user_id);
    } else {
        // If no new password is provided, do not update the password field
        $sql = "UPDATE users SET name = ?, email = ?, usertype_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $name, $email, $usertypeid, $user_id);
    }

    // Validate password
    if (!empty($new_password)) {
        if (!preg_match('/[A-Z]/', $new_password) || // At least one uppercase letter
            !preg_match('/[0-9]/', $new_password) || // At least one number
            !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password) || // At least one special character
            strlen($new_password) < 8) { // Minimum length of 8
            $password_err = "Password must be at least 8 characters long and include one uppercase letter, one number, and one special character.";
        }
    }

    // If there are no errors, proceed with the update
    if (empty($email_err) && empty($password_err)) {
        if (!empty($new_password)) {
            // Hash the new password if provided
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name = ?, email = ?, password = ?, usertype_id = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssii", $name, $email, $hashed_password, $usertypeid, $user_id);
        } else {
            // If no new password is provided, do not update the password field
            $sql = "UPDATE users SET name = ?, email = ?, usertype_id = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $name, $email, $usertypeid, $user_id);
        }

        if ($stmt->execute()) {
            // Redirect back to user management page
            header("Location: ../Admin/Admin.php");
            exit();
        } else {
            $general_err = "Error updating user!";
        }
    }
}

$conn->close();

include 'edituser.html';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css"> 
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?id=" . $user['id']; ?>">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" placeholder="New Password (leave blank to keep current)">

            <!-- Role Selection Label and Dropdown -->
            <label for="role" class="form-label">Role</label>
            <select class="form-control" name="role" required>
                <option value="admin" <?php echo $user['usertype_id'] == 1 ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo $user['usertype_id'] == 0 ? 'selected' : ''; ?>>User</option>
            </select>

            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
