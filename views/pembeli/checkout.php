<main class="checkout-page">
    <div class="container">
        <h2>Checkout</h2>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="checkout-card">
                    <div class="card-body">
                        <div class="checkout-section mb-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-start">
                                <div>
                                    <div class="checkout-section-title">Alamat Pengiriman</div>
                                    <h5>Rumah · Rafli Aryadika</h5>
                                    <p>Jl. Perjuangan Teluk Pucung 2 Rt02 Rw 01 (Rumah Pagar Hitam no 05, Gang Merah Putih), Bekasi Utara, Kota Bekasi, Jawa Barat</p>
                                </div>
                                <button type="button" class="btn btn-outline-custom btn-sm">Ganti</button>
                            </div>
                        </div>

                        <div class="checkout-section mb-4">
                            <div class="checkout-section-title">ALVACLOTH</div>
                            <div class="checkout-product-card">
                                <div class="d-flex gap-3 align-items-start">
                                    <img src="<?= base_url('assets/images/product-placeholder.jpg') ?>" alt="Produk" />
                                    <div class="checkout-product-info">
                                        <h5>Kaos Polos Premium Cotton Combed 24S Reguler Fit Unisex T-Shirt Oneck Pria Wanita Lengan Pendek</h5>
                                        <small>HITAM, M</small>
                                        <div class="d-flex flex-wrap gap-2 mt-3">
                                            <span class="badge bg-success rounded-pill">1 x Rp42.700</span>
                                            <span class="text-muted">Proteksi Rusak Total 3 bulan (Rp4.500)</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="checkout-product-summary mt-4">
                                    <div class="summary-row">
                                        <span>Reguler</span>
                                        <strong></strong>
                                    </div>
                                    <div class="summary-row">
                                        <span><strong>JNE (Rp10.000)</strong><br><small>Estimasi tiba 6 - 9 Jun</small></span>
                                    </div>
                                    <div class="text-muted small mt-2">
                                        <i class="bi bi-shield-check me-2"></i>Pakai Asuransi Pengiriman (Rp400)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-section">
                            <button type="button" class="btn btn-outline-custom w-100">Kasih Catatan <span class="text-muted">0/200</span></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="payment-method-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-1">Metode Pembayaran</h6>
                        </div>
                        <a href="#" class="text-success small">Lihat Semua</a>
                    </div>

                    <div class="payment-option">
                        <label>
                            <div class="payment-method-icon"><i class="bi bi-bank fs-4"></i></div>
                            <div>
                                <strong>BCA Virtual Account</strong>
                                <div class="payment-description">Transfer via BCA Virtual Account</div>
                            </div>
                            <div class="payment-radio">
                                <input type="radio" name="payment_method" checked>
                            </div>
                        </label>
                    </div>

                    <div class="payment-option">
                        <label>
                            <div class="payment-method-icon"><i class="bi bi-bank2 fs-4"></i></div>
                            <div>
                                <strong>BRI Virtual Account</strong>
                                <div class="payment-description">Transfer via BRI Virtual Account</div>
                            </div>
                            <div class="payment-radio">
                                <input type="radio" name="payment_method">
                            </div>
                        </label>
                    </div>

                    <div class="payment-option">
                        <label>
                            <div class="payment-method-icon"><i class="bi bi-wallet2 fs-4"></i></div>
                            <div>
                                <strong>GoPay</strong>
                                <div class="payment-description">Rp17.567 terpakai. Bisa digabung metode lain</div>
                            </div>
                            <div class="payment-radio">
                                <input type="radio" name="payment_method">
                            </div>
                        </label>
                    </div>

                    <div class="payment-option">
                        <label>
                            <div class="payment-method-icon"><i class="bi bi-shop fs-4"></i></div>
                            <div>
                                <strong>Alfamart / Alfamidi / Lawson / Dan+Dan</strong>
                                <div class="payment-description">Bayar di gerai terdekat</div>
                            </div>
                            <div class="payment-radio">
                                <input type="radio" name="payment_method">
                            </div>
                        </label>
                    </div>

                    <button type="button" class="btn btn-outline-custom w-100 mt-3">Pakai promo biar makin hemat!</button>
                </div>

                <div class="payment-summary-card mt-4">
                    <h6 class="summary-title">Cek ringkasan transaksi-mu, yuk</h6>
                    <div class="summary-row">
                        <span>Total Harga (1 Barang)</span>
                        <strong>Rp70.000</strong>
                    </div>
                    <div class="summary-row">
                        <span>Total Tagihan</span>
                        <strong>Rp59.600</strong>
                    </div>
                    <button class="btn btn-primary-custom btn-pay rounded-pill">Bayar Sekarang</button>
                    <p class="small text-muted mt-3">Dengan melanjutkan pembayaran, kamu menyetujui S&K Asuransi Pengiriman & Proteksi.</p>
                </div>
            </div>
        </div>
    </div>
</main>
