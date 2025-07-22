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
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `username`, `password`, `role`) VALUES
(18, '230305021', '3d176c058bbef786b1fc706df5b303ea', 'admin'),
(22, '230305018', '1a755086aa374ad23fe4303e078c7193', 'user'),
(23, '230305023', '3fba47fda6a80b1ce36628913fd8e3d9', 'user'),
(24, '230305022', '565e9139b005cf616b3e94187827ae29', 'user'),
(25, '230305028', 'df09ea616dda030dbe72448b332d8a36', 'user'),
(26, '230305019', 'e96057fca75b48c1e776b6d6d96967a1', 'user'),
(27, '230305029', 'e62c8156a301fe00d36763b477ef2e82', 'user'),
(28, '230305001', 'd4b9b96ba802507e786fe0da0ae17540', 'user'),
(29, '230305002', '624ade9962636754cfef57701dddceb7', 'user'),
(30, '230305003', 'f53e42b977387b87a7cc6a59b533b885', 'user'),
(31, '230305004', '530ba61402fda26c01630dc7700c9001', 'user'),
(32, '230305005', '33460639524ba1aa96bf7313d67071ac', 'user'),
(33, '230305006', 'ddfc9e4b1df2704e555d6a5965ae3c7e', 'user'),
(34, '230305007', 'b6523d07432a92baf7bedb717fb67070', 'user'),
(35, '230305024', '61490c356a283185ee70d83d3db815e4', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
