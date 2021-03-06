-- MySQL dump 10.13  Distrib 5.5.32, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: banksys
-- ------------------------------------------------------
-- Server version	5.5.32-0ubuntu0.12.04.1

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
-- Current Database: `banksys`
--

/*!40000 DROP DATABASE IF EXISTS `banksys`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `banksys` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `banksys`;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `account_number` varchar(8) NOT NULL,
  `client_id` int(8) NOT NULL,
  `balance` double unsigned NOT NULL DEFAULT '50000',
  `created_date` datetime NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_number_ukey` (`account_number`),
  KEY `account_k1` (`id`,`client_id`),
  KEY `account_k2` (`client_id`),
  CONSTRAINT `account_client_fk` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  `email` varchar(64) NOT NULL,
  `title_type_id` int(8) DEFAULT NULL,
  `use_scs` char(1) NOT NULL DEFAULT 'N',
  `created_date` datetime NOT NULL,
  `activation_date` datetime NOT NULL,
  `activated_by` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_ukey` (`email`),
  KEY `client_k1` (`title_type_id`),
  CONSTRAINT `client_title_type_fk` FOREIGN KEY (`title_type_id`) REFERENCES `title_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  `email` varchar(64) NOT NULL,
  `title_type_id` int(8) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `activation_date` datetime NOT NULL,
  `activated_by` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_k1` (`title_type_id`),
  CONSTRAINT `employee_title_type_fk` FOREIGN KEY (`title_type_id`) REFERENCES `title_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (1,'banksys','admin','admin@banksys.de',NULL,'2014-10-14 21:15:43','2014-10-14 21:15:43',1);
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scs`
--

DROP TABLE IF EXISTS `scs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scs` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `client_id` int(8) NOT NULL,
  `pin_code` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scs_ukey` (`pin_code`),
  KEY `account_k1` (`id`,`client_id`),
  KEY `scs_fk` (`client_id`),
  CONSTRAINT `scs_fk` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scs`
--

LOCK TABLES `scs` WRITE;
/*!40000 ALTER TABLE `scs` DISABLE KEYS */;
/*!40000 ALTER TABLE `scs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tan_code`
--

DROP TABLE IF EXISTS `tan_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tan_code` (
  `client_id` int(8) NOT NULL,
  `code` varchar(15) NOT NULL,
  `valid` char(1) NOT NULL DEFAULT 'Y',
  `used_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`client_id`,`code`),
  UNIQUE KEY `tan_code_ukey` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tan_code`
--

LOCK TABLES `tan_code` WRITE;
/*!40000 ALTER TABLE `tan_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `tan_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `title_type`
--

DROP TABLE IF EXISTS `title_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `title_type` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `title_type`
--

LOCK TABLES `title_type` WRITE;
/*!40000 ALTER TABLE `title_type` DISABLE KEYS */;
INSERT INTO `title_type` VALUES (1,'Mr.'),(2,'Mrs.'),(3,'Ms.');
/*!40000 ALTER TABLE `title_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `origin_account_id` int(8) NOT NULL,
  `destination_account_id` int(8) DEFAULT NULL,
  `amount` double NOT NULL,
  `transaction_type_id` int(8) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_k1` (`origin_account_id`),
  KEY `transaction_k2` (`destination_account_id`),
  KEY `transaction_k3` (`transaction_type_id`),
  CONSTRAINT `transaction_account_fk1` FOREIGN KEY (`origin_account_id`) REFERENCES `account` (`id`),
  CONSTRAINT `transaction_transaction_type_fk` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction`
--

LOCK TABLES `transaction` WRITE;
/*!40000 ALTER TABLE `transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_history`
--

DROP TABLE IF EXISTS `transaction_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_history` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `origin_account_id` int(8) NOT NULL,
  `destination_account_id` int(8) DEFAULT NULL,
  `amount` double NOT NULL,
  `transaction_type_id` int(8) NOT NULL,
  `created_date` datetime NOT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approved_by` int(8) DEFAULT NULL,
  `rejected_date` datetime DEFAULT NULL,
  `rejected_by` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_history_k1` (`origin_account_id`),
  KEY `transaction_history_k2` (`destination_account_id`),
  KEY `transaction_history_k3` (`transaction_type_id`),
  CONSTRAINT `transaction_history_account_fk1` FOREIGN KEY (`origin_account_id`) REFERENCES `account` (`id`),
  CONSTRAINT `transaction_history_transaction_type_fk` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_history`
--

LOCK TABLES `transaction_history` WRITE;
/*!40000 ALTER TABLE `transaction_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_type`
--

DROP TABLE IF EXISTS `transaction_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_type` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `description` varchar(128) NOT NULL,
  `short_description` char(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_type`
--

LOCK TABLES `transaction_type` WRITE;
/*!40000 ALTER TABLE `transaction_type` DISABLE KEYS */;
INSERT INTO `transaction_type` VALUES (1,'Deposit','D'),(2,'Withdrawal','W'),(3,'Transfer','T');
/*!40000 ALTER TABLE `transaction_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `person_id` int(8) NOT NULL,
  `pwd` varchar(64) NOT NULL,
  `token` varchar(20) DEFAULT NULL,
  `user_type_id` int(8) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_ukey` (`person_id`,`user_type_id`),
  KEY `user_k1` (`user_type_id`),
  CONSTRAINT `user_user_type` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,1,'5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',NULL,2,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_type`
--

DROP TABLE IF EXISTS `user_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_type` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_type`
--

LOCK TABLES `user_type` WRITE;
/*!40000 ALTER TABLE `user_type` DISABLE KEYS */;
INSERT INTO `user_type` VALUES (1,'Client'),(2,'Employee');
/*!40000 ALTER TABLE `user_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'banksys'
--
/*!50003 DROP PROCEDURE IF EXISTS `approveTransaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `approveTransaction`(IN `in_id` int(8), IN `in_employee_id` int(8))
BEGIN

    DECLARE last_id INT(8);
    DECLARE tran_type_desc CHAR(1);
    DECLARE tran_amount DOUBLE UNSIGNED;
    DECLARE o_account_id INT(8);
    DECLARE d_account_id INT(8);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
    BEGIN

        ROLLBACK;

        SHOW ERRORS;
    END;

    START TRANSACTION;

    INSERT INTO transaction_history(origin_account_id, destination_account_id, amount, transaction_type_id, created_date, approved_date, approved_by)
    SELECT origin_account_id, destination_account_id, amount, transaction_type_id, created_date, NOW(), in_employee_id FROM transaction WHERE id = in_id;

    SELECT LAST_INSERT_ID() INTO last_id;

    SELECT th.origin_account_id, th.destination_account_id, tt.short_description, th.amount INTO o_account_id, d_account_id, tran_type_desc, tran_amount
    FROM transaction_history th, transaction_type tt
    WHERE th.id = last_id AND th.transaction_type_id = tt.id;

    UPDATE account SET balance = CASE tran_type_desc
        WHEN 'D' THEN balance + tran_amount
        ELSE balance - tran_amount END
    WHERE id = o_account_id;

    IF d_account_id IS NOT NULL THEN
        UPDATE account SET balance = balance + tran_amount
        WHERE id = d_account_id;
    END IF;

    DELETE FROM transaction WHERE id = in_id;

    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `clientLogin` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `clientLogin`(IN `in_email` varchar(64), IN `in_pwd` varchar(64))
BEGIN
    SELECT c.id, c.title_type_id, tt.description title_type, c.first_name, c.last_name,
    c.email, a.account_number, a.balance, u.user_type_id, ut.description user_type, u.last_login
    FROM client c, title_type tt,
        user u, user_type ut, account a
    WHERE c.id = u.person_id AND u.user_type_id = ut.id
        AND c.email = in_email AND u.pwd = in_pwd
        AND c.activation_date IS NOT NULL AND c.activated_by IS NOT NULL
        AND c.id = a.client_id
        AND c.title_type_id = tt.id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `createAccount` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `createAccount`(IN `in_employee_id` int(8), IN `in_client_id` int(8))
BEGIN
    DECLARE last_account_id int(8);
    DECLARE new_account_number char(8);
    DECLARE EXIT HANDLER FOR 1062 SELECT 'Duplicate entry!' AS error_msg;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION SELECT 'SQLException found!' AS error_msg;

    INSERT INTO account(account_number, client_id, created_date)
    VALUES('', in_client_id, now());

    SET last_account_id = LAST_INSERT_ID();
    SET new_account_number = CAST(50000000 + last_account_id AS CHAR(8));

    UPDATE account SET account_number = new_account_number
    WHERE id = last_account_id;

    UPDATE client SET activation_date = now(), activated_by = in_employee_id
    WHERE id = in_client_id;

    SELECT last_account_id AS account_id, new_account_number AS account_number;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `createClient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `createClient`(IN `in_title_type_id` int(8), IN `in_first_name` varchar(128), IN `in_last_name` varchar(128), IN `in_email` varchar(64), IN `in_pwd` varchar(64), IN `in_scsOpt` char(1))
BEGIN
    DECLARE EXIT HANDLER FOR 1062 SELECT 'Duplicate entry!' AS error_msg;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION SELECT 'SQLException found!' AS error_msg;

    INSERT INTO client(first_name, last_name, email, title_type_id, use_scs)
    VALUES (in_first_name, in_last_name, in_email, in_title_type_id, in_scsOpt);

    INSERT INTO user(person_id, pwd, user_type_id)
    SELECT LAST_INSERT_ID() AS last_client_id,
        in_pwd AS in_pwd, id
    FROM user_type
    WHERE description = 'Client';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `deleteRejectedClient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRejectedClient`(IN `in_client_id` INT(8))
BEGIN
    DELETE FROM client WHERE id = in_client_id;
    DELETE FROM user WHERE person_id = in_client_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `employeeLogin` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `employeeLogin`(IN `in_email` varchar(64), IN `in_pwd` varchar(64))
BEGIN
    SELECT e.id, e.title_type_id, tt.description title_type, e.first_name, e.last_name,
        e.email, u.user_type_id, ut.description user_type, u.last_login
    FROM employee e left join title_type tt ON e.title_type_id = tt.id,
        user u, user_type ut
    WHERE e.id = u.person_id AND u.user_type_id = ut.id
        AND e.email = in_email AND u.pwd = in_pwd;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `forgetPassword` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `forgetPassword`(IN `in_email` varchar(64))
BEGIN
    DECLARE token_code VARCHAR(15);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION SELECT 'SQLException found!' AS error_msg;

    SELECT UPPER(SUBSTR(MD5(UUID()), 1, 10)) INTO token_code;

    UPDATE user SET token = token_code
    WHERE person_id = (SELECT id from client WHERE email = in_email) AND user_type_id = 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `generateClientTransactionCodes` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `generateClientTransactionCodes`(IN `in_client_id` int(8))
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE new_code VARCHAR(15);
    DECLARE is_scs_used CHAR(1);
    DECLARE new_pin_code INT(8);

    SELECT use_scs INTO is_scs_used FROM client WHERE id = in_client_id;

    IF is_scs_used = 'N' THEN

        WHILE i < 100 DO
            SELECT UPPER(SUBSTR(MD5(UUID()), 1, 15)) INTO new_code;

            IF(NOT EXISTS(SELECT code FROM tan_code WHERE code = new_code)) THEN
                INSERT INTO tan_code(client_id, code) VALUES (in_client_id, new_code);
                SET i = i + 1;
            END IF;

        END WHILE;

        SELECT code
        FROM tan_code
        WHERE client_id = in_client_id
        LIMIT 100;
    ELSE
        SELECT FLOOR(RAND() * 900000) + 100000 INTO new_pin_code;

        INSERT scs (client_id, pin_code) VALUES (in_client_id, new_pin_code);

        SELECT new_pin_code AS pin_code;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getAccountDetails` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAccountDetails`(IN `in_account_number` varchar(8))
BEGIN
    SELECT c.id, tt.description, c.first_name, c.last_name, c.email, a.account_number, a.balance, c.use_scs
    FROM account a, client c, title_type tt
    WHERE a.account_number = in_account_number AND a.client_id = c.id
        AND c.title_type_id = tt.id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getAccountTransactionHistory` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAccountTransactionHistory`(IN `in_account_number` varchar(8))
BEGIN
    SELECT a1.account_number AS origin, CONCAT(c1.first_name, ' ', c1.last_name) AS origin_name,
        a2.account_number AS destination, CONCAT(c2.first_name, ' ', c2.last_name) AS destination_name,
        th.amount, th.created_date, th.approved_date, th.rejected_date,
        CONCAT(tt.description, ' - ', CASE WHEN th.rejected_date IS NULL THEN 'Approved' ELSE 'Rejected' END) AS description
    FROM transaction_history th
        LEFT OUTER JOIN account a2 ON th.destination_account_id = a2.id
        LEFT OUTER JOIN client c2 ON a2.client_id = c2.id,
        account a1, transaction_type tt, client c1
    WHERE a1.account_number = in_account_number
        AND a1.client_id = c1.id
        AND a1.id = th.origin_account_id
        AND th.transaction_type_id = tt.id
     UNION
     SELECT a1.account_number, CONCAT(c1.first_name, ' ', c1.last_name),
        a2.account_number, CONCAT(c2.first_name, ' ', c2.last_name),
        t.amount, t.created_date, NULL, NULL,
        CONCAT(tt.description, ' - Pending') AS description
    FROM transaction t
        LEFT OUTER JOIN account a2 ON t.destination_account_id = a2.id
        LEFT OUTER JOIN client c2 ON a2.client_id = c2.id,
        account a1, transaction_type tt, client c1
    WHERE a1.account_number = in_account_number
        AND a1.client_id = c1.id
        AND a1.id = t.origin_account_id
        AND t.transaction_type_id = tt.id
    UNION
    SELECT a1.account_number, CONCAT(c1.first_name, ' ', c1.last_name),
        a2.account_number, CONCAT(c2.first_name, ' ', c2.last_name),
        th.amount, th.created_date, th.approved_date, th.rejected_date,
        CONCAT(tt.description, ' - ', CASE WHEN th.rejected_date IS NULL THEN 'Approved' ELSE 'Rejected' END)
    FROM transaction_history th, account a2, client c2,
        account a1, transaction_type tt, client c1
    WHERE a2.account_number = in_account_number
        AND th.destination_account_id = a2.id
        AND a2.client_id = c2.id
        AND a1.client_id = c1.id
        AND a1.id = th.origin_account_id
        AND th.transaction_type_id = tt.id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getClientAccountAndBalance` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getClientAccountAndBalance`(IN `in_client_id` int(8))
BEGIN
    SELECT a.account_number, a.balance, c.use_scs
    FROM account a, client c
    WHERE a.client_id=in_client_id AND c.id = a.client_id; 
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getClientPaswordToken` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getClientPaswordToken`(IN `in_email` varchar(64))
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION SELECT 'SQLException found!' AS error_msg;

    SELECT u.token
    FROM user u, client c
    WHERE u.person_id = c.id AND c.email = in_email;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getClientsToApprove` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getClientsToApprove`()
BEGIN
    SELECT c.id, tt.description, c.first_name, c.last_name, c.email
    FROM client c, title_type tt
    WHERE tt.id = c.title_type_id AND activated_by= '0'
    LIMIT 10;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getClientTransationNumbers` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getClientTransationNumbers`(IN `in_client_id` int(8))
BEGIN
    SELECT code FROM tan_code WHERE client_id=in_client_id LIMIT 100;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getSCSPin` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getSCSPin`(IN `in_client_id` varchar(64))
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION SELECT 'SQLException found!' AS error_msg;

    SELECT pin_code FROM scs WHERE client_id=in_client_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getTitleTypes` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getTitleTypes`()
SELECT id, description FROM title_type ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getTransactionsToApprove` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getTransactionsToApprove`()
BEGIN
    SELECT t.id, a1.account_number AS origin, a2.account_number AS destination,
        t.amount, t.created_date, tt.description
    FROM transaction t LEFT OUTER JOIN account a2 ON t.destination_account_id = a2.id,
        account a1, transaction_type tt
    WHERE t.origin_account_id = a1.id AND t.transaction_type_id = tt.id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `performTransaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `performTransaction`(IN `in_client_id` int(8), IN `in_destination_account_number` varchar(8), IN `in_amount` double unsigned, IN `in_tan_code` varchar(15), IN `in_transaction_type_id` int(8))
BEGIN
    DECLARE error TINYINT DEFAULT 0;
    DECLARE origin_account_id int(8);
    DECLARE origin_account_number VARCHAR(8);
    DECLARE destination_account_id int(8);
    DECLARE is_scs_used CHAR(1);
    DECLARE scs_code VARCHAR(15);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
    BEGIN
    
        ROLLBACK;
        
        IF(error > 0 AND error < 7) THEN
            SELECT 'Error' AS Level,
                error AS Code,
                CASE error
                WHEN 1 THEN 'Unknown client!'
                WHEN 2 THEN 'Unknown account number!'
                WHEN 3 THEN 'Invalid TAN Code'
                WHEN 4 THEN 'Account does not have enough money!'
                WHEN 5 THEN 'Unknown transaction type!'
                WHEN 6 THEN 'Destination account does not exist!' END AS Message;
       ELSE
           SHOW ERRORS;
       END IF;
            
        
    END;
    
    START TRANSACTION;

        SELECT use_scs INTO is_scs_used FROM client WHERE id = in_client_id;

        IF is_scs_used = 'Y' THEN
            SELECT SUBSTR(MD5(CONCAT(in_destination_account_number, CAST(s.pin_code AS CHAR(6)), CAST(in_amount AS CHAR(100)), 'secureCodingTeam17')), 1, 15) INTO scs_code
            FROM account a, scs s WHERE a.client_id = in_client_id AND a.client_id = s.client_id;

        END IF;


        IF (EXISTS (SELECT id FROM client WHERE id = in_client_id)) THEN
            SELECT account_number, id INTO origin_account_number, origin_account_id FROM account WHERE client_id = in_client_id;

            IF origin_account_number IS NOT NULL THEN

                IF (EXISTS (
                        SELECT code FROM tan_code
                        WHERE client_id = in_client_id AND code = in_tan_code AND valid = 'Y')
                    OR (is_scs_used = 'Y' AND scs_code = in_tan_code)) THEN

                    IF is_scs_used = 'N' THEN
                        UPDATE tan_code SET valid = 'N' WHERE client_id = in_client_id AND code = in_tan_code;
                    END IF;

                    IF in_transaction_type_id = 1 THEN

                        IF in_amount < 10000 THEN

                            UPDATE account SET balance = balance + in_amount
                            WHERE client_id = in_client_id AND account_number = origin_account_number;

                            INSERT INTO transaction_history(origin_account_id, amount, transaction_type_id, created_date)
                            VALUES(origin_account_id, in_amount, in_transaction_type_id, now());

                        ELSE

                                INSERT INTO transaction(origin_account_id, amount, transaction_type_id, created_date)
                                VALUES(origin_account_id, in_amount, in_transaction_type_id, now());
                        END IF;
                    ELSE
                        IF (EXISTS (SELECT balance FROM account
                                        WHERE client_id = in_client_id AND account_number = origin_account_number
                                            AND balance >= in_amount)) THEN

                            IF in_transaction_type_id = 2 THEN
                                IF in_amount < 10000 THEN
                                    UPDATE account SET balance = balance - in_amount
                                    WHERE client_id = in_client_id AND account_number = origin_account_number;

                                    INSERT INTO transaction_history(origin_account_id, amount, transaction_type_id, created_date)
                                    VALUES(origin_account_id, in_amount, in_transaction_type_id, now());
                                ELSE
                                    INSERT INTO transaction(origin_account_id, amount, transaction_type_id, created_date)
                                    VALUES(origin_account_id, in_amount, in_transaction_type_id, now());
                                END IF;
                            ELSEIF in_transaction_type_id = 3 THEN
                                IF (EXISTS (SELECT id FROM account WHERE account_number = in_destination_account_number)) THEN

                                    SELECT id INTO destination_account_id FROM account WHERE account_number = in_destination_account_number;

                                    IF in_amount < 10000 THEN
                                        UPDATE account SET balance = balance - in_amount
                                        WHERE client_id = in_client_id AND account_number = origin_account_number;

                                        UPDATE account SET balance = balance + in_amount
                                        WHERE account_number = in_destination_account_number;

                                        INSERT INTO transaction_history(origin_account_id, destination_account_id, amount, transaction_type_id, created_date)
                                        VALUES(origin_account_id, destination_account_id, in_amount, in_transaction_type_id, now());
                                    ELSE
                                        INSERT INTO transaction(origin_account_id, destination_account_id, amount, transaction_type_id, created_date)
                                        VALUES(origin_account_id, destination_account_id, in_amount, in_transaction_type_id, now());
                                    END IF;
                                ELSE
                                    SET error = 6; 
                                    CALL raise_error;
                                END IF;
                            ELSE
                                SET error = 5; 
                                CALL raise_error;
                            END IF;
                        ELSE
                            SET error = 4; 
                            CALL raise_error;
                        END IF;
                    END IF;
                ELSE
                    SET error = 3; 
                    CALL raise_error;
                END IF;
            ELSE
                SET error = 2; 
                CALL raise_error;
            END IF;
        ELSE
            SET error = 1; 
            CALL raise_error;
        END IF;
    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `rejectTransaction` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `rejectTransaction`(IN `in_id` int(8), IN `in_employee_id` int(8))
BEGIN

    DECLARE last_id INT(8);
    DECLARE tran_type_desc CHAR(1);
    DECLARE tran_amount DOUBLE UNSIGNED;
    DECLARE account_id INT(8);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
    BEGIN

        ROLLBACK;

        SHOW ERRORS;
    END;

    START TRANSACTION;

    INSERT INTO transaction_history(origin_account_id, destination_account_id, amount, transaction_type_id, created_date, rejected_date, rejected_by)
    SELECT origin_account_id, destination_account_id, amount, transaction_type_id, created_date, NOW(), in_employee_id FROM transaction WHERE id = in_id;

    SELECT LAST_INSERT_ID() INTO last_id;

    SELECT th.origin_account_id, tt.short_description, th.amount INTO account_id, tran_type_desc, tran_amount
    FROM transaction_history th, transaction_type tt
    WHERE th.id = last_id AND th.transaction_type_id = tt.id;

    DELETE FROM transaction WHERE id = in_id;

    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `resetPassword` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `resetPassword`(IN `in_email` varchar(64), IN `in_token` varchar(15), IN `in_password` varchar(128))
BEGIN
    DECLARE token_code VARCHAR(15);
    DECLARE client_id INT(15);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION SELECT 'SQLException found!' AS error_msg;

    SELECT id INTO client_id FROM client WHERE email = in_email;
    SELECT token INTO token_code FROM user WHERE person_id = client_id AND user_type_id = 1;

    IF(token_code = in_token) THEN
        UPDATE user SET pwd = in_password,token = NULL
        WHERE user_type_id = 1 AND person_id = client_id;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Manually added
GRANT EXECUTE ON banksys.* TO 'webuser'@'localhost' IDENTIFIED BY 'kubruf#eGa4e';
GRANT EXECUTE ON banksys.* TO 'parser'@'localhost' IDENTIFIED BY 'vEq7saf@&eVU';

-- Dump completed on 2014-12-02  4:51:49
