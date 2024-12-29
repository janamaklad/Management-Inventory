<?php
class User {
    private $name;
    private $email;
    private $role;

    public function __construct($name, $email, $role) {
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }

    public function getDetails() {
        return "Name: {$this->name}, Email: {$this->email}, Role: {$this->role}";
    }

    public function saveToDatabase($conn) {
        $stmt = $conn->prepare("INSERT INTO users (name, email, usertype_id) VALUES (?, ?, ?)");
        $usertype_id = ($this->role === "Admin") ? 1 : 2; // Map role to usertype_id
        $stmt->bind_param("ssi", $this->name, $this->email, $usertype_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
