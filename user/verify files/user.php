<?php
class User {
    private $db;

    // Constructor to establish database connection
    public function __construct($db) {
        $this->db = $db;
    }

    // Method to check if username exists
    public function usernameExists($username) {
        $query = "SELECT id FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->rowCount() > 0; // Returns true if username exists
    }

    // Method to register a new user
    public function register($username, $password, $role = 'user') {
        if ($this->usernameExists($username)) {
            return false; // Username already exists
        }

        // Hashing the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);

        return $stmt->execute(); // Returns true if registration is successful
    }

    // Method to authenticate user
    public function authenticate($username, $password) {
        $query = "SELECT password FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return password_verify($password, $row['password']); // Returns true if password matches
        }

        return false; // Username not found
    }

    // Method to get user role
    public function getUserRole($username) {
        $query = "SELECT role FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['role'];
        }

        return null; // User not found
    }

    // Method to get user information by username
    public function getUserInfo($username) {
        $query = "SELECT id, username, role FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Returns user information
    }
}
?>
