<?php
require_once 'models/OrderModel.php';
require_once 'models/NotificationModel.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$action = $_GET['action'] ?? '';
$orderModel = new OrderModel($pdo);
$notifModel = new NotificationModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? 0;
    $status = $_POST['status'] ?? '';

    $order = $orderModel->getById($order_id);
    if (!$order) {
        header('Location: index.php');
        exit;
    }

    $valid = false;
    $redirect = 'index.php';
    
    if ($_SESSION['user']['role'] === 'provider' && $order['provider_id'] === $_SESSION['user']['id']) {
        if (in_array($status, ['accepted', 'in_progress', 'completed', 'cancelled'])) {
            $valid = true;
            $redirect = 'index.php?page=provider_orders';
            $notifModel->create($order['buyer_id'], 'Status Pesanan Diperbarui', "Pesanan Anda {$order['order_number']} sekarang berstatus: $status.");
        }
    } elseif ($_SESSION['user']['role'] === 'buyer' && $order['buyer_id'] === $_SESSION['user']['id']) {
        if (in_array($status, ['completed', 'cancelled'])) {
            $valid = true;
            $redirect = 'index.php?page=orders';
            $notifModel->create($order['provider_id'], 'Status Pesanan Diperbarui', "Pembeli mengubah status pesanan {$order['order_number']} menjadi: $status.");
        }
    }

    if ($valid) {
        $orderModel->updateStatus($order_id, $status);
        header("Location: $redirect&msg=status_updated");
        exit;
    }
}

header('Location: index.php');
exit;
