-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2026 at 10:59 AM
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
-- Database: `db_resto`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `status` enum('sedang di masak','sudah di masak') NOT NULL,
  `metode_pembelian` enum('TA','DI') NOT NULL,
  `sub_total` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id`, `jumlah`, `catatan`, `status`, `metode_pembelian`, `sub_total`, `id_transaksi`, `id_menu`) VALUES
(1, 1, '', 'sedang di masak', 'DI', 3000, 7, 16);

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id` int(11) NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id`, `nama_karyawan`, `no_hp`, `alamat`, `jabatan`) VALUES
(1, 'Admin Resto', '081234567890', NULL, 'Manager'),
(2, 'Kasir Satu', '081234567891', NULL, 'Kasir'),
(3, 'Kasir Dua', '081234567892', NULL, 'Kasir');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(1, 'Makanan'),
(2, 'Minuman'),
(3, 'Snack'),
(4, 'Dessert');

-- --------------------------------------------------------

--
-- Table structure for table `meja`
--

CREATE TABLE `meja` (
  `id` int(11) NOT NULL,
  `no_meja` int(11) NOT NULL,
  `status` enum('available','booking') NOT NULL,
  `kapasitas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meja`
--

INSERT INTO `meja` (`id`, `no_meja`, `status`, `kapasitas`) VALUES
(1, 1, 'booking', 4),
(2, 2, 'available', 4),
(3, 3, 'available', 2),
(4, 4, 'available', 2),
(5, 5, 'available', 6),
(6, 6, 'available', 4),
(7, 7, 'available', 4),
(8, 8, 'available', 8);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `status` enum('tersedia','habis') NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `id_kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `harga`, `stok`, `status`, `foto`, `deskripsi`, `id_kategori`) VALUES
(1, 'Nasi Goreng Spesial', 25000, 50, 'tersedia', NULL, 'Nasi goreng dengan telur, ayam, dan sayuran', 1),
(2, 'Mie Goreng', 20000, 50, 'tersedia', NULL, 'Mie goreng dengan sayuran dan telur', 1),
(3, 'Ayam Goreng Kremes', 30000, 30, 'tersedia', NULL, 'Ayam goreng dengan kremesan renyah', 1),
(4, 'Soto Ayam', 22000, 40, 'tersedia', NULL, 'Soto ayam kuah kuning dengan nasi', 1),
(5, 'Gado-Gado', 18000, 35, 'tersedia', NULL, 'Sayuran dengan bumbu kacang', 1),
(6, 'Sate Ayam', 28000, 25, 'tersedia', NULL, 'Sate ayam 10 tusuk dengan bumbu kacang', 1),
(7, 'Nasi Uduk', 15000, 45, 'tersedia', NULL, 'Nasi uduk dengan lauk pauk', 1),
(8, 'Rendang', 35000, 20, 'tersedia', NULL, 'Rendang daging sapi pedas', 1),
(9, 'Es Teh Manis', 5000, 100, 'tersedia', NULL, 'Teh manis dingin', 2),
(10, 'Es Jeruk', 7000, 80, 'tersedia', NULL, 'Jeruk peras dengan es', 2),
(11, 'Jus Alpukat', 15000, 30, 'tersedia', NULL, 'Jus alpukat segar', 2),
(12, 'Jus Mangga', 15000, 30, 'tersedia', NULL, 'Jus mangga segar', 2),
(13, 'Kopi Hitam', 8000, 50, 'tersedia', NULL, 'Kopi hitam panas', 2),
(14, 'Cappuccino', 18000, 40, 'tersedia', NULL, 'Kopi cappuccino', 2),
(15, 'Teh Tarik', 10000, 60, 'tersedia', NULL, 'Teh tarik panas', 2),
(16, 'Air Mineral', 3000, 199, 'tersedia', NULL, 'Air mineral botol', 2),
(17, 'Pisang Goreng', 10000, 50, 'tersedia', NULL, 'Pisang goreng crispy', 3),
(18, 'Tahu Isi', 12000, 40, 'tersedia', NULL, 'Tahu isi sayuran', 3),
(19, 'Cireng', 8000, 60, 'tersedia', NULL, 'Cireng isi pedas', 3),
(20, 'Kentang Goreng', 15000, 45, 'tersedia', NULL, 'Kentang goreng crispy', 3),
(21, 'Es Campur', 12000, 35, 'tersedia', NULL, 'Es campur dengan buah dan agar-agar', 4),
(22, 'Es Krim Vanila', 10000, 40, 'tersedia', NULL, 'Es krim vanila 2 scoop', 4),
(23, 'Puding Coklat', 8000, 30, 'tersedia', NULL, 'Puding coklat lembut', 4),
(24, 'Pancake', 20000, 25, 'tersedia', NULL, 'Pancake dengan madu dan buah', 4);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `nama_kostumer` varchar(100) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `tanggal_transaksi` datetime NOT NULL,
  `metode_pembayaran` enum('tunai','qris') NOT NULL,
  `status` enum('sudah dibayar','belum dibayar') NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_meja` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `nama_kostumer`, `total_bayar`, `tanggal_transaksi`, `metode_pembayaran`, `status`, `id_user`, `id_meja`) VALUES
(7, 'Ikhsan', 3000, '2026-02-27 14:32:47', 'tunai', 'sudah dibayar', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `update_stok_harian`
--

CREATE TABLE `update_stok_harian` (
  `id` int(11) NOT NULL,
  `jumlah_porsi` int(11) NOT NULL,
  `tgl_update` datetime NOT NULL,
  `id_menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','manager','kasir') NOT NULL,
  `status` enum('aktif','tidak aktif') NOT NULL,
  `id_karyawan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `role`, `status`, `id_karyawan`) VALUES
(1, 'admin', 'admin@resto.com', '$2y$10$PSVWk/nQeUgIqBcnTEn7jOu5c8A7DT8SYIghBqVkYzJhzIxrc4b02', 'owner', 'aktif', 1),
(2, 'kasir1', 'kasir1@resto.com', '$2y$10$/Rd6csYW8uDfMXzKSf5vkuWZ5K50yP8pUJ8o8JZoVvePgxOU6MOz2', 'kasir', 'aktif', 2),
(3, 'kasir2', 'kasir2@resto.com', '$2y$10$lYjDTUlEcGm2Dra4xyO.3Ox.phui44PuukKm36rf5chQR6PwcefZa', 'kasir', 'aktif', 3),
(4, 'admin', 'admin@restoran.com', 'admin123', '', 'aktif', NULL),
(5, 'kasir', 'kasir@restoran.com', 'kasir123', 'kasir', 'aktif', NULL),
(6, 'user1', 'user@restoran.com', 'user123', '', 'aktif', NULL),
(7, 'admin', 'admin@restoran.com', 'admin123', '', 'aktif', NULL),
(8, 'kasir', 'kasir@restoran.com', 'kasir123', 'kasir', 'aktif', NULL),
(9, 'user1', 'user@restoran.com', 'user123', '', 'aktif', NULL),
(10, 'admin', 'admin@restoran.com', 'admin123', '', 'aktif', NULL),
(11, 'kasir', 'kasir@restoran.com', 'kasir123', 'kasir', 'aktif', NULL),
(12, 'user1', 'user@restoran.com', 'user123', '', 'aktif', NULL),
(13, 'admin', 'admin@restoran.com', 'admin123', '', 'aktif', NULL),
(14, 'kasir', 'kasir@restoran.com', 'kasir123', 'kasir', 'aktif', NULL),
(15, 'user1', 'user@restoran.com', 'user123', '', 'aktif', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meja`
--
ALTER TABLE `meja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_meja` (`id_meja`);

--
-- Indexes for table `update_stok_harian`
--
ALTER TABLE `update_stok_harian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `meja`
--
ALTER TABLE `meja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `update_stok_harian`
--
ALTER TABLE `update_stok_harian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_meja`) REFERENCES `meja` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `update_stok_harian`
--
ALTER TABLE `update_stok_harian`
  ADD CONSTRAINT `update_stok_harian_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
