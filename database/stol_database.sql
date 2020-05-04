-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2020 at 07:58 PM
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
('c9526e3221d689b48c621d1babe0bb87', 'abcdef@yahoo.com', 'abcdef', 'abcdef', '2020-05-04 08:03:14', '2020-05-04 08:03:14');

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
  `name` varchar(200) NOT NULL,
  `mode` varchar(10) NOT NULL,
  `from_service` varchar(20) NOT NULL,
  `file_service_id` varchar(200) NOT NULL,
  `fragments_id` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `item_id`, `folder_id`, `name`, `mode`, `from_service`, `file_service_id`, `fragments_id`) VALUES
(1, '1', '67b6e87381a8fb18c96c7acca3b6c35d', 'file1', '', '', '', '1'),
(2, '2', 'b5908118157cff12d8d1f1ae6ed4c104', 'file2', '', '', '', '2'),
(3, '3', '0f6ead903e13eba64c624a45afba9184', 'file3', '', '', '', '3'),
(4, '4', 'd2617194116dea57dd6ca65498f01ee7', 'file4', '', '', '', '4');

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
(7, 'e86edca146bbefd773838a7e7955b521', NULL, 'root', '2020-05-04 08:03:14'),
(8, '67b6e87381a8fb18c96c7acca3b6c35d', 'e86edca146bbefd773838a7e7955b521', 'NewFolder1', '2020-05-04 08:09:14'),
(9, 'b5908118157cff12d8d1f1ae6ed4c104', 'e86edca146bbefd773838a7e7955b521', 'NewFolder2', '2020-05-04 09:52:15'),
(10, '0f6ead903e13eba64c624a45afba9184', 'b5908118157cff12d8d1f1ae6ed4c104', 'Folder3InFolder2', '2020-05-04 08:32:54'),
(12, 'd2617194116dea57dd6ca65498f01ee7', '0f6ead903e13eba64c624a45afba9184', 'Folder4InFolder3', '2020-05-04 13:07:10');

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
  `dropbox_offset` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fragments`
--

INSERT INTO `fragments` (`fragments_id`, `file_id`, `onedrive_id`, `dropbox_id`, `googledrive_id`, `onedrive_offset`, `googledrive_offset`, `dropbox_offset`) VALUES
(3, 3, 123, 456, 789, '', '', '');

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
('c9526e3221d689b48c621d1babe0bb87', '0f6ead903e13eba64c624a45afba9184', 'folder'),
('c9526e3221d689b48c621d1babe0bb87', '1', 'file'),
('c9526e3221d689b48c621d1babe0bb87', '2', 'file'),
('c9526e3221d689b48c621d1babe0bb87', '3', 'file'),
('c9526e3221d689b48c621d1babe0bb87', '4', 'file'),
('c9526e3221d689b48c621d1babe0bb87', '67b6e87381a8fb18c96c7acca3b6c35d', 'folder'),
('c9526e3221d689b48c621d1babe0bb87', 'b5908118157cff12d8d1f1ae6ed4c104', 'folder'),
('c9526e3221d689b48c621d1babe0bb87', 'd2617194116dea57dd6ca65498f01ee7', 'folder'),
('c9526e3221d689b48c621d1babe0bb87', 'e86edca146bbefd773838a7e7955b521', 'folder');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `fragments`
--
ALTER TABLE `fragments`
  MODIFY `fragments_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
