<?php
require_once __DIR__ . '/../../models/ReviewModel.php';

$reviewModel = new ReviewModel($pdo);
$reviews = $reviewModel->getByProvider($_SESSION['user']['id']);
$ratingData = $reviewModel->getAverageRatingByProvider($_SESSION['user']['id']);
$avgRating = $ratingData['avg_rating'] ? number_format($ratingData['avg_rating'], 1) : '0.0';
?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Ulasan</h2>
            <p class="text-muted mb-0">Rating rata-rata <?= e($avgRating) ?> dari <?= count($reviews) ?> ulasan.</p>
        </div>
        <span class="badge bg-dark rounded-pill fs-6"><?= count($reviews) ?> ulasan</span>
    </div>

    <?php if (empty($reviews)): ?>
        <div class="text-center py-5">
            <i class="bi bi-star fs-1 text-muted"></i>
            <p class="text-muted mt-2">Belum ada ulasan dari pembeli.</p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($reviews as $review): ?>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between gap-3 mb-2">
                                <div>
                                    <h5 class="mb-1"><?= e($review['service_title']) ?></h5>
                                    <small class="text-muted"><?= e($review['order_number']) ?> - <?= e($review['reviewer_name']) ?></small>
                                </div>
                                <div class="rating text-nowrap">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi <?= $i <= (int) $review['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="mb-0 text-muted"><?= e($review['comment'] ?: 'Tidak ada komentar.') ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
