<?php
// --- CONTROLLER: NOTIFICATION SYSTEM ---
require_once __DIR__ . '/../models/NotificationModel.php';

// Access control: Users must be logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=home&auth=login');
    exit;
}

$notificationModel = new NotificationModel($pdo);
$action = $_GET['action'] ?? 'list';

// Action: Mark single notification as read
if ($action === 'read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $notificationModel->markAsRead((int) ($_POST['id'] ?? 0), (int) $_SESSION['user']['id']);
    header('Location: index.php?page=notification');
    exit;
}

// Action: Mark all notifications as read
if ($action === 'read_all' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $notificationModel->markAllAsRead((int) $_SESSION['user']['id']);
    header('Location: index.php?page=notification');
    exit;
}

// Action: List unread notifications
$notifications = $notificationModel->getUnreadByUser((int) $_SESSION['user']['id']);
include __DIR__ . '/../views/public/notifications.php';
