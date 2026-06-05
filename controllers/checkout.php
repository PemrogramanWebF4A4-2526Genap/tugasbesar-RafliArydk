<?php
require_once 'models/ServiceModel.php';
require_once 'models/OrderModel.php';
require_once 'models/NotificationModel.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'buyer') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['cart'])) {
        header('Location: index.php?page=cart&error=empty');
        exit;
    }

    $service_date = trim($_POST['service_date'] ?? '');
    $service_address = trim($_POST['service_address'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? 'bank_transfer');
    
    if (!$service_date || !$service_address) {
        header('Location: index.php?page=checkout&error=missing_fields');
        exit;
    }

    $serviceModel = new ServiceModel($pdo);
    $orderModel = new OrderModel($pdo);
    $notifModel = new NotificationModel($pdo);

    // To handle multiple items from different providers, we group the cart items by provider
    $providerItems = [];
    foreach ($_SESSION['cart'] as $service_id => $quantity) {
        $service = $serviceModel->getById($service_id);
        if ($service) {
            $provider_id = $service['provider_id'];
            if (!isset($providerItems[$provider_id])) {
                $providerItems[$provider_id] = [];
            }
            $providerItems[$provider_id][] = [
                'service' => $service,
                'quantity' => $quantity
            ];
        }
    }

    $buyer_id = $_SESSION['user']['id'];
    
    try {
        $pdo->beginTransaction();

        foreach ($providerItems as $provider_id => $items) {
            $order_number = 'ORD' . date('Ymd') . rand(1000, 9999);
            $total_price = 0;
            $total_quantity = 0;

            // Calculate total first
            foreach ($items as $item) {
                $total_price += ($item['service']['price'] * $item['quantity']);
                $total_quantity += $item['quantity'];
            }

            // Create Order
            $status = $payment_method === 'cod' ? 'paid' : 'waiting_payment';
            $order_id = $orderModel->createOrder($buyer_id, $provider_id, $order_number, $total_price, $total_quantity, $service_date, $service_address, $notes, $status);

            // Create Order Items
            foreach ($items as $item) {
                $orderModel->createOrderItem($order_id, $item['service']['id'], $item['quantity'], $item['service']['price']);
            }

            // Notify Provider
            $notifModel->create($provider_id, 'Pesanan Baru', "Anda mendapat pesanan baru dengan No. Order $order_number");
        }

        $pdo->commit();

        // Clear Cart
        $_SESSION['cart'] = [];
        header('Location: index.php?page=orders&msg=checkout_success');
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        header('Location: index.php?page=cart&error=checkout_failed');
        exit;
    }
} else {
    header('Location: index.php?page=cart');
    exit;
}
