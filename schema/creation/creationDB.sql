-- MySQL dump 10.13  Distrib 8.0.27, for Linux (x86_64)
--
-- Host: localhost    Database: FourTestingP
-- ------------------------------------------------------
-- Server version	8.0.27-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `formTable`
--

DROP TABLE IF EXISTS `formTable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formTable` (
  `userID` int NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `duration` int NOT NULL,
  `year` varchar(20) DEFAULT NULL,
  `language` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`userID`),
  CONSTRAINT `formTable_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userLogin` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formTable`
--

LOCK TABLES `formTable` WRITE;
/*!40000 ALTER TABLE `formTable` DISABLE KEYS */;
/*!40000 ALTER TABLE `formTable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movies`
--

DROP TABLE IF EXISTS `movies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movies` (
  `movie_id` int NOT NULL AUTO_INCREMENT,
  `imbd_id` varchar(12) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text,
  `image` text,
  `genre` varchar(200) DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `year` int DEFAULT NULL,
  `language` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`movie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movies`
--

LOCK TABLES `movies` WRITE;
/*!40000 ALTER TABLE `movies` DISABLE KEYS */;
INSERT INTO `movies` VALUES (1,'123abc','funnytITLE','FUNNY LIFE AND STRESSFUL LIFE','IMAGE.PNG','HORROR',30,1990,'ENG');
/*!40000 ALTER TABLE `movies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `movie_id` int NOT NULL,
  `userID` int NOT NULL,
  `review_id` int NOT NULL AUTO_INCREMENT,
  `reviewRating` int NOT NULL,
  `reviewText` text,
  PRIMARY KEY (`review_id`),
  KEY `userID` (`userID`),
  KEY `movie_id` (`movie_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userLogin` (`userID`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testingcon`
--

DROP TABLE IF EXISTS `testingcon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testingcon` (
  `tiD` int NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`tiD`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testingcon`
--

LOCK TABLES `testingcon` WRITE;
/*!40000 ALTER TABLE `testingcon` DISABLE KEYS */;
INSERT INTO `testingcon` VALUES (1,'ryan1','passowrd1'),(2,'test','SuperSad4u32:('),(7,NULL,NULL),(8,NULL,NULL),(9,'andrew','password_lol');
/*!40000 ALTER TABLE `testingcon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userInv`
--

DROP TABLE IF EXISTS `userInv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `userInv` (
  `movie_id` int NOT NULL,
  `userID` int NOT NULL,
  KEY `userID` (`userID`),
  KEY `movie_id` (`movie_id`),
  CONSTRAINT `userInv_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userLogin` (`userID`),
  CONSTRAINT `userInv_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userInv`
--

LOCK TABLES `userInv` WRITE;
/*!40000 ALTER TABLE `userInv` DISABLE KEYS */;
/*!40000 ALTER TABLE `userInv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userLogin`
--

DROP TABLE IF EXISTS `userLogin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `userLogin` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userLogin`
--

LOCK TABLES `userLogin` WRITE;
/*!40000 ALTER TABLE `userLogin` DISABLE KEYS */;
INSERT INTO `userLogin` VALUES (1,'andrew_sohn','LINUX','sohna@njit.edu'),(4,'blah','blah','blah@aol.com'),(6,'jecl','123','jre29@njit.edu'),(7,'danny','123','dr479@njit.edu'),(8,'andrew','lol','ak2426@njit.edu'),(12,'itani','no survivors','itani@njit.edu');
/*!40000 ALTER TABLE `userLogin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userSession`
--

DROP TABLE IF EXISTS `userSession`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `userSession` (
  `session_id` varchar(255) DEFAULT NULL,
  `user_id` int NOT NULL,
  `creation` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userSession`
--

LOCK TABLES `userSession` WRITE;
/*!40000 ALTER TABLE `userSession` DISABLE KEYS */;
/*!40000 ALTER TABLE `userSession` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-08 21:24:11
