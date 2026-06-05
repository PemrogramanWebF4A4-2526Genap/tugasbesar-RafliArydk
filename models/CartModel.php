<?php
class CartModel {
    public function all() {
        return $_SESSION['cart'] ?? [];
    }

    public function add($serviceId, $quantity = 1) {
        $serviceId = (int) $serviceId;
        $quantity = max(1, (int) $quantity);
        $_SESSION['cart'][$serviceId] = ($_SESSION['cart'][$serviceId] ?? 0) + $quantity;
    }

    public function update($serviceId, $quantity) {
        $serviceId = (int) $serviceId;
        $quantity = (int) $quantity;
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$serviceId]);
            return;
        }
        $_SESSION['cart'][$serviceId] = $quantity;
    }

    public function clear() {
        $_SESSION['cart'] = [];
    }
}
