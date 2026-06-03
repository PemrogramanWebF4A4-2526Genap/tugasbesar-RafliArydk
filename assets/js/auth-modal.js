function openAuthModal(tab, role) {
    const overlay = document.getElementById('authOverlay');
    if (!overlay) return;
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
    ['login', 'register'].forEach(function (t) {
        const tabEl = document.getElementById('auth-tab-' + t);
        const panelEl = document.getElementById('auth-panel-' + t);
        if (tabEl) tabEl.classList.toggle('active', t === tab);
        if (panelEl) panelEl.classList.toggle('active', t === tab);
    });
    if (tab === 'register') goAuthStep(1);
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
        const pass = document.getElementById('reg-pass');
        if (pass && pass.value.length < 8) {
            if (typeof showToast === 'function') showToast('Password minimal 8 karakter', 'warning');
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

function initAuthForms() {
    const loginForm = document.getElementById('authLoginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const email = document.getElementById('login-email')?.value.trim();
            const password = document.getElementById('login-pass')?.value || '';
            if (!email || !password) {
                e.preventDefault();
                if (typeof showToast === 'function') showToast('Masukkan email Anda', 'warning');
                return;
            }
        });
    }

    const regForm = document.getElementById('authRegisterForm');
    if (regForm) {
        regForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const pass = document.getElementById('reg-pass')?.value || '';
            if (pass.length < 8) {
                if (typeof showToast === 'function') showToast('Password minimal 8 karakter', 'warning');
                return;
            }
            showAuthSuccess('reg-success');
            if (typeof showToast === 'function') showToast('Akun berhasil dibuat! Silakan cek email Anda.', 'success');
            setTimeout(closeAuthModal, 2000);
        });
    }

    const googleBtn = document.getElementById('authGoogleBtn');
    if (googleBtn) {
        googleBtn.addEventListener('click', function () {
            if (typeof showToast === 'function') showToast('Login Google akan segera tersedia', 'info');
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
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeAuthModal();
});

document.addEventListener('DOMContentLoaded', function () {
    initAuthForms();

    const params = new URLSearchParams(window.location.search);
    const auth = params.get('auth');
    if (auth === 'login' || auth === 'register') {
        openAuthModal(auth);
    }
});
