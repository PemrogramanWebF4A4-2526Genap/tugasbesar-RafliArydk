<main class="admin-dashboard">
    <div class="container">
        <section class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>Manage User</h2>
                    <p>Kelola akun pembeli dan penyedia jasa.</p>
                </div>
                <a href="<?= base_url('index.php?page=dashboard') ?>" class="link-accent">Kembali</a>
            </div>
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th></tr></thead>
                    <tbody>
                        <tr><td>Andi Setiawan</td><td>andi@bisabantu.com</td><td>Pembeli</td><td><span class="status-badge verified">Aktif</span></td></tr>
                        <tr><td>Budi Wijaya</td><td>budi@bisabantu.com</td><td>Penyedia</td><td><span class="status-badge verified">Terverifikasi</span></td></tr>
                        <tr><td>Sari Rahmawati</td><td>sari@bisabantu.com</td><td>Penyedia</td><td><span class="status-badge pending">Pending</span></td></tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
