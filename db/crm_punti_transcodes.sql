-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 192.168.1.10    Database: crm_punti
-- ------------------------------------------------------
-- Server version	5.1.66

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
-- Dumping data for table `transcodes`
--

LOCK TABLES `transcodes` WRITE;
/*!40000 ALTER TABLE `transcodes` DISABLE KEYS */;
INSERT INTO `transcodes` VALUES (1,'account','Azienda'),(2,'lead','Libero Professionista'),(3,'active','Attivo'),(4,'tosign','Da registrare'),(5,'nomail','Email mancante'),(6,'nocf','Codice fiscale mancante'),(7,'ready','Pronto'),(8,'signed','Registrato'),(9,'lost','Perso'),(10,'zero','Nullo'),(11,'ANNIVERSARY','Anniversario'),(12,'COWORKING','Coworking'),(13,'HOTDESKINGFT','Hot Desking Fulltime'),(14,'MANUAL','Manuale'),(15,'OFFICESHARING','Office Sharing'),(16,'POSTALESTANDARD','Recapito postale'),(17,'RECCOMPLPERS','Recapito completo'),(18,'SEDELEGALE','Sede Legale'),(19,'UFFICIO','Ufficio'),(20,'imported','Importato'),(21,'RECEIPT','Cedolino'),(22,'credited','Accreditato'),(23,'sentlost','Mancato'),(24,'primarisorsa','Valore della prima risorsa'),(25,'risorsesuccessive','Valore delle risorse successive'),(26,'mesirinnovo','Mesi per avere bonus rinnovo'),(27,'fedelta','Bonus rinnovo'),(28,'promoda','Data di inizio della promozione'),(29,'promoa','Data di termine della promozione'),(30,'promovalorepercento','Aumento percentuale dei punti per promozione'),(31,'promovalorepunti','Aumento dei punti per promozione'),(32,'BIRTHDAY','Compleanno'),(33,'RENEWAL','Rinnovo'),(34,'BONUS','Bonus'),(35,'CORRECTION','Rettifica'),(36,'true','Vero'),(37,'false','Falso'),(38,'periodo','Fatturazione su periodo (dal - al)'),(39,'adjusted','Rettificata');
/*!40000 ALTER TABLE `transcodes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-13 13:28:08
