<?php
class OrderModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createOrder($buyer_id, $provider_id, $order_number, $total_price, $quantity, $service_date, $service_address, $notes) {
        $stmt = $this->pdo->prepare('
            INSERT INTO orders (buyer_id, provider_id, order_number, total_price, quantity, service_date, service_address, status, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, "waiting_payment", ?)
        ');
        $stmt->execute([$buyer_id, $provider_id, $order_number, $total_price, $quantity, $service_date, $service_address, $notes]);
        return $this->pdo->lastInsertId();
    }

    public function createOrderItem($order_id, $service_id, $quantity, $price_per_unit) {
        $stmt = $this->pdo->prepare('
            INSERT INTO order_items (order_id, service_id, quantity, price_per_unit) 
            VALUES (?, ?, ?, ?)
        ');
        return $stmt->execute([$order_id, $service_id, $quantity, $price_per_unit]);
    }

    public function getByBuyer($buyer_id) {
        $stmt = $this->pdo->prepare('
            SELECT o.*, u.name as provider_name 
            FROM orders o 
            JOIN users u ON o.provider_id = u.id 
            WHERE o.buyer_id = ? 
            ORDER BY o.created_at DESC
        ');
        $stmt->execute([$buyer_id]);
        return $stmt->fetchAll();
    }

    public function getByProvider($provider_id) {
        $stmt = $this->pdo->prepare('
            SELECT o.*, u.name as buyer_name 
            FROM orders o 
            JOIN users u ON o.buyer_id = u.id 
            WHERE o.provider_id = ? 
            ORDER BY o.created_at DESC
        ');
        $stmt->execute([$provider_id]);
        return $stmt->fetchAll();
    }

    public function getAll() {
        $stmt = $this->pdo->query('
            SELECT o.*, ub.name as buyer_name, up.name as provider_name 
            FROM orders o 
            JOIN users ub ON o.buyer_id = ub.id 
            JOIN users up ON o.provider_id = up.id 
            ORDER BY o.created_at DESC
        ');
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare('
            SELECT o.*, ub.name as buyer_name, up.name as provider_name 
            FROM orders o 
            JOIN users ub ON o.buyer_id = ub.id 
            JOIN users up ON o.provider_id = up.id 
            WHERE o.id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getOrderItems($order_id) {
        $stmt = $this->pdo->prepare('
            SELECT oi.*, s.title, s.image 
            FROM order_items oi 
            JOIN services s ON oi.service_id = s.id 
            WHERE oi.order_id = ?
        ');
        $stmt->execute([$order_id]);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }
}
