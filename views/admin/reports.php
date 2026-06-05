<?php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';

$orderModel = new OrderModel($pdo);
$userModel = new UserModel($pdo);
$reviewModel = new ReviewModel($pdo);

$totalRevenue = $orderModel->getTotalRevenue();
$newUsersMonth = $userModel->countNewThisMonth();
$totalUsers = $userModel->countAll();
$growthPct = $totalUsers > 0 ? round(($newUsersMonth / $totalUsers) * 100, 1) : 0;
$ratingData = $reviewModel->getPlatformAverage();
$avgRating = number_format((float) $ratingData['avg_rating'], 1);
$monthlyData = $orderModel->getMonthlyRevenue(6);
$topServices = $orderModel->getTopServices(5);

$chartLabels = [];
$chartRevenue = [];
foreach ($monthlyData as $row) {
    $chartLabels[] = date('M Y', strtotime($row['month'] . '-01'));
    $chartRevenue[] = (float) $row['revenue'];
}
?>

<main class="admin-dashboard">
    <div class="container">
        <section class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>Report & Analytics</h2>
                    <p>Grafik pendapatan, jasa terlaris, dan pertumbuhan pengguna.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= base_url('index.php?page=report_export&type=orders') ?>" class="btn btn-sm btn-outline-custom">Export Order CSV</a>
                    <a href="<?= base_url('index.php?page=report_export&type=top_services') ?>" class="btn btn-sm btn-outline-custom">Export Jasa CSV</a>
                    <a href="<?= base_url('index.php?page=dashboard') ?>" class="link-accent align-self-center">Kembali</a>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="admin-stat-card">
                        <span><i class="bi bi-cash-stack"></i></span>
                        <div>
                            <strong><?= e(format_rupiah($totalRevenue)) ?></strong>
                            <p>Total pendapatan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="admin-stat-card">
                        <span><i class="bi bi-graph-up"></i></span>
                        <div>
                            <strong><?= e($growthPct) ?>%</strong>
                            <p>Pertumbuhan pengguna bulan ini</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="admin-stat-card">
                        <span><i class="bi bi-star"></i></span>
                        <div>
                            <strong><?= e($avgRating) ?></strong>
                            <p>Rating rata-rata platform</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="admin-chart-panel">
                        <h3>Pendapatan 6 Bulan Terakhir</h3>
                        <canvas id="revenueChart" height="220"></canvas>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="admin-chart-panel">
                        <h3>Jasa Terlaris</h3>
                        <?php if (empty($topServices)): ?>
                            <p class="text-muted">Belum ada data penjualan.</p>
                        <?php else: ?>
                            <ul class="admin-top-list">
                                <?php foreach ($topServices as $i => $svc): ?>
                                    <li>
                                        <span class="admin-top-rank"><?= $i + 1 ?></span>
                                        <div>
                                            <strong><?= e($svc['title']) ?></strong>
                                            <small><?= (int) $svc['order_count'] ?> pesanan · <?= e(format_rupiah($svc['revenue'])) ?></small>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?= json_encode($chartRevenue) ?>,
                backgroundColor: 'rgba(196, 61, 61, 0.7)',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: {
                        callback: function (v) {
                            return 'Rp' + v.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
