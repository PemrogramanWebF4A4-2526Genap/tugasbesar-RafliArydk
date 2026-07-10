# Testing Report — BisaBantu
**Tanggal Pengujian:** 1 Juli 2026
**Versi Aplikasi:** 1.0.0 (UAS Final Release)
**Penguji:** Rafli Aryadika (202410715061)

---

## 📋 Ringkasan Pengujian

Pengujian dilakukan secara menyeluruh pada semua alur utama aplikasi marketplace jasa lokal **BisaBantu**, mencakup: autentikasi multi-role, CRUD data, alur transaksi end-to-end, keamanan, dan responsivitas tampilan pada berbagai perangkat.

---

## ✅ Checklist Fitur & Hasil Pengujian

### 🔐 Autentikasi & Keamanan

| No | Skenario Uji | Metode | Status |
|---|---|---|---|
| 1 | Register akun Pembeli dengan validasi form | Manual | ✅ Lulus |
| 2 | Register akun Penjual (Seller) | Manual | ✅ Lulus |
| 3 | Login dengan kredensial benar | Manual | ✅ Lulus |
| 4 | Login dengan password salah → pesan error | Manual | ✅ Lulus |
| 5 | Logout & session dihapus | Manual | ✅ Lulus |
| 6 | Password di-hash bcrypt (`password_hash`) | Code Review | ✅ Lulus |
| 7 | Login menggunakan `password_verify()` | Code Review | ✅ Lulus |
| 8 | Akses halaman admin tanpa login → redirect | Manual | ✅ Lulus |
| 9 | Akses halaman seller sebagai buyer → redirect | Manual | ✅ Lulus |
| 10 | Semua query DB menggunakan PDO prepared statement | Code Review | ✅ Lulus |
| 11 | Semua output HTML di-escape via `e()` (`htmlspecialchars`) | Code Review | ✅ Lulus |
| 12 | Upload file hanya menerima JPG/PNG | Manual | ✅ Lulus |

---

### 🏠 Halaman Publik

| No | Skenario Uji | Status |
|---|---|---|
| 13 | Halaman beranda dapat diakses tanpa login | ✅ Lulus |
| 14 | Daftar jasa & kategori ditampilkan dari database | ✅ Lulus |
| 15 | Fitur pencarian jasa berjalan | ✅ Lulus |
| 16 | Filter jasa per kategori berjalan | ✅ Lulus |
| 17 | Statistik platform (jumlah jasa, penjual, pesanan selesai) dinamis | ✅ Lulus |
| 18 | Ulasan terbaru ditampilkan di beranda | ✅ Lulus |

---

### 🛒 Pembeli (Buyer)

| No | Skenario Uji | Status |
|---|---|---|
| 19 | Lihat detail jasa & rating penjual | ✅ Lulus |
| 20 | Tambah jasa ke keranjang → toast popup muncul | ✅ Lulus |
| 21 | Update & hapus item di keranjang | ✅ Lulus |
| 22 | Checkout dengan memilih tanggal & alamat | ✅ Lulus |
| 23 | Checkout COD → invoice langsung dibuat | ✅ Lulus |
| 24 | Checkout transfer → diarahkan upload bukti | ✅ Lulus |
| 25 | Upload bukti pembayaran (JPG/PNG) | ✅ Lulus |
| 26 | Lihat daftar pesanan & progress tracker | ✅ Lulus |
| 27 | Lihat & unduh invoice setelah pembayaran verified | ✅ Lulus |
| 28 | Submit review & rating setelah pesanan selesai | ✅ Lulus |
| 29 | Review kedua pada pesanan yang sama → dicegah | ✅ Lulus |
| 30 | Notifikasi masuk dan dapat ditandai dibaca | ✅ Lulus |

---

### 🛠️ Penjual (Seller/Provider)

| No | Skenario Uji | Status |
|---|---|---|
| 31 | Seller belum diverifikasi → tidak bisa akses dashboard | ✅ Lulus |
| 32 | Tambah jasa baru dengan upload foto | ✅ Lulus |
| 33 | Edit jasa yang sudah ada | ✅ Lulus |
| 34 | Nonaktifkan / aktifkan listing jasa | ✅ Lulus |
| 35 | Hapus jasa | ✅ Lulus |
| 36 | Atur jadwal ketersediaan (hari & jam) | ✅ Lulus |
| 37 | Lihat daftar pesanan masuk | ✅ Lulus |
| 38 | Update status pesanan (accepted → in_progress → completed) | ✅ Lulus |
| 39 | Dashboard statistik: total order, pendapatan, rating | ✅ Lulus |
| 40 | Grafik pendapatan bulanan (berdasarkan service_date) | ✅ Lulus |

---

### 👨‍💼 Admin

| No | Skenario Uji | Status |
|---|---|---|
| 41 | Verifikasi pendaftaran penjual (seller) | ✅ Lulus |
| 42 | Tolak pendaftaran penjual (seller) | ✅ Lulus |
| 43 | Lihat & kelola semua pengguna | ✅ Lulus |
| 44 | Suspend & aktifkan kembali pengguna | ✅ Lulus |
| 45 | Hapus akun pengguna | ✅ Lulus |
| 46 | Tambah, edit, hapus kategori jasa | ✅ Lulus |
| 47 | Verifikasi pembayaran (konfirmasi/tolak dengan alasan) | ✅ Lulus |
| 48 | Override status pesanan | ✅ Lulus |
| 49 | Lihat laporan pendapatan & jasa terlaris | ✅ Lulus |
| 50 | Export laporan ke CSV | ✅ Lulus |
| 51 | Simpan System Settings | ✅ Lulus |

---

### ⚙️ Otomasi & Notifikasi

| No | Skenario Uji | Status |
|---|---|---|
| 52 | Invoice HTML dibuat otomatis saat pembayaran diverifikasi | ✅ Lulus |
| 53 | Invoice dibuat otomatis saat COD checkout | ✅ Lulus |
| 54 | Notifikasi ke buyer saat checkout berhasil | ✅ Lulus |
| 55 | Notifikasi ke seller saat pesanan baru masuk | ✅ Lulus |
| 56 | Notifikasi ke buyer saat pembayaran diverifikasi | ✅ Lulus |
| 57 | Notifikasi ke buyer saat status pesanan diubah seller | ✅ Lulus |
| 58 | Notifikasi ke seller saat akun diverifikasi admin | ✅ Lulus |
| 59 | Toast popup berhasil muncul di setiap aksi form | ✅ Lulus |

---

### 📱 UI & Responsivitas

| No | Skenario Uji | Status |
|---|---|---|
| 60 | Tampilan desktop (≥1200px) normal | ✅ Lulus |
| 61 | Tampilan tablet (768–1199px) — sidebar & card responsif | ✅ Lulus |
| 62 | Tampilan mobile (≤767px) — tombol aksi tidak tumpang tindih | ✅ Lulus |
| 63 | Bottom navigation bar tampil di mobile | ✅ Lulus |
| 64 | Tabel admin dapat di-scroll horizontal di mobile | ✅ Lulus |

---

## 🔑 Akun Demo

> Semua akun menggunakan password: **`password`**

| Role | Email |
|---|---|
| **Admin** | `rafli@bisabantu.admin.com` |
| **Seller** | `budi@bisabantu.com` |
| **Buyer** | `arpi@bisabantu.com` |

---

## 📝 Catatan & Temuan

- Semua fitur utama telah **lulus uji** dan siap untuk didemokan.
- Grafik laporan menggunakan **`service_date`** (tanggal pelaksanaan jasa) sebagai acuan, bukan tanggal pemesanan, sehingga lebih akurat secara bisnis.
- Path `require_once` di seluruh controller menggunakan **`__DIR__`** agar tetap berjalan setelah pemindahan ke folder `src/`.
- Toast notification bersifat **global** — setiap redirect berhasil/gagal akan otomatis menampilkan popup tanpa perlu kode tambahan di setiap view.

---

## ✅ Kesimpulan

**BisaBantu versi 1.0.0 dinyatakan SIAP DEMO** untuk semua alur bisnis utama:

1. Pembeli login → cari jasa → keranjang → checkout → upload pembayaran
2. Admin verifikasi pembayaran → sistem generate invoice & notifikasi
3. Penjual (Seller) terima pesanan → update status → selesai
4. Pembeli beri review → data masuk ke laporan analitik admin
