<?php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/ScheduleModel.php';

$orderModel = new OrderModel($pdo);
$scheduleModel = new ScheduleModel($pdo);
$orders = array_filter(
    $orderModel->getByProvider($_SESSION['user']['id']),
    fn($o) => in_array($o['status'], ['accepted', 'in_progress', 'completed'], true)
);
$schedules = $scheduleModel->getByProvider($_SESSION['user']['id']);
$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

$statusMap = [
    'accepted' => ['Diterima', 'primary'],
    'in_progress' => ['Proses', 'warning'],
    'completed' => ['Selesai', 'success'],
];
?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Pengiriman / Pekerjaan</h2>
        <span class="badge bg-dark rounded-pill fs-6"><?= count($orders) ?> pekerjaan</span>
    </div>

    <?php if (empty($orders)): ?>
        <div class="text-center py-5">
            <i class="bi bi-send fs-1 text-muted"></i>
            <p class="text-muted mt-2">Belum ada pekerjaan yang sedang diproses.</p>
        </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pembeli</th>
                    <th>Tanggal Jasa</th>
                    <th>Alamat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
                <?php $s = $statusMap[$o['status']] ?? ['Unknown', 'secondary']; ?>
                <tr>
                    <td><strong><?= e($o['order_number']) ?></strong></td>
                    <td><?= e($o['buyer_name']) ?></td>
                    <td><?= date('d M Y', strtotime($o['service_date'])) ?></td>
                    <td><?= e(mb_strimwidth($o['service_address'], 0, 42, '...')) ?></td>
                    <td><span class="badge bg-<?= $s[1] ?>"><?= $s[0] ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h3 class="fw-bold mb-0">Slot Layanan</h3>
        <span class="badge bg-dark rounded-pill"><?= count($schedules) ?> slot</span>
    </div>

    <form method="post" action="<?= base_url('index.php?page=schedule&action=create') ?>" class="row g-2 align-items-end mb-4">
        <?= csrf_field() ?>
        <div class="col-md-3">
            <label class="form-label">Hari</label>
            <select name="day_of_week" class="form-select">
                <?php foreach ($days as $idx => $day): ?>
                    <option value="<?= $idx ?>"><?= e($day) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Mulai</label>
            <input type="time" name="start_time" class="form-control" value="08:00" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Selesai</label>
            <input type="time" name="end_time" class="form-control" value="17:00" required>
        </div>
        <div class="col-md-2">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="is_available" id="is_available" checked>
                <label class="form-check-label" for="is_available">Tersedia</label>
            </div>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary-custom w-100">Simpan Slot</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light"><tr><th>Hari</th><th>Jam</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                <?php if (empty($schedules)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada slot layanan.</td></tr>
                <?php else: ?>
                    <?php foreach ($schedules as $schedule): ?>
                        <tr>
                            <td><?= e($days[(int) $schedule['day_of_week']] ?? '-') ?></td>
                            <td><?= e(substr($schedule['start_time'], 0, 5)) ?> - <?= e(substr($schedule['end_time'], 0, 5)) ?></td>
                            <td><?= (int) $schedule['is_available'] === 1 ? '<span class="badge bg-success">Tersedia</span>' : '<span class="badge bg-secondary">Tidak tersedia</span>' ?></td>
                            <td class="text-end">
                                <form method="post" action="<?= base_url('index.php?page=schedule&action=delete') ?>" class="d-inline" onsubmit="return confirm('Hapus slot ini?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= (int) $schedule['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
