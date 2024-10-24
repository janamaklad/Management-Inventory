<?php
include '../db.php';
$email = $password = "";
$email_err = $password_err = "";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate email
    if (isset($_POST["email"]) && empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (isset($_POST["password"]) && empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before querying the database
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, name, password, usertype_id FROM users WHERE users.email = ?";
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            $param_email = $email;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if email exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $name, $hashed_password, $usertypeid);
                    if ($stmt->fetch()) {
                        // Use password_verify to check the password against the hashed password
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $name;
                            $_SESSION["usertypeid"] = $usertypeid;

                            // Redirect user based on usertypeid
                            if ($usertypeid == 0) {
                                // Redirect to homepage.php
                                header("Location: ../Homepage.php");
                            } elseif ($usertypeid == 1) {
                                // Redirect to Admin.php
                                header("Location: ../Admin/Admin.php");
                            } elseif ($usertypeid == 2) {
                                // Redirect to Supplier.php
                                header("Location: ../Admin/Suppliers.php");
                            }
                            exit(); // Ensure script stops after redirect
                            
                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    $email_err = "No account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
         
            // Close statement
            $stmt->close();
        }
    }
    // Close connection
    $conn->close();
}
?>

<!-- HTML Login Form -->
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
            <a href="register.php" class="toggle-btn">Sign Up</a>
        </div>
    </div>
</div>
</body>
</html>
