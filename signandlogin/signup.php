<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "management-inventory1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $email = $password = $confirm_password = "";
$name_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["name"]))) {
        $_SESSION['name_err'] = "Please enter your name.";
    } else {
        $name = ucfirst(trim($_POST["name"]));
        unset($_SESSION['name_err']);
    }

    if (empty(trim($_POST["email"]))) {
        $_SESSION['email_err'] = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $_SESSION['email_err'] = "This email is already in use.";
            } else {
                unset($_SESSION['email_err']);
            }
            $stmt->close();
        }
    }

    if (empty(trim($_POST["password"]))) {
        $_SESSION['password_err'] = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $_SESSION['password_err'] = "Password must have at least 6 characters.";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W]).+$/", $_POST["password"])) {
        $_SESSION['password_err'] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        $password = trim($_POST["password"]);
        unset($_SESSION['password_err']);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $_SESSION['confirm_password_err'] = "Please confirm your password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $_SESSION['confirm_password_err'] = "Passwords did not match.";
        } else {
            unset($_SESSION['confirm_password_err']);
        }
    }

    if (empty($_SESSION['name_err']) && empty($_SESSION['email_err']) && empty($_SESSION['password_err']) && empty($_SESSION['confirm_password_err'])) {
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $param_name, $param_email, $param_password);
            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if ($stmt->execute()) {
                session_unset();
                session_destroy();
                header("location: ../user/user.html");
                exit();
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="signup.css">
    <title>Signup</title>
    <style>
        .toggle-btn {
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }
        span.error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Sign Up</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Name</label>
            <input type="text" name="name" value="">
            <span class="error"><?php echo ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['name_err'])) ? $_SESSION['name_err'] : ''; ?></span>
        </div>    
        <div>
            <label>Email</label>
            <input type="text" name="email" value="">
            <span class="error"><?php echo ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['email_err'])) ? $_SESSION['email_err'] : ''; ?></span>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" id="password" value="">
            <span class="toggle-btn" onclick="togglePassword('password')">Show</span>
            <span class="error"><?php echo ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['password_err'])) ? $_SESSION['password_err'] : ''; ?></span>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" value="">
            <span class="toggle-btn" onclick="togglePassword('confirm_password')">Show</span>
            <span class="error"><?php echo ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['confirm_password_err'])) ? $_SESSION['confirm_password_err'] : ''; ?></span>
        </div>
        <div>
            <input type="submit" value="Submit">
        </div>
    </form>

    <script>
        function togglePassword(id) {
            var input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</body>
</html>
