-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 25, 2025 at 09:25 AM
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
(1, 'selamat datang di BPS Kota Bandar Lampung', 'Quote Terbaik Minggu ini', 'konten_1752822068.webp');

-- --------------------------------------------------------

--
-- Table structure for table `profil`
--

CREATE TABLE `profil` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `profil`
--

INSERT INTO `profil` (`id`, `nama`, `jabatan`, `foto`, `link`) VALUES
(8, 'Gun Gun Nugraha S.Si, M.S.E', 'Kepala Subbagian Umum', '6882f62cecc92.jpeg', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(9, 'Erika Haryulistiani Saksono S.E., M.E', 'Ketua tim Distribusi', '6882fc091bc70.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(10, 'Ari Rusmasari SST, M.Si.', 'Ketua tim PTID', '6882f65773942.jpeg', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(11, 'Aprilia Puspita Sari SST', 'Ketua tim Nerwilis', '6882f66d6e9d0.jpeg', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(12, 'Darul Ambardi SE', 'Ketua tim Produksi', '6882f68e379f6.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(13, 'Shista Virgo Winatha SE., ME.', 'Ketua tim Sosial', '6882f6a611986.jpeg', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(14, 'Evie Ermawati SST, M.M.', 'Pegawai', '6882fc23d4167.jpeg', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(15, 'Anne Oktavia Andriyani A.Md.', 'Pegawai', '6882f6cb82658.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(16, 'Sasma Senimawati Manik S.Si., M.Kom', 'Pegawai', '6882fa8734f50.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(17, 'Erika Santi ST, M.Si', 'Pegawai', '6882f707a6bfd.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(18, 'Andika Nur Budiharso S.E.', 'Pegawai', '6882f6f9e5c83.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(19, 'Dian Wuryandari Syafiatin S.Si., M.M.', 'Pegawai', '6882f71b49e0b.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(20, 'Wasilawati SE', 'Pegawai', '6882f72a28cfb.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(21, 'Mohammad Vicky Lukito SST', 'Pegawai', '6882f73dbac89.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(22, 'Anita Desmarini SST', 'Pegawai', '6882f74dc726b.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(23, 'Viona Rahma Agustin S.Tr.Stat.', 'Pegawai', '6882f75a5c578.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(24, 'Sari Citra Pratiwi SST', 'Pegawai', '6882f7660ffb5.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(25, 'Rizki Abdi Utama S. Kom', 'Pegawai', '6882f77946743.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(26, 'Fahroni Agustarita SE', 'Pegawai', '6882f78b86554.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(27, 'Muhammad Rafiqo Ardi SST', 'Pegawai', '6882f7a93ea05.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(28, 'Dr. Hady Suryono M.Si.', 'Kepala BPS Kota Bandar Lampung', '6882f867f3118.png', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(29, 'Kaisar Samudra, S.E.', 'Pegawai', '6882f88178342.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(30, 'Alberto Maradona', 'Pegawai', '6882f8a607f9f.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(31, 'Habni Hamara Azmatiy, S.Tr.Stat.', 'Pegawai', '6882f8b98e8cb.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(32, 'Risdiyanto, S.Psi.', 'Pegawai', '6882f8ca52aa4.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(33, 'Ikhsan, S.E.', 'Pegawai', '6882f8d701edf.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(34, 'Edy Kurniawan ', 'Pegawai', '6882f9714c1da.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(35, 'Akhmad Riadi ', 'Pegawai', '6882f8f49f2ce.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(36, 'Anggi Budi Pratiwi, S.Stat.', 'Pegawai', '6882f914843e8.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(37, 'Erwan Jafrilda, S.Sos.', 'Pegawai', '6882f926287b1.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(38, 'Indra Kurniawan, S.Sos.', 'Pegawai', '6882f93c622b8.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(40, 'Santi Yuli Elida Aritonang, A.Md.', 'Pegawai', '6882f9a31f989.jpeg', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(41, 'Mertha Pessela S.P., M.M.', 'Pegawai', '6882f9d4f357d.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(42, 'Belinda Yena Putri, A.Md.Kom.', 'Pegawai', '6882fe040e9d1.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(43, 'Bagus Prio Sambodo, S.E.', 'Pegawai', '6882fe3941eb6.JPG', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed'),
(44, 'Dini Arianindy, S.Tr.Stat.', 'Pegawai', '6882ff0210b4e.png', 'https://www.canva.com/design/DAGKD67OcC4/JK2Maa18ifTm_W3oNZrHBQ/view?embed');

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
(4, 1, 'Pengembangan Kompetensi', 'https://drive.google.com/drive/folders/1QeR_FjHGodJEv1_lXnpKXYPKN0CcLKGW?usp=drive_link', '2025-07-18 01:29:41'),
(5, 1, 'Informasi Webinar/Pelatihan LMS', 'https://drive.google.com/drive/folders/18MrWrXu0u0WQrsFc1qT8V8EHGU4IOA4T?usp=drive_link', '2025-07-18 01:29:41'),
(6, 1, 'Sertifikat', 'https://drive.google.com/drive/folders/1vgU6f5Cqpe7XluqeysKT-_FIXWtbgZ51?usp=drive_link', '2025-07-18 01:29:41'),
(7, 1, 'Kegiatan Kompetensi BPS Balam', 'https://drive.google.com/drive/folders/1WRFW4g8UR_DDTYZWuY8aBCcHWQuYysxl?usp=drive_link', '2025-07-18 01:29:41'),
(8, 2, 'Peraturan Kenaikan Pangkat', 'https://drive.google.com/drive/folders/1MwPrWhE9FUXVPOtRmKlB01HczKZIjrk4?usp=drive_link', '2025-07-18 01:29:42'),
(9, 2, 'Nominasi Kenaikan Pangkat', 'https://drive.google.com/drive/folders/1TXgMw4065Nixd5ukGpiumllFtOrrdVSi?usp=drive_link', '2025-07-18 01:29:42'),
(10, 2, 'Form Usul Kenaikan Pangkat', 'https://drive.google.com/drive/folders/13Gd4pkejdTxVej4d_IguMtPqAP3V2ZR0?usp=drive_link', '2025-07-18 01:29:42'),
(12, 3, 'Informasi Uji Kompetensi', 'https://lampung.bps.go.id/uji-kompetensi', '2025-07-18 01:29:42'),
(13, 3, 'Materi Uji Kompetensi', 'https://drive.google.com/drive/folders/1ltI53TFLyYfRZ0XwCEq3-Jp0eCtjuDy-?usp=drive_link', '2025-07-18 01:29:42'),
(14, 4, 'Angka Kredit Pegawai', 'https://drive.google.com/drive/folders/1nyoC350Lyyj2oG8N2NHRAsLD51f0Utvk?usp=drive_link', '2025-07-18 01:29:42'),
(15, 4, 'PAK Konversi', 'https://drive.google.com/drive/folders/1ujcmax6491l8cNuWPG5oTdW3G5QKbj1s?usp=drive_link', '2025-07-18 01:29:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$t2QOFn0lq9uFzDC0FyFRn.PHkgkLENR7D8we4M6mGyC7OGiyoZIo6', 'admin', '2025-07-21 07:46:36'),
(2, 'akhmad.riadi', '$2y$10$V6Q6fnbZ2P3dIuZwaj0qXeqif.qVpAtcJRWAZAlpediqTzOLjYcLu', 'user', '2025-07-21 07:48:24'),
(3, 'alberto.maradona', '$2y$10$QT4q.vTtZMfi0NjsEpBMveANQ8BkLlePTzmT.2LZyKQ.pSu6G/Uem', 'user', '2025-07-21 07:49:15'),
(4, 'andika.nb', '$2y$10$lIj1R39XaEjTpEFhLhFMX.iJsoP1gl0oCwENY/.RdFC9FXz5hExr6', 'user', '2025-07-21 07:49:55'),
(5, 'anggi.budi', '$2y$10$d43.FFZzXyolEVTvj4WG0OMJRBX129kHsLVVtf6mfxxNaaysDrqje', 'user', '2025-07-21 07:51:43'),
(6, 'anita.desmarini', '$2y$10$0Oh4h.2yFoxWYQDQMSdWYecFmzcWOO1wLXhXyIPqtAJcGQjmdaYGS', 'user', '2025-07-21 07:52:22'),
(7, 'anneoktaviaandriyani', '$2y$10$AwnjvQ8U6eUFM1w2GfuxaeQLUEvLFZhRR7wDc8PxUryCyDvGImWai', 'user', '2025-07-21 08:19:31'),
(8, 'aprilia', '$2y$10$ZIgXt5loJ58Tbn0oRg6qX.agq2pNeJu7l0pi5tvcE/eyi93OWvpPu', 'user', '2025-07-21 08:19:58'),
(9, 'arirusmasari', '$2y$10$AYdPSqmM..zrJ824GoC8W.2ZJrbWwgSlJ.B4LeZ84AbzAbFRJ3Ewm', 'admin', '2025-07-21 08:20:19'),
(10, 'baguspriosambodo', '$2y$10$OS6KM6hjT48wUbNqs5GPSe03WVGhr9FN4xQR4b8f3IyBEHVm35r/.', 'user', '2025-07-21 08:20:37'),
(11, 'belinda.putri', '$2y$10$dtU19vKPhu8yF/NRIblE1upLY1qYoViy8BL//mZ5KMYT5aW74AYvC', 'user', '2025-07-21 08:20:55'),
(12, 'darul', '$2y$10$lds3T6yCbta4lka8Yn3EpuGjLptei744kJqoAWUOXbMGxhgGgGVcS', 'user', '2025-07-21 08:21:19'),
(13, 'dian_wurya', '$2y$10$wPAzBDS62U7YroMG5qnapultt8eQDm4.vVVXnmBM1ujqYvrOOi0Ui', 'user', '2025-07-21 08:21:43'),
(14, 'dini.arianindy', '$2y$10$6lrISTPCZIgMc/USUYAoIuOp43ZMzV4/W5GUumrtJE2Bbcm8VZryu', 'user', '2025-07-21 08:22:03'),
(15, 'hadys', '$2y$10$zG7JGq97b0sdRr1LSbT0vuVaFaqoM8Tj3BxNOBXNQaUY/.vALQk2S', 'admin', '2025-07-21 08:22:29'),
(16, 'edi.kurniawan', '$2y$10$3Dv752sfoA70Qs/OsBBc1.tHtcGnrWHd/tTQWB8AjSNFaWoqYDxQK', 'user', '2025-07-21 08:22:56'),
(17, 'erika.hs', '$2y$10$pn75VSz.Si/zNcZtpBJIceQMgg7ykCTf1AKfAXCI6f4lW95Lu.X.m', 'user', '2025-07-21 08:23:21'),
(18, 'erikasanti', '$2y$10$cmzPQBa9dHBKuBH33hYNFejID8S20V54MKSA/Q7bNu7JByRtz76JW', 'user', '2025-07-21 08:23:45'),
(19, 'erwan.jafrilda', '$2y$10$eSj0yksPnxCM8GuoZsYub.zMviH3XygJcw1gGW4OG0DClyqN9qway', 'user', '2025-07-21 08:24:04'),
(20, 'evie', '$2y$10$giGVem6FzIqoUki6ML3STO6Js3oowJgtTgH9fYLF7.oeMVSo5bNay', 'admin', '2025-07-21 08:24:26'),
(21, 'fahroni.agustarita', '$2y$10$f5dAc2GxKcJR5qphhJH6Sea9imj3a86sYkvUB1nPWKlIuHNLdaeU.', 'user', '2025-07-21 08:24:49'),
(22, 'gun.nugraha', '$2y$10$42k.moG4iwfbE9liEtMdsucBmWNo4CTO8d1z0kHJZUnurU64bajFS', 'admin', '2025-07-21 08:25:06'),
(23, 'habnihamara', '$2y$10$D5psu1Z.cL9xjJzm6DM2be93Yi6u0RzqdCTk6LbcKWTvmCWl6RZJy', 'user', '2025-07-21 08:25:22'),
(24, 'ican', '$2y$10$eiHXJQWcsObazJK6RriuoODVdtux94PjH5uOE.Q7EW96oq.y.TJc2', 'user', '2025-07-21 08:25:41'),
(25, 'indrak', '$2y$10$xQwfUVmM.BKHyf0P26nDy.qobs2rkkC79vwY2q0nqLP.KAdIJq6G.', 'user', '2025-07-21 08:26:00'),
(26, 'kaisar.samudra', '$2y$10$4YvJTayOMhla89XwZHqhY.H1rrPtg687cTmcDR3ExuqtM/W6a2aMu', 'user', '2025-07-21 08:26:24'),
(27, 'merthap', '$2y$10$CE8AR2Q0WkdSuum7dOL5jeXIkYEzABiKgE8QXoq.KiagD0URuONvu', 'user', '2025-07-21 08:26:44'),
(28, 'vicky.lukito', '$2y$10$ds0LJ5X5/h0qhUfbZMmRgexy9vAzdnDcpegLjGgxN.XtqK0EC0jAq', 'user', '2025-07-21 08:28:48'),
(29, 'rafiqo.ardi', '$2y$10$DcJcfDoQv2xBY8ujbznqGuPjKpezBnyIgBee4Npsm5fCO9IJ9xhS2', 'user', '2025-07-21 08:29:13'),
(30, 'risdianto', '$2y$10$zf7Wp7p7TRspCpjgFCmdjek19/LhVznt0lxYWlXlpjyzniSr8B1ve', 'user', '2025-07-21 08:29:31'),
(31, 'abdi.utama', '$2y$10$4.A0TRNYQ3nO.FPMK4bKAOhdh7BRp2Oy6BGCDSJCWU5h2T.PAbOD2', 'user', '2025-07-21 08:29:50'),
(32, 'santi.aritonang', '$2y$10$dUXLWGqZIrOFfLK2EJI/X.GFZd13y.fw196J84iZD7kscyVpcIeSC', 'user', '2025-07-21 08:30:07'),
(33, 'sari.pratiwi', '$2y$10$JX6teseiu47KB3PH5AinZ.MDdLxRVZqKGp26IqThwK3tU9WqxyweK', 'user', '2025-07-21 08:30:31'),
(34, 'sasma', '$2y$10$WjLxcBbkPd8yoQF0JUCyuuX03WzQd99pxOaMfgx96E7zjMzlTUmX.', 'user', '2025-07-21 08:30:52'),
(35, 'virgowinatha', '$2y$10$DDPWZF1RTRvMTr3CWez4N.nwobWrSpaWRZZQGBM3EHw5J4zB7Ytpe', 'user', '2025-07-21 08:31:09'),
(36, 'viona.rahma', '$2y$10$M1Yi2LSrbXUgAaPX6i7WF.QtK6LDv8ht6q4CParFReafxvupcpdAa', 'user', '2025-07-21 08:31:26'),
(37, 'wasilawati', '$2y$10$pK6T/B0mTMZWQj.eUkGE1upKqTW3V2IEW5ArADdTXYC4Y1Eqme6B.', 'user', '2025-07-21 08:31:43'),
(38, 'coba', '$2y$10$i/hAyJvTcJo0fF3VWtx7IOuJUC1jQrEfZ6AVG9z5fnCSiI3t1366O', 'user', '2025-07-23 04:09:30'),
(39, 'adheet', '$2y$10$oDit8O36OnY14UuBKemu3eeQKxa2/wCY0.MgUjbriDTjTP.kRqWi6', 'user', '2025-07-24 03:49:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `konten`
--
ALTER TABLE `konten`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profil`
--
ALTER TABLE `profil`
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `konten`
--
ALTER TABLE `konten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
