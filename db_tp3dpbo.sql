-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2024 at 09:20 AM
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
-- Database: `db_tp3dpbo`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `location`, `photo`) VALUES
(1, 'JYP Entertainment', ' Gangdong, Seoul', 'JYP.jpg'),
(2, 'SM Entertainment', 'Seongdong-gu, Seoul', 'SM.jpg'),
(3, 'Starship Entertainment', 'Myeongdong, Seoul', 'Starship_Entertainment_Logo.jpg'),
(4, 'Cube Entertainment', 'Seoungsu, Seoul', 'cube.jpg'),
(5, 'YG Entertainment', ' Mapo-gu, Seoul', 'YG.jpg'),
(20, 'Hybe Entertainment', 'Yongsan, Seoul ', 'hybe.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `companies_id` int(11) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `leader` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`id`, `name`, `companies_id`, `logo`, `leader`) VALUES
(1, 'Twice', 1, 'twice.jpg', 'Park Jihyo'),
(2, 'Itzy', 1, 'itzy.jpg', 'Hwang Yeji'),
(3, 'Nmixx', 1, 'nmixx.jpg', 'Oh Haewon'),
(4, 'Ive', 3, 'ive.jpg', 'Ahn Yujin'),
(5, 'Aespa', 2, 'aespa.jpg', 'Karina'),
(11, 'G-Idle', 4, 'gidle.jpg', 'Jeon Soyeonn'),
(18, 'BlackPink', 5, 'blackpink.jpg', 'Jisoo');

-- --------------------------------------------------------

--
-- Table structure for table `idols`
--

CREATE TABLE `idols` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `group_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `idols`
--

INSERT INTO `idols` (`id`, `name`, `photo`, `group_id`, `position_id`, `age`) VALUES
(27, 'Ning Yizhuo', 'ningning.jpg', 5, 1, 21),
(28, 'Myoui Mina', 'mina.jpg', 1, 2, 27),
(29, 'Ahn Yujin', 'yujin.jpg', 4, 5, 20),
(30, 'Choi Ji-su', 'lia.jpg', 2, 1, 23),
(31, 'Minnie Nicha Yontararak', 'minnie.jpg', 11, 1, 26),
(32, 'Oh Haewon', 'haewon.jpg', 3, 1, 21),
(35, 'Bae Jin Sol', 'bae.jpg', 3, 6, 19),
(36, 'Naoi Rei', 'rei.jpg', 4, 3, 20),
(40, 'Chou Tzuyu', 'tzuyu.jpg', 1, 7, 24);

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `name`) VALUES
(1, 'Main Vocalist'),
(2, 'Lead Vocalist\r\n'),
(3, 'Main Rapper'),
(4, 'Sub Rapper'),
(5, 'Main Dancer'),
(6, 'Lead Dancer'),
(7, 'Visual');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companies_id` (`companies_id`);

--
-- Indexes for table `idols`
--
ALTER TABLE `idols`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `idols`
--
ALTER TABLE `idols`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group`
--
ALTER TABLE `group`
  ADD CONSTRAINT `group_ibfk_1` FOREIGN KEY (`companies_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `idols`
--
ALTER TABLE `idols`
  ADD CONSTRAINT `fk_group_id` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `idols_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`),
  ADD CONSTRAINT `idols_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
