<?php
interface Observer {
    public function update(Product $product): void;
}
?>
