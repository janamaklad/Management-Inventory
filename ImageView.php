<?php
// Include database connection
include('../db.php'); // Adjust this path based on your directory structure

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the product ID from the query string
$id = intval($_GET['id']); // Sanitize the input

// Prepare the SQL statement to fetch the image
$stmt = $conn->prepare("SELECT Picture FROM products WHERE ID = ?"); // Use ID instead of id
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($imageData);
$stmt->fetch();

// Check if the image exists
if ($stmt->num_rows > 0) {
    header("Content-Type: image/jpeg"); // Set the content type (adjust if needed)
    echo $imageData; // Output the image data
} else {
    echo "Image not found."; // Message for missing image
    // Debugging: Output image data and query information
    echo "No rows returned for ID: " . $id; // Show the ID being queried
}

$stmt->close();
$conn->close();
?>
