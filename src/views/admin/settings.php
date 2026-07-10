<?php
require_once __DIR__ . '/../../models/SettingsModel.php';

$settingsModel = new SettingsModel();
$settings = $settingsModel->getAll();
?>

<main class="admin-dashboard">
    <div class="container">
        <?php include __DIR__ . '/_alert.php'; ?>

        <section class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <h2>System Settings</h2>
                    <p>Atur ongkir, metode pembayaran, dan template email.</p>
                </div>
                <a href="<?= base_url('index.php?page=dashboard') ?>" class="link-accent">Kembali</a>
            </div>

            <form method="post" action="<?= base_url('index.php?page=admin&action=save_settings') ?>" class="admin-settings-form">
                <?= csrf_field() ?>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="admin-settings-card">
                            <h3><i class="bi bi-percent"></i> Biaya Admin dan Ongkir</h3>
                            <div class="mb-3">
                                <label class="form-label">Komisi Platform (%)</label>
                                <input type="number" name="commission_rate" class="form-control" min="0" max="100" value="<?= (int) $settings['commission_rate'] ?>" required>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Biaya Ongkir Default (Rp)</label>
                                <input type="number" name="shipping_cost" class="form-control" min="0" value="<?= (int) $settings['shipping_cost'] ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="admin-settings-card">
                            <h3><i class="bi bi-credit-card"></i> Metode Pembayaran</h3>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="payment_methods[]" value="bank_transfer" id="pm_bank" <?= in_array('bank_transfer', $settings['payment_methods'], true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pm_bank">Transfer Bank</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="payment_methods[]" value="cash" id="pm_cash" <?= in_array('cash', $settings['payment_methods'], true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pm_cash">Tunai (COD)</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="admin-settings-card">
                            <h3><i class="bi bi-envelope"></i> Template Email</h3>
                            <div class="mb-3">
                                <label class="form-label">Email Selamat Datang</label>
                                <textarea name="email_template_welcome" class="form-control" rows="2" required><?= e($settings['email_template_welcome']) ?></textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Email Konfirmasi Pesanan</label>
                                <textarea name="email_template_order" class="form-control" rows="2" required><?= e($settings['email_template_order']) ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="admin-settings-card">
                            <h3><i class="bi bi-shield-lock"></i> Keamanan dan Notifikasi</h3>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="notification_enabled" id="notif_on" <?= $settings['notification_enabled'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="notif_on">Aktifkan notifikasi sistem</label>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Session Timeout (menit)</label>
                                <input type="number" name="session_timeout" class="form-control" min="15" value="<?= (int) $settings['session_timeout'] ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-save"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </section>
    </div>
</main>
