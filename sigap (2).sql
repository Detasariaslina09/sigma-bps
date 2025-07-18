-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 18, 2025 at 04:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sigap`
--

-- --------------------------------------------------------

--
-- Table structure for table `konten`
--

CREATE TABLE `konten` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `konten`
--

INSERT INTO `konten` (`id`, `title`, `description`, `image`) VALUES
(1, 'selamat datang di BPS Kota Bandar Lampung', 'pegawai ter Baik Minggu ini', 'konten_1752629179.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `icon` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `icon`, `created_at`) VALUES
(1, 'Pengambangan Kompetensi', NULL, NULL, '2025-07-18 01:29:18'),
(2, 'Kenaikan Pangkat', NULL, NULL, '2025-07-18 01:29:18'),
(3, 'Uji Kompetensi', NULL, NULL, '2025-07-18 01:29:18'),
(4, 'Angka Kredit', NULL, NULL, '2025-07-18 01:29:18');

-- --------------------------------------------------------

--
-- Table structure for table `service_link`
--

CREATE TABLE `service_link` (
  `id` int NOT NULL,
  `service_id` int NOT NULL,
  `link_title` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `service_link`
--

INSERT INTO `service_link` (`id`, `service_id`, `link_title`, `url`, `created_at`) VALUES
(4, 1, 'Spreadsheet Pengembangan Kompetensi', 'https://docs.google.com/spreadsheets/pengembangan-kompetensi', '2025-07-18 01:29:41'),
(5, 1, 'Informasi Webinar/Pelatihan LMS', '#webinar-pelatihan', '2025-07-18 01:29:41'),
(6, 1, 'Spreadsheet Link Sertifikat', 'https://docs.google.com/spreadsheets/sertifikat', '2025-07-18 01:29:41'),
(7, 1, 'Kegiatan Kompetensi BPS Balam', '#kegiatan-kompetensi', '2025-07-18 01:29:41'),
(8, 2, 'Peraturan Kenaikan Pangkat', 'https://lampung.bps.go.id/peraturan-kp', '2025-07-18 01:29:42'),
(9, 2, 'Nominasi Kenaikan Pangkat', 'https://lampung.bps.go.id/nominasi-kp', '2025-07-18 01:29:42'),
(10, 2, 'Form Usul Kenaikan Pangkat', 'https://lampung.bps.go.id/form-usul-kp', '2025-07-18 01:29:42'),
(11, 2, 'Form naik naik', 'https://lampung.bps.go.id/form-usul-kp', '2025-07-18 01:29:42'),
(12, 3, 'Informasi Uji Kompetensi', 'https://lampung.bps.go.id/uji-kompetensi', '2025-07-18 01:29:42'),
(13, 3, 'Materi Uji Kompetensi', 'hfsofsk', '2025-07-18 01:29:42'),
(14, 4, 'Angka Kredit Pegawai', '#angka-kredit-pegawai', '2025-07-18 01:29:42'),
(15, 4, 'PAK Konversi', '#pak-konversi', '2025-07-18 01:29:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$kKrxhs7ioe06cb1FcTjuXev9zLYmH64DYOBRNquZFqjRxWnSi/w9K', 'admin', '2025-07-15 07:43:32'),
(2, 'user', '$2y$10$0n3t6lYAjLfEqlzwDjaocucjKR0YR9bsBDcbY16YOKuJ8FH1JQqR6', 'user', '2025-07-15 07:43:32'),
(3, 'deta', '$2y$10$tUAnm27phZaO9IyS/dPCo.tWq6TX.5nKELdvmqqYG4FJx6Yxz41aK', 'admin', '2025-07-16 13:46:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `konten`
--
ALTER TABLE `konten`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_link`
--
ALTER TABLE `service_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `konten`
--
ALTER TABLE `konten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_link`
--
ALTER TABLE `service_link`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `service_link`
--
ALTER TABLE `service_link`
  ADD CONSTRAINT `service_link_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
