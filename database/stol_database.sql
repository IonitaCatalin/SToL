-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2020 at 06:01 PM
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
('c9526e3221d689b48c621d1babe0bb87', 'abcdef@yahoo.com', 'abcdef', 'abcdef', '2020-05-04 08:03:14', '2020-05-13 07:41:56');

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(11) NOT NULL,
  `user_id` varchar(200) NOT NULL,
  `file_id` varchar(200) NOT NULL,
  `download_id` varchar(200) NOT NULL
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
(84, 'e24927223cb9f04426d3f8d91cda174c', 'e86edca146bbefd773838a7e7955b521', 'df2e617beb2a2183be120226aef9f735.jpg', '3c1a232084233cd7ab1cb0911aef12bc');

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
(7, 'e86edca146bbefd773838a7e7955b521', NULL, 'root', '2020-05-13 17:20:53'),
(29, '6c3f6d2eb809ec244f196dcaee56836b', 'e86edca146bbefd773838a7e7955b521', 'Docs', '2020-05-19 20:55:30'),
(30, '401aadc1a71dd4b495a7a8104e464b2b', 'e86edca146bbefd773838a7e7955b521', 'Homeworks;-)', '2020-05-19 20:55:44'),
(33, 'bcdf0938bb2fc4f0b45ca69479bcf655', 'e86edca146bbefd773838a7e7955b521', 'Weeb', '2020-05-20 14:04:18');

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
(145, '3c1a232084233cd7ab1cb0911aef12bc', 'onedrive', 0, 'C605214351BE1193!1978', 42439, '26b38d0358529f62f0f6a6135194dd4d'),
(146, '3c1a232084233cd7ab1cb0911aef12bc', 'googledrive', 0, '1B4UQbmVZwlDClXwL-syqFSTdjOO2gASz', 42439, '26b38d0358529f62f0f6a6135194dd4d');

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
('c9526e3221d689b48c621d1babe0bb87', 'ya29.a0AfH6SMBiV3SEwKyybNd4zbwyGRE9elAV2UkYb7qihRSrOvFopeeEcSXH87CSYm7nvOmOLYeJFkx1kcFug3IhktiGnOARh_PTY-98Zz00Uxpoozloq2DKC1ZMg-UoK9V57XZhSnpvY3s8y-vlYymGAfnbqwV1TA3AiMM', '1//09jkt5WT9YTcVCgYIARAAGAkSNwF-L9Ir6RrX5q9leLM0BH2kF6qSoj0KV-HwtPVn-bg-lagIbiwpxXGFZfFmeBYEisVYad000Y8', 3599, '2020-05-20 15:00:24');

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
('c9526e3221d689b48c621d1babe0bb87', '1c833b67416c0cf3e9e1f86302fff6ef', 'file'),
('c9526e3221d689b48c621d1babe0bb87', '401aadc1a71dd4b495a7a8104e464b2b', 'folder'),
('c9526e3221d689b48c621d1babe0bb87', '6c3f6d2eb809ec244f196dcaee56836b', 'folder'),
('c9526e3221d689b48c621d1babe0bb87', 'bcdf0938bb2fc4f0b45ca69479bcf655', 'folder'),
('c9526e3221d689b48c621d1babe0bb87', 'beea9188dec0fcccf5272341ebdd26e8', 'file'),
('c9526e3221d689b48c621d1babe0bb87', 'c6690649bc3cc31938d0da81fdde8383', 'file'),
('c9526e3221d689b48c621d1babe0bb87', 'e24927223cb9f04426d3f8d91cda174c', 'file'),
('c9526e3221d689b48c621d1babe0bb87', 'e86edca146bbefd773838a7e7955b521', 'folder'),
('c9526e3221d689b48c621d1babe0bb87', 'f02ca74c170277f9cfc5a3d6152f5ff8', 'file');

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
('c9526e3221d689b48c621d1babe0bb87', 'EwCAA8l6BAAUO9chh8cJscQLmU+LSWpbnr0vmwwAAfAowOPuPAkFHUMalp6vUtXJpaZ4cx/p2j6/zkxn+DiVMJ+TVyJezIGQxwctN451ek63hMXLt9QoRLRZuX381tGE83s5w0zVAYBG59QNnq+UP9SRoACk/SfWM/G9KEftwoCcbOOKfkRNzWtJbGHN/ooAAMcreQwCj0FFwQKc/8VYjc0zvoefx3Oy9sux/DAEH5L8/91xVuRERxhXmlrXXVW4cRfUBqM+F2gYXtNH3RYY7bJn7+245MHMvJFjFxVoIN1Vk42tgl8PEbCjz+1wmTW7z80UhhEelmb2HMRxNXPD6squDRm3E0v0jG5PONnlsBOs74hiQ7Z7yPRY/hJvk5MDZgAACMC6UZKE5+nKUAKHFjveCXhcavYQZf47HXfHYgkNKWkfQfKKY9QwpOb9iaSWEhdQ+QIlVf3+6NMPLjn+3OMv2f6N+cJxn6DrxXkaHSGuHCBk8B1pEn8GaehkyNNxzxuTH7zp7ZjOOouVbQjmCmCas2GnzwxllTJuHsXK4VqOGt2dcehpb4Uv+ZugxtJC7G4MVdfCIC9jZVTxLW0LvybjxBxRScDNIz++nUkzByv9D1fpKKdZWxdjlDTFZNOT3BSfCIbgdD2i/kg/TwhTE/tJ/yyk97f24d30MJoY00/VQAut79uEVFxkYAXN8zUfYUf88Hqn3L7sMo93u4eXWn427gqeBS9rriRPU121Lc68YDOgLBcx/l8eWcMzpsu5HaUqQRBd+7sz0pq+tbwSL+8YFMXIvBq9HVgOWzFwGgyuJTuu5INV3aqCRU0kEpaN2bQ75DKYiUu/ycJbgYPiyfLJEuR/ogp/SL0AzragCml+9d80vyVEMQBvEddvOl91MByzQcRcEA+Ke3BuwS9lLqkLE/QumzyzRLt0dGHVVDTuLqZ0sbnhvhnvVN+LxZYRh4R8qCLk5F/jGS+8unwew85cG3HcBIQxFsS6iTk2yIOybb2HktqgmTNiQngrmWCTfzn1biSsmwax2bP1GytvO7ivoJA9iOeF662uTJWAfDB0u5DYvTssKfvN+2N4YyXAWVDN9AyF55zCjn/jmiOP1FsKezPefnOH0mXFnkLV19rgjzGoVg5q6hb4DTNRSPlcAI5dwhIYde86H0CA7ig8nRS/6qzvx8mZF1JAlr+7kQI=', 'MCScHaub3XqIv1ejAb81vxBnqtGUgnmhuErQmNYL78Y0uUSh71jl0hwueGMTnuBSqoWSU3*C0fGJkkFIrFNwxeNEd0ls!*p3Z3VyUtGC5vrKtf01owGwZ9WGLhKd*a3JAfk!fxvudSajA0f!H8gKnPT81vLx9EsmjThXF*t6ndNOp!ngqhQHmHpdDJuN7Kqz20pxRFyhw!Aa8L!QJlgLfTprEkFwzKEzQdvddwFQv5i337Fi9zNS7etXiuKHijVi6aD0h0m1kpMigkHhX*Ds51sgJthW8q11lLhQiy9oUxZlbWyJy23XU*7a7jYlktRyTrCl91YLCnUB2h79c3Z0q3kYXpZLYLDgMKxDCiWpugh!GLr8c0t2UOClwxDS9Aa6O6bgC4kqCvZdHI*zgWFOSRYw$', 3600, '2020-05-20 17:17:15');

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
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `user_id`, `upload_id`, `parent_id`, `file_reference`, `name`, `expected_size`, `status`, `mode`) VALUES
(1320, 'c9526e3221d689b48c621d1babe0bb87', '5ec4f8a846b797.64281005', 'e86edca146bbefd773838a7e7955b521', 'b77089ee44722a341bfead96c1e7b4d0', 'WhatsApp Image 2020-05-12 at 12.03.45.jpeg', 57247, 'splitting', 'fragmented'),
(1321, 'c9526e3221d689b48c621d1babe0bb87', '5ec4f8c25ce954.95448199', 'e86edca146bbefd773838a7e7955b521', '6470e1aff74b8ee9baecde4b6bf3b095', 'WhatsApp Image 2020-05-13 at 12.03.29.jpeg', 99723, 'splitting', 'redundant'),
(1325, 'c9526e3221d689b48c621d1babe0bb87', '5ec5381f563ee8.52128833', 'e86edca146bbefd773838a7e7955b521', '99737ccf23d5bae427567de4d4dd54c0', '97211766_927507814345540_7565860195012706304_n.png', 221896, 'splitting', 'fragmented'),
(1326, 'c9526e3221d689b48c621d1babe0bb87', '5ec538a0d111a6.41590300', 'e86edca146bbefd773838a7e7955b521', '2a9978be74c9e1fbfb3a688619dedbe4', '97211766_927507814345540_7565860195012706304_n.png', 221896, 'splitting', 'fragmented');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `fragments`
--
ALTER TABLE `fragments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1362;

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
