<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
    <title>Navbar Example</title>
    <style>
        /* Navbar styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            padding: 5px 15px; /* Reduced padding around the navbar */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            height: 80px; /* Fixed height */
        }

        .navbar .logo img {
            height: 80px; /* Adjusted logo height */
            margin: 0;
            padding: 0;
        }

        .navbar ul {
            list-style-type: none;
            display: flex;
            gap: 15px;
            margin: 0;
            padding: 0;
        }

        .navbar ul li {
            display: inline;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #333;
            font-size: 15px;
            transition: color 0.3s ease;
        }

        .navbar ul li a:hover {
            color: gray;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
                height: auto;
            }

            .navbar ul {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }

            .navbar ul li {
                width: 100%;
                text-align: left;
            }

            .navbar ul li a {
                padding: 10px;
                display: block;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <!-- Logo section -->
        <div class="logo">
            <a href="/Management-Inventory/Homepage.php"> <!-- Link to homepage -->
                <img src="/Management-Inventory/images/logo.png" alt="FreshMart Logo">
            </a>
        </div>
        
        <!-- Links section -->
        <ul>
            
            <?php 
            // Render buttons dynamically from the database
            foreach ($buttons as $button) {
                $button_name = htmlspecialchars($button['button_name']);
                $pagelink = htmlspecialchars($button['pagelink']);

                // Show all buttons for logged-in users except Login and Sign Up
                if (!empty($_SESSION['id'])) {
                    if ($button_name !== "Login" && $button_name !== "Sign Up"&&$button_name !== "Contact"&&$button_name !== "About US") {
                        echo "<li><a href='$pagelink'>$button_name</a></li>";
                    }
                } else {
                    // Show Login and Sign Up for guests only
                    if ($button_name === "Login" || $button_name === "Sign Up") {
                        echo "<li><a href='$pagelink'>$button_name</a></li>";
                    }
                }
            }

            
            ?>
        </ul>
    </nav>

</body>
</html>

<?php
// Close the database connection
$stmt->close();
//$conn->close();
?>
