<?php
require_once __DIR__ . '/../../models/UserModel.php';

$userModel = new UserModel($pdo);
$pendingProviders = $userModel->getUnverifiedProviders();
$redirect = base_url('index.php?page=admin_verify');
?>

<main class="admin-dashboard">
    <div class="container">
        <?php include __DIR__ . '/_alert.php'; ?>

        <section class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>Verifikasi Penjual</h2>
                    <p>Tinjau dokumen dan setujui atau tolak pendaftaran penyedia jasa.</p>
                </div>
                <a href="<?= base_url('index.php?page=dashboard') ?>" class="link-accent">Kembali</a>
            </div>

            <?php if (empty($pendingProviders)): ?>
                <div class="admin-empty-state">
                    <i class="bi bi-shield-check"></i>
                    <p>Semua penyedia sudah terverifikasi. Tidak ada pendaftaran yang menunggu.</p>
                </div>
            <?php else: ?>
                <div class="admin-verify-grid">
                    <?php foreach ($pendingProviders as $provider): ?>
                        <article class="admin-verify-card">
                            <div class="admin-verify-head">
                                <span class="admin-verify-avatar"><?= e(strtoupper(substr($provider['name'], 0, 1))) ?></span>
                                <div>
                                    <h3><?= e($provider['name']) ?></h3>
                                    <p><?= e($provider['email']) ?></p>
                                </div>
                                <span class="status-badge pending">Pending</span>
                            </div>
                            <ul class="admin-verify-meta">
                                <li><i class="bi bi-telephone"></i> <?= e($provider['phone'] ?: 'Belum diisi') ?></li>
                                <li><i class="bi bi-geo-alt"></i> <?= e($provider['address'] ?: 'Belum diisi') ?></li>
                                <li><i class="bi bi-calendar"></i> Daftar: <?= e(date('d M Y', strtotime($provider['created_at']))) ?></li>
                            </ul>
                            <div class="admin-verify-actions">
                                <form method="post" action="<?= base_url('index.php?page=admin&action=verify_provider') ?>">
                                    <input type="hidden" name="user_id" value="<?= (int) $provider['id'] ?>">
                                    <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
                                    <button type="submit" class="btn btn-primary-custom btn-sm">
                                        <i class="bi bi-check-lg"></i> Setujui
                                    </button>
                                </form>
                                <form method="post" action="<?= base_url('index.php?page=admin&action=reject_provider') ?>" onsubmit="return confirm('Tolak pendaftaran penyedia ini?');">
                                    <input type="hidden" name="user_id" value="<?= (int) $provider['id'] ?>">
                                    <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>
