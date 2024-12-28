<?php
session_start();
include 'db.php';
// Initialize the error message variable
$error_message = "";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set usertype_id based on whether the user is logged in or not
$usertype_id = isset($_SESSION['id']) ? $_SESSION['usertypeid'] : 0; // 0 for guest, otherwise based on session

$sql = "SELECT button_name, pagelink FROM navbar_buttons WHERE usertype_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usertype_id);
$stmt->execute();
$result = $stmt->get_result();

$buttons = [];
while ($row = $result->fetch_assoc()) {
    $buttons[] = $row;
}

// Check if the error query parameter exists and set the error message
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshMart Inventory</title>
    <link rel="stylesheet" href="Design.css"> 
    <link rel="stylesheet" href="navbar.css">
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <?php 
                // Render buttons for guests or logged-in users
                if (isset($_SESSION['id'])) { // If logged in
                    foreach ($buttons as $button) {
                        $button_name = htmlspecialchars($button['button_name']);
                        $pagelink = htmlspecialchars($button['pagelink']);

                        // Show all buttons except login and signup for logged-in users
                        if ($button_name !== "Login" && $button_name !== "Sign Up"&&$button_name !== "Contact"&&$button_name !== "About US"&&$button_name !== "Home") {
                            echo "<li><a href='$pagelink'>$button_name</a></li>";
                        }
                    }
                    // Show logout button
                    
                } else { // If guest
                    foreach ($buttons as $button) {
                        $button_name = htmlspecialchars($button['button_name']);
                        $pagelink = htmlspecialchars($button['pagelink']);

                        // Show login and signup for guests only
                        if ($button_name === "Login" || $button_name === "Sign Up") {
                            echo "<li><a href='$pagelink'>$button_name</a></li>";
                        }
                    }
                }

                // Always show "Contact" and "About Us" buttons
               
                ?>
            </ul>
        </nav>
    </header>

    <main>
        <div class="content">
            <?php 
            // Display the error message if it exists
            if (!empty($error_message)) {
                echo "<p style='color: red; font-weight: bold;'>$error_message</p>";
            }

            // Welcome message for logged-in users
            if (!empty($_SESSION['id'])) {
                echo "<h3>Welcome " . htmlspecialchars($_SESSION['name']) . "</h3>";
            }
            ?>
            <h1>FreshMart Inventory</h1>
            <p>Welcome to FreshMart – Freshness delivered daily!</p>
            <a href="user/products.php" class="discover-btn">Discover more</a>
        </div>
    </main>
</body>
</html>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>
