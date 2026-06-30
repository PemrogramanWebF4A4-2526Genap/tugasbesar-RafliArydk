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

    // Mark single notification as read
    public function markAsRead($id, $user_id) {
        $stmt = $this->pdo->prepare('UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?');
        return $stmt->execute([$id, $user_id]);
    }

    // Mark all notifications for a user as read
    public function markAllAsRead($user_id) {
        $stmt = $this->pdo->prepare('UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0');
        return $stmt->execute([$user_id]);
    }
}
