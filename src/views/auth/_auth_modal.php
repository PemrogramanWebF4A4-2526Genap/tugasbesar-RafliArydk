<div class="auth-overlay" id="authOverlay" onclick="handleAuthOverlayClick(event)">
    <div class="auth-modal" id="authModal" role="dialog" aria-modal="true" aria-labelledby="authModalTitle">
        <button type="button" class="auth-modal-close" onclick="closeAuthModal()" aria-label="Tutup" style="position: absolute; right: 16px; top: 16px; z-index: 10;">
            <i class="bi bi-x" aria-hidden="true"></i>
        </button>
        <div class="auth-modal-header">
            <div class="auth-modal-brand">
                <div class="auth-brand-dot"><i class="bi bi-tools" aria-hidden="true"></i></div>
                <span class="auth-brand-name">BisaBantu</span>
            </div>
            <div class="auth-tab-row">
                <button type="button" class="auth-tab active" id="auth-tab-login" onclick="switchAuthTab('login')">Masuk</button>
                <button type="button" class="auth-tab" id="auth-tab-register" onclick="switchAuthTab('register')">Daftar</button>
                <button type="button" class="auth-tab" id="auth-tab-verify" onclick="switchAuthTab('verify')" style="display:none">Verifikasi</button>
            </div>
        </div>

        <div class="auth-modal-body">
            <!-- LOGIN -->
            <div class="auth-panel active" id="auth-panel-login">
                <div class="mb-4">
                    <div id="authModalTitle" style="font-size:17px;font-weight:500;margin-bottom:3px">Selamat datang kembali</div>
                    <div style="font-size:13px;color:var(--color-text-secondary)">Masuk ke akun BisaBantu Anda</div>
                </div>
                <div class="auth-alert-success" id="login-success">
                    <i class="bi bi-check-circle" style="font-size:16px" aria-hidden="true"></i>
                    Login berhasil! Mengarahkan ke dashboard...
                </div>
                <?php if (isset($_GET['auth_error'])): ?>
                    <div class="auth-alert-error show" id="login-error">
                        <i class="bi bi-exclamation-circle" style="font-size:16px" aria-hidden="true"></i>
                        <?php
                        $authErrorMessages = [
                            'empty' => 'Email dan password wajib diisi.',
                            'email' => 'Format email tidak valid.',
                            'invalid' => 'Email atau password salah.',
                        ];
                        ?>
                        <?= e($authErrorMessages[$_GET['auth_error']] ?? 'Email atau password salah.') ?>
                    </div>
                <?php endif; ?>
                <form id="authLoginForm" method="post" action="<?= base_url('index.php?page=auth&action=login') ?>" novalidate>
                    <div class="auth-form-group">
                        <label class="auth-form-label" for="login-email">Email</label>
                        <div class="auth-input-wrap">
                            <input class="auth-form-input" type="email" id="login-email" name="email" placeholder="nama@email.com" required>
                            <i class="bi bi-envelope auth-input-icon" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="auth-form-group">
                        <label class="auth-form-label" for="login-pass">Password</label>
                        <div class="auth-input-wrap">
                            <input class="auth-form-input" type="password" id="login-pass" name="password" placeholder="Masukkan password" required>
                            <i class="bi bi-eye auth-input-icon" id="toggle-login-pass" onclick="toggleAuthPass('login-pass','toggle-login-pass')" role="button" tabindex="0" aria-label="Tampilkan password"></i>
                        </div>
                    </div>
                    <div class="auth-remember-row">
                        <label class="auth-checkbox-wrap">
                            <input type="checkbox" name="remember"> Ingat saya
                        </label>
                        <span class="auth-forgot-link" id="authForgotLink" role="button" tabindex="0">Lupa password?</span>
                    </div>
                    <button type="submit" class="auth-btn-submit">
                        <i class="bi bi-box-arrow-in-right" style="margin-right:6px" aria-hidden="true"></i>Masuk
                    </button>
                </form>
                <div class="auth-form-error" id="login-form-error" role="alert" aria-live="assertive"></div>
                <div class="auth-form-footer">Belum punya akun? <a onclick="switchAuthTab('register')">Daftar sekarang</a></div>
            </div>

            <!-- REGISTER -->
            <div class="auth-panel" id="auth-panel-register">
                <div class="mb-3">
                    <div style="font-size:17px;font-weight:500;margin-bottom:3px">Buat akun baru</div>
                    <div style="font-size:13px;color:var(--color-text-secondary)">Bergabung dengan ribuan pengguna BisaBantu</div>
                </div>
                <?php $regRole = $_GET['role'] ?? 'buyer'; ?>
                <?php if (isset($_GET['register_error'])): ?>
                    <div class="auth-alert-error show" id="register-error">
                        <i class="bi bi-exclamation-circle" style="font-size:16px" aria-hidden="true"></i>
                        <?php
                        $registerErrorMessages = [
                            'exists'         => 'Email sudah terdaftar.',
                            'email'          => 'Format email tidak valid.',
                            'role'           => 'Pilih tipe akun dengan benar.',
                            'invalid'        => 'Lengkapi data pendaftaran dengan benar.',
                            'invalid_domain' => 'Domain email tidak valid atau tidak dapat menerima email. Gunakan email dari provider terpercaya (Gmail, Yahoo, dll).',
                        ];
                        ?>
                        <?= e($registerErrorMessages[$_GET['register_error']] ?? 'Lengkapi data pendaftaran dengan benar.') ?>
                    </div>
                <?php endif; ?>

                <div id="reg-step-1">
                    <div class="auth-step-indicator">
                        <div class="auth-step-dot active" id="sdot1">1</div>
                        <div class="auth-step-line" id="sline1"></div>
                        <div class="auth-step-dot" id="sdot2">2</div>
                        <div class="auth-step-line" id="sline2"></div>
                        <div class="auth-step-dot" id="sdot3">3</div>
                    </div>
                    <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:1rem;font-weight:500">Pilih tipe akun</div>
                    <div class="auth-role-select">
                        <div class="auth-role-card<?= $regRole === 'buyer' ? ' selected' : '' ?>" id="role-buyer" onclick="selectAuthRole('buyer')">
                            <div class="auth-role-icon" id="ri-buyer"><i class="bi bi-cart3" aria-hidden="true"></i></div>
                            <div>
                                <div class="auth-role-label">Pembeli</div>
                                <div class="auth-role-sub">Cari & pesan jasa</div>
                            </div>
                        </div>
                        <div class="auth-role-card<?= $regRole === 'provider' ? ' selected' : '' ?>" id="role-provider" onclick="selectAuthRole('provider')">
                            <div class="auth-role-icon" id="ri-provider"><i class="bi bi-briefcase" aria-hidden="true"></i></div>
                            <div>
                                <div class="auth-role-label">Penyedia</div>
                                <div class="auth-role-sub">Tawarkan jasa</div>
                            </div>
                        </div>
                    </div>
                    <div class="auth-provider-note" id="provider-note" <?= $regRole === 'provider' ? ' class="show"' : '' ?>>
                        <i class="bi bi-info-circle" style="margin-right:4px" aria-hidden="true"></i>
                        Akun penyedia perlu verifikasi admin sebelum bisa menawarkan jasa.
                    </div>
                    <button type="button" class="auth-btn-submit" onclick="goAuthStep(2)">
                        Lanjutkan <i class="bi bi-arrow-right" style="margin-left:6px" aria-hidden="true"></i>
                    </button>
                </div>

                <div id="reg-step-2" style="display:none">
                    <div class="auth-step-indicator">
                        <div class="auth-step-dot done" id="sdot1b"><i class="bi bi-check" style="font-size:10px" aria-hidden="true"></i></div>
                        <div class="auth-step-line done"></div>
                        <div class="auth-step-dot active" id="sdot2b">2</div>
                        <div class="auth-step-line" id="sline2b"></div>
                        <div class="auth-step-dot" id="sdot3b">3</div>
                    </div>
                    <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:1rem;font-weight:500">Data diri</div>
                    <div class="auth-form-row">
                        <div class="auth-form-group">
                            <label class="auth-form-label">Nama depan</label>
                            <input class="auth-form-input" type="text" name="first_name" form="authRegisterForm" placeholder="Nama Depan" value="<?= htmlspecialchars($_GET['first_name'] ?? '', ENT_QUOTES) ?>" required>
                        </div>
                        <div class="auth-form-group">
                            <label class="auth-form-label">Nama belakang</label>
                            <input class="auth-form-input" type="text" name="last_name" form="authRegisterForm" placeholder="Nama Belakang" value="<?= htmlspecialchars($_GET['last_name'] ?? '', ENT_QUOTES) ?>">
                        </div>
                    </div>
                    <div class="auth-form-group">
                        <label class="auth-form-label">Email</label>
                        <div class="auth-input-wrap">
                            <input class="auth-form-input" type="email" id="reg-email" name="email" form="authRegisterForm" placeholder="Email" value="<?= htmlspecialchars($_GET['email'] ?? '', ENT_QUOTES) ?>" required>
                            <i class="bi bi-envelope auth-input-icon" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="auth-form-group">
                        <label class="auth-form-label">Nomor telepon</label>
                        <div class="auth-input-wrap">
                            <input class="auth-form-input" type="tel" name="phone" form="authRegisterForm" placeholder="No Telepon" value="<?= htmlspecialchars($_GET['phone'] ?? '', ENT_QUOTES) ?>">
                            <i class="bi bi-telephone auth-input-icon" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="auth-form-group">
                        <label class="auth-form-label">Password</label>
                        <div class="auth-input-wrap">
                            <input class="auth-form-input" type="password" id="reg-pass" name="password" form="authRegisterForm" placeholder="Min. 8 karakter" oninput="checkAuthStrength(this.value)" required>
                            <i class="bi bi-eye auth-input-icon" id="toggle-reg-pass" onclick="toggleAuthPass('reg-pass','toggle-reg-pass')" role="button" tabindex="0" aria-label="Tampilkan password"></i>
                        </div>
                        <div class="auth-password-strength">
                            <div class="auth-strength-bar" id="sb1"></div>
                            <div class="auth-strength-bar" id="sb2"></div>
                            <div class="auth-strength-bar" id="sb3"></div>
                            <div class="auth-strength-bar" id="sb4"></div>
                        </div>
                        <div class="auth-str-label" id="str-label">Masukkan password</div>
                    </div>
                    <div style="display:flex;gap:8px;margin-top:0.25rem">
                        <button type="button" class="auth-btn-outline" style="flex:1;padding:11px" onclick="goAuthStep(1)">
                            <i class="bi bi-arrow-left" style="margin-right:4px" aria-hidden="true"></i>Kembali
                        </button>
                        <button type="button" class="auth-btn-submit" style="flex:2;margin-top:0" onclick="goAuthStep(3)">
                            Lanjutkan <i class="bi bi-arrow-right" style="margin-left:6px" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <div id="reg-step-3" style="display:none">
                    <div class="auth-step-indicator">
                        <div class="auth-step-dot done"><i class="bi bi-check" style="font-size:10px" aria-hidden="true"></i></div>
                        <div class="auth-step-line done"></div>
                        <div class="auth-step-dot done"><i class="bi bi-check" style="font-size:10px" aria-hidden="true"></i></div>
                        <div class="auth-step-line done"></div>
                        <div class="auth-step-dot active">3</div>
                    </div>
                    <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:1rem;font-weight:500">Selesai</div>
                    <form method="post" action="<?= base_url('index.php?page=auth&action=register') ?>" id="authRegisterForm" novalidate>
                        <input type="hidden" name="role" id="reg-role-input" value="<?= htmlspecialchars($regRole, ENT_QUOTES) ?>">
                        <div class="auth-form-group">
                            <label class="auth-form-label">Konfirmasi password</label>
                            <div class="auth-input-wrap">
                                <input class="auth-form-input" type="password" name="password_confirm" form="authRegisterForm" placeholder="Ulangi password" required>
                                <i class="bi bi-eye auth-input-icon" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="auth-form-group">
                            <label class="auth-form-label">Alamat</label>
                            <textarea class="auth-form-input" name="address" rows="2" form="authRegisterForm" placeholder="Jl. Contoh No.1, Jakarta Selatan" style="resize:none;line-height:1.5"><?= htmlspecialchars($_GET['address'] ?? '', ENT_QUOTES) ?></textarea>
                        </div>
                        <label class="auth-checkbox-wrap" style="margin-bottom:1rem;align-items:flex-start;gap:8px">
                            <input type="checkbox" required style="margin-top:2px">
                            <span>Saya menyetujui <a style="color:var(--auth-accent);cursor:pointer">syarat & ketentuan</a> dan <a style="color:var(--auth-accent);cursor:pointer">kebijakan privasi</a> BisaBantu.</span>
                        </label>
                        <div class="auth-alert-success" id="reg-success">
                            <i class="bi bi-check-circle" style="font-size:16px" aria-hidden="true"></i>
                            Akun berhasil dibuat! Silakan cek email Anda.
                        </div>
                        <div style="display:flex;gap:8px">
                            <button type="button" class="auth-btn-outline" style="flex:1;padding:11px" onclick="goAuthStep(2)">
                                <i class="bi bi-arrow-left" style="margin-right:4px" aria-hidden="true"></i>Kembali
                            </button>
                            <button type="submit" class="auth-btn-submit" style="flex:2;margin-top:0">
                                <i class="bi bi-person-check" style="margin-right:6px" aria-hidden="true"></i>Buat Akun
                            </button>
                        </div>
                    </form>
                </div>

                <div class="auth-form-footer" style="margin-top:1rem">Sudah punya akun? <a onclick="switchAuthTab('login')">Masuk di sini</a></div>
            </div>

            <!-- VERIFY EMAIL -->
            <div class="auth-panel" id="auth-panel-verify">
                <div class="mb-4 text-center">
                    <div class="auth-verify-icon" aria-hidden="true"><i class="bi bi-envelope-check"></i></div>
                    <div style="font-size:17px;font-weight:500;margin-bottom:4px">Verifikasi Email</div>
                    <div style="font-size:13px;color:var(--color-text-secondary)">Masukkan kode 6-digit yang telah dikirim ke</div>
                    <div id="verify-email-display" style="font-size:13px;font-weight:600;color:var(--auth-accent);margin-top:3px"></div>
                </div>

                <?php if (isset($_GET['verify_error'])): ?>
                    <div class="auth-alert-error show" id="verify-error-box">
                        <i class="bi bi-exclamation-circle" style="font-size:16px" aria-hidden="true"></i>
                        <?php
                        $verifyErrorMessages = [
                            'invalid' => 'Kode verifikasi salah atau sudah kedaluwarsa.',
                            'expired' => 'Kode verifikasi sudah kedaluwarsa. Silakan daftar ulang.',
                            'no_pending' => 'Tidak ada sesi pendaftaran aktif. Silakan daftar ulang.',
                        ];
                        ?>
                        <?= e($verifyErrorMessages[$_GET['verify_error']] ?? 'Kode tidak valid.') ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('index.php?page=auth&action=verify_email') ?>" id="authVerifyForm" novalidate data-no-validate>
                    <div class="auth-otp-wrap">
                        <input class="auth-otp-input" type="text" maxlength="1" id="otp0" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" aria-label="Digit 1">
                        <input class="auth-otp-input" type="text" maxlength="1" id="otp1" inputmode="numeric" pattern="[0-9]" aria-label="Digit 2">
                        <input class="auth-otp-input" type="text" maxlength="1" id="otp2" inputmode="numeric" pattern="[0-9]" aria-label="Digit 3">
                        <input class="auth-otp-input" type="text" maxlength="1" id="otp3" inputmode="numeric" pattern="[0-9]" aria-label="Digit 4">
                        <input class="auth-otp-input" type="text" maxlength="1" id="otp4" inputmode="numeric" pattern="[0-9]" aria-label="Digit 5">
                        <input class="auth-otp-input" type="text" maxlength="1" id="otp5" inputmode="numeric" pattern="[0-9]" aria-label="Digit 6">
                    </div>
                    <input type="hidden" name="otp_code" id="otp_code_hidden">

                    <div class="auth-otp-timer" id="auth-otp-timer">
                        <i class="bi bi-clock" aria-hidden="true"></i>
                        Kode berlaku: <span id="otp-countdown">15:00</span>
                    </div>

                    <button type="submit" class="auth-btn-submit" id="verify-submit-btn">
                        <i class="bi bi-shield-check" style="margin-right:6px" aria-hidden="true"></i>Verifikasi Akun
                    </button>
                </form>

                <div class="auth-otp-resend">
                    Tidak menerima kode?
                    <a href="<?= base_url('index.php?page=auth&action=resend_otp') ?>" id="auth-resend-otp">Kirim ulang</a>
                </div>
                <div class="auth-form-footer" style="margin-top:0.5rem">
                    <a onclick="switchAuthTab('register'); goAuthStep(1);" style="cursor:pointer">Kembali ke pendaftaran</a>
                </div>
            </div>
        </div>
    </div>
</div>