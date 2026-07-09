<?php
session_start();
require_once 'src/config/database.php';
require_once 'src/helpers/functions.php';
require_once 'src/helpers/upload.php';
require_once 'src/helpers/database_dump.php';

$page = $_GET['page'] ?? 'home';

function ensure_user_profile_photo_column(PDO $pdo)
{
    static $checked = false;
    if ($checked) {
        return;
    }

    $checked = true;
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'profile_photo'");
    if (!$stmt->fetch()) {
        $pdo->exec('ALTER TABLE users ADD COLUMN profile_photo varchar(255) DEFAULT NULL AFTER remember_token');
    }
}

if (isset($_GET['profile_update']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home');
        exit;
    }

    ensure_user_profile_photo_column($pdo);

    $userId = (int) $_SESSION['user']['id'];
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $name = trim($firstName . ' ' . $lastName);
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $errors = [];
    if ($name === '') {
        $errors[] = 'Nama depan wajib diisi.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email tidak valid.';
    }

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
    $stmt->execute([$email, $userId]);
    if ($stmt->fetch()) {
        $errors[] = 'Email sudah digunakan oleh akun lain.';
    }

    $updatePassword = false;
    if ($currentPassword !== '' || $newPassword !== '' || $confirmPassword !== '') {
        if ($currentPassword === '') {
            $errors[] = 'Masukkan password lama untuk mengganti password.';
        } else {
            $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            if (!$user || !password_verify($currentPassword, $user['password'])) {
                $errors[] = 'Password lama tidak cocok.';
            } elseif ($newPassword === '') {
                $errors[] = 'Masukkan password baru.';
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = 'Password baru dan konfirmasi tidak sama.';
            } elseif (strlen($newPassword) < 8) {
                $errors[] = 'Password baru minimal 8 karakter.';
            } else {
                $updatePassword = true;
            }
        }
    }

    // Optional profile photo upload
    $newProfilePhoto = null;
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $fileName = upload_image_file($_FILES['profile_photo'], __DIR__ . '/src/assets/uploads/profile/');
        if ($fileName !== null) {
            $newProfilePhoto = 'src/assets/uploads/profile/' . $fileName;
        } else {
            $errors[] = 'Format foto profil harus JPG/PNG dan maksimal 2MB.';
        }
    }

    if (!empty($errors)) {
        $message = urlencode(implode(' ', $errors));
        header('Location: index.php?page=' . urlencode($page) . '&profile_error=' . $message);
        exit;
    }

    if ($updatePassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        if ($newProfilePhoto !== null) {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, phone = ?, address = ?, profile_photo = ?, password = ?, updated_at = NOW() WHERE id = ?');
            $stmt->execute([$name, $email, $phone, $address, $newProfilePhoto, $hashedPassword, $userId]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, phone = ?, address = ?, password = ?, updated_at = NOW() WHERE id = ?');
            $stmt->execute([$name, $email, $phone, $address, $hashedPassword, $userId]);
        }
    } else {
        if ($newProfilePhoto !== null) {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, phone = ?, address = ?, profile_photo = ?, updated_at = NOW() WHERE id = ?');
            $stmt->execute([$name, $email, $phone, $address, $newProfilePhoto, $userId]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, phone = ?, address = ?, updated_at = NOW() WHERE id = ?');
            $stmt->execute([$name, $email, $phone, $address, $userId]);
        }
    }

    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['address'] = $address;
    if ($newProfilePhoto !== null) {
        $_SESSION['user']['profile_photo'] = $newProfilePhoto;
    }

    header('Location: index.php?page=' . urlencode($page) . '&profile_status=success');
    exit;
}


if ($page == 'home') {
    include 'src/controllers/HomeController.php';
} elseif ($page == 'auth') {
    include 'src/controllers/AuthController.php';
} elseif ($page == 'admin') {
    include 'src/controllers/AdminController.php';
} elseif ($page == 'payment') {
    include 'src/controllers/PaymentController.php';
} elseif ($page == 'order') {
    include 'src/controllers/OrderController.php';
} elseif ($page == 'invoice') {
    include 'src/controllers/InvoiceController.php';
} elseif ($page == 'notification') {
    include 'src/controllers/NotificationController.php';
} elseif ($page == 'service') {
    include 'src/controllers/ServiceController.php';
} elseif ($page == 'sync_dump') {
    if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
        header('Location: index.php?page=home');
        exit;
    }
    require __DIR__ . '/database/sync_dump.php';
    exit;
} elseif ($page == 'report_export') {
    include 'src/controllers/ReportExportController.php';
} elseif ($page == 'schedule') {
    include 'src/controllers/ScheduleController.php';
} elseif ($page == 'review') {
    include 'src/controllers/ReviewController.php';
} elseif ($page == 'service_detail') {
    $dashboard_css = 'buyer';
    include 'src/views/layout/header.php';
    include 'src/views/buyer/service_detail.php';
    include 'src/views/layout/footer.php';
} elseif ($page == 'cart') {
    if (isset($_GET['action'])) {
        include 'src/controllers/CartController.php';
        exit;
    }
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }
    if (($_SESSION['user']['role'] ?? '') !== 'buyer') {
        header('Location: index.php?page=dashboard');
        exit;
    }

    include 'src/views/layout/header.php';
    include 'src/views/buyer/cart.php';
    include 'src/views/layout/footer.php';
} elseif ($page == 'checkout') {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }
    if (($_SESSION['user']['role'] ?? '') !== 'buyer') {
        header('Location: index.php?page=dashboard');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include 'src/controllers/CheckoutController.php';
        exit;
    }

    include 'src/views/layout/header.php';
    include 'src/views/buyer/checkout.php';
    include 'src/views/layout/footer.php';
} elseif ($page == 'account_settings') {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }

    if (($_SESSION['user']['role'] ?? '') === 'admin') {
        $dashboard_css = 'admin';
    } elseif (($_SESSION['user']['role'] ?? '') === 'provider') {
        $dashboard_css = 'seller';
    } else {
        $dashboard_css = 'buyer';
    }

    include 'src/views/layout/header.php';
    include 'src/views/layout/account_settings.php';
    include 'src/views/layout/footer.php';
} elseif (in_array($page, ['orders', 'order_detail', 'upload_payment', 'review_form'], true)) {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }
    if (($_SESSION['user']['role'] ?? '') !== 'buyer') {
        header('Location: index.php?page=dashboard');
        exit;
    }

    $dashboard_css = 'buyer';
    $buyerPages = [
        'orders' => 'orders.php',
        'order_detail' => 'order_detail.php',
        'upload_payment' => 'upload_payment.php',
        'review_form' => 'review_form.php',
    ];

    include 'src/views/layout/header.php';
    include 'src/views/buyer/' . $buyerPages[$page];
    include 'src/views/layout/footer.php';
} elseif (in_array($page, ['provider_services', 'provider_orders', 'provider_shipping', 'provider_earnings', 'provider_reviews'], true)) {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }
    if (($_SESSION['user']['role'] ?? '') !== 'provider') {
        header('Location: index.php?page=dashboard');
        exit;
    }
    if ((int) ($_SESSION['user']['is_verified'] ?? 0) !== 1) {
        $dashboard_css = 'seller';
        include 'src/views/layout/header.php';
        include 'src/views/public/provider_pending.php';
        include 'src/views/layout/footer.php';
        exit;
    }

    $dashboard_css = 'seller';
    $providerPages = [
        'provider_services' => 'services.php',
        'provider_orders' => 'orders.php',
        'provider_shipping' => 'shipping.php',
        'provider_earnings' => 'earnings.php',
        'provider_reviews' => 'reviews.php',
    ];

    include 'src/views/layout/header.php';
    include 'src/views/seller/' . $providerPages[$page];
    include 'src/views/layout/footer.php';
} elseif (in_array($page, ['admin_users', 'admin_verify', 'admin_categories', 'admin_orders', 'admin_reports', 'admin_settings'], true)) {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }
    if (($_SESSION['user']['role'] ?? '') !== 'admin') {
        header('Location: index.php?page=dashboard');
        exit;
    }

    $dashboard_css = 'admin';
    $adminPages = [
        'admin_users' => 'users.php',
        'admin_verify' => 'verify_providers.php',
        'admin_categories' => 'categories.php',
        'admin_orders' => 'all_orders.php',
        'admin_reports' => 'reports.php',
        'admin_settings' => 'settings.php',
    ];

    include 'src/views/layout/header.php';
    include 'src/views/admin/' . $adminPages[$page];
    include 'src/views/layout/footer.php';
} elseif ($page == 'dashboard') {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }

    if ($_SESSION['user']['role'] == 'admin') {
        $dashboard_css = 'admin';
    } elseif ($_SESSION['user']['role'] == 'provider') {
        $dashboard_css = 'seller';
    } else {
        $dashboard_css = 'buyer';
    }

    include 'src/views/layout/header.php';
    
    if ($_SESSION['user']['role'] == 'admin') {
        include 'src/views/admin/dashboard.php';
    } elseif ($_SESSION['user']['role'] == 'provider') {
        if ((int) ($_SESSION['user']['is_verified'] ?? 0) !== 1) {
            include 'src/views/public/provider_pending.php';
        } else {
            include 'src/views/seller/dashboard.php';
        }
    } else {
        include 'src/views/buyer/dashboard.php';
    }
    include 'src/views/layout/footer.php';


} else {
    include 'src/views/layout/header.php';
    echo "<div class='container mt-5 text-center' style='min-height: 50vh;'>
            <h1 class='fw-bold' style='color: var(--accent);'>404</h1>
            <h2>Halaman tidak ditemukan</h2>
            <a href='index.php' class='btn btn-primary-custom mt-3'>Kembali ke Beranda</a>
          </div>";
    include 'src/views/layout/footer.php';
}
