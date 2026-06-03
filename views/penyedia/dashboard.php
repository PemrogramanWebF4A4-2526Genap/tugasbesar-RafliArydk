<?php require_once __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <h2 class="fw-bold">Dashboard Penyedia</h2>
    <div class="row mt-4">
        <div class="col-md-3"><div class="card text-center p-3 border-0 shadow-sm rounded-4"><h3>12</h3><p class="text-muted">Pesanan Masuk</p></div></div>
        <div class="col-md-3"><div class="card text-center p-3 border-0 shadow-sm rounded-4"><h3>Rp 2.5jt</h3><p class="text-muted">Pendapatan Bulan Ini</p></div></div>
        <div class="col-md-3"><div class="card text-center p-3 border-0 shadow-sm rounded-4"><h3>4.8</h3><p class="text-muted">Rating Rata-rata</p></div></div>
    </div>
    <div class="mt-5">
        <h4>Pesanan Terbaru</h4>
        <table class="table">
            <thead><tr><th>ID Pesanan</th><th>Layanan</th><th>Tanggal</th><th>Status</th></tr></thead>
            <tbody>
                <tr><td>#ORD001</td><td>Bersih Rumah</td><td>15 Jun 2025</td><td><span class="badge bg-primary">Selesai</span></td></tr>
                <tr><td>#ORD002</td><td>Reparasi AC</td><td>16 Jun 2025</td><td><span class="badge bg-warning text-dark">Proses</span></td></tr>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>