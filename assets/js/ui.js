/**
 * RubyBooks UI Utilities
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
     * INITIALIZATION
     */
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            initScrollLinks();
            initPlaceholderLinks();
            initProfileDropdown();
            initProfilePhotoPreview();
            handleProfileStatus();
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