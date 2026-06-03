<?php
class NotificationModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($user_id, $title, $message) {
        $stmt = $this->pdo->prepare('INSERT INTO notifications (user_id, title, message, is_read) VALUES (?, ?, ?, 0)');
        return $stmt->execute([$user_id, $title, $message]);
    }

    public function getUnreadByUser($user_id) {
        $stmt = $this->pdo->prepare('SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC');
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function markAsRead($id, $user_id) {
        $stmt = $this->pdo->prepare('UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?');
        return $stmt->execute([$id, $user_id]);
    }
}
