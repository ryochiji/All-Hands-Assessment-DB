-- MySQL dump 10.13  Distrib 5.1.31, for apple-darwin9.5.0 (i386)
--
-- Host: localhost    Database: assessments
-- ------------------------------------------------------
-- Server version	5.1.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `assessments`
--

DROP TABLE IF EXISTS `assessments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `assessments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indate` date DEFAULT NULL,
  `assessor` varchar(64) DEFAULT NULL,
  `translator` varchar(64) DEFAULT NULL,
  `family_name` varchar(64) DEFAULT NULL,
  `municipality` varchar(32) DEFAULT NULL,
  `latitude` float(9,6) DEFAULT NULL,
  `longitude` float(9,6) DEFAULT NULL,
  `address` text,
  `inhabitants` varchar(32) DEFAULT NULL,
  `occupations` text,
  `status` varchar(4) DEFAULT NULL,
  `residence` text,
  `plans` text,
  `elderly` tinyint(4) DEFAULT NULL,
  `disabled` tinyint(4) DEFAULT NULL,
  `small_children` tinyint(4) DEFAULT NULL,
  `single_female` tinyint(4) DEFAULT NULL,
  `insurance` tinyint(4) DEFAULT NULL,
  `ins_details` text,
  `description` text,
  `work` text,
  `needs` text,
  `contributions` text,
  `work_plan` text,
  `required` text,
  `est_vols` varchar(32) DEFAULT NULL,
  `est_days` varchar(32) DEFAULT NULL,
  `approved` tinyint(4) DEFAULT NULL,
  `app_deets` text,
  `perm` tinyint(4) DEFAULT NULL,
  `perm_deets` text,
  `rdy_date` date DEFAULT NULL,
  `done_date` date DEFAULT NULL,
  `rehab_status` tinyint(4) DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `employment` tinyint(4) DEFAULT NULL,
  `electricity` tinyint(4) DEFAULT NULL,
  `assmnt_date` date DEFAULT NULL,
  `blocked` tinyint(4) DEFAULT NULL,
  `proj_name` varchar(32) DEFAULT NULL,
  `shortdesc` varchar(32) DEFAULT NULL,
  `ins_deets` varchar(128) DEFAULT NULL,
  `rdy_notes` text,
  `ref_by` varchar(32) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `assmnt_date_idx` (`assmnt_date`),
  KEY `blocked_idx` (`blocked`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `calllog`
--

DROP TABLE IF EXISTS `calllog`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `calllog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) DEFAULT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `who` varchar(32) DEFAULT NULL,
  `comment` text,
  UNIQUE KEY `id` (`id`),
  KEY `assessment_id_idx` (`assessment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `phone_numbers`
--

DROP TABLE IF EXISTS `phone_numbers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `phone_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `number` varchar(16) DEFAULT NULL,
  `notes` text,
  UNIQUE KEY `id` (`id`),
  KEY `assessment_id_idx` (`assessment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `worklog`
--

DROP TABLE IF EXISTS `worklog`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `worklog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` int(11) DEFAULT NULL,
  `who` varchar(32) DEFAULT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment` text,
  `volunteers` int(11) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `assessment_id_idx` (`assessment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=200 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-06-10  8:14:37
