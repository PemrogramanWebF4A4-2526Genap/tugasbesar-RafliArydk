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
     * CENTER ALERT POPUP
     * Centered modal popup for cart success and major notifications
     */
    window.showCenterAlert = function (message, type = 'success', title = null) {
        let existing = document.getElementById('bb-center-alert');
        if (existing) existing.remove();

        const overlay = document.createElement('div');
        overlay.id = 'bb-center-alert';
        overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.45);z-index:99999;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity 0.2s ease;';

        const box = document.createElement('div');
        box.style.cssText = 'background:var(--color-background-primary,#fff);border-radius:16px;padding:28px 24px 24px;text-align:center;box-shadow:0 16px 40px rgba(0,0,0,0.18);width:90%;max-width:320px;transform:scale(0.88);transition:transform 0.22s cubic-bezier(0.34,1.56,0.64,1);';

        let iconHtml = '';
        if (type === 'success') {
            iconHtml = '<div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:50%;background:#E8F5E9;color:#4CAF50;font-size:26px;margin-bottom:14px;"><i class="bi bi-check-circle-fill"></i></div>';
        } else if (type === 'warning' || type === 'error') {
            iconHtml = '<div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:50%;background:#FFF4E5;color:#EF9F27;font-size:26px;margin-bottom:14px;"><i class="bi bi-exclamation-triangle-fill"></i></div>';
        } else {
            iconHtml = '<div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:50%;background:#F2F2F2;color:#555;font-size:26px;margin-bottom:14px;"><i class="bi bi-info-circle-fill"></i></div>';
        }

        const heading = title || (type === 'success' ? 'Berhasil!' : 'Pemberitahuan');

        box.innerHTML = `
            ${iconHtml}
            <h3 style="margin:0 0 8px;font-size:18px;font-weight:700;color:var(--color-text-primary,#111);">${heading}</h3>
            <p style="margin:0 0 20px;font-size:14px;color:var(--color-text-secondary,#555);line-height:1.5;">${message}</p>
            <button class="bb-alert-ok" style="width:100%;padding:10px;background:var(--color-primary,#B5451B);color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;transition:opacity 0.15s;">Mengerti</button>
        `;

        overlay.appendChild(box);
        document.body.appendChild(overlay);

        requestAnimationFrame(() => {
            overlay.style.opacity = '1';
            box.style.transform = 'scale(1)';
        });

        const closeAlert = () => {
            overlay.style.opacity = '0';
            box.style.transform = 'scale(0.88)';
            setTimeout(() => { if (overlay.parentNode) overlay.remove(); }, 220);
        };

        box.querySelector('.bb-alert-ok').addEventListener('click', closeAlert);
        overlay.addEventListener('click', (e) => { if (e.target === overlay) closeAlert(); });
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
            // Skip auth modal forms (handled by auth-modal.js with inline errors)
            // Skip cart forms (handled by initCartAjax with AJAX)
            if (form.closest('#authModal') || form.classList.contains('js-cart-add')) return;

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
     * AJAX CART ADD
     * Uses event delegation on document - more reliable than per-form listeners
     */
    function initCartAjax() {
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form.classList.contains('js-cart-add')) return;

            e.preventDefault();
            e.stopImmediatePropagation();

            const btn = form.querySelector('button[type="submit"]');
            const originalHTML = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menambahkan...';
            }

            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(function (res) {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(function (data) {
                if (data.success) {
                    showCenterAlert('Jasa berhasil ditambahkan ke keranjang!', 'success', 'Ditambahkan!');
                    // Update all cart badge counts
                    document.querySelectorAll('.cart-nav-badge').forEach(badge => {
                        if (data.cart_count !== undefined) {
                            badge.textContent = data.cart_count;
                            badge.style.display = data.cart_count > 0 ? '' : 'none';
                        }
                    });
                    
                    // If badge doesn't exist but we have count > 0, we might need to create it inside .cart-nav-btn
                    if (data.cart_count > 0) {
                        document.querySelectorAll('.cart-nav-btn').forEach(btn => {
                            if (!btn.querySelector('.cart-nav-badge')) {
                                const newBadge = document.createElement('span');
                                newBadge.className = 'cart-nav-badge';
                                newBadge.textContent = data.cart_count;
                                btn.appendChild(newBadge);
                            }
                        });
                    }
                    
                    // Update the cart preview dropdown list if it exists
                    if (data.preview_html !== undefined) {
                        const previewLists = document.querySelectorAll('.cart-preview-list');
                        previewLists.forEach(list => {
                            list.innerHTML = data.preview_html;
                        });
                        const previewHeads = document.querySelectorAll('.cart-preview-head strong');
                        previewHeads.forEach(head => {
                            head.textContent = 'Keranjang (' + data.cart_count + ')';
                        });
                    }
                } else {
                    showCenterAlert('Gagal menambahkan ke keranjang.', 'warning');
                }
            })
            .catch(function () {
                showCenterAlert('Terjadi kesalahan jaringan. Silakan coba lagi.', 'warning');
            })
            .finally(function () {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                }
            });
        }, true); // useCapture = true so it fires before other listeners
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
            initCartAjax();
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
