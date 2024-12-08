<?php
include '../db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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

