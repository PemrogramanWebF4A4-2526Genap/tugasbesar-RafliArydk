<?php
require_once __DIR__ . '/../../models/CategoryModel.php';

$categoryModel = new CategoryModel($pdo);
$categories = $categoryModel->getAllWithServiceCount();
$editId = (int) ($_GET['edit'] ?? 0);
$editCategory = $editId ? $categoryModel->getById($editId) : null;
?>

<main class="admin-dashboard">
    <div class="container">
        <?php include __DIR__ . '/_alert.php'; ?>

        <section class="admin-panel mb-4">
            <div class="admin-panel-head">
                <div>
                    <h2>Manage Kategori</h2>
                    <p>CRUD kategori jasa: bersih-bersih, tukang, les, dan lainnya.</p>
                </div>
                <a href="<?= base_url('index.php?page=dashboard') ?>" class="link-accent">Kembali</a>
            </div>

            <form method="post" action="<?= base_url('index.php?page=admin&action=' . ($editCategory ? 'category_update' : 'category_create')) ?>" class="admin-form-row">
                <?php if ($editCategory): ?>
                    <input type="hidden" name="id" value="<?= (int) $editCategory['id'] ?>">
                <?php endif; ?>
                <div class="admin-form-field">
                    <label>Nama Kategori</label>
                    <input type="text" name="name" class="form-control" required placeholder="Contoh: Bersih-bersih" value="<?= e($editCategory['name'] ?? '') ?>">
                </div>
                <div class="admin-form-field admin-form-field-grow">
                    <label>Deskripsi</label>
                    <input type="text" name="description" class="form-control" placeholder="Deskripsi singkat kategori" value="<?= e($editCategory['description'] ?? '') ?>">
                </div>
                <div class="admin-form-field admin-form-field-action">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary-custom">
                        <?= $editCategory ? 'Simpan Perubahan' : 'Tambah Kategori' ?>
                    </button>
                    <?php if ($editCategory): ?>
                        <a href="<?= base_url('index.php?page=admin_categories') ?>" class="btn btn-outline-custom">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section class="admin-panel">
            <div class="admin-menu-grid">
                <?php if (empty($categories)): ?>
                    <p class="text-muted mb-0">Belum ada kategori.</p>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <div class="admin-menu-card admin-category-card">
                            <span><i class="bi <?= e(category_icon($cat['name'])) ?>"></i></span>
                            <div>
                                <h3><?= e($cat['name']) ?></h3>
                                <p><?= (int) $cat['service_count'] ?> jasa aktif</p>
                                <?php if ($cat['description']): ?>
                                    <small class="text-muted"><?= e($cat['description']) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="admin-category-actions">
                                <a href="<?= base_url('index.php?page=admin_categories&edit=' . (int) $cat['id']) ?>" class="btn btn-sm btn-outline-custom">Edit</a>
                                <form method="post" action="<?= base_url('index.php?page=admin&action=category_delete') ?>" onsubmit="return confirm('Hapus kategori ini?');">
                                    <input type="hidden" name="id" value="<?= (int) $cat['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>
