<?php require_once __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card rounded-4 shadow-sm p-4">
                <h4 class="text-center">Beri Review & Rating</h4>
                <p class="text-center">Jasa: Jasa Bersih Rumah</p>
                <form>
                    <div class="mb-3 text-center">
                        <label class="form-label">Rating</label><br>
                        <div class="rating-stars">
                            <i class="bi bi-star fs-3" data-value="1"></i>
                            <i class="bi bi-star fs-3" data-value="2"></i>
                            <i class="bi bi-star fs-3" data-value="3"></i>
                            <i class="bi bi-star fs-3" data-value="4"></i>
                            <i class="bi bi-star fs-3" data-value="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="rating_value">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Komentar</label>
                        <textarea class="form-control" rows="3" placeholder="Tulis pengalaman Anda..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Foto (opsional)</label>
                        <input type="file" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">Kirim Review</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Simple star rating JS
    document.querySelectorAll('.rating-stars i').forEach(star => {
        star.addEventListener('click', function() {
            let value = this.getAttribute('data-value');
            document.getElementById('rating_value').value = value;
            document.querySelectorAll('.rating-stars i').forEach(s => s.classList.remove('text-warning'));
            for(let i=0; i<value; i++) {
                document.querySelectorAll('.rating-stars i')[i].classList.add('text-warning');
            }
        });
    });
</script>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
