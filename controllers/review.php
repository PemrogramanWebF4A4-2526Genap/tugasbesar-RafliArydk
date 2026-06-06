<?php
require_once 'models/ReviewModel.php';
require_once 'models/OrderModel.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'buyer') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? 0;
    $service_id = $_POST['service_id'] ?? 0;
    $rating = (int) ($_POST['rating'] ?? 5);
    $comment = trim($_POST['comment'] ?? '');

    $orderModel = new OrderModel($pdo);
    $reviewModel = new ReviewModel($pdo);

    $order = $orderModel->getById($order_id);
    if (!$order || $order['buyer_id'] !== $_SESSION['user']['id'] || $order['status'] !== 'completed') {
        header('Location: index.php?page=orders&error=invalid_review');
        exit;
    }
    $items = $orderModel->getOrderItems($order_id);
    $validServiceIds = array_map(fn($item) => (int) $item['service_id'], $items);
    if (!in_array((int) $service_id, $validServiceIds, true)) {
        header('Location: index.php?page=orders&error=invalid_review');
        exit;
    }

    if ($reviewModel->checkExists($order_id)) {
        header('Location: index.php?page=orders&error=already_reviewed');
        exit;
    }

    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $name = basename($_FILES['image']['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $mime = mime_content_type($tmp_name);
        if (in_array($ext, ['jpg', 'jpeg', 'png'], true) && in_array($mime, ['image/jpeg', 'image/png'], true)) {
            $uploadDir = __DIR__ . '/../assets/uploads/reviews/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            $image = time() . '_' . uniqid() . '.' . $ext;
            if (!move_uploaded_file($tmp_name, $uploadDir . $image)) {
                $image = null;
            }
        }
    }

    $reviewModel->create($service_id, $order_id, $_SESSION['user']['id'], $rating, $comment, $image);
    header('Location: index.php?page=orders&msg=review_submitted');
    exit;
}

header('Location: index.php');
exit;
