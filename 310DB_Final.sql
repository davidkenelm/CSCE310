-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: 310_db2
-- ------------------------------------------------------
-- Server version	8.0.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applications` (
  `App_Num` int NOT NULL AUTO_INCREMENT,
  `Program_Num` int DEFAULT NULL,
  `UIN` int DEFAULT NULL,
  `Uncom_Cert` varchar(500) DEFAULT NULL,
  `Com_Cert` varchar(500) DEFAULT NULL,
  `Purpose_Statement` longtext,
  PRIMARY KEY (`App_Num`),
  KEY `Program_Num_app_idx` (`Program_Num`),
  KEY `UIN_app_idx` (`UIN`),
  CONSTRAINT `Program_Num_app` FOREIGN KEY (`Program_Num`) REFERENCES `programs` (`Program_Num`),
  CONSTRAINT `UIN_app` FOREIGN KEY (`UIN`) REFERENCES `college_student` (`UIN`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applications`
--

LOCK TABLES `applications` WRITE;
/*!40000 ALTER TABLE `applications` DISABLE KEYS */;
INSERT INTO `applications` VALUES (16,16,730002164,'Gamer #1 Certificate','#1 Victory Royale','fortnite battle pass');
/*!40000 ALTER TABLE `applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cert_enrollment`
--

DROP TABLE IF EXISTS `cert_enrollment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cert_enrollment` (
  `CertE_Num` int NOT NULL AUTO_INCREMENT,
  `UIN` int DEFAULT NULL,
  `Cert_ID` int DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Training_Status` varchar(45) DEFAULT NULL,
  `Program_Num` int DEFAULT NULL,
  `Semester` varchar(45) DEFAULT NULL,
  `Year` year DEFAULT NULL,
  PRIMARY KEY (`CertE_Num`),
  KEY `UIN_CE_idx` (`UIN`),
  KEY `Cert_ID_CE_idx` (`Cert_ID`),
  KEY `Program_Num_CE_idx` (`Program_Num`),
  CONSTRAINT `Cert_ID_CertE` FOREIGN KEY (`Cert_ID`) REFERENCES `certifications` (`Cert_ID`),
  CONSTRAINT `Program_Num_CertE` FOREIGN KEY (`Program_Num`) REFERENCES `programs` (`Program_Num`),
  CONSTRAINT `UIN_CertE` FOREIGN KEY (`UIN`) REFERENCES `college_student` (`UIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cert_enrollment`
--

LOCK TABLES `cert_enrollment` WRITE;
/*!40000 ALTER TABLE `cert_enrollment` DISABLE KEYS */;
/*!40000 ALTER TABLE `cert_enrollment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certifications`
--

DROP TABLE IF EXISTS `certifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certifications` (
  `Cert_ID` int NOT NULL AUTO_INCREMENT,
  `Level` varchar(45) DEFAULT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `Description` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`Cert_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certifications`
--

LOCK TABLES `certifications` WRITE;
/*!40000 ALTER TABLE `certifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `certifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_enrollment`
--

DROP TABLE IF EXISTS `class_enrollment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class_enrollment` (
  `CE_NUM` int NOT NULL AUTO_INCREMENT,
  `UIN` int DEFAULT NULL,
  `Class_ID` int DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Semester` varchar(45) DEFAULT NULL,
  `Year` year DEFAULT NULL,
  PRIMARY KEY (`CE_NUM`),
  KEY `UIN_classE_idx` (`UIN`),
  KEY `Class_ID_classE_idx` (`Class_ID`),
  CONSTRAINT `Class_ID_ClassE` FOREIGN KEY (`Class_ID`) REFERENCES `classes` (`Class_ID`),
  CONSTRAINT `UIN_ClassE` FOREIGN KEY (`UIN`) REFERENCES `college_student` (`UIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_enrollment`
--

LOCK TABLES `class_enrollment` WRITE;
/*!40000 ALTER TABLE `class_enrollment` DISABLE KEYS */;
/*!40000 ALTER TABLE `class_enrollment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes` (
  `Class_ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `Description` varchar(5000) DEFAULT NULL,
  `Type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Class_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `college_student`
--

DROP TABLE IF EXISTS `college_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `college_student` (
  `UIN` int NOT NULL,
  `Gender` varchar(45) DEFAULT NULL,
  `Hispanic_Latino` binary(1) DEFAULT NULL,
  `Race` varchar(45) DEFAULT NULL,
  `US_Citizen` binary(1) DEFAULT NULL,
  `First_Generation` binary(1) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `GPA` float DEFAULT NULL,
  `Major` varchar(45) DEFAULT NULL,
  `Minor #1` varchar(45) DEFAULT NULL,
  `Minor #2` varchar(45) DEFAULT NULL,
  `Expected_Graduation` smallint DEFAULT NULL,
  `School` varchar(45) DEFAULT NULL,
  `Classification` varchar(45) DEFAULT NULL,
  `Phone` int DEFAULT NULL,
  `Student_Type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`UIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `college_student`
--

LOCK TABLES `college_student` WRITE;
/*!40000 ALTER TABLE `college_student` DISABLE KEYS */;
INSERT INTO `college_student` VALUES (730002164,'Male',NULL,'Asian',NULL,NULL,'2002-05-05',3.63,'Computer Engineering','','',2089,'Engineering','Senior',1234567890,'Student');
/*!40000 ALTER TABLE `college_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentation`
--

DROP TABLE IF EXISTS `documentation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documentation` (
  `Doc_Num` int NOT NULL AUTO_INCREMENT,
  `App_Num` int DEFAULT NULL,
  `Link` varchar(5000) DEFAULT NULL,
  `Doc_Type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Doc_Num`),
  KEY `App_Num_Doc_idx` (`App_Num`),
  CONSTRAINT `App_Num_Doc` FOREIGN KEY (`App_Num`) REFERENCES `applications` (`App_Num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentation`
--

LOCK TABLES `documentation` WRITE;
/*!40000 ALTER TABLE `documentation` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `event_information`
--

DROP TABLE IF EXISTS `event_information`;
/*!50001 DROP VIEW IF EXISTS `event_information`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `event_information` AS SELECT 
 1 AS `Event_ID`,
 1 AS `UIN`,
 1 AS `Program_Num`,
 1 AS `Start_Date`,
 1 AS `Time`,
 1 AS `Location`,
 1 AS `End_Date`,
 1 AS `Event_Type`,
 1 AS `Attendee_Count`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `event_tracking`
--

DROP TABLE IF EXISTS `event_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_tracking` (
  `ET_Num` int NOT NULL AUTO_INCREMENT,
  `Event_ID` int DEFAULT NULL,
  `UIN` int DEFAULT NULL,
  PRIMARY KEY (`ET_Num`),
  KEY `UIN_ET_idx` (`UIN`),
  KEY `Event_ID_ET_idx` (`Event_ID`),
  CONSTRAINT `Event_ID_ET` FOREIGN KEY (`Event_ID`) REFERENCES `events` (`Event_ID`),
  CONSTRAINT `UIN_ET` FOREIGN KEY (`UIN`) REFERENCES `users` (`UIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_tracking`
--

LOCK TABLES `event_tracking` WRITE;
/*!40000 ALTER TABLE `event_tracking` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_tracking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `Event_ID` int NOT NULL AUTO_INCREMENT,
  `UIN` int DEFAULT NULL,
  `Program_Num` int DEFAULT NULL,
  `Start_Date` date DEFAULT NULL,
  `Time` time DEFAULT NULL,
  `Location` varchar(45) DEFAULT NULL,
  `End_Date` date DEFAULT NULL,
  `Event_Type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Event_ID`),
  KEY `UIN_event_idx` (`UIN`),
  KEY `Program_Num_event_idx` (`Program_Num`),
  CONSTRAINT `Program_Num_event` FOREIGN KEY (`Program_Num`) REFERENCES `programs` (`Program_Num`),
  CONSTRAINT `UIN_event` FOREIGN KEY (`UIN`) REFERENCES `users` (`UIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intern_app`
--

DROP TABLE IF EXISTS `intern_app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intern_app` (
  `IA_Num` int NOT NULL AUTO_INCREMENT,
  `UIN` int DEFAULT NULL,
  `Intern_ID` int DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Year` year DEFAULT NULL,
  PRIMARY KEY (`IA_Num`),
  KEY `UIN_intern_app_idx` (`UIN`),
  KEY `Intern_ID_intern_app_idx` (`Intern_ID`),
  CONSTRAINT `Intern_ID_intern_app` FOREIGN KEY (`Intern_ID`) REFERENCES `internships` (`Intern_ID`),
  CONSTRAINT `UIN_intern_app` FOREIGN KEY (`UIN`) REFERENCES `college_student` (`UIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intern_app`
--

LOCK TABLES `intern_app` WRITE;
/*!40000 ALTER TABLE `intern_app` DISABLE KEYS */;
/*!40000 ALTER TABLE `intern_app` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `internships`
--

DROP TABLE IF EXISTS `internships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `internships` (
  `Intern_ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `Description` varchar(5000) DEFAULT NULL,
  `Is_Gov` binary(1) DEFAULT NULL,
  PRIMARY KEY (`Intern_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internships`
--

LOCK TABLES `internships` WRITE;
/*!40000 ALTER TABLE `internships` DISABLE KEYS */;
/*!40000 ALTER TABLE `internships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `program_registration_view`
--

DROP TABLE IF EXISTS `program_registration_view`;
/*!50001 DROP VIEW IF EXISTS `program_registration_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `program_registration_view` AS SELECT 
 1 AS `Program_Num`,
 1 AS `Name`,
 1 AS `UIN`,
 1 AS `First_Name`,
 1 AS `M_Initial`,
 1 AS `Last_Name`,
 1 AS `Uncom_Cert`,
 1 AS `Com_Cert`,
 1 AS `Purpose_Statement`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programs` (
  `Program_Num` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `Description` varchar(5000) DEFAULT NULL,
  `isDeleted` binary(1) DEFAULT NULL,
  PRIMARY KEY (`Program_Num`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programs`
--

LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;
INSERT INTO `programs` VALUES (1,'Competitive Cars 1 Watching','Kachow!!!!',NULL),(12,'potato throwing','behind you',NULL),(14,'duck duck goose','duck',NULL),(16,'gamers rise up','fight discrimination ',NULL),(18,'I AM SPEED','i eat losers for breakfast',NULL),(20,'batman','MARTHA!!!',NULL),(22,'Archery','competitive bow shooting, crossbows disallowed, no skill',NULL),(23,'Fishing Dogs','caught one!',NULL);
/*!40000 ALTER TABLE `programs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `track`
--

DROP TABLE IF EXISTS `track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `track` (
  `Tracking_Num` int NOT NULL AUTO_INCREMENT,
  `Program_Num` int DEFAULT NULL,
  `Student_Num` int DEFAULT NULL,
  PRIMARY KEY (`Tracking_Num`),
  KEY `Program_Num_track_idx` (`Program_Num`),
  KEY `UIN_track_idx` (`Student_Num`),
  CONSTRAINT `Program_Num_track` FOREIGN KEY (`Program_Num`) REFERENCES `programs` (`Program_Num`),
  CONSTRAINT `UIN_track` FOREIGN KEY (`Student_Num`) REFERENCES `college_student` (`UIN`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `track`
--

LOCK TABLES `track` WRITE;
/*!40000 ALTER TABLE `track` DISABLE KEYS */;
INSERT INTO `track` VALUES (3,16,730002164);
/*!40000 ALTER TABLE `track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `UIN` int NOT NULL,
  `First_Name` varchar(45) DEFAULT NULL,
  `M_Initial` char(1) DEFAULT NULL,
  `Last_Name` varchar(45) DEFAULT NULL,
  `Username` varchar(45) DEFAULT NULL,
  `Password` varchar(45) DEFAULT NULL,
  `User_Type` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `Discord_Name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`UIN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (730002164,'Sohaib','A','Raja','username','pass','Student','raja@mail.com','discord');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `event_information`
--

/*!50001 DROP VIEW IF EXISTS `event_information`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `event_information` AS select `e`.`Event_ID` AS `Event_ID`,`e`.`UIN` AS `UIN`,`e`.`Program_Num` AS `Program_Num`,`e`.`Start_Date` AS `Start_Date`,`e`.`Time` AS `Time`,`e`.`Location` AS `Location`,`e`.`End_Date` AS `End_Date`,`e`.`Event_Type` AS `Event_Type`,count(`et`.`UIN`) AS `Attendee_Count` from (`events` `e` left join `event_tracking` `et` on((`e`.`Event_ID` = `et`.`Event_ID`))) group by `e`.`Event_ID`,`e`.`UIN`,`e`.`Program_Num`,`e`.`Start_Date`,`e`.`Time`,`e`.`Location`,`e`.`End_Date`,`e`.`Event_Type` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `program_registration_view`
--

/*!50001 DROP VIEW IF EXISTS `program_registration_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `program_registration_view` AS select `programs`.`Program_Num` AS `Program_Num`,`programs`.`Name` AS `Name`,`users`.`UIN` AS `UIN`,`users`.`First_Name` AS `First_Name`,`users`.`M_Initial` AS `M_Initial`,`users`.`Last_Name` AS `Last_Name`,`applications`.`Uncom_Cert` AS `Uncom_Cert`,`applications`.`Com_Cert` AS `Com_Cert`,`applications`.`Purpose_Statement` AS `Purpose_Statement` from (((`users` join `college_student` on((`users`.`UIN` = `college_student`.`UIN`))) join `applications` on((`college_student`.`UIN` = `applications`.`UIN`))) join `programs` on((`applications`.`Program_Num` = `programs`.`Program_Num`))) */;
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

-- Dump completed on 2023-12-05 22:34:24
