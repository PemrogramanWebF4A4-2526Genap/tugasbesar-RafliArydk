<?php
class ReviewModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($service_id, $order_id, $user_id, $rating, $comment, $image = null) {
        $stmt = $this->pdo->prepare('
            INSERT INTO reviews (service_id, order_id, user_id, rating, comment, image) 
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        return $stmt->execute([$service_id, $order_id, $user_id, $rating, $comment, $image]);
    }

    public function getByService($service_id) {
        $stmt = $this->pdo->prepare('
            SELECT r.*, u.name as reviewer_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.service_id = ? 
            ORDER BY r.created_at DESC
        ');
        $stmt->execute([$service_id]);
        return $stmt->fetchAll();
    }

    public function checkExists($order_id) {
        $stmt = $this->pdo->prepare('SELECT id FROM reviews WHERE order_id = ?');
        $stmt->execute([$order_id]);
        return $stmt->fetch() ? true : false;
    }

    public function getAverageRatingByProvider($provider_id) {
        $stmt = $this->pdo->prepare('
            SELECT AVG(r.rating) as avg_rating, COUNT(r.id) as total_reviews 
            FROM reviews r 
            JOIN services s ON r.service_id = s.id 
            WHERE s.provider_id = ?
        ');
        $stmt->execute([$provider_id]);
        return $stmt->fetch();
    }

    public function getByProvider($provider_id) {
        $stmt = $this->pdo->prepare('
            SELECT r.*, u.name as reviewer_name, s.title as service_title, o.order_number
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            JOIN services s ON r.service_id = s.id
            JOIN orders o ON r.order_id = o.id
            WHERE s.provider_id = ?
            ORDER BY r.created_at DESC
        ');
        $stmt->execute([$provider_id]);
        return $stmt->fetchAll();
    }
}
