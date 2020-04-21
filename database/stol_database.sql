-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2020 at 12:20 AM
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
(25, 'abcdef@yahoo.com', 'abcdef', 'abcdef', '2020-04-13 08:54:46', '2020-04-13 08:54:46'),
(26, 'testify@test.com', 'testify', 'testify', '2020-04-21 05:33:24', '2020-04-21 05:33:24');

-- --------------------------------------------------------

--
-- Table structure for table `dropbox_service`
--

CREATE TABLE `dropbox_service` (
  `user_id` int(11) NOT NULL,
  `access_token` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dropbox_service`
--

INSERT INTO `dropbox_service` (`user_id`, `access_token`) VALUES
(26, 'RfMDs1XUrTAAAAAAAAAAKUUx3zx8ZMhatV4JQKruaMSskkJp0-EElTWu8PniMaPw');

-- --------------------------------------------------------

--
-- Table structure for table `googledrive_service`
--

CREATE TABLE `googledrive_service` (
  `user_id` int(11) NOT NULL,
  `access_token` varchar(200) NOT NULL,
  `refresh_token` varchar(200) DEFAULT NULL,
  `expires_in` int(11) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `googledrive_service`
--

INSERT INTO `googledrive_service` (`user_id`, `access_token`, `refresh_token`, `expires_in`, `generated_at`) VALUES
(26, 'ya29.a0Ae4lvC3-fhGbWMeMTth2xUihqZWDNK5IKzuZIMNu9BoWf-NDd7pQKUwLYqlu2Zirduc9_RHqk8JxJfpfm__-mESZ6c2icLcRu9hAxO3h5lJ5kAbTQ3yxVuHPvFjsFUiUzOGtNZqCEnXyBiY1dd836oi81C1Hu9Ph6LE', '1//03jaSXR4tY83WCgYIARAAGAMSNwF-L9IrhRCoEUYqgnHbgSYnyKtb-ukti6TyeqL_0pS6hRrFyqPH6Re8X1MR6Mzmzy1jY_2p7cU', 3599, '2020-04-21 15:51:14');

-- --------------------------------------------------------

--
-- Table structure for table `onedrive_service`
--

CREATE TABLE `onedrive_service` (
  `user_id` int(11) NOT NULL,
  `access_token` varchar(200) NOT NULL,
  `refresh_token` varchar(200) NOT NULL,
  `expires_in` int(11) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `onedrive_service`
--

INSERT INTO `onedrive_service` (`user_id`, `access_token`, `refresh_token`, `expires_in`, `generated_at`) VALUES
(26, 'EwCAA8l6BAAUO9chh8cJscQLmU+LSWpbnr0vmwwAAfZw5Ru90DZHNyw6J8e2sO1NPf8GGMolUTLA1Zv3YoMbsiM990C13dZSzpblMAP8eZYQS2PRYIfdZV1nwfggul00zptyFUxQFVOLch4v3GLP6c9aBD7Q5O5bVqRPIUy71W9ile/uCVUsgrVNAhY3PSsVjHIfF11G', 'MCQI4LeoJ!YRtNBZO5ZGeuutA2lx1OsK*NPuqQ!g1tZRTaR!aKuXnx6!Fs84K36lr8FLynfTDqteSy5y43YR6CNP7tGHAR1tjiDUEuSyi*kJ4ohc3y2aQjWMaDwV!UORBOHdHzcR1rSfJdTZq19KRz5R19P5FS04fp7WOsPeBvB2VrhElLVPVF*UzWauKAvio4ybgJaG', 3600, '2020-04-21 15:49:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dropbox_service`
--
ALTER TABLE `dropbox_service`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `googledrive_service`
--
ALTER TABLE `googledrive_service`
  ADD PRIMARY KEY (`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dropbox_service`
--
ALTER TABLE `dropbox_service`
  ADD CONSTRAINT `fk_acccounts_dropbox` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `googledrive_service`
--
ALTER TABLE `googledrive_service`
  ADD CONSTRAINT `fk_accounts_google` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `onedrive_service`
--
ALTER TABLE `onedrive_service`
  ADD CONSTRAINT `fk_acccounts_onedrive` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
