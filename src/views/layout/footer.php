<footer class="site-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <a class="footer-brand d-inline-block" href="<?= base_url('index.php?page=home') ?>">BisaBantu<span>.</span></a>
                <p class="footer-tagline">Platform yang menghubungkan pembeli dengan penyedia jasa terverifikasi di seluruh Indonesia.</p>
                <div class="d-flex gap-2 mt-4 social-icons">
                    <a href="#" class="social-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-btn" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Platform</h6>
                <ul class="list-unstyled">
                    <li><a href="<?= base_url('index.php?page=home') ?>">Beranda</a></li>
                    <li><a href="<?= base_url('index.php?page=home') ?>#layanan-jasa" data-scroll="#layanan-jasa">Semua Jasa</a></li>
                    <li><a href="<?= base_url('index.php?page=home') ?>#cara-kerja" data-scroll="#cara-kerja">Cara Kerja</a></li>
                    <li><a href="<?= base_url('index.php?page=home') ?>#testimoni" data-scroll="#testimoni">Tentang Kami</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Akun</h6>
                <ul class="list-unstyled">
                    <li><a href="#" onclick="openAuthModal('login'); return false;">Masuk</a></li>
                    <li><a href="#" onclick="openAuthModal('register','buyer'); return false;">Daftar Pembeli</a></li>
                    <li><a href="#" onclick="openAuthModal('register','provider'); return false;">Daftar Penyedia</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Bantuan</h6>
                <ul class="list-unstyled">
                    <li><a href="#" data-toast="Hubungi kami di support@bisabantu.id" data-toast-type="info">Kontak Kami</a></li>
                    <li><a href="#" data-toast="Halaman kebijakan privasi segera hadir" data-toast-type="info">Kebijakan Privasi</a></li>
                    <li><a href="#" data-toast="Halaman syarat dan ketentuan segera hadir" data-toast-type="info">Syarat dan Ketentuan</a></li>
                    <li><a href="#" data-toast="Lihat pertanyaan yang sering diajukan" data-toast-type="info">FAQ</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Legal</h6>
                <ul class="list-unstyled">
                    <li><a href="#">Syarat dan Ketentuan</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Cookie</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="footer-bottom text-center">© 2025 BisaBantu Service Marketplace. MIT License. Dibuat untuk Tugas Besar Pemrograman Web</div>
    </div>
</footer>

<?php if (!isset($_SESSION['user'])): ?>
    <?php include __DIR__ . '/../auth/_auth_modal.php'; ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('src/assets/js/ui.js?v=' . time()) ?>"></script>
<script src="<?= base_url('src/assets/js/auth-modal.js?v=' . time()) ?>"></script>
<script src="<?= asset_url('src/assets/js/animations.js') ?>"></script>
<?php if (($page ?? '') === 'home'): ?>
<script src="<?= base_url('src/assets/js/home.js') ?>"></script>
<?php endif; ?>
</body>
</html>
