<?php
require_once 'Observer.php';

class ReorderNotifier implements Observer {
    public function update(Product $product): void {
        $name = $product->getName();
        $stock = $product->getStock();
        echo "ReorderNotifier: Checking stock for {$name}. Current stock: {$stock}.\n";
    
        if ($stock < 10) { // Low stock threshold
            echo "ReorderNotifier: Low stock detected for {$name}. Trigger reorder.\n";
        }
    }
}
?>