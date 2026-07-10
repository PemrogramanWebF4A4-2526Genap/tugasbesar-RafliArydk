<?php
require_once __DIR__ . '/../models/ScheduleModel.php';

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'provider') {
    header('Location: index.php?page=home');
    exit;
}

if ((int) ($_SESSION['user']['is_verified'] ?? 0) !== 1) {
    header('Location: index.php?page=dashboard&error=provider_not_verified');
    exit;
}

$scheduleModel = new ScheduleModel($pdo);
$action = $_GET['action'] ?? '';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $day = max(0, min(6, (int) ($_POST['day_of_week'] ?? 0)));
    $start = $_POST['start_time'] ?? '08:00';
    $end = $_POST['end_time'] ?? '17:00';
    $available = isset($_POST['is_available']) ? 1 : 0;

    if ($start < $end) {
        $scheduleModel->create($_SESSION['user']['id'], $day, $start, $end, $available);
    }

    header('Location: index.php?page=provider_shipping&msg=schedule_saved');
    exit;
}

if ($action === 'delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=provider_shipping&error=invalid_request');
        exit;
    }
    require_csrf();
    $scheduleModel->delete((int) ($_POST['id'] ?? 0), $_SESSION['user']['id']);
    header('Location: index.php?page=provider_shipping&msg=schedule_deleted');
    exit;
}

header('Location: index.php?page=provider_shipping');
exit;
