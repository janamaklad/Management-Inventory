<?php
// submission, validation, database querying
//user=0,admin=1
include '../db.php';
include 'User.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="Register.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<div class="header">
    <img src="\Management-Inventory\images\logo.png" alt="Logo" class="logo"> <!-- Update path to your logo image -->
    <button class="back-home" onclick="window.location.href='../Homepage.php'">Back to Home</button>
</div>
<div class="container">
    <div class="form-container">

        <h1>Login</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" value="">
                <span class="error"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" value="">
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
        <div class="toggle-container">
            <span>Don't have an account?</span>
            <a href="\Management-Inventory\verify\register.php" class="toggle-btn">Sign Up</a>
        </div>
    </div>
</div>
</body>
</html>


?>
