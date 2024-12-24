<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch navbar items for usertype_id = 1
$sql = "SELECT button_name, pagelink FROM navbar_buttons WHERE usertype_id = 1";
$navbarButtons = $conn->query($sql);
?>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="\Management-Inventory\Admin\Admin.php">FreshMart Inventory</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php
                if ($navbarButtons->num_rows > 0) {
                    while ($button = $navbarButtons->fetch_assoc()) {
                        echo "<li class='nav-item'>
                                <a class='nav-link' href='" . htmlspecialchars($button['pagelink']) . "'>" . htmlspecialchars($button['button_name']) . "</a>
                              </li>";
                    }
                } else {
                    echo "<li class='nav-item'>
                            <a class='nav-link disabled' href='#'>No Menu Items</a>
                          </li>";
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
