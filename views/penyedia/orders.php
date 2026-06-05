<?php
require_once __DIR__ . '/../../models/OrderModel.php';

$orderModel = new OrderModel($pdo);
$orders = $orderModel->getByProvider($_SESSION['user']['id']);

$statusMap = [
    'pending' => ['Menunggu', 'secondary'],
    'waiting_payment' => ['Menunggu Bayar', 'warning'],
    'paid' => ['Dibayar', 'info'],
    'accepted' => ['Diterima', 'primary'],
    'in_progress' => ['Proses', 'warning'],
    'completed' => ['Selesai', 'success'],
    'cancelled' => ['Dibatalkan', 'danger'],
];
?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Pesanan Masuk</h2>
        <span class="badge bg-dark rounded-pill fs-6"><?= count($orders) ?> pesanan</span>
    </div>

    <?php if (empty($orders)): ?>
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="text-muted mt-2">Belum ada pesanan masuk.</p>
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
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
                <?php $s = $statusMap[$o['status']] ?? ['Unknown', 'secondary']; ?>
                <tr>
                    <td><strong><?= e($o['order_number']) ?></strong></td>
                    <td><?= e($o['buyer_name']) ?></td>
                    <td><?= date('d M Y', strtotime($o['service_date'])) ?></td>
                    <td><?= e(mb_strimwidth($o['service_address'], 0, 30, '...')) ?></td>
                    <td>Rp <?= number_format($o['total_price'], 0, ',', '.') ?></td>
                    <td><span class="badge bg-<?= $s[1] ?>"><?= $s[0] ?></span></td>
                    <td class="text-center">
                        <?php if (in_array($o['status'], ['paid', 'accepted'])): ?>
                        <form method="POST" action="<?= base_url('index.php?page=order&action=update_status') ?>" style="display:inline">
                            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                            <?php if ($o['status'] === 'paid'): ?>
                                <button type="submit" name="status" value="accepted" class="btn btn-sm btn-outline-primary rounded-pill">Terima</button>
                            <?php elseif ($o['status'] === 'accepted'): ?>
                                <button type="submit" name="status" value="in_progress" class="btn btn-sm btn-outline-warning rounded-pill">Mulai Kerja</button>
                                <button type="submit" name="status" value="completed" class="btn btn-sm btn-outline-success rounded-pill">Selesai</button>
                            <?php endif; ?>
                        </form>
                        <?php elseif ($o['status'] === 'in_progress'): ?>
                        <form method="POST" action="<?= base_url('index.php?page=order&action=update_status') ?>" style="display:inline">
                            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                            <button type="submit" name="status" value="completed" class="btn btn-sm btn-outline-success rounded-pill">Selesai</button>
                        </form>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
