<?php
require_once __DIR__ . '/../../models/OrderModel.php';

$orderModel = new OrderModel($pdo);
$orders = $orderModel->getByBuyer($_SESSION['user']['id']);
$activeOrders = array_filter($orders, fn($o) => in_array($o['status'], ['paid', 'accepted', 'in_progress'], true));
$completedOrders = array_filter($orders, fn($o) => $o['status'] === 'completed');
$waitingPaymentOrders = array_filter($orders, fn($o) => $o['status'] === 'waiting_payment');
$latestOrders = array_slice($orders, 0, 4);
?>

<div class="container mt-4">
    <h2 class="fw-bold">Dashboard</h2>
    <p>Selamat datang, <strong><?= e($_SESSION['user']['name']) ?></strong></p>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center">
                    <i class="bi bi-bag-check fs-1" style="color: var(--orange-primary);"></i>
                    <h3><?= count($activeOrders) ?></h3>
                    <p class="text-muted">Pesanan Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center">
                    <i class="bi bi-star-fill fs-1" style="color: var(--orange-primary);"></i>
                    <h3><?= count($completedOrders) ?></h3>
                    <p class="text-muted">Pesanan Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history fs-1" style="color: var(--orange-primary);"></i>
                    <h3><?= count($waitingPaymentOrders) ?></h3>
                    <p class="text-muted">Menunggu Pembayaran</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Pesanan Terbaru</h4>
            <a href="<?= base_url('index.php?page=orders') ?>" class="text-decoration-none">Lihat Semua</a>
        </div>

        <?php if (empty($latestOrders)): ?>
            <div class="alert alert-info">Belum ada pesanan.</div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($latestOrders as $order): ?>
                    <a href="<?= base_url('index.php?page=order_detail&id=' . (int) $order['id']) ?>" class="list-group-item list-group-item-action rounded-3 mb-2 border">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= e($order['order_number']) ?></strong><br>
                                <small class="text-muted"><?= e($order['provider_name']) ?> · <?= e(date('d M Y', strtotime($order['service_date']))) ?></small>
                            </div>
                            <span class="badge bg-secondary"><?= e(order_status_info($order['status'])[0]) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
