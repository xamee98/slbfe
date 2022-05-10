-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2022 at 06:08 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `slbfe_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `citizens`
--

CREATE TABLE `citizens` (
  `national_id` varchar(12) NOT NULL,
  `name` varchar(190) NOT NULL,
  `age` int(11) NOT NULL,
  `address` varchar(500) NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `profession` varchar(190) NOT NULL,
  `email` varchar(190) NOT NULL,
  `affiliation` varchar(190) DEFAULT NULL,
  `password` varchar(190) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'activated',
  `information_verification` varchar(30) NOT NULL DEFAULT 'not_verified',
  `qualifications` text DEFAULT NULL,
  `birth_certificate` text DEFAULT NULL,
  `resume` text DEFAULT NULL,
  `passport` text DEFAULT NULL,
  `device_token` text DEFAULT NULL,
  `added_by` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `citizens`
--

INSERT INTO `citizens` (`national_id`, `name`, `age`, `address`, `latitude`, `longitude`, `profession`, `email`, `affiliation`, `password`, `status`, `information_verification`, `qualifications`, `birth_certificate`, `resume`, `passport`, `device_token`, `added_by`) VALUES
('972333', 'nilanka', 12, 'no-256/1,mahahella,beliatta', 12.5, 12.4, 'sse', 'user@gmail.comf', 'no', '81dc9bdb52d04dc20036dbd8313ed055', 'activated', 'not_verified', NULL, NULL, NULL, NULL, NULL, '2022-05-10 14:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `national_id` varchar(12) NOT NULL,
  `name` varchar(190) NOT NULL,
  `age` int(11) NOT NULL,
  `address` varchar(500) NOT NULL,
  `profession` varchar(190) DEFAULT NULL,
  `email` varchar(190) NOT NULL,
  `affiliation` varchar(190) DEFAULT NULL,
  `password` varchar(190) NOT NULL,
  `device_token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `citizens`
--
ALTER TABLE `citizens`
  ADD PRIMARY KEY (`national_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`national_id`),
  ADD UNIQUE KEY `email` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
