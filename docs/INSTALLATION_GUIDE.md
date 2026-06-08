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
   database/bisabantu.sql
   ```

4. Cek konfigurasi koneksi database di:

   ```text
   config/database.php
   ```

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
