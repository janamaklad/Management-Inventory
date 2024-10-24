<?php
class Order {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createOrder($product_id, $supplier_id, $quantity) {
        $this->conn->begin_transaction();
        try {
            $product = new Product($this->conn, $product_id);

            if ($product->getStock() < $quantity) {
                throw new Exception("Not enough stock available.");
            }

            // Insert order
            $sql = "INSERT INTO orders (product_id, supplier_id, quantity, order_date, status) VALUES (?, ?, ?, NOW(), 'Pending')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iii", $product_id, $supplier_id, $quantity);
            $stmt->execute();

            // Reduce stock after order creation
            $product->reduceStock($quantity);

            $this->conn->commit();
            return "Order created successfully!";
        } catch (Exception $e) {
            $this->conn->rollback();
            throw new Exception("Failed to create order: " . $e->getMessage());
        }
    }

    public function fetchOrders() {
        $sql = "SELECT orders.id, products.ProductName, suppliers.supplier_name, orders.quantity, orders.order_date, orders.status
                FROM orders
                JOIN products ON orders.product_id = products.id
                JOIN suppliers ON orders.supplier_id = suppliers.id";
        return $this->conn->query($sql);
    }

    public function deleteOrder($order_id) {
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        return $stmt->execute();
    }

    public function updateOrder($order_id, $quantity, $status) {
        $sql = "UPDATE orders SET quantity = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isi", $quantity, $status, $order_id);
        return $stmt->execute();
    }
}
?>
