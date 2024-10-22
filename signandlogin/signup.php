<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $email = $password = $confirm_password = "";

// Only process the form if it has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $has_error = false; // Flag for error checking

    // Validate name
    if (empty(trim($_POST["name"]))) {
        $_SESSION['name_err'] = "Please enter your name.";
        $has_error = true;
    } else {
        $name = ucfirst(trim($_POST["name"]));
        unset($_SESSION['name_err']);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $_SESSION['email_err'] = "Please enter your email.";
        $has_error = true;
    } else {
        $email = trim($_POST["email"]);
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $_SESSION['email_err'] = "This email is already in use.";
                $has_error = true;
            } else {
                unset($_SESSION['email_err']);
            }
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $_SESSION['password_err'] = "Please enter a password.";
        $has_error = true;
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $_SESSION['password_err'] = "Password must have at least 6 characters.";
        $has_error = true;
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/", $_POST["password"])) {
        $_SESSION['password_err'] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
        $has_error = true;
    } else {
        $password = trim($_POST["password"]);
        unset($_SESSION['password_err']);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $_SESSION['confirm_password_err'] = "Please confirm your password.";
        $has_error = true;
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $_SESSION['confirm_password_err'] = "Passwords did not match.";
            $has_error = true;
        } else {
            unset($_SESSION['confirm_password_err']);
        }
    }

    // If no errors, proceed to insert user
    if (!$has_error) {
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $param_name, $param_email, $param_password);
            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            if ($stmt->execute()) {
                session_unset(); // Clear session data
                session_destroy(); // Destroy the session
                header("location: ../user/user.html");
                exit();
            }
            $stmt->close();
        }
    }
}

// Clear error messages on the first load
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    unset($_SESSION['name_err']);
    unset($_SESSION['email_err']);
    unset($_SESSION['password_err']);
    unset($_SESSION['confirm_password_err']);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="Signupp.css">
</head>
<body>
    <div class="header">
        <img src="\Management-Inventory\images\logo.png" alt="Logo" class="logo"> <!-- Update path to your logo image -->
        <button class="back-home" onclick="window.location.href='../Homepage.php'">Back to Home</button>
    </div>

    <div class="container">
        <h1>Sign Up</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <span class="error"><?php echo $_SESSION['name_err'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $_SESSION['email_err'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="password">
                <span class="toggle-btn" onclick="togglePassword('password')">Show</span>
                <span class="error"><?php echo $_SESSION['password_err'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password">
                <span class="toggle-btn" onclick="togglePassword('confirm_password')">Show</span>
                <span class="error"><?php echo $_SESSION['confirm_password_err'] ?? ''; ?></span>
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
