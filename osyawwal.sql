-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 10, 2014 at 01:07 AM
-- Server version: 5.5.38-MariaDB
-- PHP Version: 5.5.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `osyawwal`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
`id` int(11) NOT NULL,
  `satker_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `hostel` tinyint(1) DEFAULT '0',
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `satker_id`, `name`, `description`, `start`, `end`, `location`, `hostel`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(6, 23, 'PRANATA KOMPUTER AHLI AKT I', 'Desc', '2014-10-01 00:00:00', '2014-11-03 00:00:00', '23|-', 1, 1, '2014-10-03 05:16:47', 1, '2014-10-08 10:32:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `activity_history`
--

CREATE TABLE IF NOT EXISTS `activity_history` (
  `id` int(11) NOT NULL,
  `revision` int(11) NOT NULL,
  `satker_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `hostel` tinyint(1) DEFAULT '0',
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activity_history`
--

INSERT INTO `activity_history` (`id`, `revision`, `satker_id`, `name`, `description`, `start`, `end`, `location`, `hostel`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(6, 0, 23, 'PRANATA KOMPUTER AHLI AKT I', 'Desc', '2014-10-01 00:00:00', '2014-11-03 00:00:00', '23|-', 1, 1, '2014-10-03 05:16:47', 1, '2014-10-08 10:32:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `activity_room`
--

CREATE TABLE IF NOT EXISTS `activity_room` (
  `activity_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL COMMENT '0=Waiting, 1=Process, 2=Approved, 3=Rejected',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `person_id` int(11) NOT NULL,
  `satker_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `organisation_id` int(11) DEFAULT NULL,
  `chairman` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`person_id`, `satker_id`, `user_id`, `organisation_id`, `chairman`) VALUES
(1, 23, 1, 387, 1),
(12, 23, 6, 394, 0),
(13, 23, 5, 393, 0),
(18, 23, 10, 394, 0),
(19, 23, 11, 397, 0),
(69, 23, 12, 393, 0);

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`id`, `name`, `file_name`, `file_type`, `file_size`, `description`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(6, '542a858885d8e.jpg', '542a858885d8e.jpg', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(7, '542a8153a85ed.xls', '542a8153a85ed.xls', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(8, '542a831218b3b.png', '542a831218b3b.png', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(9, '542a85b49e638.jpg', '542a85b49e638.jpg', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(10, '542b578aa3973.jpg', '542b578aa3973.jpg', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(11, '542cc59a5ef7f.jpg', '542cc59a5ef7f.jpg', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(13, '542f2c659d4ad.png', '542f2c659d4ad.png', NULL, NULL, NULL, 1, '2014-10-04 06:08:21', 1, '2014-10-04 06:08:21', 1),
(15, '542f2cedb662e.png', '542f2cedb662e.png', NULL, NULL, NULL, 0, '2014-10-04 06:10:37', 1, '2014-10-04 07:21:11', 1),
(16, '542f355f7da8c.png', '542f355f7da8c.png', NULL, NULL, NULL, 0, '2014-10-04 06:46:39', 1, '2014-10-04 07:24:15', 1),
(17, '542f3595c99ce.png', '542f3595c99ce.png', NULL, NULL, NULL, 0, '2014-10-04 06:47:33', 1, '2014-10-04 06:47:33', 1),
(18, '542f3ea1eed6b.png', '542f3ea1eed6b.png', NULL, NULL, NULL, 0, '2014-10-04 07:26:09', 1, '2014-10-04 07:26:09', 1),
(19, '542f6dee465ce.jpg', '542f6dee465ce.jpg', NULL, NULL, NULL, 0, '2014-10-04 10:47:58', 1, '2014-10-04 10:47:58', 1),
(20, 'OK', '542f6e9881de8.png', NULL, NULL, NULL, 1, '2014-10-04 10:50:48', 1, '2014-10-04 12:44:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE IF NOT EXISTS `meeting` (
  `activity_id` int(11) NOT NULL,
  `attendance_count_plan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
`id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(256) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
`id` int(11) NOT NULL,
  `type` int(3) NOT NULL,
  `category` int(3) NOT NULL,
  `author` int(11) NOT NULL,
  `recipient` int(11) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1411603868),
('m130524_201442_init', 1411603902);

-- --------------------------------------------------------

--
-- Table structure for table `object_file`
--

CREATE TABLE IF NOT EXISTS `object_file` (
  `object` varchar(100) NOT NULL,
  `object_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '',
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `object_file`
--

INSERT INTO `object_file` (`object`, `object_id`, `type`, `file_id`) VALUES
('person', 1, 'photo', 6),
('person', 1, 'sk_cpns', 7),
('person', 1, 'sk_pangkat', 8),
('person', 12, 'photo', 10),
('person', 13, 'photo', 9),
('program', 2, 'gbpp', 16),
('program', 2, 'gbpp', 17),
('program', 2, 'gbpp', 18),
('program', 2, 'kap', 15),
('program', 2, 'module', 19),
('program', 2, 'module', 20),
('program', 2, 'validation_document', 11);

-- --------------------------------------------------------

--
-- Table structure for table `object_person`
--

CREATE TABLE IF NOT EXISTS `object_person` (
  `object` varchar(100) NOT NULL,
  `object_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '',
  `person_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `object_person`
--

INSERT INTO `object_person` (`object`, `object_id`, `type`, `person_id`) VALUES
('program', 2, 'pic', 1),
('user', 1, 'employee', 1),
('activity', 6, 'organisation_1213020200', 12),
('activity', 6, 'organisation_1213020300', 12),
('program', 2, 'organisation_1213020200', 12),
('program', 2, 'organisation_1213020300', 12),
('program', 2, 'organisation_394', 12),
('user', 4, 'employee', 12),
('user', 6, 'employee', 12),
('activity', 6, 'organisation_393', 13),
('activity', 6, 'pic_planning_1', 13),
('program', 2, 'organisation_393', 13),
('user', 5, 'employee', 13),
('activity', 6, 'organisation_1213030100', 19);

-- --------------------------------------------------------

--
-- Table structure for table `object_reference`
--

CREATE TABLE IF NOT EXISTS `object_reference` (
  `object` varchar(100) NOT NULL,
  `object_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT 'graduate,rank_class,religion,satker,finance_position',
  `reference_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `object_reference`
--

INSERT INTO `object_reference` (`object`, `object_id`, `type`, `reference_id`) VALUES
('employee', 1, 'finance_position', NULL),
('employee', 1, 'fungsional', NULL),
('employee', 1, 'pranata_komputer', NULL),
('employee', 1, 'pranata_komputer_ahli', NULL),
('employee', 1, 'pranata_komputer_terampil', NULL),
('employee', 1, 'widyaiswara', NULL),
('employee', 12, 'finance_position', NULL),
('employee', 12, 'fungsional', NULL),
('employee', 12, 'pranata_komputer', NULL),
('employee', 12, 'pranata_komputer_ahli', NULL),
('employee', 12, 'pranata_komputer_terampil', NULL),
('employee', 12, 'widyaiswara', NULL),
('employee', 13, 'finance_position', NULL),
('employee', 13, 'fungsional', NULL),
('employee', 13, 'pranata_komputer', NULL),
('employee', 13, 'pranata_komputer_ahli', NULL),
('employee', 13, 'pranata_komputer_terampil', NULL),
('employee', 13, 'satker', NULL),
('employee', 13, 'unit', NULL),
('employee', 13, 'widyaiswara', NULL),
('employee', 18, 'finance_position', NULL),
('employee', 18, 'fungsional', NULL),
('employee', 18, 'pranata_komputer', NULL),
('employee', 18, 'pranata_komputer_ahli', NULL),
('employee', 18, 'pranata_komputer_terampil', NULL),
('employee', 18, 'unit', NULL),
('employee', 18, 'widyaiswara', NULL),
('employee', 19, 'finance_position', NULL),
('employee', 19, 'fungsional', NULL),
('employee', 19, 'pranata_komputer', NULL),
('employee', 19, 'pranata_komputer_ahli', NULL),
('employee', 19, 'pranata_komputer_terampil', NULL),
('employee', 19, 'unit', NULL),
('employee', 19, 'widyaiswara', NULL),
('employee', 69, 'finance_position', NULL),
('employee', 69, 'fungsional', NULL),
('employee', 69, 'pranata_komputer', NULL),
('employee', 69, 'pranata_komputer_ahli', NULL),
('employee', 69, 'pranata_komputer_terampil', NULL),
('employee', 69, 'unit', NULL),
('employee', 69, 'widyaiswara', NULL),
('person', 1, 'graduate', NULL),
('person', 1, 'rank_class', NULL),
('person', 1, 'religion', NULL),
('person', 12, 'graduate', NULL),
('person', 12, 'rank_class', NULL),
('person', 12, 'religion', NULL),
('person', 12, 'satker', NULL),
('person', 13, 'graduate', NULL),
('person', 14, 'graduate', NULL),
('person', 14, 'rank_class', NULL),
('person', 14, 'religion', NULL),
('person', 18, 'graduate', NULL),
('person', 18, 'rank_class', NULL),
('person', 18, 'religion', NULL),
('person', 19, 'graduate', NULL),
('person', 19, 'rank_class', NULL),
('person', 19, 'religion', NULL),
('person', 20, 'graduate', NULL),
('person', 20, 'rank_class', NULL),
('person', 20, 'religion', NULL),
('person', 69, 'graduate', NULL),
('person', 69, 'rank_class', NULL),
('person', 69, 'religion', NULL),
('person', 69, 'unit', NULL),
('employee', 69, 'satker', 17),
('employee', 1, 'satker', 23),
('employee', 12, 'satker', 23),
('person', 13, 'rank_class', 75),
('person', 13, 'religion', 91),
('person', 20, 'unit', 121),
('person', 21, 'unit', 121),
('person', 22, 'unit', 121),
('person', 23, 'unit', 121),
('person', 24, 'unit', 121),
('person', 25, 'unit', 121),
('person', 26, 'unit', 121),
('person', 27, 'unit', 121),
('person', 28, 'unit', 121),
('person', 29, 'unit', 121),
('person', 30, 'unit', 121),
('person', 31, 'unit', 121),
('person', 32, 'unit', 121),
('person', 33, 'unit', 121),
('person', 34, 'unit', 121),
('person', 35, 'unit', 121),
('person', 36, 'unit', 121),
('person', 37, 'unit', 121),
('person', 38, 'unit', 121),
('person', 39, 'unit', 121),
('person', 40, 'unit', 121),
('person', 41, 'unit', 121),
('person', 42, 'unit', 121),
('person', 43, 'unit', 121),
('person', 44, 'unit', 121),
('person', 45, 'unit', 121),
('person', 46, 'unit', 121),
('person', 47, 'unit', 121),
('person', 48, 'unit', 121),
('person', 49, 'unit', 121),
('person', 50, 'unit', 121),
('person', 51, 'unit', 121),
('person', 52, 'unit', 121),
('person', 53, 'unit', 121),
('person', 54, 'unit', 121),
('person', 55, 'unit', 121),
('person', 56, 'unit', 121),
('person', 57, 'unit', 121),
('person', 58, 'unit', 121),
('person', 59, 'unit', 121),
('person', 60, 'unit', 121),
('person', 61, 'unit', 121),
('person', 62, 'unit', 121),
('person', 63, 'unit', 121),
('person', 64, 'unit', 121),
('person', 65, 'unit', 121),
('person', 66, 'unit', 121),
('person', 67, 'unit', 121),
('person', 68, 'unit', 121),
('employee', 1, 'unit', 132),
('employee', 12, 'unit', 132),
('person', 1, 'unit', 132);

-- --------------------------------------------------------

--
-- Table structure for table `organisation`
--

CREATE TABLE IF NOT EXISTS `organisation` (
`ID` int(11) NOT NULL,
  `KD_UNIT_ORG` char(10) NOT NULL,
  `KD_UNIT_ES1` char(2) NOT NULL,
  `KD_UNIT_ES2` char(2) NOT NULL,
  `KD_UNIT_ES3` char(2) NOT NULL,
  `KD_UNIT_ES4` char(2) NOT NULL,
  `KD_UNIT_ES5` char(2) NOT NULL,
  `JNS_KANTOR` int(11) NOT NULL,
  `NM_UNIT_ORG` varchar(100) NOT NULL,
  `KD_ESELON` char(2) NOT NULL,
  `KD_SURAT_ORG` varchar(100) NOT NULL,
  `TKT_ESELON` char(2) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=403 ;

--
-- Dumping data for table `organisation`
--

INSERT INTO `organisation` (`ID`, `KD_UNIT_ORG`, `KD_UNIT_ES1`, `KD_UNIT_ES2`, `KD_UNIT_ES3`, `KD_UNIT_ES4`, `KD_UNIT_ES5`, `JNS_KANTOR`, `NM_UNIT_ORG`, `KD_ESELON`, `KD_SURAT_ORG`, `TKT_ESELON`) VALUES
(1, '1200000001', '12', '00', '00', '00', '01', 1, 'BADAN PENDIDIKAN DAN PELATIHAN KEUANGAN', '1', '', '11'),
(3, '1201000000', '12', '01', '00', '00', '00', 2, 'SEKRETARIAT BADAN', '2', '', '21'),
(4, '1201010000', '12', '01', '01', '00', '00', 2, 'BAGIAN KEPEGAWAIAN', '3', '3', '31'),
(5, '1201010100', '12', '01', '01', '01', '00', 2, 'SUBBAGIAN UMUM KEPEGAWAIAN', '4', '34', '41'),
(6, '1201010201', '12', '01', '01', '02', '01', 2, 'SUBBAGIAN PENGEMBANGAN PEGAWAI', '4', '31', '41'),
(8, '1201010300', '12', '01', '01', '03', '00', 2, 'SUBBAGIAN ADMINISTRASI JABATAN FUNGSIONAL', '4', '32', '41'),
(9, '1201010400', '12', '01', '01', '04', '00', 2, 'SUBBAGIAN KEPATUHAN INTERNAL', '4', '33', '41'),
(10, '1201020002', '12', '01', '02', '00', '02', 2, 'BAGIAN ORGANISASI DAN TATALAKSANA', '3', '2', '31'),
(13, '1201020102', '12', '01', '02', '01', '02', 2, 'SUBBAGIAN ORGANISASI', '4', '', '41'),
(16, '1201020202', '12', '01', '02', '02', '02', 2, 'SUBBAGIAN TATALAKSANA', '4', '', '41'),
(19, '1201020302', '12', '01', '02', '03', '02', 2, 'SUBBAGIAN HUKUM DAN KERJASAMA', '4', '', '41'),
(22, '1201030000', '12', '01', '03', '00', '00', 2, 'BAGIAN KEUANGAN', '3', '', '31'),
(23, '1201030100', '12', '01', '03', '01', '00', 2, 'SUBBAGIAN PENYUSUNAN ANGGARAN', '4', '', '41'),
(24, '1201030201', '12', '01', '03', '02', '01', 2, 'SUBBAGIAN PERBENDAHARAAN', '4', '', '41'),
(26, '1201030302', '12', '01', '03', '03', '02', 2, 'SUBBAGIAN AKUNTANSI DAN PELAPORAN', '4', '', '41'),
(29, '1201040003', '12', '01', '04', '00', '03', 2, 'BAGIAN TEKNOLOGI DAN INFORMASI KOMUNIKASI', '3', '', '31'),
(33, '1201040103', '12', '01', '04', '01', '03', 2, 'SUBBAGIAN SISTEM INFORMASI', '4', '', '41'),
(37, '1201040203', '12', '01', '04', '02', '03', 2, 'SUBBAGIAN KOMUNIKASI PUBLIK', '4', '', '41'),
(41, '1201040302', '12', '01', '04', '03', '02', 2, 'SUBBAGIAN DUKUNGAN TEKNIS', '4', '', '41'),
(44, '1201050000', '12', '01', '05', '00', '00', 2, 'BAGIAN UMUM', '3', '', '31'),
(45, '1201050101', '12', '01', '05', '01', '01', 2, 'SUBBAGIAN TATA USAHA', '4', '', '41'),
(47, '1201050202', '12', '01', '05', '02', '02', 2, 'SUBBAGIAN PENGELOLAAN ASET', '4', '', '41'),
(50, '1201050302', '12', '01', '05', '03', '02', 2, 'SUBBAGIAN RUMAH TANGGA', '4', '', '41'),
(56, '1202000002', '12', '02', '00', '00', '02', 3, 'Pusdiklat Pengembangan Sumber Daya Manusia', '2', '', '21'),
(59, '1202010001', '12', '02', '01', '00', '01', 3, 'Bagian Tata Usaha', '3', '', '31'),
(61, '1202010101', '12', '02', '01', '01', '01', 3, 'Subbagian Tata Usaha, Kepegawaian Dan Hubungan Masyarakat', '4', '', '41'),
(63, '1202010201', '12', '02', '01', '02', '01', 3, 'Subbagian Perencanaan Dan Keuangan', '4', '', '41'),
(65, '1202010301', '12', '02', '01', '03', '01', 3, 'Subbagian Rumah Tangga Dan Pengelolaan Aset', '4', '', '41'),
(66, '1202020000', '12', '02', '02', '00', '00', 3, 'Bidang Penjenjangan Pangkat Dan Peningkatan Kompetensi', '3', '', '31'),
(67, '1202020100', '12', '02', '02', '01', '00', 3, 'Subbidang Perencanaan Dan Pengembangan', '4', '', '41'),
(68, '1202020200', '12', '02', '02', '02', '00', 3, 'Subbidang Penyelenggaraan', '4', '', '41'),
(69, '1202020300', '12', '02', '02', '03', '00', 3, 'Subbidang Evaluasi Dan Pelaporan Kinerja', '4', '', '41'),
(70, '1202030000', '12', '02', '03', '00', '00', 3, 'Bidang Pengelolaan Tes Terpadu', '3', '', '31'),
(71, '1202030100', '12', '02', '03', '01', '00', 3, 'Subbidang Perencanaan Tes', '4', '', '41'),
(72, '1202030200', '12', '02', '03', '02', '00', 3, 'Subbidang Penyelenggaraan Tes', '4', '', '41'),
(73, '1202030300', '12', '02', '03', '03', '00', 3, 'Subbidang Evaluasi Hasil Tes', '4', '', '41'),
(76, '1202040002', '12', '02', '04', '00', '02', 3, 'Bidang Pengelolaan Beasiswa', '3', '', '31'),
(79, '1202040102', '12', '02', '04', '01', '02', 3, 'Subbidang Perencanaan Beasiswa', '4', '', '41'),
(81, '1202040201', '12', '02', '04', '02', '01', 3, 'Subbidang Seleksi Dan Penempatan', '4', '', '41'),
(82, '1202040300', '12', '02', '04', '03', '00', 3, 'Subbidang Pemantauan', '4', '', '41'),
(83, '1202050000', '12', '02', '05', '00', '00', 3, 'Balai Diklat Kepemimpinan', '3', '', '31'),
(84, '1202050101', '12', '02', '05', '01', '01', 3, 'Subbagian Tata Usaha Dan Kepatuhan Internal', '4', '', '41'),
(86, '1202050200', '12', '02', '05', '02', '00', 3, 'Seksi Penyelenggaraan', '4', '', '41'),
(87, '1202050300', '12', '02', '05', '03', '00', 3, 'Seksi Evaluasi Dan Informasi', '4', '', '41'),
(343, '1200010002', '12', '00', '01', '00', '02', 10, 'Balai Pendidikan Dan Pelatihan Keuangan', '3', '', '31'),
(345, '1200010101', '12', '00', '01', '01', '01', 10, 'Subbagian Tata Usaha Dan Kepatuhan Internal', '4', '', '41'),
(346, '1200010200', '12', '00', '01', '02', '00', 10, 'Seksi Penyelenggaraan', '4', '', '41'),
(347, '1200010300', '12', '00', '01', '03', '00', 10, 'Seksi Evaluasi Dan Informasi', '4', '', '41'),
(348, '1208000000', '12', '08', '00', '00', '00', 9, 'SEKOLAH TINGGI AKUNTANSI NEGARA', '2', '', '21'),
(349, '1208010000', '12', '08', '01', '00', '00', 9, 'SEKRETARIAT SEKOLAH TINGGI AKUNTANSI NEGARA', '3', '', '31'),
(350, '1208010200', '12', '08', '01', '02', '00', 9, 'SUBBAGIAN TATA USAHA DAN KEUANGAN', '4', '', '41'),
(351, '1208010100', '12', '08', '01', '01', '00', 9, 'SUBBAGIAN KEPEGAWAIAN DAN PERALATAN', '4', '', '41'),
(352, '1208010300', '12', '08', '01', '03', '00', 9, 'SUBBAGIAN PERALATAN', '4', '', '41'),
(353, '1208020000', '12', '08', '02', '00', '00', 9, 'BIDANG AKADEMIS PENDIDIKAN PEMBANTU AKUNTAN', '3', '', '31'),
(354, '1208020100', '12', '08', '02', '01', '00', 9, 'SUBBIDANG TATA LAKSANA PEMBANTU AKUNTAN', '4', '', '41'),
(355, '1208020200', '12', '08', '02', '02', '00', 9, 'SUBBIDANG PENGEMBANGAN PEMBANTU AKUNTAN', '4', '', '41'),
(356, '1208030000', '12', '08', '03', '00', '00', 9, 'BIDANG AKADEMIS PENDIDIKAN AJUN AKUNTAN', '3', '', '31'),
(357, '1208030100', '12', '08', '03', '01', '00', 9, 'SUBBIDANG TATA LAKSANA AJUN AKUNTAN', '4', '', '41'),
(358, '1208030200', '12', '08', '03', '02', '00', 9, 'SUBBIDANG PENGEMBANGAN AJUN AKUNTAN', '4', '', '41'),
(359, '1208040000', '12', '08', '04', '00', '00', 9, 'BIDANG AKADEMIS PENDIDIKAN AKUNTAN', '3', '', '31'),
(360, '1208040100', '12', '08', '04', '01', '00', 9, 'SUBBIDANG TATA LAKSANA AKUNTAN', '4', '', '41'),
(361, '1208040200', '12', '08', '04', '02', '00', 9, 'SUBBIDANG PENGEMBANGAN AKUNTAN', '4', '', '41'),
(387, '1213000000', '12', '13', '00', '00', '00', 13, 'Pusat Pendidikan dan Pelatihan', '2', '', '21'),
(388, '1213010000', '12', '13', '01', '00', '00', 13, 'Bagian Tata Usaha', '3', '', '31'),
(389, '1213010100', '12', '13', '01', '01', '00', 13, 'Subbagian Tata Usaha, Kepegawaian Dan Hubungan Masyarakat', '4', '', '41'),
(390, '1213010200', '12', '13', '01', '02', '00', 13, 'Subbagian Perencanaan Dan Keuangan', '4', '', '41'),
(391, '1213010300', '12', '13', '01', '03', '00', 13, 'Subbagian Rumah Tangga Dan Pengelolaan Aset', '4', '', '41'),
(392, '1213020000', '12', '13', '02', '00', '00', 13, 'Bidang Perencanaan dan Pengembangan Diklat', '3', '', '31'),
(393, '1213020100', '12', '13', '02', '01', '00', 13, 'Subbidang Program', '4', '', '41'),
(394, '1213020200', '12', '13', '02', '02', '00', 13, 'Subbidang Kurikulum', '4', '', '41'),
(395, '1213020300', '12', '13', '02', '03', '00', 13, 'Subbidang Tenaga Pengajar', '4', '', '41'),
(396, '1213030000', '12', '13', '03', '00', '00', 13, 'Bidang Penyelenggaraan', '3', '', '31'),
(397, '1213030100', '12', '13', '03', '01', '00', 13, 'Subbidang Penyelenggaraan I', '4', '', '41'),
(398, '1213030200', '12', '13', '03', '02', '00', 13, 'Subbidang Penyelenggaraan II', '4', '', '41'),
(399, '1213040000', '12', '13', '04', '00', '00', 13, 'Bidang Evaluasi dan Pelaporan Kinerja', '3', '', '31'),
(400, '1213040100', '12', '13', '04', '01', '00', 13, 'Subbidang Evaluasi Diklat', '4', '', '41'),
(401, '1213040200', '12', '13', '04', '02', '00', 13, 'Subbidang Pengolahan Hasil Diklat', '4', '', '41'),
(402, '1213040300', '12', '13', '04', '03', '00', 13, 'Subbidang Informasi Dan Pelaporan Kinerja', '4', '', '41');

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE IF NOT EXISTS `person` (
`id` int(11) NOT NULL,
  `nip` varchar(25) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `nickname` varchar(25) DEFAULT NULL,
  `front_title` varchar(25) DEFAULT NULL,
  `back_title` varchar(25) DEFAULT NULL,
  `nid` varchar(100) NOT NULL COMMENT 'No KTP  Passport',
  `npwp` varchar(100) DEFAULT NULL,
  `born` varchar(50) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` tinyint(1) DEFAULT '1',
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `office_phone` varchar(50) DEFAULT NULL,
  `office_fax` varchar(50) DEFAULT NULL,
  `office_email` varchar(100) DEFAULT NULL,
  `office_address` varchar(255) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `married` int(3) DEFAULT NULL,
  `blood` varchar(25) DEFAULT '-',
  `graduate_desc` varchar(255) DEFAULT NULL,
  `position` int(3) DEFAULT NULL,
  `position_desc` varchar(255) DEFAULT NULL,
  `organisation` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=70 ;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `nip`, `name`, `nickname`, `front_title`, `back_title`, `nid`, `npwp`, `born`, `birthday`, `gender`, `phone`, `email`, `homepage`, `address`, `office_phone`, `office_fax`, `office_email`, `office_address`, `bank_account`, `married`, `blood`, `graduate_desc`, `position`, `position_desc`, `organisation`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, '198604302009011002', 'Hafid Mukhlasin', 'Hafid', 'Dr.', 'S.Kom', '198604302009011002', '198604302009011002', 'Jember', '1986-04-30', 1, '081559915720', 'milisstudio@gmail.com', 'http://hafidmukhlasin.com', 'Cilodong Depok', '1', '2', '3', '4', 'Mandiri 126 000 545 4839', 1, '-', 'TEKNIK INFORMATIKA', 5, 'Pranata Komputer Ahli', 'BPPK', 1, NULL, NULL, NULL, NULL),
(12, '', 'Hari Dwipanjayani', '', '', '', '1234', '', '', NULL, 0, '081328023455', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', 'Swasta', 1, NULL, NULL, NULL, NULL),
(13, '123456', 'Admin Pusdiklat KU', 'pusdku', '', '', '123456', '', '', NULL, 1, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(18, '', 'staf KU', '', '', '', '222', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 0, NULL, NULL, NULL, NULL),
(19, '', 'Muhammad Asyrofi', '', '', '', '19890426', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(20, '198606052007011002', 'Ageng Budi Widiarto', '', '', '', '198606052007011002', '', '', '1986-06-05', 1, '', '', '', '', '', '', '', '', '', 0, '-', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(21, '197305071993011001', 'Agung Nana Permana', NULL, NULL, NULL, '197305071993011001', NULL, NULL, '1973-05-07', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(22, '198704302008121003', 'Andre Harahap', NULL, NULL, NULL, '198704302008121003', NULL, NULL, '1987-04-30', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(23, '196501011985032003', 'Anne Akbari N. H. C.', NULL, NULL, NULL, '196501011985032003', NULL, NULL, '1965-01-01', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(24, '196712111988021001', 'Armanzah', NULL, NULL, NULL, '196712111988021001', NULL, NULL, '1967-12-11', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(25, '197704291998031002', 'Bahrahmat Simamora', NULL, NULL, NULL, '197704291998031002', NULL, NULL, '1977-04-29', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(26, '196312281985031001', 'Dedyn Budi Prayoga', NULL, NULL, NULL, '196312281985031001', NULL, NULL, '1963-12-28', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(27, '197404131994031002', 'Didy Supriyadi', NULL, NULL, NULL, '197404131994031002', NULL, NULL, '1974-04-13', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(28, '198201062009011008', 'Dody Widayanto', NULL, NULL, NULL, '198201062009011008', NULL, NULL, '1982-01-06', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(29, '196704121992012001', 'Dwian Widyati Haristyani', NULL, NULL, NULL, '196704121992012001', NULL, NULL, '1967-04-12', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(30, '197802272000011003', 'Edy Setiawan', NULL, NULL, NULL, '197802272000011003', NULL, NULL, '1978-02-27', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(31, '197406011998031001', 'Edy Susanto', NULL, NULL, NULL, '197406011998031001', NULL, NULL, '1974-06-01', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(32, '197307021998032001', 'Fathonatan Dewi Nastiti', NULL, NULL, NULL, '197307021998032001', NULL, NULL, '1973-07-02', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(33, '196911291996031002', 'Ferdy Alfonsus Sihotang', NULL, NULL, NULL, '196911291996031002', NULL, NULL, '1969-11-29', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(34, '197903152000121006', 'Hadi Setiawan', NULL, NULL, NULL, '197903152000121006', NULL, NULL, '1979-03-15', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(35, '198105132002122001', 'Heny Setyawati', NULL, NULL, NULL, '198105132002122001', NULL, NULL, '1981-05-13', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(36, '196411051986031001', 'Indra Utama', NULL, NULL, NULL, '196411051986031001', NULL, NULL, '1964-11-05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(37, '197111181992031001', 'Iqravid Hajat', NULL, NULL, NULL, '197111181992031001', NULL, NULL, '1971-11-18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(38, '196209171983021002', 'Isnaidi', NULL, NULL, NULL, '196209171983021002', NULL, NULL, '1962-09-17', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(39, '196111051985031002', 'Jaitar Sirait', NULL, NULL, NULL, '196111051985031002', NULL, NULL, '1961-11-05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(40, '198708302008121002', 'Jarvik Fuad Rizky', NULL, NULL, NULL, '198708302008121002', NULL, NULL, '1987-08-30', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(41, '197801102002121002', 'M. Ichsan Firmansyah', NULL, NULL, NULL, '197801102002121002', NULL, NULL, '1978-01-10', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(42, '196206151983021001', 'M. Kayani', NULL, NULL, NULL, '196206151983021001', NULL, NULL, '1962-06-15', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(43, '198411052009012003', 'Maria Yosephina Siregar', NULL, NULL, NULL, '198411052009012003', NULL, NULL, '1984-11-05', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(44, '198405182006021002', 'Meiseno Purnawan', NULL, NULL, NULL, '198405182006021002', NULL, NULL, '1984-05-18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(45, '196205191988101001', 'Mohammad  Irwan', NULL, NULL, NULL, '196205191988101001', NULL, NULL, '1962-05-19', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(46, '197210231993021001', 'Muhaimin Zikri', NULL, NULL, NULL, '197210231993021001', NULL, NULL, '1972-10-23', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(47, '196705031992012001', 'Nany Nur Aini', NULL, NULL, NULL, '196705031992012001', NULL, NULL, '1967-05-03', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(48, '196707051998031001', 'Noor Cholis Yuana', NULL, NULL, NULL, '196707051998031001', NULL, NULL, '1967-07-05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(49, '196604101987031002', 'Nyamat', NULL, NULL, NULL, '196604101987031002', NULL, NULL, '1966-04-10', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(50, '198606022010122004', 'Prita Anindya', NULL, NULL, NULL, '198606022010122004', NULL, NULL, '1986-06-02', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(51, '198302112009012009', 'Putri Sion', NULL, NULL, NULL, '198302112009012009', NULL, NULL, '1983-02-11', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(52, '198511142009011007', 'Randi Mesarino', NULL, NULL, NULL, '198511142009011007', NULL, NULL, '1985-11-14', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(53, '196711221988032001', 'Ratnasari', NULL, NULL, NULL, '196711221988032001', NULL, NULL, '1967-11-22', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(54, '198306222006022001', 'Retno Maruti', NULL, NULL, NULL, '198306222006022001', NULL, NULL, '1983-06-22', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(55, '198506032006021001', 'Ridwan Ramdhani', NULL, NULL, NULL, '198506032006021001', NULL, NULL, '1985-06-03', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(56, '198606052007012002', 'Ristiana Susanti', NULL, NULL, NULL, '198606052007012002', NULL, NULL, '1986-06-05', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(57, '197004271990121001', 'Rustiyono', NULL, NULL, NULL, '197004271990121001', NULL, NULL, '1970-04-27', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(58, '197307051993031010', 'Saifudin', NULL, NULL, NULL, '197307051993031010', NULL, NULL, '1973-07-05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(59, '196506231986032003', 'Sri Sukesi, Ak.', NULL, NULL, NULL, '196506231986032003', NULL, NULL, '1965-06-23', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(60, '198711242010122004', 'Suci Lestari', NULL, NULL, NULL, '198711242010122004', NULL, NULL, '1987-11-24', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(61, '198006172001121002', 'Suhadiyan Syah Alam', NULL, NULL, NULL, '198006172001121002', NULL, NULL, '1980-06-17', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(62, '196904181990031001', 'Suparyadi', NULL, NULL, NULL, '196904181990031001', NULL, NULL, '1969-04-18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(63, '196309191985032001', 'Tirawan Mahulae', NULL, NULL, NULL, '196309191985032001', NULL, NULL, '1963-09-19', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(64, '196608031992011001', 'Tripto Tri Agustono', NULL, NULL, NULL, '196608031992011001', NULL, NULL, '1966-08-03', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(65, '196307181984021003', 'Untung Dwiyono', NULL, NULL, NULL, '196307181984021003', NULL, NULL, '1963-07-18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(66, '196602061992012001', 'Wijaya Wardhani', NULL, NULL, NULL, '196602061992012001', NULL, NULL, '1966-02-06', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(67, '199001122012122002', 'Yohana Intan Dias Sari', NULL, NULL, NULL, '199001122012122002', NULL, NULL, '1990-01-12', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(68, '198306162004121001', 'Yudhistira, S.AB.', NULL, NULL, NULL, '198306162004121001', NULL, NULL, '1983-06-16', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(69, '', 'Fajar Wahyu Abdillah', '', '', '', '135', '', '', NULL, 1, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `person_activity`
--

CREATE TABLE IF NOT EXISTS `person_activity` (
  `person_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE IF NOT EXISTS `program` (
`id` int(11) NOT NULL,
  `satker_id` int(11) NOT NULL,
  `number` varchar(15) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `hours` decimal(5,2) DEFAULT NULL,
  `days` int(3) DEFAULT NULL,
  `test` tinyint(1) DEFAULT '0',
  `note` varchar(255) DEFAULT NULL,
  `stage` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `validation_status` tinyint(1) DEFAULT '0',
  `validation_note` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `satker_id`, `number`, `name`, `hours`, `days`, `test`, `note`, `stage`, `category`, `validation_status`, `validation_note`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 0, '2.2.0.0', 'PRANATA KOMPUTER AHLI', NULL, NULL, 0, '', 'Komputer|Akuntansi', '', 0, '', 1, NULL, NULL, NULL, NULL),
(2, 23, '2.2.1.0', 'PRANATA KOMPUTER AHLI', '300.00', 30, 1, '-', 'Kebijakan Fiskal,Penganggaran', '3', 1, '', 1, NULL, NULL, '2014-10-04 10:53:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `program_history`
--

CREATE TABLE IF NOT EXISTS `program_history` (
  `id` int(11) NOT NULL,
  `revision` int(11) NOT NULL,
  `satker_id` int(11) NOT NULL,
  `number` varchar(15) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `hours` decimal(5,2) DEFAULT NULL,
  `days` int(3) DEFAULT NULL,
  `test` tinyint(1) DEFAULT '0',
  `note` varchar(255) DEFAULT NULL,
  `stage` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `validation_status` tinyint(1) DEFAULT '0',
  `validation_note` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `program_history`
--

INSERT INTO `program_history` (`id`, `revision`, `satker_id`, `number`, `name`, `hours`, `days`, `test`, `note`, `stage`, `category`, `validation_status`, `validation_note`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(2, 0, 23, '2.2.1.0', 'PRANATA KOMPUTER AHLI', '300.00', 30, 1, '-', 'Kebijakan Fiskal,Penganggaran', '3', 1, '', 1, NULL, NULL, '2014-10-04 10:53:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `program_subject`
--

CREATE TABLE IF NOT EXISTS `program_subject` (
`id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hours` decimal(5,2) NOT NULL,
  `sort` int(3) DEFAULT NULL,
  `test` tinyint(1) DEFAULT '0',
  `stage` text,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `program_subject`
--

INSERT INTO `program_subject` (`id`, `program_id`, `type`, `name`, `hours`, `sort`, `test`, `stage`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(2, 2, 109, 'MYSQL', '10.00', 2, 1, NULL, 1, NULL, NULL, NULL, NULL),
(3, 2, 109, 'PHP', '30.00', 1, 1, NULL, 0, NULL, NULL, NULL, NULL),
(4, 2, 109, 'PHP', '30.00', 1, 1, 'Teknologi, Informasi, dan Komunikasi Data Keuangan |Pranata Komputer', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `program_subject_history`
--

CREATE TABLE IF NOT EXISTS `program_subject_history` (
  `id` int(11) NOT NULL,
  `revision` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hours` decimal(5,2) NOT NULL,
  `sort` int(3) DEFAULT NULL,
  `test` tinyint(1) DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reference`
--

CREATE TABLE IF NOT EXISTS `reference` (
`id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=204 ;

--
-- Dumping data for table `reference`
--

INSERT INTO `reference` (`id`, `parent_id`, `type`, `name`, `value`, `sort`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 0, 'program_code', 'DIKLAT PRAJABATAN', '1.0.0.0', 0, 1, NULL, NULL, NULL, NULL),
(2, 0, 'program_code', 'DIKLAT DALAM JABATAN', '2.0.0.0', 0, 1, NULL, NULL, NULL, NULL),
(3, 2, 'program_code', 'DIKLAT KEPEMIMPINAN', '2.1.0.0', 0, 1, NULL, NULL, NULL, NULL),
(4, 2, 'program_code', 'DIKLAT FUNGSIONAL', '2.2.0.0', 0, 1, NULL, NULL, NULL, NULL),
(5, 4, 'program_code', 'DIKLAT FUNGSIONAL KEAHLIAN', '2.2.1.0', 0, 1, NULL, NULL, NULL, NULL),
(6, 4, 'program_code', 'DIKLAT FUNGSIONAL KETERAMPILAN', '2.2.2.0', 0, 1, NULL, NULL, NULL, NULL),
(7, 4, 'program_code', 'DIKLAT FUNGSIONAL PEMBENTUKAN', '2.2.3.0', 0, 1, NULL, NULL, NULL, NULL),
(8, 2, 'program_code', 'DIKLAT TEKNIS', '2.3.0.0', 2, 1, NULL, NULL, NULL, NULL),
(9, 8, 'program_code', 'DIKLAT TEKNIS SUBSTANTIF', '2.3.1.0', 0, 1, NULL, NULL, NULL, NULL),
(10, 9, 'program_code', 'DIKLAT TEKNIS SUBSTANTIF DASAR', '2.3.1.1', 0, 1, NULL, NULL, NULL, NULL),
(11, 9, 'program_code', 'DIKLAT TEKNIS SUBSTANTIF SPESIALISASI', '2.3.1.2', 0, 1, NULL, NULL, NULL, NULL),
(12, 8, 'program_code', 'DIKLAT TEKNIS UMUM', '2.3.2.0', 0, 1, NULL, NULL, NULL, NULL),
(13, 2, 'program_code', 'UJIAN DINAS', '2.4.0.0', 0, 1, NULL, NULL, NULL, NULL),
(14, 2, 'program_code', 'UJIAN PENYESUAIN KENAIKAN PANGKAT', '2.5.0.0', 0, 1, NULL, NULL, NULL, NULL),
(15, 2, 'program_code', 'DIKLAT PENYEGARAN', '2.6.0.0', 0, 1, NULL, NULL, NULL, NULL),
(16, 0, 'program_code', 'SERTIFIKASI', '3.0.0.0', 0, 1, NULL, NULL, NULL, NULL),
(17, 0, 'satker', 'Sekretariat Badan', '2', 1, 1, NULL, NULL, NULL, NULL),
(18, 0, 'satker', 'Pusdiklat PSDM', '2', 2, 1, NULL, NULL, NULL, NULL),
(19, 0, 'satker', 'Pusdiklat AP', '2', 3, 1, NULL, NULL, NULL, NULL),
(20, 0, 'satker', 'Pusdiklat Pajak', '2', 4, 1, NULL, NULL, NULL, NULL),
(21, 0, 'satker', 'Pusdiklat BC', '2', 5, 1, NULL, NULL, NULL, NULL),
(22, 0, 'satker', 'Pusdiklat KNPK', '2', 6, 1, NULL, NULL, NULL, NULL),
(23, 0, 'satker', 'Pusdiklat Keuangan Umum', '2', 7, 1, NULL, NULL, NULL, NULL),
(24, 0, 'satker', 'Sekolah Tinggi Akuntansi Negara', '2', 8, 1, NULL, NULL, NULL, NULL),
(25, 0, 'satker', 'Balai Diklat Keuangan Medan', '3', 9, 1, NULL, NULL, NULL, NULL),
(26, 0, 'satker', 'Balai Diklat Keuangan Palembang', '3', 10, 1, NULL, NULL, NULL, NULL),
(27, 0, 'satker', 'Balai Diklat Keuangan Yogyakarta', '3', 11, 1, NULL, NULL, NULL, NULL),
(28, 0, 'satker', 'Balai Diklat Keuangan Malang', '3', 12, 1, NULL, NULL, NULL, NULL),
(29, 0, 'satker', 'BDK Balikpapan', '3', 13, 1, NULL, NULL, NULL, NULL),
(30, 0, 'satker', 'BDK Makassar', '3', 14, 1, NULL, NULL, NULL, NULL),
(31, 0, 'satker', 'Balai Diklat Keuangan Cimahi', '3', 15, 1, NULL, NULL, NULL, NULL),
(32, 0, 'satker', 'Balai Diklat Keuangan Manado', '3', 16, 1, NULL, NULL, NULL, NULL),
(33, 0, 'satker', 'Balai Diklat Keuangan Pekanbaru', '3', 17, 1, NULL, NULL, NULL, NULL),
(34, 0, 'satker', 'Balai Diklat Keuangan Pontianak', '3', 18, 1, NULL, NULL, NULL, NULL),
(35, 0, 'satker', 'Balai Diklat Keuangan Denpasar', '3', 19, 1, NULL, NULL, NULL, NULL),
(36, 0, 'satker', 'Balai Diklat Keuangan Magelang', '3', 20, 1, NULL, NULL, NULL, NULL),
(37, 0, 'graduate', '-', '', 0, 1, NULL, NULL, NULL, NULL),
(38, 0, 'graduate', 'SD', '', 0, 1, NULL, NULL, NULL, NULL),
(39, 0, 'graduate', 'SMP', '', 0, 1, NULL, NULL, NULL, NULL),
(40, 0, 'graduate', 'SMA', '', 0, 1, NULL, NULL, NULL, NULL),
(41, 0, 'graduate', 'D I', '', 0, 1, NULL, NULL, NULL, NULL),
(42, 0, 'graduate', 'D II', '', 0, 1, NULL, NULL, NULL, NULL),
(43, 0, 'graduate', 'D III', '', 0, 1, NULL, NULL, NULL, NULL),
(44, 0, 'graduate', 'D IV', '', 0, 1, NULL, NULL, NULL, NULL),
(45, 0, 'graduate', 'S 1', '', 0, 1, NULL, NULL, NULL, NULL),
(46, 0, 'graduate', 'S 2', '', 0, 1, NULL, NULL, NULL, NULL),
(47, 0, 'graduate', 'S 3', '', 0, 1, NULL, NULL, NULL, NULL),
(66, 0, 'rank_class', 'Juru Muda / I.a', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(67, 0, 'rank_class', 'Juru Muda Tingkat I / I.b', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(68, 0, 'rank_class', 'Juru / I.c', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(69, 0, 'rank_class', 'Juru Tingkat I / I.d', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(70, 0, 'rank_class', 'Pengatur Muda / II.a', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(71, 0, 'rank_class', 'Pengatur Muda Tingkat I / II.b', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(72, 0, 'rank_class', 'Pengatur / II.c', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(73, 0, 'rank_class', 'Pengatur Tingkat I / II.d', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(74, 9, 'rank_class', 'Penata Muda / III.a', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(75, 0, 'rank_class', 'Penata Muda Tingkat I / III.b', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(76, 0, 'rank_class', 'Penata / III.c', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(77, 0, 'rank_class', 'Penata Tingkat I / III.d', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(78, 0, 'rank_class', 'Pembina / IV.a', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(79, 0, 'rank_class', 'Pembina Tingkat I / IV.b', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(80, 0, 'rank_class', 'Pembina Utama Muda / IV.c', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(81, 0, 'rank_class', 'Pembina Utama Madya / IV.d', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(82, 0, 'rank_class', 'Pembina Utama / IV.e', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(90, 0, 'religion', '-', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(91, 0, 'religion', 'Islam', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(92, 0, 'religion', 'Kristen Katolik', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(93, 0, 'religion', 'Kristen Protestan', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(94, 0, 'religion', 'Hindu', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(95, 0, 'religion', 'Budha', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(96, 0, 'religion', 'Konghucu', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(97, NULL, 'sbu', 'honor_persiapan_mengajar', '130000', NULL, 1, NULL, NULL, NULL, NULL),
(98, NULL, 'sbu', 'honor_pengajar_pns_internal', '100000', NULL, 1, NULL, NULL, NULL, NULL),
(99, NULL, 'sbu', 'honor_asisten_pns_internal', '50000', NULL, 1, NULL, NULL, NULL, NULL),
(100, NULL, 'sbu', 'honor_pengajar_pns_eksternal', '250000', NULL, 1, NULL, NULL, NULL, NULL),
(101, NULL, 'sbu', 'honor_asisten_pns_eksternal', '100000', NULL, 1, NULL, NULL, NULL, NULL),
(102, NULL, 'sbu', 'honor_penceramah_pns_internal_1', '550000', NULL, 1, NULL, NULL, NULL, NULL),
(103, NULL, 'sbu', 'honor_penceramah_pns_internal_2', '450000', NULL, 1, NULL, NULL, NULL, NULL),
(104, NULL, 'sbu', 'honor_penceramah_pns_internal_3', '350000', NULL, 1, NULL, NULL, NULL, NULL),
(105, NULL, 'sbu', 'honor_penceramah_pns_eksternal_1', '1100000', NULL, 1, NULL, NULL, NULL, NULL),
(106, NULL, 'sbu', 'honor_penceramah_pns_eksternal_2', '850000', NULL, 1, NULL, NULL, NULL, NULL),
(107, NULL, 'sbu', 'honor_penceramah_pns_eksternal_3', '700000', NULL, 1, NULL, NULL, NULL, NULL),
(108, NULL, 'sbu', 'honor_transport_dalam_kota', '110000', NULL, 1, NULL, NULL, NULL, NULL),
(109, NULL, 'subject_type', 'MP', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(110, NULL, 'subject_type', 'CERAMAH', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(111, NULL, 'subject_type', 'MFD (MENTAL FISIK DISIPLIN)', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(112, NULL, 'subject_type', 'OJT (ON THE JOB TRAINING)', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(113, NULL, 'trainer_type', 'PENGAJAR', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(114, NULL, 'trainer_type', 'PENCERAMAH', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(115, NULL, 'trainer_type', 'ASISTEN PENGAJAR', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(116, NULL, 'trainer_type', 'TEAM TEACHING', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(117, NULL, 'trainer_type', 'INSTRUKTUR MFD', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(118, NULL, 'trainer_type', 'ASISTEN INSTRUKTUR MFD', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(119, NULL, 'trainer_type', 'PEMBIMBING PKL', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(120, NULL, 'unit', '-', '-', NULL, 1, NULL, NULL, NULL, NULL),
(121, NULL, 'unit', 'Sekretariat Jenderal', 'Setjen', NULL, 1, NULL, NULL, NULL, NULL),
(122, NULL, 'unit', 'Inspektorat Jenderal', 'Itjen', NULL, 1, NULL, NULL, NULL, NULL),
(123, NULL, 'unit', 'Direktorat Jenderal Anggaran', 'DJA', NULL, 1, NULL, NULL, NULL, NULL),
(124, NULL, 'unit', 'Direktorat Jenderal Pajak', 'DJP', NULL, 1, NULL, NULL, NULL, NULL),
(125, NULL, 'unit', 'Direktorat Jenderal Bea dan Cukai', 'DJBC', NULL, 1, NULL, NULL, NULL, NULL),
(126, NULL, 'unit', 'Direktorat Jenderal Perbendaharaan', 'DJPBn', NULL, 1, NULL, NULL, NULL, NULL),
(127, NULL, 'unit', 'Direktorat Jenderal Kekayaan Negara', 'DJKN', NULL, 1, NULL, NULL, NULL, NULL),
(128, NULL, 'unit', 'Direktorat Jenderal Perimbangan Keuangan', 'DJPK', NULL, 1, NULL, NULL, NULL, NULL),
(129, NULL, 'unit', 'Direktorat Jenderal Pengelolaan Utang', 'DJPU', NULL, 1, NULL, NULL, NULL, NULL),
(130, NULL, 'unit', 'Badan Kebijakan Fiskal', 'BKF', NULL, 1, NULL, NULL, NULL, NULL),
(131, NULL, 'unit', 'Badan Pengawas Pasar Modal dan Lembaga Keuangan', 'Bapepam-LK', NULL, 1, NULL, NULL, NULL, NULL),
(132, NULL, 'unit', 'Badan Pendidikan dan Pelatihan Keuangan', 'BPPK', NULL, 1, NULL, NULL, NULL, NULL),
(133, NULL, 'unit', 'Non Kemenkeu', 'Lainnya', NULL, 1, NULL, NULL, NULL, NULL),
(134, NULL, 'finance_position', 'KPA', 'Kuasa Pengguna Anggaran', NULL, 1, NULL, NULL, NULL, NULL),
(135, NULL, 'finance_position', 'PPK', 'Pejabat Pembuat Komitmen', NULL, 1, NULL, NULL, NULL, NULL),
(136, NULL, 'finance_position', 'PPSPM', 'Pejabat Penandatanganan SPM', NULL, 1, NULL, NULL, NULL, NULL),
(137, NULL, 'finance_position', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran', NULL, 1, NULL, NULL, NULL, NULL),
(138, NULL, 'finance_position', 'Bendahara Penerimaan', 'Bendahara Penerimaan', NULL, 1, NULL, NULL, NULL, NULL),
(139, NULL, 'fungsional', 'Widyaiswara', 'Widyaiswara', NULL, 1, NULL, NULL, NULL, NULL),
(140, NULL, 'fungsional', 'Pranata Komputer', 'Pranta Komputer', NULL, 1, NULL, NULL, NULL, NULL),
(141, NULL, 'widyaiswara', 'Widyaiswara Pertama', 'Widyaiswara Pertama', NULL, 1, NULL, NULL, NULL, NULL),
(142, NULL, 'widyaiswara', 'Widyaiswara Muda', 'Widyaiswara Muda', NULL, 1, NULL, NULL, NULL, NULL),
(143, NULL, 'widyaiswara', 'Widyaiswara Madya', 'Widyaiswara Madya', NULL, 1, NULL, NULL, NULL, NULL),
(144, NULL, 'widyaiswara', 'Widyaiswara Utama', 'Widyaiswara Utama', NULL, 1, NULL, NULL, NULL, NULL),
(145, NULL, 'pranata_komputer', 'Pranata Komputer Terampil', 'Pranata Komputer Terampil', NULL, 1, NULL, NULL, NULL, NULL),
(146, NULL, 'pranata_komputer', 'Pranata Komputer Ahli', 'Pranata Komputer Ahli', NULL, 1, NULL, NULL, NULL, NULL),
(147, NULL, 'pranata_komputer_terampil', 'Pranata Komputer Pelaksana Pemula', 'Pranata Komputer Pelaksana Pemula', NULL, 1, NULL, NULL, NULL, NULL),
(148, NULL, 'pranata_komputer_terampil', 'Pranata Komputer Pelaksana', 'Pranata Komputer Pelaksana', NULL, 1, NULL, NULL, NULL, NULL),
(149, NULL, 'pranata_komputer_terampil', 'Pranata Komputer Pelaksana Lanjutan', 'Pranata Komputer Pelaksana Lanjutan', NULL, 1, NULL, NULL, NULL, NULL),
(150, NULL, 'pranata_komputer_terampil', 'Pranata Komputer Penyelia', 'Pranata Komputer Penyelia', NULL, 1, NULL, NULL, NULL, NULL),
(151, NULL, 'pranata_komputer_ahli', 'Pranata Komputer Pertama', 'Pranata Komputer Pertama', NULL, 1, NULL, NULL, NULL, NULL),
(152, NULL, 'pranata_komputer_ahli', 'Pranata Komputer Muda', 'Pranata Komputer Muda', NULL, 1, NULL, NULL, NULL, NULL),
(153, NULL, 'pranata_komputer_ahli', 'Pranata Komputer Madya', 'Pranata Komputer Madya Lanjutan', NULL, 1, NULL, NULL, NULL, NULL),
(154, NULL, 'pranata_komputer_ahli', 'Pranata Komputer Utama', 'Pranata Komputer Utama', NULL, 1, NULL, NULL, NULL, NULL),
(155, NULL, 'stage', 'Ilmu Ekonomi dan Keuangan Negara', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(156, NULL, 'stage', 'Kebijakan Fiskal', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(157, NULL, 'stage', 'Lembaga keuangan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(158, NULL, 'stage', 'Pasar Modal', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(159, NULL, 'stage', 'Penganggaran', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(160, NULL, 'stage', 'KUP', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(161, NULL, 'stage', 'PPN', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(162, NULL, 'stage', 'PPh', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(163, NULL, 'stage', 'PBB', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(164, NULL, 'stage', 'TIK Perpajakan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(165, NULL, 'stage', 'Administrasi Perpajakan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(166, NULL, 'stage', 'Kepabeanan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(167, NULL, 'stage', 'Cukai', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(168, NULL, 'stage', 'Pengelolaan PNBP', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(169, NULL, 'stage', 'Aplikasi Anggaran dan Perbendaharaan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(170, NULL, 'stage', 'Pinjaman Hibah Luar Negeri ', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(171, NULL, 'stage', 'Belanja Pengguna Anggaran', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(172, NULL, 'stage', 'Pencairan Dana BUN', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(173, NULL, 'stage', 'Perimbangan Keuangan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(174, NULL, 'stage', 'Perolehan Barang/Jasa', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(175, NULL, 'stage', 'Barang Milik Negara/Daerah', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(176, NULL, 'stage', 'Kekayaan Negara yang Dipisahkan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(177, NULL, 'stage', 'Piutang dan Lelang', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(178, NULL, 'stage', 'Penilaian', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(179, NULL, 'stage', 'Investasi', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(180, NULL, 'stage', 'Pembiayaan Utang', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(181, NULL, 'stage', 'Pengelolaan Kas', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(182, NULL, 'stage', 'Akuntansi Pemerintahan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(183, NULL, 'stage', 'Hukum', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(184, NULL, 'stage', 'Bahasa Indonesia', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(185, NULL, 'stage', 'Bahasa Inggris', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(186, NULL, 'stage', 'Teknologi, Informasi, dan Komunikasi Data Keuangan ', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(187, NULL, 'stage', 'Manajemen Teknologi', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(188, NULL, 'stage', 'Pranata Komputer', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(189, NULL, 'stage', 'Softskill Kementerian Keuangan', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(190, NULL, 'stage', 'Kediklatan', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(191, NULL, 'stage', 'Manajemen', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(192, NULL, 'stage', 'Organisasi dan TL Kementerian Keuangan', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(193, NULL, 'stage', 'Etika Profesi Kementerian Keuangan', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(194, NULL, 'stage', 'Statistik dan Riset', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(195, NULL, 'stage', 'Kesemaptaan ', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(196, NULL, 'stage', 'Audit', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(197, NULL, 'stage', 'Audit SI-TIK', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(198, NULL, 'stage', 'Kenegaraan', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(199, NULL, 'stage', 'Akuntansi Konvensional ', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(200, NULL, 'stage', 'Akuntansi Syariah', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(201, NULL, 'stage', 'Investigasi, Penyidikan, dan Forensik', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(202, NULL, 'stage', 'Keahlian Senjata', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(203, NULL, 'stage', 'Kepatuhan  Internal', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
`id` int(3) NOT NULL,
  `satker_id` int(11) NOT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(255) NOT NULL,
  `capacity` int(5) DEFAULT '30' COMMENT 'SISWA',
  `owner` tinyint(1) DEFAULT '1' COMMENT '1:SATKER ; 0: BUKAN SATKER',
  `computer` tinyint(1) DEFAULT '0',
  `hostel` tinyint(1) DEFAULT '0',
  `address` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `person_id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password_hash` varchar(60) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `eselon2` varchar(255) DEFAULT NULL,
  `eselon3` varchar(255) DEFAULT NULL,
  `eselon4` varchar(255) DEFAULT NULL,
  `satker` int(3) DEFAULT NULL,
  `no_sk` int(11) DEFAULT NULL COMMENT 'SK Pangkat',
  `tmt_sk` int(11) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`person_id`, `username`, `password_hash`, `auth_key`, `password_reset_token`, `eselon2`, `eselon3`, `eselon4`, `satker`, `no_sk`, `tmt_sk`, `status`) VALUES
(20, '198606052007011002', '$2y$13$6u3NSw0Cugy6.mIfpuEePeGm2YjkhhO6eO7.oTPRipGye89uERP9W', '', NULL, '', '', '', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

CREATE TABLE IF NOT EXISTS `trainer` (
  `person_id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `education_history` text,
  `training_history` text,
  `experience_history` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `trainer`
--

INSERT INTO `trainer` (`person_id`, `category`, `education_history`, `training_history`, `experience_history`) VALUES
(1, 'tes', '', '', ''),
(12, NULL, NULL, NULL, NULL),
(13, NULL, NULL, NULL, NULL),
(18, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE IF NOT EXISTS `training` (
  `activity_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `program_revision` int(11) DEFAULT NULL,
  `number` varchar(100) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `regular` tinyint(1) DEFAULT '0',
  `stakeholder` varchar(255) DEFAULT NULL,
  `student_count_plan` int(11) DEFAULT NULL,
  `class_count_plan` int(11) DEFAULT NULL,
  `execution_sk` varchar(255) DEFAULT NULL,
  `result_sk` varchar(255) DEFAULT NULL,
  `cost_source` varchar(255) DEFAULT NULL,
  `cost_plan` decimal(15,2) DEFAULT NULL,
  `cost_real` decimal(15,2) DEFAULT NULL,
  `approved_status` int(3) DEFAULT NULL,
  `approved_note` varchar(255) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `training`
--

INSERT INTO `training` (`activity_id`, `program_id`, `program_revision`, `number`, `note`, `regular`, `stakeholder`, `student_count_plan`, `class_count_plan`, `execution_sk`, `result_sk`, `cost_source`, `cost_plan`, `cost_real`, `approved_status`, `approved_note`, `approved_date`, `approved_by`) VALUES
(6, 2, 0, '2014-07-00-2.2.1.0.2', '', 1, '', 24, 3, '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `training_class`
--

CREATE TABLE IF NOT EXISTS `training_class` (
`id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `class` varchar(5) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `training_class`
--

INSERT INTO `training_class` (`id`, `training_id`, `class`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(8, 6, 'A', 1, NULL, NULL, NULL, NULL),
(9, 6, 'B', 1, NULL, NULL, NULL, NULL),
(10, 6, 'C', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `training_class_student`
--

CREATE TABLE IF NOT EXISTS `training_class_student` (
`id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `training_class_id` int(11) NOT NULL,
  `training_student_id` int(11) NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `head_class` tinyint(1) DEFAULT '0',
  `activity` decimal(5,2) DEFAULT '1.00' COMMENT 'NILAI AKTIFITAS',
  `presence` decimal(5,2) DEFAULT NULL,
  `pre_test` decimal(5,2) DEFAULT NULL,
  `post_test` decimal(5,2) DEFAULT NULL,
  `test` decimal(5,2) DEFAULT NULL COMMENT 'Nilai Ujian',
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_student_attendance`
--

CREATE TABLE IF NOT EXISTS `training_class_student_attendance` (
`id` int(11) NOT NULL,
  `training_schedule_id` int(11) NOT NULL,
  `training_class_student_id` int(11) NOT NULL,
  `hours` decimal(5,2) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_student_certificate`
--

CREATE TABLE IF NOT EXISTS `training_class_student_certificate` (
  `training_class_student_id` int(11) NOT NULL,
  `number` varchar(50) DEFAULT NULL,
  `seri` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `position` int(1) DEFAULT NULL COMMENT '[1:Es1;2:Es2;3:Es3;4:Es4;5:Pelaksana]',
  `position_desc` varchar(255) DEFAULT NULL,
  `graduate` int(11) DEFAULT NULL,
  `graduate_desc` varchar(255) DEFAULT NULL,
  `eselon2` varchar(255) DEFAULT NULL,
  `eselon3` varchar(255) DEFAULT NULL,
  `eselon4` varchar(255) DEFAULT NULL,
  `satker` enum('2','3','4') DEFAULT '2',
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_subject`
--

CREATE TABLE IF NOT EXISTS `training_class_subject` (
`id` int(11) NOT NULL,
  `training_class_id` int(11) NOT NULL,
  `program_subject_id` int(11) NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `training_class_subject`
--

INSERT INTO `training_class_subject` (`id`, `training_class_id`, `program_subject_id`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(10, 8, 2, 1, NULL, NULL, NULL, NULL),
(11, 8, 4, 1, NULL, NULL, NULL, NULL),
(12, 10, 2, 1, NULL, NULL, NULL, NULL),
(13, 10, 4, 1, NULL, NULL, NULL, NULL),
(14, 9, 2, 1, NULL, NULL, NULL, NULL),
(15, 9, 4, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `training_class_subject_trainer_evaluation`
--

CREATE TABLE IF NOT EXISTS `training_class_subject_trainer_evaluation` (
`id` int(11) NOT NULL,
  `training_class_subject_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `comment` varchar(3000) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_execution_evaluation`
--

CREATE TABLE IF NOT EXISTS `training_execution_evaluation` (
`id` int(11) NOT NULL,
  `training_class_student_id` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `text1` varchar(500) DEFAULT NULL,
  `text2` varchar(500) DEFAULT NULL,
  `text3` varchar(500) DEFAULT NULL,
  `text4` varchar(500) DEFAULT NULL,
  `text5` varchar(500) DEFAULT NULL,
  `overall` int(3) DEFAULT NULL,
  `comment` varchar(3000) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_history`
--

CREATE TABLE IF NOT EXISTS `training_history` (
  `activity_id` int(11) NOT NULL,
  `revision` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `program_revision` int(11) DEFAULT NULL,
  `number` varchar(100) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `regular` tinyint(1) DEFAULT '0',
  `stakeholder` varchar(255) DEFAULT NULL,
  `student_count_plan` int(11) DEFAULT NULL,
  `class_count_plan` int(11) DEFAULT NULL,
  `execution_sk` varchar(255) DEFAULT NULL,
  `result_sk` varchar(255) DEFAULT NULL,
  `cost_source` varchar(255) DEFAULT NULL,
  `cost_plan` decimal(15,2) DEFAULT NULL,
  `cost_real` decimal(15,2) DEFAULT NULL,
  `approved_status` int(3) DEFAULT NULL,
  `approved_note` varchar(255) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `training_history`
--

INSERT INTO `training_history` (`activity_id`, `revision`, `program_id`, `program_revision`, `number`, `note`, `regular`, `stakeholder`, `student_count_plan`, `class_count_plan`, `execution_sk`, `result_sk`, `cost_source`, `cost_plan`, `cost_real`, `approved_status`, `approved_note`, `approved_date`, `approved_by`) VALUES
(6, 0, 2, 0, '2014-07-00-2.2.1.0.2', '', 1, '', 24, 3, '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `training_schedule`
--

CREATE TABLE IF NOT EXISTS `training_schedule` (
`id` int(11) NOT NULL,
  `training_class_id` int(11) NOT NULL,
  `training_class_subject_id` int(11) NOT NULL,
  `activity_room_id` int(11) NOT NULL,
  `activity` varchar(255) DEFAULT NULL COMMENT 'Honor untuk PIC/JP',
  `pic` varchar(100) DEFAULT NULL COMMENT '0-25',
  `hours` decimal(5,2) DEFAULT NULL COMMENT '1JP = 45menit',
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_schedule_trainer`
--

CREATE TABLE IF NOT EXISTS `training_schedule_trainer` (
`id` int(11) NOT NULL,
  `training_schedule_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `hours` decimal(5,2) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_student`
--

CREATE TABLE IF NOT EXISTS `training_student` (
`id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

--
-- Dumping data for table `training_student`
--

INSERT INTO `training_student` (`id`, `training_id`, `student_id`, `note`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(101, 6, 20, 'Ganti', 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `training_student_plan`
--

CREATE TABLE IF NOT EXISTS `training_student_plan` (
  `training_id` int(11) NOT NULL,
  `spread` varchar(500) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `training_student_plan`
--

INSERT INTO `training_student_plan` (`training_id`, `spread`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(6, '{"121":"2","122":"10","123":"0","124":"0","125":"0","126":"0","127":"2","128":"0","129":"0","130":"10","131":"0","132":"0","133":"0"}', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `training_subject_trainer_recommendation`
--

CREATE TABLE IF NOT EXISTS `training_subject_trainer_recommendation` (
`id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `program_subject_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `sort` int(5) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `training_subject_trainer_recommendation`
--

INSERT INTO `training_subject_trainer_recommendation` (`id`, `training_id`, `program_subject_id`, `type`, `trainer_id`, `note`, `sort`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(3, 6, 4, 113, 1, '', 1, 1, NULL, NULL, NULL, NULL),
(4, 6, 4, 114, 13, '', 2, 1, NULL, NULL, NULL, NULL),
(6, 6, 4, 113, 18, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(7, 6, 4, 114, 12, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(8, 6, 2, 113, 12, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(9, 6, 2, 113, 1, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(10, 6, 2, 114, 13, NULL, NULL, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', '5_082mYBEKJPI2VsPKBUwh6EEZFhpuv0', '$2y$13$8W7z/7NCJ1BaLigR9dCWH.C0VdYrCB2.yw68xpoCwn3Hq82Zbi2ye', '', 'milisstudio@gmail.com', 1, 1, 1411603965, 1412108460),
(5, 'pusdku', '', '$2y$13$Lhrs5qJGoItvyBUylLxFlehlnuKxK.rh99UrzFSYIh8kKRZ8RpG.O', NULL, 'pusdku@gmail.com', 1, 1, 1412073196, 1412073196),
(6, 'hari', '', '$2y$13$ea1jSF3gdhZPuwZOHUdDK.tUtZT3KkA14kC27Idf1o6Jcw03kOiVy', NULL, 'hari@g.c', 1, 1, 1412074456, 1412115785),
(10, '222', '', '$2y$13$BIpniokROhM.juQ3YrPMeucDstIaQF7jQBFjAi7Lm5UsxJd4ooZQa', NULL, '222@gmail.com', 1, 0, 1412117909, 1412117909),
(11, '19890426', '', '$2y$13$gue6b6n8UCZg/nhSTPR6jODPS1EYl1qHSXZb9A0wnffyZGGc5FPW.', NULL, '19890426@gmail.com', 1, 0, 1412521515, 1412521515),
(12, '135', '', '$2y$13$cj/Q0oMtl9O.Mbnk0IOfAOiWlyNKsPkuETalPams/rX.ixBPkrfJ6', NULL, '135@gmail.com', 1, 0, 1412739725, 1412739725);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `activity_history`
--
ALTER TABLE `activity_history`
 ADD PRIMARY KEY (`id`,`revision`), ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `activity_room`
--
ALTER TABLE `activity_room`
 ADD PRIMARY KEY (`activity_id`,`room_id`), ADD KEY `tb_room_id` (`room_id`), ADD KEY `tb_training_id` (`activity_id`);

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
 ADD PRIMARY KEY (`item_name`,`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
 ADD PRIMARY KEY (`name`), ADD KEY `rule_name` (`rule_name`), ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
 ADD PRIMARY KEY (`parent`,`child`), ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
 ADD PRIMARY KEY (`name`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
 ADD PRIMARY KEY (`person_id`), ADD KEY `user_id` (`user_id`), ADD KEY `organisation_id` (`organisation_id`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meeting`
--
ALTER TABLE `meeting`
 ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
 ADD PRIMARY KEY (`id`), ADD KEY `parent` (`parent`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name_UNIQUE` (`type`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
 ADD PRIMARY KEY (`version`);

--
-- Indexes for table `object_file`
--
ALTER TABLE `object_file`
 ADD PRIMARY KEY (`object`,`object_id`,`type`,`file_id`), ADD KEY `file_id` (`file_id`);

--
-- Indexes for table `object_person`
--
ALTER TABLE `object_person`
 ADD PRIMARY KEY (`object`,`object_id`,`type`), ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `object_reference`
--
ALTER TABLE `object_reference`
 ADD PRIMARY KEY (`object`,`object_id`,`type`), ADD KEY `reference_id` (`reference_id`);

--
-- Indexes for table `organisation`
--
ALTER TABLE `organisation`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `nid` (`nid`);

--
-- Indexes for table `person_activity`
--
ALTER TABLE `person_activity`
 ADD UNIQUE KEY `name_UNIQUE` (`activity_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program_history`
--
ALTER TABLE `program_history`
 ADD PRIMARY KEY (`id`,`revision`);

--
-- Indexes for table `program_subject`
--
ALTER TABLE `program_subject`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_tb_program_subject_tb_program1` (`program_id`);

--
-- Indexes for table `program_subject_history`
--
ALTER TABLE `program_subject_history`
 ADD PRIMARY KEY (`id`,`revision`), ADD KEY `fk_tb_program_subject_tb_program1` (`program_id`);

--
-- Indexes for table `reference`
--
ALTER TABLE `reference`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code_UNIQUE` (`code`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
 ADD PRIMARY KEY (`person_id`), ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `trainer`
--
ALTER TABLE `trainer`
 ADD PRIMARY KEY (`person_id`);

--
-- Indexes for table `training`
--
ALTER TABLE `training`
 ADD PRIMARY KEY (`activity_id`), ADD KEY `activity_id` (`activity_id`,`program_id`), ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `training_class`
--
ALTER TABLE `training_class`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_tb_training_assignment_tb_training_subject1` (`training_id`);

--
-- Indexes for table `training_class_student`
--
ALTER TABLE `training_class_student`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `tb_training_id_2` (`training_id`,`training_student_id`), ADD KEY `fk_tb_training_subject_student_tb_training_assignment1` (`training_class_id`), ADD KEY `fk_tb_training_subject_student_tb_student1` (`training_student_id`), ADD KEY `tb_training_id` (`training_id`);

--
-- Indexes for table `training_class_student_attendance`
--
ALTER TABLE `training_class_student_attendance`
 ADD PRIMARY KEY (`id`), ADD KEY `tb_training_schedule_id` (`training_schedule_id`), ADD KEY `tb_training_class_student_id` (`training_class_student_id`);

--
-- Indexes for table `training_class_student_certificate`
--
ALTER TABLE `training_class_student_certificate`
 ADD PRIMARY KEY (`training_class_student_id`), ADD KEY `fk_tb_training_certificate_tb_training1` (`training_class_student_id`);

--
-- Indexes for table `training_class_subject`
--
ALTER TABLE `training_class_subject`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_tb_training_assignment_tb_training_subject1` (`training_class_id`), ADD KEY `fk_tb_training_assignment_tb_trainer1` (`program_subject_id`);

--
-- Indexes for table `training_class_subject_trainer_evaluation`
--
ALTER TABLE `training_class_subject_trainer_evaluation`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_tb_training_trainer_evaluation_tb_student1` (`student_id`), ADD KEY `tb_training_class_subject_id` (`training_class_subject_id`), ADD KEY `trainer_id` (`trainer_id`);

--
-- Indexes for table `training_execution_evaluation`
--
ALTER TABLE `training_execution_evaluation`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_tb_training_execution_evaluation_tb_training_student1` (`training_class_student_id`);

--
-- Indexes for table `training_history`
--
ALTER TABLE `training_history`
 ADD PRIMARY KEY (`activity_id`,`revision`);

--
-- Indexes for table `training_schedule`
--
ALTER TABLE `training_schedule`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_tb_training_schedule_tb_room1` (`activity_room_id`), ADD KEY `tb_activity_room_id` (`activity_room_id`), ADD KEY `tb_training_class_subject_assignment_id` (`training_class_subject_id`), ADD KEY `tb_training_class_id` (`training_class_id`);

--
-- Indexes for table `training_schedule_trainer`
--
ALTER TABLE `training_schedule_trainer`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `tb_training_schedule_id` (`training_schedule_id`,`trainer_id`), ADD KEY `tb_training_class_subject_assignment_id` (`trainer_id`), ADD KEY `tb_training_class_id` (`training_schedule_id`);

--
-- Indexes for table `training_student`
--
ALTER TABLE `training_student`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_tb_training_assignment_tb_training_subject1` (`training_id`), ADD KEY `tb_student_id` (`student_id`);

--
-- Indexes for table `training_student_plan`
--
ALTER TABLE `training_student_plan`
 ADD PRIMARY KEY (`training_id`);

--
-- Indexes for table `training_subject_trainer_recommendation`
--
ALTER TABLE `training_subject_trainer_recommendation`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_tb_training_subject_trainer_recommendation_tb_training_sub1` (`program_subject_id`), ADD KEY `fk_tb_training_subject_trainer_recommendation_tb_trainer1` (`trainer_id`), ADD KEY `tb_training_id` (`training_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `organisation`
--
ALTER TABLE `organisation`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=403;
--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `program_subject`
--
ALTER TABLE `program_subject`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `reference`
--
ALTER TABLE `reference`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=204;
--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `training_class`
--
ALTER TABLE `training_class`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `training_class_student`
--
ALTER TABLE `training_class_student`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `training_class_student_attendance`
--
ALTER TABLE `training_class_student_attendance`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `training_class_subject`
--
ALTER TABLE `training_class_subject`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `training_class_subject_trainer_evaluation`
--
ALTER TABLE `training_class_subject_trainer_evaluation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `training_execution_evaluation`
--
ALTER TABLE `training_execution_evaluation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `training_schedule`
--
ALTER TABLE `training_schedule`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `training_schedule_trainer`
--
ALTER TABLE `training_schedule_trainer`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `training_student`
--
ALTER TABLE `training_student`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `training_subject_trainer_recommendation`
--
ALTER TABLE `training_subject_trainer_recommendation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_room`
--
ALTER TABLE `activity_room`
ADD CONSTRAINT `activity_room_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `activity_room_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `meeting`
--
ALTER TABLE `meeting`
ADD CONSTRAINT `meeting_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `object_file`
--
ALTER TABLE `object_file`
ADD CONSTRAINT `object_file_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `object_person`
--
ALTER TABLE `object_person`
ADD CONSTRAINT `object_person_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `object_reference`
--
ALTER TABLE `object_reference`
ADD CONSTRAINT `object_reference_ibfk_1` FOREIGN KEY (`reference_id`) REFERENCES `reference` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `program_subject`
--
ALTER TABLE `program_subject`
ADD CONSTRAINT `program_subject_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trainer`
--
ALTER TABLE `trainer`
ADD CONSTRAINT `trainer_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `training`
--
ALTER TABLE `training`
ADD CONSTRAINT `training_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `training_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_class`
--
ALTER TABLE `training_class`
ADD CONSTRAINT `training_class_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_class_student`
--
ALTER TABLE `training_class_student`
ADD CONSTRAINT `training_class_student_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON UPDATE CASCADE,
ADD CONSTRAINT `training_class_student_ibfk_2` FOREIGN KEY (`training_class_id`) REFERENCES `training_class` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `training_class_student_ibfk_3` FOREIGN KEY (`training_student_id`) REFERENCES `training_student` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_class_student_attendance`
--
ALTER TABLE `training_class_student_attendance`
ADD CONSTRAINT `training_class_student_attendance_ibfk_1` FOREIGN KEY (`training_class_student_id`) REFERENCES `training_class_student` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_class_subject`
--
ALTER TABLE `training_class_subject`
ADD CONSTRAINT `training_class_subject_ibfk_1` FOREIGN KEY (`training_class_id`) REFERENCES `training_class` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_class_subject_trainer_evaluation`
--
ALTER TABLE `training_class_subject_trainer_evaluation`
ADD CONSTRAINT `training_class_subject_trainer_evaluation_ibfk_1` FOREIGN KEY (`training_class_subject_id`) REFERENCES `training_class_subject` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `training_class_subject_trainer_evaluation_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`person_id`) ON UPDATE CASCADE,
ADD CONSTRAINT `training_class_subject_trainer_evaluation_ibfk_3` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`person_id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_execution_evaluation`
--
ALTER TABLE `training_execution_evaluation`
ADD CONSTRAINT `training_execution_evaluation_ibfk_1` FOREIGN KEY (`training_class_student_id`) REFERENCES `training_class_student` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_schedule`
--
ALTER TABLE `training_schedule`
ADD CONSTRAINT `training_schedule_ibfk_1` FOREIGN KEY (`training_class_id`) REFERENCES `training_class` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_schedule_trainer`
--
ALTER TABLE `training_schedule_trainer`
ADD CONSTRAINT `training_schedule_trainer_ibfk_1` FOREIGN KEY (`training_schedule_id`) REFERENCES `training_schedule` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `training_schedule_trainer_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`person_id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_student`
--
ALTER TABLE `training_student`
ADD CONSTRAINT `training_student_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON UPDATE CASCADE,
ADD CONSTRAINT `training_student_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`person_id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_student_plan`
--
ALTER TABLE `training_student_plan`
ADD CONSTRAINT `training_student_plan_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `training_subject_trainer_recommendation`
--
ALTER TABLE `training_subject_trainer_recommendation`
ADD CONSTRAINT `training_subject_trainer_recommendation_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON UPDATE CASCADE,
ADD CONSTRAINT `training_subject_trainer_recommendation_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`person_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
