# Panduan Pengguna – BisaBantu

## 1. Untuk Pembeli

### 1.1 Registrasi dan Login
- Klik **Register** di pojok kanan atas.
- Isi nama, email, password, nomor telepon, dan alamat.
- Klik **Daftar**, lalu login dengan email dan password.

### 1.2 Mencari Jasa
- Pada halaman **Beranda**, lihat daftar jasa.
- Gunakan filter **Kategori** dan **Lokasi**.
- Ketik kata kunci pada **Search** untuk mencari judul jasa.

### 1.3 Melihat Detail Jasa
- Klik tombol **Detail** pada salah satu jasa.
- Anda akan melihat deskripsi lengkap, harga per unit, durasi estimasi, area layanan, dan review dari pembeli lain.
- Klik **Tambah ke Keranjang** untuk memasukkan jasa ke keranjang belanja.

### 1.4 Keranjang dan Checkout
- Klik ikon keranjang di navbar.
- Anda bisa mengubah **jumlah unit** atau menghapus jasa.
- Klik **Lanjutkan ke Checkout**.
- Isi **alamat pelaksanaan** (tempat jasa akan dikerjakan).
- Pilih **tanggal pelaksanaan** yang diinginkan.
- Pilih **metode pembayaran** (Transfer Bank atau COD).
- Klik **Buat Pesanan**.

### 1.5 Pembayaran
- Setelah checkout, buka menu **Pesanan Saya**.
- Klik **Upload Bukti** pada pesanan dengan status "Menunggu Pembayaran".
- Upload foto bukti transfer (jpg/png, maks 2MB).
- Klik **Kirim Bukti**. Admin akan memverifikasi.

### 1.6 Tracking Pesanan
- Status pesanan berubah:
  - `Menunggu Pembayaran` → `Dibayar` (setelah admin verifikasi) → `Diterima Penyedia` → `Sedang Dikerjakan` → `Selesai`
- Anda akan mendapat notifikasi email dan in-app setiap perubahan status.

### 1.7 Review & Rating
- Setelah pesanan selesai, buka detail pesanan.
- Klik **Beri Review**.
- Beri rating bintang (1-5), tulis komentar, dan upload foto hasil pekerjaan (opsional).

---

## 2. Untuk Penyedia Jasa

### 2.1 Registrasi dan Verifikasi
- Klik **Daftar sebagai Penyedia**.
- Isi data diri lengkap (nama, email, password, telepon, alamat).
- Setelah submit, akun Anda akan berstatus "pending verifikasi". Tunggu admin mengaktifkan.
- Anda akan mendapat email notifikasi jika sudah diverifikasi.

### 2.2 Login dan Dashboard
- Login dengan email dan password.
- Dashboard menampilkan: total pesanan, pendapatan, rating rata-rata, grafik penjualan (Chart.js).

### 2.3 Mengelola Jasa (CRUD)
- Buka menu **Jasa Saya**.
- **Tambah Jasa**: Klik tombol "Tambah", isi judul, deskripsi, kategori, harga per unit, satuan (per jam/per kg), durasi estimasi, lokasi layanan, dan upload gambar.
- **Edit / Hapus**: Klik ikon pensil atau tempat sampah pada daftar jasa.

### 2.4 Mengelola Pesanan
- Buka menu **Pesanan Masuk**.
- Untuk pesanan dengan status `Dibayar`:
  - Klik **Terima** jika Anda bersedia mengerjakan → status menjadi `Diterima Penyedia`.
  - Jika tidak bisa, klik **Tolak** (pesanan batal, dana dikembalikan ke pembeli via admin).
- Saat mulai mengerjakan: ubah status menjadi `Sedang Dikerjakan`.
- Setelah selesai: ubah status menjadi `Selesai`. Pembeli akan diminta review.

### 2.5 Pendapatan
- Menu **Riwayat Pendapatan** menampilkan daftar pesanan yang sudah selesai beserta total pendapatan yang diterima (setelah dipotong biaya admin jika ada).

---

## 3. Untuk Admin

### 3.1 Login
- Gunakan akun admin yang sudah disediakan (atau buat langsung di database).

### 3.2 Dashboard Admin
- Menampilkan statistik global: jumlah pengguna (pembeli/penyedia), jumlah pesanan, total pendapatan, grafik bulanan.

### 3.3 Verifikasi Penyedia Jasa
- Buka menu **Kelola Pengguna** → tab **Penyedia Jasa**.
- Penyedia yang belum diverifikasi akan ditandai.
- Klik **Verifikasi** untuk mengaktifkan akun penyedia.
- Klik **Nonaktifkan** jika penyedia melanggar aturan.

### 3.4 Kelola Kategori
- Menu **Kategori Jasa**.
- Tambah, edit, atau hapus kategori (misal: Bersih-bersih, Perbaikan, Les Privat).
- Kategori yang memiliki jasa tidak dapat dihapus (akan muncul peringatan).

### 3.5 Kelola Semua Pesanan
- Menu **Semua Pesanan** (lintas penyedia).
- Bisa melihat detail, mengubah status secara manual (jika diperlukan), atau membatalkan pesanan.

### 3.6 Laporan & Ekspor
- Menu **Laporan**:
  - Pilih rentang tanggal.
  - Tampilkan total pendapatan, jumlah pesanan, jasa terpopuler, penyedia terbaik.
  - Klik **Ekspor CSV** untuk mengunduh data.

### 3.7 Pengaturan Sistem
- Menu **Pengaturan**:
  - Biaya admin (persen dari harga jasa, misal 5%).
  - Batas waktu pembayaran (jam/hari).
  - Alamat email sistem untuk notifikasi.

---

## 4. Notifikasi Sistem (Email & In-app)

| Event | Notifikasi Email | In-app |
|-------|------------------|--------|
| Registrasi berhasil | ✅ Link verifikasi (jika diaktifkan) | ❌ |
| Akun penyedia diverifikasi | ✅ Selamat, Anda dapat mulai menawarkan jasa | ✅ |
| Pembayaran diverifikasi admin | ✅ ke pembeli & penyedia | ✅ |
| Pesanan diterima penyedia | ✅ ke pembeli | ✅ |
| Pesanan selesai | ✅ minta review | ✅ |
| Invoice tersedia | ✅ link download PDF | ✅ |

---

## 5. Pemecahan Masalah Umum

| Masalah | Solusi |
|---------|--------|
| Tidak bisa login | Cek email dan password. Lupa password? Gunakan fitur "Lupa Password" (jika diimplementasikan) atau hubungi admin. |
| Upload bukti gagal | Pastikan file gambar (jpg/png) dan ukuran <2MB. Cek folder `uploads/payments` dapat ditulis. |
| Status pesanan tidak berubah | Refresh halaman. Jika masih, hubungi admin. |
| Penyedia tidak muncul di pencarian | Pastikan akun penyedia sudah diverifikasi dan jasa di-set "aktif". |

---

## 6. Tips Optimal

- **Pembeli**: Beri review sejujur mungkin untuk membantu pengguna lain.  
- **Penyedia**: Lengkapi deskripsi jasa dengan foto menarik dan informasi jelas.  
- **Admin**: Rutin cek laporan untuk memantau aktivitas mencurigakan.
