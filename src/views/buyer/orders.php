<?php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/InvoiceModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';
require_once __DIR__ . '/../../models/PaymentModel.php';

$orderModel = new OrderModel($pdo);
$invoiceModel = new InvoiceModel($pdo);
$reviewModel = new ReviewModel($pdo);
$paymentModel = new PaymentModel($pdo);
$orders = $orderModel->getByBuyer($_SESSION['user']['id']);
$invoiceAllowedStatuses = ['paid', 'accepted', 'in_progress', 'completed'];
?>

<div class="container">
    <h2 class="fw-bold mb-4">Pesanan Saya</h2>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Penyedia</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <?php [$statusLabel, $statusClass] = order_status_info($order['status']); ?>
                        <?php $invoice = $invoiceModel->getByOrderId($order['id']); ?>
                        <?php $hasPendingPayment = $paymentModel->hasPendingForOrder($order['id']); ?>
                        <tr>
                            <td><strong><?= e($order['order_number']) ?></strong></td>
                            <td><?= e($order['provider_name']) ?></td>
                            <td><?= e(date('d M Y', strtotime($order['service_date']))) ?></td>
                            <td><?= e(format_rupiah($order['total_price'])) ?></td>
                            <td><span class="status-badge <?= e($statusClass) ?>"><?= e($statusLabel) ?></span></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a href="<?= base_url('index.php?page=order_detail&id=' . (int) $order['id']) ?>" class="btn btn-sm btn-outline-custom">Detail</a>
                                <?php if ($order['status'] === 'waiting_payment' && !$hasPendingPayment): ?>
                                    <a href="<?= base_url('index.php?page=upload_payment&id=' . (int) $order['id']) ?>" class="btn btn-sm btn-primary-custom">Upload Bayar</a>
                                <?php elseif ($order['status'] === 'waiting_payment' && $hasPendingPayment): ?>
                                    <span class="btn btn-sm btn-outline-secondary disabled">Menunggu Verifikasi</span>
                                <?php elseif ($order['status'] === 'completed' && !$reviewModel->checkExists($order['id'])): ?>
                                    <a href="<?= base_url('index.php?page=review_form&order_id=' . (int) $order['id']) ?>" class="btn btn-sm btn-outline-custom">Beri Review</a>
                                <?php endif; ?>
                                <?php if ($invoice || in_array($order['status'], $invoiceAllowedStatuses, true)): ?>
                                    <a href="<?= base_url('index.php?page=invoice&id=' . (int) $order['id']) ?>" class="btn btn-sm btn-outline-custom">Invoice</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
