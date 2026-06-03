<?php
if (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'home.php') {
    $appPath = dirname(dirname($_SERVER['SCRIPT_NAME']));
    header('Location: ' . rtrim($appPath, '/\\') . '/index.php?page=home');
    exit;
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
                <form class="search-box mt-4" id="heroSearchForm" role="search">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <i class="bi bi-search ms-2"></i>
                            <input type="search" id="heroSearchInput" class="form-control-plaintext d-inline w-75" placeholder="Cari jasa ..." style="padding-left: 8px;" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-geo-alt ms-2"></i>
                            <select id="heroSearchCity" class="form-select-plaintext d-inline w-75" aria-label="Pilih kota">
                                <option>Semua Kota</option>
                                <option>Jakarta</option>
                                <option>Bandung</option>
                                <option>Surabaya</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn w-100 btn-hero-search">Cari Jasa</button>
                        </div>
                    </div>
                </form>
                <div class="hero-stats-row">
                    <div class="stat-card"><div class="stat-number">2.400+</div><div class="stat-label">Jasa tersedia</div></div>
                    <div class="stat-card"><div class="stat-number">850+</div><div class="stat-label">Penyedia terverifikasi</div></div>
                    <div class="stat-card"><div class="stat-number">12.000+</div><div class="stat-label">Pesanan selesai</div></div>
                    <div class="stat-card"><div class="stat-number">4.8</div><div class="stat-label">Rating rata-rata</div></div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block hero-visual">
                <img src="https://placehold.co/520x400/1C1A16/C8922A?text=BisaBantu" alt="Ilustrasi layanan BisaBantu" class="img-fluid w-100">
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
                <div class="feature-badge" data-toast="Pembayaran Anda dilindungi sistem escrow hingga jasa selesai" data-toast-type="info"><i class="bi bi-lock me-2"></i>Pembayaran Aman</div>
                <p class="small section-sub mt-2 mb-0">Dana terlindungi escrow</p>
            </div>
            <div class="col-md-3">
                <div class="feature-badge" data-toast="Tim dukungan siap membantu 24 jam" data-toast-type="info"><i class="bi bi-headset me-2"></i>Dukungan 24/7</div>
                <p class="small section-sub mt-2 mb-0">Siap membantu kapanpun</p>
            </div>
            <div class="col-md-3">
                <div class="feature-badge" data-toast="Garansi kepuasan 100% atau uang kembali" data-toast-type="info"><i class="bi bi-arrow-return-left me-2"></i>Garansi Kepuasan</div>
                <p class="small section-sub mt-2 mb-0">Uang kembali jika tidak puas</p>
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
        <div class="col-6 col-md-3"><div class="category-card" data-category="bersih-bersih"><div class="category-icon"><i class="fas fa-broom"></i></div><h5 class="mb-0">Bersih-bersih</h5><small class="section-sub">143 jasa</small></div></div>
        <div class="col-6 col-md-3"><div class="category-card" data-category="perbaikan"><div class="category-icon"><i class="fas fa-tools"></i></div><h5 class="mb-0">Perbaikan</h5><small class="section-sub">98 jasa</small></div></div>
        <div class="col-6 col-md-3"><div class="category-card" data-category="les-privat"><div class="category-icon"><i class="fas fa-chalkboard-user"></i></div><h5 class="mb-0">Les Privat</h5><small class="section-sub">215 jasa</small></div></div>
        <div class="col-6 col-md-3"><div class="category-card" data-category="laundry"><div class="category-icon"><i class="fas fa-tshirt"></i></div><h5 class="mb-0">Laundry</h5><small class="section-sub">72 jasa</small></div></div>
        <div class="col-6 col-md-3"><div class="category-card" data-category="taman"><div class="category-icon"><i class="fas fa-tree"></i></div><h5 class="mb-0">Taman</h5><small class="section-sub">55 jasa</small></div></div>
        <div class="col-6 col-md-3"><div class="category-card" data-category="penitipan"><div class="category-icon"><i class="fas fa-paw"></i></div><h5 class="mb-0">Penitipan</h5><small class="section-sub">41 jasa</small></div></div>
        <div class="col-6 col-md-3"><div class="category-card" data-category="memasak"><div class="category-icon"><i class="fas fa-utensils"></i></div><h5 class="mb-0">Memasak</h5><small class="section-sub">88 jasa</small></div></div>
        <div class="col-6 col-md-3"><div class="category-card" data-category="lainnya"><div class="category-icon"><i class="fas fa-ellipsis-h"></i></div><h5 class="mb-0">Lainnya</h5><small class="section-sub">320 jasa</small></div></div>
    </div>
</section>

<!-- JASA POPULER -->
<section class="container my-5" id="layanan-jasa">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
        <div>
            <div class="section-eyebrow">Unggulan</div>
            <h2 class="section-title">Jasa populer <em>minggu ini</em></h2>
        </div>
        <a href="#" class="link-accent" id="viewAllServices">Lihat semua →</a>
    </div>
    <div class="mt-3 mb-4 filter-pills d-flex flex-wrap gap-2" role="group" aria-label="Filter kategori jasa">
        <button type="button" class="filter-pill active" data-filter="semua" aria-pressed="true">Semua</button>
        <button type="button" class="filter-pill" data-filter="bersih-bersih" aria-pressed="false">Bersih-bersih</button>
        <button type="button" class="filter-pill" data-filter="perbaikan" aria-pressed="false">Perbaikan</button>
        <button type="button" class="filter-pill" data-filter="les-privat" aria-pressed="false">Les Privat</button>
        <button type="button" class="filter-pill" data-filter="laundry" aria-pressed="false">Laundry</button>
    </div>
    <div id="servicesEmpty" class="d-none">
        <i class="bi bi-search fs-2 d-block mb-2 section-sub"></i>
        Tidak ada jasa yang cocok. Coba kata kunci atau filter lain.
    </div>
    <div class="row g-4" id="servicesGrid">
        <div class="col-md-6 col-lg-3" data-service-card data-category="bersih-bersih" data-title="Jasa Bersih Rumah Profesional">
            <div class="service-card">
                <div class="service-img"><i class="fas fa-home fa-3x"></i></div>
                <div class="p-3">
                    <h5>Jasa Bersih Rumah Profesional</h5>
                    <div class="rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><span class="ms-1">4.9 (128 ulasan)</span></div>
                    <p class="service-price mt-2 mb-1">Rp 150.000 <span class="fw-normal section-sub">/kunjungan</span></p>
                    <small class="section-sub"><i class="bi bi-person-circle"></i> Budi W. - Bandung</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3" data-service-card data-category="les-privat" data-title="Les Matematika SD-SMP">
            <div class="service-card">
                <div class="service-img"><i class="fas fa-calculator fa-3x"></i></div>
                <div class="p-3">
                    <h5>Les Matematika SD-SMP</h5>
                    <div class="rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span class="ms-1">4.8 (94 ulasan)</span></div>
                    <p class="service-price mt-2 mb-1">Rp 75.000 <span class="fw-normal section-sub">/jam</span></p>
                    <small class="section-sub"><i class="bi bi-person-circle"></i> Sari R. - Jakarta Selatan</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3" data-service-card data-category="perbaikan" data-title="Servis AC & Kulkas Rumahan">
            <div class="service-card">
                <div class="service-img"><i class="fas fa-wrench fa-3x"></i></div>
                <div class="p-3">
                    <h5>Servis AC & Kulkas Rumahan</h5>
                    <div class="rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span class="ms-1">4.7 (61 ulasan)</span></div>
                    <p class="service-price mt-2 mb-1">Rp 200.000 <span class="fw-normal section-sub">/unit</span></p>
                    <small class="section-sub"><i class="bi bi-person-circle"></i> Arif H. - Surabaya</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3" data-service-card data-category="laundry" data-title="Laundry Kiloan Antar Jemput">
            <div class="service-card">
                <div class="service-img"><i class="fas fa-tshirt fa-3x"></i></div>
                <div class="p-3">
                    <h5>Laundry Kiloan Antar Jemput</h5>
                    <div class="rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><span class="ms-1">4.9 (207 ulasan)</span></div>
                    <p class="service-price mt-2 mb-1">Rp 8.000 <span class="fw-normal section-sub">/kg</span></p>
                    <small class="section-sub"><i class="bi bi-person-circle"></i> Dewi L. - Yogyakarta</small>
                </div>
            </div>
        </div>
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
            <div class="col-md-3"><div class="step-card"><div class="step-number">1</div><h5>Cari jasa</h5><p class="small section-sub">Gunakan filter kategori, lokasi, dan harga untuk menemukan jasa yang paling sesuai.</p></div></div>
            <div class="col-md-3"><div class="step-card"><div class="step-number">2</div><h5>Pesan & checkout</h5><p class="small section-sub">Tambahkan ke keranjang, pilih tanggal pelaksanaan dan metode pembayaran, lalu buat pesanan.</p></div></div>
            <div class="col-md-3"><div class="step-card"><div class="step-number">3</div><h5>Bayar dengan aman</h5><p class="small section-sub">Upload bukti transfer atau bayar tunai. Dana hanya diteruskan setelah pekerjaan selesai.</p></div></div>
            <div class="col-md-3"><div class="step-card"><div class="step-number">4</div><h5>Beri ulasan</h5><p class="small section-sub">Setelah selesai, beri rating dan komentar untuk membantu pengguna lain.</p></div></div>
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
        <div class="col-md-4">
            <div class="testimonial-card">
                <div class="d-flex gap-3 mb-2"><i class="bi bi-person-circle fs-1 section-sub"></i><div><h5 class="mb-0">Nita Permata</h5><small class="section-sub">Pembeli - Jakarta</small></div></div>
                <div class="rating mb-2">★★★★★</div>
                <p class="section-sub mb-0">Sangat mudah digunakan! Saya menemukan cleaning service yang bagus dalam hitungan menit. Penyedianya ramah dan profesional.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="testimonial-card">
                <div class="d-flex gap-3 mb-2"><i class="bi bi-person-circle fs-1 section-sub"></i><div><h5 class="mb-0">Rizki Kurniawan</h5><small class="section-sub">Penyedia Jasa - Bandung</small></div></div>
                <div class="rating mb-2">★★★★★</div>
                <p class="section-sub mb-0">Sebagai penyedia, platform ini sangat membantu. Pesanan datang terus dan sistem pembayarannya transparan dan aman.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="testimonial-card">
                <div class="d-flex gap-3 mb-2"><i class="bi bi-person-circle fs-1 section-sub"></i><div><h5 class="mb-0">Maya Andriani</h5><small class="section-sub">Pembeli - Surabaya</small></div></div>
                <div class="rating mb-2">★★★★★</div>
                <p class="section-sub mb-0">Les matematika anak saya meningkat pesat. Mudah booking dan gurunya bisa datang ke rumah sesuai jadwal yang kita tentukan.</p>
            </div>
        </div>
    </div>
</section>
