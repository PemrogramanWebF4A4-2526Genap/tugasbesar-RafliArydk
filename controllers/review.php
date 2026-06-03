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

    if ($reviewModel->checkExists($order_id)) {
        header('Location: index.php?page=orders&error=already_reviewed');
        exit;
    }

    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $name = basename($_FILES['image']['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $image = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($tmp_name, 'assets/uploads/reviews/' . $image);
        }
    }

    $reviewModel->create($service_id, $order_id, $_SESSION['user']['id'], $rating, $comment, $image);
    header('Location: index.php?page=orders&msg=review_submitted');
    exit;
}

header('Location: index.php');
exit;
