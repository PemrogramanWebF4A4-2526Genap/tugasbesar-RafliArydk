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
                'service'   => $service,
                'quantity'  => (int) $quantity,
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
                            <form method="post" action="<?= base_url('index.php?page=cart&action=clear') ?>" class="m-0">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">Bersihkan Keranjang</button>
                            </form>
                        </div>
                    </section>

                    <?php foreach ($cartItems as $item): ?>
                        <section class="cart-panel cart-shop mb-3" id="cart-item-<?= (int) $item['service']['id'] ?>">
                            <div class="cart-product">
                                <label class="cart-check cart-product-check">
                                    <input type="checkbox" checked aria-label="Pilih <?= e($item['service']['title']) ?>">
                                </label>
                                <div class="cart-product-thumb">
                                    <i class="bi <?= e(category_icon($item['service']['category_name'])) ?>"></i>
                                </div>
                                <div class="cart-product-info">
                                    <h3><?= e($item['service']['title']) ?></h3>
                                    <p><?= e($item['service']['provider_name']) ?> · <?= e($item['service']['location']) ?></p>
                                    <div class="cart-actions mt-2 d-flex gap-2 align-items-center">
                                        <div class="input-group input-group-sm" style="width:120px;">
                                            <button class="btn btn-outline-secondary cart-qty-btn" type="button"
                                                data-service-id="<?= (int) $item['service']['id'] ?>"
                                                data-action="down">−</button>
                                            <input type="number"
                                                class="form-control text-center px-1 cart-qty-input"
                                                min="1"
                                                value="<?= (int) $item['quantity'] ?>"
                                                data-service-id="<?= (int) $item['service']['id'] ?>"
                                                data-price="<?= (int) $item['service']['price'] ?>">
                                            <button class="btn btn-outline-secondary cart-qty-btn" type="button"
                                                data-service-id="<?= (int) $item['service']['id'] ?>"
                                                data-action="up">+</button>
                                        </div>
                                        <form method="post" action="<?= base_url('index.php?page=cart&action=remove') ?>" class="m-0">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= (int) $item['service']['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                        <span class="text-muted small" id="saving-<?= (int) $item['service']['id'] ?>" style="display:none;">
                                            <span class="spinner-border spinner-border-sm"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="cart-product-price text-end" id="price-box-<?= (int) $item['service']['id'] ?>">
                                    <strong class="cart-price-line">
                                        <?= e(format_rupiah($item['service']['price'])) ?> x <span class="qty-display"><?= (int) $item['quantity'] ?></span>
                                    </strong>
                                    <div class="text-muted">Subtotal: <span class="subtotal-display"><?= e(format_rupiah($item['sub_total'])) ?></span></div>
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
                        <strong id="cart-total-display"><?= e(format_rupiah($total)) ?></strong>
                    </div>
                    <div class="text-muted mb-4">Jumlah item: <span id="cart-summary-count"><?= count($cartItems) ?></span></div>
                    <a href="<?= base_url('index.php?page=checkout') ?>" class="btn btn-primary-custom w-100<?= empty($cartItems) ? ' disabled' : '' ?>">Lanjutkan ke Checkout</a>
                    <?php if (!empty($cartItems)): ?>
                        <p class="small text-muted mt-3">Pastikan alamat dan tanggal pelaksanaan sudah sesuai di halaman checkout.</p>
                    <?php endif; ?>
                </section>
            </aside>
        </div>
    </div>
</main>

<script>
(function () {
    var cartUpdateUrl = '<?= base_url('index.php?page=cart&action=update') ?>';
    var csrfToken = '<?= e(csrf_token()) ?>';

    function formatRupiah(num) {
        return 'Rp' + Number(num).toLocaleString('id-ID');
    }

    function recalcTotal() {
        var total = 0;
        document.querySelectorAll('.cart-qty-input').forEach(function (input) {
            var price = parseInt(input.dataset.price) || 0;
            var qty   = parseInt(input.value) || 1;
            total += price * qty;
        });
        var el = document.getElementById('cart-total-display');
        if (el) el.textContent = formatRupiah(total);
    }

    function updateQtyOnServer(serviceId, quantity, input) {
        var badge = document.getElementById('saving-' + serviceId);
        if (badge) badge.style.display = '';

        var formData = new FormData();
        formData.append('csrf_token', csrfToken);
        formData.append('service_id', serviceId);
        formData.append('quantity', quantity);

        fetch(cartUpdateUrl, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data && !data.success && input) {
                input.value = data.old_qty || 1;
                recalcTotal();
            }
        })
        .catch(function () {})
        .finally(function () {
            if (badge) badge.style.display = 'none';
        });
    }

    function debounce(fn, ms) {
        var timer;
        return function () {
            var args = arguments, ctx = this;
            clearTimeout(timer);
            timer = setTimeout(function () { fn.apply(ctx, args); }, ms);
        };
    }

    function applyQtyChange(serviceId, qty, input) {
        qty = Math.max(1, qty);
        input.value = qty;

        var priceBox = document.getElementById('price-box-' + serviceId);
        if (priceBox) {
            var price = parseInt(input.dataset.price) || 0;
            var qtyEl = priceBox.querySelector('.qty-display');
            var subEl = priceBox.querySelector('.subtotal-display');
            if (qtyEl) qtyEl.textContent = qty;
            if (subEl) subEl.textContent = formatRupiah(price * qty);
        }
        recalcTotal();
        updateQtyOnServer(serviceId, qty, input);
    }

    // +/- buttons
    document.querySelectorAll('.cart-qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var sid   = btn.dataset.serviceId;
            var input = document.querySelector('.cart-qty-input[data-service-id="' + sid + '"]');
            if (!input) return;
            var qty = parseInt(input.value) || 1;
            qty = btn.dataset.action === 'up' ? qty + 1 : qty - 1;
            applyQtyChange(sid, qty, input);
        });
    });

    // Manual number input (debounced)
    document.querySelectorAll('.cart-qty-input').forEach(function (input) {
        var sid = input.dataset.serviceId;
        var debouncedSave = debounce(function () {
            applyQtyChange(sid, parseInt(input.value) || 1, input);
        }, 600);
        input.addEventListener('input', debouncedSave);
        input.addEventListener('change', function () {
            applyQtyChange(sid, parseInt(input.value) || 1, input);
        });
    });
})();
</script>
