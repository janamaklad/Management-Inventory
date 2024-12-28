<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../db.php';
include '../verify/User.php'; 

$email_err = $password_err = $general_err = "";

// Determine the user ID to edit
if (isset($_GET['id']) && $_SESSION['usertypeid'] == 1) {
    // Admin editing another user
    $user_id = intval($_GET['id']);
} elseif (isset($_SESSION['id'])) {
    // Logged-in user editing their own profile
    $user_id = $_SESSION['id'];
} else {
    // Redirect to login if no valid session or user ID
    header("Location: ../login.php?error=Unauthorized access");
    exit();
}

// Include navigation bar based on user type
if ($_SESSION['usertypeid'] == 1) {
    include 'AdminNavBar.php'; // Admin navigation bar
} else {
    // Load dynamic navbar buttons for regular users
    include '../navbar.php';
}

$sql = "SELECT id, name, email, usertype_id FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    // If no user is found, redirect with an error
    header("Location: ../Admin/Admin.php?error=User not found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form inputs
    $name = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : $user['usertype_id'];

    // Create User object
    $userObj = new User($conn);

    // Call edit method
    $result = $userObj->edit($user_id, $name, $email, $new_password, $role);

    // Redirect or display error
    if ($result === true) {
        if ($_SESSION['usertypeid'] == 1) {
            // Admin redirection
            header("Location: ../Admin/Admin.php?success=User updated successfully");
        } else {
            // User redirection
            header("Location: ../Homepage.php?success=Profile updated successfully");
        }
        exit();
    } elseif (is_string($result)) {
        // Display error message
        $general_err = $result;
    }
}

$conn->close();
include "edituser.html";
?>