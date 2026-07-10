<?php
class ServiceModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllActive() {
        $stmt = $this->pdo->query('
            SELECT s.*, u.name as provider_name, u.is_verified as provider_verified, c.name as category_name,
                   COALESCE(AVG(r.rating), 0) as avg_rating,
                   COUNT(r.id) as review_count
            FROM services s 
            JOIN users u ON s.provider_id = u.id 
            JOIN categories c ON s.category_id = c.id 
            LEFT JOIN reviews r ON r.service_id = s.id
            WHERE s.is_active = 1 AND u.is_verified = 1
            GROUP BY s.id
            ORDER BY s.created_at DESC
        ');
        return $stmt->fetchAll();
    }

    public function getPopularActive($limit = 8) {
        $stmt = $this->pdo->prepare('
            SELECT s.*, u.name as provider_name, u.is_verified as provider_verified, c.name as category_name,
                   COALESCE(rr.avg_rating, 0) as avg_rating,
                   COALESCE(rr.review_count, 0) as review_count,
                   COALESCE(oo.order_count, 0) as order_count,
                   COALESCE(oo.order_quantity, 0) as order_quantity
            FROM services s
            JOIN users u ON s.provider_id = u.id
            JOIN categories c ON s.category_id = c.id
            LEFT JOIN (
                SELECT service_id, AVG(rating) as avg_rating, COUNT(id) as review_count
                FROM reviews
                GROUP BY service_id
            ) rr ON rr.service_id = s.id
            LEFT JOIN (
                SELECT oi.service_id,
                       COUNT(DISTINCT o.id) as order_count,
                       COALESCE(SUM(oi.quantity), 0) as order_quantity
                FROM order_items oi
                JOIN orders o ON o.id = oi.order_id
                WHERE o.status IN ("paid", "accepted", "in_progress", "completed")
                GROUP BY oi.service_id
            ) oo ON oo.service_id = s.id
            WHERE s.is_active = 1
              AND u.is_verified = 1
              AND (COALESCE(oo.order_count, 0) > 0 OR COALESCE(rr.review_count, 0) > 0)
            ORDER BY COALESCE(oo.order_quantity, 0) DESC,
                     COALESCE(oo.order_count, 0) DESC,
                     COALESCE(rr.avg_rating, 0) DESC,
                     COALESCE(rr.review_count, 0) DESC,
                     s.created_at DESC
            LIMIT ?
        ');
        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countActive() {
        $stmt = $this->pdo->query('
            SELECT COUNT(s.id)
            FROM services s
            JOIN users u ON s.provider_id = u.id
            WHERE s.is_active = 1 AND u.is_verified = 1
        ');
        return (int) $stmt->fetchColumn();
    }

    public function getByProvider($provider_id) {
        $stmt = $this->pdo->prepare('
            SELECT s.*, c.name as category_name 
            FROM services s 
            JOIN categories c ON s.category_id = c.id 
            WHERE s.provider_id = ? 
            ORDER BY s.created_at DESC
        ');
        $stmt->execute([$provider_id]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare('
            SELECT s.*, u.name as provider_name, u.is_verified as provider_verified, c.name as category_name,
                   COALESCE(AVG(r.rating), 0) as avg_rating,
                   COUNT(r.id) as review_count
            FROM services s 
            JOIN users u ON s.provider_id = u.id 
            JOIN categories c ON s.category_id = c.id 
            LEFT JOIN reviews r ON r.service_id = s.id
            WHERE s.id = ?
            GROUP BY s.id
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($provider_id, $category_id, $title, $description, $price, $price_unit, $estimated_duration, $location, $image) {
        $stmt = $this->pdo->prepare('
            INSERT INTO services (provider_id, category_id, title, description, price, price_unit, estimated_duration, location, image, is_active) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
        ');
        return $stmt->execute([$provider_id, $category_id, $title, $description, $price, $price_unit, $estimated_duration, $location, $image]);
    }

    public function update($id, $provider_id, $category_id, $title, $description, $price, $price_unit, $estimated_duration, $location, $image) {
        if ($image) {
            $stmt = $this->pdo->prepare('
                UPDATE services SET category_id = ?, title = ?, description = ?, price = ?, price_unit = ?, estimated_duration = ?, location = ?, image = ? 
                WHERE id = ? AND provider_id = ?
            ');
            return $stmt->execute([$category_id, $title, $description, $price, $price_unit, $estimated_duration, $location, $image, $id, $provider_id]);
        } else {
            $stmt = $this->pdo->prepare('
                UPDATE services SET category_id = ?, title = ?, description = ?, price = ?, price_unit = ?, estimated_duration = ?, location = ? 
                WHERE id = ? AND provider_id = ?
            ');
            return $stmt->execute([$category_id, $title, $description, $price, $price_unit, $estimated_duration, $location, $id, $provider_id]);
        }
    }

    public function delete($id, $provider_id) {
        $stmt = $this->pdo->prepare('DELETE FROM services WHERE id = ? AND provider_id = ?');
        return $stmt->execute([$id, $provider_id]);
    }

    public function toggleActive($id, $provider_id, $status) {
        $stmt = $this->pdo->prepare('UPDATE services SET is_active = ? WHERE id = ? AND provider_id = ?');
        return $stmt->execute([$status, $id, $provider_id]);
    }
}
