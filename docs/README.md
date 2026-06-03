# BisaBantu - Lokal Service Marketplace

**BisaBantu** adalah platform berbasis web yang dirancang untuk mempertemukan penyedia jasa lokal (seperti bersih-bersih, servis AC, les privat, laundry, taman, dll) dengan pembeli yang membutuhkan layanan tersebut. Aplikasi ini dibangun menggunakan arsitektur **PHP Native** tanpa framework, dikombinasikan dengan **Bootstrap 5** dan basis data **MySQL**.

---

## 🚀 Fitur Utama Sistem

Platform ini mendukung tiga peran pengguna (Role) utama yang saling berintegrasi:

### 1. Peran Pembeli (Buyer)
* **Pencarian Jasa**: Mencari layanan berdasarkan kata kunci, lokasi, maupun kategori tertentu.
* **Keranjang & Transaksi**: Menambahkan beberapa unit jasa ke dalam keranjang belanja, menentukan alamat pengerjaan, tanggal kunjungan, dan metode pembayaran.
* **Manajemen Transaksi & Pembayaran**: Mengunggah bukti pembayaran untuk diverifikasi oleh admin.
* **Review & Rating**: Memberikan ulasan berserta rating (bintang 1-5) dan foto hasil pekerjaan setelah status pesanan dinyatakan selesai.

### 2. Peran Penyedia Jasa (Provider)
* **Pendaftaran Akun**: Mendaftar sebagai mitra penyedia jasa (menunggu verifikasi/approval dari admin).
* **Dasbor Kinerja**: Visualisasi ringkasan pesanan masuk, total pendapatan, ulasan pelanggan, serta grafik penjualan.
* **Manajemen Jasa (CRUD)**: Menambah, mengubah, mengaktifkan/menonaktifkan, dan menghapus penawaran jasa.
* **Manajemen Pesanan**: Menerima/menolak pesanan, serta memperbarui status pengerjaan secara berkala (*Pending* -> *Accepted* -> *In Progress* -> *Completed*).

### 3. Peran Admin (Administrator)
* **Verifikasi Penyedia**: Memvalidasi akun penyedia jasa baru sebelum mereka dapat mulai berjualan.
* **Kelola Kategori**: Melakukan CRUD kategori jasa yang tersedia di platform.
* **Kelola Semua Transaksi**: Memantau dan melakukan verifikasi bukti pembayaran dari pembeli untuk diteruskan ke penyedia jasa.
* **Laporan Pendapatan**: Melihat ringkasan pendapatan platform, grafik performa bulanan, dan opsi ekspor data.

---

## 🛠️ Stack Teknologi

* **Backend**: PHP 8.x (Native, berorientasi objek dengan PDO untuk keamanan query database)
* **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript (termasuk AJAX untuk interaksi dinamis)
* **Database**: MySQL 8.x / MariaDB 10.x
* **Keamanan**: Hash Password dengan Bcrypt (`password_hash`), Prepared Statements untuk mencegah SQL Injection, dan sanitasi input terhadap ancaman XSS.

---

## 📂 Struktur Direktori Proyek

```text
BisaBantu/
├── assets/                  # File statis (CSS, JS, Gambar Layanan, Bukti Bayar)
│   ├── css/
│   ├── js/
│   └── uploads/
│       ├── services/        # Foto layanan/jasa
│       ├── payments/        # Bukti transfer pembayaran
│       └── reviews/         # Foto ulasan hasil kerja
├── config/                  # Konfigurasi aplikasi
│   ├── database.php         # Koneksi PDO ke database MySQL
│   └── database.example.php # Contoh konfigurasi database
├── controllers/             # Logika pengendali alur request (Auth, Cart, Payment, dsb.)
├── database/                # Berkas SQL skema & dummy data awal
│   └── bisabantu.sql
├── docs/                    # Dokumentasi lengkap proyek
│   ├── DATABASE_SCHEMA.md
│   ├── USER_MANUAL.md
│   └── README.md
├── helpers/                 # Fungsi bantuan (Autentikasi, sanitasi, optimasi)
├── models/                  # Logika interaksi tabel database (User, Service, Order, dll)
├── views/                   # File tampilan UI (termasuk layout header & footer)
└── index.php                # Router utama dan gerbang masuk aplikasi
```

---

## 📥 Panduan Instalasi & Konfigurasi

### Prasyarat System
1. Web Server lokal seperti **Laragon** (sangat direkomendasikan) atau **XAMPP** dengan PHP versi minimal **8.0**.
2. Database Server **MySQL** atau **MariaDB**.

### Langkah-langkah Memulai
1. **Ekstrak/Salin Folder Proyek**
   Tempatkan folder `BisaBantu` ke dalam root direktori server web Anda:
   * **Laragon**: `C:\laragon\www\BisaBantu`
   * **XAMPP**: `C:\xampp\htdocs\BisaBantu`

2. **Impor Database**
   * Masuk ke **phpMyAdmin** (`http://localhost/phpmyadmin`) atau pengelola database favorit Anda (HeidiSQL, DBeaver, dll).
   * Buat database baru bernama `bisabantu`.
   * Impor file `BisaBantu/database/bisabantu.sql` ke dalam database baru tersebut.

3. **Konfigurasi Aplikasi**
   * Masuk ke folder `BisaBantu/config/`.
   * Salin berkas `database.example.php` dan ubah namanya menjadi `database.php`.
   * Buka berkas `database.php` menggunakan teks editor dan sesuaikan kredensial koneksi database Anda (host, database name, username, password).

4. **Menjalankan Aplikasi**
   Buka peramban web (browser) Anda dan akses URL berikut:
   * `http://localhost/BisaBantu/index.php` atau `http://bisabantu.test` (jika menggunakan Laragon virtual host).

---

## 👥 Akun Uji Coba (Demo Accounts)

Untuk mempermudah pengujian alur kerja sistem, gunakan akun berikut yang telah terdaftar dalam database bawaan:

| Role | Email | Password | Status Awal |
|---|---|---|---|
| **Admin** | `admin@bisabantu.com` | `password` | Aktif |
| **Penyedia Jasa** | `budi@bisabantu.com` | `password` | Terverifikasi / Aktif |
| **Penyedia Jasa** | `sari@bisabantu.com` | `password` | Pending Verifikasi |
| **Pembeli** | `rafli@bisabantu.com` | `password` | Aktif |
| **Pembeli** | `nasyla@bisabantu.com` | `password` | Aktif |

---

## 📜 Lisensi & Pengembang

Proyek ini dikembangkan sebagai bagian dari memenuhi Tugas Besar Pemrograman Web (UAS INFO 24/25).
* **Pengembang**: Rafli Aryadika (202410715061)
* **Lisensi**: MIT License
