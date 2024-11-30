<?php
// register.php
include '../db.php';
include 'User.php';

session_start();

// Initialize variables to avoid undefined variable warnings
$name = $email = $password = $confirm_password = "";
$name_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = ucfirst(trim($_POST["name"]));
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($name)) {
        $name_err = "Please enter your name.";
    }

    if (empty($email)) {
        $email_err = "Please enter your email.";
    }

    if (empty($password)) {
        $password_err = "Please enter your password.";
    } elseif (strlen($password) < 6) {
        $password_err = "Password must be at least 6 characters.";
    }

    if (empty($confirm_password)) {
        $confirm_password_err = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $confirm_password_err = "Passwords do not match.";
    }

    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        $user = new User($conn);
        $result = $user->register($name, $email, $password, $confirm_password);

        if ($result === true) {
            header("Location: ../Homepage.php");
            exit();
        } else {
            $email_err = $result;
        }
    }
}

// Include the HTML file
include 'register.html';
?>
