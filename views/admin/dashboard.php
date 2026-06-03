<?php
$adminName = $_SESSION['user']['name'] ?? 'Admin';
$stats = [
    ['label' => 'Total Pengguna', 'value' => '45', 'icon' => 'bi-people', 'tone' => 'users'],
    ['label' => 'Pembeli Aktif', 'value' => '28', 'icon' => 'bi-bag-check', 'tone' => 'buyers'],
    ['label' => 'Penyedia Jasa', 'value' => '12', 'icon' => 'bi-briefcase', 'tone' => 'providers'],
    ['label' => 'Pesanan Bulan Ini', 'value' => '86', 'icon' => 'bi-receipt', 'tone' => 'orders'],
];

$quickMenus = [
    [
        'title' => 'Manage User',
        'desc' => 'Kelola akun pembeli, penyedia, verifikasi, dan status pengguna.',
        'icon' => 'bi-person-gear',
        'href' => base_url('index.php?page=admin_users'),
    ],
    [
        'title' => 'Manage Kategori Produk',
        'desc' => 'Tambah, ubah, atau nonaktifkan kategori jasa yang tampil di marketplace.',
        'icon' => 'bi-grid',
        'href' => base_url('index.php?page=admin_categories'),
    ],
    [
        'title' => 'Manage Pesanan',
        'desc' => 'Pantau semua pesanan, pembayaran, status pekerjaan, dan komplain.',
        'icon' => 'bi-list-check',
        'href' => base_url('index.php?page=admin_orders'),
    ],
    [
        'title' => 'Report & Analytics',
        'desc' => 'Lihat performa transaksi, pertumbuhan pengguna, dan pendapatan platform.',
        'icon' => 'bi-bar-chart-line',
        'href' => base_url('index.php?page=admin_reports'),
    ],
    [
        'title' => 'System Settings',
        'desc' => 'Atur konfigurasi platform, biaya admin, notifikasi, dan kebijakan sistem.',
        'icon' => 'bi-sliders',
        'href' => base_url('index.php?page=admin_settings'),
    ],
];

$recentOrders = [
    ['code' => 'ORD-2401', 'buyer' => 'Andi Setiawan', 'service' => 'Jasa Bersih Rumah', 'status' => 'Diproses', 'amount' => 'Rp150.000'],
    ['code' => 'ORD-2402', 'buyer' => 'Nita Permata', 'service' => 'Les Matematika', 'status' => 'Selesai', 'amount' => 'Rp75.000'],
    ['code' => 'ORD-2403', 'buyer' => 'Maya Andriani', 'service' => 'Servis AC', 'status' => 'Menunggu', 'amount' => 'Rp200.000'],
];
?>

<main class="admin-dashboard">
    <div class="container">
        <section class="admin-hero">
            <div>
                <p class="admin-eyebrow">Dashboard Admin</p>
                <h1>Halo, <?= e($adminName) ?></h1>
                <p>Admin memiliki akses penuh untuk mengelola seluruh sistem BisaBantu.</p>
            </div>
            <div class="admin-hero-actions">
                <a href="<?= base_url('index.php?page=admin_reports') ?>" class="btn btn-outline-custom admin-action-btn">
                    <i class="bi bi-file-earmark-bar-graph"></i> Report
                </a>
                <a href="<?= base_url('index.php?page=admin_settings') ?>" class="btn btn-primary-custom admin-action-btn">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </div>
        </section>

        <section class="row g-3 mb-4">
            <?php foreach ($stats as $stat): ?>
                <div class="col-sm-6 col-xl-3">
                    <div class="admin-stat-card admin-stat-<?= e($stat['tone']) ?>">
                        <span><i class="bi <?= e($stat['icon']) ?>"></i></span>
                        <div>
                            <strong><?= e($stat['value']) ?></strong>
                            <p><?= e($stat['label']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <div class="row g-4">
            <div class="col-lg-8">
                <section class="admin-panel">
                    <div class="admin-panel-head">
                        <div>
                            <h2>Menu Pengelolaan</h2>
                            <p>Akses utama untuk operasional admin.</p>
                        </div>
                    </div>
                    <div class="admin-menu-grid">
                        <?php foreach ($quickMenus as $menu): ?>
                            <a class="admin-menu-card" href="<?= e($menu['href']) ?>">
                                <span><i class="bi <?= e($menu['icon']) ?>"></i></span>
                                <div>
                                    <h3><?= e($menu['title']) ?></h3>
                                    <p><?= e($menu['desc']) ?></p>
                                </div>
                                <i class="bi bi-chevron-right admin-menu-arrow"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>

            <aside class="col-lg-4">
                <section class="admin-panel admin-access-panel">
                    <h2>Akses Admin</h2>
                    <ul>
                        <li><i class="bi bi-check-circle-fill"></i> Dashboard admin</li>
                        <li><i class="bi bi-check-circle-fill"></i> Manage user pembeli & penyedia</li>
                        <li><i class="bi bi-check-circle-fill"></i> Manage kategori produk</li>
                        <li><i class="bi bi-check-circle-fill"></i> Manage semua pesanan</li>
                        <li><i class="bi bi-check-circle-fill"></i> Report & analytics</li>
                        <li><i class="bi bi-check-circle-fill"></i> System settings</li>
                    </ul>
                </section>
            </aside>
        </div>

        <section class="admin-panel mt-4">
            <div class="admin-panel-head">
                <div>
                    <h2>Pesanan Terbaru</h2>
                    <p>Ringkasan aktivitas transaksi yang perlu dipantau.</p>
                </div>
                <a href="<?= base_url('index.php?page=admin_orders') ?>" class="link-accent">Lihat semua</a>
            </div>
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pembeli</th>
                            <th>Jasa</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><strong><?= e($order['code']) ?></strong></td>
                                <td><?= e($order['buyer']) ?></td>
                                <td><?= e($order['service']) ?></td>
                                <td><span class="status-badge active"><?= e($order['status']) ?></span></td>
                                <td class="text-end"><?= e($order['amount']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
<?php include __DIR__ . '/../layout/_profile_settings_modal.php'; ?>
