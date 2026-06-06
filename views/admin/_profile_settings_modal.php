<div class="modal fade" id="profileSettingsModal" tabindex="-1" aria-labelledby="profileSettingsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.1);">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title" id="profileSettingsLabel">Pengaturan Profil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="profileSettingsForm" method="post" action="<?= base_url('index.php?page=' . $currentPage . '&profile_update=1') ?>" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-3 text-center">
                            <div class="profile-photo-preview" id="profilePhotoPreview" data-initial="<?= e($userInitial) ?>" style="margin: 0 auto; <?= $profilePhotoStyle ?? '' ?>"><?= !empty($profilePhotoExists) ? '' : e($userInitial) ?></div>
                            <input class="form-control form-control-sm mt-2" type="file" id="profilePhotoInput" name="profile_photo" accept="image/*">
                            <small class="text-muted d-block mt-2">Format JPG/PNG</small>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label">Nama depan</label>
                                    <input class="form-control" type="text" name="first_name" value="<?= htmlspecialchars($firstNameValue, ENT_QUOTES) ?>" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Nama belakang</label>
                                    <input class="form-control" type="text" name="last_name" value="<?= htmlspecialchars($lastNameValue, ENT_QUOTES) ?>">
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="form-label">Email</label>
                                <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '', ENT_QUOTES) ?>" required>
                            </div>
                            <div class="mt-2">
                                <label class="form-label">Telepon</label>
                                <input class="form-control" type="tel" name="phone" value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '', ENT_QUOTES) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="address" rows="2"><?= htmlspecialchars($_SESSION['user']['address'] ?? '', ENT_QUOTES) ?></textarea>
                    </div>
                    <hr>
                    <h6 class="mb-3">Ubah Password</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Password lama</label>
                            <input class="form-control" type="password" name="current_password" placeholder="Masukkan password lama">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password baru</label>
                            <input class="form-control" type="password" name="new_password" placeholder="Min. 8 karakter">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ulangi password baru</label>
                        <input class="form-control" type="password" name="confirm_password" placeholder="Ulangi password baru">
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
