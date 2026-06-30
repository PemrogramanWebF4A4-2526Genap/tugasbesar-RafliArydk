# 🎤 Panduan Presentasi & Pitching — BisaBantu

> Gunakan panduan ini sebagai **script** saat presentasi UAS. Sesuaikan dengan gaya bicara kamu sendiri!

---

## 🎯 Struktur Presentasi (10–15 Menit)

| Segmen                 | Durasi  | Isi                                 |
| ---------------------- | ------- | ----------------------------------- |
| **Opening / Hook**     | 1 menit | Buka dengan pertanyaan menarik      |
| **Masalah**            | 2 menit | Jelaskan masalah yang dipecahkan    |
| **Solusi (BisaBantu)** | 2 menit | Kenalkan produk & value proposition |
| **Fitur & Arsitektur** | 3 menit | Jelaskan teknis secara ringkas      |
| **Demo Langsung**      | 4 menit | Tunjukkan alur utama di browser     |
| **Penutup & Q&A**      | 2 menit | Kesimpulan + siap jawab pertanyaan  |

---

## 💬 Script Per Segmen

---

### 1. OPENING — HOOK (Buka Kuat!)

> **"Bayangkan AC rumah Anda mati di tengah terik. Anda butuh teknisi sekarang — tapi bingung cari yang terpercaya, nggak tahu harganya, dan nggak ada jaminan kualitasnya. Itu pengalaman hampir semua orang Indonesia saat butuh jasa lokal."**

_Lanjutkan:_

> "Itulah masalah yang ingin saya selesaikan dengan proyek ini. Nama saya Rafli Aryadika, NIM 202410715061. Izinkan saya memperkenalkan **BisaBantu**."

---

### 2. MASALAH — Problem Statement

> "Di Indonesia, jasa lokal seperti tukang bersih, servis elektronik, les privat, dan laundry masih sangat tersebar. Pengguna mencari lewat mulut ke mulut, grup WhatsApp, atau media sosial — tanpa transparansi harga, tanpa tracking, dan tanpa jaminan."

**3 Masalah Utama yang Diselesaikan:**

1. 🔍 **Sulit menemukan** penyedia jasa terpercaya di sekitar lokasi
2. 💸 **Tidak ada transparansi** harga dan proses pembayaran
3. 📊 **Tidak ada tracking** status pengerjaan secara real-time

---

### 3. SOLUSI — Kenalkan BisaBantu

> "BisaBantu adalah **marketplace jasa lokal** berbasis web yang menghubungkan tiga pihak: Pembeli, Penyedia Jasa, dan Admin Platform — dalam satu ekosistem yang terintegrasi."

**Value Proposition:**

- ✅ Penyedia **diverifikasi** oleh admin sebelum bisa listing jasa
- 💳 Pembayaran **transparan** dengan upload bukti & verifikasi admin
- 📦 **Tracking pesanan** real-time dari checkout hingga selesai
- ⭐ **Review & rating** setelah jasa selesai dikerjakan
- 🧾 **Invoice otomatis** langsung bisa diunduh setelah bayar

---

### 4. PENJELASAN TEKNIS — Arsitektur & Database

> "Secara teknis, BisaBantu dibangun dengan **PHP Native** menggunakan pola arsitektur **MVC** — tanpa framework PHP apapun. Ini membuktikan saya memahami fundamental pemrograman web, bukan hanya menggunakan library."

**Poin Teknis Utama:**

| Aspek          | Implementasi                                                   |
| -------------- | -------------------------------------------------------------- |
| Arsitektur     | MVC — Model, View, Controller                                  |
| Backend        | PHP 8.x Native                                                 |
| Database       | MySQL dengan **10 tabel** berelasi (FK + InnoDB)               |
| Keamanan       | bcrypt, PDO Prepared Statement, `htmlspecialchars()`           |
| Access Control | Role-Based: buyer / provider / admin                           |
| Otomasi        | Invoice & notifikasi dibuat otomatis oleh sistem               |
| Analitik       | Chart.js — grafik dinamis berdasarkan tanggal pelaksanaan jasa |
| UI             | Bootstrap 5 + CSS Custom — responsive mobile & tablet          |

> "Semua 10 tabel saling berelasi menggunakan foreign key — ini memastikan integritas data terjaga. Misalnya, jika sebuah pesanan dihapus, item dan invoice-nya ikut terhapus secara otomatis melalui `ON DELETE CASCADE`."

---

### 5. DEMO LANGSUNG — Alur yang Didemonstrasikan

Lakukan demo secara berurutan di browser:

#### 🟢 Demo 1 — Sisi Pembeli

1. Buka homepage → tunjukkan listing jasa & statistik platform (dinamis dari DB)
2. Login sebagai `arpi@bisabantu.com` (password: `password`)
3. Klik detail jasa → lihat rating & ulasan
4. Tambah ke keranjang → **tunjukkan toast popup "✅ Jasa berhasil ditambahkan"**
5. Checkout → pilih tanggal pelaksanaan & alamat
6. Upload bukti bayar → **toast "📤 Bukti pembayaran berhasil dikirim"**

#### 🔵 Demo 2 — Sisi Admin

7. Login sebagai `rafli@bisabantu.admin.com`
8. Verifikasi pembayaran → **toast "✅ Pembayaran berhasil diproses"**
9. Tunjukkan invoice yang dibuat otomatis oleh sistem
10. Buka halaman **Report & Analytics** → grafik pendapatan & jasa terlaris

#### 🟠 Demo 3 — Sisi Penyedia

11. Login sebagai `budi@bisabantu.com`
12. Update status: `accepted` → `in_progress` → `completed`
13. Tunjukkan dashboard statistik + grafik pendapatan bulanan

---

### 6. PENUTUP — Closing Statement

> "BisaBantu bukan sekadar tugas kuliah. Ini adalah prototype nyata dari solusi yang bisa membantu ribuan UMKM jasa lokal Indonesia untuk go digital — dengan fitur lengkap, keamanan yang solid, dan arsitektur yang mudah dikembangkan."

> "Saya membangun ini dari nol menggunakan PHP Native, memahami setiap baris kodenya, dan siap menjelaskan keputusan teknis apapun yang ada di dalamnya."

> "Terima kasih. Saya siap untuk demo tambahan atau menjawab pertanyaan."

---

## ❓ Persiapan Jawaban Q&A

### Q: Mengapa PHP Native, bukan Laravel/CodeIgniter?

> **A:** "Saya sengaja memilih PHP Native untuk membuktikan bahwa saya memahami fundamental web dari dasar — cara kerja HTTP, session, routing, dan OOP tanpa 'magic' framework. Ini juga membuat proyek lebih ringan dan semua alurnya bisa saya jelaskan baris per baris."

### Q: Bagaimana sistem keamanannya?

> **A:** "Tiga lapis: **(1)** bcrypt untuk hashing password, **(2)** PDO Prepared Statement di semua query — mencegah SQL Injection, dan **(3)** helper `e()` berbasis `htmlspecialchars()` untuk semua output HTML — mencegah XSS. Ditambah validasi role di setiap controller."

### Q: Bagaimana cara kerja otomasi invoice?

> **A:** "Saat admin mengklik 'Konfirmasi Pembayaran', fungsi `after_payment_verified()` di `helpers/automation.php` dipanggil. Fungsi ini mengambil data order & items dari database, lalu membuat file HTML invoice dan menyimpan path-nya ke tabel `invoices`. Semua terjadi dalam satu transaksi database."

### Q: Kenapa grafik pakai service_date, bukan created_at?

> **A:** "Secara bisnis, yang relevan adalah kapan jasa dikerjakan, bukan kapan dipesan. Jika pesanan dibuat bulan Februari tapi dikerjakan Maret, pendapatan itu seharusnya masuk laporan bulan Maret — mencerminkan realita bisnis yang sebenarnya."

### Q: Bagaimana jika satu checkout punya jasa dari dua penyedia berbeda?

> **A:** "Sistem mengelompokkan cart items berdasarkan `provider_id`. Jika ada dua penyedia, sistem membuat dua order terpisah dalam satu transaksi database menggunakan `beginTransaction()` dan `commit()`. Masing-masing order punya nomor unik dan notifikasi tersendiri."

### Q: Apakah sudah diuji?

> **A:** "Ya. Saya memiliki Testing Report dengan **64 skenario uji** yang semuanya lulus — mencakup autentikasi, CRUD, alur transaksi end-to-end, keamanan, otomasi, dan responsivitas di mobile."

---

## 💡 Tips Presentasi

- 🗣️ Bicara **percaya diri** — kamu yang paling tahu proyekmu
- 👁️ **Kontak mata** dengan dosen, jangan hanya baca catatan
- 🖱️ **Demo langsung di browser** lebih meyakinkan dari slide statis
- ⏱️ **Kelola waktu** — demo 3 alur utama saja, jangan semua fitur
- 🧩 Jelaskan **"mengapa"** bukan hanya **"apa"** — dosen ingin tahu cara berpikirmu
- 🤝 Jika ditanya sesuatu yang tidak yakin: _"Ini keputusan teknis yang menarik, izinkan saya jelaskan pertimbangannya..."_

---

_Semangat presentasinya, Rafli! 🚀 BisaBantu siap bikin dosen kagum._
