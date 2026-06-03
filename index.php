<?php
session_start();
require_once 'config/database.php';
require_once 'helpers/functions.php';

$page = $_GET['page'] ?? 'home';

if ($page == 'home') {
    include 'views/layout/header.php';
    include 'views/home.php';
    include 'views/layout/footer.php';
} elseif ($page == 'auth') {
    // handle auth
    include 'controllers/auth.php';
} elseif ($page == 'cart') {
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
} elseif (in_array($page, ['orders', 'upload_payment', 'review_form'], true)) {
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
        'upload_payment' => 'upload_payment.php',
        'review_form' => 'review_form.php',
    ];

    include 'views/layout/header.php';
    include 'views/pembeli/' . $buyerPages[$page];
    include 'views/layout/footer.php';
} elseif (in_array($page, ['provider_services', 'provider_orders', 'provider_earnings'], true)) {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=home&auth=login');
        exit;
    }
    if (($_SESSION['user']['role'] ?? '') !== 'provider') {
        header('Location: index.php?page=dashboard');
        exit;
    }

    $dashboard_css = 'penyedia';
    $providerPages = [
        'provider_services' => 'services.php',
        'provider_orders' => 'orders.php',
        'provider_earnings' => 'earnings.php',
    ];

    include 'views/layout/header.php';
    include 'views/penyedia/' . $providerPages[$page];
    include 'views/layout/footer.php';
} elseif (in_array($page, ['admin_users', 'admin_categories', 'admin_orders', 'admin_reports', 'admin_settings'], true)) {
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
        'admin_categories' => 'categories.php',
        'admin_orders' => 'all_orders.php',
        'admin_reports' => 'reports.php',
        'admin_settings' => 'settings.php',
    ];

    include 'views/layout/header.php';
    include 'views/admin/' . $adminPages[$page];
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
        include 'views/penyedia/dashboard.php';
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
