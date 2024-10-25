<?php
include 'AdminNavBar.php'; 
session_start();
include '../db.php';

// Function to validate the password strength
function validatePassword($password) {
    // Password must contain at least:
    // - 1 uppercase letter
    // - 1 lowercase letter
    // - 1 number
    // - 1 special character
    // - 6 characters minimum
    $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/";
    
    if (preg_match($pattern, $password)) {
        return true;
    } else {
        return false;
    }
}

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
    // Update user data
    $name = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['password'];
    $role = $_POST['role'];  // Capture the selected role
    $usertypeid = ($role === 'admin') ? 1 : 0;

    // Password validation (assuming validatePassword() is defined)
    if (!empty($new_password) && !validatePassword($new_password)) {
        $password_error = "Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 6 characters long.";
    } else {
        // Check if the email is changed and already exists
        if ($email !== $user['email']) {
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $email_error = "This email is already registered!";
            }
        }

        // Proceed if no email conflict
        if (empty($email_error)) {
            // Hash the new password if provided
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET name = ?, email = ?, password = ?, usertype_id = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssii", $name, $email, $hashed_password, $usertypeid, $user_id);
            } else {
                // If no new password is provided, don't update the password field
                $sql = "UPDATE users SET name = ?, email = ?, usertype_id = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssii", $name, $email, $usertypeid, $user_id);
            }

            if ($stmt->execute()) {
                // Redirect back to user management page after successful update
                header("Location: ../Admin/Admin.php");
                exit();
            } else {
                $error_message = "Error updating user!";
            }
        }
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
    <link rel="stylesheet" href="your-css-file.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <?php if (!empty($password_error)): ?>
            <div class="alert alert-danger"><?php echo $password_error; ?></div>
        <?php endif; ?>
        <?php if (!empty($email_error)): ?>
            <div class="alert alert-danger"><?php echo $email_error; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
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