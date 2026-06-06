<?php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/InvoiceModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';
require_once __DIR__ . '/../../models/PaymentModel.php';

$orderModel = new OrderModel($pdo);
$invoiceModel = new InvoiceModel($pdo);
$reviewModel = new ReviewModel($pdo);
$paymentModel = new PaymentModel($pdo);
$orderId = (int) ($_GET['id'] ?? 0);
$order = $orderModel->getById($orderId);

if (!$order || (int) $order['buyer_id'] !== (int) $_SESSION['user']['id']) {
    echo '<div class="container py-5"><div class="alert alert-danger">Pesanan tidak ditemukan.</div></div>';
    return;
}

$items = $orderModel->getOrderItems($orderId);
$invoice = $invoiceModel->getByOrderId($orderId);
$hasReview = $reviewModel->checkExists($orderId);
$hasPendingPayment = $paymentModel->hasPendingForOrder($orderId);
$invoiceAllowedStatuses = ['paid', 'accepted', 'in_progress', 'completed'];
$steps = ['waiting_payment', 'paid', 'accepted', 'in_progress', 'completed'];
$currentIndex = array_search($order['status'], $steps, true);
$progress = $currentIndex === false ? 10 : (($currentIndex + 1) / count($steps)) * 100;
?>

<div class="container">
    <h2 class="fw-bold">Detail Pesanan <?= e($order['order_number']) ?></h2>
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card rounded-4 shadow-sm p-4">
                <h5>Status Pesanan</h5>
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-warning" style="width: <?= (int) $progress ?>%"></div>
                </div>
                <ul class="list-unstyled d-flex justify-content-between gap-2 small">
                    <?php foreach ($steps as $step): ?>
                        <?php [$label] = order_status_info($step); ?>
                        <li class="text-center"><?= e($label) ?></li>
                    <?php endforeach; ?>
                </ul>
                <hr>
                <h5>Informasi Pesanan</h5>
                <p><strong>Penyedia:</strong> <?= e($order['provider_name']) ?></p>
                <p><strong>Tanggal:</strong> <?= e(date('d M Y', strtotime($order['service_date']))) ?></p>
                <p><strong>Alamat:</strong> <?= nl2br(e($order['service_address'])) ?></p>
                <p><strong>Catatan:</strong> <?= e($order['notes'] ?: '-') ?></p>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Jasa</th><th>Qty</th><th class="text-end">Harga</th></tr></thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= e($item['title']) ?></td>
                                    <td><?= (int) $item['quantity'] ?></td>
                                    <td class="text-end"><?= e(format_rupiah($item['price_per_unit'] * $item['quantity'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="fs-5"><strong>Total:</strong> <?= e(format_rupiah($order['total_price'])) ?></p>
                <div class="d-flex flex-wrap gap-2 mt-3">
                <?php if ($order['status'] === 'waiting_payment' && !$hasPendingPayment): ?>
                    <a href="<?= base_url('index.php?page=upload_payment&id=' . (int) $order['id']) ?>" class="btn btn-primary-custom rounded-pill">Upload Bukti Pembayaran</a>
                <?php elseif ($order['status'] === 'waiting_payment' && $hasPendingPayment): ?>
                    <span class="btn btn-outline-secondary rounded-pill disabled">Menunggu Verifikasi Pembayaran</span>
                <?php elseif ($order['status'] === 'completed' && !$hasReview): ?>
                    <a href="<?= base_url('index.php?page=review_form&order_id=' . (int) $order['id']) ?>" class="btn btn-outline-custom rounded-pill">Beri Review</a>
                <?php elseif ($order['status'] === 'completed' && $hasReview): ?>
                    <span class="badge bg-success">Review sudah dikirim</span>
                <?php endif; ?>
                <?php if ($invoice || in_array($order['status'], $invoiceAllowedStatuses, true)): ?>
                    <a href="<?= base_url('index.php?page=invoice&id=' . (int) $order['id']) ?>" class="btn btn-outline-custom rounded-pill">Lihat Invoice</a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
