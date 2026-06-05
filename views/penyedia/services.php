<?php
require_once __DIR__ . '/../../models/ServiceModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';

$serviceModel = new ServiceModel($pdo);
$categoryModel = new CategoryModel($pdo);
$services = $serviceModel->getByProvider($_SESSION['user']['id']);
$categories = $categoryModel->getAll();
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <h2 class="fw-bold mb-0">Kelola Jasa Saya</h2>
        <button class="btn btn-primary-custom rounded-pill" data-bs-toggle="modal" data-bs-target="#addModal">+ Tambah Jasa</button>
    </div>

    <div class="table-responsive mt-4">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($services)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada jasa. Tambahkan layanan pertama Anda.</td></tr>
                <?php else: ?>
                    <?php foreach ($services as $service): ?>
                        <?php $imagePath = $service['image'] ? 'assets/uploads/services/' . $service['image'] : ''; ?>
                        <tr>
                            <td>
                                <?php if ($imagePath && is_file(__DIR__ . '/../../' . $imagePath)): ?>
                                    <img src="<?= e(base_url($imagePath)) ?>" alt="<?= e($service['title']) ?>" style="width:56px;height:56px;object-fit:cover;border-radius:8px;">
                                <?php else: ?>
                                    <i class="bi bi-image fs-3 text-muted"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= e($service['title']) ?></strong>
                                <div class="small text-muted"><?= e(mb_strimwidth($service['description'], 0, 70, '...')) ?></div>
                            </td>
                            <td><?= e($service['category_name']) ?></td>
                            <td><?= e(format_rupiah($service['price'])) ?> / <?= e($service['price_unit']) ?></td>
                            <td>
                                <?php if ((int) $service['is_active'] === 1): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-warning rounded-pill" data-bs-toggle="modal" data-bs-target="#editModal<?= (int) $service['id'] ?>">Edit</button>
                                <a class="btn btn-sm btn-outline-secondary rounded-pill" href="<?= base_url('index.php?page=service&action=toggle&id=' . (int) $service['id'] . '&status=' . ((int) $service['is_active'] === 1 ? 0 : 1)) ?>">
                                    <?= (int) $service['is_active'] === 1 ? 'Nonaktifkan' : 'Aktifkan' ?>
                                </a>
                                <a class="btn btn-sm btn-outline-danger rounded-pill" href="<?= base_url('index.php?page=service&action=delete&id=' . (int) $service['id']) ?>" onclick="return confirm('Hapus jasa ini?')">Hapus</a>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal<?= (int) $service['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content rounded-4 border-0 shadow">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title fw-bold">Edit Jasa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="<?= base_url('index.php?page=service&action=update') ?>" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="<?= (int) $service['id'] ?>">
                                            <div class="mb-3"><label>Judul Jasa</label><input type="text" name="title" value="<?= e($service['title']) ?>" class="form-control rounded-pill" required></div>
                                            <div class="mb-3">
                                                <label>Kategori</label>
                                                <select name="category_id" class="form-select rounded-pill">
                                                    <?php foreach ($categories as $category): ?>
                                                        <option value="<?= (int) $category['id'] ?>" <?= (int) $service['category_id'] === (int) $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3"><label>Harga (Rp)</label><input type="number" name="price" value="<?= (int) $service['price'] ?>" class="form-control rounded-pill" min="0" required></div>
                                            <div class="mb-3"><label>Satuan Harga</label><input type="text" name="price_unit" value="<?= e($service['price_unit']) ?>" class="form-control rounded-pill"></div>
                                            <div class="mb-3"><label>Estimasi Durasi</label><input type="text" name="estimated_duration" value="<?= e($service['estimated_duration']) ?>" class="form-control rounded-pill"></div>
                                            <div class="mb-3"><label>Lokasi</label><input type="text" name="location" value="<?= e($service['location']) ?>" class="form-control rounded-pill" required></div>
                                            <div class="mb-3"><label>Deskripsi</label><textarea name="description" class="form-control" rows="3" required><?= e($service['description']) ?></textarea></div>
                                            <div class="mb-3"><label>Gambar Baru</label><input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png"></div>
                                            <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">Simpan Perubahan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Jasa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= base_url('index.php?page=service&action=create') ?>" enctype="multipart/form-data">
                    <div class="mb-3"><label>Judul Jasa</label><input type="text" name="title" class="form-control rounded-pill" required></div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="category_id" class="form-select rounded-pill">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= (int) $category['id'] ?>"><?= e($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label>Harga (Rp)</label><input type="number" name="price" class="form-control rounded-pill" min="0" required></div>
                    <div class="mb-3"><label>Satuan Harga</label><input type="text" name="price_unit" class="form-control rounded-pill" value="per kunjungan"></div>
                    <div class="mb-3"><label>Estimasi Durasi</label><input type="text" name="estimated_duration" class="form-control rounded-pill" placeholder="Contoh: 2 jam"></div>
                    <div class="mb-3"><label>Lokasi</label><input type="text" name="location" class="form-control rounded-pill" required></div>
                    <div class="mb-3"><label>Deskripsi</label><textarea name="description" class="form-control" rows="3" required></textarea></div>
                    <div class="mb-3"><label>Gambar</label><input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png"></div>
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">Simpan Jasa</button>
                </form>
            </div>
        </div>
    </div>
</div>
