-- ======================================================
-- Database: bisabantu
-- BisaBantu (Lokal Service Marketplace)
-- PHP Native + Bootstrap 5
-- ======================================================

CREATE DATABASE IF NOT EXISTS `bisabantu`
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `bisabantu`;

-- --------------------------------------------------------
-- Tabel 1: users
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('buyer','provider','admin') NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),

  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel 2: categories
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel 3: services
-- --------------------------------------------------------
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `price_unit` varchar(20) DEFAULT 'per unit',
  `estimated_duration` varchar(50) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `provider_id` (`provider_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `services_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `services_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel 4: orders
-- --------------------------------------------------------
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `service_date` date NOT NULL,
  `service_address` text NOT NULL,
  `status` enum('pending','waiting_payment','paid','accepted','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `buyer_id` (`buyer_id`),
  KEY `provider_id` (`provider_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel 5: order_items
-- --------------------------------------------------------
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_unit` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel 6: payments
-- --------------------------------------------------------
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `method` enum('bank_transfer','cash') NOT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `verified_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel 7: reviews
-- --------------------------------------------------------
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `service_id` (`service_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel 8: notifications
-- --------------------------------------------------------
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel 9: invoices
-- --------------------------------------------------------
CREATE TABLE `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `invoice_number` varchar(20) NOT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `generated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  UNIQUE KEY `order_id_unique` (`order_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel 10: provider_schedules (opsional)
-- --------------------------------------------------------
CREATE TABLE `provider_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `day_of_week` tinyint(1) NOT NULL COMMENT '0=Senin,1=Selasa,...,6=Minggu',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `provider_id` (`provider_id`),
  CONSTRAINT `provider_schedules_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ======================================================
-- DATA DUMMY (BisaBantu)
-- ======================================================

-- Admin
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_verified`, `phone`, `address`, `profile_photo`) VALUES
('Rafli Aryadika', 'Rafli@bisabantu.admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, '081234567890', 'Jakarta', NULL);

-- Penyedia jasa
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_verified`, `phone`, `address`, `profile_photo`) VALUES
('Budi Wijaya', 'budi@bisabantu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'provider', 1, '0811111111', 'Bandung', NULL),
('Sienna', 'sienna@bisabantu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'provider', 0, '0822222222', 'Jakarta Selatan', NULL);

-- Pembeli
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_verified`, `phone`, `address`, `profile_photo`) VALUES
('Arpi', 'arpi@bisabantu.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'buyer', 1, '0833333333', 'Bekasi', NULL),
('Nasyla Putri', 'nasyla@bisabantu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'buyer', 1, '0844444444', 'Jakarta Pusat', NULL);



-- Kategori
INSERT INTO `categories` (`name`, `description`) VALUES
('Bersih-bersih', 'Layanan kebersihan rumah, kantor'),
('Perbaikan', 'Servis AC, kulkas, pipa, elektronik'),
('Les Privat', 'Bimbingan belajar SD, SMP, SMA'),
('Laundry', 'Cuci setrika, kiloan antar jemput'),
('Taman', 'Perawatan taman, potong rumput'),
('Penitipan', 'Penitipan anak, hewan peliharaan'),
('Memasak', 'Jasa catering, koki pribadi'),
('Lainnya', 'Kategori lain-lain');

-- Jasa (services)
INSERT INTO `services` (`provider_id`, `category_id`, `title`, `description`, `price`, `price_unit`, `estimated_duration`, `location`, `image`, `is_active`) VALUES
(2, 1, 'Jasa Bersih Rumah Profesional', 'Membersihkan seluruh rumah dengan standar hotel', 150000.00, 'per kunjungan', '3-4 jam', 'Bandung Raya', 'service1.jpg', 1),
(2, 2, 'Servis AC & Kulkas Rumahan', 'Service AC dan kulkas untuk rumah tangga', 200000.00, 'per unit', '1-2 jam', 'Bandung', 'service2.jpg', 1),
(3, 3, 'Les Matematika SD-SMP', 'Guru privat matematika, persiapan ujian', 75000.00, 'per jam', '1 jam', 'Jakarta Selatan', 'service3.jpg', 1),
(3, 4, 'Laundry Kiloan Antar Jemput', 'Laundry kiloan dengan kualitas bersih dan wangi', 8000.00, 'per kg', '2 hari', 'Jakarta Selatan', 'service4.jpg', 1);

-- Orders
INSERT INTO `orders` (`buyer_id`, `provider_id`, `order_number`, `total_price`, `quantity`, `service_date`, `service_address`, `status`, `notes`) VALUES
(4, 2, 'ORD202506010001', 150000.00, 1, '2025-06-05', 'Jl. Raya No. 10, Surabaya', 'waiting_payment', 'Tolong bersihkan dengan teliti'),
(5, 3, 'ORD202506010002', 150000.00, 2, '2025-06-07', 'Jl. Sudirman No. 5, Jakarta Pusat', 'completed', 'Terima kasih');

-- Order items
INSERT INTO `order_items` (`order_id`, `service_id`, `quantity`, `price_per_unit`) VALUES
(1, 1, 1, 150000.00),
(2, 3, 2, 75000.00);

-- Payments
INSERT INTO `payments` (`order_id`, `method`, `proof_image`, `status`, `verified_at`, `notes`) VALUES
(2, 'bank_transfer', 'proof_ord002.jpg', 'verified', NOW(), 'Pembayaran valid');

-- Reviews
INSERT INTO `reviews` (`service_id`, `order_id`, `user_id`, `rating`, `comment`, `image`) VALUES
(3, 2, 5, 5, 'Lesnya sangat membantu, nilai anak saya meningkat', 'review1.jpg');

-- Notifications
INSERT INTO `notifications` (`user_id`, `title`, `message`, `is_read`) VALUES
(4, 'Pesanan Dibuat', 'Pesanan #ORD202506010001 berhasil dibuat, silakan upload bukti bayar', 0),
(2, 'Pesanan Baru', 'Anda mendapatkan pesanan baru #ORD202506010001', 0);

-- ======================================================
-- Selesai
-- ======================================================
