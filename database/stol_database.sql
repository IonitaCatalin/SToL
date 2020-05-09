-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2020 at 01:06 PM
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
('c9526e3221d689b48c621d1babe0bb87', 'abcdef@yahoo.com', 'abcdef', 'abcdef', '2020-05-04 08:03:14', '2020-05-04 08:03:14');

-- --------------------------------------------------------

--
-- Table structure for table `dropbox_service`
--

CREATE TABLE `dropbox_service` (
  `user_id` varchar(200) NOT NULL,
  `access_token` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dropbox_service`
--

INSERT INTO `dropbox_service` (`user_id`, `access_token`) VALUES
('c9526e3221d689b48c621d1babe0bb87', 'RfMDs1XUrTAAAAAAAAAAMyPPMTgM2-pNBfPuAjPJnwIl7-TYfbzzD3-VAOvrEGd5');

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

--
-- Dumping data for table `googledrive_service`
--

INSERT INTO `googledrive_service` (`user_id`, `access_token`, `refresh_token`, `expires_in`, `generated_at`) VALUES
('c9526e3221d689b48c621d1babe0bb87', 'ya29.a0Ae4lvC0_aE-iK0KaUG7WKJTMgNH7cmRmbO7umqXEuLS45j4cDJKalvjsrwcmwidd6oZeddmYfmmpS9IYIrr6_F-9OuA1HUCMScut9IBMn32x7_zUn552er0VvMhxFKEk9JJ-sENVyrVZRgi0-zVF7GTRZnpYjvMj3iwm', NULL, 3599, '2020-05-06 21:19:12');

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
-- Dumping data for table `onedrive_service`
--

INSERT INTO `onedrive_service` (`user_id`, `access_token`, `refresh_token`, `expires_in`, `generated_at`) VALUES
('c9526e3221d689b48c621d1babe0bb87', 'EwCAA8l6BAAUO9chh8cJscQLmU+LSWpbnr0vmwwAARbU8lBlqX+5eFPywvvgaqluEsZmlkc+BegG4vd8cfoevfVquSrSBkgfhUh4xTwseRMv0OiNIUPiyjoD+SNHZC3z4y4dOQbLsRdK+Zl0q6WjPK2dyW2eeP6B51fFv6sF2m0ruYzpDC7fB6PCJkwUlPWA1FK3uJtYHyZkq5/1mvUqy1QVZqiSe1LteGaCpWfgl+aCjhncHWdhha99R9E0IZJuWfGq/8pmrB/mDzmDUD3qBpV71z0Yy5TQPU4Lr2pCLsQD7YLC1+onG1NhVv5zR5vmHbqa8VIuC1X2dreyO+kIUycaoOBis1FiFkZUYVjIGRTXRnfT0lml0lP7p/qw8xMDZgAACD822UQ5zvWUUAIWu2mAkiYq/e26Wpq23IVPbKfS3dxwRM0A0PKWDIsQHCJHm2xrf6H8fj7L2/1RxfiYPvvYISko6YzpUAHH7FqZbeHe5NLDxAHgLra8pDn0rjHQ6deqcgInEDmutkhiA4B/oCnMLdNxpfgdiCB2v7iyKTRz+/6FzcOX7hhp1GOu/LX7VhiBvdPeaTTu1UZzhFgbCZopi7lqvuMsrz90eY8eT4j8YoM0nmDQv5eX4QznXrN33FzTq6ieMRg04SP2KqCozTSZK7x2dOyaAoWTNeikKeU/lAefGAJGzDXtG7I135kqxT5mPwA4J7jIQKSk0anL5WbPIKsVpdtrXANPLkkmKgYt0rCv/5VgSAh9RUWijMA3eu56PtD1lN/cp3JKPGbW/Ex3r7M+cUCUHvnNQHje56mdmKvDmHiYKw0n6Agx4PVWPS9UwU2DO/VPtTQPg4DYAOjd8Mb4aP2pKVwnAXpyie4ZRX85iLAaiNwXyhf3rNAF33H0FI6HpZnCh795gwDf9rl0gX/WcFasS4hsr8a3bIRy+v4IbxQr+tpukaKj8nLNiHNP3syf7Mm2HH+nNR6dvgAQCMv5edWZvn45Z/aj4X/9aQceCYAsfmXujvRtvzNeIL8qBBJWWGBwRBJ+BdOyV+/GzznOatn4joXBQUoxlN/txaZ2WUxAQ+KjIN/dthZmFrsF1yLkhNrB2VKQh2Eakq74RAWxXcsSmJbPCG3b4mcRckydkD8j2bUar1AFeOTd8DZ/LEffUDo4TmSAbmz7tpYlow5j9K673yt4ALQjlQI=', 'MCYiQJGX7BRtu7WZ3INAc*utvcJtTD5cbKKY5Ioruxfr1H*Ciw2!fvJ0ECG1CpTxaY2P1Qe*5D3AomDzcOvejxb*sS6s3dkWO5eSzw44xmZPQT0!!MX!nZ2qPWRtLqnhEHeI5jHGYWHnxkenp6uJK0hQVL7pub2wKWrIgowKY4vNSkRAprUYe6lDHxkRtrsv0BsZNlgxGdBMwUyRnJbm*LEuqyRhexJ22i6qvYOQPdMT99*IoXOBQ!Jn7vXXyc519eo8lG3lVKYVKxCXsnBbXiLA!Kcm7EGDdeO3eBkCSfiEQOkGZ!WNFTnzKEhUyNV2UWFQgf*Dx2SCVBBkcupCtrjC**eofjv6BNQJsl5mGITrnXqPKnizVltKNn2Pj3BZu0CYH3GymLEQlz6e2o6HU1IVKHe7M2kGKUBLAzgJ25iBn', 3600, '2020-05-06 21:19:59');

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
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `user_id`, `upload_id`, `parent_id`, `file_reference`, `name`, `expected_size`, `status`) VALUES
(1118, 'c9526e3221d689b48c621d1babe0bb87', '5eb666bd4b39f3.59291306', '67b6e87381a8fb18c96c7acca3b6c35d', '0ce0956799f2a7bcd3dffc1a4ae18db0', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'chunking'),
(1119, 'c9526e3221d689b48c621d1babe0bb87', '5eb666e33c8d86.41244423', '67b6e87381a8fb18c96c7acca3b6c35d', 'cabd116f17695b9a1797593b1b6963c5', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'chunking'),
(1120, 'c9526e3221d689b48c621d1babe0bb87', '5eb6670a4d1346.51591427', '67b6e87381a8fb18c96c7acca3b6c35d', 'f89efc1854d3cca684d02afe571c0034', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'chunking'),
(1121, 'c9526e3221d689b48c621d1babe0bb87', '5eb6672cd00f50.08277988', '67b6e87381a8fb18c96c7acca3b6c35d', 'eb027a94b45b3a52d2de276f9540355d', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1122, 'c9526e3221d689b48c621d1babe0bb87', '5eb66b0081ff72.27501846', '67b6e87381a8fb18c96c7acca3b6c35d', '1f3fb0a51ccb0ba05fdd90dab0b0d2a2', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1123, 'c9526e3221d689b48c621d1babe0bb87', '5eb66b00aeae46.52447929', '67b6e87381a8fb18c96c7acca3b6c35d', '7340d159a5f0c7cb7c26762f19b025f2', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1124, 'c9526e3221d689b48c621d1babe0bb87', '5eb66b78b242d2.13423031', '67b6e87381a8fb18c96c7acca3b6c35d', 'ba7b0c05af1131827dfb08e5816ffebe', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1125, 'c9526e3221d689b48c621d1babe0bb87', '5eb66dd64d52c8.10839476', '67b6e87381a8fb18c96c7acca3b6c35d', '76d1764caa70d0f45e1b740f64601ddc', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'splitting'),
(1126, 'c9526e3221d689b48c621d1babe0bb87', '5eb66defb5e6c2.74075539', '67b6e87381a8fb18c96c7acca3b6c35d', '1facb8193d4304c4f334d69fe5061250', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'chunking'),
(1127, 'c9526e3221d689b48c621d1babe0bb87', '5eb66df61c4d30.21554466', '67b6e87381a8fb18c96c7acca3b6c35d', '3fce08a5c032f51b80b49bef7639e956', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1128, 'c9526e3221d689b48c621d1babe0bb87', '5eb66e0161f050.56580623', '67b6e87381a8fb18c96c7acca3b6c35d', 'c5810eb57b8e14ebe48dc5fe036d789c', 'WhatsApp Image 2020-04-29 at 14.14.01 (1).jpeg', 383704, 'splitting'),
(1129, 'c9526e3221d689b48c621d1babe0bb87', '5eb68035d5b018.15451112', '67b6e87381a8fb18c96c7acca3b6c35d', '6ee092eb1588caa57781227eca40f138', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'chunking'),
(1130, 'c9526e3221d689b48c621d1babe0bb87', '5eb6803d73faa8.60172319', '67b6e87381a8fb18c96c7acca3b6c35d', '42cdeb3355883fd6dae5e3fe0db695b4', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'chunking'),
(1132, 'c9526e3221d689b48c621d1babe0bb87', '5eb680f9cd8b87.92829895', '67b6e87381a8fb18c96c7acca3b6c35d', 'f20f00df890b34fb6875a9c65ae8b46d', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'chunking'),
(1133, 'c9526e3221d689b48c621d1babe0bb87', '5eb6811a363006.25764712', '67b6e87381a8fb18c96c7acca3b6c35d', '7a44ff7b7d18fcf910415b257737ee0e', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1134, 'c9526e3221d689b48c621d1babe0bb87', '5eb6815b2a2994.05701411', '67b6e87381a8fb18c96c7acca3b6c35d', 'fadd2ad6644847cc9b1e10c4db77993a', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'chunking'),
(1135, 'c9526e3221d689b48c621d1babe0bb87', '5eb68168a706f4.79152430', '67b6e87381a8fb18c96c7acca3b6c35d', '24a412eead6946eaae98f1c735ba1f08', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'splitting'),
(1136, 'c9526e3221d689b48c621d1babe0bb87', '5eb6819b442e33.27239440', '67b6e87381a8fb18c96c7acca3b6c35d', 'bb973491069edafb4b867089a35b157e', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'splitting'),
(1137, 'c9526e3221d689b48c621d1babe0bb87', '5eb681c1906778.06200503', '67b6e87381a8fb18c96c7acca3b6c35d', '7718df6b7c2c7dcf5985c19b98ff52fd', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'chunking'),
(1138, 'c9526e3221d689b48c621d1babe0bb87', '5eb6822e1cff21.85657758', '67b6e87381a8fb18c96c7acca3b6c35d', 'a718516ee9c13a7ab232c41c1a73c258', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'chunking'),
(1139, 'c9526e3221d689b48c621d1babe0bb87', '5eb682676dfa52.30267862', '67b6e87381a8fb18c96c7acca3b6c35d', '556d57d2606497801c3171fd7c14f549', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'splitting'),
(1140, 'c9526e3221d689b48c621d1babe0bb87', '5eb682676e8ae0.39750858', '67b6e87381a8fb18c96c7acca3b6c35d', 'be8eeaefc8bf5ab0d0825b574e73ace4', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1141, 'c9526e3221d689b48c621d1babe0bb87', '5eb683962d63f0.60523313', '67b6e87381a8fb18c96c7acca3b6c35d', 'ab0a3a6361d1eb62c3026ab3b53f5ebf', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1142, 'c9526e3221d689b48c621d1babe0bb87', '5eb68427a2b466.98530405', '67b6e87381a8fb18c96c7acca3b6c35d', '243fea7fe281e7aa65f24f5329a295ed', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1143, 'c9526e3221d689b48c621d1babe0bb87', '5eb68809f3d8e9.70700317', '67b6e87381a8fb18c96c7acca3b6c35d', '33ec9288f3417802e1ac4228efdecb4e', 'stol_database (2).sql', 7823, 'splitting'),
(1144, 'c9526e3221d689b48c621d1babe0bb87', '5eb68ac924e688.37838649', '67b6e87381a8fb18c96c7acca3b6c35d', '233b527d5c6c1ff40bf1068eccb14042', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1145, 'c9526e3221d689b48c621d1babe0bb87', '5eb68ad227c897.30810275', '67b6e87381a8fb18c96c7acca3b6c35d', '7d7391468ab0961042a4751deffc65e8', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1147, 'c9526e3221d689b48c621d1babe0bb87', '5eb68b1c43c142.50662678', '67b6e87381a8fb18c96c7acca3b6c35d', 'd0f1b777c5729c4e62869af9deef0529', 'iverilog-v11-20190809-x64_setup.exe', 17847594, 'chunking'),
(1148, 'c9526e3221d689b48c621d1babe0bb87', '5eb68b1c445fc1.64976803', '67b6e87381a8fb18c96c7acca3b6c35d', '960e40a720adf333e908486dc41a0bf2', 'comenzi_verificare.txt', 4134, 'splitting'),
(1149, 'c9526e3221d689b48c621d1babe0bb87', '5eb68b760d4c65.33185480', '67b6e87381a8fb18c96c7acca3b6c35d', '362b880030bc4872fba8c23526c9243c', 'comenzi_verificare.txt', 4134, 'splitting'),
(1152, 'c9526e3221d689b48c621d1babe0bb87', '5eb68c460f3157.32517439', '67b6e87381a8fb18c96c7acca3b6c35d', '9be34b52de80dc59d991e436f3978bfb', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1153, 'c9526e3221d689b48c621d1babe0bb87', '5eb68de97eca74.91178900', '67b6e87381a8fb18c96c7acca3b6c35d', 'ee9f0bb64b3954b2d5a890a9ff0cc205', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1154, 'c9526e3221d689b48c621d1babe0bb87', '5eb68dfd4b96e3.06548104', '67b6e87381a8fb18c96c7acca3b6c35d', '29e7e131cc40e658c3bbcd494eb64d0e', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'splitting'),
(1155, 'c9526e3221d689b48c621d1babe0bb87', '5eb68e24497c45.80195609', '67b6e87381a8fb18c96c7acca3b6c35d', '912fc346e4a1af0e74bc41ff4584ad61', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'splitting'),
(1156, 'c9526e3221d689b48c621d1babe0bb87', '5eb68e62531ba1.07372216', '67b6e87381a8fb18c96c7acca3b6c35d', '9a747eddcf820e6a18d4fbffc670b1d9', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'splitting'),
(1157, 'c9526e3221d689b48c621d1babe0bb87', '5eb68e6dadd0e0.96977103', '67b6e87381a8fb18c96c7acca3b6c35d', '98839af9a5c7ad7c08ed337314038e74', 'WhatsApp Image 2020-05-05 at 12.03.01.jpeg', 26577, 'splitting'),
(1158, 'c9526e3221d689b48c621d1babe0bb87', '5eb68e6dadfd11.68376879', '67b6e87381a8fb18c96c7acca3b6c35d', '264a16dd8524c4ddb396f3fe83c5e66d', 'WhatsApp Image 2020-05-04 at 11.55.23.jpeg', 40305, 'splitting');

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
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1159;

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
