# Panduan Pengguna (User Manual) - BisaBantu

Selamat datang di Panduan Pengguna **BisaBantu**. Dokumen ini dirancang untuk memandu seluruh peran pengguna (Pembeli, Penjual, dan Admin) dalam mengoperasikan fitur-fitur yang ada di platform kami.

---

## 🛍️ 1. Panduan Pembeli (Buyer)

### 1.1 Registrasi & Pendaftaran Akun
1. Buka peramban (browser) dan akses halaman utama BisaBantu.
2. Klik tombol **Register** pada sudut kanan atas bilah menu (navbar).
3. Isi formulir pendaftaran secara lengkap:
   * Nama Lengkap
   * Email Aktif
   * Password
   * Nomor Telepon
   * Alamat Domisili
4. Klik **Daftar** untuk membuat akun baru.
5. Gunakan email dan password tersebut pada menu **Login** untuk masuk ke sistem.

### 1.2 Pencarian Layanan & Detail Jasa
1. Di halaman **Beranda**, semua katalog layanan aktif akan ditampilkan.
2. Anda dapat menyaring layanan berdasarkan **Kategori** (menu filter) dan **Lokasi** (area cakupan).
3. Ketik kata kunci pada kotak pencarian untuk menemukan layanan spesifik.
4. Klik tombol **Detail** pada kartu layanan untuk melihat deskripsi jasa, harga per unit, satuan harga, perkiraan durasi kerja, lokasi, serta riwayat rating dari pembeli sebelumnya.

### 1.3 Melakukan Pemesanan (Checkout & Pembayaran)
1. Pada detail jasa, tentukan jumlah kuantitas pesanan, lalu klik **Tambah ke Keranjang**.
2. Buka halaman keranjang belanja (ikon keranjang di navbar) untuk meninjau pesanan Anda.
3. Klik **Lanjutkan ke Checkout**.
4. Isi data pelaksanaan dengan teliti:
   * **Alamat Pelaksanaan**: Tempat jasa akan dikerjakan.
   * **Tanggal Pelaksanaan**: Kapan jasa ingin mulai dikerjakan.
   * **Catatan Tambahan**: Instruksi atau pesan tambahan untuk penyedia.
5. Pilih metode pembayaran (*Bank Transfer* atau *Cash on Delivery*).
6. Klik **Buat Pesanan**. Status pesanan akan menjadi `waiting_payment` (jika menggunakan Bank Transfer).

### 1.4 Melakukan Konfirmasi Pembayaran
1. Buka menu **Pesanan Saya** (dashboard pembeli).
2. Temukan pesanan Anda yang bertuliskan status `Menunggu Pembayaran`.
3. Klik tombol **Upload Bukti Pembayaran**.
4. Pilih gambar bukti transfer bank Anda (maksimal ukuran file 2MB), lalu klik **Kirim Bukti**.
5. Tunggu Administrator memvalidasi pembayaran Anda.

### 1.5 Melacak Pesanan & Ulasan Pekerjaan
* **Alur Status Transaksi**:
  `Pending` ➔ `Waiting Payment` ➔ `Paid` (setelah pembayaran divalidasi) ➔ `Accepted` (diterima oleh penyedia) ➔ `In Progress` (sedang dikerjakan) ➔ `Completed` (pekerjaan selesai).
* Setelah pesanan bertatus `Selesai` (Completed), Anda dapat memberikan penilaian dengan membuka halaman detail pesanan lalu memilih tombol **Beri Ulasan (Review)**. Masukkan rating bintang 1-5, isi ulasan teks, dan upload foto dokumentasi hasil kerja (opsional).

---

## 🛠️ 2. Panduan Penjual (Seller/Provider)

### 2.1 Pendaftaran & Verifikasi Kemitraan
1. Pada halaman registrasi, pilih opsi peran sebagai **Penjual (Seller/Provider)**.
2. Isi formulir informasi data diri dan data usaha layanan Anda.
3. Setelah mendaftar, akun Anda berstatus **Pending Verifikasi**.
4. Akun Anda harus disetujui terlebih dahulu oleh Admin sebelum dapat mengakses dasbor penuh dan membuat iklan layanan jasa.

### 2.2 Dasbor Utama Penyedia
* Setelah login dengan akun terverifikasi, Anda akan diarahkan ke Dasbor Penyedia.
* Dasbor menampilkan grafik pendapatan, jumlah transaksi, rating rata-rata kualitas layanan, serta ringkasan aktivitas pesanan terbaru.

### 2.3 Manajemen Penawaran Jasa (CRUD)
1. Buka menu **Jasa Saya**.
2. **Tambah Jasa Baru**: Klik tombol **Tambah Jasa**, isi judul layanan, deskripsi pengerjaan, pilih kategori yang relevan, tentukan harga, satuan harga (misal: per jam/per kunjungan), estimasi waktu, area jangkauan, dan unggah foto profil layanan terbaik.
3. **Ubah/Hapus Jasa**: Klik ikon pensil untuk memperbarui deskripsi atau matikan status `is_active` untuk menyembunyikan layanan sementara dari pembeli.

### 2.4 Mengelola Pesanan Masuk
1. Buka menu **Pesanan Masuk**.
2. Setiap pesanan baru dengan status pembayaran valid (`Paid` / `Cash on Delivery`) akan masuk ke daftar ini.
3. Klik **Terima Pesanan** jika Anda menyanggupi pengerjaan pada tanggal pelaksanaan tersebut.
4. Klik **Tolak Pesanan** jika jadwal Anda sedang penuh.
5. Perbarui status pesanan secara bertahap saat Anda mulai bekerja menjadi `Sedang Dikerjakan` (In Progress) dan tandai sebagai `Selesai` (Completed) jika pekerjaan telah selesai sepenuhnya.

---

## 👑 3. Panduan Administrator (Admin)

### 3.1 Login Admin
* Gunakan email administrator `admin@bisabantu.com` dengan password default `password`.

### 3.2 Verifikasi Mitra Baru (Provider)
1. Masuk ke menu **Kelola Pengguna** ➔ Tab **Penyedia Jasa**.
2. Daftar penyedia baru dengan status belum diverifikasi akan diberi penanda khusus.
3. Klik tombol **Verifikasi** setelah memastikan profil dan data penyedia valid untuk mengaktifkan akun mereka.

### 3.3 Verifikasi Bukti Pembayaran Transaksi
1. Buka menu **Semua Pesanan**.
2. Klik tombol detail pada transaksi yang memiliki status pembayaran `Pending/Waiting Verification`.
3. Periksa gambar bukti transfer yang diunggah oleh pembeli.
4. Klik **Konfirmasi Pembayaran** jika bukti valid (status pesanan otomatis berubah ke `Paid`), atau klik **Tolak Pembayaran** dan isi alasan penolakan jika tidak valid.

### 3.4 Kelola Kategori Jasa
1. Masuk ke menu **Kategori Jasa**.
2. Anda dapat menambahkan kategori baru, mengubah nama kategori lama, atau menghapus kategori yang tidak lagi digunakan.
3. *Catatan*: Kategori yang masih terikat dengan penawaran jasa aktif milik penyedia tidak dapat dihapus untuk menjaga integritas data.

### 3.5 Laporan Pendapatan & Grafik Analitik
1. Buka menu **Laporan**.
2. Tentukan filter rentang tanggal laporan.
3. Dasbor akan menampilkan metrik total transaksi, total perputaran uang, layanan paling laris, dan penyedia terbaik.
4. Klik tombol **Ekspor CSV** untuk mengunduh laporan detail dalam format spreadsheet.
