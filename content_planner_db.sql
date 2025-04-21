-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 21, 2025 at 01:58 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `content_planner_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `content_planner`
--

CREATE TABLE `content_planner` (
  `id` int NOT NULL,
  `nama_brand` varchar(100) DEFAULT NULL,
  `target_medsos` varchar(50) DEFAULT NULL,
  `jenis_produk` varchar(100) DEFAULT NULL,
  `tanggal_upload` date DEFAULT NULL,
  `jumlah_video` int DEFAULT NULL,
  `rate_per_video` decimal(12,2) DEFAULT NULL,
  `total_jumlah` decimal(12,2) DEFAULT NULL,
  `status` enum('Sudah','Belum') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `content_planner`
--

INSERT INTO `content_planner` (`id`, `nama_brand`, `target_medsos`, `jenis_produk`, `tanggal_upload`, `jumlah_video`, `rate_per_video`, `total_jumlah`, `status`) VALUES
(4, 'azarin', 'instagram ', 'Skintific', '2025-04-25', 3, 250000.00, 750000.00, 'Sudah'),
(5, 'Kahf', 'Instagram dan Tiktok', 'Facial Wash', '2025-04-25', 2, 250000.00, 500000.00, 'Sudah'),
(7, 'OMG', 'intsgaram', 'MAscara', '2025-04-23', 2, 300000.00, 600000.00, 'Sudah'),
(8, 'Pixy', 'Ig', 'Makeup', '2025-04-23', 1, 300000.00, 300000.00, 'Sudah'),
(9, 'Wardah', 'Array', 'Cushion', '2025-04-26', 2, 250000.00, 500000.00, 'Belum'),
(10, 'Elformula', 'Instagram, TikTok, Shopee', 'Skincare', '2025-04-29', 6, 200000.00, 1200000.00, 'Belum');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content_planner`
--
ALTER TABLE `content_planner`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `content_planner`
--
ALTER TABLE `content_planner`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
