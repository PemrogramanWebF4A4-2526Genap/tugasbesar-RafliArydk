<?php
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/PaymentModel.php';

$userModel = new UserModel($pdo);
$orderModel = new OrderModel($pdo);
$paymentModel = new PaymentModel($pdo);

$adminName = $_SESSION['user']['name'] ?? 'Admin';
$totalUsers = $userModel->countAll();
$buyerCount = $userModel->countByRole('buyer');
$providerCount = $userModel->countByRole('provider');
$ordersThisMonth = $orderModel->countThisMonth();
$pendingVerify = $userModel->countUnverifiedProviders();
$pendingPayments = count($paymentModel->getPending());

$stats = [
    ['label' => 'Total Pengguna', 'value' => (string) $totalUsers, 'icon' => 'bi-people', 'tone' => 'users'],
    ['label' => 'Pembeli Aktif', 'value' => (string) $buyerCount, 'icon' => 'bi-bag-check', 'tone' => 'buyers'],
    ['label' => 'Penyedia Jasa', 'value' => (string) $providerCount, 'icon' => 'bi-briefcase', 'tone' => 'providers'],
    ['label' => 'Pesanan Bulan Ini', 'value' => (string) $ordersThisMonth, 'icon' => 'bi-receipt', 'tone' => 'orders'],
];

$quickMenus = [
    [
        'title' => 'Dashboard Admin',
        'desc' => 'Ringkasan platform: statistik pengguna, pesanan, pendapatan, dan log sistem.',
        'icon' => 'bi-grid',
        'href' => base_url('index.php?page=dashboard'),
        'badge' => null,
    ],
    [
        'title' => 'Manage User',
        'desc' => 'Kelola pembeli dan penyedia, verifikasi akun, dan suspend pengguna.',
        'icon' => 'bi-person-gear',
        'href' => base_url('index.php?page=admin_users'),
        'badge' => null,
    ],
    [
        'title' => 'Verifikasi Penjual',
        'desc' => 'Tinjau dokumen dan setujui atau tolak pendaftaran penyedia jasa.',
        'icon' => 'bi-arrow-repeat',
        'href' => base_url('index.php?page=admin_verify'),
        'badge' => $pendingVerify > 0 ? (string) $pendingVerify : null,
    ],
    [
        'title' => 'Manage Kategori',
        'desc' => 'CRUD kategori jasa: bersih-bersih, tukang, les, dan lainnya.',
        'icon' => 'bi-grid-3x3',
        'href' => base_url('index.php?page=admin_categories'),
        'badge' => null,
    ],
    [
        'title' => 'Semua Pesanan',
        'desc' => 'Pantau dan override seluruh transaksi di platform.',
        'icon' => 'bi-clipboard-check',
        'href' => base_url('index.php?page=admin_orders'),
        'badge' => $pendingPayments > 0 ? (string) $pendingPayments : null,
    ],
    [
        'title' => 'Report dan Analytics',
        'desc' => 'Grafik pendapatan, jasa terlaris, dan pertumbuhan pengguna.',
        'icon' => 'bi-pie-chart',
        'href' => base_url('index.php?page=admin_reports'),
        'badge' => null,
    ],
    [
        'title' => 'System Settings',
        'desc' => 'Atur ongkir, metode pembayaran, dan template email.',
        'icon' => 'bi-gear',
        'href' => base_url('index.php?page=admin_settings'),
        'badge' => null,
    ],
];

$allOrders = $orderModel->getAllWithServices();
$recentOrders = array_slice($allOrders, 0, 5);
?>

<main class="admin-dashboard">
    <div class="container">
        <?php include __DIR__ . '/_alert.php'; ?>

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
                            <p>7 fitur wajib untuk operasional admin.</p>
                        </div>
                    </div>
                    <div class="admin-menu-grid">
                        <?php foreach ($quickMenus as $menu): ?>
                            <a class="admin-menu-card" href="<?= e($menu['href']) ?>">
                                <span><i class="bi <?= e($menu['icon']) ?>"></i></span>
                                <div>
                                    <h3>
                                        <?= e($menu['title']) ?>
                                        <?php if ($menu['badge']): ?>
                                            <em class="admin-wajib-badge"><?= e($menu['badge']) ?></em>
                                        <?php else: ?>
                                            <em class="admin-wajib-badge admin-wajib-label">wajib</em>
                                        <?php endif; ?>
                                    </h3>
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
                        <li><i class="bi bi-check-circle-fill"></i> Manage user pembeli dan penyedia</li>
                        <li><i class="bi bi-check-circle-fill"></i> Verifikasi penjual</li>
                        <li><i class="bi bi-check-circle-fill"></i> Manage kategori jasa</li>
                        <li><i class="bi bi-check-circle-fill"></i> Semua pesanan</li>
                        <li><i class="bi bi-check-circle-fill"></i> Report dan analytics</li>
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
                        <?php if (empty($recentOrders)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentOrders as $order): ?>
                                <?php [$statusLabel, $statusClass] = order_status_info($order['status']); ?>
                                <tr>
                                    <td><strong><?= e($order['order_number']) ?></strong></td>
                                    <td><?= e($order['buyer_name']) ?></td>
                                    <td><?= e($order['service_title'] ?? '-') ?></td>
                                    <td><span class="status-badge <?= e($statusClass) ?>"><?= e($statusLabel) ?></span></td>
                                    <td class="text-end"><?= e(format_rupiah($order['total_price'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
