<!-- Navbar.php -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="\Management-Inventory\Admin\Admin.php">FreshMart Inventory</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="Admin.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Stock Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="Suppliers.php">Suppliers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="\Management-Inventory\Admin\report.php">Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
