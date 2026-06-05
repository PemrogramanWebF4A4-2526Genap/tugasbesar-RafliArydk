<?php
require_once __DIR__ . '/../../models/ServiceModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';

$serviceModel = new ServiceModel($pdo);
$reviewModel = new ReviewModel($pdo);
$serviceId = (int) ($_GET['id'] ?? 0);
$service = $serviceModel->getById($serviceId);

if (!$service || (int) $service['is_active'] !== 1) {
    echo '<div class="container py-5"><div class="alert alert-danger">Layanan tidak ditemukan atau tidak aktif.</div></div>';
    return;
}

$reviews = $reviewModel->getByService($serviceId);
$imagePath = $service['image'] ? 'assets/uploads/services/' . $service['image'] : '';
$hasImage = $imagePath && is_file(__DIR__ . '/../../' . $imagePath);
$averageRating = number_format($service['avg_rating'] ?? 0, 1);
?>

<div class="container py-5">
    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card rounded-4 shadow-sm overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 bg-light d-flex align-items-center justify-content-center" style="min-height: 260px;">
                        <?php if ($hasImage): ?>
                            <img src="<?= e(base_url($imagePath)) ?>" alt="<?= e($service['title']) ?>" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="bi <?= e(service_icon($service['category_name'])) ?> fs-1"></i>
                                <p class="mb-0">Tidak ada gambar</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-7 p-4">
                        <h2 class="fw-bold"><?= e($service['title']) ?></h2>
                        <div class="mb-3">
                            <span class="badge bg-secondary me-2"><?= e($service['category_name']) ?></span>
                            <span class="badge bg-success"><?= e($service['provider_name']) ?></span>
                        </div>
                        <p class="fs-5 mb-2"><?= e(format_rupiah($service['price'])) ?> / <?= e($service['price_unit']) ?></p>
                        <p class="text-muted mb-1">Durasi: <?= e($service['estimated_duration']) ?></p>
                        <p class="text-muted mb-3">Lokasi: <?= e($service['location']) ?></p>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi <?= $i <= round($service['avg_rating']) ? 'bi-star-fill text-warning' : 'bi-star' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-muted"><?= $averageRating ?> (<?= count($reviews) ?> ulasan)</span>
                        </div>
                        <p><?= nl2br(e($service['description'])) ?></p>
                    </div>
                </div>
            </div>

            <div class="card rounded-4 shadow-sm p-4 mt-4">
                <h4>Ulasan Pelanggan</h4>
                <?php if (empty($reviews)): ?>
                    <p class="text-muted">Belum ada ulasan untuk layanan ini.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong><?= e($review['reviewer_name']) ?></strong>
                                <span><?= e($review['rating']) ?> ★</span>
                            </div>
                            <p class="mb-1"><?= e($review['comment']) ?></p>
                            <?php if ($review['image']): ?>
                                <img src="<?= e(base_url('assets/uploads/reviews/' . $review['image'])) ?>" alt="Review image" class="img-fluid rounded" style="max-height: 180px; object-fit: cover;">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sticky-top" style="top: 100px;">
                <div class="card rounded-4 shadow-sm p-4 mb-4">
                    <h5 class="mb-3">Ringkasan Layanan</h5>
                    <p class="mb-2"><strong>Harga:</strong> <?= e(format_rupiah($service['price'])) ?> / <?= e($service['price_unit']) ?></p>
                    <p class="mb-2"><strong>Durasi:</strong> <?= e($service['estimated_duration']) ?></p>
                    <p class="mb-2"><strong>Lokasi:</strong> <?= e($service['location']) ?></p>
                    <?php if (isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'buyer'): ?>
                        <form method="post" action="<?= base_url('index.php?page=cart&action=add') ?>">
                            <input type="hidden" name="service_id" value="<?= (int) $serviceId ?>">
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="quantity" min="1" value="1" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100">Tambah ke Keranjang</button>
                        </form>
                    <?php else: ?>
                        <p class="text-muted">Masuk sebagai pembeli untuk menambahkan ke keranjang.</p>
                    <?php endif; ?>
                </div>
                <a href="<?= base_url('index.php?page=home') ?>" class="btn btn-outline-custom w-100">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
