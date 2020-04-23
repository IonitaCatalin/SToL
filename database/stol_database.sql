-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2020 at 12:30 AM
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
(26, 'testify@test.com', 'testify', 'testify', '2020-04-21 05:33:24', '2020-04-22 13:58:16');

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
(26, 'RfMDs1XUrTAAAAAAAAAALOuWABJ0tmI2xG88R5D2LZG4AENmlpyn3_t8apogcFeq');

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
(26, 'ya29.a0Ae4lvC1KZm5RHj6HqaOiaJmJAs3AGRuS7QkcVyyH2cZfG4efonBknbxHlhhpEr1hyOyrUR7hHxgaEKuejd1m6fEnrjzuxMaUPcrrBgZRwGraKEdlKSwWRp_A_WqBdcKQvxmy5qWL2145cBpxG8tP48gapiiJDe4SahY', '1//098BQk42kT5bZCgYIARAAGAkSNwF-L9IrT0oUTuR1E6mzKVbBnWsr7XYIwle-xf9a8HPD236y1cbT6zqMKNpOFFHp4J-mT_i2REo', 3599, '2020-04-22 21:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `onedrive_service`
--

CREATE TABLE `onedrive_service` (
  `user_id` int(11) NOT NULL,
  `access_token` varchar(1500) NOT NULL,
  `refresh_token` varchar(1500) NOT NULL,
  `expires_in` int(11) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `onedrive_service`
--

INSERT INTO `onedrive_service` (`user_id`, `access_token`, `refresh_token`, `expires_in`, `generated_at`) VALUES
(26, 'EwCAA8l6BAAUO9chh8cJscQLmU+LSWpbnr0vmwwAAS0ewaRa4HagAKzopwPkMtZQ4+OtdN5+FVrKLWk8pu/IYpekAoEeKd9tz79jmL2Jo613m8d78FdbBaBs8g18dD+Yyy6bK/BEwEbSImrwbfgruuW018giL+1oiM80fk5e4VW4KefYC8ya9HwyEvdTgISV667Qgf2pZ8FyXyD6EVCWqKPJYruKUQ+mYaA/qhla97T03p4nv68Z/qKn2CWwPxoi1nArTXSs9Rv61RyWlNOIqvTYkX9gaOCY6HrRK5lgXHZH720VNvOuE1uyDDrqIfDPy0jjE4gsVrO0x0NuXp9LJHes3qm12fIoDjHpu+zDSDv4Vv8gd0ah1y7hnoSEcFYDZgAACMQjrwHuVNGCUAItMCArN0OYYMD/z4LgzLXXku9qL0ZsEeKEfy/MB3NyZAsEcXFUiya8VPo7OZ1L9/wY5K2TsC9Sn7QOrThQwEFoird3ZzRi1wbgSWOhFZtUhp78EAXK8xuBplFEx3cNr05RtP087NsKF+sAkPurdVzetHpgdVCiY463r/ktY0+D2D6QZyW+/hbhksDgGaPwmk0XXsMN+Au1cSGGoUliEkfvsOUJzoskHJLNOQQrsV+u8ysFnFoZ1TOJLnO0sZ7PXyFz1VjqUUz6a+ZeVp4u2/PsiUQaGrzxUfa2h5Us8740M3nOpRTvxQeDHVT+YNOt8uU6R6uC4kgrFoLv2EHMWJtTZ9HdAGSE/I507S2N/p70Dzors/QfALdXdae7loKB8tw5DVwQBvuyLH9/LMHHDbXLdWV6UcV69Oix0TKuF0lr1XMKCqtrY1Ilf1V82vqAdi7xc7GKhBpNGIQ4/pjsmCN5IIytkVaqrFuLy77qbfsZi8jwQk0L5EIdb3eXosSagS/o5/i2BhjW3I8XqzdKtMQgZCCLQAPmLD/hXB5zYwnWfidho9iISdR4+vjvkGv9PjiX4oPy7e6SPEm56TQWDhGo4bNNuedSWk5yMZe29KcdsxpteG4Yl0rRQsM4LMQt6JUuLorLZBfK4yPYgKahIanZZ0GSAtfyX7g1ORZtphHEK4jY2krEIDYuNb6PWsX34RIhCAyv9eoJ5pFCAdCn9SdD6C4eGTrzsXDOa29MJ1E5H4HIYCrDBYvfnwybEJbKIAmXTFrk5Ald05qxLG3t2Zb0kQI=', 'MCfPdCq3BOTZT!2s4atyWCkdgxrZ5G8TsBfiyZL3dwyBD36Dj3WeSq4YvG6rrDMIpsfJhNQY*54hHV5oL8VMRD3b5G!i0Kgi1Bi6Mb0OYhiRnH6EjmOdHqEs5vvo8pQVi79EDNXkYyRpjfI7osZt7uzK8uqFCIV0I8ByQ0no*WfDQ8REfXor7g5W4b9Pg5Mke0a4ICatWmp61z1FUhjAr6*xhLo!WFYZabkEnhR2nCFeSE1QBfKiGNePe9mWfyBxbsJ3ZWgtt9wqA1r6ntX4PU!DM3HxdXsm8E3zJD*xQ0mZ8kz8ot5F8TODXZSsdAVyF4I3exrIXMdrQiQbvXM7!tJ3OABbOOACoP!6*fcT48WUnhRiaty6Ubvr8whIT6M6Sh*R4xdPmkFHftFWjjY1glj8$', 3600, '2020-04-23 22:27:28');

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
