<?php
include '../db.php'; 
include 'AdminNavbar.php';

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
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Capture the selected role

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
            
          // Set UserType-id based on the selected role
$usertype_id = ($role === 'admin') ? 1 : 0; // Set admin = 1, user = 0

// Insert the new user with UserType-id
$sql = "INSERT INTO users (name, email, password, usertype_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $username, $email, $hashed_password, $usertype_id);

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

include 'adduser.html';
?>



