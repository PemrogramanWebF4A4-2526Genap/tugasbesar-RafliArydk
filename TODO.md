# TODO - Integrasi Fitur (BisaBantu)

- [x] Update database schema user: tambah kolom `profile_photo`.
- [x] Update backend upload foto profile: tangani `profile_photo` di `index.php`, simpan file, lalu update `users.profile_photo`.




- [ ] Cek integrasi tombol “Upload Bayar”, “Beri Review”, dan “Invoice” agar benar-benar kondisional berdasarkan status order (waiting_payment/completed/dll). (Sebagian sudah terlihat kondisional di `views/pembeli/orders.php` dan `order_detail.php`.)
- [ ] Pastikan tombol/link “@BisaBantu/” bisa diklik dan mengarah ke route yang benar atau fungsi yang benar.
- [ ] Jalankan smoke test manual: flow checkout -> order_detail -> upload_payment -> admin verify (optional) -> completed -> review_form.

