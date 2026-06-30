/**
 * - Toast Notification
 * - Smooth Scroll
 * - Profile Dropdown
 * - Profile Photo Preview
 * - URL Hash Handling
 */

(function () {
    'use strict';
    /**
     * TOAST NOTIFICATION
     */
    function getToastContainer() {
        let container = document.getElementById('bbToastContainer');

        if (!container) {
            container = document.createElement('div');
            container.id = 'bbToastContainer';
            container.className = 'bb-toast-container';
            container.setAttribute('aria-live', 'polite');
            document.body.appendChild(container);
        }
        return container;
    }

    window.showToast = function (message, type = 'info') {
        const container = getToastContainer();
        const icons = {
            success: 'bi-check-circle-fill',
            error: 'bi-exclamation-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill'
        };

        const toast = document.createElement('div');
        toast.className = `bb-toast bb-toast--${type} bb-toast--show`;

        const icon = document.createElement('i');
        icon.className = `bi ${icons[type] || icons.info}`;
        icon.setAttribute('aria-hidden', 'true');

        const text = document.createElement('span');
        text.textContent = message;

        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'bb-toast-close';
        closeBtn.setAttribute('aria-label', 'Tutup');
        closeBtn.innerHTML = '<i class="bi bi-x"></i>';

        const closeToast = () => {
            toast.classList.remove('bb-toast--show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        };

        closeBtn.addEventListener('click', closeToast);

        toast.append(icon, text, closeBtn);
        container.appendChild(toast);

        setTimeout(closeToast, 4000);
    };

    /**
     * SMOOTH SCROLL
     */
    window.smoothScrollTo = function (target, offset = 80) {
        let element = null;

        if (typeof target === 'string') {
            element = document.querySelector(target);
        } else if (target instanceof Element) {
            element = target;
        }

        if (!element) return false;
        const top =
            element.getBoundingClientRect().top +
            window.pageYOffset -
            offset;
        window.scrollTo({
            top,
            behavior: 'smooth'
        });
        return true;
    };

    function initScrollLinks() {
        document.querySelectorAll('[data-scroll]').forEach(link => {
            link.addEventListener('click', function (e) {
                const selector = this.getAttribute('data-scroll');
                if (!selector || selector.charAt(0) !== '#') return;
                const target = document.querySelector(selector);
                if (!target) return;
                e.preventDefault();
                smoothScrollTo(target);
                const navbar = document.getElementById('navbarMain');
                if (navbar && navbar.classList.contains('show')) {
                    document
                        .querySelector('[data-bs-target="#navbarMain"]')
                        ?.click();
                }
            });
        });
    }

    /**
     * PLACEHOLDER LINKS
     */
    function initPlaceholderLinks() {
        document.querySelectorAll('[data-toast]').forEach(element => {
            element.addEventListener('click', function (e) {
                const message = this.getAttribute('data-toast');
                if (!message) return;
                const href = this.getAttribute('href');
                if (href === '#' || href === '') {
                    e.preventDefault();
                }
                showToast(
                    message,
                    this.getAttribute('data-toast-type') || 'info'
                );
            });
        });
    }

    /**
     * PROFILE DROPDOWN
     */
    function initProfileDropdown() {
        var wrappers = document.querySelectorAll('.profile-dropdown-wrapper');
        if (!wrappers.length) return;

        wrappers.forEach(function (wrapper) {
            var toggle = wrapper.querySelector('.role-profile');
            var dropdown = wrapper.querySelector('.profile-dropdown');
            if (!toggle || !dropdown) return;

            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                /* close every other dropdown first */
                wrappers.forEach(function (w) {
                    if (w !== wrapper) {
                        var d = w.querySelector('.profile-dropdown');
                        if (d) d.classList.remove('show');
                    }
                });
                dropdown.classList.toggle('show');
            });

            dropdown.querySelectorAll('a').forEach(function (link) {
                if (!link.hasAttribute('data-bs-toggle')) {
                    link.addEventListener('click', function () {
                        dropdown.classList.remove('show');
                    });
                }
            });
        });

        document.addEventListener('click', function (e) {
            wrappers.forEach(function (wrapper) {
                var toggle = wrapper.querySelector('.role-profile');
                var dropdown = wrapper.querySelector('.profile-dropdown');
                if (toggle && dropdown && !toggle.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        });
    }

    /**
     * PROFILE PHOTO PREVIEW
     */
    function initProfilePhotoPreview() {
        const fileInput = document.getElementById('profilePhotoInput');
        const preview = document.getElementById('profilePhotoPreview');
        if (!fileInput || !preview) return;
        fileInput.addEventListener('change', function () {
            const file = this.files?.[0];
            if (!file) {
                preview.style.backgroundImage = '';
                preview.textContent =
                    preview.dataset.initial || '';
                return;
            }
            if (!file.type.startsWith('image/')) {
                showToast(
                    'File harus berupa gambar.',
                    'warning'
                );
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.style.backgroundImage =
                    `url(${e.target.result})`;
                preview.textContent = '';
            };
            reader.readAsDataURL(file);
        });
    }

    /**
     * PROFILE STATUS TOAST
     */
    function handleProfileStatus() {
        const params = new URLSearchParams(
            window.location.search
        );

        if (params.get('profile_status') === 'success') {
            showToast(
                'Profil berhasil disimpan.',
                'success'
            );
            params.delete('profile_status');
            params.delete('profile_error');
            const query = params.toString();
            history.replaceState(
                null,
                '',
                window.location.pathname +
                    (query ? '?' + query : '')
            );
        }
        const error = params.get('profile_error');
        if (error) {
            showToast(error, 'error');
            params.delete('profile_error');
            const query = params.toString();
            history.replaceState(
                null,
                '',
                window.location.pathname +
                    (query ? '?' + query : '')
            );
        }
    }

    /**
     * UNIVERSAL ACTION MSG TOAST
     * Reads ?msg= and ?error= from URL after any redirect and shows a toast.
     */
    function handleActionMsg() {
        const params = new URLSearchParams(window.location.search);
        const msg = params.get('msg');
        const error = params.get('error');

        // Map of msg codes -> human-readable success messages
        const successMessages = {
            // Cart
            'added':               '✅ Jasa berhasil ditambahkan ke keranjang!',
            'updated':             '🔄 Keranjang berhasil diperbarui.',
            'removed':             '🗑️ Jasa dihapus dari keranjang.',
            'cleared':             '🗑️ Keranjang berhasil dikosongkan.',
            // Checkout & Order
            'checkout_success':   '🎉 Pesanan berhasil dibuat! Silakan lakukan pembayaran.',
            'status_updated':     '✅ Status pesanan berhasil diperbarui.',
            'order_updated':      '✅ Status pesanan berhasil diperbarui.',
            // Payment
            'payment_uploaded':   '📤 Bukti pembayaran berhasil dikirim. Menunggu konfirmasi admin.',
            'payment_processed':  '✅ Pembayaran berhasil diproses.',
            // Review
            'review_submitted':   '⭐ Review berhasil dikirim. Terima kasih!',
            // Seller / Provider Services
            'created':            '✅ Jasa baru berhasil ditambahkan!',
            'updated':            '✅ Jasa berhasil diperbarui.',
            'deleted':            '🗑️ Jasa berhasil dihapus.',
            'status_changed':     '✅ Status jasa berhasil diubah.',
            // Admin: Users & Providers
            'provider_verified':  '✅ Penyedia jasa berhasil diverifikasi.',
            'provider_rejected':  '✅ Pendaftaran penyedia berhasil ditolak.',
            'user_deleted':       '🗑️ Akun pengguna berhasil dihapus.',
            'user_status_updated':'✅ Status pengguna berhasil diperbarui.',
            // Admin: Categories
            'category_created':   '✅ Kategori baru berhasil ditambahkan!',
            'category_updated':   '✅ Kategori berhasil diperbarui.',
            'category_deleted':   '🗑️ Kategori berhasil dihapus.',
            // Admin: Settings
            'settings_saved':     '✅ Pengaturan sistem berhasil disimpan.',
            // Notification
            'all_read':           '✅ Semua notifikasi ditandai sudah dibaca.',
        };

        // Map of error codes -> human-readable error messages
        const errorMessages = {
            'empty':              '🛒 Keranjang masih kosong.',
            'checkout_failed':    '❌ Checkout gagal. Coba lagi.',
            'invalid_review':     '❌ Review tidak valid atau pesanan belum selesai.',
            'already_reviewed':   'ℹ️ Pesanan ini sudah pernah diberi review.',
            'invalid_order':      '❌ Pesanan tidak valid.',
            'upload_failed':      '❌ Gagal mengunggah bukti pembayaran. Pastikan format JPG/PNG.',
            'category_failed':    '❌ Gagal menyimpan kategori.',
            'category_in_use':    '❌ Kategori tidak bisa dihapus karena masih digunakan.',
            'verify_failed':      '❌ Gagal memverifikasi penyedia.',
            'reject_failed':      '❌ Gagal menolak penyedia.',
            'delete_failed':      '❌ Gagal menghapus akun.',
            'invalid_user':       '❌ Pengguna tidak valid.',
            'settings_failed':    '❌ Gagal menyimpan pengaturan.',
            'order_failed':       '❌ Gagal memperbarui status pesanan.',
            'missing_fields':     '⚠️ Lengkapi semua field yang wajib diisi.',
            'provider_not_verified': 'ℹ️ Akun Anda belum diverifikasi oleh admin.',
        };

        let changed = false;

        if (msg && successMessages[msg]) {
            showToast(successMessages[msg], 'success');
            params.delete('msg');
            changed = true;
        }

        if (error && errorMessages[error]) {
            showToast(errorMessages[error], 'error');
            params.delete('error');
            changed = true;
        }

        if (changed) {
            const query = params.toString();
            history.replaceState(
                null, '',
                window.location.pathname + (query ? '?' + query : '')
            );
        }
    }

    /**
     * HASH HANDLER
     */
    function openProfileSettingsModal() {
        const modalElement = document.getElementById(
            'profileSettingsModal'
        );
        if (!modalElement) return false;
        if (
            typeof bootstrap === 'undefined' ||
            !bootstrap.Modal
        ) {
            console.warn(
                'Bootstrap Modal belum tersedia.'
            );
            return false;
        }

        const modal =
            bootstrap.Modal.getOrCreateInstance(
                modalElement
            );
        modal.show();
        return true;
    }

    function handleHash(hash) {
        if (!hash) return;
        if (hash === '#profile-settings') {
            openProfileSettingsModal();
            return;
        }

        smoothScrollTo(hash);
    }

    /**
     * GLOBAL FORM VALIDATION
     */
    function getLabelText(element) {
        if (element.id) {
            const label = document.querySelector(`label[for="${element.id}"]`);
            if (label) return label.textContent.replace(/[*:]/g, '').trim();
        }
        if (element.parentElement) {
            const label = element.parentElement.querySelector('label');
            if (label) return label.textContent.replace(/[*:]/g, '').trim();
        }
        const parentLabel = element.closest('label');
        if (parentLabel) {
            return parentLabel.textContent.replace(/[*:]/g, '').trim();
        }
        if (element.placeholder) {
            return element.placeholder.trim();
        }
        if (element.name) {
            return element.name.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        }
        return 'Field';
    }

    function initGlobalFormValidation() {
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (form.hasAttribute('data-no-validate')) return;

            const inputs = Array.from(form.querySelectorAll('input, select, textarea'));
            
            for (const input of inputs) {
                if (input.disabled || input.type === 'submit' || input.type === 'button' || input.type === 'hidden') {
                    continue;
                }

                // If input is not visible (unless it is a file upload input which can be styled/hidden in custom wrappers)
                if (input.offsetWidth === 0 && input.offsetHeight === 0 && input.type !== 'file') {
                    continue;
                }

                let isValid = true;
                let errorMessage = '';
                const label = getLabelText(input);

                if (input.required) {
                    if (input.type === 'checkbox') {
                        if (!input.checked) {
                            isValid = false;
                            errorMessage = `${label} harus disetujui.`;
                        }
                    } else if (input.type === 'radio') {
                        const name = input.name;
                        if (name) {
                            const checked = form.querySelector(`input[name="${name}"]:checked`);
                            if (!checked) {
                                isValid = false;
                                errorMessage = `Pilih salah satu ${label}.`;
                            }
                        }
                    } else {
                        if (input.value.trim() === '') {
                            isValid = false;
                            errorMessage = `${label} wajib diisi.`;
                        }
                    }
                }

                if (isValid && input.value.trim() !== '') {
                    if (input.type === 'email') {
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim())) {
                            isValid = false;
                            errorMessage = `Format ${label} tidak valid.`;
                        } else {
                            const domain = input.value.trim().split('@').pop().toLowerCase();
                            if (domain.endsWith('.co') && !domain.endsWith('.com')) {
                                if (domain.endsWith('.co') && domain.split('.').length <= 2) {
                                    isValid = false;
                                    errorMessage = `Format ${label} tidak valid.`;
                                }
                            }
                        }
                    } else if (input.minLength && input.value.length < input.minLength) {
                        isValid = false;
                        errorMessage = `${label} minimal ${input.minLength} karakter.`;
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (typeof window.showToast === 'function') {
                        window.showToast(errorMessage, 'warning');
                    }
                    input.focus();
                    break;
                }
            }
        });
    }

    /**
     * INITIALIZATION
     */
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            initGlobalFormValidation();
            initScrollLinks();
            initPlaceholderLinks();
            initProfileDropdown();
            initProfilePhotoPreview();
            handleProfileStatus();
            handleActionMsg();
            if (window.location.hash) {
                setTimeout(() => {
                    handleHash(window.location.hash);
                }, 200);
            }
            window.addEventListener(
                'hashchange',
                () => handleHash(window.location.hash)
            );
        }
    );
})();
