(function () {
    'use strict';
    var grid = document.getElementById('allServicesGrid');
    if (!grid) return;

    var search = document.getElementById('servicesSearch');
    var location = document.getElementById('servicesLocation');
    var sort = document.getElementById('servicesSort');
    var resultCount = document.getElementById('servicesResultCount');
    var emptyState = document.getElementById('servicesEmptyState');
    var activeCategory = document.querySelector('.services-category-pill.active')?.dataset.category || 'semua';
    var items = Array.prototype.slice.call(grid.querySelectorAll('.all-service-item'));

    function updateServices() {
        var term = (search.value || '').trim().toLowerCase();
        var selectedLocation = (location.value || '').toLowerCase();
        var visibleItems = items.filter(function (item) {
            var show = (activeCategory === 'semua' || item.dataset.category === activeCategory) &&
                (!selectedLocation || item.dataset.location === selectedLocation) &&
                (!term || item.dataset.search.indexOf(term) !== -1);
            item.classList.toggle('d-none', !show);
            return show;
        });

        visibleItems.sort(function (a, b) {
            if (sort.value === 'price-asc') return Number(a.dataset.price) - Number(b.dataset.price);
            if (sort.value === 'price-desc') return Number(b.dataset.price) - Number(a.dataset.price);
            if (sort.value === 'rating-desc') return Number(b.dataset.rating) - Number(a.dataset.rating);
            return new Date(b.dataset.created) - new Date(a.dataset.created);
        }).forEach(function (item) { grid.appendChild(item); });

        resultCount.innerHTML = 'Menampilkan <strong>' + visibleItems.length + '</strong> jasa';
        emptyState.classList.toggle('d-none', visibleItems.length !== 0);
    }

    document.querySelectorAll('.services-category-pill').forEach(function (pill) {
        pill.addEventListener('click', function () {
            activeCategory = pill.dataset.category;
            document.querySelectorAll('.services-category-pill').forEach(function (item) { item.classList.toggle('active', item === pill); });
            updateServices();
        });
    });
    search.addEventListener('input', updateServices);
    location.addEventListener('change', updateServices);
    sort.addEventListener('change', updateServices);
    updateServices();
})();
