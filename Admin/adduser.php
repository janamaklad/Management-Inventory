<?php
include '../db.php';
include 'AdminNavbar.php';
include '../verify/User.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['password']); // Add confirm password
    $role = $_POST['role']; // Capture the selected role

    // Field validations
    if (empty($username)) {
        $username_error = "Username is required.";
    }
    if (empty($email)) {
        $email_error = "Email is required.";
    }
    if (empty($password)) {
        $password_error = "Password is required.";
    } elseif (!validatePassword($password)) {
        $password_error = "Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 6 characters long.";
    }

    if (empty($username_error) && empty($email_error) && empty($password_error)) {
        // Initialize the User class
        $user = new User($conn);

        // Set UserType-id based on the selected role
        $usertype_id = ($role === 'admin') ? 1 : 0;

        // Call the register function
        $result = $user->register($username, $email, $password, $confirm_password);

        if ($result === true) {
            // If registration is successful, update usertype_id
            $sql = "UPDATE users SET usertype_id = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("is", $usertype_id, $email);
                $stmt->execute();

                // Redirect to the user management page
                header("Location: ../Admin/Admin.php");
                exit();
            } else {
                $error_message = "Error updating user type: " . $conn->error;
            }
        } else {
            // Handle errors from the register function
            $error_message = $result;
        }
    }
}
if (empty($_SESSION['id']) ) {
    // Redirect to a login page with an error message
    header("Location: ./adduser.php?error=access_denied");
}

// Fetch all users from the database
$sql = "SELECT id, name, email FROM users"; // You don't need to fetch passwords here
$result = $conn->query($sql);
include 'adduser.html';
?>