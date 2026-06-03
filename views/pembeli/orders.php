<?php require_once __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <h2 class="fw-bold mb-4">Pesanan Saya</h2>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>No. Pesanan</th><th>Jasa</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <tr><td>ORD001</td><td>Jasa Bersih Rumah</td><td>Rp 150.000</td><td><span class="badge bg-warning text-dark">Menunggu Pembayaran</span></td><td><a href="order_detail.php" class="btn btn-sm btn-outline-custom">Detail</a></td></tr>
                <tr><td>ORD002</td><td>Les Matematika</td><td>Rp 150.000</td><td><span class="badge bg-success">Selesai</span></td><td><a href="order_detail.php" class="btn btn-sm btn-outline-custom">Detail</a></td></tr>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>