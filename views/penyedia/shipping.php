<?php
require_once __DIR__ . '/../../models/OrderModel.php';

$orderModel = new OrderModel($pdo);
$orders = array_filter(
    $orderModel->getByProvider($_SESSION['user']['id']),
    fn($o) => in_array($o['status'], ['accepted', 'in_progress', 'completed'], true)
);

$statusMap = [
    'accepted' => ['Diterima', 'primary'],
    'in_progress' => ['Proses', 'warning'],
    'completed' => ['Selesai', 'success'],
];
?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Pengiriman / Pekerjaan</h2>
        <span class="badge bg-dark rounded-pill fs-6"><?= count($orders) ?> pekerjaan</span>
    </div>

    <?php if (empty($orders)): ?>
        <div class="text-center py-5">
            <i class="bi bi-send fs-1 text-muted"></i>
            <p class="text-muted mt-2">Belum ada pekerjaan yang sedang diproses.</p>
        </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pembeli</th>
                    <th>Tanggal Jasa</th>
                    <th>Alamat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
                <?php $s = $statusMap[$o['status']] ?? ['Unknown', 'secondary']; ?>
                <tr>
                    <td><strong><?= e($o['order_number']) ?></strong></td>
                    <td><?= e($o['buyer_name']) ?></td>
                    <td><?= date('d M Y', strtotime($o['service_date'])) ?></td>
                    <td><?= e(mb_strimwidth($o['service_address'], 0, 42, '...')) ?></td>
                    <td><span class="badge bg-<?= $s[1] ?>"><?= $s[0] ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
