<?php include __DIR__ . '/../layout/header.php'; ?>
<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="fw-bold mb-0">Notifikasi</h1>
        <?php if (!empty($notifications)): ?>
            <form method="post" action="<?= base_url('index.php?page=notification&action=read_all') ?>">
                <button type="submit" class="btn btn-sm btn-primary-custom">Tandai Semua Dibaca</button>
            </form>
        <?php endif; ?>
    </div>
    <?php if (empty($notifications)): ?>
        <div class="alert alert-info">Tidak ada notifikasi baru.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($notifications as $notification): ?>
                <form method="post" action="<?= base_url('index.php?page=notification&action=read') ?>" class="list-group-item">
                    <input type="hidden" name="id" value="<?= (int) $notification['id'] ?>">
                    <div class="d-flex justify-content-between gap-3">
                        <div>
                            <strong><?= e($notification['title']) ?></strong>
                            <p class="mb-0 text-muted"><?= e($notification['message']) ?></p>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" type="submit">Tandai Dibaca</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
<?php include __DIR__ . '/../layout/footer.php'; ?>
