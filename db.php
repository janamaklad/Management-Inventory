<?php
$servername = "localhost";  // Make sure it's 'localhost' if you're using XAMPP
$username = "root";         // Default XAMPP MySQL user is 'root'
$password = "";             // No password for 'root' by default in XAMPP
$dbname = "project";        // Ensure the database 'project' exists in your MySQL
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
