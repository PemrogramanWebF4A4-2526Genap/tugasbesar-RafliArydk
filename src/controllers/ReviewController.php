<?php
require_once __DIR__ . '/../models/ReviewModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../helpers/upload.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'buyer') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $order_id = $_POST['order_id'] ?? 0;
    $service_id = $_POST['service_id'] ?? 0;
    $rating = (int) ($_POST['rating'] ?? 5);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5) {
        header('Location: index.php?page=orders&error=invalid_review');
        exit;
    }

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

    $image = upload_image_file($_FILES['image'] ?? null, __DIR__ . '/../assets/uploads/reviews/');

    $reviewModel->create($service_id, $order_id, $_SESSION['user']['id'], $rating, $comment, $image);
    header('Location: index.php?page=orders&msg=review_submitted');
    exit;
}

header('Location: index.php');
exit;
