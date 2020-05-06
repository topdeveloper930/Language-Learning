-- MySQL dump 10.13  Distrib 5.7.25, for Win64 (x86_64)
--
-- Host: localhost    Database: pronunci_livelingua
-- ------------------------------------------------------
-- Server version	5.7.25

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
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`(100)),
  KEY `locationCountries_code_index` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=259 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locationCountries`
--

LOCK TABLES `locationCountries` WRITE;
/*!40000 ALTER TABLE `locationCountries` DISABLE KEYS */;
INSERT INTO `locationCountries` VALUES (1,'Albania','al'),(2,'Algeria','dz'),(3,'American Samoa','as'),(4,'Angola','ao'),(5,'Anguilla','ai'),(6,'Antarctica','aq'),(7,'Antigua and Barbuda','ag'),(8,'Argentina','ar'),(9,'Armenia','am'),(10,'Aruba','aw'),(11,'Ashmore and Cartier','au'),(12,'Australia','au'),(13,'Austria','at'),(14,'Azerbaijan','az'),(15,'Bahamas','bs'),(16,'Bahrain','bh'),(17,'Bangladesh','bd'),(18,'Barbados','bb'),(19,'Belarus','by'),(20,'Belgium','be'),(21,'Belize','bz'),(22,'Benin','bj'),(23,'Bermuda','bm'),(24,'Bhutan','bt'),(25,'Bolivia','bo'),(26,'Bosnia and Herzegovina','ba'),(27,'Botswana','bw'),(28,'Brazil','br'),(29,'British Virgin Islands','vg'),(30,'Brunei','bn'),(31,'Bulgaria','bg'),(32,'Burkina Faso','bf'),(33,'Burma','mm'),(34,'Burundi','bi'),(35,'Cambodia','kh'),(36,'Cameroon','cm'),(37,'Canada','ca'),(38,'Cape Verde','cv'),(39,'Cayman Islands','ky'),(40,'Central African Republic','cf'),(41,'Chad','td'),(42,'Chile','cl'),(43,'China','cn'),(44,'Christmas Island','cx'),(45,'Clipperton Island','fr'),(46,'Cocos (Keeling) Islands','cc'),(47,'Colombia','co'),(48,'Comoros','km'),(49,'Congo, Democratic Republic of the','cd'),(50,'Congo, Republic of the','cg'),(51,'Cook Islands','ck'),(52,'Costa Rica','cr'),(54,'Croatia','hr'),(55,'Cuba','cu'),(56,'Cyprus','cy'),(57,'Czech Republic','cz'),(58,'Denmark','dk'),(59,'Djibouti','dj'),(60,'Dominica','dm'),(61,'Dominican Republic','do'),(62,'Ecuador','ec'),(63,'Egypt','eg'),(64,'El Salvador','sv'),(65,'Equatorial Guinea','gq'),(66,'Eritrea','er'),(67,'Estonia','ee'),(68,'Ethiopia','et'),(69,'Europa Island',''),(70,'Falkland Islands (Islas Malvinas)','fk'),(71,'Faroe Islands','fo'),(72,'Fiji','fj'),(73,'Finland','fi'),(74,'France','fr'),(75,'French Guiana','gf'),(76,'French Polynesia','pf'),(77,'French Southern and Antarctic Lands','tf'),(78,'Gabon','ga'),(79,'Gambia, The','gm'),(80,'Gaza Strip','ps'),(81,'Georgia','ge'),(82,'Germany','de'),(83,'Ghana','gh'),(84,'Gibraltar','gi'),(85,'Glorioso Islands',''),(86,'Greece','gr'),(87,'Greenland','gl'),(88,'Grenada','gd'),(89,'Guadeloupe','gp'),(90,'Guam','gu'),(91,'Guatemala','gt'),(92,'Guernsey','gg'),(93,'Guinea','gn'),(94,'Guinea-Bissau','gw'),(95,'Guyana','gy'),(96,'Haiti','ht'),(97,'Heard Island and McDonald Islands','hm'),(98,'Holy See (Vatican City)','va'),(99,'Honduras','hn'),(100,'Hong Kong','hk'),(101,'Howland Island','us'),(102,'Hungary','hu'),(103,'Iceland','is'),(104,'India','in'),(105,'Indonesia','id'),(106,'Iran','ir'),(107,'Iraq','iq'),(108,'Ireland','ie'),(109,'Ireland, Northern','gb'),(110,'Israel','il'),(111,'Italy','it'),(112,'Jamaica','jm'),(113,'Jan Mayen','sj'),(114,'Japan','jp'),(115,'Jarvis Island','us'),(116,'Jersey','je'),(117,'Johnston Atoll','us'),(118,'Jordan','jo'),(119,'Juan de Nova Island','fr'),(120,'Kazakhstan','kz'),(121,'Kenya','ke'),(122,'Kiribati','ki'),(123,'Korea, North','kp'),(124,'Korea, South','kr'),(125,'Kuwait','kw'),(126,'Kyrgyzstan','kg'),(127,'Laos','la'),(128,'Latvia','lv'),(129,'Lebanon','lb'),(130,'Lesotho','ls'),(131,'Liberia','lr'),(132,'Libya','ly'),(133,'Liechtenstein','li'),(134,'Lithuania','lt'),(135,'Luxembourg','lu'),(136,'Macau','mo'),(137,'Macedonia, Former Yugoslav Republic of','mk'),(138,'Madagascar','mg'),(139,'Malawi','mw'),(140,'Malaysia','my'),(141,'Maldives','mv'),(142,'Mali','ml'),(143,'Malta','mt'),(144,'Man, Isle of','im'),(145,'Marshall Islands','mh'),(146,'Martinique','mq'),(147,'Mauritania','mr'),(148,'Mauritius','mu'),(149,'Mayotte','yt'),(150,'Mexico','mx'),(151,'Micronesia','fm'),(152,'Midway Islands','us'),(153,'Moldova','md'),(154,'Monaco','mc'),(155,'Mongolia','mn'),(156,'Montserrat','ms'),(157,'Morocco','ma'),(158,'Mozambique','mz'),(159,'Namibia','na'),(160,'Nauru','nr'),(161,'Nepal','np'),(162,'Netherlands','nl'),(163,'Netherlands Antilles','an'),(164,'New Caledonia','nc'),(165,'New Zealand','nz'),(166,'Nicaragua','ni'),(167,'Niger','ne'),(168,'Nigeria','ng'),(169,'Niue','nu'),(170,'Norfolk Island','nf'),(171,'Northern Mariana Islands','mp'),(172,'Norway','no'),(173,'Oman','om'),(174,'Pakistan','pk'),(175,'Palau','pw'),(176,'Panama','pa'),(177,'Papua New Guinea','pg'),(178,'Paraguay','py'),(179,'Peru','pe'),(180,'Philippines','ph'),(181,'Pitcaim Islands','pn'),(182,'Poland','pl'),(183,'Portugal','pt'),(184,'Puerto Rico','pr'),(185,'Qatar','qa'),(186,'Reunion','re'),(187,'Romania','ro'),(188,'Russia','ru'),(189,'Rwanda','rw'),(190,'Saint Helena','sh'),(191,'Saint Kitts and Nevis','kn'),(192,'Saint Lucia','lc'),(193,'Saint Pierre and Miquelon','pm'),(194,'Saint Vincent and the Grenadines','vc'),(195,'Samoa','ws'),(196,'San Marino','sm'),(197,'Sao Tome and Principe','st'),(198,'Saudi Arabia','sa'),(199,'Scotland','gb'),(200,'Senegal','sn'),(201,'Seychelles','sc'),(202,'Sierra Leone','sl'),(203,'Singapore','sg'),(204,'Slovakia','sk'),(205,'Slovenia','si'),(206,'Solomon Islands','sb'),(207,'Somalia','so'),(208,'South Africa','za'),(209,'South Georgia and South Sandwich Islands','gs'),(210,'Spain','es'),(211,'Spratly Islands',''),(212,'Sri Lanka','lk'),(213,'Sudan','sd'),(214,'Suriname','sr'),(215,'Svalbard','sj'),(216,'Swaziland','sz'),(217,'Sweden','se'),(218,'Switzerland','ch'),(219,'Syria','sy'),(220,'Taiwan','tw'),(221,'Tajikistan','tj'),(222,'Tanzania','tz'),(223,'Thailand','th'),(224,'Tobago','tt'),(225,'Togo','tg'),(226,'Tokelau','tk'),(227,'Tonga','to'),(228,'Trinidad','tt'),(229,'Tunisia','tn'),(230,'Turkey','tr'),(231,'Turkmenistan','tm'),(232,'Tuvalu','tv'),(233,'Uganda','ug'),(234,'Ukraine','ua'),(235,'United Arab Emirates','ae'),(236,'United Kingdom','gb'),(237,'Uruguay','uy'),(238,'USA','us'),(239,'Uzbekistan','uz'),(240,'Vanuatu','vu'),(241,'Venezuela','ve'),(242,'Vietnam','vn'),(243,'Virgin Islands','vi'),(244,'Wales','gb'),(245,'Wallis and Futuna','wf'),(246,'West Bank','ps'),(247,'Western Sahara','eh'),(248,'Yemen','ye'),(249,'Yugoslavia',''),(250,'Zambia','zm'),(252,'Zimbabwe','zw'),(253,'Afghanistan','af'),(256,'Cote d\'Ivoire','ci'),(257,'Serbia','rs'),(258,'Montenegro','me');
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

-- Dump completed on 2020-03-05  9:56:29
