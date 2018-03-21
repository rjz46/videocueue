-- phpMyAdmin SQL Dump
-- version 4.6.5.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8888
-- Generation Time: Mar 21, 2018 at 11:31 PM
-- Server version: 5.6.34
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `CMC`
--

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `person` text,
  `ind` int(11) NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `next` timestamp NULL DEFAULT NULL,
  `removed` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`person`, `ind`, `added`, `next`, `removed`) VALUES
('Jim', 1, '2018-03-21 22:30:30', '2018-03-21 22:30:38', NULL),
('Luping', 2, '2018-03-21 22:30:30', '2018-03-21 22:30:40', NULL),
('Ru', 3, '2018-03-21 22:30:31', NULL, '2018-03-21 22:30:39'),
('Liye', 4, '2018-03-21 22:30:31', '2018-03-21 22:31:01', NULL),
('Elise', 5, '2018-03-21 22:30:32', NULL, '2018-03-21 22:30:41'),
('Paula', 6, '2018-03-21 22:30:32', '2018-03-21 22:31:16', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`ind`);
