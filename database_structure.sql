-- phpMyAdmin SQL Dump
-- version 4.6.5.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 27, 2016 at 05:29 AM
-- Server version: 5.5.52-0+deb8u1
-- PHP Version: 5.6.27-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `pi`
--

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE `temp` (
  `id` int(11) NOT NULL,
  `reading` int(11) NOT NULL,
  `voltage` decimal(8,2) DEFAULT NULL,
  `temp` decimal(8,2) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`id`);

CREATE TABLE `data` (
  `name` varchar(16) NOT NULL,
  `current_value` varchar(32) NOT NULL,
  `is_control` enum('0','1') NOT NULL DEFAULT '0',
  `set_hi` varchar(32) DEFAULT NULL,
  `set_low` varchar(32) DEFAULT NULL,
  `trig_on_state` enum('hi','low') NOT NULL DEFAULT 'hi',
  `delay_sec` int(10) NOT NULL DEFAULT '5',
  `output` enum('0','1') NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`name`);