<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/SettingsModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';
require_once __DIR__ . '/../helpers/automation.php';

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: index.php?page=home');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=dashboard');
    exit;
}

$action = $_GET['action'] ?? '';
$redirect = $_POST['redirect'] ?? 'index.php?page=dashboard';

$userModel = new UserModel($pdo);
$categoryModel = new CategoryModel($pdo);
$orderModel = new OrderModel($pdo);
$settingsModel = new SettingsModel();
$notifModel = new NotificationModel($pdo);

if ($action === 'verify_provider') {
    $id = (int) ($_POST['user_id'] ?? 0);
    if ($userModel->verifyProvider($id)) {
        $notifModel->create($id, 'Akun Terverifikasi', 'Selamat! Akun penyedia Anda telah diverifikasi oleh admin.');
        header('Location: ' . $redirect . '&msg=provider_verified');
    } else {
        header('Location: ' . $redirect . '&error=verify_failed');
    }
    exit;
}

if ($action === 'reject_provider') {
    $id = (int) ($_POST['user_id'] ?? 0);
    if ($userModel->rejectProvider($id)) {
        header('Location: ' . $redirect . '&msg=provider_rejected');
    } else {
        header('Location: ' . $redirect . '&error=reject_failed');
    }
    exit;
}
 

if ($action === 'delete_user') {
    $id = (int) ($_POST['user_id'] ?? 0);
    if ($userModel->deleteUser($id)) {
        header('Location: ' . $redirect . '&msg=user_deleted');
    } else {
        header('Location: ' . $redirect . '&error=delete_failed');
    }
    exit;
}

if ($action === 'toggle_suspend') {
    $id = (int) ($_POST['user_id'] ?? 0);
    $user = $userModel->getById($id);
    if ($user && $user['role'] !== 'admin') {
        $settingsModel->toggleUserSuspension($id);
        header('Location: ' . $redirect . '&msg=user_status_updated');
    } else {
        header('Location: ' . $redirect . '&error=invalid_user');
    }
    exit;
}

if ($action === 'category_create') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    if ($name !== '' && $categoryModel->create($name, $description)) {
        header('Location: index.php?page=admin_categories&msg=category_created');
    } else {
        header('Location: index.php?page=admin_categories&error=category_failed');
    }
    exit;
}

if ($action === 'category_update') {
    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    if ($id && $name !== '' && $categoryModel->update($id, $name, $description)) {
        header('Location: index.php?page=admin_categories&msg=category_updated');
    } else {
        header('Location: index.php?page=admin_categories&error=category_failed');
    }
    exit;
}

if ($action === 'category_delete') {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id && $categoryModel->delete($id)) {
        header('Location: index.php?page=admin_categories&msg=category_deleted');
    } else {
        header('Location: index.php?page=admin_categories&error=category_in_use');
    }
    exit;
}

if ($action === 'order_status') {
    $id = (int) ($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $allowed = ['pending', 'waiting_payment', 'paid', 'accepted', 'in_progress', 'completed', 'cancelled'];
    if ($id && in_array($status, $allowed, true) && $orderModel->updateStatus($id, $status)) {
        if (in_array($status, ['paid', 'completed'], true)) {
            generate_invoice($pdo, $id);
        }
        header('Location: index.php?page=admin_orders&msg=order_updated');
    } else {
        header('Location: index.php?page=admin_orders&error=order_failed');
    }
    exit;
}

if ($action === 'save_settings') {
    $settings = $settingsModel->getAll();
    $settings['commission_rate'] = max(0, min(100, (int) ($_POST['commission_rate'] ?? 10)));
    $settings['shipping_cost'] = max(0, (int) ($_POST['shipping_cost'] ?? 15000));
    $settings['email_template_welcome'] = trim($_POST['email_template_welcome'] ?? '');
    $settings['email_template_order'] = trim($_POST['email_template_order'] ?? '');
    $settings['notification_enabled'] = isset($_POST['notification_enabled']);
    $settings['session_timeout'] = max(15, (int) ($_POST['session_timeout'] ?? 60));
    $methods = $_POST['payment_methods'] ?? [];
    $settings['payment_methods'] = is_array($methods) ? $methods : ['bank_transfer'];

    if ($settingsModel->save($settings)) {
        header('Location: index.php?page=admin_settings&msg=settings_saved');
    } else {
        header('Location: index.php?page=admin_settings&error=settings_failed');
    }
    exit;
}

header('Location: index.php?page=dashboard');
exit;
