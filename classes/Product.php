<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Product {
    private $conn;
    private $productId;

    public function __construct($dbConnection, $productId) {
        $this->conn = $dbConnection;
        $this->productId = $productId;
    }

    public function getName() {
        $sql = "SELECT ProductName FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->productId);
        $stmt->execute();
        $stmt->bind_result($productName);
        $stmt->fetch();
        return $productName;
    }

    public function getStock() {
        $sql = "SELECT Quantity FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->productId);
        $stmt->execute();
        $stmt->bind_result($quantity);
        $stmt->fetch();
        return $quantity;
    }

    public function reduceStock($quantity) {
        if ($this->getStock() >= $quantity) {
            $newQuantity = $this->getStock() - $quantity;
            $sql = "UPDATE products SET Quantity = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $newQuantity, $this->productId);
            $stmt->execute();
            return true;
        } else {
            throw new Exception("Not enough stock available.");
        }
    }
}

// Make sure this closing bracket is present
?>
