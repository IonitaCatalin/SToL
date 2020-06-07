-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2020 at 02:40 PM
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
('08b8141665331514b2d67b7b566b6eb5', '55c3bb42c@gmail.com', 'f04bf18f7', '680775027', '2020-06-06 10:35:08', '2020-06-06 10:35:08'),
('31df56d4a1c73d08e4c647b506dd7af3', '3c9ddaff7@gmail.com', '560526377', '711fa959d', '2020-06-06 10:38:10', '2020-06-06 10:38:10'),
('53b003dba9ea077b34f57c135eb4665d', '2b9a27f2d@gmail.com', '983ea537e', '28ee604ec', '2020-06-06 10:36:53', '2020-06-06 10:36:53'),
('64263eb4e140e25a1c1769b8ee738f5b', 'b102f13e1@gmail.com', 'f83567703', 'bf642cf50', '2020-06-06 10:36:25', '2020-06-06 10:36:25'),
('8def0bbc4ac5e9315ccb1eca6394bafc', 'user123@gmail.com', 'user123', 'user123', '2020-05-26 07:51:51', '2020-05-26 07:51:51'),
('a04ce113f7d4f569d1e8725cba25387c', '13468bcb7@gmail.com', '6c7534d95', 'aa40daf7c', '2020-06-06 10:35:37', '2020-06-06 10:35:37'),
('b3cdafc6eab783c89b8399a72374823e', 'ac92cb16f@gmail.com', 'a313d68d9', '5b256f926', '2020-06-05 18:44:58', '2020-06-05 18:44:58'),
('c158b2aa7ea42bcbdd219d8415244131', 'bbd85375a@gmail.com', '215d1a6b5', '6263d25da', '2020-06-06 10:36:04', '2020-06-06 10:36:04'),
('c4c5df0d7ed360c14262fbc3a0f46fac', 'admin@admin.com', 'sysadmin', 'sysadmin', '2020-06-06 18:42:34', '2020-06-06 18:42:34'),
('db995b2d08f5691764a283953dda4853', '9c1e65205@gmail.com', 'e3485249a', '5c7d5c2eb', '2020-06-06 10:38:37', '2020-06-06 10:38:37');

-- --------------------------------------------------------

--
-- Table structure for table `allowed`
--

CREATE TABLE `allowed` (
  `service` varchar(300) NOT NULL,
  `allowed` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `allowed`
--

INSERT INTO `allowed` (`service`, `allowed`) VALUES
('dropbox', 0),
('googledrive', 0),
('onedrive', 0);

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(11) NOT NULL,
  `user_id` varchar(200) NOT NULL,
  `file_id` varchar(200) NOT NULL,
  `download_id` varchar(200) NOT NULL,
  `file_type` varchar(30) NOT NULL,
  `service_hint` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `fragments_id` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `item_id`, `folder_id`, `name`, `fragments_id`) VALUES
(110, '69fd298c9dbe75d53625a0c15758390a', '5ef1c37d5ede0dc5334e72d78cc4dba4', 'fisier123456789', '4fffe8d038a41b39f6660bee962f1815'),
(111, 'b0242d18dd76e1dc42f57c115e744b13', '356798d2789b91970c7aa7624f035fe2', 'WhatsApp Image 2020-05-07 at 11.58.06 (4) (1).jpeg', 'e5e999a6819e467312771a52feb59631'),
(112, 'f7d54b85036f3c738f83f57da591539d', '356798d2789b91970c7aa7624f035fe2', 'Fuck me', '6c2cf76a5ba38ecca3f43fb297da8b0f'),
(113, '2bb6e86c1228699557681f2378f51656', '5ef1c37d5ede0dc5334e72d78cc4dba4', 'OMlHSDF.jpg', '85f1f6424686183a14087977bdb9d4c6');

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
(62, '494fbfc263965d35361a89a5ea2c061a', NULL, 'root', '2020-05-26 07:51:51'),
(63, '5ef1c37d5ede0dc5334e72d78cc4dba4', '494fbfc263965d35361a89a5ea2c061a', 'Fuck me', '2020-05-26 09:35:59'),
(64, '356798d2789b91970c7aa7624f035fe2', '494fbfc263965d35361a89a5ea2c061a', 'ceva', '2020-05-26 14:14:20'),
(65, '9dcdb44536fe435273407b97d9d8a50a', '356798d2789b91970c7aa7624f035fe2', 'Fuck me', '2020-05-26 13:02:25'),
(66, '6b4cde916c8b0f794b11d67160d4a5cb', NULL, 'root', '2020-06-05 18:44:58'),
(67, '18aec202b132db8b8faa4b3a357ec908', NULL, 'root', '2020-06-06 10:35:08'),
(68, 'fd0f0c5f257e727be9dc28d08f6e0123', NULL, 'root', '2020-06-06 10:35:37'),
(69, '37dfc13613df0efdd7e9536e137bc2fa', NULL, 'root', '2020-06-06 10:36:04'),
(70, 'e606b5119758f63ffcad2798219d2601', NULL, 'root', '2020-06-06 10:36:25'),
(71, '0d443555957934f377eb477301bbc699', NULL, 'root', '2020-06-06 10:36:54'),
(72, '9a01f278efc50c744ce636b234988966', NULL, 'root', '2020-06-06 10:38:10'),
(73, '54b45d67643e0fd88577be4531f86921', NULL, 'root', '2020-06-06 10:38:37'),
(74, '8e0726d84844b7ee8805802a68469bdf', NULL, 'root', '2020-06-06 18:42:34'),
(76, '333ac742faaaea03055ea6dc67114c58', '494fbfc263965d35361a89a5ea2c061a', 'FolderSecret', '2020-06-07 06:52:41'),
(77, 'f56269dabc3393310f45acf0d02005ab', '8e0726d84844b7ee8805802a68469bdf', 'ceva', '2020-06-07 12:33:27'),
(78, '24576bb664f381654c32ba49eb4903f6', '8e0726d84844b7ee8805802a68469bdf', 'altceva', '2020-06-07 12:33:36');

-- --------------------------------------------------------

--
-- Table structure for table `fragments`
--

CREATE TABLE `fragments` (
  `id` int(11) NOT NULL,
  `fragments_id` varchar(200) NOT NULL,
  `service` varchar(40) NOT NULL,
  `offset` bigint(20) NOT NULL,
  `service_id` varchar(1000) NOT NULL,
  `fragment_size` bigint(20) NOT NULL,
  `redundancy_id` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fragments`
--

INSERT INTO `fragments` (`id`, `fragments_id`, `service`, `offset`, `service_id`, `fragment_size`, `redundancy_id`) VALUES
(177, '4fffe8d038a41b39f6660bee962f1815', 'onedrive', 0, 'C605214351BE1193!1987', 33431, NULL),
(178, 'e5e999a6819e467312771a52feb59631', 'onedrive', 0, 'C605214351BE1193!1988', 33431, NULL),
(179, '6c2cf76a5ba38ecca3f43fb297da8b0f', 'onedrive', 0, 'C605214351BE1193!1989', 33431, NULL),
(180, '85f1f6424686183a14087977bdb9d4c6', 'onedrive', 0, 'C605214351BE1193!1990', 254502, NULL);

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

--
-- Dumping data for table `googledrive_service`
--

INSERT INTO `googledrive_service` (`user_id`, `access_token`, `refresh_token`, `expires_in`, `generated_at`) VALUES
('21ac97b40b27f0a96dc76e5e5c147421', 'ya29.a0AfH6SMBs6lTv-l-vU-aWDdhV5AJs3X4T1cKma8YjydbFAy4vHrqYrX8TIulj44bhWrcvcfJ1VjyNPJReEnoG0LWluGMyZAqcu0U77hrR7NDgBK8HqkkAipN282mqS707eRltDLPMGgTONHlVX2gFGuSlcN22qDegPwZh', '1//03td016jh_HPaCgYIARAAGAMSNwF-L9Ir1Dx_FB8hvY4CNfRaw3fEMUNZJT5TpxyF1svHvQvECridoctOQjGufvLBX8ZERf19H5M', 3599, '2020-06-07 05:52:30'),
('8def0bbc4ac5e9315ccb1eca6394bafc', 'ya29.a0AfH6SMBs6lTv-l-vU-aWDdhV5AJs3X4T1cKma8YjydbFAy4vHrqYrX8TIulj44bhWrcvcfJ1VjyNPJReEnoG0LWluGMyZAqcu0U77hrR7NDgBK8HqkkAipN282mqS707eRltDLPMGgTONHlVX2gFGuSlcN22qDegPwZh', '1//0952sI2MyeIiYCgYIARAAGAkSNwF-L9IrXlQixg6XMKUeGuv8zOOKoyi4ZyfzoNyD2K3qViezLYbggVHy3qU_l6QaGcsZHnxBSxE', 3599, '2020-06-07 05:52:30'),
('c9526e3221d689b48c621d1babe0bb87', 'ya29.a0AfH6SMBs6lTv-l-vU-aWDdhV5AJs3X4T1cKma8YjydbFAy4vHrqYrX8TIulj44bhWrcvcfJ1VjyNPJReEnoG0LWluGMyZAqcu0U77hrR7NDgBK8HqkkAipN282mqS707eRltDLPMGgTONHlVX2gFGuSlcN22qDegPwZh', '1//09gwgpL4d32SGCgYIARAAGAkSNwF-L9IrMPZX7ETqktPDKPP7KXDVa5WJQozT8jsSkOujatl8yfldnDsVpJf6abevgp3aABBQQJk', 3599, '2020-06-07 05:52:30');

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
('53b003dba9ea077b34f57c135eb4665d', '0d443555957934f377eb477301bbc699', 'folder'),
('08b8141665331514b2d67b7b566b6eb5', '18aec202b132db8b8faa4b3a357ec908', 'folder'),
('c4c5df0d7ed360c14262fbc3a0f46fac', '24576bb664f381654c32ba49eb4903f6', 'folder'),
('8def0bbc4ac5e9315ccb1eca6394bafc', '2bb6e86c1228699557681f2378f51656', 'file'),
('8def0bbc4ac5e9315ccb1eca6394bafc', '333ac742faaaea03055ea6dc67114c58', 'folder'),
('8def0bbc4ac5e9315ccb1eca6394bafc', '356798d2789b91970c7aa7624f035fe2', 'folder'),
('c158b2aa7ea42bcbdd219d8415244131', '37dfc13613df0efdd7e9536e137bc2fa', 'folder'),
('8def0bbc4ac5e9315ccb1eca6394bafc', '494fbfc263965d35361a89a5ea2c061a', 'folder'),
('db995b2d08f5691764a283953dda4853', '54b45d67643e0fd88577be4531f86921', 'folder'),
('8def0bbc4ac5e9315ccb1eca6394bafc', '5ef1c37d5ede0dc5334e72d78cc4dba4', 'folder'),
('8def0bbc4ac5e9315ccb1eca6394bafc', '69fd298c9dbe75d53625a0c15758390a', 'file'),
('b3cdafc6eab783c89b8399a72374823e', '6b4cde916c8b0f794b11d67160d4a5cb', 'folder'),
('c4c5df0d7ed360c14262fbc3a0f46fac', '8e0726d84844b7ee8805802a68469bdf', 'folder'),
('31df56d4a1c73d08e4c647b506dd7af3', '9a01f278efc50c744ce636b234988966', 'folder'),
('8def0bbc4ac5e9315ccb1eca6394bafc', '9dcdb44536fe435273407b97d9d8a50a', 'folder'),
('8def0bbc4ac5e9315ccb1eca6394bafc', 'b0242d18dd76e1dc42f57c115e744b13', 'file'),
('64263eb4e140e25a1c1769b8ee738f5b', 'e606b5119758f63ffcad2798219d2601', 'folder'),
('c4c5df0d7ed360c14262fbc3a0f46fac', 'f56269dabc3393310f45acf0d02005ab', 'folder'),
('8def0bbc4ac5e9315ccb1eca6394bafc', 'f7d54b85036f3c738f83f57da591539d', 'file'),
('a04ce113f7d4f569d1e8725cba25387c', 'fd0f0c5f257e727be9dc28d08f6e0123', 'folder');

-- --------------------------------------------------------

--
-- Table structure for table `onedrive_service`
--

CREATE TABLE `onedrive_service` (
  `user_id` varchar(200) NOT NULL,
  `access_token` varchar(1500) NOT NULL,
  `refresh_token` varchar(1500) NOT NULL,
  `expires_in` int(11) NOT NULL,
  `generated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `onedrive_service`
--

INSERT INTO `onedrive_service` (`user_id`, `access_token`, `refresh_token`, `expires_in`, `generated_at`) VALUES
('8def0bbc4ac5e9315ccb1eca6394bafc', 'EwCAA8l6BAAUO9chh8cJscQLmU+LSWpbnr0vmwwAAUBVkxslKFxHBxfEpZKl7VOMccyT6dmk932VgAzJqi7e9VU4Xe/uBfmKIUdpYObs7gxi5dRvadl7aPv4Q56r0CpEDzEbTk1Ko5XLLBYxrsl+yqWS3FnMUHJgaUgtwWY52LG1zO/sxCDUvTFQvnoi5CBytDSfz+3La0X8RDjgAUE9iRf2VhA3Vog5T9KdwfZQxniluPRrfFjzXwiZEcNb978lh55r9nFeF6xZGk1E4dH6t61AHLPQ8EhkpsMI/76TaaXTU8FrCgPzMK8URqQ18Shky02kaY70CdGGDirPHtV4o710ubCEDKd8puFr4iKDPcWuNQjUWS0Ld1GqlSo5mZ8DZgAACNgqQVrKXCPJUAJFnHqC/dK8aW5epn5bsoqxpoZAU+K3Ld1RgP1zfwtxU8wpxMHEiilrQgNeOqfrcsnwG42X8fEyP34FuLy8O7nTAMQyBxl9su0ZVHAK14e2vvjVTZDnYOPxxWT3B7+756cD/9oFmyirdNb+BNjRSAoRla6Vws2HDp9O0GebEpnp+1vSu2b7/Ue69GYZeh8TzgeqQzbFyvmWBe9xeiuXpaXENr6UAtXaFFaX4Am6hvuhsUukFecZZ8lDd/rYcFOGTQAF+4UzLZ++hfYGx+XkLI1WfJsuMVN1RNJlEirkn2uyosq9TD8HTafAoRz0bQac924xalHrVoK7KSlSbOTsE0qMbfSdgb0Z8tt9F9JnxzNNODfb+YZD4qcqd6Kf1kq0JJASgP/Pu6wS4FmRVMxUlcnwR/rq0BNkLGGrpXDNry6HHmjB1JQehaPS2O+ttZAs8qovTwnozdT080Pj1dBAI/PSpAGvDdkdTykWURuiWYTtWTpSDWCyU/Cb++61NRpA3Vz5mB1wnB5kJoRFnahemLCYmXzM1Zj9vjWfvS8PfrTOOsG7VAEOS2WXgbkrQzm4uBpwPYGOJwOm8ldW5XFugiNghiBhzktoFdymu/sJkIvVL8y3AKVsy7rfMgatDCYXb4hKPyQE7uhb8D78Xf/2an+YH3tR3lM1hb7nUfBuKKWl8IdlV0VlliykpALKfJTcRY5Mhe4yrkE0c++RCur8HENm5ipd1ttZWw5tbykDc1BAlX0BGQv4T/J2JUDIqRt+ZFIKVGPisVjACiIEvYTubpzJkQI=', 'MCfNNnWVv7WTCwcRRuTwOMwAeew*pelt1AilJoy1D3hN*XHkTUQ5zOC4*KuDAjF6JNQS!lusj2AmpCNOArwLRmRnwacug*I5zbJIZ0fPoTd7UtvCC!5wBIq1gP!E6jpS*6wkm96Ae3uC7HynRRdCl!t58sAm4uQyjg59P7c93B5laJuI!eChOkQOLhdNpHcr0HfJbPujrj*wbsBRHB8o5h4yzWCjbdoYBiYpV6pKxZQTh0DLbZel3GF3ydhSZ8ScE9dKu6Kh9VyUohA0HCv4H5nGTY!MgIlVF88eKA7T3ux27UijqyPc8n1wdkoCVu*uS*HO5bN99Eqg9sdVzRFSt47hIffZUaxFZ9MwslKN6v*pZITOnJXwPw9HNJLwtBlKPWIrlB4EmG8wR57ZnF8wNfXk$', 3600, '2020-06-07 08:52:29');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `user_id` varchar(200) NOT NULL,
  `upload_id` varchar(200) NOT NULL,
  `parent_id` varchar(200) NOT NULL,
  `file_reference` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `expected_size` bigint(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `mode` varchar(30) NOT NULL
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
-- Indexes for table `allowed`
--
ALTER TABLE `allowed`
  ADD UNIQUE KEY `service` (`service`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_fragments_id` (`fragments_id`);

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
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `upload_id` (`upload_id`),
  ADD KEY `fk_upload_item_id` (`parent_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `fragments`
--
ALTER TABLE `fragments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1398;

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
-- Constraints for table `fragments`
--
ALTER TABLE `fragments`
  ADD CONSTRAINT `fk_fragments_id` FOREIGN KEY (`fragments_id`) REFERENCES `files` (`fragments_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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

--
-- Constraints for table `uploads`
--
ALTER TABLE `uploads`
  ADD CONSTRAINT `fk_upload_item_id` FOREIGN KEY (`parent_id`) REFERENCES `folders` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
