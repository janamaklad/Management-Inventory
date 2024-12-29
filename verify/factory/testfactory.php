<?php
require_once '../../db.php'; // Database connection
require_once 'factory.php';
require_once 'baseuser.php';


// Display menu
echo "<h3>Choose an option:</h3>";
echo "<a href='?action=create_user'>1. Create User</a><br>";


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action === 'create_user') {
        echo "<form method='POST' action='?action=create_user'>
                <input type='text' name='name' placeholder='Name' required><br>
                <input type='email' name='email' placeholder='Email' required><br>
                <select name='role'>
                    <option value='Admin'>Admin</option>
                    <option value='Supplier'>Supplier</option>
                </select><br>
                <button type='submit' name='create_user'>Create User</button>
              </form>";
    
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
        echo "User created successfully!";
    } }
}
?>
