<?php require_once __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <h2 class="fw-bold">Detail Pesanan #ORD001</h2>
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card rounded-4 shadow-sm p-4">
                <h5>Status Pesanan</h5>
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-warning" style="width: 25%"></div>
                </div>
                <ul class="list-unstyled d-flex justify-content-between">
                    <li class="text-center"><i class="bi bi-check-circle-fill text-success"></i><br>Menunggu Bayar</li>
                    <li class="text-center"><i class="bi bi-hourglass-split text-warning"></i><br>Dibayar</li>
                    <li class="text-center"><i class="bi bi-truck text-muted"></i><br>Diterima</li>
                    <li class="text-center"><i class="bi bi-check-lg text-muted"></i><br>Selesai</li>
                </ul>
                <hr>
                <h5>Informasi Pesanan</h5>
                <p><strong>Jasa:</strong> Jasa Bersih Rumah Profesional</p>
                <p><strong>Penyedia:</strong> Budi W.</p>
                <p><strong>Tanggal:</strong> 15 Juni 2025</p>
                <p><strong>Alamat:</strong> Jl. Contoh No. 123, Jakarta</p>
                <p><strong>Total:</strong> Rp 150.000</p>
                <?php if(!isset($status)) $status = 'waiting_payment'; // dummy default ?>
                <?php if($status == 'waiting_payment'): ?>
                    <a href="upload_payment.php" class="btn btn-primary-custom rounded-pill">Upload Bukti Pembayaran</a>
                <?php elseif($status == 'completed'): ?>
                    <a href="review_form.php" class="btn btn-outline-custom rounded-pill">Beri Review</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>