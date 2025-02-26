-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Feb 2025 pada 19.40
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

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
-- Struktur dari tabel `t_beasiswa`
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
-- Dumping data untuk tabel `t_beasiswa`
--

INSERT INTO `t_beasiswa` (`idBeasiswa`, `namaBeasiswa`, `visiBeasiswa`, `misiBeasiswa`, `motoBeasiswa`, `deskripsiBeasiswa`, `benefitBeasiswa`, `gambarBeasiswa`) VALUES
(1, 'AAAAA', 'AAAAA', 'AAAAA', 'bersatu', 'AAAAA', '[\"AAAAA\"]', 'lpdp-1.jpg'),
(15, 'BBBBB', 'BBBBB', 'BBBBB', 'berdua', 'BBBBB', '[\"BBBBB\"]', 'Desain tanpa judul (3).png'),
(18, 'DDDDD', 'DDDDD', 'DDDDD', 'DDDDD', 'DDDDD', '[\"DDDDD\",\"DDDDD\",\"DDDDD\",\"DDDDD\"]', 'images_9217931699517483718.jpg'),
(19, 'CCCCC', 'CCCCC', 'CCCCC', 'CCCCC', 'CCCCC', '[\"CCCCC\",\"CCCCC\",\"CCCCC\"]', 'UML Diagram-Use Case (1).png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_program`
--

CREATE TABLE `t_program` (
  `idProgram` int(11) NOT NULL,
  `namaProgram` varchar(255) NOT NULL,
  `paketProgram` varchar(255) NOT NULL,
  `priceProgram` varchar(255) NOT NULL,
  `waktuProgram` varchar(255) NOT NULL,
  `benefitProgram` varchar(255) NOT NULL,
  `deskripsiProgram` text NOT NULL,
  `gambarProgram` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_program`
--

INSERT INTO `t_program` (`idProgram`, `namaProgram`, `paketProgram`, `priceProgram`, `waktuProgram`, `benefitProgram`, `deskripsiProgram`, `gambarProgram`) VALUES
(1, 'AAAAA', '[\"AAAAA\",\"BBBBB\"]', '[\"AAAAA\",\"BBBBB\"]', '[[\"10.30 WITA\",\"13.00 WITA\"],[\"15.00 WITA\",\"17.00 WITA\"]]', '[[\"AAAAA\",\"AAAAA\"],[\"ABCDE\",\"ABCDE\"]]', 'AAAAA', 'lpdp-1.jpg'),
(2, 'BBBBB', '[\"BBBBB\",\"BBBBB\"]', '[\"BBBBB\",\"BBBBB\"]', '[[\"BBBBB\",\"BBBBB\",\"BBBBB\",\"BBBBB\"],[\"BBBBB\",\"BBBBB\",\"BBBBB\",\"BBBBB\"]]', '[[\"BBBBB\",\"BBBBB\",\"BBBBB\",\"BBBBB\"],[\"BBBBB\",\"BBBBB\",\"BBBBB\",\"BBBBB\"]]', 'BBBBB', 'Desain tanpa judul (3).png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_user`
--

CREATE TABLE `t_user` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_user`
--

INSERT INTO `t_user` (`id`, `email`, `fullName`, `password`) VALUES
(1, 'insyanulamal@gmail.com', 'Insyanul Amal', '$2y$10$5GUjsKSnbiCZ8uvRMx0ZiuIaX1cro3q8fsG6WqFRsT.E4qdvy1svu');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `t_beasiswa`
--
ALTER TABLE `t_beasiswa`
  ADD PRIMARY KEY (`idBeasiswa`);

--
-- Indeks untuk tabel `t_program`
--
ALTER TABLE `t_program`
  ADD PRIMARY KEY (`idProgram`);

--
-- Indeks untuk tabel `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `t_beasiswa`
--
ALTER TABLE `t_beasiswa`
  MODIFY `idBeasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `t_program`
--
ALTER TABLE `t_program`
  MODIFY `idProgram` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
