# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.22)
# Database: voucher
# Generation Time: 2018-08-17 04:13:17 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table phinxlog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `phinxlog`;

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `phinxlog` WRITE;
/*!40000 ALTER TABLE `phinxlog` DISABLE KEYS */;

INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`)
VALUES
	(20180812135858,'AddVoucherTables','2018-08-12 14:03:07','2018-08-12 14:03:08');

/*!40000 ALTER TABLE `phinxlog` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table recipients
# ------------------------------------------------------------

DROP TABLE IF EXISTS `recipients`;

CREATE TABLE `recipients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `recipients` WRITE;
/*!40000 ALTER TABLE `recipients` DISABLE KEYS */;

INSERT INTO `recipients` (`id`, `name`, `email`, `created_at`, `updated_at`, `status`)
VALUES
	(1,'aloz','aloz@gmail.com','2018-08-13 08:58:15','2018-08-13 08:58:15','active'),
	(2,'add','as@gmail.com','2018-08-16 15:24:08','2018-08-16 15:24:08','active'),
	(3,'aloz','alozi@gmail.com','2018-08-17 02:00:14','2018-08-17 02:00:14','active'),
	(4,'ade','alozo@gmail.com','2018-08-17 03:11:20','2018-08-17 03:11:20','active'),
	(5,'add','asop@gmail.com','2018-08-17 03:25:20','2018-08-17 03:25:20','active'),
	(6,'add','asoowp@gmail.com','2018-08-17 03:28:19','2018-08-17 03:28:19','active');

/*!40000 ALTER TABLE `recipients` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table special_offers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `special_offers`;

CREATE TABLE `special_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `special_offers` WRITE;
/*!40000 ALTER TABLE `special_offers` DISABLE KEYS */;

INSERT INTO `special_offers` (`id`, `name`, `discount`, `created_at`, `updated_at`, `status`)
VALUES
	(1,'yy',40.00,'2018-08-12 14:04:27','2018-08-12 14:04:27','active'),
	(2,'aloz',40.00,'2018-08-13 09:02:09','2018-08-13 09:02:09','active');

/*!40000 ALTER TABLE `special_offers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table vouchers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `vouchers`;

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(150) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `special_offer_id` int(11) NOT NULL,
  `expiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expire_interval` int(11) NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `date_of_usage` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','disabled', 'expired') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `recipient_id` (`recipient_id`),
  KEY `special_offer_id` (`special_offer_id`),
  CONSTRAINT `vouchers_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `recipients` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `vouchers_ibfk_2` FOREIGN KEY (`special_offer_id`) REFERENCES `special_offers` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `vouchers` WRITE;
/*!40000 ALTER TABLE `vouchers` DISABLE KEYS */;

INSERT INTO `vouchers` (`id`, `code`, `recipient_id`, `special_offer_id`, `expiry_date`, `expire_interval`, `is_used`, `date_of_usage`, `created_at`, `updated_at`, `status`)
VALUES
	(1,'S462OQKR',1,1,'2018-08-13 10:20:53',43000,1,'2018-08-13 10:20:53','2018-08-13 09:04:29','2018-08-13 10:20:53','disabled'),
	(2,'HM34T7YS',1,1,'2018-08-13 09:20:22',3000,0,NULL,'2018-08-13 09:20:22','2018-08-13 09:20:22','active');

/*!40000 ALTER TABLE `vouchers` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
