<?php
require_once __DIR__ . '/../models/InvoiceModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../helpers/automation.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=home&auth=login');
    exit;
}

$orderId = (int) ($_GET['id'] ?? 0);
$orderModel = new OrderModel($pdo);
$order = $orderModel->getById($orderId);

if (!$order) {
    header('Location: index.php?page=dashboard&error=invoice_not_found');
    exit;
}

$role = $_SESSION['user']['role'] ?? '';
$userId = (int) $_SESSION['user']['id'];
$canView = $role === 'admin'
    || ($role === 'buyer' && (int) $order['buyer_id'] === $userId)
    || ($role === 'provider' && (int) $order['provider_id'] === $userId);

if (!$canView) {
    header('Location: index.php?page=dashboard&error=invoice_forbidden');
    exit;
}

$invoiceAllowedStatuses = ['paid', 'accepted', 'in_progress', 'completed'];
if (!in_array($order['status'], $invoiceAllowedStatuses, true)) {
    header('Location: index.php?page=order_detail&id=' . $orderId . '&error=invoice_unavailable');
    exit;
}

generate_invoice($pdo, $orderId);
$invoiceModel = new InvoiceModel($pdo);
$invoice = $invoiceModel->getPrintableByOrderId($orderId);
$items = $orderModel->getOrderItems($orderId);

include __DIR__ . '/../views/public/invoice.php';
