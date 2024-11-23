<?php
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Register.css">
    <title>Signup</title>
</head>
<body>
<div class="header">
    <img src="\Management-Inventory\images\logo.png" alt="Logo" class="logo">
    <button class="back-home" onclick="window.location.href='../Homepage.php'">Back to Home</button>
</div>
<div class="container">
    <h1>Sign Up</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <span class="error"><?php echo $name_err; ?></span>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <span class="error"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="password">
            <span class="toggle-btn" onclick="togglePassword('password')">Show</span>
            <span class="error"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password">
            <span class="toggle-btn" onclick="togglePassword('confirm_password')">Show</span>
            <span class="error"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <button type="submit">Sign Up</button>
        </div>
    </form>
    <div class="toggle-container">
        <span>Already have an account?</span>
        <a href="login.php" class="toggle-btn">Login</a>
    </div>
</div>
<script>
    function togglePassword(id) {
        var input = document.getElementById(id);
        input.type = (input.type === "password") ? "text" : "password";
    }
</script>
</body>
</html>
