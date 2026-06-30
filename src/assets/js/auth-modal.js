function openAuthModal(tab, role) {
    const overlay = document.getElementById('authOverlay');
    if (!overlay) return;

    const navbar = document.getElementById('navbarMain');
    if (navbar && navbar.classList.contains('show')) {
        document.querySelector('[data-bs-target="#navbarMain"]')?.click();
    }

    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
    switchAuthTab(tab || 'login');
    if (role) {
        setTimeout(function () {
            selectAuthRole(role);
        }, 0);
    }
}

function closeAuthModal() {
    const overlay = document.getElementById('authOverlay');
    if (!overlay) return;
    overlay.classList.remove('show');
    document.body.style.overflow = '';
}

function handleAuthOverlayClick(e) {
    if (e.target === document.getElementById('authOverlay')) {
        closeAuthModal();
    }
}

function switchAuthTab(tab) {
    ['login', 'register', 'verify'].forEach(function (t) {
        const tabEl = document.getElementById('auth-tab-' + t);
        const panelEl = document.getElementById('auth-panel-' + t);
        if (tabEl) tabEl.classList.toggle('active', t === tab);
        if (panelEl) panelEl.classList.toggle('active', t === tab);
    });
    if (tab === 'register') goAuthStep(1);
    
    // Hide the entire tab row when on the verify panel
    const tabRow = document.querySelector('.auth-tab-row');
    if (tabRow) {
        tabRow.style.display = tab === 'verify' ? 'none' : '';
    }
}

function toggleAuthPass(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const ico = document.getElementById(iconId);
    if (!inp || !ico) return;
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'bi bi-eye-slash auth-input-icon';
    } else {
        inp.type = 'password';
        ico.className = 'bi bi-eye auth-input-icon';
    }
}

let selectedAuthRole = 'buyer';

function selectAuthRole(role) {
    selectedAuthRole = role;
    const roleInput = document.getElementById('reg-role-input');
    if (roleInput) roleInput.value = role;

    ['buyer', 'provider'].forEach(function (r) {
        const card = document.getElementById('role-' + r);
        if (card) card.classList.toggle('selected', r === role);
    });

    const note = document.getElementById('provider-note');
    if (note) note.classList.toggle('show', role === 'provider');
}

function goAuthStep(n) {
    if (n === 3) {
        const firstNameInput = document.querySelector('input[name="first_name"][form="authRegisterForm"]');
        const emailInput = document.getElementById('reg-email');
        const passInput = document.getElementById('reg-pass');

        const firstName = firstNameInput?.value.trim() || '';
        const email = emailInput?.value.trim() || '';
        const pass = passInput?.value || '';

        if (!firstName) {
            if (typeof showToast === 'function') showToast('Nama depan wajib diisi', 'warning');
            firstNameInput?.focus();
            return;
        }
        if (!email) {
            if (typeof showToast === 'function') showToast('Email wajib diisi', 'warning');
            emailInput?.focus();
            return;
        }
        if (!isValidAuthEmail(email)) {
            if (typeof showToast === 'function') showToast('Format email tidak valid', 'warning');
            emailInput?.focus();
            return;
        }
        if (!pass) {
            if (typeof showToast === 'function') showToast('Password wajib diisi', 'warning');
            passInput?.focus();
            return;
        }
        if (pass.length < 8) {
            if (typeof showToast === 'function') showToast('Password minimal 8 karakter', 'warning');
            passInput?.focus();
            return;
        }
    }

    [1, 2, 3].forEach(function (i) {
        const step = document.getElementById('reg-step-' + i);
        if (step) step.style.display = i === n ? 'block' : 'none';
    });
}

function checkAuthStrength(val) {
    const bars = [
        document.getElementById('sb1'),
        document.getElementById('sb2'),
        document.getElementById('sb3'),
        document.getElementById('sb4'),
    ];
    const lbl = document.getElementById('str-label');
    if (!lbl) return;

    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['#E24B4A', '#EF9F27', '#EF9F27', '#B5451B'];
    const labels = ['Terlalu lemah', 'Cukup', 'Sedang', 'Kuat'];

    bars.forEach(function (b, i) {
        if (b) b.style.background = i < score ? colors[score - 1] : 'var(--color-border-tertiary)';
    });

    lbl.textContent = val.length === 0 ? 'Masukkan password' : labels[score - 1] || 'Terlalu lemah';
    lbl.style.color = score <= 1 ? '#A32D2D' : score <= 2 ? '#854F0B' : '#9a3615';
}

function showAuthSuccess(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.add('show');
        setTimeout(function () {
            el.classList.remove('show');
        }, 3000);
    }
}

function showLoginInlineError(message) {
    const el = document.getElementById('login-form-error');
    if (!el) return;
    el.textContent = message;
    el.classList.add('show');
}

function clearLoginInlineError() {
    const el = document.getElementById('login-form-error');
    if (!el) return;
    el.textContent = '';
    el.classList.remove('show');
}

function isValidAuthEmail(email) {
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        return false;
    }
    const domain = email.split('@').pop().toLowerCase();
    return !domain.endsWith('.co');
}

function initAuthForms() {
    const loginForm = document.getElementById('authLoginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            if (e.defaultPrevented) return;
            const emailInput = document.getElementById('login-email');
            const passwordInput = document.getElementById('login-pass');
            const email = emailInput?.value.trim() || '';
            const password = passwordInput?.value || '';
            clearLoginInlineError();
            if (!email) {
                e.preventDefault();
                showLoginInlineError('Masukkan email Anda.');
                emailInput?.focus();
                return;
            }
            if (!isValidAuthEmail(email)) {
                e.preventDefault();
                showLoginInlineError('Email tidak valid.');
                emailInput?.focus();
                return;
            }
            if (!password) {
                e.preventDefault();
                showLoginInlineError('Masukkan password Anda.');
                passwordInput?.focus();
                return;
            }
        });

        const loginInputs = [document.getElementById('login-email'), document.getElementById('login-pass')];
        loginInputs.forEach(function (input) {
            if (input) {
                input.addEventListener('input', clearLoginInlineError);
            }
        });
    }

    const regForm = document.getElementById('authRegisterForm');
    if (regForm) {
        regForm.addEventListener('submit', function (e) {
            if (e.defaultPrevented) return;
            const emailInput = document.getElementById('reg-email');
            const email = emailInput?.value.trim() || '';
            const confirm = regForm.querySelector('[name="password_confirm"]')?.value || '';
            const pass = document.getElementById('reg-pass')?.value || '';
            if (!isValidAuthEmail(email)) {
                e.preventDefault();
                if (typeof showToast === 'function') showToast('Email tidak valid', 'warning');
                emailInput?.focus();
                goAuthStep(2);
                return;
            }
            if (pass.length < 8) {
                e.preventDefault();
                if (typeof showToast === 'function') showToast('Password minimal 8 karakter', 'warning');
                return;
            }
            if (pass !== confirm) {
                e.preventDefault();
                if (typeof showToast === 'function') showToast('Konfirmasi password belum sama', 'warning');
            }
        });
    }

    const forgot = document.getElementById('authForgotLink');
    if (forgot) {
        const openForgot = function () {
            if (typeof showToast === 'function') showToast('Link reset password dikirim ke email (demo)', 'info');
        };
        forgot.addEventListener('click', openForgot);
        forgot.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openForgot();
            }
        });
    }

    const verifyForm = document.getElementById('authVerifyForm');
    if (verifyForm) {
        verifyForm.addEventListener('submit', function (e) {
            syncOtpHidden();
            const code = document.getElementById('otp_code_hidden')?.value || '';
            if (code.length < 6 || /\D/.test(code)) {
                e.preventDefault();
                if (typeof showToast === 'function') showToast('Masukkan kode 6 digit yang dikirim ke email Anda.', 'warning');
                document.getElementById('otp0')?.focus();
            }
        });
    }
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeAuthModal();
});

// ---------------------------------------------------------------------------
// OTP INPUT HANDLING
// ---------------------------------------------------------------------------
function initOtpInputs() {
    const inputs = Array.from(document.querySelectorAll('.auth-otp-input'));
    if (!inputs.length) return;

    inputs.forEach(function (input, index) {
        // Only allow single digit
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(-1);
            if (this.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            syncOtpHidden();
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
                syncOtpHidden();
            }
        });

        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            pasted.split('').forEach(function (char, i) {
                if (inputs[index + i]) {
                    inputs[index + i].value = char;
                }
            });
            const nextEmpty = inputs.find(function (inp) { return !inp.value; });
            if (nextEmpty) nextEmpty.focus();
            syncOtpHidden();
        });
    });
}

function syncOtpHidden() {
    const inputs = Array.from(document.querySelectorAll('.auth-otp-input'));
    const hidden = document.getElementById('otp_code_hidden');
    if (hidden) hidden.value = inputs.map(function (i) { return i.value; }).join('');
}

// ---------------------------------------------------------------------------
// OTP COUNTDOWN TIMER
// ---------------------------------------------------------------------------
let otpCountdownInterval = null;

function startOtpCountdown(seconds) {
    clearInterval(otpCountdownInterval);
    const el = document.getElementById('otp-countdown');
    if (!el) return;

    let remaining = seconds;
    function tick() {
        const m = Math.floor(remaining / 60);
        const s = remaining % 60;
        el.textContent = m + ':' + String(s).padStart(2, '0');
        if (remaining <= 0) {
            clearInterval(otpCountdownInterval);
            el.textContent = 'Kedaluwarsa';
            el.style.color = 'var(--color-danger, #e24b4a)';
            const btn = document.getElementById('verify-submit-btn');
            if (btn) btn.disabled = true;
        }
        remaining--;
    }
    tick();
    otpCountdownInterval = setInterval(tick, 1000);
}

// ---------------------------------------------------------------------------
// VERIFY PANEL: populate email display + resent toast
// ---------------------------------------------------------------------------
function initVerifyPanel() {
    const params = new URLSearchParams(window.location.search);
    const pendingEmail = params.get('pending_email');
    const resent = params.get('resent');
    const emailDisplay = document.getElementById('verify-email-display');

    if (emailDisplay && pendingEmail) {
        emailDisplay.textContent = decodeURIComponent(pendingEmail);
    }

    if (resent === '1' && typeof showToast === 'function') {
        showToast('Kode verifikasi baru telah dikirim ke email Anda.', 'success');
    }

    startOtpCountdown(900);
    initOtpInputs();

    // Auto-submit when all 6 digits are filled
    const form = document.getElementById('authVerifyForm');
    const inputs = Array.from(document.querySelectorAll('.auth-otp-input'));
    if (form && inputs.length === 6) {
        inputs[5].addEventListener('input', function () {
            if (inputs.every(function (i) { return i.value; })) {
                syncOtpHidden();
                form.submit();
            }
        });
    }
}

// ---------------------------------------------------------------------------
// DOMContentLoaded
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    initAuthForms();

    const params = new URLSearchParams(window.location.search);
    const auth = params.get('auth');
    const role = params.get('role');
    const registerStep = params.get('register_step');

    if (auth === 'login' || auth === 'register' || auth === 'verify') {
        openAuthModal(auth);
    }
    if (auth === 'register') {
        if (role === 'provider' || role === 'buyer') {
            selectAuthRole(role);
        }
        if (registerStep) {
            goAuthStep(Number(registerStep));
        }
    }
    if (auth === 'verify') {
        initVerifyPanel();
    }
});

