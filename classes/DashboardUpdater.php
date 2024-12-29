<?php
require_once 'Observer.php';

class DashboardUpdater implements Observer {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    public function update(Product $product): void {
        $name = $product->getName();
        $stock = $product->getStock();
        echo "DashboardUpdater: Updating dashboard for {$name}. Stock: {$stock}.\n";
    }
}
?>