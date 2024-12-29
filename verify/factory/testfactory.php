<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
$dbPath = __DIR__ . '/../../db.php';
if (file_exists($dbPath)) {
    require_once $dbPath;
} else {
    die("Database configuration file not found. Please check the file path.");
}

require_once 'factory.php';
require_once 'baseuser.php';

// Redirect to login if session ID is not set
if (empty($_SESSION['id'])) {
    header("Location: ./testfactory.php?error=access_denied");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="testfactory.css"> 
</head>
<body>
<div class="container">
    <h2>User Management</h2>
    
    <?php
    // Check the current action
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($action !== 'create_user') {
        // Display "Create User" button if no action is set
        echo "<h3>Choose an option:</h3>";
        echo "<a href='?action=create_user' class='btn'>1. Create User</a>";
        echo "<a href='/Management-Inventory/Admin/Admin.php' class='btn'>Back</a>";
    }

    if ($action === 'create_user') {
        // Display the form for creating a user
        echo "<form method='POST' action='?action=create_user'>
                <label class='form-label'>Name:</label>
                <input type='text' name='name' placeholder='Name' class='form-control' required><br>
                <label class='form-label'>Email:</label>
                <input type='email' name='email' placeholder='Email' class='form-control' required><br>
                <label class='form-label'>Role:</label>
                <select name='role' class='form-control'>
                    <option value='Admin'>Admin</option>
                    <option value='Supplier'>Supplier</option>
                </select><br>
                <button type='submit' name='create_user' class='btn'>Create User</button>
              </form>";

        // Display "Back" button
        echo "<a href='/Management-Inventory/Admin/Admin.php' class='btn'>Back</a>";
    }

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['create_user'])) {
            $user = Factory::create("User", [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'role' => $_POST['role'],
            ]);
            $user->saveToDatabase($conn);
            echo "<p>User created successfully!</p>";
        }
    }
    ?>
</div>
</body>
</html>
