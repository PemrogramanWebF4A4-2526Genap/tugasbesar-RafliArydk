-- ======================================================
-- Database: bisabantu
-- BisaBantu (Lokal Service Marketplace)
-- Dump otomatis (mysqldump): 2026-07-10 14:49:46
-- ======================================================

-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: bisabantu
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Bersih-bersih','Layanan kebersihan rumah, kantor','2026-06-01 06:47:36'),(2,'Perbaikan','Servis AC, kulkas, pipa, elektronik','2026-06-01 06:47:36'),(3,'Les Privat','Bimbingan belajar SD, SMP, SMA','2026-06-01 06:47:36'),(4,'Laundry','Cuci setrika, kiloan antar jemput','2026-06-01 06:47:36'),(5,'Taman','Perawatan taman, potong rumput','2026-06-01 06:47:36'),(6,'Penitipan','Penitipan anak, hewan peliharaan','2026-06-01 06:47:36'),(7,'Memasak','Jasa catering, koki pribadi','2026-06-01 06:47:36');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `invoice_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pdf_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `generated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (2,2,'INV202606050002','assets/invoices/invoice_ORD202506010002.html','2026-06-05 14:39:02'),(3,5,'INV202606060005','assets/invoices/invoice_ORD202606064295.html','2026-06-06 21:20:15'),(4,4,'INV202606060004','assets/invoices/invoice_ORD202606069684.html','2026-06-06 21:20:17'),(5,3,'INV202606060003','assets/invoices/invoice_ORD202606065061.html','2026-06-06 21:20:53'),(6,6,'INV202606090006','assets/invoices/invoice_ORD202606092955.html','2026-06-09 20:36:35'),(7,7,'INV202606090007','assets/invoices/invoice_ORD202606091953.html','2026-06-09 20:36:35'),(9,9,'INV202607090009','src/assets/invoices/invoice_ORD202607096925.html','2026-07-09 18:32:27');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (2,2,'Pesanan Baru','Anda mendapatkan pesanan baru #ORD202506010001',1,'2026-06-01 06:47:37'),(3,3,'Akun Terverifikasi','Selamat! Akun penyedia Anda telah diverifikasi oleh admin.',1,'2026-06-04 22:50:59'),(4,2,'Pesanan Baru','Anda mendapat pesanan baru dengan No. Order ORD202606065061',1,'2026-06-06 06:33:26'),(5,6,'Pesanan Dibuat','Pesanan ORD202606065061 berhasil dibuat. Silakan upload bukti pembayaran.',0,'2026-06-06 06:33:26'),(6,2,'Pesanan Baru','Anda mendapat pesanan baru dengan No. Order ORD202606069684',1,'2026-06-06 06:35:30'),(7,5,'Pesanan Dibuat','Pesanan ORD202606069684 berhasil dibuat. Silakan upload bukti pembayaran.',1,'2026-06-06 06:35:30'),(8,3,'Pesanan Baru','Anda mendapat pesanan baru dengan No. Order ORD202606064295',1,'2026-06-06 07:09:09'),(9,5,'Pesanan Dibuat','Pesanan ORD202606064295 berhasil dibuat. Silakan upload bukti pembayaran.',1,'2026-06-06 07:09:09'),(10,5,'Pembayaran Berhasil','Pembayaran untuk pesanan ORD202606064295 telah diverifikasi.',1,'2026-06-06 07:20:15'),(11,3,'Pesanan Dibayar','Pesanan ORD202606064295 telah dibayar oleh pembeli. Silakan kerjakan.',1,'2026-06-06 07:20:15'),(12,5,'Pembayaran Berhasil','Pembayaran untuk pesanan ORD202606069684 telah diverifikasi.',1,'2026-06-06 07:20:17'),(13,2,'Pesanan Dibayar','Pesanan ORD202606069684 telah dibayar oleh pembeli. Silakan kerjakan.',1,'2026-06-06 07:20:17'),(14,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606064295 sekarang berstatus: Diterima.',1,'2026-06-06 07:21:48'),(15,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606064295 sekarang berstatus: Diproses.',1,'2026-06-06 07:21:54'),(16,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606064295 sekarang berstatus: Selesai.',1,'2026-06-06 07:21:57'),(17,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606069684 sekarang berstatus: Diterima.',1,'2026-06-06 21:56:20'),(18,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606069684 sekarang berstatus: Diproses.',1,'2026-06-06 22:59:41'),(19,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606069684 sekarang berstatus: Selesai.',1,'2026-06-06 22:59:43'),(20,6,'Status Pesanan Diperbarui','Pesanan Anda ORD202606065061 sekarang berstatus: Diproses.',0,'2026-06-06 22:59:44'),(21,6,'Status Pesanan Diperbarui','Pesanan Anda ORD202606065061 sekarang berstatus: Selesai.',0,'2026-06-08 06:11:02'),(22,3,'Pesanan Baru','Anda mendapat pesanan baru dengan No. Order ORD202606092955',1,'2026-06-09 06:36:35'),(23,5,'Pesanan COD Dibuat','Pesanan ORD202606092955 berhasil dibuat dengan metode COD.',1,'2026-06-09 06:36:35'),(24,2,'Pesanan Baru','Anda mendapat pesanan baru dengan No. Order ORD202606091953',1,'2026-06-09 06:36:35'),(25,5,'Pesanan COD Dibuat','Pesanan ORD202606091953 berhasil dibuat dengan metode COD.',1,'2026-06-09 06:36:35'),(27,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606091953 sekarang berstatus: Diterima.',1,'2026-06-09 07:14:11'),(28,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606091953 sekarang berstatus: Diproses.',1,'2026-06-09 07:14:13'),(29,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606091953 sekarang berstatus: Selesai.',1,'2026-06-09 07:14:15'),(31,5,'Pesanan Dibuat','Pesanan ORD202606094573 berhasil dibuat. Silakan upload bukti pembayaran.',1,'2026-06-09 07:15:24'),(32,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606092955 sekarang berstatus: Diterima.',1,'2026-06-09 07:18:56'),(33,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606092955 sekarang berstatus: Diproses.',1,'2026-06-09 07:18:57'),(34,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606094573 sekarang berstatus: Diterima.',1,'2026-06-09 07:25:35'),(35,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606094573 sekarang berstatus: Diproses.',1,'2026-06-09 07:47:10'),(36,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606094573 sekarang berstatus: Selesai.',1,'2026-06-09 07:47:14'),(37,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202606092955 sekarang berstatus: Selesai.',1,'2026-06-14 02:55:17'),(38,2,'Pesanan Baru','Anda mendapat pesanan baru dengan No. Order ORD202607096925',1,'2026-07-09 11:32:27'),(39,5,'Pesanan COD Dibuat','Pesanan ORD202607096925 berhasil dibuat dengan metode COD.',1,'2026-07-09 11:32:27'),(40,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202607096925 sekarang berstatus: Diterima.',1,'2026-07-09 11:35:08'),(41,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202607096925 sekarang berstatus: Diproses.',1,'2026-07-09 11:35:11'),(42,5,'Status Pesanan Diperbarui','Pesanan Anda ORD202607096925 sekarang berstatus: Selesai.',1,'2026-07-09 11:35:14'),(43,5,'Pembayaran Berhasil','Pembayaran untuk pesanan ORD202606094573 telah diverifikasi.',1,'2026-07-09 11:39:50'),(45,11,'Akun Terverifikasi','Selamat! Akun penyedia Anda telah diverifikasi oleh admin.',0,'2026-07-09 15:26:46'),(46,3,'Pesanan Baru','Anda mendapat pesanan baru dengan No. Order ORD202607092807',0,'2026-07-09 16:05:17'),(47,5,'Pesanan Dibuat','Pesanan ORD202607092807 berhasil dibuat. Silakan upload bukti pembayaran.',1,'2026-07-09 16:05:17'),(48,2,'Pesanan Baru','Anda mendapat pesanan baru dengan No. Order ORD202607093540',0,'2026-07-09 16:05:17'),(49,5,'Pesanan Dibuat','Pesanan ORD202607093540 berhasil dibuat. Silakan upload bukti pembayaran.',1,'2026-07-09 16:05:17');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `service_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_per_unit` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (2,2,3,2,75000.00),(3,3,1,1,150000.00),(4,4,1,1,150000.00),(5,4,2,1,200000.00),(6,5,3,1,75000.00),(7,5,4,1,8000.00),(8,6,4,1,8000.00),(9,7,1,1,150000.00),(11,9,1,1,150000.00),(12,10,4,1,8000.00),(13,10,3,1,75000.00),(14,11,1,1,150000.00);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `buyer_id` int NOT NULL,
  `provider_id` int NOT NULL,
  `order_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `service_date` date NOT NULL,
  `service_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','waiting_payment','paid','accepted','in_progress','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `buyer_id` (`buyer_id`),
  KEY `provider_id` (`provider_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (2,5,3,'ORD202506010002',150000.00,2,'2025-06-07','Jl. Sudirman No. 5, Jakarta Pusat','completed','Terima kasih','2026-06-01 06:47:36','2026-06-05 00:39:02'),(3,6,2,'ORD202606065061',150000.00,1,'2026-06-10','Alamat smoke test','completed','Smoke test integrasi','2026-06-06 06:33:26','2026-06-08 06:11:02'),(4,5,2,'ORD202606069684',350000.00,2,'2026-06-06','awda','completed','awdwad','2026-06-06 06:35:30','2026-06-06 22:59:43'),(5,5,3,'ORD202606064295',83000.00,2,'2026-06-06','bababall','completed','awdawd','2026-06-06 07:09:09','2026-06-06 07:21:57'),(6,5,3,'ORD202606092955',8000.00,1,'2026-06-09','Jl perjuangan','completed','gang meerah putih','2026-06-09 06:36:35','2026-06-14 02:55:17'),(7,5,2,'ORD202606091953',150000.00,1,'2026-06-09','Jl perjuangan','completed','gang meerah putih','2026-06-09 06:36:35','2026-06-09 07:14:15'),(9,5,2,'ORD202607096925',150000.00,1,'2026-08-06','Jalan Agus Salim','completed','Patokan dekat pertigaan tugu, rumah samping sekolah','2026-07-09 11:32:27','2026-07-09 15:34:05'),(10,5,3,'ORD202607092807',83000.00,2,'2026-07-09','wwww','waiting_payment','awewaewa','2026-07-09 16:05:17','2026-07-09 16:05:17'),(11,5,2,'ORD202607093540',150000.00,1,'2026-07-09','wwww','waiting_payment','awewaewa','2026-07-09 16:05:17','2026-07-09 16:05:17');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `method` enum('bank_transfer','cash') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `proof_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','verified','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `verified_at` datetime DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,2,'bank_transfer','proof_ord002.jpg','verified','2026-06-01 20:47:37','Pembayaran valid','2026-06-01 06:47:37'),(2,4,'cash','1780752943_6a24222f25290.png','verified','2026-06-06 21:20:17','','2026-06-06 06:35:43'),(3,5,'bank_transfer','1780754961_6a242a112df92.png','verified','2026-06-06 21:20:15','','2026-06-06 07:09:21');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_schedules`
--

DROP TABLE IF EXISTS `provider_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_schedules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `provider_id` int NOT NULL,
  `day_of_week` tinyint(1) NOT NULL COMMENT '0=Senin,1=Selasa,...,6=Minggu',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `provider_id` (`provider_id`),
  CONSTRAINT `provider_schedules_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_schedules`
--

LOCK TABLES `provider_schedules` WRITE;
/*!40000 ALTER TABLE `provider_schedules` DISABLE KEYS */;
INSERT INTO `provider_schedules` VALUES (1,2,0,'08:00:00','17:00:00',1);
/*!40000 ALTER TABLE `provider_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `service_id` int NOT NULL,
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `service_id` (`service_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_chk_1` CHECK ((`rating` between 1 and 5))
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,3,2,5,5,'Lesnya sangat membantu, nilai anak saya meningkat','review1.jpg','2026-06-01 06:47:37'),(2,3,5,5,4,'sangat bagus',NULL,'2026-06-06 07:23:30'),(3,1,4,5,5,'sangat baik','1780812083_6a25093344409.jpg','2026-06-06 23:01:23'),(4,1,3,6,5,'Tepat Waktu',NULL,'2026-06-08 06:11:29'),(5,1,7,5,3,'biasa saja',NULL,'2026-06-09 07:36:42'),(7,4,6,5,4,'www',NULL,'2026-06-30 17:11:50');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `provider_id` int NOT NULL,
  `category_id` int NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `price_unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'per unit',
  `estimated_duration` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `provider_id` (`provider_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `services_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `services_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,2,1,'Jasa Bersih Rumah Profesional','Membersihkan seluruh rumah dengan standar hotel',150000.00,'per kunjungan','3-4 jam','Bandung','6a510413f1a408.30055372.jpg',1,'2026-06-01 06:47:36','2026-07-10 14:39:15'),(2,2,2,'Servis AC & Kulkas Rumahan','Service AC dan kulkas untuk rumah tangga',200000.00,'per unit','1-2 jam','Bandung','6a5103e2bf4562.99588123.jpg',1,'2026-06-01 06:47:36','2026-07-10 14:38:26'),(3,3,3,'Les Matematika SD-SMP','Guru privat matematika, persiapan ujian',75000.00,'per jam','1 jam','Jakarta Selatan','1780755468_6a242c0cc8ace.png',1,'2026-06-01 06:47:36','2026-06-06 07:17:48'),(4,3,4,'Laundry Kiloan Antar Jemput','Laundry kiloan dengan kualitas bersih dan wangi',8000.00,'per kg','2 hari','Jakarta Selatan','6a2e7c1015a815.39695712.jpg',1,'2026-06-01 06:47:36','2026-06-14 03:01:52'),(6,12,1,'Paket Bersih Rumah Harian','Layanan bersih-bersih rumah untuk ruang tamu, kamar, dapur, dan kamar mandi.',135000.00,'per kunjungan','3 jam','Bekasi Utara','6a5104c39985b7.55258532.jpg',1,'2026-07-10 14:33:02','2026-07-10 14:42:11'),(7,12,2,'Teknisi Perbaikan Rumah','Perbaikan ringan untuk AC, listrik kecil, kran, pintu, dan perlengkapan rumah.',175000.00,'per kunjungan','1-2 jam','Bekasi Utara','6a5104d136c6e8.94739871.jpg',1,'2026-07-10 14:33:02','2026-07-10 14:42:25'),(8,12,3,'Les Privat SD-SMA','Bimbingan belajar matematika, bahasa Inggris, dan persiapan ujian sekolah.',85000.00,'per jam','1 jam','Bekasi Utara','6a51068a134d73.74780218.jpg',1,'2026-07-10 14:33:02','2026-07-10 14:49:46'),(9,12,4,'Laundry Kiloan Cepat','Cuci, setrika, dan lipat pakaian dengan opsi antar jemput area sekitar.',9000.00,'per kg','2 hari','Bekasi Utara','6a510604471c18.28145505.jpg',1,'2026-07-10 14:33:02','2026-07-10 14:47:32'),(10,12,5,'Perawatan Taman Rumah','Potong rumput, rapikan tanaman, bersihkan daun kering, dan tata pot sederhana.',120000.00,'per kunjungan','2 jam','Bekasi Utara',NULL,1,'2026-07-10 14:33:02','2026-07-10 14:33:02'),(11,12,6,'Penitipan Anak dan Hewan','Jasa penitipan harian dengan pendampingan, jadwal makan, dan laporan singkat.',100000.00,'per hari','1 hari','Bekasi Utara',NULL,1,'2026-07-10 14:33:02','2026-07-10 14:33:02'),(12,12,7,'Jasa Masak Harian Rumahan','Bantuan memasak menu harian keluarga, meal prep, dan catering kecil rumahan.',65000.00,'per porsi','2-3 jam','Bekasi Utara','6a5106760ba348.69007699.jpg',1,'2026-07-10 14:33:02','2026-07-10 14:49:26');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('buyer','provider','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profile_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Rafli Aryadika','rafli@bisabantu.admin.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin',1,'','',NULL,'src/assets/uploads/profile/6a2819e0ea6e45.70898538.png','2026-06-01 06:47:36','2026-07-09 14:37:00'),(2,'Budi Wijaya','budi@bisabantu.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','provider',1,'','',NULL,'src/assets/uploads/profile/1780812028_6a2508fcc607f.jpg','2026-06-01 06:47:36','2026-07-09 14:37:00'),(3,'Sienna','sienna@bisabantu.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','provider',1,'0822222222','Jakarta Selatan',NULL,'src/assets/uploads/profile/6a2e7aa2168a05.85800877.png','2026-06-01 06:47:36','2026-07-09 14:37:00'),(5,'Blue purple','nasyla@bisabantu.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','buyer',1,'','',NULL,'src/assets/uploads/profile/6a4f85e2be0af0.78695526.jpg','2026-06-01 06:47:36','2026-07-09 11:28:34'),(6,'Arpi','arpi@bisabantu.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','buyer',1,'0833333333','Bekasi',NULL,NULL,'2026-06-03 07:16:50','2026-06-06 23:05:25'),(10,'Rafli Aryadika','rafliaryadika100@gmail.com','$2y$10$RwF3IHyWM1iYIEiqKUFDP.qGZmd7AAi4zvkKw3VIltOdono6Kjftm','buyer',1,'085171076449','jalan simatupang',NULL,NULL,'2026-06-24 00:00:48','2026-07-09 13:53:36'),(11,'Arpi Aryadika','rafliaryadika243@gmail.com','$2y$10$Tz2l4FChIWPawYC38CR2iOy7r/EmPN/S959YPypJDc4V39QWTltFO','provider',1,'08211020251','jalan raya mana aja dah',NULL,'src/assets/uploads/profile/6a4fbd9669add5.51993466.jpg','2026-06-24 00:22:20','2026-07-09 15:26:46'),(12,'BisaBantu Multi Jasa','alljasa@bisabantu.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','provider',1,'0812-7000-2026','Bekasi Utara',NULL,'src/assets/uploads/profile/6a5105937b0ea9.82534140.png','2026-07-10 14:33:02','2026-07-10 14:45:39');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'bisabantu'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-10 21:49:46
