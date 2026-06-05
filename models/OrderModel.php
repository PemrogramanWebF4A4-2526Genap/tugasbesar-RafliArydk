<?php
class OrderModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createOrder($buyer_id, $provider_id, $order_number, $total_price, $quantity, $service_date, $service_address, $notes, $status = 'waiting_payment') {
        $stmt = $this->pdo->prepare('
            INSERT INTO orders (buyer_id, provider_id, order_number, total_price, quantity, service_date, service_address, status, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$buyer_id, $provider_id, $order_number, $total_price, $quantity, $service_date, $service_address, $status, $notes]);
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
        $stmt = $this->pdo->prepare('UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    public function countThisMonth() {
        $stmt = $this->pdo->query('SELECT COUNT(id) FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())');
        return (int) $stmt->fetchColumn();
    }

    public function getTotalRevenue() {
        $stmt = $this->pdo->query('SELECT COALESCE(SUM(total_price), 0) FROM orders WHERE status IN ("paid","accepted","in_progress","completed")');
        return (float) $stmt->fetchColumn();
    }

    public function getMonthlyRevenue($months = 6) {
        $stmt = $this->pdo->prepare('
            SELECT DATE_FORMAT(created_at, "%Y-%m") as month, COALESCE(SUM(total_price), 0) as revenue, COUNT(id) as order_count
            FROM orders
            WHERE status IN ("paid","accepted","in_progress","completed")
              AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL ? MONTH)
            GROUP BY DATE_FORMAT(created_at, "%Y-%m")
            ORDER BY month ASC
        ');
        $stmt->execute([$months]);
        return $stmt->fetchAll();
    }

    public function getTopServices($limit = 5) {
        $stmt = $this->pdo->prepare('
            SELECT s.title, COUNT(oi.id) as order_count, COALESCE(SUM(oi.quantity * oi.price_per_unit), 0) as revenue
            FROM order_items oi
            JOIN services s ON oi.service_id = s.id
            JOIN orders o ON oi.order_id = o.id
            WHERE o.status IN ("paid","accepted","in_progress","completed")
            GROUP BY s.id, s.title
            ORDER BY order_count DESC
            LIMIT ?
        ');
        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllWithServices() {
        $stmt = $this->pdo->query('
            SELECT o.*, ub.name as buyer_name, up.name as provider_name,
                   (SELECT s.title FROM order_items oi JOIN services s ON oi.service_id = s.id WHERE oi.order_id = o.id LIMIT 1) as service_title
            FROM orders o
            JOIN users ub ON o.buyer_id = ub.id
            JOIN users up ON o.provider_id = up.id
            ORDER BY o.created_at DESC
        ');
        return $stmt->fetchAll();
    }
}
