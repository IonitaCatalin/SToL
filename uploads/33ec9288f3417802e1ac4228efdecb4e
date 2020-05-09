-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2020 at 03:35 PM
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
  `id` varchar(200) NOT NULL,
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
('2d83d91251e51c642e8d17388b27accc', 'ceva@altcevaaaa2ssa.com', 'unuser223', 'unuser', '2020-05-03 12:32:40', '2020-05-03 12:32:40'),
('5e1dd85b9454f9180e494d402137da92', 'ceva@altceva2a.com', 'unuser2', 'unuser', '2020-05-03 12:25:36', '2020-05-03 12:25:36'),
('753b57edd6a31482b1615c1ad992f274', 'ceva@altcevaaa2ssa.com', 'unuser22', 'unuser', '2020-05-03 12:27:39', '2020-05-03 12:27:39'),
('91e4ba71454dbc6f6043e14121f7bcec', 'ceva@altcev2a.com', 'unuser', 'unuser', '2020-05-03 11:59:22', '2020-05-03 11:59:22');

-- --------------------------------------------------------

--
-- Table structure for table `dropbox_service`
--

CREATE TABLE `dropbox_service` (
  `user_id` varchar(200) NOT NULL,
  `access_token` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `item_id` varchar(200) NOT NULL,
  `folder_id` varchar(200) NOT NULL,
  `mode` varchar(10) NOT NULL,
  `from_service` varchar(20) NOT NULL,
  `file_service_id` varchar(200) NOT NULL,
  `fragments_id` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `item_id` varchar(200) NOT NULL,
  `parent_id` varchar(200) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `item_id`, `parent_id`, `name`, `created_at`) VALUES
(1, '98aa51fcd877ebcc0f3daf71680b081a', NULL, 'root', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `fragments`
--

CREATE TABLE `fragments` (
  `fragments_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `onedrive_id` int(11) DEFAULT NULL,
  `dropbox_id` int(11) DEFAULT NULL,
  `googledrive_id` int(11) DEFAULT NULL,
  `onedrive_offset` varchar(80) NOT NULL,
  `googledrive_offset` varchar(80) NOT NULL,
  `dorpbox_offset` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `googledrive_service`
--

CREATE TABLE `googledrive_service` (
  `user_id` varchar(200) NOT NULL,
  `access_token` varchar(200) NOT NULL,
  `refresh_token` varchar(200) DEFAULT NULL,
  `expires_in` int(11) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `user_id` varchar(200) NOT NULL,
  `item_id` varchar(200) NOT NULL,
  `content_type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`user_id`, `item_id`, `content_type`) VALUES
('2d83d91251e51c642e8d17388b27accc', '98aa51fcd877ebcc0f3daf71680b081a', 'folder'),
('753b57edd6a31482b1615c1ad992f274', 'c4458bd9eeb79d933cca6be82bb73434', 'folder');

-- --------------------------------------------------------

--
-- Table structure for table `onedrive_service`
--

CREATE TABLE `onedrive_service` (
  `user_id` varchar(200) NOT NULL,
  `access_token` varchar(1500) NOT NULL,
  `refresh_token` varchar(1500) NOT NULL,
  `expires_in` int(11) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fragments_id` (`fragments_id`),
  ADD KEY `fk_folder_id` (`folder_id`),
  ADD KEY `fk_files_item_id` (`item_id`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_id` (`item_id`);

--
-- Indexes for table `fragments`
--
ALTER TABLE `fragments`
  ADD PRIMARY KEY (`fragments_id`);

--
-- Indexes for table `googledrive_service`
--
ALTER TABLE `googledrive_service`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fk_user _id` (`user_id`);

--
-- Indexes for table `onedrive_service`
--
ALTER TABLE `onedrive_service`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fragments`
--
ALTER TABLE `fragments`
  MODIFY `fragments_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dropbox_service`
--
ALTER TABLE `dropbox_service`
  ADD CONSTRAINT `fk_accounts_id` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_files_item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_folder_id` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `folders`
--
ALTER TABLE `folders`
  ADD CONSTRAINT `fk_item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `googledrive_service`
--
ALTER TABLE `googledrive_service`
  ADD CONSTRAINT `fk_account_id` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_user _id` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `onedrive_service`
--
ALTER TABLE `onedrive_service`
  ADD CONSTRAINT `fk_onedrive_user_id` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
