# Database Schema – BisaBantu

**Database name:** `bisabantu`  
**Engine:** InnoDB (untuk foreign key)  
**Charset:** utf8mb4_general_ci

## ERD (Entity Relationship Diagram)
```text
[users] 1───* [services] *───1 [categories]
  │               │
  │               │ 1
  │               │
  │               *
  │         [order_items]
  │               │
  │               │ *
  │               │ 1
  │         [orders] 1───* [payments]
  │               │  │
  │               │  │ 1
  │               │  │
  │             * │  │
  │         [reviews] ─────────────┘
  │
  └───* [notifications]

        [invoices]
```

## Daftar Tabel (10 tabel)

### 1. users
Menyimpan semua pengguna (pembeli, penyedia jasa, admin).

| Kolom          | Tipe                     | Keterangan                                 |
|----------------|--------------------------|---------------------------------------------|
| id             | INT(11) AUTO_INCREMENT   | PRIMARY KEY                                 |
| name           | VARCHAR(100) NOT NULL    | Nama lengkap                                |
| email          | VARCHAR(100) NOT NULL    | UNIQUE, untuk login                         |
| password       | VARCHAR(255) NOT NULL    | Hash bcrypt                                 |
| role           | ENUM('buyer','provider','admin') NOT NULL | Role pengguna             |
| is_verified    | TINYINT(1) DEFAULT 0     | Khusus provider: 1 jika sudah diverifikasi admin |
| phone          | VARCHAR(20)              | Nomor telepon                               |
| address        | TEXT                     | Alamat lengkap                              |
| remember_token | VARCHAR(255) NULL        | Untuk fitur "remember me"                   |
| created_at     | TIMESTAMP DEFAULT CURRENT_TIMESTAMP |                                      |
| updated_at     | TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | |

**Index:** `email` (UNIQUE), `role`, `is_verified`

---

### 2. categories
Kategori jasa (misal: Bersih-bersih, Perbaikan, Les Privat).

| Kolom       | Tipe                     | Keterangan        |
|-------------|--------------------------|-------------------|
| id          | INT(11) AUTO_INCREMENT   | PRIMARY KEY       |
| name        | VARCHAR(50) NOT NULL     | Nama kategori     |
| description | TEXT                     | Deskripsi opsional |
| created_at  | TIMESTAMP DEFAULT CURRENT_TIMESTAMP | |

---

### 3. services
Jasa yang ditawarkan oleh penyedia.

| Kolom               | Tipe                       | Keterangan                                        |
|---------------------|----------------------------|---------------------------------------------------|
| id                  | INT(11) AUTO_INCREMENT     | PRIMARY KEY                                       |
| provider_id         | INT(11) NOT NULL           | FOREIGN KEY ke `users.id` (role provider)         |
| category_id         | INT(11) NOT NULL           | FOREIGN KEY ke `categories.id`                    |
| title               | VARCHAR(200) NOT NULL      | Judul jasa                                        |
| description         | TEXT NOT NULL              | Deskripsi lengkap                                 |
| price               | DECIMAL(12,2) NOT NULL     | Harga per unit                                    |
| price_unit          | VARCHAR(20) DEFAULT 'per unit' | Satuan: per jam, per kg, per item, dll       |
| estimated_duration  | VARCHAR(50)                | Estimasi waktu pengerjaan (misal: "2 jam")         |
| location            | VARCHAR(255) NOT NULL      | Wilayah layanan (kota/kecamatan)                  |
| image               | VARCHAR(255)               | Nama file gambar (upload ke `assets/uploads/services/`) |
| is_active           | TINYINT(1) DEFAULT 1       | 1 = aktif (dapat dipesan), 0 = nonaktif sementara |
| created_at          | TIMESTAMP DEFAULT CURRENT_TIMESTAMP |                                             |
| updated_at          | TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE |                                      |

**Foreign Key:** `provider_id` → `users(id)` ON DELETE CASCADE  
**Foreign Key:** `category_id` → `categories(id)` ON DELETE RESTRICT  
**Index:** `provider_id`, `category_id`, `location`

---

### 4. orders
Pesanan yang dibuat oleh pembeli.

| Kolom            | Tipe                        | Keterangan                                                      |
|------------------|-----------------------------|-----------------------------------------------------------------|
| id               | INT(11) AUTO_INCREMENT      | PRIMARY KEY                                                     |
| buyer_id         | INT(11) NOT NULL            | FOREIGN KEY ke `users.id` (role buyer)                          |
| provider_id      | INT(11) NOT NULL            | FOREIGN KEY ke `users.id` (role provider)                       |
| order_number     | VARCHAR(20) NOT NULL UNIQUE | Nomor pesanan unik (format: ORD/yyyyMMdd/xxxx)                  |
| total_price      | DECIMAL(12,2) NOT NULL      | Total harga (price * quantity, belum termasuk biaya admin)      |
| quantity         | INT(11) NOT NULL DEFAULT 1  | Jumlah unit jasa yang dipesan                                   |
| service_date     | DATE NOT NULL               | Tanggal pelaksanaan jasa (diisi pembeli)                        |
| service_address  | TEXT NOT NULL               | Alamat tempat jasa dikerjakan                                   |
| status           | ENUM('pending','waiting_payment','paid','accepted','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending' | |
| notes            | TEXT                        | Catatan dari pembeli untuk penyedia jasa                        |
| created_at       | TIMESTAMP DEFAULT CURRENT_TIMESTAMP |                                                          |
| updated_at       | TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE |                                            |

**Foreign Keys:**  
- `buyer_id` → `users(id)` ON DELETE RESTRICT  
- `provider_id` → `users(id)` ON DELETE RESTRICT  

**Index:** `order_number` (UNIQUE), `buyer_id`, `provider_id`, `status`, `service_date`

---

### 5. order_items
Detail item pesanan (mendukung satu pesanan berisi beberapa jasa berbeda).  
Meskipun defaultnya satu pesanan bisa untuk satu jasa, struktur ini memungkinkan ekspansi.

| Kolom          | Tipe                 | Keterangan                                    |
|----------------|----------------------|-----------------------------------------------|
| id             | INT(11) AUTO_INCREMENT | PRIMARY KEY                                   |
| order_id       | INT(11) NOT NULL       | FOREIGN KEY ke `orders.id` ON DELETE CASCADE |
| service_id     | INT(11) NOT NULL       | FOREIGN KEY ke `services.id`                 |
| quantity       | INT(11) NOT NULL       | Jumlah unit untuk jasa ini pada pesanan       |
| price_per_unit | DECIMAL(12,2) NOT NULL | Harga per unit saat checkout (snapshot)       |

**Foreign Key:** `order_id` → `orders(id)` ON DELETE CASCADE  
**Foreign Key:** `service_id` → `services(id)` ON DELETE RESTRICT

---

### 6. payments
Data pembayaran untuk setiap pesanan.

| Kolom        | Tipe                           | Keterangan                                        |
|--------------|--------------------------------|---------------------------------------------------|
| id           | INT(11) AUTO_INCREMENT         | PRIMARY KEY                                       |
| order_id     | INT(11) NOT NULL               | FOREIGN KEY ke `orders.id`                        |
| method       | ENUM('bank_transfer','cash') NOT NULL | Metode pembayaran                           |
| proof_image  | VARCHAR(255)                   | Nama file bukti transfer (jika method bank_transfer) |
| status       | ENUM('pending','verified','rejected') DEFAULT 'pending' | |
| verified_at  | DATETIME NULL                  | Waktu verifikasi oleh admin                       |
| notes        | TEXT NULL                      | Catatan admin (misal alasan ditolak)              |
| created_at   | TIMESTAMP DEFAULT CURRENT_TIMESTAMP |                                            |

**Foreign Key:** `order_id` → `orders(id)` ON DELETE CASCADE  
**Index:** `order_id`, `status`

---

### 7. reviews
Rating dan komentar dari pembeli untuk jasa yang sudah selesai.

| Kolom       | Tipe                 | Keterangan                                           |
|-------------|----------------------|------------------------------------------------------|
| id          | INT(11) AUTO_INCREMENT | PRIMARY KEY                                          |
| service_id  | INT(11) NOT NULL       | FOREIGN KEY ke `services.id`                         |
| order_id    | INT(11) NOT NULL       | FOREIGN KEY ke `orders.id` (memastikan review hanya dari pemesan) |
| user_id     | INT(11) NOT NULL       | FOREIGN KEY ke `users.id` (pembeli yang memberi review) |
| rating      | TINYINT(1) NOT NULL    | Nilai 1–5                                            |
| comment     | TEXT                   | Komentar teks                                        |
| image       | VARCHAR(255)           | Foto hasil pekerjaan (upload ke `assets/uploads/reviews/`) |
| created_at  | TIMESTAMP DEFAULT CURRENT_TIMESTAMP |                                            |

**Foreign Keys:**  
- `service_id` → `services(id)` ON DELETE CASCADE  
- `order_id` → `orders(id)` ON DELETE CASCADE  
- `user_id` → `users(id)` ON DELETE CASCADE  

**Unique Constraint:** `UNIQUE(order_id)` – satu pesanan hanya boleh satu review.

---

### 8. notifications
Notifikasi in-app untuk semua role.

| Kolom      | Tipe                     | Keterangan                         |
|------------|--------------------------|------------------------------------|
| id         | INT(11) AUTO_INCREMENT   | PRIMARY KEY                        |
| user_id    | INT(11) NOT NULL         | FOREIGN KEY ke `users.id`          |
| title      | VARCHAR(100) NOT NULL    | Judul notifikasi                   |
| message    | TEXT NOT NULL            | Isi pesan                          |
| is_read    | TINYINT(1) DEFAULT 0     | 0 = belum dibaca, 1 = sudah        |
| created_at | TIMESTAMP DEFAULT CURRENT_TIMESTAMP |                            |

**Foreign Key:** `user_id` → `users(id)` ON DELETE CASCADE  
**Index:** `user_id`, `is_read`, `created_at`

---

### 9. invoices
Invoice otomatis yang digenerate saat pembayaran diverifikasi.

| Kolom          | Tipe                 | Keterangan                                        |
|----------------|----------------------|---------------------------------------------------|
| id             | INT(11) AUTO_INCREMENT | PRIMARY KEY                                       |
| order_id       | INT(11) NOT NULL       | FOREIGN KEY ke `orders.id`                        |
| invoice_number | VARCHAR(20) NOT NULL UNIQUE | Nomor invoice (misal INV/20250515/0001)      |
| pdf_path       | VARCHAR(255) NOT NULL  | Path file PDF (relatif terhadap root proyek)       |
| generated_at   | DATETIME NOT NULL      | Waktu generate                                    |

**Foreign Key:** `order_id` → `orders(id)` ON DELETE CASCADE

---

### 10. provider_schedules (opsional, untuk fitur jadwal)
Menyimpan ketersediaan waktu penyedia jasa.

| Kolom         | Type                     | Keterangan                          |
|---------------|--------------------------|-------------------------------------|
| id            | INT(11) AUTO_INCREMENT   | PRIMARY KEY                         |
| provider_id   | INT(11) NOT NULL         | FOREIGN KEY ke `users.id`           |
| day_of_week   | TINYINT(1) NOT NULL      | 0=Senin, 1=Selasa, ..., 6=Minggu    |
| start_time    | TIME NOT NULL            | Jam mulai (misal 09:00:00)          |
| end_time      | TIME NOT NULL            | Jam selesai (misal 17:00:00)        |
| is_available  | TINYINT(1) DEFAULT 1     | Apakah tersedia di slot tersebut    |

**Foreign Key:** `provider_id` → `users(id)` ON DELETE CASCADE

> Tabel ini opsional, tidak wajib untuk memenuhi jumlah minimal 8 tabel.

---

## Contoh Data Dummy (seed)

File `database/bisabantu.sql` menyertakan data awal:
- 1 admin (`admin@bisabantu.com` / `password`)
- 2 penyedia jasa (sudah diverifikasi)
- 3 pembeli
- 5 kategori
- 10 jasa
- Beberapa pesanan dengan berbagai status
