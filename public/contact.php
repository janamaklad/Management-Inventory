<?php
define('_ROOT_', 'C:/xampp/htdocs/Management-Inventory');

// Include the ViewContact file
require_once(_ROOT_ . '/app/view/ViewContact.php');

$contactView = new ViewContact();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
        // Render the contact form
        echo $contactView->renderContactForm();
    ?>
</body>
</html>
