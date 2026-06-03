<?php require_once __DIR__ . '/../layout/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow border-0 rounded-4">
            <div class="card-body p-5">
                <h3 class="text-center fw-bold mb-4">Daftar sebagai Penyedia</h3>
                <form method="POST">
                    <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" name="name" class="form-control rounded-pill" required></div>
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control rounded-pill" required></div>
                    <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control rounded-pill" required></div>
                    <div class="mb-3"><label class="form-label">Nomor Telepon</label><input type="text" name="phone" class="form-control rounded-pill"></div>
                    <div class="mb-3"><label class="form-label">Alamat</label><textarea name="address" class="form-control" rows="2"></textarea></div>
                    <!-- Tambahan informasi khusus penyedia bisa ditambahkan di sini nantinya -->
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">Daftar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
