<?php
// --- MODEL: NOTIFICATION SYSTEM ---
class NotificationModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create a new notification
    public function create($user_id, $title, $message) {
        $stmt = $this->pdo->prepare('INSERT INTO notifications (user_id, title, message, is_read) VALUES (?, ?, ?, 0)');
        return $stmt->execute([$user_id, $title, $message]);
    }

    // Retrieve unread notifications for a user
    public function getUnreadByUser($user_id) {
        $stmt = $this->pdo->prepare('SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC');
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // Delete single notification (previously mark as read)
    public function markAsRead($id, $user_id) {
        $stmt = $this->pdo->prepare('DELETE FROM notifications WHERE id = ? AND user_id = ?');
        return $stmt->execute([$id, $user_id]);
    }

    // Delete all notifications for a user (previously mark all as read)
    public function markAllAsRead($user_id) {
        $stmt = $this->pdo->prepare('DELETE FROM notifications WHERE user_id = ?');
        return $stmt->execute([$user_id]);
    }
}
