-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2025 at 05:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_mis`
--

-- --------------------------------------------------------

--
-- Table structure for table `alivestock_entered`
--

CREATE TABLE `alivestock_entered` (
  `hh_id` varchar(250) NOT NULL,
  `category_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `amachineries_entered`
--

CREATE TABLE `amachineries_entered` (
  `hh_id` varchar(250) NOT NULL,
  `category_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appliances_entered`
--

CREATE TABLE `appliances_entered` (
  `res_id` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `res_id` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `filename` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bldg_amenities_entered`
--

CREATE TABLE `bldg_amenities_entered` (
  `hh_id` varchar(250) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bldg_info`
--

CREATE TABLE `bldg_info` (
  `hh_id` varchar(250) NOT NULL,
  `bldg_type_id` int(11) NOT NULL,
  `construction_yr` timestamp NOT NULL DEFAULT current_timestamp(),
  `yr_occupied` timestamp NOT NULL DEFAULT current_timestamp(),
  `bldg_permit_no` varchar(255) NOT NULL,
  `lot_no` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brgy_code`
--

CREATE TABLE `brgy_code` (
  `id` int(11) NOT NULL,
  `brgy_name` text NOT NULL,
  `code` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brgy_code`
--

INSERT INTO `brgy_code` (`id`, `brgy_name`, `code`, `status`) VALUES
(1, 'CENTRO 2', '0002', 'ACTIVE'),
(2, 'BANGAN', '0003', 'ACTIVE'),
(3, 'MAGACAN', '0009', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `comm_entered`
--

CREATE TABLE `comm_entered` (
  `hh_id` varchar(250) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comorbidities_entered`
--

CREATE TABLE `comorbidities_entered` (
  `res_id` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooking_entered`
--

CREATE TABLE `cooking_entered` (
  `hh_id` varchar(250) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dialect_entered`
--

CREATE TABLE `dialect_entered` (
  `res_id` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disability_entered`
--

CREATE TABLE `disability_entered` (
  `res_id` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `encoding_schedule`
--

CREATE TABLE `encoding_schedule` (
  `id` int(11) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `encoding_schedule`
--

INSERT INTO `encoding_schedule` (`id`, `start_date`, `end_date`) VALUES
(1, '2025-04-10 03:04:56', '2025-04-10 03:04:56');

-- --------------------------------------------------------

--
-- Table structure for table `garbage_entered`
--

CREATE TABLE `garbage_entered` (
  `hh_id` varchar(250) NOT NULL,
  `hazardous` varchar(10) NOT NULL,
  `recyclable` varchar(10) NOT NULL,
  `residual` varchar(10) NOT NULL,
  `biodegradable` varchar(10) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gprograms_entered`
--

CREATE TABLE `gprograms_entered` (
  `res_id` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `date_acquired` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `h_appliances_entered`
--

CREATE TABLE `h_appliances_entered` (
  `hh_id` varchar(250) NOT NULL,
  `category_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `h_vehicle_entered`
--

CREATE TABLE `h_vehicle_entered` (
  `hh_id` varchar(250) NOT NULL,
  `category_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `map_source`
--

CREATE TABLE `map_source` (
  `id` int(11) NOT NULL,
  `filename` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `power_entered`
--

CREATE TABLE `power_entered` (
  `hh_id` varchar(250) NOT NULL,
  `category_id` int(11) NOT NULL,
  `ave_per_mo` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sanitation_entered`
--

CREATE TABLE `sanitation_entered` (
  `hh_id` varchar(250) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sincome_entered`
--

CREATE TABLE `sincome_entered` (
  `res_id` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblactivity`
--

CREATE TABLE `tblactivity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_done` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblbanner`
--

CREATE TABLE `tblbanner` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` longtext NOT NULL,
  `img_path` text NOT NULL,
  `status` text NOT NULL,
  `brgy_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL,
  `category` text NOT NULL,
  `description` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblcertificate`
--

CREATE TABLE `tblcertificate` (
  `id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `business_name` text NOT NULL,
  `purpose` longtext NOT NULL,
  `document_type` text NOT NULL,
  `ctc_no` text NOT NULL,
  `ctc_date` datetime NOT NULL,
  `control_no` text NOT NULL,
  `document_fee` double NOT NULL,
  `amount_paid` double NOT NULL,
  `payment_status` text NOT NULL,
  `payment_method` text NOT NULL,
  `or_no` text NOT NULL,
  `or_date` datetime NOT NULL,
  `application_status` text NOT NULL,
  `status` text NOT NULL,
  `brgy_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbldocument_fee`
--

CREATE TABLE `tbldocument_fee` (
  `id` int(11) NOT NULL,
  `document_type` text NOT NULL,
  `fee` double NOT NULL,
  `brgy_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblofficial`
--

CREATE TABLE `tblofficial` (
  `id` int(11) NOT NULL,
  `lname` text NOT NULL,
  `fname` text NOT NULL,
  `mname` text NOT NULL,
  `suffix` text NOT NULL,
  `fullname` text NOT NULL,
  `bday` datetime NOT NULL,
  `age` int(11) NOT NULL,
  `email` text NOT NULL,
  `cp` text NOT NULL,
  `img_path` text NOT NULL,
  `position_id` int(11) NOT NULL,
  `term` text NOT NULL,
  `brgy_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblposts`
--

CREATE TABLE `tblposts` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` longtext NOT NULL,
  `category_id` int(11) NOT NULL,
  `img_path` text NOT NULL,
  `status` text NOT NULL,
  `brgy_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblresident`
--

CREATE TABLE `tblresident` (
  `id` bigint(20) NOT NULL,
  `lname` text NOT NULL,
  `fname` text NOT NULL,
  `mname` text NOT NULL,
  `suffix` text NOT NULL,
  `fullname` text NOT NULL,
  `bday` datetime DEFAULT NULL,
  `age` int(11) NOT NULL,
  `bplace` text NOT NULL,
  `gender` text NOT NULL,
  `cstatus_id` int(11) NOT NULL,
  `educ_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `rel_id` int(11) NOT NULL,
  `phealth_no` varchar(255) NOT NULL,
  `occ_id` int(11) NOT NULL,
  `m_income` double NOT NULL,
  `cp` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nstatus` text NOT NULL,
  `relation_hh` int(11) NOT NULL,
  `relation_fh` int(11) NOT NULL,
  `fh_id` text NOT NULL,
  `btype` varchar(10) NOT NULL,
  `height` double NOT NULL,
  `weight` double NOT NULL,
  `img_path` text NOT NULL,
  `house_no` varchar(50) NOT NULL,
  `street` text NOT NULL,
  `add_id` int(11) NOT NULL,
  `isHead` text NOT NULL,
  `status` text NOT NULL,
  `household` varchar(250) NOT NULL,
  `resident_id` varchar(255) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbltemp_resident`
--

CREATE TABLE `tbltemp_resident` (
  `id` text NOT NULL,
  `lname` text NOT NULL,
  `fname` text NOT NULL,
  `mname` text NOT NULL,
  `suffix` text NOT NULL,
  `fullname` text NOT NULL,
  `bday` datetime DEFAULT NULL,
  `age` int(11) NOT NULL,
  `bplace` text NOT NULL,
  `gender` text NOT NULL,
  `cstatus_id` int(11) NOT NULL,
  `educ_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `rel_id` int(11) NOT NULL,
  `phealth_no` varchar(255) NOT NULL,
  `occ_id` int(11) NOT NULL,
  `m_income` double NOT NULL,
  `cp` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nstatus` text NOT NULL,
  `relation_hh` int(11) NOT NULL,
  `relation_fh` int(11) NOT NULL,
  `fh_id` text NOT NULL,
  `btype` varchar(10) NOT NULL,
  `height` double NOT NULL,
  `weight` double NOT NULL,
  `img_path` text NOT NULL,
  `house_no` varchar(50) NOT NULL,
  `street` text NOT NULL,
  `add_id` int(11) NOT NULL,
  `isHead` text NOT NULL,
  `status` text NOT NULL,
  `household` varchar(250) NOT NULL,
  `resident_id` varchar(255) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbluploads`
--

CREATE TABLE `tbluploads` (
  `id` int(11) NOT NULL,
  `file_path` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `lname` text NOT NULL,
  `fname` text NOT NULL,
  `mname` text NOT NULL,
  `suffix` text NOT NULL,
  `fullname` text NOT NULL,
  `gender` text NOT NULL,
  `bday` datetime NOT NULL,
  `age` int(11) NOT NULL,
  `cstatus_id` int(11) NOT NULL,
  `email` text NOT NULL,
  `cp` varchar(15) NOT NULL,
  `role` text NOT NULL,
  `brgy_id` int(11) NOT NULL,
  `res_id` text NOT NULL,
  `status` text NOT NULL,
  `image` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`id`, `username`, `password`, `lname`, `fname`, `mname`, `suffix`, `fullname`, `gender`, `bday`, `age`, `cstatus_id`, `email`, `cp`, `role`, `brgy_id`, `res_id`, `status`, `image`, `created_on`, `updated_on`) VALUES
(10, 's@nch3zM1ra', '$2y$10$C05x3NYdyPl1aK64e6fGMuKKPulxT7PijW.orxcaP2.ktPKjSrYRq', 'ADMIN', 'SYSTEM', '', '', 'SYSTEM ADMIN', '', '0000-00-00 00:00:00', 0, 0, 'ejcasil@webdev-system.com', '09998105045', 'MAIN', 0, '', 'ACTIVE', '', '2025-01-21 14:34:00', '2025-03-10 15:37:38');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brgy_profile`
--

CREATE TABLE `tbl_brgy_profile` (
  `id` int(11) NOT NULL,
  `logo` text NOT NULL,
  `brgy_id` int(11) NOT NULL,
  `municipality` text NOT NULL,
  `province` text NOT NULL,
  `region` text NOT NULL,
  `official_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_entered`
--

CREATE TABLE `training_entered` (
  `res_id` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_certification`
--

CREATE TABLE `uploaded_certification` (
  `id` int(11) NOT NULL,
  `certificate_id` int(11) NOT NULL,
  `file_name` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_entered`
--

CREATE TABLE `vehicle_entered` (
  `res_id` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `water_entered`
--

CREATE TABLE `water_entered` (
  `hh_id` varchar(250) NOT NULL,
  `category_id` int(11) NOT NULL,
  `ave_per_mo` double NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zp_code`
--

CREATE TABLE `zp_code` (
  `id` int(11) NOT NULL,
  `brgy_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `code` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brgy_code`
--
ALTER TABLE `brgy_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `encoding_schedule`
--
ALTER TABLE `encoding_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `map_source`
--
ALTER TABLE `map_source`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblactivity`
--
ALTER TABLE `tblactivity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbanner`
--
ALTER TABLE `tblbanner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcertificate`
--
ALTER TABLE `tblcertificate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbldocument_fee`
--
ALTER TABLE `tbldocument_fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblofficial`
--
ALTER TABLE `tblofficial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblposts`
--
ALTER TABLE `tblposts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblresident`
--
ALTER TABLE `tblresident`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbluploads`
--
ALTER TABLE `tbluploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_brgy_profile`
--
ALTER TABLE `tbl_brgy_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploaded_certification`
--
ALTER TABLE `uploaded_certification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zp_code`
--
ALTER TABLE `zp_code`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brgy_code`
--
ALTER TABLE `brgy_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `encoding_schedule`
--
ALTER TABLE `encoding_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `map_source`
--
ALTER TABLE `map_source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblactivity`
--
ALTER TABLE `tblactivity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1287;

--
-- AUTO_INCREMENT for table `tblbanner`
--
ALTER TABLE `tblbanner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `tblcertificate`
--
ALTER TABLE `tblcertificate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbldocument_fee`
--
ALTER TABLE `tbldocument_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblofficial`
--
ALTER TABLE `tblofficial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tblposts`
--
ALTER TABLE `tblposts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tblresident`
--
ALTER TABLE `tblresident`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `tbluploads`
--
ALTER TABLE `tbluploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_brgy_profile`
--
ALTER TABLE `tbl_brgy_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `uploaded_certification`
--
ALTER TABLE `uploaded_certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `zp_code`
--
ALTER TABLE `zp_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
