<?php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/PaymentModel.php';

$orderModel = new OrderModel($pdo);
$paymentModel = new PaymentModel($pdo);

$orders = $orderModel->getAllWithServices();
$pendingPayments = $paymentModel->getPending();
$statusOptions = ['waiting_payment', 'paid', 'accepted', 'in_progress', 'completed', 'cancelled'];
?>

<main class="admin-dashboard">
    <div class="container">
        <?php include __DIR__ . '/_alert.php'; ?>



        <section class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>Semua Pesanan</h2>
                    <p>Pantau dan override seluruh transaksi di platform.</p>
                </div>
                <div class="d-flex gap-3 align-items-center">
                    <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Cari pesanan..." style="width: 200px;">
                    <a href="<?= base_url('index.php?page=dashboard') ?>" class="link-accent">Kembali</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pembeli</th>
                            <th>Penyedia</th>
                            <th>Jasa</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Override</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <?php [$statusLabel, $statusClass] = order_status_info($order['status']); ?>
                                <tr>
                                    <td><strong><?= e($order['order_number']) ?></strong></td>
                                    <td><?= e($order['buyer_name']) ?></td>
                                    <td><?= e($order['provider_name']) ?></td>
                                    <td><?= e($order['service_title'] ?? '-') ?></td>
                                    <td><span class="status-badge <?= e($statusClass) ?>"><?= e($statusLabel) ?></span></td>
                                    <td class="text-end"><?= e(format_rupiah($order['total_price'])) ?></td>
                                    <td class="text-end">
                                        <form method="post" action="<?= base_url('index.php?page=admin&action=order_status') ?>" class="admin-status-form">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                                            <select name="status" class="form-select form-select-sm">
                                                <?php foreach ($statusOptions as $opt): ?>
                                                    <option value="<?= e($opt) ?>" <?= $order['status'] === $opt ? 'selected' : '' ?>><?= e(order_status_info($opt)[0]) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-custom">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<script>
document.getElementById('tableSearch')?.addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.admin-table tbody tr').forEach(row => {
        if (row.cells.length === 1) return;
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
});
</script>
