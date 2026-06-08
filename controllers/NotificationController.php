<?php
require_once __DIR__ . '/../models/NotificationModel.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=home&auth=login');
    exit;
}

$notificationModel = new NotificationModel($pdo);
$action = $_GET['action'] ?? 'list';

if ($action === 'read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $notificationModel->markAsRead((int) ($_POST['id'] ?? 0), (int) $_SESSION['user']['id']);
    header('Location: index.php?page=dashboard');
    exit;
}

$notifications = $notificationModel->getUnreadByUser((int) $_SESSION['user']['id']);
include __DIR__ . '/../views/public/notifications.php';
