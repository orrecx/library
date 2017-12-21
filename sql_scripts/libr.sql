-- MySQL dump 10.16  Distrib 10.1.28-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: library
-- ------------------------------------------------------
-- Server version	10.1.28-MariaDB

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
-- Current Database: `library`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `library` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `library`;

--
-- Table structure for table `tb_books`
--

DROP TABLE IF EXISTS `tb_books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_books` (
  `book_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_title` varchar(128) NOT NULL,
  `book_author` varchar(128) NOT NULL,
  `book_onloan` tinyint(1) DEFAULT NULL,
  `book_duedate` date DEFAULT NULL,
  `borrower_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`book_id`),
  KEY `borrower_id` (`borrower_id`),
  CONSTRAINT `tb_books_ibfk_1` FOREIGN KEY (`borrower_id`) REFERENCES `tb_borrowers` (`borrower_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_books`
--

LOCK TABLES `tb_books` WRITE;
/*!40000 ALTER TABLE `tb_books` DISABLE KEYS */;
INSERT INTO `tb_books` VALUES (1,'Harry Potter and the G Fire','J. K. Rowling',0,NULL,NULL),(2,'Harry Potter and Half-Blood Prince','J. K. Rowling',0,NULL,NULL),(3,'Wind in the willows','Kenneth Grahame',0,NULL,NULL),(4,'Great Expectations','Charles Dickens',0,NULL,NULL),(5,'A christmas carol','Charles Dickens',0,NULL,NULL),(6,'Knots and crosses','Ian Rankin',0,NULL,NULL),(7,'The hanging garden','Ian Rankin',0,NULL,NULL),(8,'Linux System Programming','Robert Love',0,NULL,NULL),(9,'Suse Linux','Chris Brown',0,NULL,NULL),(10,'PHP and MySQL','Welling and Thomson',0,NULL,NULL),(11,'High Performance MySQL','Schwarts et al',0,NULL,NULL),(12,'Computer Security','Stallings and Brown',0,NULL,NULL),(13,'MySQL','Paul Dubois',0,NULL,NULL),(14,'PHP 7 und MySQL','Christian Wenz and Tobias Hauser',0,NULL,NULL),(15,'Modern PHP: New Features and Good Practices','Josh Lockhart',0,NULL,NULL),(16,'HTML5 und CSS3','Juergen Wolf',0,NULL,NULL),(17,'Angular: Grundlagen','Gregor Woiwode and Ferdinand Malcher',0,NULL,NULL),(18,'Angular 5: From Theory To Practice','Asim Hussain',0,NULL,NULL);
/*!40000 ALTER TABLE `tb_books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_borrowers`
--

DROP TABLE IF EXISTS `tb_borrowers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_borrowers` (
  `borrower_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `borrower_name` varchar(128) NOT NULL,
  `borrower_addresse` varchar(512) NOT NULL,
  `borrower_email` varchar(512) NOT NULL,
  PRIMARY KEY (`borrower_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_borrowers`
--

LOCK TABLES `tb_borrowers` WRITE;
/*!40000 ALTER TABLE `tb_borrowers` DISABLE KEYS */;
INSERT INTO `tb_borrowers` VALUES (1,'Atangana','Yaounde, olezoa','at@atango.com'),(2,'Madiba','Yaound, byiemassi','ma@madiba.com'),(3,'Simo','Doula, Cite verte','sim@simo.com'),(4,'Ndjee','Edea, sous le Pont','ndj@ndjee.com'),(5,'Salif','Garoua, Njamboutou','sal@salig.com'),(6,'Nguenang','Kribi, Plage','ngue@nguenang.com'),(7,'Ngo bell','Douala, Ndokoti','ngo.bell@bell.com');
/*!40000 ALTER TABLE `tb_borrowers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-16  0:54:03
