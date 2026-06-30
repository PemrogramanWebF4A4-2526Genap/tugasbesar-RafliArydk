/**
 * Interaksi halaman beranda
 */
(function () {
    'use strict';

    const CATEGORY_MAP = {
        'bersih-bersih': 'Bersih-bersih',
        'perbaikan': 'Perbaikan',
        'les-privat': 'Les Privat',
        laundry: 'Laundry',
        taman: 'Taman',
        penitipan: 'Penitipan',
        memasak: 'Memasak',
        lainnya: 'Lainnya',
    };

    function normalizeCategory(slug) {
        if (!slug || slug === 'semua') return 'semua';
        return slug;
    }

    function getActiveFilter() {
        const active = document.querySelector('.filter-pills .filter-pill.active');
        return active ? normalizeCategory(active.getAttribute('data-filter')) : 'semua';
    }

    function setActiveFilter(slug) {
        slug = normalizeCategory(slug);
        document.querySelectorAll('.filter-pills .filter-pill').forEach(function (pill) {
            const match = normalizeCategory(pill.getAttribute('data-filter')) === slug;
            pill.classList.toggle('active', match);
            pill.setAttribute('aria-pressed', match ? 'true' : 'false');
        });
        filterServices();
    }

    function filterServices() {
        const filter = getActiveFilter();
        const query = (document.getElementById('heroSearchInput')?.value || '').trim().toLowerCase();
        const cards = document.querySelectorAll('#servicesGrid [data-service-card]');
        let visible = 0;

        cards.forEach(function (wrap) {
            const cat = wrap.getAttribute('data-category') || '';
            const title = (wrap.getAttribute('data-title') || '').toLowerCase();
            const matchCat = filter === 'semua' || cat === filter;
            const matchQuery = !query || title.includes(query) || cat.includes(query.replace(/\s+/g, '-'));
            const show = matchCat && matchQuery;
            wrap.classList.toggle('is-hidden', !show);
            if (show) visible++;
        });

        const empty = document.getElementById('servicesEmpty');
        if (empty) empty.classList.toggle('d-none', visible > 0);

        return visible;
    }

    function handleHeroSearch(e) {
        if (e) e.preventDefault();
        const query = document.getElementById('heroSearchInput')?.value.trim();
        const city = document.getElementById('heroSearchCity')?.value || 'Semua Kota';

        smoothScrollTo('#layanan-jasa');
        const count = filterServices();

        if (query) {
            showToast('Menampilkan hasil untuk "' + query + '" di ' + city + ' (' + count + ' jasa)', count ? 'success' : 'warning');
        } else {
            showToast('Menampilkan semua jasa di ' + city, 'info');
        }
    }

    function handleCategoryClick(card) {
        const slug = card.getAttribute('data-category');
        if (!slug) return;
        setActiveFilter(slug);
        smoothScrollTo('#layanan-jasa');
        const label = CATEGORY_MAP[slug] || slug;
        showToast('Filter kategori: ' + label, 'info');
    }

    function handleServiceCard(card) {
        const title = card.getAttribute('data-title') || 'Jasa';
        const isLoggedIn = document.body.classList.contains('user-logged-in');

        if (!isLoggedIn) {
            showToast('Masuk untuk memesan "' + title + '"', 'info');
            if (typeof openAuthModal === 'function') openAuthModal('login');
            return;
        }
        showToast('"' + title + '" — fitur pemesanan segera hadir!', 'success');
    }

    function initFilterPills() {
        document.querySelectorAll('.filter-pills .filter-pill').forEach(function (pill) {
            pill.addEventListener('click', function () {
                setActiveFilter(pill.getAttribute('data-filter'));
            });
        });
    }

    function initNavCategoryLinks() {
        document.querySelectorAll('[data-nav-category]').forEach(function (link) {
            link.addEventListener('click', function () {
                const slug = link.getAttribute('data-nav-category');
                setTimeout(function () {
                    setActiveFilter(slug);
                    const label = CATEGORY_MAP[slug] || slug;
                    showToast('Filter kategori: ' + label, 'info');
                }, 120);
            });
        });
    }

    function initCategoryCards() {
        document.querySelectorAll('.category-card[data-category]').forEach(function (card) {
            card.setAttribute('role', 'button');
            card.setAttribute('tabindex', '0');
            card.addEventListener('click', function () {
                handleCategoryClick(card);
            });
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    handleCategoryClick(card);
                }
            });
        });
    }

    function initServiceCards() {
        document.querySelectorAll('[data-service-card]').forEach(function (wrap) {
            const inner = wrap.querySelector('.service-card');
            if (!inner) return;
            inner.querySelectorAll('a, button, form, input').forEach(function (control) {
                control.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            });
            inner.setAttribute('role', 'button');
            inner.setAttribute('tabindex', '0');
            const activate = function () {
                handleServiceCard(wrap);
            };
            inner.addEventListener('click', activate);
            inner.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    activate();
                }
            });
        });
    }

    function initHeroSearch() {
        const form = document.getElementById('heroSearchForm');
        if (form) {
            form.addEventListener('submit', handleHeroSearch);
        }
        const input = document.getElementById('heroSearchInput');
        if (input) {
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') handleHeroSearch(e);
            });
        }
    }

function initViewAll() {
    const link = document.getElementById('viewAllServices');
    if (!link) return;
    link.addEventListener('click', function (e) {
        e.preventDefault();
        setActiveFilter('semua');
        const searchInput =
            document.getElementById('heroSearchInput');
        if (searchInput) {
            searchInput.value = '';
        }
        smoothScrollTo('#layanan-jasa');
        showToast(
            'Semua jasa populer ditampilkan',
            'info'
        );
    });
}

    document.addEventListener('DOMContentLoaded', function () {
        if (!document.getElementById('layanan-jasa')) return;
        setActiveFilter('semua');
        initFilterPills();
        initNavCategoryLinks();
        initCategoryCards();
        initServiceCards();
        initHeroSearch();
        initViewAll();
    });
})();
