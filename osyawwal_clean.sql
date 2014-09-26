-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2014 at 02:42 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `hostel` tinyint(1) DEFAULT '0',
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_history`
--

CREATE TABLE IF NOT EXISTS `activity_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `hostel` tinyint(1) DEFAULT '0',
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `activity_id` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`activity_id`,`room_id`),
  KEY `tb_room_id` (`room_id`),
  KEY `tb_training_id` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`)
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
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `person_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`person_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `fileName` varchar(255) NOT NULL,
  `fileType` varchar(255) DEFAULT NULL,
  `fileSize` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE IF NOT EXISTS `meeting` (
  `activity_id` int(11) NOT NULL,
  `attendance_count_plan` int(11) DEFAULT NULL,
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(256) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(3) NOT NULL,
  `category` int(3) NOT NULL,
  `author` int(11) NOT NULL,
  `recipient` int(11) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
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
  `type` varchar(50) DEFAULT NULL,
  `file_id` int(11) NOT NULL,
  KEY `object_id` (`object_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `object_reference`
--

CREATE TABLE IF NOT EXISTS `object_reference` (
  `object` varchar(100) NOT NULL,
  `object_id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `reference_id` int(11) NOT NULL,
  KEY `object_id` (`object_id`,`reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `photo` varchar(255) DEFAULT NULL,
  `blood` varchar(25) DEFAULT '-',
  `graduate` int(3) DEFAULT NULL,
  `graduate_desc` varchar(255) DEFAULT NULL,
  `position` int(3) DEFAULT NULL,
  `position_desc` varchar(255) DEFAULT NULL,
  `organisation` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `modified_by` int(11) DEFAULT NULL,
  UNIQUE KEY `name_UNIQUE` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE IF NOT EXISTS `program` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `program_history`
--

CREATE TABLE IF NOT EXISTS `program_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `program_history` int(11) DEFAULT NULL,
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
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `program_id` (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `program_subject`
--

CREATE TABLE IF NOT EXISTS `program_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hours` decimal(5,2) NOT NULL,
  `sort` int(3) DEFAULT NULL,
  `test` tinyint(1) DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_program_subject_tb_program1` (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `program_subject_history`
--

CREATE TABLE IF NOT EXISTS `program_subject_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_subject_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `program_revision` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `hours` decimal(5,2) NOT NULL,
  `sort` int(3) DEFAULT NULL,
  `test` tinyint(1) DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_program_subject_tb_program1` (`program_id`),
  KEY `program_subject_id` (`program_subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reference`
--

CREATE TABLE IF NOT EXISTS `reference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
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
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `person_id` int(11) NOT NULL,
  `username` varchar(25) DEFAULT NULL,
  `password_hash` varchar(60) DEFAULT NULL,
  `auth_key` varchar(32) DEFAULT NULL,
  `eselon2` varchar(255) DEFAULT NULL,
  `eselon3` varchar(255) DEFAULT NULL,
  `eselon4` varchar(255) DEFAULT NULL,
  `satker` int(3) DEFAULT NULL,
  `no_sk` int(11) DEFAULT NULL COMMENT 'SK Pangkat',
  `tmt_sk` int(11) DEFAULT NULL,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

CREATE TABLE IF NOT EXISTS `trainer` (
  `person_id` int(11) NOT NULL,
  `widyaiswara` tinyint(1) DEFAULT '0',
  `widyaiswara_stage` int(11) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `education_history` text,
  `training_history` text,
  `experience_history` text,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `approved_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`activity_id`),
  KEY `activity_id` (`activity_id`,`program_id`),
  KEY `program_id` (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `training_class`
--

CREATE TABLE IF NOT EXISTS `training_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `class` varchar(5) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_assignment_tb_training_subject1` (`training_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_student`
--

CREATE TABLE IF NOT EXISTS `training_class_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tb_training_id_2` (`training_id`,`training_student_id`),
  KEY `fk_tb_training_subject_student_tb_training_assignment1` (`training_class_id`),
  KEY `fk_tb_training_subject_student_tb_student1` (`training_student_id`),
  KEY `tb_training_id` (`training_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_student_attendance`
--

CREATE TABLE IF NOT EXISTS `training_class_student_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_schedule_id` int(11) NOT NULL,
  `training_class_student_id` int(11) NOT NULL,
  `hours` decimal(5,2) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_training_schedule_id` (`training_schedule_id`),
  KEY `tb_training_class_student_id` (`training_class_student_id`)
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
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`training_class_student_id`),
  KEY `fk_tb_training_certificate_tb_training1` (`training_class_student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_subject`
--

CREATE TABLE IF NOT EXISTS `training_class_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_class_id` int(11) NOT NULL,
  `program_subject_id` int(11) NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_assignment_tb_training_subject1` (`training_class_id`),
  KEY `fk_tb_training_assignment_tb_trainer1` (`program_subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_subject_trainer_evaluation`
--

CREATE TABLE IF NOT EXISTS `training_class_subject_trainer_evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_class_subject_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `comment` varchar(3000) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_trainer_evaluation_tb_student1` (`student_id`),
  KEY `tb_training_class_subject_id` (`training_class_subject_id`),
  KEY `trainer_id` (`trainer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_execution_evaluation`
--

CREATE TABLE IF NOT EXISTS `training_execution_evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_execution_evaluation_tb_training_student1` (`training_class_student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_schedule`
--

CREATE TABLE IF NOT EXISTS `training_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_schedule_tb_room1` (`activity_room_id`),
  KEY `tb_activity_room_id` (`activity_room_id`),
  KEY `tb_training_class_subject_assignment_id` (`training_class_subject_id`),
  KEY `tb_training_class_id` (`training_class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_schedule_trainer`
--

CREATE TABLE IF NOT EXISTS `training_schedule_trainer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_schedule_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `hours` decimal(5,2) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tb_training_schedule_id` (`training_schedule_id`,`trainer_id`),
  KEY `tb_training_class_subject_assignment_id` (`trainer_id`),
  KEY `tb_training_class_id` (`training_schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_student`
--

CREATE TABLE IF NOT EXISTS `training_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_assignment_tb_training_subject1` (`training_id`),
  KEY `tb_student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`training_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `training_subject_trainer_recommendation`
--

CREATE TABLE IF NOT EXISTS `training_subject_trainer_recommendation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `program_subject_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `sort` int(5) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_subject_trainer_recommendation_tb_training_sub1` (`program_subject_id`),
  KEY `fk_tb_training_subject_trainer_recommendation_tb_trainer1` (`trainer_id`),
  KEY `tb_training_id` (`training_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', '5_082mYBEKJPI2VsPKBUwh6EEZFhpuv0', '$2y$13$.uslseFdd5WQCFEXdlx.Du7FCynIsh8qSi2NWOTpxi7zhcoPYghMa', NULL, 'admin@gmail.com', 10, 10, 1411603965, 1411603965);

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
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `object_file_ibfk_1` FOREIGN KEY (`object_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `object_reference`
--
ALTER TABLE `object_reference`
  ADD CONSTRAINT `object_reference_ibfk_1` FOREIGN KEY (`object_id`) REFERENCES `reference` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `training_class_student_ibfk_3` FOREIGN KEY (`training_student_id`) REFERENCES `training_student` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_class_student_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_class_student_ibfk_2` FOREIGN KEY (`training_class_id`) REFERENCES `training_class` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `training_class_subject_trainer_evaluation_ibfk_3` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`person_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_class_subject_trainer_evaluation_ibfk_1` FOREIGN KEY (`training_class_subject_id`) REFERENCES `training_class_subject` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_class_subject_trainer_evaluation_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`person_id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_execution_evaluation`
--
ALTER TABLE `training_execution_evaluation`
  ADD CONSTRAINT `training_execution_evaluation_ibfk_1` FOREIGN KEY (`training_class_student_id`) REFERENCES `training_class_student` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_schedule`
--
ALTER TABLE `training_schedule`
  ADD CONSTRAINT `training_schedule_ibfk_2` FOREIGN KEY (`training_class_subject_id`) REFERENCES `training_class_subject` (`id`) ON UPDATE CASCADE,
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
  ADD CONSTRAINT `training_student_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`person_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_student_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_student_plan`
--
ALTER TABLE `training_student_plan`
  ADD CONSTRAINT `training_student_plan_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `training_subject_trainer_recommendation`
--
ALTER TABLE `training_subject_trainer_recommendation`
  ADD CONSTRAINT `training_subject_trainer_recommendation_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`person_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_subject_trainer_recommendation_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
