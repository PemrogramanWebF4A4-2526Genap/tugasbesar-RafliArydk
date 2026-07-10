<?php
// Beranda publik. Dipanggil oleh index.php?page=home.

if (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'home.php') {
    $appPath = dirname(dirname($_SERVER['SCRIPT_NAME']));
    header('Location: ' . rtrim($appPath, '/\\') . '/index.php?page=home');
    exit;
}

require_once __DIR__ . '/../../models/ServiceModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';

$serviceModel = new ServiceModel($pdo);
$categoryModel = new CategoryModel($pdo);
$userModel = new UserModel($pdo);
$orderModel = new OrderModel($pdo);
$reviewModel = new ReviewModel($pdo);

$activeHomeServices = $serviceModel->getAllActive();
$homeServices = $serviceModel->getPopularActive(8);
$homeCategories = $categoryModel->getAllWithServiceCount();
$homeReviews = $reviewModel->getRecentReviews(3);
$activeServiceCount = $serviceModel->countActive();
$verifiedProviderCount = $userModel->countVerifiedProviders();
$completedOrderCount = count(array_filter($orderModel->getAll(), fn($order) => $order['status'] === 'completed'));
$avgRating = 0;
$brandImagePath = 'src/assets/uploads/images/brand.png';
$brandImageUrl = base_url($brandImagePath);
$brandImageFile = __DIR__ . '/../../' . $brandImagePath;
if (is_file($brandImageFile)) {
    $brandImageUrl .= '?v=' . filemtime($brandImageFile);
}
$ratingRows = array_filter($activeHomeServices, fn($service) => (int) $service['review_count'] > 0);
if (!empty($ratingRows)) {
    $avgRating = array_sum(array_map(fn($service) => (float) $service['avg_rating'], $ratingRows)) / count($ratingRows);
}
?>

<!-- HERO -->
<section class="hero" id="beranda">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-eyebrow">Marketplace Jasa Terpercaya</div>
                <h1 class="hero-title">Temukan jasa <em>terbaik</em> di sekitar Anda</h1>
                <p class="hero-desc">Dari bersih-bersih, perbaikan rumah, hingga les privat — semua tersedia dengan penyedia terverifikasi.</p>
                <form class="search-box mt-4" id="heroSearchForm" role="search" action="<?= base_url('index.php') ?>" method="get">
                    <input type="hidden" name="page" value="services">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <i class="bi bi-search ms-2"></i>
                            <input type="search" id="heroSearchInput" name="search" class="form-control-plaintext d-inline w-75" placeholder="Cari jasa ..." style="padding-left: 8px;" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-geo-alt ms-2"></i>
                            <select id="heroSearchCity" name="location" class="form-select-plaintext d-inline w-75" aria-label="Pilih kota">
                                <option value="">Semua Kota</option>
                                <?php foreach (array_slice(array_values(array_unique(array_filter(array_map(static fn($service) => trim((string) $service['location']), $activeHomeServices)))), 0, 6) as $location): ?>
                                    <option value="<?= e($location) ?>"><?= e($location) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn w-100 btn-hero-search">Cari Jasa</button>
                        </div>
                    </div>
                </form>
                <div class="hero-stats-row">
                    <div class="stat-card">
                        <div class="stat-number"><?= (int) $activeServiceCount ?></div>
                        <div class="stat-label">Jasa tersedia</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= (int) $verifiedProviderCount ?></div>
                        <div class="stat-label">Penyedia terverifikasi</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= (int) $completedOrderCount ?></div>
                        <div class="stat-label">Pesanan selesai</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $avgRating > 0 ? e(number_format($avgRating, 1)) : '0.0' ?></div>
                        <div class="stat-label">Rating rata-rata</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block hero-visual img">
                <img src="<?= e($brandImageUrl) ?>" alt="Ilustrasi layanan BisaBantu" class="img-fluid w-100">
            </div>
        </div>
    </div>
</section>

<!-- FITUR -->
<section class="features-strip py-5" id="fitur">
    <div class="container">
        <div class="row text-center g-4 justify-content-center">
            <div class="col-md-3">
                <div class="feature-badge" data-toast="Semua penyedia diverifikasi oleh tim admin BisaBantu" data-toast-type="info"><i class="bi bi-shield-check me-2"></i>Penyedia Terverifikasi</div>
                <p class="small section-sub mt-2 mb-0">Semua diverifikasi admin</p>
            </div>
            <div class="col-md-3">
                <div class="feature-badge" data-toast="Bukti transfer diverifikasi admin sebelum pesanan diproses" data-toast-type="info"><i class="bi bi-lock me-2"></i>Pembayaran Aman</div>
                <p class="small section-sub mt-2 mb-0">Transfer diverifikasi admin</p>
            </div>
            <div class="col-md-3">
                <div class="feature-badge" data-toast="Notifikasi membantu pembeli dan penyedia mengikuti status pesanan" data-toast-type="info"><i class="bi bi-headset me-2"></i>Status Transparan</div>
                <p class="small section-sub mt-2 mb-0">Pantau progres pesanan</p>
            </div>
            <div class="col-md-3">
                <div class="feature-badge" data-toast="Ulasan pelanggan membantu menjaga kualitas penyedia jasa" data-toast-type="info"><i class="bi bi-arrow-return-left me-2"></i>Ulasan Terbuka</div>
                <p class="small section-sub mt-2 mb-0">Rating dari pelanggan</p>
            </div>
        </div>
    </div>
</section>

<!-- KATEGORI -->
<section class="container my-5 py-2" id="kategori">
    <div class="section-eyebrow">Kategori</div>
    <h2 class="section-title">Jelajahi berdasarkan <em>kategori</em></h2>
    <p class="section-sub">Pilih kategori untuk menemukan jasa yang tepat</p>
    <div class="row g-4 mt-3">
        <?php foreach ($homeCategories as $category): ?>
            <?php $categorySlug = slugify($category['name']); ?>
            <div class="col-6 col-md-3">
                <div class="category-card" data-category="<?= e($categorySlug) ?>" data-services-url="<?= e(base_url('index.php?page=services&category=' . rawurlencode($categorySlug))) ?>">
                    <div class="category-icon"><i class="bi <?= e(category_icon($category['name'])) ?>"></i></div>
                    <h5 class="mb-0"><?= e($category['name']) ?></h5>
                    <small class="section-sub"><?= (int) $category['service_count'] ?> jasa</small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- JASA POPULER -->
<section class="container my-5" id="layanan-jasa">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
        <div>
            <div class="section-eyebrow">Unggulan</div>
            <h2 class="section-title">Jasa populer <em>minggu ini</em></h2>
        </div>
        <a href="<?= base_url('index.php?page=services') ?>" class="link-accent" id="viewAllServices">Lihat semua →</a>
    </div>
    <div class="mt-3 mb-4 filter-pills d-flex flex-wrap gap-2" role="group" aria-label="Filter kategori jasa">
        <button type="button" class="filter-pill active" data-filter="semua" aria-pressed="true">Semua</button>
        <?php foreach ($homeCategories as $category): ?>
            <button type="button" class="filter-pill" data-filter="<?= e(slugify($category['name'])) ?>" aria-pressed="false"><?= e($category['name']) ?></button>
        <?php endforeach; ?>
    </div>
    <div id="servicesEmpty" class="d-none">
        <i class="bi bi-search fs-2 d-block mb-2 section-sub"></i>
        Tidak ada jasa yang cocok. Coba kata kunci atau filter lain.
    </div>
    <div class="row g-4" id="servicesGrid">
        <?php if (empty($homeServices)): ?>
            <div class="col-12">
                <div class="alert alert-info">Belum ada layanan aktif dari penyedia terverifikasi.</div>
            </div>
        <?php else: ?>
            <?php foreach ($homeServices as $service): ?>
                <?php
                $serviceCategorySlug = slugify($service['category_name']);
                $rating = (float) $service['avg_rating'];
                $imagePath = $service['image'] ? 'src/assets/uploads/services/' . $service['image'] : '';
                $hasImage = $imagePath && is_file(__DIR__ . '/../../../' . ltrim($imagePath, '/'));
                ?>
                <div class="col-md-6 col-lg-3" data-service-card data-category="<?= e($serviceCategorySlug) ?>" data-title="<?= e($service['title']) ?>" data-detail-url="<?= e(base_url('index.php?page=service_detail&id=' . (int) $service['id'])) ?>">
                    <div class="service-card">
                        <div class="service-img">
                            <a href="<?= base_url('index.php?page=service_detail&id=' . (int) $service['id']) ?>" class="d-flex align-items-center justify-content-center w-100 h-100" style="text-decoration: none; color: inherit;">
                                <?php if ($hasImage): ?>
                                    <img src="<?= e(base_url($imagePath)) ?>" alt="<?= e($service['title']) ?>" class="img-fluid w-100 h-100" style="object-fit: cover;">
                                <?php else: ?>
                                    <i class="bi <?= e(category_icon($service['category_name'])) ?> fa-3x"></i>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="p-3">
                            <h5><?= e($service['title']) ?></h5>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span class="ms-1"><?= e(number_format($rating, 1)) ?> (<?= (int) $service['review_count'] ?> ulasan)</span>
                            </div>
                            <p class="service-price mt-2 mb-1"><?= e(format_rupiah($service['price'])) ?> <span class="fw-normal section-sub">/<?= e($service['price_unit']) ?></span></p>
                            <small class="section-sub"><i class="bi bi-person-circle"></i> <?= e($service['provider_name']) ?> - <?= e($service['location']) ?></small>
                            <div class="d-grid gap-2 mt-3">
                                <a href="<?= base_url('index.php?page=service_detail&id=' . (int) $service['id']) ?>" class="btn btn-sm btn-outline-custom w-100">Detail</a>
                                <?php if (isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'buyer'): ?>
                                    <form method="post" action="<?= base_url('index.php?page=cart&action=add') ?>" class="m-0 js-cart-add" onclick="event.stopPropagation();">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="service_id" value="<?= (int) $service['id'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-primary-custom w-100">Tambah ke Keranjang</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- CARA KERJA -->
<section class="section-steps py-5" id="cara-kerja">
    <div class="container my-2">
        <div class="text-center mb-5">
            <div class="section-eyebrow">Cara Kerja</div>
            <h2 class="section-title">Mudah dan cepat dalam <em>4 langkah</em></h2>
            <p class="section-sub">Dari pencarian hingga jasa selesai, semuanya di satu platform</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5>Cari jasa</h5>
                    <p class="small section-sub">Gunakan filter kategori, lokasi, dan harga untuk menemukan jasa yang paling sesuai.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5>Pesan dan checkout</h5>
                    <p class="small section-sub">Tambahkan ke keranjang, pilih tanggal pelaksanaan dan metode pembayaran, lalu buat pesanan.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5>Bayar dengan aman</h5>
                    <p class="small section-sub">Upload bukti transfer atau pilih COD. Admin dapat memverifikasi pembayaran sebelum pesanan diproses.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h5>Beri ulasan</h5>
                    <p class="small section-sub">Setelah selesai, beri rating dan komentar untuk membantu pengguna lain.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONI -->
<section class="container my-5 py-4" id="testimoni">
    <div class="text-center mb-5">
        <div class="section-eyebrow">Testimoni</div>
        <h2 class="section-title">Apa kata <em>pengguna</em> kami</h2>
    </div>
    <div class="row g-4">
        <?php if (!empty($homeReviews)): ?>
            <?php foreach ($homeReviews as $review): ?>
                <?php
                $reviewerRole = $review['reviewer_role'] === 'provider' ? 'Penyedia Jasa' : 'Pembeli';
                
                // Dapatkan kota / lokasi
                $address = trim($review['reviewer_address'] ?? '');
                if ($address === '') {
                    $address = trim($review['service_location'] ?? 'Indonesia');
                }
                $city = 'Indonesia';
                if ($address !== '') {
                    $parts = explode(',', $address);
                    $city = trim(end($parts));
                }
                $roleLocation = $reviewerRole . ' - ' . $city;
                
                $profilePhoto = $review['reviewer_photo'] ? base_url($review['reviewer_photo']) : null;
                ?>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="d-flex gap-3 mb-2">
                            <?php if ($profilePhoto && is_file(__DIR__ . '/../../../' . ltrim($review['reviewer_photo'], '/'))): ?>
                                <img src="<?= e($profilePhoto) ?>" alt="<?= e($review['reviewer_name']) ?>" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                            <?php else: ?>
                                <i class="bi bi-person-circle fs-1 section-sub"></i>
                            <?php endif; ?>
                            <div>
                                <h5 class="mb-0"><?= e($review['reviewer_name']) ?></h5>
                                <small class="section-sub"><?= e($roleLocation) ?></small>
                            </div>
                        </div>
                        <div class="rating mb-2" style="color: #ffc107;">
                            <?php
                            $stars = (int)$review['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $stars ? '★' : '☆';
                            }
                            ?>
                        </div>
                        <p class="section-sub mb-0">"<?= e($review['comment']) ?>"</p>
                        <?php if ($review['service_title']): ?>
                            <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">Jasa: <?= e($review['service_title']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Fallback static reviews if database is empty -->
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex gap-3 mb-2"><i class="bi bi-person-circle fs-1 section-sub"></i>
                        <div>
                            <h5 class="mb-0">Nita Permata</h5><small class="section-sub">Pembeli - Jakarta</small>
                        </div>
                    </div>
                    <div class="rating mb-2">★★★★★</div>
                    <p class="section-sub mb-0">Sangat mudah digunakan! Saya menemukan cleaning service yang bagus dalam hitungan menit. Penyedianya ramah dan profesional.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex gap-3 mb-2"><i class="bi bi-person-circle fs-1 section-sub"></i>
                        <div>
                            <h5 class="mb-0">Rizki Kurniawan</h5><small class="section-sub">Penyedia Jasa - Bandung</small>
                        </div>
                    </div>
                    <div class="rating mb-2">★★★★★</div>
                    <p class="section-sub mb-0">Sebagai penyedia, platform ini sangat membantu. Pesanan datang terus dan sistem pembayarannya transparan dan aman.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex gap-3 mb-2"><i class="bi bi-person-circle fs-1 section-sub"></i>
                        <div>
                            <h5 class="mb-0">Maya Andriani</h5><small class="section-sub">Pembeli - Surabaya</small>
                        </div>
                    </div>
                    <div class="rating mb-2">★★★★★</div>
                    <p class="section-sub mb-0">Les matematika anak saya meningkat pesat. Mudah booking dan gurunya bisa datang ke rumah sesuai jadwal yang kita tentukan.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
