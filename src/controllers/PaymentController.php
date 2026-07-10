<?php
require_once __DIR__ . '/../models/PaymentModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';
require_once __DIR__ . '/../helpers/automation.php';
require_once __DIR__ . '/../helpers/upload.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$action = $_GET['action'] ?? '';
$paymentModel = new PaymentModel($pdo);
$orderModel = new OrderModel($pdo);
$notifModel = new NotificationModel($pdo);

// Buyer uploads proof of payment
if ($action === 'upload') {
    if ($_SESSION['user']['role'] !== 'buyer' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php');
        exit;
    }
    require_csrf();

    $order_id = $_POST['order_id'] ?? 0;
    $method = $_POST['method'] ?? 'bank_transfer';
    if ($method !== 'bank_transfer') {
        header('Location: index.php?page=upload_payment&id=' . (int) $order_id . '&error=upload_failed');
        exit;
    }
    
    $order = $orderModel->getById($order_id);
    if (!$order || $order['buyer_id'] !== $_SESSION['user']['id'] || $order['status'] !== 'waiting_payment') {
        header('Location: index.php?page=orders&error=invalid_order');
        exit;
    }

    $proof_image = upload_image_file($_FILES['proof'] ?? null, __DIR__ . '/../assets/uploads/payments/');

    if ($proof_image) {
        $paymentModel->create($order_id, $method, $proof_image);
        header('Location: index.php?page=orders&msg=payment_uploaded');
        exit;
    } else {
        header('Location: index.php?page=upload_payment&id=' . $order_id . '&error=upload_failed');
        exit;
    }
}

// Admin verifies payment
if ($action === 'verify') {
    if ($_SESSION['user']['role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php');
        exit;
    }
    require_csrf();

    $payment_id = $_POST['payment_id'] ?? 0;
    $status = $_POST['status'] ?? 'pending';
    $notes = trim($_POST['notes'] ?? '');

    if (!in_array($status, ['verified', 'rejected'], true)) {
        header('Location: index.php?page=admin_orders&error=order_failed');
        exit;
    }

    $payment = $paymentModel->getWithOrderForVerification($payment_id);

    if ($payment) {
        $paymentModel->updateStatus($payment_id, $status, $notes);

        if ($status === 'verified') {
            $orderModel->updateStatus($payment['order_id'], 'paid');
            after_payment_verified($pdo, $payment);
        } elseif ($status === 'rejected') {
            $orderModel->updateStatus($payment['order_id'], 'waiting_payment');
            $notifModel->create($payment['buyer_id'], 'Pembayaran Ditolak', "Pembayaran untuk pesanan {$payment['order_number']} ditolak. Alasan: $notes. Silakan unggah ulang.");
        }

        header('Location: index.php?page=admin_orders&msg=payment_processed');
        exit;
    }
}

header('Location: index.php');
exit;
