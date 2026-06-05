<?php
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/SettingsModel.php';

$userModel = new UserModel($pdo);
$settingsModel = new SettingsModel();

$tab = $_GET['tab'] ?? 'all';
$users = $userModel->getAll();
$filtered = array_filter($users, function ($u) use ($tab) {
    if ($tab === 'buyer') return $u['role'] === 'buyer';
    if ($tab === 'provider') return $u['role'] === 'provider';
    if ($tab === 'admin') return $u['role'] === 'admin';
    return $u['role'] !== 'admin';
});
$redirect = base_url('index.php?page=admin_users&tab=' . urlencode($tab));
?>

<main class="admin-dashboard">
    <div class="container">
        <?php include __DIR__ . '/_alert.php'; ?>

        <section class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>Manage User</h2>
                    <p>Kelola akun pembeli dan penyedia jasa, verifikasi, dan suspend pengguna.</p>
                </div>
                <a href="<?= base_url('index.php?page=dashboard') ?>" class="link-accent">Kembali</a>
            </div>

            <div class="admin-tabs">
                <a href="<?= base_url('index.php?page=admin_users&tab=all') ?>" class="<?= $tab === 'all' ? 'active' : '' ?>">Semua</a>
                <a href="<?= base_url('index.php?page=admin_users&tab=buyer') ?>" class="<?= $tab === 'buyer' ? 'active' : '' ?>">Pembeli</a>
                <a href="<?= base_url('index.php?page=admin_users&tab=provider') ?>" class="<?= $tab === 'provider' ? 'active' : '' ?>">Penyedia</a>
            </div>

            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($filtered)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada pengguna.</td></tr>
                        <?php else: ?>
                            <?php foreach ($filtered as $user): ?>
                                <?php
                                $isSuspended = $settingsModel->isUserSuspended($user['id']);
                                if ($user['role'] === 'provider' && !$user['is_verified']) {
                                    $statusLabel = 'Pending Verifikasi';
                                    $statusClass = 'pending';
                                } elseif ($isSuspended) {
                                    $statusLabel = 'Disuspend';
                                    $statusClass = 'inactive';
                                } else {
                                    $statusLabel = $user['role'] === 'provider' ? 'Terverifikasi' : 'Aktif';
                                    $statusClass = 'verified';
                                }
                                ?>
                                <tr>
                                    <td><?= e($user['name']) ?></td>
                                    <td><?= e($user['email']) ?></td>
                                    <td><?= e(role_label($user['role'])) ?></td>
                                    <td><span class="status-badge <?= e($statusClass) ?>"><?= e($statusLabel) ?></span></td>
                                    <td class="text-end">
                                        <div class="admin-action-group">
                                            <?php if ($user['role'] === 'provider' && !$user['is_verified']): ?>
                                                <form method="post" action="<?= base_url('index.php?page=admin&action=verify_provider') ?>" class="d-inline">
                                                    <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                                    <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
                                                    <button type="submit" class="btn btn-sm btn-primary-custom">Verifikasi</button>
                                                </form>
                                            <?php endif; ?>
                                            <?php if ($user['role'] !== 'admin'): ?>
                                                <form method="post" action="<?= base_url('index.php?page=admin&action=toggle_suspend') ?>" class="d-inline">
                                                    <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                                    <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
                                                    <button type="submit" class="btn btn-sm <?= $isSuspended ? 'btn-outline-custom' : 'btn-outline-danger' ?>">
                                                        <?= $isSuspended ? 'Aktifkan' : 'Suspend' ?>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
