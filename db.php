<?php
// Database connection
$host = 'localhost'; // Update with your database host
$db = 'project'; // Update with your database name
$user = 'root'; // Update with your database user
$pass = ''; // Update with your database password

// Connect to MySQL database
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
