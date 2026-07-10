# Installation Guide

Panduan singkat menjalankan BisaBantu di lingkungan lokal.

## Kebutuhan

- PHP 8.1 atau lebih baru
- MySQL atau MariaDB
- Web server lokal seperti Laragon, XAMPP, atau Apache
- Ekstensi PHP PDO MySQL aktif

## Instalasi

1. Salin folder project ke direktori web server.
   Contoh Laragon:

   ```text
   C:\laragon\www\UAS_INFO2425_RafliAryadika_202410715061\BisaBantu
   ```

2. Buat database:

   ```sql
   CREATE DATABASE bisabantu;
   ```

3. Import file database:

   ```text
   database/database.sql
   ```

   File ini otomatis diperbarui setiap ada data baru lewat aplikasi (register, tambah/edit jasa, dll.).
   Untuk sinkron manual dari MySQL ke file SQL:

   ```bash
   php database/sync_dump.php
   ```

   Atau buka sebagai admin: `index.php?page=sync_dump`

4. Cek konfigurasi koneksi database di:

   ```text
   config/database.php
   ```

   Untuk hosting, sesuaikan `BISABANTU_DB_HOST`, `BISABANTU_DB_PORT`, `BISABANTU_DB_NAME`, `BISABANTU_DB_USER`, dan `BISABANTU_DB_PASS` jika panel hosting menyediakan environment variable. Jika tidak, ubah nilai default di file tersebut dengan kredensial database dari hosting.

   Jika error yang muncul berbunyi `No such file or directory`, biasanya `localhost` sedang memakai socket. Gunakan host TCP seperti `127.0.0.1` atau host database dari panel hosting.

5. Jalankan Apache dan MySQL, lalu buka:

   ```text
   http://localhost/UAS_INFO2425_RafliAryadika_202410715061/BisaBantu/
   ```

## Akun Demo

Semua akun demo memakai password:

```text
password
```

Lihat daftar akun terbaru di `README.md` atau `TESTING_REPORT.md`.

## Catatan Upload

Folder upload berada di:

```text
assets/uploads/
```

Pastikan folder tersebut dapat ditulis oleh web server. File gambar dibatasi JPG/PNG dan maksimal 2MB.
