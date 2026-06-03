# BisaBantu - Tugas Besar Pemrograman Web
**Platform untuk mencari dan memesan jasa lokal** (bersih-bersih, perbaikan, les privat, laundry, dll).  
Dibangun dengan **PHP Native** + **Bootstrap 5** + **MySQL**, mengimplementasikan 4 role user dan fitur otomatisasi.

## Fitur Utama
- **Pembeli**: Cari jasa, keranjang, checkout, upload pembayaran, tracking pesanan, review & rating.
- **Penyedia Jasa**: CRUD jasa, kelola pesanan, dashboard pendapatan.
- **Admin**: Verifikasi penyedia, kelola kategori, laporan, pengaturan sistem.
- **System Automation**: Auto update status pesanan, auto hitung biaya, auto email, auto generate invoice.

## Teknologi
| Komponen | Teknologi |
|----------|------------|
| Backend  | PHP 8.x (Native, PDO) |
| Frontend | HTML5, CSS3, Bootstrap 5, JavaScript (AJAX) |
| Database | MySQL 8.x |
| Keamanan | bcrypt, prepared statement, CSRF token, XSS protection |

## Instalasi

### Prasyarat
- XAMPP / Laragon / MAMP (PHP >= 8.0, MySQL)
- Web browser modern

### Langkah-langkah

1. **Clone / ekstrak proyek** ke dalam folder root server (misal `C:\xampp\htdocs\bisabantu` atau `C:\laragon\www\bisabantu`)

2. **Buat database**  
   - Buka phpMyAdmin: `http://localhost/phpmyadmin`  
   - Buat database baru: `bisabantu` (utf8mb4_general_ci)

3. **Import struktur dan data awal**  
   - Import file `database/bisabantu.sql` (tersedia di folder proyek)

4. **Konfigurasi koneksi database**  
   - Salin `config/database.example.php` menjadi `config/database.php`  
   - Sesuaikan parameter: host, dbname, username, password

5. **Set permissions folder uploads**  
   - Pastikan folder `assets/uploads/` dan subfoldernya dapat ditulis oleh server (chmod 755 atau 777)

6. **Akses aplikasi**  
   - Buka browser: `http://localhost/bisabantu/index.php`

## Akun Demo

| Role | Email | Password |
|--------------------------|--------------------------|--------------|
| Admin                    | admin@bisabantu.com      | password     |
| Penyedia (terverifikasi) | budi@bisabantu.com       | password     |
| Penyedia (pending)       | sari@bisabantu.com       | password     |
| Pembeli                  | andi@bisabantu.com       | password     |
| Pembeli                  | nita@bisabantu.com       | password     |

> **Catatan:** Penyedia jasa baru perlu diverifikasi oleh admin terlebih dahulu.

## Struktur Folder Penting
```text
bisabantu/
├── assets/ (CSS, JS, uploads)
├── config/ (database.php)
├── helpers/ (auth, security, automation)
├── models/ (interaksi database)
├── controllers/ (logika halaman)
├── views/ (template HTML + Bootstrap)
├── docs/ (dokumentasi proyek)
├── index.php (router utama)
└── database/ (file .sql)
```

## Cara Penggunaan Singkat

- **Pembeli**: Register → cari jasa → tambah ke keranjang → checkout → upload bukti → tracking → review.  
- **Penyedia**: Register → tunggu verifikasi admin → login → tambah jasa → kelola pesanan → ubah status.  
- **Admin**: Login → verifikasi penyedia → kelola kategori → lihat laporan.

## Pengembangan Lebih Lanjut

- Integrasi pembayaran online (Midtrans, Xendit)  
- Sistem notifikasi real-time (WebSocket)  
- Mobile responsive enhancement  
- Export laporan lebih detail (PDF, Excel)

## Kontak & Lisensi

Dibuat untuk memenuhi Tugas Besar Pemrograman Web.  
Lisensi: MIT (bebas digunakan untuk pembelajaran).
