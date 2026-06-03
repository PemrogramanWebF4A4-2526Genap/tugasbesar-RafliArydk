<?php
class UserModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllByRole($role) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE role = ? ORDER BY created_at DESC');
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getUnverifiedProviders() {
        $stmt = $this->pdo->query('SELECT * FROM users WHERE role = "provider" AND is_verified = 0 ORDER BY created_at ASC');
        return $stmt->fetchAll();
    }

    public function verifyProvider($id) {
        $stmt = $this->pdo->prepare('UPDATE users SET is_verified = 1 WHERE id = ? AND role = "provider"');
        return $stmt->execute([$id]);
    }
    
    public function countByRole($role) {
        $stmt = $this->pdo->prepare('SELECT COUNT(id) FROM users WHERE role = ?');
        $stmt->execute([$role]);
        return $stmt->fetchColumn();
    }
}
