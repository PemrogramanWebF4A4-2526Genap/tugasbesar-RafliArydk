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
    ['login', 'register', 'verify', 'forgot', 'forgot-verify', 'forgot-reset'].forEach(function (t) {
        const tabEl = document.getElementById('auth-tab-' + t);
        const panelEl = document.getElementById('auth-panel-' + t);
        if (tabEl) tabEl.classList.toggle('active', t === tab);
        if (panelEl) panelEl.classList.toggle('active', t === tab);
    });
    if (tab === 'register') goAuthStep(1);
    
    // Hide the entire tab row when on the verify or forgot panels
    const tabRow = document.querySelector('.auth-tab-row');
    if (tabRow) {
        tabRow.style.display = (tab === 'verify' || tab.startsWith('forgot')) ? 'none' : '';
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
            showCenterAlert('Nama depan wajib diisi');
            firstNameInput?.focus();
            return;
        }
        if (!email) {
            showCenterAlert('Email wajib diisi');
            emailInput?.focus();
            return;
        }
        if (!isValidAuthEmail(email)) {
            showCenterAlert('Format email tidak valid');
            emailInput?.focus();
            return;
        }
        if (!pass) {
            showCenterAlert('Password wajib diisi');
            passInput?.focus();
            return;
        }
        if (pass.length < 8) {
            showCenterAlert('Password minimal 8 karakter');
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

function showInlineError(inputElement, message) {
    if (!inputElement) return;
    inputElement.classList.add('error');
    
    // Check if error message already exists
    let errorEl = inputElement.parentElement.nextElementSibling;
    if (!errorEl || !errorEl.classList.contains('auth-inline-error')) {
        errorEl = document.createElement('div');
        errorEl.className = 'auth-inline-error';
        // bi-exclamation-triangle icon
        errorEl.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> <span class="err-msg"></span>';
        inputElement.parentElement.after(errorEl);
    }
    errorEl.querySelector('.err-msg').textContent = message;
    
    // Listen for input to clear error
    const inputHandler = function() {
        inputElement.classList.remove('error');
        if (errorEl && errorEl.parentNode) {
            errorEl.remove();
        }
        inputElement.removeEventListener('input', inputHandler);
    };
    inputElement.addEventListener('input', inputHandler);
}

function clearInlineErrors(formElement) {
    if (!formElement) return;
    const inputs = formElement.querySelectorAll('.auth-form-input.error');
    inputs.forEach(i => i.classList.remove('error'));
    
    const errors = formElement.querySelectorAll('.auth-inline-error');
    errors.forEach(e => e.remove());
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
            clearInlineErrors(loginForm);
            
            const emailInput = document.getElementById('login-email');
            const passwordInput = document.getElementById('login-pass');
            
            const email = emailInput?.value.trim() || '';
            const password = passwordInput?.value || '';

            if (!email) {
                e.preventDefault();
                showInlineError(emailInput, 'Masukkan email Anda.');
                emailInput?.focus();
                return;
            }
            if (!isValidAuthEmail(email)) {
                e.preventDefault();
                showInlineError(emailInput, 'Email tidak valid.');
                emailInput?.focus();
                return;
            }
            if (!password) {
                e.preventDefault();
                showInlineError(passwordInput, 'Masukkan password Anda.');
                passwordInput?.focus();
                return;
            }
        });
    }

    const regForm = document.getElementById('authRegisterForm');
    if (regForm) {
        regForm.addEventListener('submit', function (e) {
            clearInlineErrors(regForm);
            if (e.defaultPrevented) return;
            const emailInput = document.getElementById('reg-email');
            const email = emailInput?.value.trim() || '';
            const confirmInput = regForm.querySelector('[name="password_confirm"]');
            const confirm = confirmInput?.value || '';
            const passInput = document.getElementById('reg-pass');
            const pass = passInput?.value || '';
            
            if (!isValidAuthEmail(email)) {
                e.preventDefault();
                showInlineError(emailInput, 'Email tidak valid');
                emailInput?.focus();
                goAuthStep(2);
                return;
            }
            if (pass.length < 8) {
                e.preventDefault();
                showInlineError(passInput, 'Password minimal 8 karakter');
                goAuthStep(3);
                return;
            }
            if (pass !== confirm) {
                e.preventDefault();
                showInlineError(confirmInput, 'Konfirmasi password belum sama');
                goAuthStep(3);
            }
        });
    }

    const forgot = document.getElementById('authForgotLink');
    if (forgot) {
        forgot.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                switchAuthTab('forgot');
            }
        });
    }

    const verifyForm = document.getElementById('authVerifyForm');
    if (verifyForm) {
        verifyForm.addEventListener('submit', function (e) {
            clearInlineErrors(verifyForm);
            syncOtpHidden('authVerifyForm');
            const code = verifyForm.querySelector('input[name="otp_code"]')?.value || '';
            const otpFirst = document.getElementById('otp0');
            if (code.length < 6 || /\D/.test(code)) {
                e.preventDefault();
                showInlineError(otpFirst, 'Masukkan kode 6 digit yang dikirim ke email Anda.');
                otpFirst?.focus();
            }
        });
    }

    const forgotVerifyForm = document.getElementById('authForgotVerifyForm');
    if (forgotVerifyForm) {
        forgotVerifyForm.addEventListener('submit', function (e) {
            clearInlineErrors(forgotVerifyForm);
            syncOtpHidden('authForgotVerifyForm');
            const code = forgotVerifyForm.querySelector('input[name="otp_code"]')?.value || '';
            const fotpFirst = document.getElementById('fotp0');
            if (code.length < 6 || /\D/.test(code)) {
                e.preventDefault();
                showInlineError(fotpFirst, 'Masukkan kode 6 digit yang dikirim ke email Anda.');
                fotpFirst?.focus();
            }
        });
    }

    const forgotRequestForm = document.getElementById('authForgotRequestForm');
    if (forgotRequestForm) {
        forgotRequestForm.addEventListener('submit', function (e) {
            clearInlineErrors(forgotRequestForm);
            const emailInput = document.getElementById('fotp-email');
            const email = emailInput?.value.trim() || '';
            if (!email) {
                e.preventDefault();
                showInlineError(emailInput, 'Masukkan email Anda.');
                emailInput?.focus();
            } else if (!isValidAuthEmail(email)) {
                e.preventDefault();
                showInlineError(emailInput, 'Email tidak valid.');
                emailInput?.focus();
            }
        });
    }

    const forgotResetForm = document.getElementById('authForgotResetForm');
    if (forgotResetForm) {
        forgotResetForm.addEventListener('submit', function (e) {
            clearInlineErrors(forgotResetForm);
            const passInput = document.getElementById('freset-pass');
            const confirmInput = document.getElementById('freset-confirm');
            const pass = passInput?.value || '';
            const confirm = confirmInput?.value || '';

            if (pass.length < 8) {
                e.preventDefault();
                showInlineError(passInput, 'Password minimal 8 karakter');
                passInput?.focus();
                return;
            }
            if (pass !== confirm) {
                e.preventDefault();
                showInlineError(confirmInput, 'Konfirmasi password tidak cocok');
                confirmInput?.focus();
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
    ['authVerifyForm', 'authForgotVerifyForm'].forEach(function(formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const inputs = Array.from(form.querySelectorAll('.auth-otp-input'));
        if (!inputs.length) return;

        inputs.forEach(function (input, index) {
            input.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '').slice(-1);
                if (this.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                syncOtpHidden(formId);
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    inputs[index - 1].focus();
                    inputs[index - 1].value = '';
                    syncOtpHidden(formId);
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
                syncOtpHidden(formId);
            });
        });
    });
}

function syncOtpHidden(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    const inputs = Array.from(form.querySelectorAll('.auth-otp-input'));
    let hidden = form.querySelector('input[name="otp_code"]');
    
    if (hidden) hidden.value = inputs.map(function (i) { return i.value; }).join('');
}

// ---------------------------------------------------------------------------
// OTP COUNTDOWN TIMER
// ---------------------------------------------------------------------------
let otpCountdownInterval = null;

function startOtpCountdown(seconds, targetId = 'otp-countdown') {
    clearInterval(otpCountdownInterval);
    const el = document.getElementById(targetId);
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
function initVerifyPanel(type = 'register') {
    const params = new URLSearchParams(window.location.search);
    const pendingEmail = params.get('pending_email');
    const resent = params.get('resent');
    
    let displayId = 'verify-email-display';
    let formId = 'authVerifyForm';
    let timerId = 'otp-countdown';
    
    if (type === 'forgot') {
        displayId = 'forgot-verify-email-display';
        formId = 'authForgotVerifyForm';
        timerId = 'fotp-countdown';
    }
    
    const emailDisplay = document.getElementById(displayId);

    if (emailDisplay && pendingEmail) {
        emailDisplay.textContent = decodeURIComponent(pendingEmail);
    }

    if (resent === '1' && typeof showToast === 'function') {
        showToast('Kode verifikasi baru telah dikirim ke email Anda.', 'success');
        const newUrl = window.location.href.replace(/([&?])resent=1/, '');
        window.history.replaceState({}, document.title, newUrl);
    }

    startOtpCountdown(60, timerId);
    initOtpInputs();

    // Auto-submit when all 6 digits are filled
    const form = document.getElementById(formId);
    if (form) {
        const inputs = Array.from(form.querySelectorAll('.auth-otp-input'));
        if (inputs.length === 6) {
            inputs[5].addEventListener('input', function () {
                if (inputs.every(function (i) { return i.value; })) {
                    syncOtpHidden(formId);
                    form.submit();
                }
            });
        }
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

    if (['login', 'register', 'verify', 'forgot', 'forgot-verify', 'forgot-reset'].includes(auth)) {
        openAuthModal(auth);
    }
    if (auth === 'verify') {
        initVerifyPanel('register');
    } else if (auth === 'forgot-verify') {
        initVerifyPanel('forgot');
    }
    
    if (auth === 'register') {
        if (role === 'provider' || role === 'buyer') {
            selectAuthRole(role);
        }
        if (registerStep) {
            goAuthStep(Number(registerStep));
        }
    }

});

