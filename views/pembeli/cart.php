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
?>

<main class="cart-page">
    <div class="container">
        <h2 class="cart-title">Keranjang</h2>
        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                <?php if (empty($cartItems)): ?>
                    <div class="alert alert-info">Keranjang Anda masih kosong. Tambahkan jasa dari beranda.</div>
                    <a href="<?= base_url('index.php?page=home') ?>" class="btn btn-primary-custom">Kembali ke Beranda</a>
                <?php else: ?>
                    <section class="cart-panel cart-select-all mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Ringkasan Keranjang</h5>
                                <small class="text-muted"><?= count($cartItems) ?> item</small>
                            </div>
                            <a href="<?= base_url('index.php?page=cart&action=clear') ?>" class="btn btn-sm btn-outline-danger">Bersihkan Keranjang</a>
                        </div>
                    </section>

                    <?php foreach ($cartItems as $item): ?>
                        <section class="cart-panel cart-shop mb-3">
                            <div class="cart-product">
                                <label class="cart-check cart-product-check">
                                    <input type="checkbox" checked aria-label="Pilih <?= e($item['service']['title']) ?>">
                                </label>
                                <div class="cart-product-thumb">
                                    <i class="bi <?= e(service_icon($item['service']['category_name'])) ?>"></i>
                                </div>
                                <div class="cart-product-info">
                                    <h3><?= e($item['service']['title']) ?></h3>
                                    <p><?= e($item['service']['provider_name']) ?> · <?= e($item['service']['location']) ?></p>
                                    <div class="cart-actions mt-2">
                                        <form method="post" action="<?= base_url('index.php?page=cart&action=update') ?>" class="d-flex gap-2 align-items-center">
                                            <input type="hidden" name="service_id" value="<?= (int) $item['service']['id'] ?>">
                                            <input type="number" name="quantity" min="1" value="<?= (int) $item['quantity'] ?>" class="form-control form-control-sm" style="width: 80px;">
                                            <button type="submit" class="btn btn-sm btn-outline-custom">Update</button>
                                        </form>
                                        <a href="<?= base_url('index.php?page=cart&action=remove&id=' . (int) $item['service']['id']) ?>" class="btn btn-sm btn-outline-danger">Hapus</a>
                                    </div>
                                </div>
                                <div class="cart-product-price text-end">
                                    <strong><?= e(format_rupiah($item['service']['price'])) ?> x <?= (int) $item['quantity'] ?></strong>
                                    <div class="text-muted">Subtotal: <?= e(format_rupiah($item['sub_total'])) ?></div>
                                </div>
                            </div>
                        </section>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <aside class="col-lg-4">
                <section class="cart-summary p-4 rounded-4 shadow-sm">
                    <h3 class="mb-3">Ringkasan Belanja</h3>
                    <div class="cart-summary-row mb-3">
                        <span>Total</span>
                        <strong><?= e(format_rupiah($total)) ?></strong>
                    </div>
                    <div class="text-muted mb-4">Jumlah item: <?= count($cartItems) ?></div>
                    <a href="<?= base_url('index.php?page=checkout') ?>" class="btn btn-primary-custom w-100<?= empty($cartItems) ? ' disabled' : '' ?>">Lanjutkan ke Checkout</a>
                    <?php if (!empty($cartItems)): ?>
                        <p class="small text-muted mt-3">Pastikan alamat dan tanggal pelaksanaan sudah sesuai di halaman checkout.</p>
                    <?php endif; ?>
                </section>
            </aside>
        </div>
    </div>
</main>
