-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2020 at 09:35 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stol_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `email`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'catalin_talen@2020.com', 'KaTaLiN', 'parola', '2020-04-04 08:32:20', '2020-04-04 08:32:20'),
(2, 'harambe@cincinatti.zoo', 'Harambe', 'banane', '2020-04-04 08:36:45', '2020-04-04 08:36:45'),
(3, 'enteremail@email.email', 'Username', 'Password', '2020-04-04 08:38:06', '2020-04-04 08:38:06'),
(4, 'test4@test.com', 'name4', 'password4', '2020-04-04 08:51:23', '2020-04-04 08:51:23'),
(5, 'test4@test.com', 'name4', 'esfefsef', '2020-04-04 08:55:43', '2020-04-04 08:55:43'),
(6, 'dwandnbaw@oop.com', 'rdgdr', 'hjkjhk', '2020-04-04 09:12:26', '2020-04-04 09:12:26'),
(7, 'dwandnbaw@oop.com', 'rdgdr', 'hjkjhk', '2020-04-04 09:12:31', '2020-04-04 09:12:31'),
(8, 'ionita.catalin2000@gmail.com', 'asdasd', 'asdasd', '2020-04-06 18:25:50', '2020-04-06 18:25:50'),
(9, 'sddf@gmail.com', '<script>console.log(\"Test\")</script>', 'asdasdas', '2020-04-06 18:55:13', '2020-04-06 18:55:13'),
(10, 'asdasd@assdasdas.com', 'asdasdasd', 'asdasdasda', '2020-04-06 19:51:31', '2020-04-06 19:51:31'),
(11, 'unmictest@yahoo.com', 'test', 'tet', '2020-04-06 19:51:46', '2020-04-06 19:51:46'),
(12, 'alttest22@gmail.com', 'asdasdasdsad', 'asdasdasdad', '2020-04-06 20:09:21', '2020-04-06 20:09:21'),
(13, 'alttest2@gmail.com', 'Username', 'Parola', '2020-04-06 20:18:41', '2020-04-06 20:18:41'),
(14, 'asdasd@isasd', 'adasdasd', 'aasdasdasd', '2020-04-06 20:19:30', '2020-04-06 20:19:30'),
(15, 'asdsad@asdasd', 'asdasdasd', 'asdadadasd', '2020-04-06 20:19:46', '2020-04-06 20:19:46'),
(16, 'asdasdas@asdasd', 'asdasdasd', 'asdasdadssad', '2020-04-06 20:27:27', '2020-04-06 20:27:27'),
(17, 'alttest@gmail.com', 'cevausername', 'cevaparola', '2020-04-07 07:21:54', '2020-04-07 07:21:54'),
(18, 'asdasd@asdsad', 'asdasdasd', 'asda', '2020-04-07 07:26:15', '2020-04-07 07:26:15'),
(19, 'ceva@gmail.com', 'adsad', 'asda', '2020-04-07 08:19:17', '2020-04-07 08:19:17'),
(20, 'asdda@yahoo.uk', 'jfaskljfaskjdklas', 'asdasdasda', '2020-04-07 14:20:06', '2020-04-07 14:20:06'),
(21, 'masnhds@yahoo.c', 'asda', 'asdasd', '2020-04-07 14:23:44', '2020-04-07 14:23:44'),
(23, 'test@test.com', 'testify', 'test', '2020-04-07 17:19:52', '2020-04-07 17:19:52'),
(24, 'gigi@balta.com', 'gigibalta', 'gigibalta', '2020-04-11 08:06:15', '2020-04-11 08:06:15');

-- --------------------------------------------------------

--
-- Table structure for table `onedrive_service`
--

CREATE TABLE `onedrive_service` (
  `user_id` int(11) NOT NULL,
  `onedrive_access_token` varchar(200) NOT NULL,
  `onedrive_refresh_token` varchar(200) NOT NULL,
  `onedrive_expires_in` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `onedrive_service`
--

INSERT INTO `onedrive_service` (`user_id`, `onedrive_access_token`, `onedrive_refresh_token`, `onedrive_expires_in`) VALUES
(23, 'EwBoA8l6BAAUO9chh8cJscQLmU+LSWpbnr0vmwwAAYDBp487ZwYofuxRGSP8mvh84WU8t/5/fmuumkE8zmgNddKKO254DAwHxsBA097ZouODrsXeaRs6Iwjk0It99TX3qlGDqpiKinLPHbx3OPEwM8wgn46x0kGD3pWIaordpXOIJHHpLA1VuJ7dnviN9EDS5n/BOSMs', 'MCTVS7UYvsCwYz6SdJvSTFmSnrLQTDyAVhlmQRGJHF07Z0mvW1dkrJdaBjYJAYoRYdEIR*iJj5qfkdDBBPWkORteXnJPk*7ElcAcmVA3wIHkpW3VwdauNAr0AQsMwuZ9SClwCELr2FKOF1kdHTQpK4fmKGQ68TZVq*ZhyXvH*yCYCtOAooOgn*Yc5dBY3fKNl!Ea8RiF', 3600);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `onedrive_service`
--
ALTER TABLE `onedrive_service`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `onedrive_service`
--
ALTER TABLE `onedrive_service`
  ADD CONSTRAINT `fk_acccounts` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
