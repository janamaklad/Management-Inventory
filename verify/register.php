<?php
// register.php
include '../db.php';
include 'User.php';

// Function to validate the password
function validatePassword($password) {
    return preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{6,}$/", $password);
}

// Initialize variables to avoid undefined variable warnings
$name = $email = $password = $confirm_password = "";
$name_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = ucfirst(trim($_POST["name"]));
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Validate name
    if (empty($name)) {
        $name_err = "Please enter your name.";
    }

    // Validate email
    if (empty($email)) {
        $email_err = "Please enter your email.";
    }

    // Validate password
    if (empty($password)) {
        $password_err = "Please enter your password.";
    } else if (!validatePassword($password)) {
        $password_err = "Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 6 characters long.";
    }

    // Confirm password
    if (empty($confirm_password)) {
        $confirm_password_err = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $confirm_password_err = "Passwords do not match.";
    }

    // Proceed with registration if no errors
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        $user = new User($conn);
        $result = $user->register($name, $email, $password, $confirm_password);

        if ($result === true) {
            header("Location: ../Homepage.php");
            exit();
        } else {
            $email_err = $result; // Display any registration error (e.g., duplicate email)
        }
    }
}

include 'register.html';
?>
