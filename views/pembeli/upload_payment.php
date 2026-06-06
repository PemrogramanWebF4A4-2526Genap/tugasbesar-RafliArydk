<?php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/PaymentModel.php';

$orderModel = new OrderModel($pdo);
$paymentModel = new PaymentModel($pdo);
$orderId = (int) ($_GET['id'] ?? 0);
$order = $orderModel->getById($orderId);

if (!$order || (int) $order['buyer_id'] !== (int) ($_SESSION['user']['id'] ?? 0)) {
    echo '<div class="container py-5"><div class="alert alert-danger">Pesanan tidak ditemukan.</div></div>';
    return;
}

if ($order['status'] !== 'waiting_payment') {
    echo '<div class="container py-5"><div class="alert alert-warning">Pesanan saat ini tidak memerlukan upload bukti pembayaran.</div><a href="' . base_url('index.php?page=orders') . '" class="btn btn-primary-custom mt-3">Kembali ke Pesanan Saya</a></div>';
    return;
}

$latestPayment = $paymentModel->getByOrderId($orderId);
if ($latestPayment && $latestPayment['status'] === 'pending') {
    echo '<div class="container py-5"><div class="alert alert-info">Bukti pembayaran sudah diunggah dan sedang menunggu verifikasi admin.</div><a href="' . base_url('index.php?page=orders') . '" class="btn btn-primary-custom mt-3">Kembali ke Pesanan Saya</a></div>';
    return;
}

$errorMessage = '';
if (isset($_GET['error']) && $_GET['error'] === 'upload_failed') {
    $errorMessage = 'Gagal mengunggah bukti pembayaran. Pastikan file berformat JPG/PNG dan coba lagi.';
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card rounded-4 shadow-sm p-4">
                <h4 class="text-center mb-3">Upload Bukti Pembayaran</h4>
                <p class="text-center text-muted">Pesanan <strong><?= e($order['order_number']) ?></strong> - Total <?= e(format_rupiah($order['total_price'])) ?></p>
                <?php if ($errorMessage): ?>
                    <div class="alert alert-danger"><?= e($errorMessage) ?></div>
                <?php endif; ?>
                <?php if ($latestPayment && $latestPayment['status'] === 'rejected'): ?>
                    <div class="alert alert-warning">Bukti sebelumnya ditolak<?= $latestPayment['notes'] ? ': ' . e($latestPayment['notes']) : '.' ?></div>
                <?php endif; ?>
                <form method="post" action="<?= base_url('index.php?page=payment&action=upload') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="order_id" value="<?= (int) $orderId ?>">
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" name="method">
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Tunai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pengirim</label>
                        <input type="text" name="sender_name" class="form-control" placeholder="Nama pengirim" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Gambar Bukti</label>
                        <input type="file" name="proof" class="form-control" accept="image/jpeg,image/png" required>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">Kirim Bukti</button>
                </form>
            </div>
        </div>
    </div>
</div>
