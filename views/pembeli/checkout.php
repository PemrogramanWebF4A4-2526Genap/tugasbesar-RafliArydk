<?php
require_once __DIR__ . '/../../models/ServiceModel.php';

$serviceModel = new ServiceModel($pdo);
$cartItems = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $serviceId => $quantity) {
        $service = $serviceModel->getById((int) $serviceId);
        if ($service) {
            $subTotal = $service['price'] * (int) $quantity;
            $total += $subTotal;
            $cartItems[] = [
                'service' => $service,
                'quantity' => (int) $quantity,
                'sub_total' => $subTotal,
            ];
        }
    }
}

$userAddress = $_SESSION['user']['address'] ?? '';
$errorMessage = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'missing_fields') {
        $errorMessage = 'Lengkapi tanggal pelaksanaan dan alamat pelaksanaan.';
    } elseif ($_GET['error'] === 'empty') {
        $errorMessage = 'Keranjang kosong. Tambahkan jasa sebelum checkout.';
    }
}
?>

<main class="checkout-page">
    <div class="container">
        <h2>Checkout</h2>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= e($errorMessage) ?></div>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="alert alert-info">Keranjang Anda masih kosong. Tambahkan jasa terlebih dahulu.</div>
            <a href="<?= base_url('index.php?page=home') ?>" class="btn btn-primary-custom">Kembali ke Beranda</a>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <form method="post" action="<?= base_url('index.php?page=checkout') ?>">
                        <div class="checkout-card">
                            <div class="card-body">
                                <div class="checkout-section mb-4">
                                    <div class="row g-3 align-items-start">
                                        <div class="col-12 col-md-8">
                                            <div class="checkout-section-title">Alamat Pelaksanaan</div>
                                            <textarea name="service_address" class="form-control" rows="3" required><?= e($userAddress) ?></textarea>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="checkout-section-title">Tanggal Pelaksanaan</div>
                                            <input type="date" name="service_date" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="checkout-section mb-4">
                                    <div class="checkout-section-title">Catatan Tambahan</div>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="Beri instruksi atau informasi tambahan untuk penyedia."></textarea>
                                </div>

                                <div class="checkout-section mb-4">
                                    <div class="checkout-section-title">Metode Pembayaran</div>
                                    <div class="d-flex flex-column gap-2 mt-2">
                                        <label class="form-check-label">
                                            <input class="form-check-input me-2" type="radio" name="payment_method" value="bank_transfer" checked>
                                            Transfer Bank
                                        </label>
                                        <label class="form-check-label">
                                            <input class="form-check-input me-2" type="radio" name="payment_method" value="cod">
                                            Cash on Delivery (COD)
                                        </label>
                                    </div>
                                    <p class="small text-muted mt-2">Pilih COD jika ingin bayar saat jasa selesai. Untuk transfer bank, unggah bukti pembayaran setelah membuat pesanan.</p>
                                </div>

                                <button type="submit" class="btn btn-primary-custom w-100">Buat Pesanan</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4">
                    <div class="payment-summary-card p-4 rounded-4 shadow-sm">
                        <h6 class="summary-title mb-3">Ringkasan Pesanan</h6>
                        <?php foreach ($cartItems as $item): ?>
                            <div class="summary-row mb-3">
                                <div>
                                    <strong><?= e($item['service']['title']) ?></strong><br>
                                    <small class="text-muted"><?= e($item['service']['provider_name']) ?></small>
                                </div>
                                <div class="text-end">
                                    <?= e($item['quantity']) ?> x <?= e(format_rupiah($item['service']['price'])) ?><br>
                                    <small class="text-muted"><?= e(format_rupiah($item['sub_total'])) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="summary-row fw-bold">
                            <span>Total Harga</span>
                            <strong><?= e(format_rupiah($total)) ?></strong>
                        </div>
                        <p class="small text-muted mt-3">Total sudah termasuk semua item dalam keranjang Anda.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
