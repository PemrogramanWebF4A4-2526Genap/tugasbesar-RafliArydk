<?php
require_once __DIR__ . '/../models/ServiceModel.php';
require_once __DIR__ . '/../helpers/upload.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'provider') {
    header('Location: index.php');
    exit;
}

if ((int) ($_SESSION['user']['is_verified'] ?? 0) !== 1) {
    header('Location: index.php?page=dashboard&error=provider_not_verified');
    exit;
}

$serviceModel = new ServiceModel($pdo);
$action = $_GET['action'] ?? '';

if ($action === 'create') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_csrf();
        $category_id = $_POST['category_id'] ?? 1;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = str_replace(['Rp', '.', ' '], '', $_POST['price'] ?? 0);
        $price_unit = $_POST['price_unit'] ?? 'per unit';
        $estimated_duration = trim($_POST['estimated_duration'] ?? '');
        $location = trim($_POST['location'] ?? '');
        
        $image = upload_image_file($_FILES['image'] ?? null, __DIR__ . '/../assets/uploads/services/');

        $serviceModel->create($_SESSION['user']['id'], $category_id, $title, $description, $price, $price_unit, $estimated_duration, $location, $image);
        bisabantu_sync_sql_dump_after_write($pdo);
        header('Location: index.php?page=provider_services&msg=created');
        exit;
    }
}

if ($action === 'update') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_csrf();
        $id = $_POST['id'] ?? 0;
        $category_id = $_POST['category_id'] ?? 1;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = str_replace(['Rp', '.', ' '], '', $_POST['price'] ?? 0);
        $price_unit = $_POST['price_unit'] ?? 'per unit';
        $estimated_duration = trim($_POST['estimated_duration'] ?? '');
        $location = trim($_POST['location'] ?? '');
        
        $image = upload_image_file($_FILES['image'] ?? null, __DIR__ . '/../assets/uploads/services/');

        $serviceModel->update($id, $_SESSION['user']['id'], $category_id, $title, $description, $price, $price_unit, $estimated_duration, $location, $image);
        bisabantu_sync_sql_dump_after_write($pdo);
        header('Location: index.php?page=provider_services&msg=updated');
        exit;
    }
}

if ($action === 'delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=provider_services&error=invalid_request');
        exit;
    }
    require_csrf();
    $id = $_POST['id'] ?? 0;
    $serviceModel->delete($id, $_SESSION['user']['id']);
    bisabantu_sync_sql_dump_after_write($pdo);
    header('Location: index.php?page=provider_services&msg=deleted');
    exit;
}

if ($action === 'toggle') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=provider_services&error=invalid_request');
        exit;
    }
    require_csrf();
    $id = $_POST['id'] ?? 0;
    $status = (int) ($_POST['status'] ?? 1);
    $status = $status === 1 ? 1 : 0;
    $serviceModel->toggleActive($id, $_SESSION['user']['id'], $status);
    bisabantu_sync_sql_dump_after_write($pdo);
    header('Location: index.php?page=provider_services&msg=status_changed');
    exit;
}

header('Location: index.php?page=provider_services');
exit;
