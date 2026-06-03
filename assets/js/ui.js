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

    document.addEventListener('DOMContentLoaded', function () {
        initScrollLinks();
        initPlaceholderLinks();

        if (window.location.hash) {
            setTimeout(function () {
                smoothScrollTo(window.location.hash);
            }, 200);
        }
    });
})();
