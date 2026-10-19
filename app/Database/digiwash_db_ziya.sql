-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2026 at 09:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digiwash_db_ziya`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `noHP` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `noHP`, `email`, `password`, `created_at`, `updated_at`, `status`, `foto`) VALUES
(1, 'Admin DigiWash', '081200000001', 'admin@digiwash.test', '$2y$10$k3cyB68nEezeQTk.v2nmLeFwPSbvMxaEubA9/zBvbgLvumHcS70Oq', '2026-10-09 13:03:42', '2026-10-10 14:07:30', 'aktif', '1791641250_d87a8959c52f4a65e0ee.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `distribusi`
--

CREATE TABLE `distribusi` (
  `id_distribusi` int(10) UNSIGNED NOT NULL,
  `id_pesanan` int(10) UNSIGNED NOT NULL,
  `id_kurir` int(10) UNSIGNED DEFAULT NULL,
  `status_antar` enum('menunggu_kurir','proses_antar','terkirim') NOT NULL DEFAULT 'menunggu_kurir',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distribusi`
--

INSERT INTO `distribusi` (`id_distribusi`, `id_pesanan`, `id_kurir`, `status_antar`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'terkirim', '2026-10-18 11:42:10', '2026-10-18 15:22:27'),
(2, 1, 1, 'terkirim', '2026-10-18 11:42:58', '2026-10-18 15:24:59'),
(3, 3, 3, 'terkirim', '2026-10-18 15:40:01', '2026-10-18 18:32:13'),
(4, 6, 3, 'menunggu_kurir', '2026-10-18 16:32:36', '2026-10-19 06:45:44'),
(5, 4, 3, 'proses_antar', '2026-10-18 18:42:21', '2026-10-19 06:24:20'),
(6, 7, 2, 'menunggu_kurir', '2026-10-19 03:28:03', '2026-10-19 03:34:43'),
(7, 12, 3, 'terkirim', '2026-10-19 06:28:18', '2026-10-19 06:28:18'),
(8, 11, 3, 'proses_antar', '2026-10-19 06:47:00', '2026-10-19 06:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `kritik_saran`
--

CREATE TABLE `kritik_saran` (
  `id_kritik_saran` int(10) UNSIGNED NOT NULL,
  `id_pelanggan` int(10) UNSIGNED NOT NULL,
  `id_admin` int(10) UNSIGNED DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED DEFAULT 5,
  `isi_pesan` text NOT NULL,
  `jenis` varchar(20) DEFAULT 'Saran',
  `status` varchar(50) DEFAULT 'Belum ditindaklanjuti',
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kritik_saran`
--

INSERT INTO `kritik_saran` (`id_kritik_saran`, `id_pelanggan`, `id_admin`, `rating`, `isi_pesan`, `jenis`, `status`, `tanggal`) VALUES
(1, 2, 1, 4, 'Saya ingin pakaian saya diantar kurir nya sama abang yugo', 'Saran', 'Selesai', '2026-10-17 20:54:58'),
(2, 2, 1, 3, 'kurirnya bau', 'Keluhan', 'Dalam Proses', '2026-10-18 20:26:53'),
(3, 2, NULL, 4, 'lain kali lepas sepatu ya kurirnya pas masuk rumah :)', 'Saran', 'Belum ditindaklanjuti', '2026-10-18 20:27:46');

-- --------------------------------------------------------

--
-- Table structure for table `kurir`
--

CREATE TABLE `kurir` (
  `id_kurir` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `status` enum('tersedia','bertugas','nonaktif') NOT NULL DEFAULT 'tersedia',
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kurir`
--

INSERT INTO `kurir` (`id_kurir`, `nama`, `no_hp`, `status`, `email`, `password`, `created_at`, `updated_at`, `foto`) VALUES
(1, 'Andi Kurniawan', '081200000020', 'bertugas', 'andi@digiwash.test', '$2y$10$k3cyB68nEezeQTk.v2nmLeFwPSbvMxaEubA9/zBvbgLvumHcS70Oq', '2026-10-09 13:03:42', '2026-10-18 17:43:49', '1792345429_83995eaac2350aa1f733.png'),
(2, 'Rudi Hartono', '081200000021', 'bertugas', 'rudi@digiwash.test', '$2y$10$k3cyB68nEezeQTk.v2nmLeFwPSbvMxaEubA9/zBvbgLvumHcS70Oq', '2026-10-09 13:03:42', '2026-10-18 15:23:38', NULL),
(3, 'Ziya', '089023433456', 'bertugas', 'kurir@digiwash.test', '$2y$10$UFNta2swSXWf.gNU9zTEa.V5GRDjF2DkdI1xmKpXZ.Y/xAdFhq/Ma', '2026-10-10 13:57:23', '2026-10-19 06:45:32', '1791642176_8c9390f39b998f540ffc.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` int(10) UNSIGNED NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `jenis_layanan` enum('kiloan','satuan') NOT NULL DEFAULT 'kiloan',
  `harga_per_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `harga_flat` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estimasi_hari` int(11) NOT NULL DEFAULT 3,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deskripsi` text DEFAULT NULL,
  `ikon` varchar(50) DEFAULT 'bi-basket2-fill'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `jenis_layanan`, `harga_per_kg`, `harga_flat`, `estimasi_hari`, `is_active`, `deskripsi`, `ikon`) VALUES
(1, 'Laundry Reguler', 'kiloan', 7000.00, 0.00, 3, 1, 'Estimasi 3 hari kerja, bersih, wangi & rapi', 'bi-basket2-fill'),
(2, 'Laundry Express', 'kiloan', 12000.00, 0.00, 1, 1, 'Estimasi 1 hari kerja, prioritas kilat', 'bi-lightning-charge-fill'),
(3, 'Laundry Satuan', 'satuan', 0.00, 5000.00, 2, 1, 'Jasa cuci satuan seperti jas, selimut, boneka, sepatu', 'bi-tag-fill'),
(4, 'Laundry Bed Cover', 'kiloan', 7000.00, 35000.00, 3, 1, 'Cuci bed cover, sprei, selimut besar', 'bi-layers-fill');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(10) UNSIGNED NOT NULL,
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
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `no_hp`, `alamat`, `alamat_alternatif`, `email`, `tanggal_lahir`, `jenis_kelamin`, `password`, `created_at`, `updated_at`, `foto`, `status`) VALUES
(1, 'Budi Santoso', '081200000010', 'Jl. A. Yani Km 5, Banjarbaru', NULL, 'budi@digiwash.test', NULL, NULL, '$2y$10$k3cyB68nEezeQTk.v2nmLeFwPSbvMxaEubA9/zBvbgLvumHcS70Oq', '2026-10-09 13:03:42', '2026-10-09 13:03:42', NULL, 'aktif'),
(2, 'Siti Rahma', '081200000011', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Veteran No. 3, Banjarmasin', 'siti@digiwash.test', NULL, NULL, '$2y$10$k3cyB68nEezeQTk.v2nmLeFwPSbvMxaEubA9/zBvbgLvumHcS70Oq', '2026-10-09 13:03:42', '2026-10-10 14:26:29', '1791642389_5bca7a32eb6f9bb93fbc.jpeg', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(10) UNSIGNED NOT NULL,
  `id_pesanan` int(10) UNSIGNED NOT NULL,
  `id_admin` int(10) UNSIGNED DEFAULT NULL,
  `metode` varchar(50) NOT NULL,
  `status_bayar` enum('belum_dibayar','menunggu_verifikasi','lunas','gagal') NOT NULL DEFAULT 'menunggu_verifikasi',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `total_bayar` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tanggal_bayar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_pesanan`, `id_admin`, `metode`, `status_bayar`, `bukti_bayar`, `total_bayar`, `tanggal_bayar`) VALUES
(1, 1, 1, 'Bank BCA', 'lunas', '1792315815_f272482a47fa2aaf96fd.png', 35000.00, '2026-10-18 01:30:15'),
(2, 2, 1, 'Bank Mandiri', 'lunas', '1792323405_df74790a9f89c05c5ad2.png', 48000.00, '2026-10-18 03:36:45'),
(3, 3, 1, 'Bank Mandiri', 'lunas', '1792337599_3fad2c878a94475f0e0c.jpeg', 35000.00, '2026-10-18 07:33:19'),
(4, 4, 1, 'GoPay', 'lunas', '1792348866_1426a4db07f85c6c4ed8.png', 35000.00, '2026-10-18 10:41:06'),
(5, 5, 1, 'Bank Mandiri', 'gagal', '1792383328_09f3cdb9c766626a6966.png', 60000.00, '2026-10-18 20:15:28'),
(6, 6, 1, 'Bank BCA', 'lunas', '1792340394_e09345ad170a55736cdc.jpeg', 60000.00, '2026-10-18 08:19:54'),
(7, 7, 1, 'Bank BNI', 'lunas', '1792344714_033df868ba49192c8c0e.png', 35000.00, '2026-10-18 09:31:54'),
(8, 8, NULL, 'Bank Mandiri', 'gagal', NULL, 60000.00, '2026-10-18 09:38:38'),
(9, 9, NULL, 'Bank BRI', 'gagal', NULL, 60000.00, '2026-10-18 19:00:03'),
(10, 10, 1, 'Bank BNI', 'gagal', '1792386949_85ddd69cb0a687b3e5d2.png', 35000.00, '2026-10-18 21:15:49'),
(11, 11, 1, 'Bank BNI', 'lunas', '1792388281_29283b53e44ade7e69e0.png', 60000.00, '2026-10-18 21:38:01'),
(12, 12, 1, 'ShopeePay', 'lunas', '1792390413_ec200caf82cc8b760d2e.png', 60000.00, '2026-10-18 22:13:33');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(10) UNSIGNED NOT NULL,
  `id_pelanggan` int(10) UNSIGNED NOT NULL,
  `id_layanan` int(10) UNSIGNED NOT NULL,
  `id_admin` int(10) UNSIGNED DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `berat` decimal(6,2) NOT NULL DEFAULT 1.00,
  `alamat_pengambilan` text NOT NULL,
  `alamat_pengantaran` text NOT NULL,
  `status_pesanan` enum('menunggu','dikonfirmasi','dijemput','dicuci','disetrika','diantar','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu',
  `catatan` text DEFAULT NULL,
  `detail_satuan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_pelanggan`, `id_layanan`, `id_admin`, `tanggal`, `berat`, `alamat_pengambilan`, `alamat_pengantaran`, `status_pesanan`, `catatan`, `detail_satuan`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, '2026-10-18 09:30:00', 5.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'selesai', '', '', '2026-10-18 09:30:00', '2026-10-18 15:24:59'),
(2, 2, 2, 1, '2026-10-18 11:36:13', 4.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'selesai', '', '', '2026-10-18 11:36:13', '2026-10-18 15:22:27'),
(3, 2, 1, 1, '2026-10-19 15:32:55', 5.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'selesai', '', '', '2026-10-18 15:32:56', '2026-10-18 18:32:13'),
(4, 2, 1, 1, '2026-10-18 16:13:37', 5.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'diantar', '', '', '2026-10-18 16:13:37', '2026-10-19 06:24:20'),
(5, 2, 2, 1, '2026-10-18 16:16:43', 5.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'dibatalkan', '[Dibatalkan oleh pelanggan]', '', '2026-10-18 16:16:43', '2026-10-19 04:58:44'),
(6, 2, 2, 1, '2026-10-18 16:19:45', 5.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'dijemput', '', '', '2026-10-18 16:19:45', '2026-10-19 06:45:44'),
(7, 2, 1, 1, '2026-10-18 17:30:17', 5.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'dikonfirmasi', '', '', '2026-10-18 17:30:17', '2026-10-18 17:32:57'),
(8, 2, 2, NULL, '2026-10-18 17:38:38', 5.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'dibatalkan', '[Dibatalkan oleh pelanggan]', '', '2026-10-18 17:38:38', '2026-10-19 04:16:02'),
(9, 2, 2, NULL, '2026-10-19 03:00:03', 5.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'dibatalkan', '[Dibatalkan oleh pelanggan]', '', '2026-10-19 03:00:03', '2026-10-19 04:24:20'),
(10, 2, 1, 1, '2026-10-19 04:16:37', 5.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'dibatalkan', '', '', '2026-10-19 04:16:37', '2026-10-19 05:16:23'),
(11, 2, 2, 1, '2026-10-19 05:37:53', 5.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'dikonfirmasi', '', '', '2026-10-19 05:37:53', '2026-10-19 06:13:51'),
(12, 2, 2, 1, '2026-10-19 06:12:29', 5.00, 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'Jl. Lambung Mangkurat No. 12, Banjarmasin', 'selesai', '', '', '2026-10-19 06:12:29', '2026-10-19 06:28:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `uq_admin_email` (`email`),
  ADD UNIQUE KEY `uq_admin_noHP` (`noHP`);

--
-- Indexes for table `distribusi`
--
ALTER TABLE `distribusi`
  ADD PRIMARY KEY (`id_distribusi`),
  ADD KEY `fk_distribusi_pesanan` (`id_pesanan`),
  ADD KEY `fk_distribusi_kurir` (`id_kurir`);

--
-- Indexes for table `kritik_saran`
--
ALTER TABLE `kritik_saran`
  ADD PRIMARY KEY (`id_kritik_saran`),
  ADD KEY `fk_kritik_pelanggan` (`id_pelanggan`),
  ADD KEY `fk_kritik_admin` (`id_admin`);

--
-- Indexes for table `kurir`
--
ALTER TABLE `kurir`
  ADD PRIMARY KEY (`id_kurir`),
  ADD UNIQUE KEY `uq_kurir_email` (`email`),
  ADD UNIQUE KEY `uq_kurir_no_hp` (`no_hp`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD UNIQUE KEY `uq_pelanggan_email` (`email`),
  ADD UNIQUE KEY `uq_pelanggan_no_hp` (`no_hp`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `fk_pembayaran_pesanan` (`id_pesanan`),
  ADD KEY `fk_pembayaran_admin` (`id_admin`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `fk_pesanan_pelanggan` (`id_pelanggan`),
  ADD KEY `fk_pesanan_layanan` (`id_layanan`),
  ADD KEY `fk_pesanan_admin` (`id_admin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `distribusi`
--
ALTER TABLE `distribusi`
  MODIFY `id_distribusi` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kritik_saran`
--
ALTER TABLE `kritik_saran`
  MODIFY `id_kritik_saran` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kurir`
--
ALTER TABLE `kurir`
  MODIFY `id_kurir` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `distribusi`
--
ALTER TABLE `distribusi`
  ADD CONSTRAINT `fk_distribusi_kurir` FOREIGN KEY (`id_kurir`) REFERENCES `kurir` (`id_kurir`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_distribusi_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE;

--
-- Constraints for table `kritik_saran`
--
ALTER TABLE `kritik_saran`
  ADD CONSTRAINT `fk_kritik_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_kritik_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_pesanan_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_pesanan_layanan` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`),
  ADD CONSTRAINT `fk_pesanan_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
