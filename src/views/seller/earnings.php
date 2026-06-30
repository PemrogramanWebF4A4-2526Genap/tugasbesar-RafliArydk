<?php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';
require_once __DIR__ . '/../../models/ServiceModel.php';

$orderModel = new OrderModel($pdo);
$reviewModel = new ReviewModel($pdo);
$serviceModel = new ServiceModel($pdo);

$providerId = $_SESSION['user']['id'];
$orders = $orderModel->getByProvider($providerId);
$ratingData = $reviewModel->getAverageRatingByProvider($providerId);

// Calculate statistics
$totalOrders = count($orders);
$completedOrders = count(array_filter($orders, fn($o) => $o['status'] === 'completed'));
$pendingOrders = count(array_filter($orders, fn($o) => !in_array($o['status'], ['completed', 'cancelled'])));
$cancelledOrders = count(array_filter($orders, fn($o) => $o['status'] === 'cancelled'));
$totalEarnings = array_sum(array_map(fn($o) => $o['status'] === 'completed' ? (float)$o['total_price'] : 0, $orders));
$avgRating = $ratingData['avg_rating'] ? number_format($ratingData['avg_rating'], 1) : '0.0';
$totalReviews = $ratingData['total_reviews'] ?? 0;

// Orders grouped by month for chart
$monthlyData = [];
foreach ($orders as $o) {
    $month = date('Y-m', strtotime($o['service_date']));
    if (!isset($monthlyData[$month])) {
        $monthlyData[$month] = ['orders' => 0, 'revenue' => 0];
    }
    $monthlyData[$month]['orders']++;
    if ($o['status'] === 'completed') {
        $monthlyData[$month]['revenue'] += (float)$o['total_price'];
    }
}
ksort($monthlyData);

// Ensure at least 6 months of data for a nice chart
$endMonth = date('Y-m');
$startMonth = date('Y-m', strtotime('-5 months'));
$allMonths = [];
$current = $startMonth;
while ($current <= $endMonth) {
    $allMonths[$current] = $monthlyData[$current] ?? ['orders' => 0, 'revenue' => 0];
    $current = date('Y-m', strtotime($current . '-01 +1 month'));
}

$chartLabels = array_map(fn($m) => date('M Y', strtotime($m . '-01')), array_keys($allMonths));
$chartOrders = array_column(array_values($allMonths), 'orders');
$chartRevenue = array_column(array_values($allMonths), 'revenue');

// Status distribution for pie chart
$statusCounts = [
    'Selesai' => $completedOrders,
    'Dalam Proses' => $pendingOrders,
    'Dibatalkan' => $cancelledOrders,
];
?>
<div class="container">
    <h2 class="fw-bold mb-4">Statistik & Pendapatan</h2>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                <i class="bi bi-receipt fs-2" style="color: var(--orange-primary, #e67e22);"></i>
                <h3 class="mt-2 mb-0"><?= $totalOrders ?></h3>
                <p class="text-muted mb-0">Total Pesanan</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                <i class="bi bi-check-circle fs-2 text-success"></i>
                <h3 class="mt-2 mb-0"><?= $completedOrders ?></h3>
                <p class="text-muted mb-0">Pesanan Selesai</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                <i class="bi bi-cash-stack fs-2 text-primary"></i>
                <h3 class="mt-2 mb-0">Rp <?= number_format($totalEarnings, 0, ',', '.') ?></h3>
                <p class="text-muted mb-0">Total Pendapatan</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                <i class="bi bi-star-fill fs-2" style="color: #f1c40f;"></i>
                <h3 class="mt-2 mb-0"><?= $avgRating ?> <small class="fs-6 text-muted">(<?= $totalReviews ?>)</small></h3>
                <p class="text-muted mb-0">Rating Rata-rata</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Line Chart: Orders & Revenue per Month -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-graph-up me-2"></i>Tren Pesanan & Pendapatan</h5>
                <div class="chart-shell chart-shell-lg">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Pie Chart: Status Distribution -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2"></i>Distribusi Status</h5>
                <div class="chart-shell">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bar Chart: Monthly Revenue -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2"></i>Pendapatan per Bulan</h5>
                <div class="chart-shell chart-shell-sm">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const labels = <?= json_encode($chartLabels) ?>;
    const ordersData = <?= json_encode($chartOrders) ?>;
    const revenueData = <?= json_encode($chartRevenue) ?>;
    const maxOrders = Math.max(...ordersData, 0);
    const maxRevenue = Math.max(...revenueData, 0);
    const rupiahTick = value => {
        if (value >= 1000000) return 'Rp ' + (value / 1000000).toLocaleString('id-ID') + ' jt';
        if (value >= 1000) return 'Rp ' + (value / 1000).toLocaleString('id-ID') + ' rb';
        return 'Rp ' + value.toLocaleString('id-ID');
    };

    // 1. Trend Line Chart
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Jumlah Pesanan',
                    data: ordersData,
                    borderColor: '#e67e22',
                    backgroundColor: 'rgba(230,126,34,0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'Pendapatan (Rp)',
                    data: revenueData,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52,152,219,0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { type: 'linear', display: true, position: 'left', beginAtZero: true, suggestedMax: maxOrders > 0 ? undefined : 5, title: { display: true, text: 'Pesanan' },
                    ticks: { precision: 0, stepSize: 1 }
                },
                y1: { type: 'linear', display: true, position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, title: { display: true, text: 'Rupiah' },
                    suggestedMax: maxRevenue > 0 ? undefined : 100000,
                    ticks: { callback: rupiahTick }
                }
            }
        }
    });

    // 2. Status Pie Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_keys($statusCounts)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($statusCounts)) ?>,
                backgroundColor: ['#2ecc71', '#f39c12', '#e74c3c'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // 3. Revenue Bar Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenueData,
                backgroundColor: 'rgba(52,152,219,0.7)',
                borderColor: '#3498db',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: maxRevenue > 0 ? undefined : 100000,
                    ticks: { callback: rupiahTick }
                }
            }
        }
    });
});
</script>
