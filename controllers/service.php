<?php
require_once 'models/ServiceModel.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'provider') {
    header('Location: index.php');
    exit;
}

$serviceModel = new ServiceModel($pdo);
$action = $_GET['action'] ?? '';

if ($action === 'create') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $category_id = $_POST['category_id'] ?? 1;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = str_replace(['Rp', '.', ' '], '', $_POST['price'] ?? 0);
        $price_unit = $_POST['price_unit'] ?? 'per unit';
        $estimated_duration = trim($_POST['estimated_duration'] ?? '');
        $location = trim($_POST['location'] ?? '');
        
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['image']['tmp_name'];
            $name = basename($_FILES['image']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $image = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($tmp_name, 'assets/uploads/services/' . $image);
            }
        }

        $serviceModel->create($_SESSION['user']['id'], $category_id, $title, $description, $price, $price_unit, $estimated_duration, $location, $image);
        header('Location: index.php?page=provider_services&msg=created');
        exit;
    }
}

if ($action === 'update') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? 0;
        $category_id = $_POST['category_id'] ?? 1;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = str_replace(['Rp', '.', ' '], '', $_POST['price'] ?? 0);
        $price_unit = $_POST['price_unit'] ?? 'per unit';
        $estimated_duration = trim($_POST['estimated_duration'] ?? '');
        $location = trim($_POST['location'] ?? '');
        
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['image']['tmp_name'];
            $name = basename($_FILES['image']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $image = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($tmp_name, 'assets/uploads/services/' . $image);
            }
        }

        $serviceModel->update($id, $_SESSION['user']['id'], $category_id, $title, $description, $price, $price_unit, $estimated_duration, $location, $image);
        header('Location: index.php?page=provider_services&msg=updated');
        exit;
    }
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? 0;
    $serviceModel->delete($id, $_SESSION['user']['id']);
    header('Location: index.php?page=provider_services&msg=deleted');
    exit;
}

if ($action === 'toggle') {
    $id = $_GET['id'] ?? 0;
    $status = $_GET['status'] ?? 1;
    $serviceModel->toggleActive($id, $_SESSION['user']['id'], $status);
    header('Location: index.php?page=provider_services&msg=status_changed');
    exit;
}

header('Location: index.php?page=provider_services');
exit;
