-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: digiwash_db_ziya
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `digiwash_db_ziya`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `digiwash_db_ziya` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `digiwash_db_ziya`;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id_admin` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `noHP` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `uq_admin_email` (`email`),
  UNIQUE KEY `uq_admin_noHP` (`noHP`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Admin DigiWash','081200000001','admin@digiwash.test','$2y$10$k3cyB68nEezeQTk.v2nmLeFwPSbvMxaEubA9/zBvbgLvumHcS70Oq','2026-10-09 13:03:42','2026-10-10 14:07:30','aktif','1791641250_d87a8959c52f4a65e0ee.jpeg');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--


--
-- Table structure for table `distribusi`
--

DROP TABLE IF EXISTS `distribusi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `distribusi` (
  `id_distribusi` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pesanan` int(10) unsigned NOT NULL,
  `id_kurir` int(10) unsigned DEFAULT NULL,
  `jenis_kendaraan` varchar(50) DEFAULT NULL,
  `biaya` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status_antar` enum('menunggu_kurir','proses_antar','terkirim') NOT NULL DEFAULT 'menunggu_kurir',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_distribusi`),
  KEY `fk_distribusi_pesanan` (`id_pesanan`),
  KEY `fk_distribusi_kurir` (`id_kurir`),
  CONSTRAINT `fk_distribusi_kurir` FOREIGN KEY (`id_kurir`) REFERENCES `kurir` (`id_kurir`) ON DELETE SET NULL,
  CONSTRAINT `fk_distribusi_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distribusi`
--

LOCK TABLES `distribusi` WRITE;
/*!40000 ALTER TABLE `distribusi` DISABLE KEYS */;
INSERT INTO `distribusi` VALUES (1,2,3,NULL,0.00,'menunggu_kurir','2026-10-18 11:42:10','2026-10-18 11:42:20'),(2,1,2,NULL,0.00,'menunggu_kurir','2026-10-18 11:42:58','2026-10-18 11:42:58');
/*!40000 ALTER TABLE `distribusi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kritik_saran`
--

DROP TABLE IF EXISTS `kritik_saran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kritik_saran` (
  `id_kritik_saran` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pelanggan` int(10) unsigned NOT NULL,
  `id_admin` int(10) unsigned DEFAULT NULL,
  `no_pesanan` varchar(50) DEFAULT NULL,
  `rating` tinyint(3) unsigned DEFAULT 5,
  `isi_pesan` text NOT NULL,
  `jenis` varchar(20) DEFAULT 'Saran',
  `status` varchar(50) DEFAULT 'Belum ditindaklanjuti',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_kritik_saran`),
  KEY `fk_kritik_pelanggan` (`id_pelanggan`),
  KEY `fk_kritik_admin` (`id_admin`),
  CONSTRAINT `fk_kritik_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL,
  CONSTRAINT `fk_kritik_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kritik_saran`
--

LOCK TABLES `kritik_saran` WRITE;
/*!40000 ALTER TABLE `kritik_saran` DISABLE KEYS */;
INSERT INTO `kritik_saran` VALUES (1,2,1,'#INV-181026-836',4,'Saya ingin pakaian saya diantar kurir nya sama abang yugo','Saran','Selesai',NULL,NULL,'2026-10-17 20:54:58');
/*!40000 ALTER TABLE `kritik_saran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kurir`
--

DROP TABLE IF EXISTS `kurir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kurir` (
  `id_kurir` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `status` enum('tersedia','bertugas','nonaktif') NOT NULL DEFAULT 'tersedia',
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_kurir`),
  UNIQUE KEY `uq_kurir_email` (`email`),
  UNIQUE KEY `uq_kurir_no_hp` (`no_hp`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kurir`
--

LOCK TABLES `kurir` WRITE;
/*!40000 ALTER TABLE `kurir` DISABLE KEYS */;
INSERT INTO `kurir` VALUES (1,'Andi Kurniawan','081200000020','tersedia','andi@digiwash.test','$2y$10$k3cyB68nEezeQTk.v2nmLeFwPSbvMxaEubA9/zBvbgLvumHcS70Oq','2026-10-09 13:03:42','2026-10-09 13:03:42',NULL),(2,'Rudi Hartono','081200000021','tersedia','rudi@digiwash.test','$2y$10$k3cyB68nEezeQTk.v2nmLeFwPSbvMxaEubA9/zBvbgLvumHcS70Oq','2026-10-09 13:03:42','2026-10-09 13:03:42',NULL),(3,'Ziya','089023433456','bertugas','kurir@digiwash.test','$2y$10$UFNta2swSXWf.gNU9zTEa.V5GRDjF2DkdI1xmKpXZ.Y/xAdFhq/Ma','2026-10-10 13:57:23','2026-10-18 11:42:20','1791642176_8c9390f39b998f540ffc.jpeg');
/*!40000 ALTER TABLE `kurir` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `layanan`
--

DROP TABLE IF EXISTS `layanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layanan` (
  `id_layanan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_layanan` varchar(100) NOT NULL,
  `jenis_layanan` enum('kiloan','satuan') NOT NULL DEFAULT 'kiloan',
  `harga_per_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `harga_flat` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estimasi_hari` int(11) NOT NULL DEFAULT 3,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deskripsi` text DEFAULT NULL,
  `ikon` varchar(50) DEFAULT 'bi-basket2-fill',
  PRIMARY KEY (`id_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `layanan`
--

LOCK TABLES `layanan` WRITE;
/*!40000 ALTER TABLE `layanan` DISABLE KEYS */;
INSERT INTO `layanan` VALUES (1,'Laundry Reguler','kiloan',7000.00,0.00,3,1,'Estimasi 3 hari kerja, bersih, wangi & rapi','bi-basket2-fill'),(2,'Laundry Express','kiloan',12000.00,0.00,1,1,'Estimasi 1 hari kerja, prioritas kilat','bi-lightning-charge-fill'),(3,'Laundry Satuan','satuan',0.00,5000.00,2,1,'Jasa cuci satuan seperti jas, selimut, boneka, sepatu','bi-tag-fill'),(4,'Laundry Bed Cover','kiloan',7000.00,35000.00,3,1,'Cuci bed cover, sprei, selimut besar','bi-layers-fill');
/*!40000 ALTER TABLE `layanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pelanggan`
--

DROP TABLE IF EXISTS `pelanggan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pelanggan` (
  `id_pelanggan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `alamat_alternatif` text DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  PRIMARY KEY (`id_pelanggan`),
  UNIQUE KEY `uq_pelanggan_email` (`email`),
  UNIQUE KEY `uq_pelanggan_no_hp` (`no_hp`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pelanggan`
--

LOCK TABLES `pelanggan` WRITE;
/*!40000 ALTER TABLE `pelanggan` DISABLE KEYS */;
INSERT INTO `pelanggan` VALUES (1,'Budi Santoso','081200000010','Jl. A. Yani Km 5, Banjarbaru',NULL,'budi@digiwash.test',NULL,NULL,'$2y$10$k3cyB68nEezeQTk.v2nmLeFwPSbvMxaEubA9/zBvbgLvumHcS70Oq','2026-10-09 13:03:42','2026-10-09 13:03:42',NULL,'aktif'),(2,'Siti Rahma','081200000011','Jl. Lambung Mangkurat No. 12, Banjarmasin','Jl. Veteran No. 3, Banjarmasin','siti@digiwash.test',NULL,NULL,'$2y$10$k3cyB68nEezeQTk.v2nmLeFwPSbvMxaEubA9/zBvbgLvumHcS70Oq','2026-10-09 13:03:42','2026-10-10 14:26:29','1791642389_5bca7a32eb6f9bb93fbc.jpeg','aktif');
/*!40000 ALTER TABLE `pelanggan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayaran`
--

DROP TABLE IF EXISTS `pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pembayaran` (
  `id_pembayaran` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pesanan` int(10) unsigned NOT NULL,
  `id_admin` int(10) unsigned DEFAULT NULL,
  `metode` varchar(50) NOT NULL,
  `status_bayar` enum('belum_dibayar','menunggu_verifikasi','lunas','gagal') NOT NULL DEFAULT 'menunggu_verifikasi',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `total_bayar` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tanggal_bayar` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pembayaran`),
  KEY `fk_pembayaran_pesanan` (`id_pesanan`),
  KEY `fk_pembayaran_admin` (`id_admin`),
  CONSTRAINT `fk_pembayaran_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL,
  CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran`
--

LOCK TABLES `pembayaran` WRITE;
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
INSERT INTO `pembayaran` VALUES (1,1,1,'Bank BCA','lunas','1792315815_f272482a47fa2aaf96fd.png',35000.00,'2026-10-18 01:30:15'),(2,2,1,'Bank Mandiri','lunas','1792323405_df74790a9f89c05c5ad2.png',48000.00,'2026-10-18 03:36:45');
/*!40000 ALTER TABLE `pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan`
--

DROP TABLE IF EXISTS `pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesanan` (
  `id_pesanan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pelanggan` int(10) unsigned NOT NULL,
  `id_layanan` int(10) unsigned NOT NULL,
  `id_admin` int(10) unsigned DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `berat` decimal(6,2) NOT NULL DEFAULT 1.00,
  `alamat_pengambilan` text NOT NULL,
  `alamat_pengantaran` text NOT NULL,
  `status_pesanan` enum('menunggu','dikonfirmasi','dijemput','dicuci','disetrika','diantar','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu',
  `catatan` text DEFAULT NULL,
  `detail_satuan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_pesanan`),
  KEY `fk_pesanan_pelanggan` (`id_pelanggan`),
  KEY `fk_pesanan_layanan` (`id_layanan`),
  KEY `fk_pesanan_admin` (`id_admin`),
  CONSTRAINT `fk_pesanan_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL,
  CONSTRAINT `fk_pesanan_layanan` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`),
  CONSTRAINT `fk_pesanan_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan`
--

LOCK TABLES `pesanan` WRITE;
/*!40000 ALTER TABLE `pesanan` DISABLE KEYS */;
INSERT INTO `pesanan` VALUES (1,2,1,1,'2026-10-18 09:30:00',5.00,'Jl. Lambung Mangkurat No. 12, Banjarmasin','Jl. Lambung Mangkurat No. 12, Banjarmasin','dicuci','','','2026-10-18 09:30:00','2026-10-18 11:42:58'),(2,2,2,1,'2026-10-18 11:36:13',4.00,'Jl. Lambung Mangkurat No. 12, Banjarmasin','Jl. Lambung Mangkurat No. 12, Banjarmasin','dijemput','','','2026-10-18 11:36:13','2026-10-18 11:42:10');
/*!40000 ALTER TABLE `pesanan` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-18 20:53:45
