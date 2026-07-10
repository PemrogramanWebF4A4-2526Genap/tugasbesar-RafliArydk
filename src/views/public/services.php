<?php
require_once __DIR__ . '/../../models/ServiceModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';

$serviceModel = new ServiceModel($pdo);
$categoryModel = new CategoryModel($pdo);
$services = $serviceModel->getAllActive();
$categories = $categoryModel->getAllWithServiceCount();
$requestedCategory = slugify($_GET['category'] ?? 'semua');
$requestedSearch = trim((string) ($_GET['search'] ?? ''));
$requestedLocation = trim((string) ($_GET['location'] ?? ''));
$availableCategories = array_map(static fn($category) => slugify($category['name']), $categories);
if ($requestedCategory !== 'semua' && !in_array($requestedCategory, $availableCategories, true)) {
    $requestedCategory = 'semua';
}
$locations = array_values(array_unique(array_filter(array_map(static fn($service) => trim((string) $service['location']), $services))));
sort($locations, SORT_NATURAL | SORT_FLAG_CASE);
if ($requestedLocation !== '' && !in_array($requestedLocation, $locations, true)) {
    $requestedLocation = '';
}
?>

<main class="services-page">
    <div class="container">
        <nav class="services-breadcrumb" aria-label="Breadcrumb">
            <a href="<?= base_url('index.php?page=home') ?>">Beranda</a><span>/</span><span>Semua Jasa</span>
        </nav>

        <header class="services-heading">
            <div class="section-eyebrow">Marketplace Jasa Lokal</div>
            <h1>Semua <em>jasa</em> yang tersedia</h1>
            <p>Jelajahi jasa dari penyedia terverifikasi untuk kebutuhan Anda.</p>
        </header>

        <section class="services-toolbar" aria-label="Pencarian dan pengurutan jasa">
            <label class="services-search" for="servicesSearch">
                <i class="bi bi-search" aria-hidden="true"></i>
                <input id="servicesSearch" type="search" placeholder="Cari nama jasa atau penyedia..." autocomplete="off" value="<?= e($requestedSearch) ?>">
            </label>
            <label class="visually-hidden" for="servicesLocation">Lokasi</label>
            <select id="servicesLocation" class="services-select">
                <option value="">Semua Kota</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= e($location) ?>" <?= $requestedLocation === $location ? 'selected' : '' ?>><?= e($location) ?></option>
                <?php endforeach; ?>
            </select>
            <label class="visually-hidden" for="servicesSort">Urutkan jasa</label>
            <select id="servicesSort" class="services-select services-sort">
                <option value="latest">Urutkan: Terbaru</option>
                <option value="price-asc">Harga Terendah</option>
                <option value="price-desc">Harga Tertinggi</option>
                <option value="rating-desc">Rating Tertinggi</option>
            </select>
        </section>

        <div class="services-category-pills" role="group" aria-label="Filter kategori">
            <button type="button" class="services-category-pill<?= $requestedCategory === 'semua' ? ' active' : '' ?>" data-category="semua">Semua <span><?= count($services) ?></span></button>
            <?php foreach ($categories as $category): ?>
                <?php $slug = slugify($category['name']); ?>
                <button type="button" class="services-category-pill<?= $requestedCategory === $slug ? ' active' : '' ?>" data-category="<?= e($slug) ?>">
                    <?= e($category['name']) ?> <span><?= (int) $category['service_count'] ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <p id="servicesResultCount" class="services-result-count">Menampilkan <strong><?= count($services) ?></strong> jasa</p>
        <div id="servicesEmptyState" class="services-empty-state d-none">
            <i class="bi bi-search" aria-hidden="true"></i>
            <h2>Jasa tidak ditemukan</h2>
            <p>Coba ganti kata kunci, kategori, atau lokasi pencarian.</p>
        </div>

        <section id="allServicesGrid" class="row g-3" aria-live="polite">
            <?php foreach ($services as $service): ?>
                <?php
                $categorySlug = slugify($service['category_name']);
                $rating = (float) $service['avg_rating'];
                $imagePath = $service['image'] ? 'src/assets/uploads/services/' . $service['image'] : '';
                $hasImage = $imagePath && is_file(__DIR__ . '/../../../' . ltrim($imagePath, '/'));
                ?>
                <div class="col-sm-6 col-lg-4 col-xxl-3 all-service-item"
                     data-category="<?= e($categorySlug) ?>"
                     data-location="<?= e(strtolower($service['location'])) ?>"
                     data-search="<?= e(strtolower($service['title'] . ' ' . $service['provider_name'] . ' ' . $service['category_name'] . ' ' . $service['location'])) ?>"
                     data-price="<?= (float) $service['price'] ?>"
                     data-rating="<?= $rating ?>"
                     data-created="<?= e($service['created_at']) ?>">
                    <article class="all-service-card">
                        <div class="all-service-image">
                            <span class="all-service-category"><?= e($service['category_name']) ?></span>
                            <?php if ($hasImage): ?>
                                <img src="<?= e(base_url($imagePath)) ?>" alt="<?= e($service['title']) ?>">
                            <?php else: ?>
                                <i class="bi <?= e(category_icon($service['category_name'])) ?>" aria-hidden="true"></i>
                            <?php endif; ?>
                        </div>
                        <div class="all-service-body">
                            <h2><?= e($service['title']) ?></h2>
                            <div class="all-service-rating" aria-label="Rating <?= e(number_format($rating, 1)) ?> dari <?= (int) $service['review_count'] ?> ulasan">
                                <i class="bi bi-star-fill"></i><strong><?= e(number_format($rating, 1)) ?></strong><span>(<?= (int) $service['review_count'] ?> ulasan)</span>
                            </div>
                            <p class="all-service-price"><?= e(format_rupiah($service['price'])) ?> <span>/<?= e($service['price_unit']) ?></span></p>
                            <p class="all-service-provider"><i class="bi bi-person"></i><?= e($service['provider_name']) ?> <span>-</span> <?= e($service['location']) ?></p>
                            <a href="<?= base_url('index.php?page=service_detail&id=' . (int) $service['id']) ?>" class="btn btn-outline-custom w-100">Lihat Detail</a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </section>
    </div>
</main>
