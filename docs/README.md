# BisaBantu — Platform Marketplace Jasa Lokal

> Temukan penyedia jasa terpercaya di sekitar Anda: bersih-bersih, servis elektronik, les privat, laundry, dan banyak lagi.

---

## 📖 Tentang Proyek

**BisaBantu** adalah aplikasi web marketplace jasa lokal yang dibangun menggunakan **PHP Native** dengan arsitektur **MVC (Model-View-Controller)**. Platform ini menghubungkan tiga pihak utama: **Pembeli** yang membutuhkan jasa, **Penyedia Jasa** yang menawarkan layanan, dan **Administrator** yang mengelola seluruh platform.

Proyek ini merupakan tugas akhir semester (UAS) mata kuliah Pemrograman Web yang memenuhi standar aplikasi e-commerce jasa dengan fitur lengkap, termasuk sistem pembayaran, tracking pesanan, invoice otomatis, review, dan analitik bisnis real-time.

---

## 🚀 Fitur Utama

### 👤 Untuk Pembeli (Buyer)
- Registrasi & login dengan hashing bcrypt
- Browse & filter jasa berdasarkan kategori dan lokasi
- Tambah jasa ke keranjang belanja (session-based cart)
- Checkout dengan memilih tanggal & alamat pelaksanaan
- Upload bukti transfer pembayaran
- Lacak status pesanan secara real-time (progress tracker)
- Unduh invoice HTML setelah pembayaran terverifikasi
- Beri review & rating setelah jasa selesai dikerjakan
- Notifikasi otomatis setiap perubahan status

### 🛠️ Untuk Penyedia Jasa (Seller/Provider)
- Registrasi sebagai penyedia, menunggu verifikasi admin
- CRUD jasa (tambah, edit, hapus, nonaktifkan listing)
- Upload foto layanan
- Kelola jadwal ketersediaan (hari & jam operasional)
- Lihat & update status pesanan masuk
- Dashboard pendapatan dengan grafik bulanan (Line, Bar, Pie Chart)
- Lacak statistik: total pesanan, pendapatan, rating rata-rata

### 🔑 Untuk Admin
- Dashboard ringkasan platform (total user, pesanan, pendapatan)
- Verifikasi & tolak pendaftaran penyedia jasa baru
- Kelola semua pengguna (suspend/aktifkan/hapus)
- CRUD kategori jasa
- Verifikasi bukti pembayaran (konfirmasi/tolak)
- Override status pesanan
- Laporan & analitik: grafik pendapatan 6 bulan, jasa terlaris
- Export laporan ke CSV
- System Settings: komisi, metode pembayaran, notifikasi

### ⚙️ Otomasi Sistem
- Invoice HTML dibuat otomatis saat pembayaran diverifikasi
- Notifikasi dikirim otomatis pada setiap event penting
- Toast popup berhasil/gagal di setiap aksi pengguna
- Grafik report menggunakan **tanggal pelaksanaan jasa** (bukan tanggal pesan)

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

## 📁 Struktur Direktori

```
UAS_INFO2425_RafliAryadika_202410715061/
├── src/                     # Seluruh source code aplikasi
│   ├── config/              # Konfigurasi koneksi database
│   ├── controllers/         # Logika bisnis & request handling
│   ├── models/              # Interaksi database (query PDO)
│   ├── views/               # Tampilan HTML/PHP
│   │   ├── admin/           # Halaman khusus Admin
│   │   ├── buyer/           # Halaman khusus Pembeli
│   │   ├── seller/          # Halaman khusus Penyedia
│   │   ├── public/          # Halaman publik (home, invoice)
│   │   └── layout/          # Header, footer, sidebar
│   ├── helpers/             # Fungsi bantu (upload, format, dll)
│   └── assets/              # File statis CSS, JS, gambar, upload
│       ├── css/             # Stylesheet per role
│       ├── js/              # JavaScript modular
│       ├── uploads/         # File upload pengguna
│       └── invoices/        # Invoice HTML yang digenerate
├── database/
│   └── bisabantu.sql        # File dump database lengkap
├── docs/                    # Dokumentasi teknis
├── presentation/            # Materi presentasi UAS
├── index.php                # Entry point utama (front controller)
└── .htaccess               # URL routing Apache
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
   - Import file: `database/bisabantu.sql`

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
