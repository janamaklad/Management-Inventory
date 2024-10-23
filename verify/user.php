<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Check if the username (Name) exists
    public function usernameExists($name) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE Name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Register a new user
    public function register($name, $email, $password, $usertype_id) {
        if ($this->usernameExists($name)) {
            return "Username already exists!";
        }

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (Name, Email, Password, usertype_id) VALUES (:name, :email, :password, :usertype_id)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':usertype_id', $usertype_id);

        if ($stmt->execute()) {
            return "User registered successfully!";
        } else {
            return "Registration failed!";
        }
    }

    // Login a user
    public function login($name, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE Name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {
            return "Login successful!";
        } else {
            return "Invalid username or password.";
        }
    }
}
?>
