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
-- Dumping data for table `credits_schema`
--

LOCK TABLES `credits_schema` WRITE;
/*!40000 ALTER TABLE `credits_schema` DISABLE KEYS */;
INSERT INTO `credits_schema` VALUES (1,'UFFICIO','PrimaRisorsa','100'),(2,'UFFICIO','RisorseSuccessive','30'),(3,'UFFICIO','MesiRinnovo','6'),(4,'UFFICIO','Fedelta','50'),(6,'UFFICIO','Condizioni','AND ((DesEstesa LIKE \'%uffici%\' OR DesEstesa LIKE \'%Sala%\' OR DesEstesa LIKE \'%Hot desk%\') AND DesEstesa NOT LIKE \'%giorna%\') AND (RagSoc1 NOT LIKE \'%uffici%\' AND RagSoc2 NOT LIKE \'%uffici%\' AND DesEstesa NOT LIKE \'%uffici %\') '),(7,'OFFICESHARING','PrimaRisorsa','10'),(8,'OFFICESHARING','RisorseSuccessive','10'),(9,'OFFICESHARING','MesiRinnovo','0'),(10,'OFFICESHARING','Fedelta','0'),(11,'OFFICESHARING','Condizioni','AND (DesEstesa LIKE \'%postaz%\' AND DesEstesa LIKE \'%giornat%\'  AND DesEstesa NOT LIKE \'%B-SIDE%\')'),(12,'HOTDESKINGFT','PrimaRisorsa','15'),(13,'HOTDESKINGFT','RisorseSuccessive','15'),(14,'HOTDESKINGFT','MesiRinnovo','2'),(15,'HOTDESKINGFT','Fedelta','5'),(16,'HOTDESKINGFT','Condizioni','AND (DesEstesa LIKE \'%pacchett%\' AND DesEstesa LIKE \'%hotdesking%\' AND DesEstesa NOT LIKE  \'%4 ore%\')'),(17,'UFFICIO','Periodo','true'),(18,'OFFICESHARING','Periodo ','true'),(19,'HOTDESKINGFT','Periodo ','false'),(20,'COWORKING','PrimaRisorsa','0'),(21,'COWORKING','RisorseSuccessive','0'),(22,'COWORKING','MesiRinnovo','1'),(23,'COWORKING','Fedelta','5'),(24,'COWORKING','Condizioni','AND (DesEstesa LIKE \'%Postazione coworking%\')'),(25,'COWORKING','Periodo','true'),(26,'POSTALESTANDARD','PrimaRisorsa','5'),(27,'POSTALESTANDARD','RisorseSuccessive','5'),(28,'POSTALESTANDARD','MesiRinnovo','6'),(29,'POSTALESTANDARD','Fedelta','5'),(30,'POSTALESTANDARD','Condizioni','and (DesEstesa LIKE \'%rec%\' AND DesEstesa LIKE \'%post%\' AND DesEstesa NOT LIKE \'%smart%\') OR (DesEstesa LIKE \'%postale standard%\')'),(31,'POSTALESTANDARD','Periodo','true'),(32,'SEDELEGALE','PrimaRisorsa','10'),(33,'SEDELEGALE','RisorseSuccessive','10'),(34,'SEDELEGALE','MesiRinnovo','6'),(35,'SEDELEGALE','Fedelta','5'),(36,'SEDELEGALE','Condizioni','AND (DesEstesa LIKE \'%Sede Lega%\' OR DesEstesa like \'%unità locale%\') AND DesEstesa NOT LIKE \'%box%\''),(37,'SEDELEGALE','Periodo','true'),(38,'RECCOMPLPERS','PrimaRisorsa','10'),(39,'RECCOMPLPERS','RisorseSuccessive','10'),(40,'RECCOMPLPERS','MesiRinnovo','6'),(41,'RECCOMPLPERS','Fedelta','5'),(42,'RECCOMPLPERS','Condizioni','AND (DesEstesa LIKE \'%compl%\' AND DesEstesa LIKE \'%pers%\') OR (DesEstesa LIKE \'%recapito business%\') OR (DesEstesa LIKE \'%Recapito Smart%\')  OR (DesEstesa LIKE \'%recapito standard%\')'),(43,'RECCOMPLPERS','Periodo','true'),(44,'UFFICIO','PromoDa',NULL),(45,'UFFICIO ','PromoA',NULL),(46,'UFFICIO','PromoValorePercento','0'),(47,'UFFICIO','PromoValorePunti','0'),(48,'OFFICESHARING','PromoDa',NULL),(49,'OFFICESHARING','PromoA',NULL),(50,'OFFICESHARING','PromoValorePercento','0'),(51,'OFFICESHARING','PromoValorePunti','0'),(52,'RECCOMPLPERS','PromoDa',NULL),(53,'RECCOMPLPERS','PromoA',NULL),(54,'RECCOMPLPERS','PromoValorePercento','0'),(55,'RECCOMPLPERS','PromoValorePunti','0'),(56,'SEDELEGALE','PromoDa',NULL),(57,'SEDELEGALE','PromoA',NULL),(58,'SEDELEGALE','PromoValorePercento','0'),(59,'SEDELEGALE','PromoValorePunti','0'),(60,'POSTALESTANDARD','PromoDa',NULL),(61,'POSTALESTANDARD','PromoA',NULL),(62,'POSTALESTANDARD','PromoValorePercento','0'),(63,'POSTALESTANDARD','PromoValorePunti','0'),(64,'COWORKING','PromoDa','2019-03-01'),(65,'COWORKING','PromoA','2019-03-30'),(66,'COWORKING','PromoValorePercento','0'),(67,'COWORKING','PromoValorePunti','10'),(68,'HOTDESKINGFT','PromoDa',NULL),(69,'HOTDESKINGFT','PromoA',NULL),(70,'HOTDESKINGFT','PromoValorePercento','0'),(71,'HOTDESKINGFT','PromoValorePunti','0');
/*!40000 ALTER TABLE `credits_schema` ENABLE KEYS */;
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
