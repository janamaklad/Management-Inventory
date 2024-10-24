<?php
$host = 'localhost'; // XAMPP uses localhost for database access
$dbname = 'project'; // Your database name
$username = 'root'; // Default XAMPP MySQL username
$password = ''; // Default password is empty for root in XAMPP

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

