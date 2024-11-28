<?php
// login.php
include '../db.php';
include 'User.php';

session_start();

$email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email)) {
        $email_err = "Please enter your email.";
    }

    if (empty($password)) {
        $password_err = "Please enter your password.";
    }

    if (empty($email_err) && empty($password_err)) {
        $user = new User($conn);
        $error = $user->login($email, $password);

        if ($error) {
            $password_err = $error;
        }
    }
}


include 'login.html';
?>
