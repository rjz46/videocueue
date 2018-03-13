-- phpMyAdmin SQL Dump
-- version 4.6.5.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8888
-- Generation Time: Mar 13, 2018 at 06:44 PM
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
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`person`, `ind`, `time`) VALUES
('Jim', 1, '2018-03-13 17:44:10'),
('Ru', 2, '2018-03-13 17:44:10'),
('Luping', 3, '2018-03-13 17:44:11'),
('Liye', 4, '2018-03-13 17:44:11'),
('Elise', 5, '2018-03-13 17:44:12'),
('Paula', 6, '2018-03-13 17:44:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`ind`);
