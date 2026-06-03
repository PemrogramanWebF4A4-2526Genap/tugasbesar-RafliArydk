<?php
class ServiceModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllActive() {
        $stmt = $this->pdo->query('
            SELECT s.*, u.name as provider_name, c.name as category_name 
            FROM services s 
            JOIN users u ON s.provider_id = u.id 
            JOIN categories c ON s.category_id = c.id 
            WHERE s.is_active = 1 AND u.is_verified = 1
            ORDER BY s.created_at DESC
        ');
        return $stmt->fetchAll();
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
            SELECT s.*, u.name as provider_name, c.name as category_name 
            FROM services s 
            JOIN users u ON s.provider_id = u.id 
            JOIN categories c ON s.category_id = c.id 
            WHERE s.id = ?
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
