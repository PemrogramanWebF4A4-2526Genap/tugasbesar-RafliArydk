# BisaBantu — Platform Marketplace Jasa Lokal

> Temukan penyedia jasa terpercaya di sekitar Anda: bersih-bersih, servis elektronik, les privat, laundry, dan banyak lagi.

---

## 📖 Tentang Proyek

**BisaBantu** adalah aplikasi web marketplace jasa lokal yang dibangun menggunakan **PHP Native** dengan arsitektur **MVC (Model-View-Controller)**. Platform ini menghubungkan pihak-pihak dengan skema 4 Role User yang mengintegrasikan HTML, CSS, Bootstrap, PHP, dan MySQL.

Proyek ini merupakan tugas akhir semester (UAS) mata kuliah Pemrograman Web yang memenuhi standar aplikasi e-commerce jasa dengan fitur lengkap untuk Pembeli, Penjual, System Automation, dan Admin sesuai spesifikasi wajib.

---

## 🚀 Fitur Utama (Skema 4 Role User)

### 1. PEMBELI
- Register/Login dengan hashing bcrypt
- Browse & filter jasa berdasarkan kategori dan lokasi
- Tambah jasa ke keranjang belanja (session-based cart)
- Checkout dengan memilih tanggal & alamat pelaksanaan
- Upload bukti transfer pembayaran
- Lacak status pesanan secara real-time (progress tracker)
- Unduh invoice HTML setelah pembayaran terverifikasi
- Beri review & rating (dengan foto opsional) setelah jasa selesai dikerjakan
- Order tracking dan Payment confirmation

### 2. PENJUAL
- Register/Login (memerlukan Verifikasi Admin)
- Manage produk/jasa (CRUD: tambah, edit, hapus, nonaktifkan listing)
- Upload foto layanan
- Kelola jadwal ketersediaan (hari & jam operasional)
- Lihat & update status pesanan masuk
- Dashboard pendapatan dengan grafik bulanan (Line, Bar, Pie Chart)
- Lacak statistik: total pesanan, pendapatan, rating rata-rata
- Dashboard penjualan dan Konfirmasi pengiriman / pelaksanaan

### 3. SYSTEM OTOMASI
- Auto update stok (dalam bentuk ketersediaan jadwal/slot)
- Auto calculate ongkir (berdasarkan area jangkauan)
- Auto send email notification / in-app notification
- Auto update order status (berdasarkan aksi provider/buyer)
- Auto generate invoice setelah pembayaran dikonfirmasi

### 4. ADMIN
- Dashboard admin ringkasan platform (total user, pesanan, pendapatan)
- Manage user (Pembeli & Penjual: verifikasi, suspend, hapus)
- CRUD kategori jasa
- Verifikasi bukti pembayaran (konfirmasi/tolak)
- Override status pesanan
- Laporan & analitik: grafik pendapatan 6 bulan, jasa terlaris
- Export laporan ke CSV
- Report & Analytics - System settings: komisi, metode pembayaran

---

## 🏗️ Arsitektur & Teknologi

| Komponen | Teknologi |
|---|---|
| Backend | PHP 8.x Native (tanpa framework) |
| Arsitektur | MVC (Model-View-Controller) |
| Database | MySQL 8.x via PDO |
| Frontend | HTML5, CSS3 (Vanilla), JavaScript ES6 |
| UI Framework | Bootstrap 5.3 (CDN) |
| Charts | Chart.js 4.4 (CDN) |
| Icons | Bootstrap Icons, Font Awesome |
| Fonts | Google Fonts (Plus Jakarta Sans, Playfair Display) |
| Server | Apache (Laragon) |

---

## 📁 Struktur Direktori Sesuai Standar

```
UAS_INFO2425_RafliAryadika_202410715061/
├── src/                     # Seluruh source code aplikasi
│   ├── config/              # Konfigurasi koneksi database
│   │   └── database.php
│   ├── controllers/         # Logika bisnis & request handling
│   ├── models/              # Interaksi database (query PDO)
│   ├── views/               # Tampilan HTML/PHP
│   │   ├── admin/           # Halaman khusus Admin
│   │   ├── seller/          # Halaman khusus Penyedia
│   │   ├── buyer/           # Halaman khusus Pembeli
│   │   └── public/          # Halaman publik (home, invoice)
│   ├── assets/              # File statis CSS, JS, gambar, upload
│   │   ├── css/             
│   │   ├── js/              
│   │   └── images/        
│   └── uploads/             # File upload pengguna
├── database/
│   └── database.sql         # File dump database lengkap
├── docs/                    # Dokumentasi teknis
│   ├── README.md
│   ├── USER_MANUAL.md
│   └── DATABASE_SCHEMA.pdf
├── presentation/            # Materi presentasi UAS
│   └── PRESENTASI_UAS.pptx
└── TESTING_REPORT.pdf       # Hasil testing aplikasi
```

---

## ⚙️ Cara Instalasi

### Prasyarat
- **Laragon** (atau XAMPP/WAMP) dengan PHP 8.x dan MySQL 8.x
- Browser modern (Chrome/Firefox/Edge)

### Langkah Instalasi

1. **Clone / Letakkan Proyek**
   ```
   Letakkan folder proyek di: C:\laragon\www\UAS_INFO2425_RafliAryadika_202410715061\
   ```

2. **Import Database**
   - Buka phpMyAdmin di `http://localhost/phpmyadmin`
   - Buat database baru bernama `bisabantu`
   - Import file: `database/database.sql`

3. **Konfigurasi Database** (jika perlu)
   - Edit file: `src/config/database.php`
   - Sesuaikan `host`, `dbname`, `username`, `password`

4. **Akses Aplikasi**
   ```
   http://localhost/UAS_INFO2425_RafliAryadika_202410715061/
   ```

5. **Buat Folder Upload** (jika belum ada)
   ```
   src/assets/uploads/services/
   src/assets/uploads/payments/
   src/assets/uploads/profile/
   src/assets/uploads/reviews/
   src/assets/invoices/
   ```

---

## 🔐 Akun Demo

> Semua akun menggunakan password: **`password`**

| Role | Email | Keterangan |
|---|---|---|
| **Admin** | `rafli@bisabantu.admin.com` | Akses penuh ke seluruh sistem |
| **Provider** | `budi@bisabantu.com` | Penyedia jasa terverifikasi |
| **Provider** | `sienna@bisabantu.com` | Penyedia jasa terverifikasi |
| **Buyer** | `nasyla@bisabantu.com` | Pembeli aktif |
| **Buyer** | `arpi@bisabantu.com` | Pembeli dengan riwayat order |

---

## 🔒 Keamanan

| Mekanisme | Implementasi |
|---|---|
| Password Hashing | `password_hash()` + `password_verify()` bcrypt |
| SQL Injection Prevention | PDO Prepared Statements pada semua query |
| XSS Prevention | Helper `e()` — `htmlspecialchars(ENT_QUOTES, UTF-8)` |
| File Upload Validation | Validasi ekstensi & MIME type (JPG, PNG only) |
| Role-Based Access Control | Cek `$_SESSION['user']['role']` di setiap controller |
| Session Management | `session_start()` di setiap entry point |

---

## 📊 Alur Transaksi

```
[Buyer] → Browse & Pilih Jasa → Tambah Keranjang → Checkout
    ↓
[Sistem] → Buat Order (status: waiting_payment) → Notifikasi ke Provider & Buyer
    ↓
[Buyer] → Upload Bukti Pembayaran
    ↓
[Admin] → Verifikasi Pembayaran (Konfirmasi/Tolak)
    ↓
[Sistem] → Order status: paid → Generate Invoice → Notifikasi ke Buyer
    ↓
[Provider] → Terima Pesanan → Update: accepted → in_progress → completed
    ↓
[Buyer] → Terima Jasa → Beri Review & Rating → Lihat Invoice
```

---

## 👨‍💻 Pengembang

| Info | Detail |
|---|---|
| **Nama** | Rafli Aryadika |
| **NIM** | 202410715061 |
| **Mata Kuliah** | Pemrograman Web (INFO2425) |
| **Semester** | Genap 2025/2026 |
| **Dosen** | — |

---

*BisaBantu — Jasa Terpercaya di Sekitarmu 🏠🔧📚*
