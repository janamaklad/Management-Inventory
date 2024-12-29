<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if there is a session message
if (isset($_SESSION['message'])) {
    // Get the message and type
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];

    // Set the message color based on the type
    $color = ($message_type == 'success') ? 'green' : 'red';

    // Display the message
    echo "<p style='color: $color; font-weight: bold; text-align: center;'>$message</p>";

    // Clear the session message after displaying it
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Define the root directory if not already defined
if (!defined('_ROOT_')) {
    define('_ROOT_', 'C:/xampp/htdocs/Management-Inventory');
}

// Include the ContactModel file
$contactModelPath = _ROOT_ . '/app/model/ContactModel.php';
if (!file_exists($contactModelPath)) {
    die("Error: ContactModel file not found at $contactModelPath");
}

require_once($contactModelPath);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate the form inputs
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : null;
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : null;
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : null;
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : null;

    // Check if all required fields are filled
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        // Set session message for error
        $_SESSION['message'] = 'All fields are required.';
        $_SESSION['message_type'] = 'error';
        // Redirect to the homepage
        header('Location: /Management-Inventory/Homepage.php');
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set session message for error
        $_SESSION['message'] = 'Invalid email format.';
        $_SESSION['message_type'] = 'error';
        // Redirect to the homepage
        header('Location: /Management-Inventory/Homepage.php');
        exit;
    }

    // Instantiate the ContactModel
    try {
        $contactModel = new ContactModel();
        
        // Add the message
        $contactModel->addContact($name, $email, $subject, $message);
        
        // Set session message for success
        $_SESSION['message'] = 'Last Message was submitted successfully.';
        $_SESSION['message_type'] = 'success';
        // Redirect to the homepage
        header('Location: /Management-Inventory/Homepage.php');
        exit;
    } catch (Exception $e) {
        // Set session message for error
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = 'error';
        // Redirect to the homepage
        header('Location: /Management-Inventory/Homepage.php');
        exit;
    }
}
?>

   
   