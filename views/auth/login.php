<?php require_once __DIR__ . '/../layout/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow border-0 rounded-4">
            <div class="card-body p-5">
                <h3 class="text-center fw-bold mb-4">Masuk ke Akun</h3>
                <form method="POST" action="#">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control rounded-pill" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control rounded-pill" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="remember">
                        <label class="form-check-label">Ingat saya</label>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">Masuk</button>
                </form>
                <hr>
                <p class="text-center">Belum punya akun? <a href="index.php?page=auth&action=register_pembeli" class="text-decoration-none" style="color: var(--orange-primary);">Daftar sebagai Pembeli</a> | <a href="index.php?page=auth&action=register_penyedia" style="color: var(--orange-primary);">Daftar sebagai Penyedia</a></p>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>