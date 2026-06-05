<?php
require_once __DIR__ . '/../models/PaymentModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';
require_once __DIR__ . '/../helpers/automation.php';

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

    $order_id = $_POST['order_id'] ?? 0;
    $method = $_POST['method'] ?? 'bank_transfer';
    
    $order = $orderModel->getById($order_id);
    if (!$order || $order['buyer_id'] !== $_SESSION['user']['id'] || $order['status'] !== 'waiting_payment') {
        header('Location: index.php?page=orders&error=invalid_order');
        exit;
    }

    $proof_image = null;
    if (isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['proof']['tmp_name'];
        $name = basename($_FILES['proof']['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $proof_image = time() . '_' . uniqid() . '.' . $ext;
            $uploadDir = 'assets/uploads/payments/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            move_uploaded_file($tmp_name, $uploadDir . $proof_image);
        }
    }

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

    $payment_id = $_POST['payment_id'] ?? 0;
    $status = $_POST['status'] ?? 'pending';
    $notes = trim($_POST['notes'] ?? '');

    // Get payment and order details
    $stmt = $pdo->prepare('
        SELECT p.*, o.id as order_id, o.order_number, o.buyer_id, o.provider_id, ub.email as buyer_email
        FROM payments p
        JOIN orders o ON p.order_id = o.id
        JOIN users ub ON o.buyer_id = ub.id
        WHERE p.id = ?
    ');
    $stmt->execute([$payment_id]);
    $payment = $stmt->fetch();

    if ($payment) {
        $paymentModel->updateStatus($payment_id, $status, $notes);

        if ($status === 'verified') {
            $orderModel->updateStatus($payment['order_id'], 'paid');
            after_payment_verified($pdo, $payment);
        } elseif ($status === 'rejected') {
            $notifModel->create($payment['buyer_id'], 'Pembayaran Ditolak', "Pembayaran untuk pesanan {$payment['order_number']} ditolak. Alasan: $notes. Silakan unggah ulang.");
        }

        header('Location: index.php?page=admin_orders&msg=payment_processed');
        exit;
    }
}

header('Location: index.php');
exit;
