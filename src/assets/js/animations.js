(function () {
    'use strict';

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var compactMotion = window.matchMedia && window.matchMedia('(max-width: 767.98px)').matches;
    var scrollSelectors = [
        '.card',
        '.service-card',
        '.order-card',
        '.dashboard-card',
        '.stat-card',
        '.review-card',
        '.notification-card',
        '.admin-settings-card',
        '.admin-verify-card',
        '.checkout-card',
        '.payment-summary-card',
        '.cart-item',
        '.alert',
        '.table-responsive',
        'section',
        'main > .container',
        '.admin-dashboard > *'
    ];
    var counterSelectors = [
        '.stat-card h2',
        '.stat-card h3',
        '.dashboard-card h2',
        '.dashboard-card h3',
        '.earnings-card h2',
        '.earnings-card h3',
        '.card h2',
        '.card h3'
    ];

    function ready(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback, { once: true });
            return;
        }
        callback();
    }

    function addPageLoader() {
        return;
    }

    function setLoaded() {
        window.requestAnimationFrame(function () {
            document.body.classList.add('loaded');
        });
    }

    function enhanceNavbar() {
        var nav = document.querySelector('.navbar');
        if (!nav) {
            return;
        }
        var ticking = false;
        var onScroll = function () {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    nav.classList.toggle('navbar-scrolled', window.scrollY > 12);
                    ticking = false;
                });
                ticking = true;
            }
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    function enhanceScrollAnimation() {
        var nodes = [];
        scrollSelectors.forEach(function (selector) {
            document.querySelectorAll(selector).forEach(function (node) {
                if (node.closest('.navbar') || node.closest('.role-sidebar') || node.closest('.site-footer')) {
                    return;
                }
                if (compactMotion && (node.matches('section') || node.matches('.table-responsive'))) {
                    return;
                }
                nodes.push(node);
            });
        });

        nodes = nodes.filter(function (node, index) {
            return nodes.indexOf(node) === index;
        });

        nodes.forEach(function (node, index) {
            if (!node.classList.contains('animate-on-scroll')) {
                node.classList.add('animate-on-scroll');
            }
            if (!node.classList.contains('fade-up') && !node.classList.contains('fade-down') &&
                !node.classList.contains('fade-left') && !node.classList.contains('fade-right') &&
                !node.classList.contains('zoom-in')) {
                node.classList.add(index % 7 === 0 ? 'zoom-in' : 'fade-up');
            }
            node.style.setProperty('--bb-stagger-delay', Math.min(index % 8, 7) * 55 + 'ms');
        });

        var reveal = function (node) {
            node.classList.add('in-view');
            window.setTimeout(function () {
                node.style.willChange = 'auto';
            }, 520);
        };

        if (reduceMotion || !('IntersectionObserver' in window)) {
            nodes.forEach(function (node) {
                reveal(node);
            });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    reveal(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px'
        });

        nodes.forEach(function (node) {
            observer.observe(node);
        });
    }

    function enhanceButtons() {
        document.addEventListener('click', function (event) {
            var target = event.target.closest('.btn, button, .cart-nav-btn, .header-icon-btn, .social-btn');
            if (!target || reduceMotion || target.disabled || target.classList.contains('disabled') ||
                target.closest('.role-actions') || target.closest('.role-sidebar')) {
                return;
            }
            var rect = target.getBoundingClientRect();
            var ripple = document.createElement('span');
            ripple.className = 'bb-ripple';
            ripple.style.left = event.clientX - rect.left + 'px';
            ripple.style.top = event.clientY - rect.top + 'px';
            target.appendChild(ripple);
            setTimeout(function () {
                if (ripple.parentNode) {
                    ripple.parentNode.removeChild(ripple);
                }
            }, 680);
        });
    }

    function enhanceForms() {
        document.querySelectorAll('.form-control, .form-select, textarea, input').forEach(function (field) {
            var group = field.closest('.mb-3, .form-group, .form-floating, .col-md-6, .col-lg-6');
            if (!group) {
                return;
            }
            field.addEventListener('focus', function () {
                group.classList.add('bb-field-active');
            });
            field.addEventListener('blur', function () {
                group.classList.remove('bb-field-active');
            });
        });
    }

    function parseNumber(text) {
        if (/\([^)]+\)/.test(text)) {
            return null;
        }
        var clean = text
            .replace(/rp/ig, '')
            .replace(/\s/g, '')
            .replace(/\./g, '')
            .replace(',', '.')
            .replace(/[^\d.-]/g, '');
        if (!clean || clean === '-') {
            return null;
        }
        var value = parseFloat(clean);
        return Number.isFinite(value) ? value : null;
    }

    function formatLike(original, value) {
        var rounded = Math.round(value);
        if (/rp/i.test(original)) {
            return 'Rp ' + rounded.toLocaleString('id-ID');
        }
        if (/%/.test(original)) {
            return rounded.toLocaleString('id-ID') + '%';
        }
        return rounded.toLocaleString('id-ID');
    }

    function enhanceCounters() {
        var nodes = [];
        counterSelectors.forEach(function (selector) {
            document.querySelectorAll(selector).forEach(function (node) {
                if (nodes.indexOf(node) === -1 && parseNumber(node.textContent) !== null) {
                    nodes.push(node);
                }
            });
        });

        if (!nodes.length) {
            return;
        }

        var run = function (node) {
            if (node.dataset.counterDone === 'true') {
                return;
            }
            node.dataset.counterDone = 'true';
            node.classList.add('counter-animate');
            var original = node.textContent.trim();
            var end = parseNumber(original);
            if (end === null || reduceMotion) {
                return;
            }
            var startTime = null;
            var duration = 950;
            function tick(timestamp) {
                if (!startTime) {
                    startTime = timestamp;
                }
                var progress = Math.min((timestamp - startTime) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                node.textContent = formatLike(original, end * eased);
                if (progress < 1) {
                    window.requestAnimationFrame(tick);
                } else {
                    node.textContent = original;
                }
            }
            window.requestAnimationFrame(tick);
        };

        if (!('IntersectionObserver' in window)) {
            nodes.forEach(run);
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    run(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.35 });

        nodes.forEach(function (node) {
            observer.observe(node);
        });
    }

    function enhanceStagger() {
        document.querySelectorAll('.row, tbody, .cart-preview-list, .role-sidebar-nav').forEach(function (group) {
            var children = Array.prototype.slice.call(group.children).filter(function (child) {
                return child.nodeType === 1;
            });
            if (children.length < 2 || children.length > 24) {
                return;
            }
            children.forEach(function (child, index) {
                child.style.setProperty('--bb-stagger-delay', Math.min(index, 10) * 45 + 'ms');
            });
        });
    }

    ready(function () {
        addPageLoader();
        setLoaded();
        enhanceNavbar();
        enhanceScrollAnimation();
        enhanceButtons();
        enhanceForms();
        enhanceCounters();
        enhanceStagger();
    });
})();
