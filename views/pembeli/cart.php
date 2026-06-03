<?php
$cartItems = [
    [
        'provider' => 'Budi Clean',
        'title' => 'Jasa Bersih Rumah Profesional',
        'variant' => 'Rumah 2 kamar - Bandung',
        'price' => 150000,
        'old_price' => 190000,
        'discount' => '21%',
        'icon' => 'bi-brush',
    ],
    [
        'provider' => 'Sari Edukasi',
        'title' => 'Les Matematika SD-SMP privat datang ke rumah',
        'variant' => '2 jam - Jakarta Selatan',
        'price' => 150000,
        'old_price' => 180000,
        'discount' => '17%',
        'icon' => 'bi-calculator',
    ],
    [
        'provider' => 'Arif Teknik',
        'title' => 'Servis AC & Kulkas Rumahan',
        'variant' => 'Pemeriksaan 1 unit - Surabaya',
        'price' => 200000,
        'old_price' => 250000,
        'discount' => '20%',
        'icon' => 'bi-tools',
    ],
];
$total = array_sum(array_column($cartItems, 'price'));
?>

<main class="cart-page">
    <div class="container">
        <h2 class="cart-title">Keranjang</h2>
        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                <section class="cart-panel cart-select-all">
                    <label class="cart-check">
                        <input type="checkbox" aria-label="Pilih semua jasa">
                        <span>Pilih Semua (<?= count($cartItems) ?>)</span>
                    </label>
                </section>

                <?php foreach ($cartItems as $item): ?>
                    <section class="cart-panel cart-shop">
                        <label class="cart-check cart-shop-name">
                            <input type="checkbox" checked aria-label="Pilih penyedia <?= e($item['provider']) ?>">
                            <span><?= e($item['provider']) ?></span>
                        </label>
                        <div class="cart-product">
                            <label class="cart-check cart-product-check">
                                <input type="checkbox" aria-label="Pilih <?= e($item['title']) ?>">
                            </label>
                            <div class="cart-product-thumb">
                                <span class="cart-discount"><?= e($item['discount']) ?></span>
                                <i class="bi <?= e($item['icon']) ?>"></i>
                            </div>
                            <div class="cart-product-info">
                                <h3><?= e($item['title']) ?></h3>
                                <p><?= e($item['variant']) ?></p>
                                <div class="cart-actions">
                                    <button type="button" aria-label="Tambah ke favorit"><i class="bi bi-heart"></i></button>
                                    <button type="button" aria-label="Hapus jasa"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            <div class="cart-product-price">
                                <strong>Rp<?= number_format($item['price'], 0, ',', '.') ?></strong>
                                <span>Rp<?= number_format($item['old_price'], 0, ',', '.') ?></span>
                                <div class="cart-qty" aria-label="Jumlah jasa">
                                    <button type="button" aria-label="Kurangi jumlah">-</button>
                                    <span>1</span>
                                    <button type="button" aria-label="Tambah jumlah">+</button>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>

            <aside class="col-lg-4">
                <section class="cart-summary">
                    <h3>Ringkasan belanja</h3>
                    <div class="cart-summary-row">
                        <span>Total</span>
                        <strong>Rp<?= number_format($total, 0, ',', '.') ?></strong>
                    </div>
                    <button type="button" class="cart-promo" data-toast="Pilih jasa terlebih dahulu sebelum memakai promo" data-toast-type="info">
                        <span><i class="bi bi-ticket-perforated-fill"></i> Pakai promo</span>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <button type="button" class="btn btn-primary-custom w-100 cart-buy-btn" data-toast="Checkout segera tersedia" data-toast-type="info">Beli</button>
                </section>
            </aside>
        </div>
    </div>
</main>
