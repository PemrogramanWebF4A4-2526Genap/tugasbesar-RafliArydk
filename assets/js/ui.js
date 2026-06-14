/**
 * UI umum: toast, smooth scroll, link anchor
 */
(function () {
    'use strict';

    let toastTimer = null;

    window.showToast = function (message, type) {
        type = type || 'info';
        let container = document.getElementById('bbToastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'bbToastContainer';
            container.className = 'bb-toast-container';
            container.setAttribute('aria-live', 'polite');
            document.body.appendChild(container);
        }

        const icons = {
            success: 'bi-check-circle-fill',
            error: 'bi-exclamation-circle-fill',
            info: 'bi-info-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
        };

        const toast = document.createElement('div');
        toast.className = 'bb-toast bb-toast--' + type + ' bb-toast--show';
        toast.innerHTML =
            '<i class="bi ' + (icons[type] || icons.info) + '" aria-hidden="true"></i>' +
            '<span>' + message + '</span>' +
            '<button type="button" class="bb-toast-close" aria-label="Tutup"><i class="bi bi-x"></i></button>';

        const close = function () {
            toast.classList.remove('bb-toast--show');
            setTimeout(function () {
                toast.remove();
            }, 300);
        };

        toast.querySelector('.bb-toast-close').addEventListener('click', close);
        container.appendChild(toast);

        clearTimeout(toastTimer);
        toastTimer = setTimeout(close, 4000);
    };

    window.smoothScrollTo = function (target, offset) {
        offset = offset || 80;
        let el = null;
        if (typeof target === 'string') {
            el = document.querySelector(target);
        } else if (target instanceof Element) {
            el = target;
        }
        if (!el) return false;
        const top = el.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
        return true;
    };

    function initScrollLinks() {
        document.querySelectorAll('[data-scroll]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                const sel = link.getAttribute('data-scroll');
                const onHome = document.getElementById('layanan-jasa') || document.querySelector('.hero');
                const isHashOnly = sel && sel.charAt(0) === '#';

                if (isHashOnly && onHome) {
                    const el = document.querySelector(sel);
                    if (el) {
                        e.preventDefault();
                        smoothScrollTo(el);
                        const collapse = document.getElementById('navbarMain');
                        if (collapse && collapse.classList.contains('show')) {
                            const toggler = document.querySelector('[data-bs-target="#navbarMain"]');
                            if (toggler) toggler.click();
                        }
                    }
                }
            });
        });
    }

    function initPlaceholderLinks() {
        document.querySelectorAll('[data-toast]').forEach(function (el) {
            el.addEventListener('click', function (e) {
                const msg = el.getAttribute('data-toast');
                if (!msg) return;
                if (el.getAttribute('href') === '#' || el.getAttribute('href') === '') {
                    e.preventDefault();
                }
                showToast(msg, el.getAttribute('data-toast-type') || 'info');
            });
        });
    }

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

    document.addEventListener('DOMContentLoaded', function () {
        initScrollLinks();
        initPlaceholderLinks();
        initProfileDropdown();

        const fileInput = document.getElementById('profilePhotoInput');
        const preview = document.getElementById('profilePhotoPreview');
        if (fileInput && preview) {
            fileInput.addEventListener('change', function () {
                const file = fileInput.files[0];
                if (!file) {
                    preview.style.backgroundImage = '';
                    preview.textContent = preview.dataset.initial || '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.style.backgroundImage = 'url(' + e.target.result + ')';
                    preview.textContent = '';
                };
                reader.readAsDataURL(file);
            });
        }

        const params = new URLSearchParams(window.location.search);
        if (params.get('profile_status') === 'success') {
            showToast('Profil berhasil disimpan.', 'success');
            params.delete('profile_status');
            params.delete('profile_error');
            const query = params.toString();
            history.replaceState(null, '', window.location.pathname + (query ? '?' + query : ''));
        }
        if (params.get('profile_error')) {
            showToast(decodeURIComponent(params.get('profile_error')), 'error');
            params.delete('profile_error');
            const query = params.toString();
            history.replaceState(null, '', window.location.pathname + (query ? '?' + query : ''));
        }

        function maybeOpenProfileSettingsModal(hash) {
            if (hash === '#profile-settings') {
                const profileModal = document.querySelector('#profileSettingsModal');
                if (profileModal) {
                    const modal = new bootstrap.Modal(profileModal);
                    modal.show();
                    return true;
                }
            }
            return false;
        }

        if (window.location.hash) {
            const hash = window.location.hash;
            setTimeout(function () {
                if (!maybeOpenProfileSettingsModal(hash)) {
                    smoothScrollTo(hash);
                }
            }, 200);
        }

        window.addEventListener('hashchange', function () {
            maybeOpenProfileSettingsModal(window.location.hash);
        });
    });
})();
