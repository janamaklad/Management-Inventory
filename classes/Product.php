<?php
class Product {
    private $conn; // Database connection
    private $id; // Product ID
    private $name; // Product Name
    private $quantity; // Stock Quantity

    // Constructor to initialize a product
    public function __construct($conn, $id) {
        $this->conn = $conn;
        $this->id = $id;

        // Fetch the product from the database
        $this->fetchProduct();
    }

    // Method to fetch product details from the database
    private function fetchProduct() {
        $sql = "SELECT ProductName, Quantity FROM products WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->name = $row['ProductName'];
            $this->quantity = $row['Quantity'];
        } else {
            throw new Exception("Product not found.");
        }
    }

    // Get the current stock quantity
    public function getStock() {
        return $this->quantity;
    }

    // Reduce the stock and update the database
    public function reduceStock($quantity) {
        if ($this->quantity >= $quantity) {
            $this->quantity -= $quantity;

            // Update the stock in the database
            $sql = "UPDATE products SET Quantity = ? WHERE ID = ?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare update statement: " . $this->conn->error);
            }

            $stmt->bind_param("ii", $this->quantity, $this->id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to update stock: " . $stmt->error);
            }

            return true;
        } else {
            throw new Exception("Not enough stock available.");
        }
    }

     // Method to increase sales quantity
     public function increaseSales($quantity) {
        // Update the total sales in the database (you need to have a Sales column in the products table)
        $sql = "UPDATE products SET Sales = Sales + ? WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Failed to prepare sales update statement: " . $this->conn->error);
        }

        $stmt->bind_param("ii", $quantity, $this->id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update sales: " . $stmt->error);
        }

        return true;
    }
    // Get the product name
    public function getName() {
        return $this->name;
    }

    // Optional: Get product details as an associative array
    public function getProductDetails() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $this->quantity
        ];
    }
}
?>
