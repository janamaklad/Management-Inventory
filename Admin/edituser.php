<?php
include 'AdminNavBar.php'; 
session_start();
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

    // Validate email uniqueness
    $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $email_err = "This email is already registered with another user.";
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
