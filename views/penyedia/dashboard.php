<?php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';

$orderModel = new OrderModel($pdo);
$reviewModel = new ReviewModel($pdo);

$providerId = $_SESSION['user']['id'];
$providerName = $_SESSION['user']['name'] ?? 'Penyedia';

$orders = $orderModel->getByProvider($providerId);
$totalOrders = count($orders);
$completedOrders = count(array_filter($orders, fn($o) => $o['status'] === 'completed'));
$totalEarnings = array_sum(array_map(fn($o) => $o['status'] === 'completed' ? $o['total_price'] : 0, $orders));
$ratingData = $reviewModel->getAverageRatingByProvider($providerId);
$avgRating = $ratingData['avg_rating'] ? number_format($ratingData['avg_rating'], 1) : '0.0';

$recentOrders = array_slice($orders, 0, 5);
?>
<div class="container">
    <h2 class="fw-bold">Dashboard Penyedia</h2>
    <p>Selamat datang, <strong><?= e($providerName) ?></strong></p>
    <div class="row mt-4 g-3">
        <div class="col-md-3"><div class="card text-center p-3 border-0 shadow-sm rounded-4"><h3><?= $totalOrders ?></h3><p class="text-muted">Pesanan Masuk</p></div></div>
        <div class="col-md-3"><div class="card text-center p-3 border-0 shadow-sm rounded-4"><h3><?= $completedOrders ?></h3><p class="text-muted">Pesanan Selesai</p></div></div>
        <div class="col-md-3"><div class="card text-center p-3 border-0 shadow-sm rounded-4"><h3>Rp <?= number_format($totalEarnings, 0, ',', '.') ?></h3><p class="text-muted">Pendapatan</p></div></div>
        <div class="col-md-3"><div class="card text-center p-3 border-0 shadow-sm rounded-4"><h3><?= $avgRating ?></h3><p class="text-muted">Rating Rata-rata</p></div></div>
    </div>
    <div class="mt-5">
        <h4>Pesanan Terbaru</h4>
        <?php if (empty($recentOrders)): ?>
            <p class="text-muted">Belum ada pesanan.</p>
        <?php else: ?>
        <table class="table">
            <thead><tr><th>No. Pesanan</th><th>Pembeli</th><th>Total</th><th>Tanggal</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($recentOrders as $o): ?>
                <tr>
                    <td><?= e($o['order_number']) ?></td>
                    <td><?= e($o['buyer_name']) ?></td>
                    <td>Rp <?= number_format($o['total_price'], 0, ',', '.') ?></td>
                    <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                    <td>
                        <?php
                        $statusMap = [
                            'pending' => ['Menunggu', 'secondary'],
                            'waiting_payment' => ['Menunggu Bayar', 'warning'],
                            'paid' => ['Dibayar', 'info'],
                            'accepted' => ['Diterima', 'primary'],
                            'in_progress' => ['Proses', 'warning'],
                            'completed' => ['Selesai', 'success'],
                            'cancelled' => ['Dibatalkan', 'danger'],
                        ];
                        $s = $statusMap[$o['status']] ?? ['Unknown', 'secondary'];
                        ?>
                        <span class="badge bg-<?= $s[1] ?>"><?= $s[0] ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
