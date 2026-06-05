# Presentasi UAS BisaBantu

## Slide 1 - Judul

BisaBantu: Local Service Marketplace

## Slide 2 - Latar Belakang

Banyak pengguna membutuhkan jasa lokal terpercaya, tetapi pemesanan, pembayaran, dan pelacakan status masih tersebar. BisaBantu menyediakan satu platform untuk mencari jasa, memesan, membayar, melacak, dan memberi ulasan.

## Slide 3 - Role User

- Pembeli: cari jasa, cart, checkout, upload pembayaran, tracking, review.
- Penyedia: verifikasi admin, CRUD jasa, kelola order, dashboard pendapatan.
- Admin: kelola user, kategori, order, pembayaran, laporan, settings.
- System Automation: invoice, notifikasi, email, status, fee layanan.

## Slide 4 - Struktur MVC

- `config/`
- `controllers/`
- `models/`
- `views/`
- `assets/`
- `database/`
- `docs/`
- `uploads/`

## Slide 5 - Database

Tabel utama: `users`, `categories`, `services`, `orders`, `order_items`, `payments`, `reviews`, `notifications`, `invoices`, `provider_schedules`.

## Slide 6 - Alur Pembeli

Register/login, browse jasa, tambah ke keranjang, checkout, upload bukti pembayaran, lihat status pesanan, lihat invoice, beri review.

## Slide 7 - Alur Penyedia

Register sebagai penyedia, menunggu verifikasi admin, membuat jasa, menerima pesanan, update status dari dibayar sampai selesai.

## Slide 8 - Alur Admin

Verifikasi penyedia, kelola user, kelola kategori, verifikasi pembayaran, override status order, melihat laporan, mengatur sistem.

## Slide 9 - Automation

- Invoice otomatis saat pembayaran diverifikasi.
- Notifikasi otomatis saat order dibuat, pembayaran diverifikasi, dan status berubah.
- Status order berjalan dari `waiting_payment`, `paid`, `accepted`, `in_progress`, sampai `completed`.
- Fee platform dihitung otomatis dari persentase komisi.

## Slide 10 - Security

- Password hashing dengan bcrypt.
- SQL injection prevention dengan PDO prepared statement.
- XSS prevention dengan `htmlspecialchars()`.
- Validasi upload bukti pembayaran.

## Slide 11 - Demo

Demo end-to-end: buyer checkout, admin verify payment, invoice otomatis, provider update status, buyer review.

## Slide 12 - Penutup

BisaBantu memenuhi konsep marketplace jasa lokal berbasis PHP Native MVC dengan role lengkap, automation, keamanan dasar, dan dokumentasi pendukung.
