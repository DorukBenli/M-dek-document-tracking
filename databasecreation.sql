CREATE DATABASE  IF NOT EXISTS `bitirme` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `bitirme`;
-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: localhost    Database: bitirme
-- ------------------------------------------------------
-- Server version	8.0.32

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
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course` (
  `crn` int NOT NULL,
  `course_code` varchar(255) DEFAULT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `exam_count` int DEFAULT NULL,
  `program_code` varchar(255) DEFAULT NULL,
  `term` varchar(255) DEFAULT NULL,
  `section_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`crn`, `term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (101,'CS101','Introduction to Computer Science',2,'CS','202302','A'),(102,'MATH202','Calculus II',2,'MATH','202302','B'),(103,'ENG101','English Composition',1,'ENG','202302','A'),(104,'PHYS101','Physics I',2,'PHYS','202302','A'),(105,'CHEM101','Chemistry I',2,'CHEM','202302','B'),(106,'HIST201','World History',1,'HIST','202302','A'),(107,'ECON101','Principles of Economics',1,'ECON','202302','A'),(108,'ART101','Introduction to Art',1,'ART','202302','B'),(109,'PSYC101','Introduction to Psychology',1,'PSYC','202302','A'),(110,'BIOL101','Biology I',2,'BIOL','202302','A');
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courserequirements`
--

DROP TABLE IF EXISTS `courserequirements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courserequirements` (
  `requirement_type` varchar(255) NOT NULL,
  `term` varchar(255) NOT NULL,
  `crn` int NOT NULL,
  PRIMARY KEY (`requirement_type`,`term`,`crn`),
  KEY `crn` (`crn`),
  CONSTRAINT `courserequirements_ibfk_1` FOREIGN KEY (`requirement_type`) REFERENCES `requirement` (`type`),
  CONSTRAINT `courserequirements_ibfk_2` FOREIGN KEY (`crn`) REFERENCES `course` (`crn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courserequirements`
--

LOCK TABLES `courserequirements` WRITE;
/*!40000 ALTER TABLE `courserequirements` DISABLE KEYS */;
INSERT INTO `courserequirements` VALUES ('MUDEK','202302',102),('YÖK','202302',102);
/*!40000 ALTER TABLE `courserequirements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `type` varchar(255) NOT NULL,
  `exam` tinyint(1) DEFAULT NULL,
  `soft` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES ('Attendance',0,1),('Final',1,0),('Final - Average',1,0),('Final - Bottom',1,0),('Final - Top',1,0),('Final Answer Key',1,0),('Final Attendance',0,1),('Final Makeup',1,0),('Final Makeup - Average',1,0),('Final Makeup - Bottom',1,0),('Final Makeup - Top',1,0),('Final Makeup Answer Key',1,0),('Final Makeup Attendance',0,1),('Midterm 1',1,0),('Midterm 1 - Average',1,0),('Midterm 1 - Bottom',1,0),('Midterm 1 - Top',1,0),('Midterm 1 Answer Key',1,0),('Midterm 1 Attendance',0,1),('Midterm 1 Makeup',1,0),('Midterm 1 Makeup - Average',1,0),('Midterm 1 Makeup - Bottom',1,0),('Midterm 1 Makeup - Top',1,0),('Midterm 1 Makeup Answer Key',1,0),('Midterm 1 Makeup Attendance',0,1),('Midterm 2',1,0),('Midterm 2 - Average',1,0),('Midterm 2 - Bottom',1,0),('Midterm 2 - Top',1,0),('Midterm 2 Answer Key',1,0),('Midterm 2 Attendance',0,1),('Midterm 2 Makeup',1,0),('Midterm 2 Makeup - Average',1,0),('Midterm 2 Makeup - Bottom',1,0),('Midterm 2 Makeup - Top',1,0),('Midterm 2 Makeup Answer Key',1,0),('Midterm 2 Makeup Attendance',0,1),('Midterm 3',1,0),('Midterm 3 - Average',1,0),('Midterm 3 - Bottom',1,0),('Midterm 3 - Top',1,0),('Midterm 3 Answer Key',1,0),('Midterm 3 Attendance',0,1),('Midterm_1 Makeup',1,0),('Midterm_1 Makeup - Average',1,0),('Midterm_1 Makeup - Bottom',1,0),('Midterm_1 Makeup - Top',1,0),('Midterm_1 Makeup Answer Key',1,0),('Midterm_1 Makeup Attendance',0,1),('Overall Grade Sheet',0,1),('Quiz 1',1,0),('Quiz 2',1,0),('Syllabus',0,1),('Take Home Exam 1',1,0),('Take Home Exam 2',1,0),('Tutanak',0,1);
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `handles`
--

DROP TABLE IF EXISTS `handles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `handles` (
  `crn` int NOT NULL,
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`crn`,`username`),
  KEY `username` (`username`),
  CONSTRAINT `handles_ibfk_1` FOREIGN KEY (`crn`) REFERENCES `course` (`crn`),
  CONSTRAINT `handles_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `handles`
--

LOCK TABLES `handles` WRITE;
/*!40000 ALTER TABLE `handles` DISABLE KEYS */;
INSERT INTO `handles` VALUES (101,'Onur'),(102,'Onur'),(103,'Onur'),(104,'Onur');
/*!40000 ALTER TABLE `handles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requireddocuments`
--

DROP TABLE IF EXISTS `requireddocuments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requireddocuments` (
  `requirement_type` varchar(255) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  PRIMARY KEY (`requirement_type`,`document_type`),
  KEY `document_type` (`document_type`),
  CONSTRAINT `requireddocuments_ibfk_1` FOREIGN KEY (`requirement_type`) REFERENCES `requirement` (`type`),
  CONSTRAINT `requireddocuments_ibfk_2` FOREIGN KEY (`document_type`) REFERENCES `documents` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requireddocuments`
--

LOCK TABLES `requireddocuments` WRITE;
/*!40000 ALTER TABLE `requireddocuments` DISABLE KEYS */;
INSERT INTO `requireddocuments` VALUES ('YÖK','Attendance'),('YÖK','Final'),('MUDEK','Final - Average'),('MUDEK','Final - Bottom'),('MUDEK','Final - Top'),('YÖK','Final Answer Key'),('YÖK','Final Attendance'),('YÖK','Final Makeup'),('MUDEK','Final Makeup - Average'),('MUDEK','Final Makeup - Bottom'),('MUDEK','Final Makeup - Top'),('YÖK','Final Makeup Answer Key'),('YÖK','Final Makeup Attendance'),('YÖK','Midterm 1'),('MUDEK','Midterm 1 - Average'),('MUDEK','Midterm 1 - Bottom'),('MUDEK','Midterm 1 - Top'),('YÖK','Midterm 1 Answer Key'),('YÖK','Midterm 1 Attendance'),('YÖK','Midterm 1 Makeup'),('MUDEK','Midterm 1 Makeup - Average'),('MUDEK','Midterm 1 Makeup - Bottom'),('MUDEK','Midterm 1 Makeup - Top'),('YÖK','Midterm 1 Makeup Answer Key'),('YÖK','Midterm 1 Makeup Attendance'),('YÖK','Midterm 2'),('MUDEK','Midterm 2 - Average'),('MUDEK','Midterm 2 - Bottom'),('MUDEK','Midterm 2 - Top'),('YÖK','Midterm 2 Answer Key'),('YÖK','Midterm 2 Attendance'),('YÖK','Midterm 2 Makeup'),('MUDEK','Midterm 2 Makeup - Average'),('MUDEK','Midterm 2 Makeup - Bottom'),('MUDEK','Midterm 2 Makeup - Top'),('YÖK','Midterm 2 Makeup Answer Key'),('YÖK','Midterm 2 Makeup Attendance'),('YÖK','Midterm 3'),('MUDEK','Midterm 3 - Average'),('MUDEK','Midterm 3 - Bottom'),('MUDEK','Midterm 3 - Top'),('YÖK','Midterm 3 Answer Key'),('YÖK','Midterm 3 Attendance'),('YÖK','Midterm_1 Makeup'),('MUDEK','Midterm_1 Makeup - Average'),('MUDEK','Midterm_1 Makeup - Bottom'),('MUDEK','Midterm_1 Makeup - Top'),('YÖK','Midterm_1 Makeup Answer Key'),('YÖK','Midterm_1 Makeup Attendance'),('YÖK','Overall Grade Sheet'),('YÖK','Quiz 1'),('YÖK','Quiz 2'),('YÖK','Syllabus'),('YÖK','Take Home Exam 1'),('YÖK','Take Home Exam 2'),('YÖK','Tutanak');
/*!40000 ALTER TABLE `requireddocuments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requirement`
--

DROP TABLE IF EXISTS `requirement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requirement` (
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requirement`
--

LOCK TABLES `requirement` WRITE;
/*!40000 ALTER TABLE `requirement` DISABLE KEYS */;
INSERT INTO `requirement` VALUES ('MUDEK'),('YÖK');
/*!40000 ALTER TABLE `requirement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soft_submit`
--

DROP TABLE IF EXISTS `soft_submit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `soft_submit` (
  `term` varchar(255) NOT NULL,
  `crn` int NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `submitted_prof` tinyint(1) DEFAULT NULL,
  `submitted_arg` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`term`,`crn`,`document_type`),
  KEY `crn` (`crn`),
  KEY `document_type` (`document_type`),
  CONSTRAINT `soft_submit_ibfk_1` FOREIGN KEY (`crn`) REFERENCES `course` (`crn`),
  CONSTRAINT `soft_submit_ibfk_2` FOREIGN KEY (`document_type`) REFERENCES `documents` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soft_submit`
--

LOCK TABLES `soft_submit` WRITE;
/*!40000 ALTER TABLE `soft_submit` DISABLE KEYS */;
INSERT INTO `soft_submit` VALUES ('202302',102,'Attendance',0,1),('202302',102,'Final',0,0),('202302',102,'Final Answer Key',0,0),('202302',102,'Final Attendance',0,0),('202302',102,'Midterm 1',0,0),('202302',102,'Midterm 1 Answer Key',0,0),('202302',102,'Midterm 1 Attendance',0,0),('202302',102,'Overall Grade Sheet',0,1),('202302',102,'Syllabus',0,1),('202302',102,'Tutanak',0,0);
/*!40000 ALTER TABLE `soft_submit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `submit`
--

DROP TABLE IF EXISTS `submit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `submit` (
  `term` varchar(255) NOT NULL,
  `crn` int NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `submitted` tinyint(1) DEFAULT NULL,
  `pdf_data` longblob,
  PRIMARY KEY (`term`,`crn`,`document_type`),
  KEY `crn` (`crn`),
  KEY `document_type` (`document_type`),
  CONSTRAINT `submit_ibfk_1` FOREIGN KEY (`crn`) REFERENCES `course` (`crn`),
  CONSTRAINT `submit_ibfk_2` FOREIGN KEY (`document_type`) REFERENCES `documents` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submit`
--

LOCK TABLES `submit` WRITE;
/*!40000 ALTER TABLE `submit` DISABLE KEYS */;
INSERT INTO `submit` VALUES ('202302',102,'Attendance',0,NULL),('202302',102,'Final',0,NULL),('202302',102,'Final - Average',0,NULL),('202302',102,'Final - Bottom',0,NULL),('202302',102,'Final - Top',0,NULL),('202302',102,'Final Answer Key',0,NULL),('202302',102,'Final Attendance',0,NULL),('202302',102,'Midterm 1',0,NULL),('202302',102,'Midterm 1 - Average',0,NULL),('202302',102,'Midterm 1 - Bottom',0,NULL),('202302',102,'Midterm 1 - Top',0,NULL),('202302',102,'Midterm 1 Answer Key',0,NULL),('202302',102,'Midterm 1 Attendance',0,NULL),('202302',102,'Overall Grade Sheet',0,NULL),('202302',102,'Syllabus',0,NULL),('202302',102,'Tutanak',0,NULL);
/*!40000 ALTER TABLE `submit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teaches`
--

DROP TABLE IF EXISTS `teaches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teaches` (
  `crn` int NOT NULL,
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`crn`,`username`),
  KEY `username` (`username`),
  CONSTRAINT `teaches_ibfk_1` FOREIGN KEY (`crn`) REFERENCES `course` (`crn`),
  CONSTRAINT `teaches_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaches`
--

LOCK TABLES `teaches` WRITE;
/*!40000 ALTER TABLE `teaches` DISABLE KEYS */;
INSERT INTO `teaches` VALUES (102,'Ahmet'),(101,'Canberk'),(102,'Canberk'),(103,'Canberk'),(104,'Canberk'),(105,'Canberk'),(106,'Canberk'),(107,'Doruk'),(108,'Doruk'),(109,'Doruk'),(110,'Doruk');
/*!40000 ALTER TABLE `teaches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `username` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('Ahmet','TA'),('Canberk','professor'),('Doruk','professor'),('Kaan','TA'),('Mehmet','TA'),('Onur','argor');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Canberk','12345'),(2,'Doruk','00000'),(3,'Ahmet','11111'),(4,'Onur','12312');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-04-01 15:00:22
