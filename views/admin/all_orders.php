<main class="admin-dashboard">
    <div class="container">
        <section class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>Manage Pesanan</h2>
                    <p>Pantau semua transaksi dan status pekerjaan.</p>
                </div>
                <a href="<?= base_url('index.php?page=dashboard') ?>" class="link-accent">Kembali</a>
            </div>
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead><tr><th>No. Pesanan</th><th>Pembeli</th><th>Jasa</th><th>Status</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                        <tr><td><strong>ORD-2401</strong></td><td>Andi Setiawan</td><td>Jasa Bersih Rumah</td><td><span class="status-badge active">Diproses</span></td><td class="text-end">Rp150.000</td></tr>
                        <tr><td><strong>ORD-2402</strong></td><td>Nita Permata</td><td>Les Matematika</td><td><span class="status-badge verified">Selesai</span></td><td class="text-end">Rp75.000</td></tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
