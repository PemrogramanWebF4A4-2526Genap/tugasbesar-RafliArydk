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
                <div class="d-flex gap-3 align-items-center">
                    <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Cari user..." style="width: 200px;">
                    <a href="<?= base_url('index.php?page=dashboard') ?>" class="link-accent">Kembali</a>
                </div>
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
                                    <td class="text-end" style="min-width: 220px;">
                                        <div class="admin-action-group">
                                            <?php if ($user['role'] === 'provider' && !$user['is_verified']): ?>
                                                <form method="post" action="<?= base_url('index.php?page=admin&action=verify_provider') ?>" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                                    <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
                                                    <button type="submit" class="btn btn-sm btn-primary-custom">Verifikasi</button>
                                                </form>
                                            <?php endif; ?>
                                            <?php if ($user['role'] !== 'admin'): ?>
                                                <form method="post" action="<?= base_url('index.php?page=admin&action=toggle_suspend') ?>" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                                    <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
                                                    <button type="submit" class="btn btn-sm <?= $isSuspended ? 'btn-outline-custom' : 'btn-outline-danger' ?>">
                                                        <?= $isSuspended ? 'Aktifkan' : 'Suspend' ?>
                                                    </button>
                                                </form>
                                                <form method="post" action="<?= base_url('index.php?page=admin&action=delete_user') ?>" class="d-inline js-admin-delete-user-form" data-user-name="<?= e($user['name']) ?>" data-user-email="<?= e($user['email']) ?>">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                                    <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
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

<div class="admin-confirm-backdrop" id="adminDeleteBackdrop" hidden>
    <div class="admin-confirm-modal" role="dialog" aria-modal="true" aria-labelledby="adminDeleteTitle" aria-describedby="adminDeleteDesc">
        <div class="admin-confirm-icon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <h3 id="adminDeleteTitle">Hapus akun pengguna?</h3>
        <p id="adminDeleteDesc">
            Akun <strong id="adminDeleteUserName">pengguna ini</strong> akan dihapus permanen beserta data terkaitnya.
        </p>
        <div class="admin-confirm-user" id="adminDeleteUserEmail"></div>
        <div class="admin-confirm-actions">
            <button type="button" class="btn btn-outline-custom" id="adminDeleteCancel">Cancel</button>
            <button type="button" class="btn btn-danger" id="adminDeleteConfirm">Konfirmasi</button>
        </div>
    </div>
</div>

<script>
document.getElementById('tableSearch')?.addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.admin-table tbody tr').forEach(row => {
        // Skip the "Tidak ada pengguna" row
        if (row.cells.length === 1) return;
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
});

(function() {
    const backdrop = document.getElementById('adminDeleteBackdrop');
    const cancelBtn = document.getElementById('adminDeleteCancel');
    const confirmBtn = document.getElementById('adminDeleteConfirm');
    const userName = document.getElementById('adminDeleteUserName');
    const userEmail = document.getElementById('adminDeleteUserEmail');
    let pendingForm = null;

    if (!backdrop || !cancelBtn || !confirmBtn || !userName || !userEmail) return;

    function closeDeleteModal() {
        backdrop.hidden = true;
        document.body.classList.remove('admin-confirm-open');
        pendingForm = null;
    }

    document.querySelectorAll('.js-admin-delete-user-form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            pendingForm = form;
            userName.textContent = form.dataset.userName || 'pengguna ini';
            userEmail.textContent = form.dataset.userEmail || '';
            backdrop.hidden = false;
            document.body.classList.add('admin-confirm-open');
            cancelBtn.focus();
        });
    });

    cancelBtn.addEventListener('click', closeDeleteModal);
    backdrop.addEventListener('click', function(event) {
        if (event.target === backdrop) closeDeleteModal();
    });
    document.addEventListener('keydown', function(event) {
        if (!backdrop.hidden && event.key === 'Escape') closeDeleteModal();
    });
    confirmBtn.addEventListener('click', function() {
        if (!pendingForm) return;
        const form = pendingForm;
        pendingForm = null;
        form.submit();
    });
})();
</script>
