<?php
class PaymentModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($order_id, $method, $proof_image) {
        $stmt = $this->pdo->prepare('
            INSERT INTO payments (order_id, method, proof_image, status) 
            VALUES (?, ?, ?, "pending")
        ');
        return $stmt->execute([$order_id, $method, $proof_image]);
    }

    public function getByOrderId($order_id) {
        $stmt = $this->pdo->prepare('SELECT * FROM payments WHERE order_id = ? ORDER BY id DESC LIMIT 1');
        $stmt->execute([$order_id]);
        return $stmt->fetch();
    }

    public function hasPendingForOrder($order_id) {
        $stmt = $this->pdo->prepare('SELECT id FROM payments WHERE order_id = ? AND status = "pending" LIMIT 1');
        $stmt->execute([(int) $order_id]);
        return $stmt->fetch() ? true : false;
    }

    public function getPending() {
        $stmt = $this->pdo->query('
            SELECT p.*, o.order_number, o.total_price 
            FROM payments p 
            JOIN orders o ON p.order_id = o.id 
            WHERE p.status = "pending"
            ORDER BY p.created_at ASC
        ');
        return $stmt->fetchAll();
    }

    public function getWithOrderForVerification($id) {
        $stmt = $this->pdo->prepare('
            SELECT p.*, o.id as order_id, o.order_number, o.buyer_id, o.provider_id, ub.email as buyer_email
            FROM payments p
            JOIN orders o ON p.order_id = o.id
            JOIN users ub ON o.buyer_id = ub.id
            WHERE p.id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateStatus($id, $status, $notes = null) {
        $stmt = $this->pdo->prepare('UPDATE payments SET status = ?, verified_at = NOW(), notes = ? WHERE id = ?');
        return $stmt->execute([$status, $notes, $id]);
    }
}
