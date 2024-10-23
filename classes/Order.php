<?php
class Order {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createOrder($product_id, $supplier_id, $quantity) {
        // Start transaction
        $this->conn->begin_transaction();

        try {
            // Insert order into orders table
            $sql = "INSERT INTO orders (product_id, supplier_id, quantity, order_date, status) VALUES (?, ?, ?, NOW(), 'Pending')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iii", $product_id, $supplier_id, $quantity);
            $stmt->execute();
            $stmt->close();

            // Reduce stock and calculate sales in Product class
            $product = new Product($this->conn, $product_id);
            $product->reduceStock($quantity); // Reduce stock
            $product->increaseSales($quantity); // Increase sales

            // Commit transaction
            $this->conn->commit();
        } catch (Exception $e) {
            // Rollback if an error occurs
            $this->conn->rollback();
            throw new Exception("Failed to create order: " . $e->getMessage());
        }
    }

    public function fetchOrders() {
        $sql = "SELECT o.id, p.ProductName, s.supplier_name, o.quantity, o.order_date, o.status 
                FROM orders o 
                JOIN products p ON o.product_id = p.id 
                JOIN suppliers s ON o.supplier_id = s.id";
        return $this->conn->query($sql);
    }

    public function updateOrder($order_id, $quantity, $status) {
        $sql = "UPDATE orders SET quantity = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isi", $quantity, $status, $order_id);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteOrder($order_id) {
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
