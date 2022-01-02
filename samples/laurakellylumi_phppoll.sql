-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2022 at 08:44 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laurakellylumi_phppoll`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id_a` int(11) UNSIGNED NOT NULL,
  `id_q` int(11) UNSIGNED NOT NULL,
  `answer` int(2) NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `IP` varchar(20) COLLATE utf8mb4_estonian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_estonian_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id_a`, `id_q`, `answer`, `date`, `IP`) VALUES
(6, 2, 1, '2022-01-02 19:00:26', '::1'),
(7, 1, 1, '2022-01-02 19:11:23', '::1'),
(8, 4, 1, '2022-01-02 19:32:37', '::1'),
(9, 7, 2, '2022-01-02 20:36:26', '::1'),
(10, 12, 3, '2022-01-02 21:07:14', '::1'),
(11, 13, 2, '2022-01-02 21:08:35', '::1'),
(12, 15, 1, '2022-01-02 21:15:24', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id_q` int(11) UNSIGNED NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_estonian_ci NOT NULL,
  `answer_1` varchar(100) COLLATE utf8mb4_estonian_ci NOT NULL,
  `answer_2` varchar(100) COLLATE utf8mb4_estonian_ci NOT NULL,
  `answer_3` varchar(100) COLLATE utf8mb4_estonian_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_estonian_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id_q`, `question`, `answer_1`, `answer_2`, `answer_3`, `created`, `status`) VALUES
(1, 'Kes on parim õpetaja?', 'Marko', 'Miki', '', '2022-01-02 14:40:33', 0),
(2, 'Kes on Eesti president?', 'Alar', 'Ivar', '', '2022-01-02 14:44:40', 0),
(4, 'Küsimus?', 'Vastus', 'Vastus2', '', '2022-01-02 17:32:06', 0),
(7, 'Mis päev täna on?', 'Kolmapäev', 'Reede', 'Laupäev', '2022-01-02 18:35:59', 0),
(12, 'Mismis?', 'Jah', 'See', 'Kolmas', '2022-01-02 19:07:03', 0),
(13, 'Kes on parim õpetaja?', 'Vastus', 'Vastus4', '', '2022-01-02 19:08:23', 0),
(14, 'Küsimus?', 'Jah', 'Ivar', '', '2022-01-02 19:09:05', 0),
(15, 'Mis päev täna on?', 'Alar', 'Vastus2', '', '2022-01-02 19:09:16', 1),
(16, 'Mis päev täna on?', 'Jah', 'Miki', '', '2022-01-02 19:09:29', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id_a`),
  ADD KEY `id_q` (`id_q`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id_q`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id_a` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id_q` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`id_q`) REFERENCES `questions` (`id_q`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
