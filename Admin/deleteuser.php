<?php
$servername = "localhost"; 
$username = "root";
$password = ""; 
$dbname = "newmanagment"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];

    // Delete user from the database
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        header("Location: ../Admin/Admin.php"); // Redirect to the main page
    } else {
        echo "Error deleting user!";
    }
}
$conn->close();
?>

