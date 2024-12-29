<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Product {
    private $conn;
    private $productId;
    private $observers = []; // List of registered observers

    public function __construct($dbConnection, $productId=null) {
        $this->conn = $dbConnection;
        $this->productId = $productId;
    }

    // Register an observer
    public function attachObserver($observer) {
        $this->observers[] = $observer;
    }

 

    public function getName() {
        $sql = "SELECT ProductName FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->productId);
        $stmt->execute();
        $stmt->bind_result($productName);
        $stmt->fetch();
        $stmt->close();
        return $productName;
    }

    public function getStock() {
        $sql = "SELECT Quantity FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->productId);
        $stmt->execute();
        $stmt->bind_result($quantity);
        $stmt->fetch();
        $stmt->close();
        return $quantity;
    }
    private function notifyObservers() {
        echo "Product: Notifying observers about changes to {$this->getName()}...\n";
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
    
    public function reduceStock($quantity) {
        $currentStock = $this->getStock();
        if ($currentStock >= $quantity) {
            $newQuantity = $currentStock - $quantity;
    
            // Update stock
            $sql = "UPDATE products SET Quantity = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $newQuantity, $this->productId);
            $stmt->execute();
            $stmt->close();
    
            // Notify observers only if stock has changed
            if ($newQuantity == 0) {
                echo "Product stock is now zero for {$this->getName()}.\n";
            }
    
            $this->notifyObservers(); // Notify observers of the stock change
            return true;
    
        } else {
            throw new Exception("Not enough stock available.");
        }
    }
    
    

public function addProduct($name, $category, $quantity, $sellerName, $imageURL, $price) {
    $sql = "INSERT INTO products (ProductName, Category, Quantity, SellerName, image_url, Price) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ssissd", $name, $category, $quantity, $sellerName, $imageURL, $price);
    return $stmt->execute();
}

public function editProduct($id, $name, $category, $quantity, $sellerName, $imageURL, $price) {
    $sql = "UPDATE products SET ProductName = ?, Category = ?, Quantity = ?, SellerName = ?, image_url = ?, Price = ? WHERE ID = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ssissdi", $name, $category, $quantity, $sellerName, $imageURL, $price, $id);
    return $stmt->execute();
}

public function deleteProduct($id) {
    $checkQuery = $this->conn->prepare("SELECT * FROM orders WHERE product_id = ?");
    $checkQuery->bind_param("i", $id);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    if ($result->num_rows > 0) {
        throw new Exception("Cannot delete this product because it is associated with existing orders.");
    } else {
        $sql = "DELETE FROM products WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

public function getProducts() {
    $sql = "SELECT * FROM products";
    return $this->conn->query($sql);
}
}
?>

