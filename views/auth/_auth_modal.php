<div class="auth-overlay" id="authOverlay" onclick="handleAuthOverlayClick(event)">
    <div class="auth-modal" id="authModal" role="dialog" aria-modal="true" aria-labelledby="authModalTitle">
        <div class="auth-modal-header">
            <div class="auth-modal-brand">
                <div class="auth-brand-dot"><i class="bi bi-tools" aria-hidden="true"></i></div>
                <span class="auth-brand-name">BisaBantu</span>
            </div>
            <button type="button" class="auth-modal-close" onclick="closeAuthModal()" aria-label="Tutup">
                <i class="bi bi-x" aria-hidden="true"></i>
            </button>
            <div class="auth-tab-row">
                <button type="button" class="auth-tab active" id="auth-tab-login" onclick="switchAuthTab('login')">Masuk</button>
                <button type="button" class="auth-tab" id="auth-tab-register" onclick="switchAuthTab('register')">Daftar</button>
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
                        <?= $_GET['auth_error'] === 'empty' ? 'Email dan password wajib diisi.' : 'Email atau password salah. Akun demo memakai password: password.' ?>
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
                <div class="auth-divider">atau</div>
                <button type="button" class="auth-btn-google" id="authGoogleBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Masuk dengan Google
                </button>
                <div class="auth-form-footer">Belum punya akun? <a onclick="switchAuthTab('register')">Daftar sekarang</a></div>
            </div>

            <!-- REGISTER -->
            <div class="auth-panel" id="auth-panel-register">
                <div class="mb-3">
                    <div style="font-size:17px;font-weight:500;margin-bottom:3px">Buat akun baru</div>
                    <div style="font-size:13px;color:var(--color-text-secondary)">Bergabung dengan ribuan pengguna BisaBantu</div>
                </div>

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
                        <div class="auth-role-card selected" id="role-buyer" onclick="selectAuthRole('buyer')">
                            <div class="auth-role-icon" id="ri-buyer"><i class="bi bi-cart3" aria-hidden="true"></i></div>
                            <div>
                                <div class="auth-role-label">Pembeli</div>
                                <div class="auth-role-sub">Cari & pesan jasa</div>
                            </div>
                        </div>
                        <div class="auth-role-card" id="role-provider" onclick="selectAuthRole('provider')">
                            <div class="auth-role-icon" id="ri-provider"><i class="bi bi-briefcase" aria-hidden="true"></i></div>
                            <div>
                                <div class="auth-role-label">Penyedia</div>
                                <div class="auth-role-sub">Tawarkan jasa</div>
                            </div>
                        </div>
                    </div>
                    <div class="auth-provider-note" id="provider-note">
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
                            <input class="auth-form-input" type="text" name="first_name" placeholder="Budi">
                        </div>
                        <div class="auth-form-group">
                            <label class="auth-form-label">Nama belakang</label>
                            <input class="auth-form-input" type="text" name="last_name" placeholder="Santoso">
                        </div>
                    </div>
                    <div class="auth-form-group">
                        <label class="auth-form-label">Email</label>
                        <div class="auth-input-wrap">
                            <input class="auth-form-input" type="email" id="reg-email" name="email" placeholder="budi@email.com">
                            <i class="bi bi-envelope auth-input-icon" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="auth-form-group">
                        <label class="auth-form-label">Nomor telepon</label>
                        <div class="auth-input-wrap">
                            <input class="auth-form-input" type="tel" name="phone" placeholder="+62 812 3456 7890">
                            <i class="bi bi-telephone auth-input-icon" aria-hidden="true"></i>
                        </div>
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
                    <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:1rem;font-weight:500">Buat password</div>
                    <form method="post" action="<?= base_url('index.php?page=auth&action=register') ?>" id="authRegisterForm" novalidate>
                        <input type="hidden" name="role" id="reg-role-input" value="buyer">
                        <div class="auth-form-group">
                            <label class="auth-form-label">Password</label>
                            <div class="auth-input-wrap">
                                <input class="auth-form-input" type="password" id="reg-pass" name="password" placeholder="Min. 8 karakter" oninput="checkAuthStrength(this.value)" required>
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
                        <div class="auth-form-group">
                            <label class="auth-form-label">Konfirmasi password</label>
                            <div class="auth-input-wrap">
                                <input class="auth-form-input" type="password" name="password_confirm" placeholder="Ulangi password" required>
                                <i class="bi bi-eye auth-input-icon" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="auth-form-group">
                            <label class="auth-form-label">Alamat</label>
                            <textarea class="auth-form-input" name="address" rows="2" placeholder="Jl. Contoh No.1, Jakarta Selatan" style="resize:none;line-height:1.5"></textarea>
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
        </div>
    </div>
</div>
