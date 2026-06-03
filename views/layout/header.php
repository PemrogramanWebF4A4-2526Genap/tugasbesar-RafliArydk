<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BisaBantu - Jasa Terpercaya di Sekitarmu</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,800;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/auth-modal.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/footer.css') ?>">
    <?php if (isset($dashboard_css) && $dashboard_css === 'admin'): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/dashboard_admin.css') ?>">
    <?php elseif (isset($dashboard_css) && $dashboard_css === 'pembeli'): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/dashboard_pembeli.css') ?>">
    <?php elseif (isset($dashboard_css) && $dashboard_css === 'penyedia'): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/dashboard_penyedia.css') ?>">
    <?php endif; ?>
</head>
<?php
$page = $page ?? ($_GET['page'] ?? 'home');
$currentPage = $page;
?>
<body class="<?= isset($_SESSION['user']) ? 'user-logged-in' : '' ?> <?= isset($_SESSION['user']['role']) ? 'role-' . e($_SESSION['user']['role']) : '' ?> <?= (isset($_SESSION['user']) && $page !== 'home') ? 'has-role-sidebar' : '' ?>">

<?php
$homeUrl = base_url('index.php?page=home');
$isLoggedIn = isset($_SESSION['user']);
$userRole = $_SESSION['user']['role'] ?? null;
$roleLabels = [
    'buyer' => 'Pembeli',
    'provider' => 'Penyedia',
    'admin' => 'Admin',
];
$roleLabel = $roleLabels[$userRole] ?? 'Akun';
$roleShortLabels = [
    'buyer' => 'Buyer',
    'provider' => 'Penyedia',
    'admin' => 'Admin',
];
$roleShortLabel = $roleShortLabels[$userRole] ?? $roleLabel;
$userName = $_SESSION['user']['name'] ?? 'Pengguna';
$userInitial = strtoupper(substr($userName, 0, 1));
$nameParts = explode(' ', $userName, 2);
$firstNameValue = $nameParts[0] ?? '';
$lastNameValue = $nameParts[1] ?? '';
$profileHref = base_url('index.php?page=dashboard');
$cartUrl = base_url('index.php?page=cart');
$cartItems = [
    [
        'title' => 'Jasa Bersih Rumah Profesional',
        'meta' => 'Budi W. - Bandung',
        'price' => 'Rp 150.000',
        'icon' => 'bi-brush',
    ],
    [
        'title' => 'Les Matematika SD-SMP',
        'meta' => 'Sari R. - Jakarta Selatan',
        'price' => 'Rp 75.000',
        'icon' => 'bi-calculator',
    ],
    [
        'title' => 'Servis AC & Kulkas Rumahan',
        'meta' => 'Arif H. - Surabaya',
        'price' => 'Rp 200.000',
        'icon' => 'bi-tools',
    ],
];
$cartCount = count($cartItems);
$cartAction = $isLoggedIn
    ? 'href="' . e($cartUrl) . '"'
    : 'href="#" onclick="openAuthModal(\'login\'); return false;"';
$sideNav = [
    'buyer' => [
        ['label' => 'Dashboard', 'icon' => 'bi-grid', 'href' => base_url('index.php?page=dashboard')],
        ['label' => 'Pesanan Saya', 'icon' => 'bi-bag', 'href' => base_url('index.php?page=orders')],
        ['label' => 'Keranjang', 'icon' => 'bi-cart3', 'href' => base_url('index.php?page=cart')],
        ['label' => 'Review', 'icon' => 'bi-star', 'href' => base_url('index.php?page=review_form')],
        ['label' => 'Pembayaran', 'icon' => 'bi-credit-card', 'href' => base_url('index.php?page=upload_payment')],
    ],
    'provider' => [
        ['label' => 'Dashboard', 'icon' => 'bi-grid', 'href' => base_url('index.php?page=dashboard')],
        ['label' => 'Produk', 'icon' => 'bi-box-seam', 'href' => base_url('index.php?page=provider_services')],
        ['label' => 'Pesanan', 'icon' => 'bi-receipt', 'href' => base_url('index.php?page=provider_orders'), 'badge' => '7'],
        ['label' => 'Pengiriman', 'icon' => 'bi-send', 'href' => base_url('index.php?page=provider_shipping')],
        ['label' => 'Statistik', 'icon' => 'bi-bar-chart', 'href' => base_url('index.php?page=provider_earnings')],
        ['label' => 'Ulasan', 'icon' => 'bi-star', 'href' => base_url('index.php?page=provider_reviews')],
    ],
    'admin' => [
        ['label' => 'Manage Pembeli', 'icon' => 'bi-people-fill', 'href' => base_url('index.php?page=admin_users')],
        ['label' => 'Verifikasi Penyedia', 'icon' => 'bi-shield-check', 'href' => base_url('index.php?page=admin_users'), 'badge' => '5'],
        ['label' => 'Laporan Masalah', 'icon' => 'bi-exclamation-triangle', 'href' => base_url('index.php?page=admin_reports')],
        ['label' => 'Analytics', 'icon' => 'bi-file-earmark-bar-graph', 'href' => base_url('index.php?page=admin_reports')],
        ['label' => 'Admin Mode', 'icon' => 'bi-sliders', 'href' => base_url('index.php?page=admin_settings')],
    ],
];
$activeSideNav = $isLoggedIn && isset($sideNav[$userRole]) ? $sideNav[$userRole] : [];
?>
<nav class="navbar navbar-expand-lg navbar-custom navbar-dark sticky-top role-header role-header-<?= e($userRole ?? 'guest') ?>">
    <div class="container role-header-container">
        <a class="navbar-brand" href="<?= base_url('index.php?page=home') ?>">BisaBantu<span>.</span></a>
        <?php if ($isLoggedIn): ?>
            <span class="header-role-text"><?= e($roleShortLabel) ?></span>
        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <?php if (!$isLoggedIn): ?>
                    <li class="nav-item"><a class="nav-link active" href="<?= $homeUrl ?>#beranda" data-scroll="#beranda">Beranda</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?= $homeUrl ?>#kategori" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Kategori</a>
                        <ul class="dropdown-menu bb-nav-dropdown" aria-labelledby="categoryDropdown">
                            <li><a class="dropdown-item" href="<?= $homeUrl ?>#kategori" data-scroll="#kategori">Semua Kategori</a></li>
                            <li><a class="dropdown-item" href="<?= $homeUrl ?>#layanan-jasa" data-scroll="#layanan-jasa" data-nav-category="bersih-bersih">Bersih-bersih</a></li>
                            <li><a class="dropdown-item" href="<?= $homeUrl ?>#layanan-jasa" data-scroll="#layanan-jasa" data-nav-category="perbaikan">Perbaikan</a></li>
                            <li><a class="dropdown-item" href="<?= $homeUrl ?>#layanan-jasa" data-scroll="#layanan-jasa" data-nav-category="les-privat">Les Privat</a></li>
                            <li><a class="dropdown-item" href="<?= $homeUrl ?>#layanan-jasa" data-scroll="#layanan-jasa" data-nav-category="laundry">Laundry</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?= $homeUrl ?>#layanan-jasa" data-scroll="#layanan-jasa">Jasa</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $homeUrl ?>#cara-kerja" data-scroll="#cara-kerja">Cara Kerja</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $homeUrl ?>#testimoni" data-scroll="#testimoni">Tentang Kami</a></li>
                <?php endif; ?>
            </ul>
            <div class="d-flex gap-2 align-items-center role-actions">
                <?php if ($isLoggedIn && $userRole === 'provider'): ?>
                    <a href="<?= base_url('index.php?page=dashboard') ?>" class="btn btn-outline-custom btn-dashboard">
                        <i class="bi bi-grid me-1"></i>Dashboard
                    </a>
                <?php endif; ?>
                <?php if (!$isLoggedIn || $userRole === 'buyer'): ?>
                    <div class="cart-nav">
                        <a class="cart-nav-btn" <?= $cartAction ?> aria-label="Buka keranjang">
                            <i class="bi bi-cart3"></i>
                            <span class="cart-nav-badge"><?= $cartCount ?></span>
                        </a>
                        <?php if ($isLoggedIn): ?>
                            <div class="cart-preview">
                                <div class="cart-preview-head">
                                    <strong>Keranjang (<?= $cartCount ?>)</strong>
                                    <a href="<?= $cartUrl ?>">Lihat</a>
                                </div>
                                <div class="cart-preview-list">
                                    <?php foreach ($cartItems as $item): ?>
                                        <a class="cart-preview-item" href="<?= $cartUrl ?>">
                                            <span class="cart-preview-thumb"><i class="bi <?= e($item['icon']) ?>"></i></span>
                                            <span class="cart-preview-copy">
                                                <span><?= e($item['title']) ?></span>
                                                <small><?= e($item['meta']) ?></small>
                                            </span>
                                            <strong>1x <?= e($item['price']) ?></strong>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="#" class="header-icon-btn" aria-label="Notifikasi"><i class="bi bi-bell"></i><span>2</span></a>
                    <a href="#" class="header-icon-btn" aria-label="Pesan"><i class="bi bi-envelope"></i></a>
                    <div class="profile-dropdown-wrapper">
                        <a href="#" class="role-profile" id="profileToggle">
                            <span class="role-profile-avatar"><?= e($userInitial) ?></span>
                            <span class="role-profile-copy">
                                <strong><?= e($userName) ?></strong>
                                <small><?= e($roleLabel) ?></small>
                            </span>
                        </a>
                        <div class="profile-dropdown" id="profileDropdown">
                            <div class="profile-dropdown-header">
                                <div class="profile-dropdown-avatar"><?= e($userInitial) ?></div>
                                <div>
                                    <strong><?= e($userName) ?></strong>
                                    <p class="text-muted small mb-0">Member Silver</p>
                                    <small><?= e($_SESSION['user']['email'] ?? '') ?></small>
                                </div>
                            </div>
                            <hr class="my-2">
                            <a href="<?= base_url('index.php?page=dashboard') ?>" class="profile-dropdown-item">Dashboard</a>
                            <?php if ($userRole === 'buyer'): ?>
                                <a href="<?= base_url('index.php?page=orders') ?>" class="profile-dropdown-item">Pesanan Saya</a>
                                <a href="<?= base_url('index.php?page=cart') ?>" class="profile-dropdown-item">Keranjang</a>
                                <a href="<?= base_url('index.php?page=review_form') ?>" class="profile-dropdown-item">Review</a>
                                <a href="<?= base_url('index.php?page=upload_payment') ?>" class="profile-dropdown-item">Pembayaran</a>
                            <?php elseif ($userRole === 'provider'): ?>
                                <a href="<?= base_url('index.php?page=provider_services') ?>" class="profile-dropdown-item">Produk</a>
                                <a href="<?= base_url('index.php?page=provider_orders') ?>" class="profile-dropdown-item">Pesanan</a>
                                <a href="<?= base_url('index.php?page=provider_shipping') ?>" class="profile-dropdown-item">Pengiriman</a>
                                <a href="<?= base_url('index.php?page=provider_reviews') ?>" class="profile-dropdown-item">Ulasan</a>
                            <?php endif; ?>
                            <hr class="my-2">
                            <a href="#" class="profile-dropdown-item" data-bs-toggle="modal" data-bs-target="#profileSettingsModal">Pengaturan Profil</a>
                            <a href="<?= base_url('index.php?page=auth&action=logout') ?>" class="profile-dropdown-item text-danger">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <button type="button" class="btn btn-outline-custom btn-login" onclick="openAuthModal('login')">Masuk</button>
                    <button type="button" class="btn btn-primary-custom btn-register" onclick="openAuthModal('register')">Daftar</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<div class="modal fade" id="profileSettingsModal" tabindex="-1" aria-labelledby="profileSettingsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.1);">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title" id="profileSettingsLabel">Pengaturan Profil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="profileSettingsForm" method="post" action="<?= base_url('index.php?page=' . $currentPage . '&profile_update=1') ?>" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-3 text-center">
                            <div class="profile-photo-preview" id="profilePhotoPreview" data-initial="<?= e($userInitial) ?>" style="margin: 0 auto;"><?= e($userInitial) ?></div>
                            <input class="form-control form-control-sm mt-2" type="file" id="profilePhotoInput" name="profile_photo" accept="image/*">
                            <small class="text-muted d-block mt-2">Hanya preview di browser</small>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label">Nama depan</label>
                                    <input class="form-control" type="text" name="first_name" value="<?= htmlspecialchars($firstNameValue, ENT_QUOTES) ?>" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Nama belakang</label>
                                    <input class="form-control" type="text" name="last_name" value="<?= htmlspecialchars($lastNameValue, ENT_QUOTES) ?>">
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="form-label">Email</label>
                                <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '', ENT_QUOTES) ?>" required>
                            </div>
                            <div class="mt-2">
                                <label class="form-label">Telepon</label>
                                <input class="form-control" type="tel" name="phone" value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '', ENT_QUOTES) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="address" rows="2"><?= htmlspecialchars($_SESSION['user']['address'] ?? '', ENT_QUOTES) ?></textarea>
                    </div>
                    <hr>
                    <h6 class="mb-3">Ubah Password</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Password lama</label>
                            <input class="form-control" type="password" name="current_password" placeholder="Masukkan password lama">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password baru</label>
                            <input class="form-control" type="password" name="new_password" placeholder="Min. 8 karakter">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ulangi password baru</label>
                        <input class="form-control" type="password" name="confirm_password" placeholder="Ulangi password baru">
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if ($isLoggedIn && $page !== 'home'): ?>
    <aside class="role-sidebar" aria-label="Navigasi fitur <?= e($roleLabel) ?>">
        <div class="role-sidebar-brand">
            <span><?= e($roleShortLabel) ?></span>
            <strong>Menu</strong>
        </div>
        <nav class="role-sidebar-nav">
            <?php foreach ($activeSideNav as $index => $item): ?>
                <?php $isActiveSideNav = strpos($item['href'], 'page=' . $currentPage) !== false || ($currentPage === 'dashboard' && $index === 0); ?>
                <a class="<?= $isActiveSideNav ? 'active' : '' ?>" href="<?= e($item['href']) ?>">
                    <i class="bi <?= e($item['icon']) ?>"></i>
                    <span><?= e($item['label']) ?></span>
                    <?php if (isset($item['badge'])): ?><em><?= e($item['badge']) ?></em><?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>
<?php endif; ?>
