# Testing Report BisaBantu

Tanggal uji: 2026-06-05

## Ringkasan

Project diuji untuk alur utama marketplace jasa lokal: autentikasi, role access, CRUD jasa, cart, checkout, upload pembayaran, verifikasi admin, tracking order, review, notifikasi, dan invoice otomatis.

## Checklist Fitur

| Area | Skenario | Status |
| --- | --- | --- |
| Auth | Register pembeli memakai `password_hash()` | Lulus |
| Auth | Login memakai `password_verify()` | Lulus |
| Security | Query login, register, order memakai PDO prepared statement | Lulus |
| Public | Landing marketplace dapat dilihat tanpa login | Lulus |
| Public | Homepage listing jasa dan kategori membaca data dari database | Lulus |
| Buyer | Browse jasa, cart, checkout, upload pembayaran | Lulus |
| Provider | CRUD jasa, upload gambar layanan, slot layanan, dan update status pesanan | Lulus |
| Provider | Provider belum terverifikasi tidak dapat mengakses dashboard/CRUD | Lulus |
| Admin | Verifikasi penyedia, kelola user, kategori, order, settings | Lulus |
| Admin | Export laporan order dan jasa terlaris ke CSV | Lulus |
| Automation | Invoice dibuat otomatis saat pembayaran diverifikasi | Lulus |
| Automation | Notifikasi otomatis saat checkout, pembayaran, dan status berubah | Lulus |
| Review | Pembeli dapat memberi review setelah order selesai | Lulus |

## Catatan Keamanan

- Password disimpan sebagai hash bcrypt.
- SQL menggunakan PDO prepared statement untuk input user.
- Output HTML memakai helper `e()` berbasis `htmlspecialchars()`.
- Upload bukti bayar dibatasi ke ekstensi `jpg`, `jpeg`, dan `png`.
- Folder upload tersedia untuk `assets/uploads/services`, `assets/uploads/payments`, dan `assets/invoices`.

## Akun Demo

Semua akun default memakai password `password`.

| Role | Email |
| --- | --- |
| Admin | Rafli@bisabantu.com |
| Penyedia | budi@bisabantu.com |
| Pembeli | arpi@bisabantu.co |

## Hasil Akhir

Project siap didemokan untuk alur:

1. Pembeli login, memilih jasa, checkout, lalu upload bukti pembayaran.
2. Admin memverifikasi pembayaran.
3. Sistem otomatis mengubah status, membuat invoice, dan mengirim notifikasi.
4. Penyedia menerima pesanan, mulai kerja, lalu menyelesaikan order.
5. Pembeli melihat invoice dan memberi review.
