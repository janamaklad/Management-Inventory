<?php
// Include database connection and User class
include '../db.php';
include 'User.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize error variables
$email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim input values
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST["password"]);

    // Validate email
    if (empty($email)) {
        $email_err = "Please enter your email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    }

    // Validate password
    if (empty($password)) {
        $password_err = "Please enter your password.";
    }

    // If no validation errors, proceed to login
    if (empty($email_err) && empty($password_err)) {
        $user = new User($conn);
        $error = $user->login($email, $password);

        if ($error) {
            $password_err = $error; // Display error returned from login function
        }
    }
}
include 'login.html';
?>