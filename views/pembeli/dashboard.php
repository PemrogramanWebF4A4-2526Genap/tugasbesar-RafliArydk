<?php require_once __DIR__ . '/../layout/header.php'; ?>
<div class="container mt-4">
    <h2 class="fw-bold">Dashboard Pembeli</h2>
    <p>Selamat datang, <strong>Nama Pembeli</strong></p>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center">
                    <i class="bi bi-bag-check fs-1" style="color: var(--orange-primary);"></i>
                    <h3>3</h3>
                    <p class="text-muted">Pesanan Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center">
                    <i class="bi bi-star-fill fs-1" style="color: var(--orange-primary);"></i>
                    <h3>5</h3>
                    <p class="text-muted">Ulasan Diberikan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history fs-1" style="color: var(--orange-primary);"></i>
                    <h3>2</h3>
                    <p class="text-muted">Menunggu Pembayaran</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Daftar pesanan terbaru -->
    <div class="mt-5">
        <h4>Pesanan Terbaru</h4>
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action rounded-3 mb-2 border">#ORD001 - Jasa Bersih Rumah - Status: Diproses</a>
            <a href="#" class="list-group-item list-group-item-action rounded-3 mb-2 border">#ORD002 - Les Matematika - Status: Selesai</a>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>