<?php
$adminMessages = [
    'provider_verified' => ['success', 'Penyedia berhasil diverifikasi.'],
    'provider_rejected' => ['success', 'Pendaftaran penyedia ditolak.'],
    'user_status_updated' => ['success', 'Status pengguna berhasil diperbarui.'],
    'category_created' => ['success', 'Kategori berhasil ditambahkan.'],
    'category_updated' => ['success', 'Kategori berhasil diperbarui.'],
    'category_deleted' => ['success', 'Kategori berhasil dihapus.'],
    'order_updated' => ['success', 'Status pesanan berhasil diperbarui.'],
    'payment_processed' => ['success', 'Pembayaran berhasil diproses.'],
    'settings_saved' => ['success', 'Pengaturan sistem berhasil disimpan.'],
];
$adminErrors = [
    'verify_failed' => 'Gagal memverifikasi penyedia.',
    'reject_failed' => 'Gagal menolak penyedia.',
    'invalid_user' => 'Pengguna tidak valid.',
    'category_failed' => 'Gagal menyimpan kategori.',
    'category_in_use' => 'Kategori tidak dapat dihapus karena masih digunakan.',
    'order_failed' => 'Gagal memperbarui pesanan.',
    'settings_failed' => 'Gagal menyimpan pengaturan.',
];
if (isset($_GET['msg'], $adminMessages[$_GET['msg']])): ?>
    <div class="admin-alert admin-alert-<?= e($adminMessages[$_GET['msg']][0]) ?>">
        <i class="bi bi-check-circle-fill"></i> <?= e($adminMessages[$_GET['msg']][1]) ?>
    </div>
<?php elseif (isset($_GET['error'], $adminErrors[$_GET['error']])): ?>
    <div class="admin-alert admin-alert-error">
        <i class="bi bi-exclamation-circle-fill"></i> <?= e($adminErrors[$_GET['error']]) ?>
    </div>
<?php endif; ?>
