-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 22, 2025 at 02:15 AM
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
-- Database: `db_mahasiswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mahasiswa`
--

CREATE TABLE `tbl_mahasiswa` (
  `idMhs` int NOT NULL,
  `npm` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_mahasiswa`
--

INSERT INTO `tbl_mahasiswa` (`idMhs`, `npm`, `nama`, `alamat`, `foto`) VALUES
(13, '230305021', 'AINUL LUTFI', 'DASAN LEKONG', '687e5db7d510b__MG_3008.JPG'),
(17, '230305018', 'AGIL ARYA', 'MASBAGEK', ''),
(18, '230305023', 'AHMAD YUSRIL AZIZ MAULANA', 'KALIJAGA', ''),
(19, '230305022', 'MOH. ANDRE SATRIAWAN', 'KALIJAGA TENGAH', ''),
(20, '230305028', 'M. FAQIH ALJUMHURI', 'KALIJAGA', ''),
(21, '230305019', 'ARIF SATRIAWAN', 'BERMIS - SELONG', ''),
(22, '230305029', 'MOH. ZAIDI', 'SEMAYA', ''),
(23, '230305001', 'ALDA AMELIA PUTRI', 'SUMBAWA', ''),
(24, '230305002', 'WINA AGUSTINA', 'DASAN LEKONG', ''),
(25, '230305003', 'RINDI ANTIKA', 'MUJUR - LOTENG', ''),
(26, '230305004', 'YULIA DWI WAHYUNI', 'KERUAK', ''),
(27, '230305005', 'MERY SEPTIA', 'LENTING - SAKRA', ''),
(28, '230305006', 'RIA APRILIA', 'PANCOR', ''),
(29, '230305007', 'LAELA FEBRIANA', 'PANCOR', ''),
(30, '230305024', 'BAIQ RAVIKA AULIA WIRANTI', 'RARANG', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  ADD PRIMARY KEY (`idMhs`),
  ADD UNIQUE KEY `npm` (`npm`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  MODIFY `idMhs` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
