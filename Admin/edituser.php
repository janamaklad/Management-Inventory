<?php
include 'AdminNavBar.php'; 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../db.php';
include '../verify/User.php'; 

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

    // Create User object
    $userObj = new User($conn);

    // Call edit method
    $result = $userObj->edit($user_id, $name, $email, $new_password, $role);

    if ($result === true) {
        // Redirect back to user management page
        header("Location: ../Admin/Admin.php");
        exit();
    } elseif (is_string($result)) {
        // Display error message
        $general_err = $result;
    }
}

$conn->close();

include 'edituser.html';
?>
