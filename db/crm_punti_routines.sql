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
-- Temporary view structure for view `v_receipts`
--

DROP TABLE IF EXISTS `v_receipts`;
/*!50001 DROP VIEW IF EXISTS `v_receipts`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_receipts` AS SELECT 
 1 AS `invoice_id`,
 1 AS `invoice_status_id`,
 1 AS `DataFatturaDa`,
 1 AS `DataFatturaA`,
 1 AS `imponibile`,
 1 AS `DesEstesa`,
 1 AS `PartitaIva`,
 1 AS `CodFiscale`,
 1 AS `company`,
 1 AS `importdate`,
 1 AS `invoice_status`,
 1 AS `months`,
 1 AS `creditid`,
 1 AS `bookid`,
 1 AS `credits_date`,
 1 AS `points`,
 1 AS `origin`,
 1 AS `status`,
 1 AS `usrbookid`,
 1 AS `discountable`,
 1 AS `value`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_credits_list`
--

DROP TABLE IF EXISTS `v_credits_list`;
/*!50001 DROP VIEW IF EXISTS `v_credits_list`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_credits_list` AS SELECT 
 1 AS `id`,
 1 AS `bookid`,
 1 AS `date`,
 1 AS `points`,
 1 AS `origin`,
 1 AS `status`,
 1 AS `note`,
 1 AS `company`,
 1 AS `bookingmail`,
 1 AS `crmemail`,
 1 AS `codfiscale`,
 1 AS `partitaiva`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_charges`
--

DROP TABLE IF EXISTS `v_charges`;
/*!50001 DROP VIEW IF EXISTS `v_charges`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_charges` AS SELECT 
 1 AS `id`,
 1 AS `number`,
 1 AS `center`,
 1 AS `category`,
 1 AS `source`,
 1 AS `dom2userid`,
 1 AS `datestart`,
 1 AS `dateend`,
 1 AS `value`,
 1 AS `partitaiva`,
 1 AS `codfiscale`,
 1 AS `description`,
 1 AS `deleted`,
 1 AS `company`,
 1 AS `bookingmail`,
 1 AS `bookid`,
 1 AS `crmid`,
 1 AS `crmtype`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_credits_anniversaries`
--

DROP TABLE IF EXISTS `v_credits_anniversaries`;
/*!50001 DROP VIEW IF EXISTS `v_credits_anniversaries`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_credits_anniversaries` AS SELECT 
 1 AS `id`,
 1 AS `bookid`,
 1 AS `points`,
 1 AS `date`,
 1 AS `origin`,
 1 AS `bookingmail`,
 1 AS `crmemail`,
 1 AS `company`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_where_options`
--

DROP TABLE IF EXISTS `v_where_options`;
/*!50001 DROP VIEW IF EXISTS `v_where_options`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_where_options` AS SELECT 
 1 AS `id`,
 1 AS `restype`,
 1 AS `metakey`,
 1 AS `value`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_continuity`
--

DROP TABLE IF EXISTS `v_continuity`;
/*!50001 DROP VIEW IF EXISTS `v_continuity`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_continuity` AS SELECT 
 1 AS `id`,
 1 AS `codfiscale`,
 1 AS `first_invoice`,
 1 AS `months`,
 1 AS `invoicedate`,
 1 AS `invoice_status`,
 1 AS `active`,
 1 AS `user_status`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_general_options`
--

DROP TABLE IF EXISTS `v_general_options`;
/*!50001 DROP VIEW IF EXISTS `v_general_options`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_general_options` AS SELECT 
 1 AS `id`,
 1 AS `restype`,
 1 AS `metakey`,
 1 AS `value`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_credits`
--

DROP TABLE IF EXISTS `v_credits`;
/*!50001 DROP VIEW IF EXISTS `v_credits`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_credits` AS SELECT 
 1 AS `id`,
 1 AS `bookid`,
 1 AS `points`,
 1 AS `date`,
 1 AS `status`,
 1 AS `company`,
 1 AS `bookingmail`,
 1 AS `crmemail`,
 1 AS `origin`,
 1 AS `codfiscale`,
 1 AS `partitaiva`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_promo_options`
--

DROP TABLE IF EXISTS `v_promo_options`;
/*!50001 DROP VIEW IF EXISTS `v_promo_options`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_promo_options` AS SELECT 
 1 AS `id`,
 1 AS `restype`,
 1 AS `metakey`,
 1 AS `value`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_invoices`
--

DROP TABLE IF EXISTS `v_invoices`;
/*!50001 DROP VIEW IF EXISTS `v_invoices`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_invoices` AS SELECT 
 1 AS `invoice_id`,
 1 AS `invoice_status_id`,
 1 AS `DataFatturaDa`,
 1 AS `DataFatturaA`,
 1 AS `imponibile`,
 1 AS `DesEstesa`,
 1 AS `PartitaIva`,
 1 AS `CodFiscale`,
 1 AS `company`,
 1 AS `importdate`,
 1 AS `invoice_status`,
 1 AS `months`,
 1 AS `creditid`,
 1 AS `bookid`,
 1 AS `credits_date`,
 1 AS `points`,
 1 AS `origin`,
 1 AS `status`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `v_receipts`
--

/*!50001 DROP VIEW IF EXISTS `v_receipts`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_receipts` AS select `v_invoices`.`invoice_id` AS `invoice_id`,`v_invoices`.`invoice_status_id` AS `invoice_status_id`,`v_invoices`.`DataFatturaDa` AS `DataFatturaDa`,`v_invoices`.`DataFatturaA` AS `DataFatturaA`,`v_invoices`.`imponibile` AS `imponibile`,`v_invoices`.`DesEstesa` AS `DesEstesa`,`v_invoices`.`PartitaIva` AS `PartitaIva`,`v_invoices`.`CodFiscale` AS `CodFiscale`,`v_invoices`.`company` AS `company`,`v_invoices`.`importdate` AS `importdate`,`v_invoices`.`invoice_status` AS `invoice_status`,`v_invoices`.`months` AS `months`,`v_invoices`.`creditid` AS `creditid`,`v_invoices`.`bookid` AS `bookid`,`v_invoices`.`credits_date` AS `credits_date`,`v_invoices`.`points` AS `points`,`v_invoices`.`origin` AS `origin`,`v_invoices`.`status` AS `status`,`users`.`bookid` AS `usrbookid`,floor(((`v_invoices`.`imponibile` * 3) / 1000)) AS `discountable`,cast(round((`v_invoices`.`imponibile` / 100),2) as decimal(10,2)) AS `value` from (`v_invoices` left join `users` on((`v_invoices`.`CodFiscale` = `users`.`codfiscale`))) where ((`v_invoices`.`invoice_status` = 'receipt') and ((`v_invoices`.`DesEstesa` like '%Sal%') or (`v_invoices`.`DesEstesa` like '%Ufficio%') or (`v_invoices`.`DesEstesa` like '%desk%') or (`v_invoices`.`DesEstesa` like '%office%') or (`v_invoices`.`DesEstesa` like '%eventi%') or (`v_invoices`.`DesEstesa` like '%telef%') or (`v_invoices`.`DesEstesa` like '%messagg%') or (`v_invoices`.`DesEstesa` like '%segreteria%') or (`v_invoices`.`DesEstesa` like '%fattorino%') or (`v_invoices`.`DesEstesa` like '%assist%') or (`v_invoices`.`DesEstesa` like '%prenot%') or (`v_invoices`.`DesEstesa` like '%video%') or (`v_invoices`.`DesEstesa` like '%lavagn%') or (`v_invoices`.`DesEstesa` like '%tv%') or (`v_invoices`.`DesEstesa` like '%pc%')) and (`users`.`bookid` <> 0) and (floor(((`v_invoices`.`imponibile` * 3) / 1000)) <> 0)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_credits_list`
--

/*!50001 DROP VIEW IF EXISTS `v_credits_list`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_credits_list` AS select `credits`.`id` AS `id`,`credits`.`bookid` AS `bookid`,`credits`.`date` AS `date`,`credits`.`points` AS `points`,`credits`.`origin` AS `origin`,`credits`.`status` AS `status`,`credits`.`note` AS `note`,`users`.`company` AS `company`,`users`.`bookingmail` AS `bookingmail`,`users`.`crmemail` AS `crmemail`,`users`.`codfiscale` AS `codfiscale`,`users`.`partitaiva` AS `partitaiva` from ((`credits` join `esolver_invoices` on((`credits`.`invoiceid` = `esolver_invoices`.`id`))) join `users` on((`users`.`codfiscale` = `esolver_invoices`.`CodFiscale`))) union all select `credits`.`id` AS `id`,`credits`.`bookid` AS `bookid`,`credits`.`date` AS `date`,`credits`.`points` AS `points`,`credits`.`origin` AS `origin`,`credits`.`status` AS `status`,`credits`.`note` AS `note`,`users`.`company` AS `company`,`users`.`bookingmail` AS `bookingmail`,`users`.`crmemail` AS `crmemail`,`users`.`codfiscale` AS `codicefiscale`,`users`.`partitaiva` AS `partitaiva` from (`credits` join `users` on((`users`.`bookid` = `credits`.`bookid`))) where (`credits`.`origin` in ('ANNIVERSARY','BIRTHDAY','BONUS','RENEWAL','CORRECTION')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_charges`
--

/*!50001 DROP VIEW IF EXISTS `v_charges`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_charges` AS select `charges`.`id` AS `id`,`charges`.`number` AS `number`,`charges`.`center` AS `center`,`charges`.`category` AS `category`,`charges`.`source` AS `source`,`charges`.`dom2userid` AS `dom2userid`,`charges`.`datestart` AS `datestart`,`charges`.`dateend` AS `dateend`,`charges`.`value` AS `value`,`charges`.`partitaiva` AS `partitaiva`,`charges`.`codfiscale` AS `codfiscale`,`charges`.`description` AS `description`,`charges`.`deleted` AS `deleted`,`users`.`company` AS `company`,`users`.`bookingmail` AS `bookingmail`,`users`.`bookid` AS `bookid`,`users`.`crmid` AS `crmid`,`users`.`crmtype` AS `crmtype` from (`charges` left join `users` on((`charges`.`codfiscale` = `users`.`codfiscale`))) where ((`users`.`bookid` <> 0) and (`users`.`status` = 'active')) order by `users`.`company` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_credits_anniversaries`
--

/*!50001 DROP VIEW IF EXISTS `v_credits_anniversaries`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_credits_anniversaries` AS select `credits`.`id` AS `id`,`credits`.`bookid` AS `bookid`,`credits`.`points` AS `points`,`credits`.`date` AS `date`,`credits`.`origin` AS `origin`,`users`.`bookingmail` AS `bookingmail`,`users`.`crmemail` AS `crmemail`,`users`.`company` AS `company` from (`credits` left join `users` on((`credits`.`bookid` = `users`.`bookid`))) where (`credits`.`origin` in ('ANNIVERSARY','BIRTHDAY')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_where_options`
--

/*!50001 DROP VIEW IF EXISTS `v_where_options`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_where_options` AS select `credits_schema`.`id` AS `id`,`credits_schema`.`restype` AS `restype`,`credits_schema`.`metakey` AS `metakey`,`credits_schema`.`value` AS `value` from `credits_schema` where (`credits_schema`.`metakey` like '%condizioni%') order by `credits_schema`.`restype` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_continuity`
--

/*!50001 DROP VIEW IF EXISTS `v_continuity`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_continuity` AS select `esolver_invoices`.`id` AS `id`,`esolver_invoices`.`CodFiscale` AS `codfiscale`,`esolver_invoices`.`DataFatturaDa` AS `first_invoice`,round(((to_days(`esolver_invoices`.`DataFatturaA`) - to_days(`esolver_invoices`.`DataFatturaDa`)) / 30),0) AS `months`,`esolver_invoices_importstatus`.`date` AS `invoicedate`,`esolver_invoices_importstatus`.`status` AS `invoice_status`,`users`.`active` AS `active`,`users`.`status` AS `user_status` from ((`esolver_invoices` left join `esolver_invoices_importstatus` on((`esolver_invoices`.`id` = `esolver_invoices_importstatus`.`id`))) join `users` on((`users`.`codfiscale` = `esolver_invoices`.`CodFiscale`))) having ((`invoice_status` not in ('RECEIPT','imported','MANUAL','ZERO')) and (`user_status` not in ('nocf','nomail')) and (`users`.`active` = 1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_general_options`
--

/*!50001 DROP VIEW IF EXISTS `v_general_options`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_general_options` AS select `credits_schema`.`id` AS `id`,`credits_schema`.`restype` AS `restype`,`credits_schema`.`metakey` AS `metakey`,`credits_schema`.`value` AS `value` from `credits_schema` where ((not((`credits_schema`.`metakey` like '%condiz%'))) and (not((`credits_schema`.`metakey` like '%Promo%')))) order by `credits_schema`.`restype` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_credits`
--

/*!50001 DROP VIEW IF EXISTS `v_credits`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_credits` AS select `credits`.`id` AS `id`,`users`.`bookid` AS `bookid`,`credits`.`points` AS `points`,`credits`.`date` AS `date`,`credits`.`status` AS `status`,`users`.`company` AS `company`,`users`.`bookingmail` AS `bookingmail`,`users`.`crmemail` AS `crmemail`,`credits`.`origin` AS `origin`,`users`.`codfiscale` AS `codfiscale`,`users`.`partitaiva` AS `partitaiva` from ((`credits` left join `esolver_invoices` on((`esolver_invoices`.`id` = `credits`.`invoiceid`))) left join `users` on((`esolver_invoices`.`CodFiscale` = `users`.`codfiscale`))) where ((`credits`.`status` not in ('zero','credited','sentlost')) and (`users`.`codfiscale` <> '')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_promo_options`
--

/*!50001 DROP VIEW IF EXISTS `v_promo_options`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_promo_options` AS select `credits_schema`.`id` AS `id`,`credits_schema`.`restype` AS `restype`,`credits_schema`.`metakey` AS `metakey`,`credits_schema`.`value` AS `value` from `credits_schema` where (`credits_schema`.`metakey` like '%Promo%') order by `credits_schema`.`restype`,`credits_schema`.`metakey` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_invoices`
--

/*!50001 DROP VIEW IF EXISTS `v_invoices`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_invoices` AS select `esolver_invoices`.`id` AS `invoice_id`,`esolver_invoices_importstatus`.`id` AS `invoice_status_id`,`esolver_invoices`.`DataFatturaDa` AS `DataFatturaDa`,`esolver_invoices`.`DataFatturaA` AS `DataFatturaA`,`esolver_invoices`.`ImportoValuta` AS `imponibile`,`esolver_invoices`.`DesEstesa` AS `DesEstesa`,`esolver_invoices`.`PartitaIva` AS `PartitaIva`,`esolver_invoices`.`CodFiscale` AS `CodFiscale`,concat(`esolver_invoices`.`RagSoc1`,' ',`esolver_invoices`.`RagSoc2`) AS `company`,`esolver_invoices_importstatus`.`date` AS `importdate`,`esolver_invoices_importstatus`.`status` AS `invoice_status`,round(((to_days(`esolver_invoices`.`DataFatturaA`) - to_days(`esolver_invoices`.`DataFatturaDa`)) / 30),0) AS `months`,`credits`.`id` AS `creditid`,`credits`.`bookid` AS `bookid`,`credits`.`date` AS `credits_date`,`credits`.`points` AS `points`,`credits`.`origin` AS `origin`,`credits`.`status` AS `status` from ((`esolver_invoices` left join `esolver_invoices_importstatus` on((`esolver_invoices`.`id` = `esolver_invoices_importstatus`.`id`))) left join `credits` on((`credits`.`invoiceid` = `esolver_invoices`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-13 13:28:08
