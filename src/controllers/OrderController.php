<?php
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';
require_once __DIR__ . '/../helpers/automation.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$action = $_GET['action'] ?? '';
$orderModel = new OrderModel($pdo);
$notifModel = new NotificationModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $order_id = $_POST['order_id'] ?? 0;
    $status = $_POST['status'] ?? '';

    $order = $orderModel->getById($order_id);
    if (!$order) {
        header('Location: index.php');
        exit;
    }

    $valid = false;
    $redirect = 'index.php';
    
    if ($_SESSION['user']['role'] === 'provider' && (int) $order['provider_id'] === (int) $_SESSION['user']['id']) {
        $allowedTransitions = [
            'waiting_payment' => ['accepted'],
            'paid' => ['accepted'],
            'accepted' => ['in_progress', 'completed'],
            'in_progress' => ['completed'],
        ];
        if (in_array($status, $allowedTransitions[$order['status']] ?? [], true)) {
            $valid = true;
            $redirect = 'index.php?page=provider_orders';
            $notifModel->create($order['buyer_id'], 'Status Pesanan Diperbarui', "Pesanan Anda {$order['order_number']} sekarang berstatus: " . order_status_info($status)[0] . '.');
            if ($status === 'completed') {
                generate_invoice($pdo, $order_id);
            }
        }
    } elseif ($_SESSION['user']['role'] === 'buyer' && (int) $order['buyer_id'] === (int) $_SESSION['user']['id']) {
        $buyerTransitions = [
            'waiting_payment' => ['cancelled'],
            'paid' => ['cancelled'],
            'accepted' => ['cancelled'],
            'in_progress' => ['completed'],
        ];
        if (in_array($status, $buyerTransitions[$order['status']] ?? [], true)) {
            $valid = true;
            $redirect = 'index.php?page=orders';
            $notifModel->create($order['provider_id'], 'Status Pesanan Diperbarui', "Pembeli mengubah status pesanan {$order['order_number']} menjadi: " . order_status_info($status)[0] . '.');
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
