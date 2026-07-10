<?php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';

$orderModel = new OrderModel($pdo);
$reviewModel = new ReviewModel($pdo);
$orderId = (int) ($_GET['order_id'] ?? 0);
$order = $orderModel->getById($orderId);

if (!$order || (int) $order['buyer_id'] !== (int) ($_SESSION['user']['id'] ?? 0)) {
    echo '<div class="container py-5"><div class="alert alert-danger">Pesanan tidak ditemukan.</div></div>';
    return;
}

if ($order['status'] !== 'completed') {
    echo '<div class="container py-5"><div class="alert alert-warning">Review hanya dapat diberikan untuk pesanan yang sudah selesai.</div><a href="' . base_url('index.php?page=orders') . '" class="btn btn-primary-custom mt-3">Kembali ke Pesanan Saya</a></div>';
    return;
}

if ($reviewModel->checkExists($orderId)) {
    echo '<div class="container py-5"><div class="alert alert-info">Review untuk pesanan ini sudah dikirim.</div><a href="' . base_url('index.php?page=orders') . '" class="btn btn-primary-custom mt-3">Kembali ke Pesanan Saya</a></div>';
    return;
}

$items = $orderModel->getOrderItems($orderId);
$serviceId = $items[0]['service_id'] ?? 0;
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card rounded-4 shadow-sm p-4">
                <h4 class="text-center mb-3">Beri Review dan Rating</h4>
                <p class="text-center text-muted">Pesanan <strong><?= e($order['order_number']) ?></strong> - <?= e($order['provider_name']) ?></p>

                <div class="mb-4">
                    <h6>Detail Jasa</h6>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($items as $item): ?>
                            <li class="list-group-item px-0 py-2">
                                <?= e($item['title']) ?> · <?= (int) $item['quantity'] ?> x <?= e(format_rupiah($item['price_per_unit'])) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <form method="post" action="<?= base_url('index.php?page=review') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="order_id" value="<?= (int) $orderId ?>">
                    <input type="hidden" name="service_id" value="<?= (int) $serviceId ?>">

                    <div class="mb-3 text-center">
                        <label class="form-label d-block mb-2">Rating</label>
                        <div class="rating-stars d-flex justify-content-center gap-2">
                            <?php for ($star = 1; $star <= 5; $star++): ?>
                                <i class="bi bi-star fs-3" data-value="<?= $star ?>" style="cursor: pointer;"></i>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="rating" id="rating_value" value="5">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Komentar</label>
                        <textarea name="comment" class="form-control" rows="4" placeholder="Tulis pengalaman Anda..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Foto (opsional)</label>
                        <input type="file" name="image" class="form-control" accept="image/jpeg,image/png">
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">Kirim Review</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.rating-stars i').forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            document.getElementById('rating_value').value = value;
            document.querySelectorAll('.rating-stars i').forEach(s => s.classList.remove('text-warning'));
            for (let i = 0; i < value; i++) {
                document.querySelectorAll('.rating-stars i')[i].classList.add('text-warning');
            }
        });
    });
</script>
