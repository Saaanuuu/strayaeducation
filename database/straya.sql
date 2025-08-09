-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 09, 2025 at 03:39 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `straya`
--

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `idPendaftaran` int(11) NOT NULL,
  `idProgram` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `waktuProgram` varchar(255) NOT NULL,
  `paymentMethod` varchar(50) NOT NULL,
  `tanggalDaftar` datetime NOT NULL,
  `statusPendaftaran` varchar(50) NOT NULL DEFAULT '-',
  `buktiBayar` varchar(255) DEFAULT NULL,
  `scoreBefore` varchar(10) DEFAULT NULL,
  `score` varchar(50) NOT NULL DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftaran`
--

INSERT INTO `pendaftaran` (`idPendaftaran`, `idProgram`, `idUser`, `fullName`, `email`, `phone`, `address`, `waktuProgram`, `paymentMethod`, `tanggalDaftar`, `statusPendaftaran`, `buktiBayar`, `scoreBefore`, `score`) VALUES
(49, 2, 10, 'arpel', 'arpel@gmail.com', '123', 'AAA', '10.30 WITA', 'QRIS', '2025-01-01 06:15:56', 'Diterima', NULL, '8.8', '1.1'),
(51, 2, 6, 'cici', 'cici@gmail.com', '321', 'FAZA', '11.00 WITA', 'Transfer Bank', '2025-02-01 00:27:34', 'Diterima', NULL, NULL, '-'),
(52, 2, 6, 'cici', 'cici@gmail.com', '123', '1', '16.00 WITA', 'Transfer Bank', '2025-03-01 02:29:01', 'Diterima', NULL, NULL, '-'),
(53, 2, 6, 'cici', 'cici@gmail.com', '123', 'S', '14.00 WITA', 'Transfer Bank', '2025-04-21 02:52:16', 'Diterima', NULL, NULL, '-'),
(54, 1, 6, 'cici', 'cici@gmail.com', '123', 'W', '13.00 WITA', 'QRIS', '2025-05-01 02:53:50', 'Diterima', NULL, '599', '-'),
(58, 2, 6, 'cici', 'cici@gmail.com', '123', 'BAD', '10.30 WITA', 'QRIS', '2025-06-01 01:28:19', 'Diterima', NULL, NULL, '-'),
(59, 2, 10, 'arpel', 'arpel@gmail.com', '123', 'ARPEL', '10.30 WITA', 'Transfer Bank', '2025-07-01 01:42:53', 'Diterima', NULL, '9', '7.9'),
(60, 1, 10, 'arpel', 'arpel@gmail.com', '085967970734', 'Jalan Sultan Salahuddin Gang Kamboja No 6 Link Batudawe', '17.00 WITA', 'Transfer Bank', '2025-08-01 01:51:04', 'Diterima', NULL, '200', '600'),
(65, 2, 6, 'cici', 'cici@gmail.com', '123', 'SSS', '10.30 WITA', 'Transfer Bank', '2025-09-01 22:34:32', 'Diterima', NULL, NULL, '-'),
(66, 2, 6, 'cici', 'cici@gmail.com', '123', 'DDD', '11.00 WITA', 'Transfer Bank', '2025-10-01 22:34:44', 'Diterima', NULL, NULL, '-'),
(67, 1, 6, 'cici', 'cici@gmail.com', '123', 'PPP', '13.00 WITA', 'Transfer Bank', '2025-11-01 22:35:32', 'Diterima', NULL, '518', '578'),
(68, 1, 6, 'cici', 'cici@gmail.com', '123', 'PPP', '15.00 WITA', 'Transfer Bank', '2025-12-01 22:36:40', 'Diterima', NULL, '500', '510'),
(69, 2, 6, 'cici', 'cici@gmail.com', '123', 'QQQ', '16.00 WITA', 'Transfer Bank', '2025-04-24 00:14:32', 'Diterima', NULL, NULL, '-'),
(72, 2, 6, 'cici', 'cici@gmail.com', '123', 'VVV', '10.30 WITA', 'QRIS', '2025-05-14 20:50:56', '-', NULL, NULL, '-');

-- --------------------------------------------------------

--
-- Table structure for table `t_beasiswa`
--

CREATE TABLE `t_beasiswa` (
  `idBeasiswa` int(11) NOT NULL,
  `namaBeasiswa` varchar(255) NOT NULL,
  `visiBeasiswa` text NOT NULL,
  `misiBeasiswa` text NOT NULL,
  `motoBeasiswa` text NOT NULL,
  `deskripsiBeasiswa` text NOT NULL,
  `benefitBeasiswa` text NOT NULL,
  `gambarBeasiswa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_beasiswa`
--

INSERT INTO `t_beasiswa` (`idBeasiswa`, `namaBeasiswa`, `visiBeasiswa`, `misiBeasiswa`, `motoBeasiswa`, `deskripsiBeasiswa`, `benefitBeasiswa`, `gambarBeasiswa`) VALUES
(1, 'MEXT (Monbukagakusho Scolarship)', '-', '-', '-', 'Beasiswa ini merupakan beasiswa dari pemerintah Jepang untuk berbagai jenjang (S1, S2, S3 dan riset). Beasiswa ini dapat diperoleh melalui jalur Kedutaan Besar Jepang atau rekomendasi universitas di Jepang.', '[\"FULLY FUNDED\"]', 'MEXT.jpeg'),
(15, 'Romanian Goverment Scholarship', 'Menjadi jembatan pendidikan global yang mempererat hubungan antarbangsa melalui pembelajaran, pertukaran budaya, dan pengembangan sumber daya manusia yang unggul di kancah internasional.', 'Mempromosikan bahasa dan budaya Rumania, serta meningkatkan hubungan internasional melalui pendidikan', 'Bridging Nations through Knowledge and Culture (Menghubungkan Bangsa lewat Ilmu dan Budaya)', 'Beasiswa dari pemerintah Romania yang diberikan kepada mahasiswa imternasional untuk studi S1, S2 dan S3 di universitas tertentu di Romania. Program ini bertujuan mendorong pertukaran akademik dan budaya serta memperkuat kerja sama internasional', '[\"FULLY FUNDED\",\"Kursus Persiapan Bahasa Rumania\"]', 'romanian.jpeg'),
(18, 'AAS (Australia Awards Scholarship)', 'Mewujudkan masa depan yang berkelanjutan dan inklusif melalui investasi pada pendidikan dan kepemimpinan global.', 'Memberikan kesempatan pendidikan tinggi di Australia bagi individu berbakat dari negara mitra untuk mendukung pembangunan negara asal mereka.', 'Empowering Change, Connecting Nations (Memberdayakan Perubahan, Menghubungkan Bangsa)', 'Beasiswa dari pemerintah Australia yang ditujukan untuk mahasiswa dari negara-negara berkembang, salah satunya termasuk Indonesia. Beasiswa ini diperuntukkan bagi yang ingin menempuh studi S2 dan S3 di Australia. Program ini berfokus pada pengembangan SDM dan kepemimpinan dengan tujuan mendukung pembangunan di negara asal penerima beasiswa.', '[\"Biaya Kuliah Penuh (Full Tuition Fees)\",\"Transportasi\",\"Biaya Hidup\",\"Biaya Penelitian dan Tesis\"]', 'AAS.jpeg'),
(19, 'FULLBRIGHT SCHOLARSHIP', 'Mendorong saling pengertian antarbangsa melalui pertukaran pendidikan dan budaya, serta membentuk generasi pemimpin global yang berkontribusi pada dunia yang damai dan kolaboratif.', 'Menyediakan kesempatan pendidikan tinggi bagi individu berprestasi untuk belajar, meneliti, dan mengajar di Amerika Serikat.', 'Mutual Understanding, Global Impact (Saling Pengertian, Dampak Global)', 'Beasiswa ini merupakan beasiswa yang didanai oleh pemerintah Amerika Serikat dandiberikan kepada mahasiswa Internasional untuk melanjutkan studi ke jenang S2 dan S3 di AS. Program ini bertujuan untuk memperkuat hubungan akademik dan budaya antara AS dan negara asal penerima.', '[\"Biaya Kuliah Penuh (Full Tuition Fees)\",\"Tunjangan Hidup\",\"Orientasi Pra-Keberangkatan dan Setelah Tiba\"]', 'fullbright.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `t_program`
--

CREATE TABLE `t_program` (
  `idProgram` int(11) NOT NULL,
  `namaProgram` varchar(255) NOT NULL,
  `paketProgram` varchar(255) NOT NULL,
  `priceProgram` varchar(255) NOT NULL,
  `kuotaProgram` varchar(255) NOT NULL,
  `waktuProgram` varchar(255) NOT NULL,
  `benefitProgram` varchar(255) NOT NULL,
  `deskripsiProgram` text NOT NULL,
  `gambarProgram` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_program`
--

INSERT INTO `t_program` (`idProgram`, `namaProgram`, `paketProgram`, `priceProgram`, `kuotaProgram`, `waktuProgram`, `benefitProgram`, `deskripsiProgram`, `gambarProgram`) VALUES
(1, 'TOEFL Preparation', '[\"Regular ( 8 Pertemuan )\",\"Exclusive ( 24 Pertemuan )\"]', '[\"350.000\",\"900.000\"]', '[8,13]', '[[\"10.30 WITA\",\"13.00 WITA\"],[\"15.00 WITA\",\"17.00 WITA\"]]', '[[\"Meningkatkan skor toefl\",\"Meningkatkan kemampuan bahasa inggris\"],[\"Meningkatkan skor toefl\",\"Mengulang kelas hingga mencapai target level\"]]', 'Program persiapan TOEFL untuk membantu peserta mendapatkan skor terbaik dalam ujian akademik standar internasional.', 'program_680138eabc7b54.23761714.jpeg'),
(2, 'IELTS Preparation', '[\"Regular ( 8 Pertemuan )\",\"Exclusive ( 24 Pertemuan )\"]', '[\"450.000\",\"1.150.000\"]', '[14,10]', '[[\"10.30 WITA\",\"11.00 WITA\"],[\"14.00 WITA\",\"16.00 WITA\"]]', '[[\"Meningkatkan skor ielts\",\"Mengulang kelas hingga mencapai target level\"],[\"Meningkatkan skor ielts\",\"Mengulang kelas hingga mencapai target level\"]]', 'Kursus intensif untuk meningkatkan kemampuan dalam tes IELTS, baik untuk keperluan akademik maupun profesional', 'WhatsApp Image 2025-04-18 at 01.06.49.jpeg'),
(14, 'Speaking Class', '[\"Speaking Class\"]', '[\"Rp. 150.000\"]', '[\"20\"]', '[[\"10.30 WITA\"]]', '[[\"Keren\"]]', 'Speaking Class membantu peserta untuk belajar berbicara dalam bahasa inggris', 'program_68308b9f502753.30029464.png');

-- --------------------------------------------------------

--
-- Table structure for table `t_user`
--

CREATE TABLE `t_user` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`id`, `email`, `fullName`, `password`, `role`) VALUES
(1, 'fazaronce@gmail.com', 'Faza Alliya', '$2y$10$HOwiiJfp7SeVsguh/L/XC.MW2TlSd2C649EW2PjJfLpuGX/EbA3rK', '1'),
(6, 'cici@gmail.com', 'cici', '$2y$10$HOwiiJfp7SeVsguh/L/XC.MW2TlSd2C649EW2PjJfLpuGX/EbA3rK', '2'),
(10, 'arpel@gmail.com', 'arpel', '$2y$10$gQqnceUr84KBO0hw5gl.YuWMQn9iNSdTlwlW3ULARD5eet9znJ9NG', '2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`idPendaftaran`),
  ADD KEY `idProgram` (`idProgram`),
  ADD KEY `fk_idUser` (`idUser`);

--
-- Indexes for table `t_beasiswa`
--
ALTER TABLE `t_beasiswa`
  ADD PRIMARY KEY (`idBeasiswa`);

--
-- Indexes for table `t_program`
--
ALTER TABLE `t_program`
  ADD PRIMARY KEY (`idProgram`);

--
-- Indexes for table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `idPendaftaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `t_beasiswa`
--
ALTER TABLE `t_beasiswa`
  MODIFY `idBeasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `t_program`
--
ALTER TABLE `t_program`
  MODIFY `idProgram` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `fk_idUser` FOREIGN KEY (`idUser`) REFERENCES `t_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`idProgram`) REFERENCES `t_program` (`idProgram`),
  ADD CONSTRAINT `pendaftaran_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `t_user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
