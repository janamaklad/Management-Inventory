<?php

include '../db.php';
// Function to check password strength
function validatePassword($password) {
    return preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{6,}$/", $password);
}

// Initialize error messages
$error_message = "";
$username_error = "";
$email_error = "";
$password_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate password strength
    if (!validatePassword($password)) {
        $password_error = "Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 6 characters long.";
    } else {
        // Check if the email already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $email_error = "This email is already registered!";
        } else {
            // Hash the password before saving
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                // Redirect to the user management page after adding the user
                header("Location: ../Admin/Admin.php");
                exit();
            } else {
                $error_message = "Error adding user!";
            }
        }
    }
}

// Fetch all users from the database
$sql = "SELECT id, name, email FROM users"; // You don't need to fetch passwords here
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Add New User</h2>
        <form method="post" >
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                <?php if ($username_error): ?>
                    <div class="text-danger"><?php echo $username_error; ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                <?php if ($email_error): ?>
                    <div class="text-danger"><?php echo $email_error; ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="New Password" required>
                <?php if ($password_error): ?>
                    <div class="text-danger"><?php echo $password_error; ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Add User</button>
            <?php if ($error_message): ?>
                <div class="text-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
