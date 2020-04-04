-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2020 at 12:48 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sezatoare`
--

-- --------------------------------------------------------

--
-- Table structure for table `mesaje`
--

CREATE TABLE `mesaje` (
  `id` int(11) NOT NULL,
  `utilizator` varchar(255) NOT NULL,
  `mesaj` varchar(2048) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mesaje`
--

INSERT INTO `mesaje` (`id`, `utilizator`, `mesaj`, `created_at`, `updated_at`) VALUES
(1, 'nume123', 'salut nume111, ce mai faci?', '2020-03-31 22:04:52', '2020-03-31 22:04:52'),
(18, 'User111', 'glumet esti tu', '2020-03-31 22:52:51', '2020-03-31 22:52:51'),
(20, 'User111', 'creste pate-ul', '2020-03-31 22:53:17', '2020-03-31 22:53:17'),
(56, 'user87', 'se face painea', '2020-04-03 22:21:59', '2020-04-03 22:21:59'),
(57, 'hatr', 'snickers22', '2020-04-03 22:43:49', '2020-04-03 22:43:49'),
(59, 'user331', 'ghj', '2020-04-04 08:34:32', '2020-04-04 08:34:32'),
(61, 'user331', 'hjkghg', '2020-04-04 11:50:36', '2020-04-04 11:55:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mesaje`
--
ALTER TABLE `mesaje`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mesaje`
--
ALTER TABLE `mesaje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
