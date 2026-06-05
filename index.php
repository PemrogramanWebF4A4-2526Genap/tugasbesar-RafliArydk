<?php
session_start();
require_once 'config/database.php';
require_once 'helpers/functions.php';

$page = $_GET['page'] ?? 'home';

if (isset($_GET['profile_update']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home');
        exit;
    }

    $userId = $_SESSION['user']['id'];
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

    if (!empty($errors)) {
        $message = urlencode(implode(' ', $errors));
        header('Location: index.php?page=' . urlencode($page) . '&profile_error=' . $message);
        exit;
    }

    if ($updatePassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, phone = ?, address = ?, password = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$name, $email, $phone, $address, $hashedPassword, $userId]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, phone = ?, address = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$name, $email, $phone, $address, $userId]);
    }

    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['address'] = $address;

    header('Location: index.php?page=' . urlencode($page) . '&profile_status=success');
    exit;
}

if ($page == 'home') {
    include 'views/layout/header.php';
    include 'views/home.php';
    include 'views/layout/footer.php';
} elseif ($page == 'auth') {
    // handle auth
    include 'controllers/auth.php';
} elseif ($page == 'admin') {
    include 'controllers/admin.php';
} elseif ($page == 'payment') {
    include 'controllers/payment.php';
} elseif ($page == 'order') {
    include 'controllers/order.php';
} elseif ($page == 'invoice') {
    include 'controllers/invoice.php';
} elseif (in_array($page, ['notification', 'settings', 'verification', 'automation'], true)) {
    include 'controllers/' . $page . '.php';
} elseif ($page == 'service') {
    include 'controllers/service.php';
} elseif ($page == 'report_export') {
    include 'controllers/report_export.php';
} elseif ($page == 'schedule') {
    include 'controllers/schedule.php';
} elseif ($page == 'review') {
    include 'controllers/review.php';
} elseif ($page == 'service_detail') {
    $dashboard_css = 'pembeli';
    include 'views/layout/header.php';
    include 'views/pembeli/service_detail.php';
    include 'views/layout/footer.php';
} elseif ($page == 'cart') {
    if (isset($_GET['action'])) {
        include 'controllers/cart.php';
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

    include 'views/layout/header.php';
    include 'views/pembeli/cart.php';
    include 'views/layout/footer.php';
} elseif ($page == 'checkout') {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }
    if (($_SESSION['user']['role'] ?? '') !== 'buyer') {
        header('Location: index.php?page=dashboard');
        exit;
    }

    include 'views/layout/header.php';
    include 'views/pembeli/checkout.php';
    include 'views/layout/footer.php';
} elseif ($page == 'account_settings') {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }

    if (($_SESSION['user']['role'] ?? '') === 'admin') {
        $dashboard_css = 'admin';
    } elseif (($_SESSION['user']['role'] ?? '') === 'provider') {
        $dashboard_css = 'penyedia';
    } else {
        $dashboard_css = 'pembeli';
    }

    include 'views/layout/header.php';
    include 'views/layout/account_settings.php';
    include 'views/layout/footer.php';
} elseif (in_array($page, ['orders', 'order_detail', 'upload_payment', 'review_form'], true)) {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }
    if (($_SESSION['user']['role'] ?? '') !== 'buyer') {
        header('Location: index.php?page=dashboard');
        exit;
    }

    $dashboard_css = 'pembeli';
    $buyerPages = [
        'orders' => 'orders.php',
        'order_detail' => 'order_detail.php',
        'upload_payment' => 'upload_payment.php',
        'review_form' => 'review_form.php',
    ];

    include 'views/layout/header.php';
    include 'views/pembeli/' . $buyerPages[$page];
    include 'views/layout/footer.php';
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
        $dashboard_css = 'penyedia';
        include 'views/layout/header.php';
        include 'views/public/provider_pending.php';
        include 'views/layout/footer.php';
        exit;
    }

    $dashboard_css = 'penyedia';
    $providerPages = [
        'provider_services' => 'services.php',
        'provider_orders' => 'orders.php',
        'provider_shipping' => 'shipping.php',
        'provider_earnings' => 'earnings.php',
        'provider_reviews' => 'reviews.php',
    ];

    include 'views/layout/header.php';
    include 'views/penyedia/' . $providerPages[$page];
    include 'views/layout/footer.php';
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

    include 'views/layout/header.php';
    include 'views/admin/' . $adminPages[$page];
    include 'views/layout/footer.php';
} elseif (in_array($page, ['about', 'contact'], true)) {
    include 'views/layout/header.php';
    include 'views/public/' . $page . '.php';
    include 'views/layout/footer.php';
} elseif ($page == 'dashboard') {
    // Basic routing for dashboard to prevent 404s
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }

    if ($_SESSION['user']['role'] == 'admin') {
        $dashboard_css = 'admin';
    } elseif ($_SESSION['user']['role'] == 'provider') {
        $dashboard_css = 'penyedia';
    } else {
        $dashboard_css = 'pembeli';
    }

    include 'views/layout/header.php';
    if ($_SESSION['user']['role'] == 'admin') {
        include 'views/admin/dashboard.php';
    } elseif ($_SESSION['user']['role'] == 'provider') {
        if ((int) ($_SESSION['user']['is_verified'] ?? 0) !== 1) {
            include 'views/public/provider_pending.php';
        } else {
            include 'views/penyedia/dashboard.php';
        }
    } else {
        include 'views/pembeli/dashboard.php';
    }
    include 'views/layout/footer.php';
} else {
    include 'views/layout/header.php';
    echo "<div class='container mt-5 text-center' style='min-height: 50vh;'>
            <h1 class='fw-bold' style='color: var(--accent);'>404</h1>
            <h2>Halaman tidak ditemukan</h2>
            <a href='index.php' class='btn btn-primary-custom mt-3'>Kembali ke Beranda</a>
          </div>";
    include 'views/layout/footer.php';
}
?>
