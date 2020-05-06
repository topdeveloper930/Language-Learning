-- MySQL dump 10.13  Distrib 5.6.45, for Linux (x86_64)
--
-- Host: localhost    Database: queretar_llingua
-- ------------------------------------------------------
-- Server version	5.6.45

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
-- Table structure for table `locationCountries`
--

DROP TABLE IF EXISTS `locationCountries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locationCountries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`(100))
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locationCountries`
--

LOCK TABLES `locationCountries` WRITE;
/*!40000 ALTER TABLE `locationCountries` DISABLE KEYS */;
INSERT INTO `locationCountries` VALUES (1,'Albania'),(2,'Algeria'),(3,'American Samoa'),(4,'Angola'),(5,'Anguilla'),(6,'Antartica'),(7,'Antigua and Barbuda'),(8,'Argentina'),(9,'Armenia'),(10,'Aruba'),(11,'Ashmore and Cartier'),(12,'Australia'),(13,'Austria'),(14,'Azerbaijan'),(15,'Bahamas'),(16,'Bahrain'),(17,'Bangladesh'),(18,'Barbados'),(19,'Belarus'),(20,'Belgium'),(21,'Belize'),(22,'Benin'),(23,'Bermuda'),(24,'Bhutan'),(25,'Bolivia'),(26,'Bosnia and Herzegovina'),(27,'Botswana'),(28,'Brazil'),(29,'British Virgin Islands'),(30,'Brunei'),(31,'Bulgaria'),(32,'Burkina Faso'),(33,'Burma'),(34,'Burundi'),(35,'Cambodia'),(36,'Cameroon'),(37,'Canada'),(38,'Cape Verde'),(39,'Cayman Islands'),(40,'Central African Republic'),(41,'Chad'),(42,'Chile'),(43,'China'),(44,'Christmas Island'),(45,'Clipperton Island'),(46,'Cocos (Keeling) Islands'),(47,'Colombia'),(48,'Comoros'),(49,'Congo, Democratic Republic of the'),(50,'Congo, Republic of the'),(51,'Cook Islands'),(52,'Costa Rica'),(54,'Croatia'),(55,'Cuba'),(56,'Cyprus'),(57,'Czech Republic'),(58,'Denmark'),(59,'Djibouti'),(60,'Dominica'),(61,'Dominican Republic'),(62,'Ecuador'),(63,'Egypt'),(64,'El Salvador'),(65,'Equatorial Guinea'),(66,'Eritrea'),(67,'Estonia'),(68,'Ethiopia'),(69,'Europa Island'),(70,'Falkland Islands (Islas Malvinas)'),(71,'Faroe Islands'),(72,'Fiji'),(73,'Finland'),(74,'France'),(75,'French Guiana'),(76,'French Polynesia'),(77,'French Southern and Antarctic Lands'),(78,'Gabon'),(79,'Gambia, The'),(80,'Gaza Strip'),(81,'Georgia'),(82,'Germany'),(83,'Ghana'),(84,'Gibraltar'),(85,'Glorioso Islands'),(86,'Greece'),(87,'Greenland'),(88,'Grenada'),(89,'Guadeloupe'),(90,'Guam'),(91,'Guatemala'),(92,'Guernsey'),(93,'Guinea'),(94,'Guinea-Bissau'),(95,'Guyana'),(96,'Haiti'),(97,'Heard Island and McDonald Islands'),(98,'Holy See (Vatican City)'),(99,'Honduras'),(100,'Hong Kong'),(101,'Howland Island'),(102,'Hungary'),(103,'Iceland'),(104,'India'),(105,'Indonesia'),(106,'Iran'),(107,'Iraq'),(108,'Ireland'),(109,'Ireland, Northern'),(110,'Israel'),(111,'Italy'),(112,'Jamaica'),(113,'Jan Mayen'),(114,'Japan'),(115,'Jarvis Island'),(116,'Jersey'),(117,'Johnston Atoll'),(118,'Jordan'),(119,'Juan de Nova Island'),(120,'Kazakhstan'),(121,'Kenya'),(122,'Kiribati'),(123,'Korea, North'),(124,'Korea, South'),(125,'Kuwait'),(126,'Kyrgyzstan'),(127,'Laos'),(128,'Latvia'),(129,'Lebanon'),(130,'Lesotho'),(131,'Liberia'),(132,'Libya'),(133,'Liechtenstein'),(134,'Lithuania'),(135,'Luxembourg'),(136,'Macau'),(137,'Macedonia, Former Yugoslav Republic of'),(138,'Madagascar'),(139,'Malawi'),(140,'Malaysia'),(141,'Maldives'),(142,'Mali'),(143,'Malta'),(144,'Man, Isle of'),(145,'Marshall Islands'),(146,'Martinique'),(147,'Mauritania'),(148,'Mauritius'),(149,'Mayotte'),(150,'Mexico'),(151,'Micronesia'),(152,'Midway Islands'),(153,'Moldova'),(154,'Monaco'),(155,'Mongolia'),(156,'Montserrat'),(157,'Morocco'),(158,'Mozambique'),(159,'Namibia'),(160,'Nauru'),(161,'Nepal'),(162,'Netherlands'),(163,'Netherlands Antilles'),(164,'New Caledonia'),(165,'New Zealand'),(166,'Nicaragua'),(167,'Niger'),(168,'Nigeria'),(169,'Niue'),(170,'Norfolk Island'),(171,'Northern Mariana Islands'),(172,'Norway'),(173,'Oman'),(174,'Pakistan'),(175,'Palau'),(176,'Panama'),(177,'Papua New Guinea'),(178,'Paraguay'),(179,'Peru'),(180,'Philippines'),(181,'Pitcaim Islands'),(182,'Poland'),(183,'Portugal'),(184,'Puerto Rico'),(185,'Qatar'),(186,'Reunion'),(187,'Romania'),(188,'Russia'),(189,'Rwanda'),(190,'Saint Helena'),(191,'Saint Kitts and Nevis'),(192,'Saint Lucia'),(193,'Saint Pierre and Miquelon'),(194,'Saint Vincent and the Grenadines'),(195,'Samoa'),(196,'San Marino'),(197,'Sao Tome and Principe'),(198,'Saudi Arabia'),(199,'Scotland'),(200,'Senegal'),(201,'Seychelles'),(202,'Sierra Leone'),(203,'Singapore'),(204,'Slovakia'),(205,'Slovenia'),(206,'Solomon Islands'),(207,'Somalia'),(208,'South Africa'),(209,'South Georgia and South Sandwich Islands'),(210,'Spain'),(211,'Spratly Islands'),(212,'Sri Lanka'),(213,'Sudan'),(214,'Suriname'),(215,'Svalbard'),(216,'Swaziland'),(217,'Sweden'),(218,'Switzerland'),(219,'Syria'),(220,'Taiwan'),(221,'Tajikistan'),(222,'Tanzania'),(223,'Thailand'),(224,'Tobago'),(225,'Toga'),(226,'Tokelau'),(227,'Tonga'),(228,'Trinidad'),(229,'Tunisia'),(230,'Turkey'),(231,'Turkmenistan'),(232,'Tuvalu'),(233,'Uganda'),(234,'Ukraine'),(235,'United Arab Emirates'),(236,'United Kingdom'),(237,'Uruguay'),(238,'USA'),(239,'Uzbekistan'),(240,'Vanuatu'),(241,'Venezuela'),(242,'Vietnam'),(243,'Virgin Islands'),(244,'Wales'),(245,'Wallis and Futuna'),(246,'West Bank'),(247,'Western Sahara'),(248,'Yemen'),(249,'Yugoslavia'),(250,'Zambia'),(252,'Zimbabwe'),(253,'Afghanistan'),(256,'Cote d\'Ivoire');
/*!40000 ALTER TABLE `locationCountries` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-03-05  1:06:59
