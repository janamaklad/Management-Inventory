<?php
// Step 1: Connect to the database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Retrieve the binary data (BLOB)
$productId = 1; // Example product ID
$sql = "SELECT Picture FROM products WHERE id = $productId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Step 3: Fetch the binary data (BLOB)
    $row = $result->fetch_assoc();
    $imageData = $row['Picture'];

    // Step 4: Set the header to display the image in the browser
    header("Content-Type: image/jpeg"); // Change the MIME type based on the image type (e.g., "image/png", "image/gif")

    // Step 5: Output the image data
    echo $imageData;
} else {
    echo "No image found!";
}

$conn->close();
?>
