<?php
$userRole = $userRole ?? ($_SESSION['user']['role'] ?? 'buyer');
$roleLabel = $roleLabel ?? ($userRole === 'provider' ? 'Penyedia Jasa' : ($userRole === 'admin' ? 'Admin' : 'Pembeli'));
$roleName = $roleName ?? $roleLabel;
$accountPageTitle = $userRole === 'provider' ? 'Profile Penyedia Jasa' : ($userRole === 'admin' ? 'Profile Admin' : 'Profile Pembeli');
$accountSubtitle = $userRole === 'provider'
    ? 'Kelola identitas penyedia agar calon pembeli jasa lokal mudah percaya dan menghubungi Anda.'
    : 'Perbarui data akun untuk pemesanan, pembayaran, dan komunikasi jasa lokal yang lebih lancar.';
$serviceFocusValue = $serviceFocusValue ?? ($userRole === 'provider' ? 'Layanan rumah, perbaikan, edukasi' : 'Jasa rumah tangga, kursus, perbaikan');

$firstNameValue = $firstNameValue ?? (explode(' ', trim($_SESSION['user']['name'] ?? ''))[0] ?? '');
$lastNameValue = $lastNameValue ?? (explode(' ', trim($_SESSION['user']['name'] ?? ''))[1] ?? '');
$userInitial = $userInitial ?? strtoupper(substr(trim($_SESSION['user']['name'] ?? ''), 0, 1));
?>

<main class="account-settings-page">
    <div class="account-settings-shell">
        <div class="account-settings-header">
            <div>
                <span class="account-eyebrow"><?= e($roleName) ?></span>
                <h2><?= e($accountPageTitle) ?></h2>
                <p><?= e($accountSubtitle) ?></p>
            </div>
            <div class="account-header-actions">
                <button type="reset" form="accountSettingsForm" class="btn btn-outline-custom">Reset</button>
                <button type="submit" form="accountSettingsForm" class="btn btn-primary-custom">
                    <i class="bi bi-save me-1"></i>Simpan
                </button>
            </div>
        </div>

        <div class="account-settings-tabs" aria-label="Bagian pengaturan akun">
            <a class="active" href="#personal-detail">Detail Personal</a>
            <a href="#security">Keamanan</a>
            <a href="#local-preferences">Preferensi Jasa</a>
        </div>

        <form id="accountSettingsForm" method="post" action="<?= base_url('index.php?page=account_settings&profile_update=1') ?>" enctype="multipart/form-data">
            <div class="account-settings-grid">
                <section class="account-panel account-panel-main" id="personal-detail">
                    <div class="account-panel-heading">
                        <h3>Detail Personal</h3>
                        <p>Data ini dipakai untuk verifikasi pesanan, invoice, dan komunikasi dengan penyedia jasa.</p>
                    </div>

                    <div class="account-form-grid">
                        <div class="account-field">
                            <label for="firstName">Nama depan</label>
                            <input id="firstName" class="form-control" type="text" name="first_name" value="<?= htmlspecialchars($firstNameValue, ENT_QUOTES) ?>" required>
                        </div>
                        <div class="account-field">
                            <label for="lastName">Nama belakang</label>
                            <input id="lastName" class="form-control" type="text" name="last_name" value="<?= htmlspecialchars($lastNameValue, ENT_QUOTES) ?>">
                        </div>
                        <div class="account-field">
                            <label for="email">Email</label>
                            <input id="email" class="form-control" type="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '', ENT_QUOTES) ?>" required>
                        </div>
                        <div class="account-field">
                            <label for="phone">Nomor telepon</label>
                            <input id="phone" class="form-control" type="tel" name="phone" value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '', ENT_QUOTES) ?>" placeholder="Contoh: 0812-3456-7890">
                        </div>
                        <div class="account-field account-field-full">
                            <label for="address">Alamat utama</label>
                            <textarea id="address" class="form-control" name="address" rows="4" placeholder="Alamat untuk kebutuhan kunjungan jasa"><?= htmlspecialchars($_SESSION['user']['address'] ?? '', ENT_QUOTES) ?></textarea>
                        </div>
                    </div>
                </section>

                <aside class="account-panel account-photo-panel">
                    <div class="account-panel-heading">
                        <h3>Foto Profile</h3>
                        <p>Gunakan foto yang jelas agar transaksi jasa terasa lebih personal dan terpercaya.</p>
                    </div>
                    <div class="account-avatar-wrap">
                        <div class="profile-photo-preview account-avatar" id="profilePhotoPreview" data-initial="<?= e($userInitial) ?>"><?= e($userInitial) ?></div>
                    </div>
                    <label class="account-upload-box" for="profilePhotoInput">
                        <i class="bi bi-image"></i>
                        <strong>Upload foto baru</strong>
                        <span>Klik untuk memilih file PNG/JPG</span>
                    </label>
                    <input class="visually-hidden" type="file" id="profilePhotoInput" name="profile_photo" accept="image/*">
                    <small class="account-file-note">Preview foto tampil di browser. Penyimpanan foto dapat disambungkan ke backend upload saat dibutuhkan.</small>
                </aside>

                <section class="account-panel" id="security">
                    <div class="account-panel-heading">
                        <h3>Keamanan Akun</h3>
                        <p>Ganti password secara berkala untuk menjaga akses pembeli, penyedia, dan admin tetap aman.</p>
                    </div>
                    <div class="account-form-grid">
                        <div class="account-field">
                            <label for="currentPassword">Password lama</label>
                            <input id="currentPassword" class="form-control" type="password" name="current_password" placeholder="Masukkan password lama">
                        </div>
                        <div class="account-field">
                            <label for="newPassword">Password baru</label>
                            <input id="newPassword" class="form-control" type="password" name="new_password" placeholder="Min. 8 karakter">
                        </div>
                        <div class="account-field account-field-full">
                            <label for="confirmPassword">Ulangi password baru</label>
                            <input id="confirmPassword" class="form-control" type="password" name="confirm_password" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </section>

                <section class="account-panel" id="local-preferences">
                    <div class="account-panel-heading">
                        <h3>Preferensi Jasa Lokal</h3>
                        <p>Bantu BisaBantu menampilkan pengalaman yang lebih relevan sesuai kebutuhan layanan Anda.</p>
                    </div>
                    <div class="account-form-grid">
                        <div class="account-field">
                            <label for="serviceFocus">Minat layanan</label>
                            <input id="serviceFocus" class="form-control" type="text" value="<?= e($serviceFocusValue) ?>" readonly>
                        </div>
                        <div class="account-field">
                            <label for="serviceArea">Area layanan</label>
                            <input id="serviceArea" class="form-control" type="text" value="Kota sekitar lokasi akun" readonly>
                        </div>
                    </div>
                </section>
            </div>
        </form>
    </div>
</main>
