-- MySQL dump 10.13  Distrib 8.4.7, for Win64 (x86_64)
--
-- Host: localhost    Database: timetable_solver
-- ------------------------------------------------------
-- Server version	8.4.7

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
-- Current Database: `timetable_solver`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `timetable_solver` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `timetable_solver`;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classrooms`
--

DROP TABLE IF EXISTS `classrooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classrooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'General Classroom',
  `capacity` int NOT NULL DEFAULT '45',
  `building` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Main Academic Block',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classrooms`
--

LOCK TABLES `classrooms` WRITE;
/*!40000 ALTER TABLE `classrooms` DISABLE KEYS */;
INSERT INTO `classrooms` VALUES (1,'Room 6A','General Classroom',45,'Academic Block Junior','2026-10-30 15:13:52','2026-10-30 15:13:52'),(2,'Room 6B','General Classroom',45,'Academic Block Junior','2026-10-30 15:13:52','2026-10-30 15:13:52'),(3,'Room 6C','General Classroom',45,'Academic Block Junior','2026-10-30 15:13:52','2026-10-30 15:13:52'),(4,'Room 7A','General Classroom',45,'Academic Block Junior','2026-10-30 15:13:53','2026-10-30 15:13:53'),(5,'Room 7B','General Classroom',45,'Academic Block Junior','2026-10-30 15:13:53','2026-10-30 15:13:53'),(6,'Room 7C','General Classroom',45,'Academic Block Junior','2026-10-30 15:13:53','2026-10-30 15:13:53'),(7,'Room 8A','General Classroom',45,'Academic Block Junior','2026-10-30 15:13:53','2026-10-30 15:13:53'),(8,'Room 8B','General Classroom',45,'Academic Block Junior','2026-10-30 15:13:53','2026-10-30 15:13:53'),(9,'Room 8C','General Classroom',45,'Academic Block Junior','2026-10-30 15:13:53','2026-10-30 15:13:53'),(10,'Room 9A','General Classroom',45,'Academic Block Senior','2026-10-30 15:13:54','2026-10-30 15:13:54'),(11,'Room 9B','General Classroom',45,'Academic Block Senior','2026-10-30 15:13:54','2026-10-30 15:13:54'),(12,'Room 9C','General Classroom',45,'Academic Block Senior','2026-10-30 15:13:54','2026-10-30 15:13:54'),(13,'Room 10A','General Classroom',45,'Academic Block Senior','2026-10-30 15:13:54','2026-10-30 15:13:54'),(14,'Room 10B','General Classroom',45,'Academic Block Senior','2026-10-30 15:13:54','2026-10-30 15:13:54'),(15,'Room 10C','General Classroom',45,'Academic Block Senior','2026-10-30 15:13:54','2026-10-30 15:13:54'),(16,'Room 11A','General Classroom',45,'Science Block','2026-10-30 15:13:54','2026-10-30 17:25:31'),(17,'Room 11B','General Classroom',45,'Science Block','2026-10-30 15:13:54','2026-10-30 17:25:23'),(18,'Room 11C','General Classroom',45,'Science Block','2026-10-30 15:13:54','2026-10-30 17:25:18'),(19,'Room 12A','General Classroom',45,'Science Block','2026-10-30 15:13:54','2026-10-30 17:25:13'),(20,'Room 12B','General Classroom',45,'Science Block','2026-10-30 15:13:54','2026-10-30 17:25:08'),(21,'Room 12C','General Classroom',45,'Science Block','2026-10-30 15:13:54','2026-10-30 17:24:59'),(22,'Computer Lab','Computer Lab',50,'Science Block','2026-10-30 15:13:54','2026-10-30 17:25:43'),(23,'Physics Lab','Physics Lab',45,'Science Wing','2026-10-30 15:13:54','2026-10-30 15:13:54'),(24,'Chemistry Lab','Chemistry Lab',45,'Science Wing','2026-10-30 15:13:54','2026-10-30 15:13:54'),(25,'Biology Lab','Biology Lab',45,'Science Block','2026-10-30 15:13:54','2026-10-30 17:25:55'),(26,'Fine Arts Studio','Fine Arts Studio',40,'Science Block','2026-10-30 15:13:54','2026-10-30 17:25:48'),(27,'Central Library','Central Library',40,'Science Block','2026-10-30 15:13:54','2026-10-30 17:24:16'),(31,'MPR','Multi-Purpose Room',150,'Science Block',NULL,'2026-10-30 18:52:08');
/*!40000 ALTER TABLE `classrooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `duty_assignments`
--

DROP TABLE IF EXISTS `duty_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `duty_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `duty_slot_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `duty_assignments_duty_slot_id_foreign` (`duty_slot_id`),
  KEY `duty_assignments_teacher_id_foreign` (`teacher_id`)
) ENGINE=MyISAM AUTO_INCREMENT=337 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `duty_assignments`
--

LOCK TABLES `duty_assignments` WRITE;
/*!40000 ALTER TABLE `duty_assignments` DISABLE KEYS */;
INSERT INTO `duty_assignments` VALUES (289,14,1,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(290,14,2,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(291,13,3,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(292,13,4,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(293,13,5,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(294,15,6,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(295,15,7,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(296,15,8,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(297,2,9,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(298,2,10,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(299,1,11,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(300,1,12,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(301,1,13,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(302,3,14,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(303,3,15,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(304,3,16,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(305,17,17,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(306,17,18,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(307,16,19,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(308,16,20,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(309,16,21,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(310,18,22,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(311,18,23,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(312,18,24,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(313,11,25,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(314,11,26,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(315,10,27,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(316,10,28,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(317,10,29,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(318,12,30,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(319,12,31,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(320,12,32,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(321,5,33,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(322,5,34,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(323,4,35,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(324,4,36,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(325,4,37,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(326,6,38,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(327,6,39,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(328,6,41,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(329,8,42,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(330,8,1,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(331,7,2,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(332,7,3,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(333,7,4,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(334,9,43,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(335,9,5,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(336,9,6,'2026-10-31 00:29:18','2026-10-31 00:29:18');
/*!40000 ALTER TABLE `duty_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `duty_slots`
--

DROP TABLE IF EXISTS `duty_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `duty_slots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `duty_type_id` bigint unsigned NOT NULL,
  `day` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period` int NOT NULL DEFAULT '0',
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Campus Courtyard',
  `required_teachers` int NOT NULL DEFAULT '2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `duty_slots_duty_type_id_foreign` (`duty_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `duty_slots`
--

LOCK TABLES `duty_slots` WRITE;
/*!40000 ALTER TABLE `duty_slots` DISABLE KEYS */;
INSERT INTO `duty_slots` VALUES (1,1,'Monday',0,'Central Assembly Ground',3,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(2,2,'Monday',-1,'Main Entrance Gate',2,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(3,3,'Monday',99,'Corridors & Cafeteria',3,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(4,1,'Tuesday',0,'Central Assembly Ground',3,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(5,2,'Tuesday',-1,'Main Entrance Gate',2,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(6,3,'Tuesday',99,'Corridors & Cafeteria',3,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(7,1,'Wednesday',0,'Central Assembly Ground',3,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(8,2,'Wednesday',-1,'Main Entrance Gate',2,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(9,3,'Wednesday',99,'Corridors & Cafeteria',3,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(10,1,'Thursday',0,'Central Assembly Ground',3,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(11,2,'Thursday',-1,'Main Entrance Gate',2,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(12,3,'Thursday',99,'Corridors & Cafeteria',3,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(13,1,'Friday',0,'Central Assembly Ground',3,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(14,2,'Friday',-1,'Main Entrance Gate',2,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(15,3,'Friday',99,'Corridors & Cafeteria',3,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(16,1,'Saturday',0,'Central Assembly Ground',3,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(17,2,'Saturday',-1,'Main Entrance Gate',2,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(18,3,'Saturday',99,'Corridors & Cafeteria',3,'2026-10-30 15:13:56','2026-10-30 15:13:56');
/*!40000 ALTER TABLE `duty_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `duty_types`
--

DROP TABLE IF EXISTS `duty_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `duty_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#6c757d',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `duty_types`
--

LOCK TABLES `duty_types` WRITE;
/*!40000 ALTER TABLE `duty_types` DISABLE KEYS */;
INSERT INTO `duty_types` VALUES (1,'Morning Assembly Supervision','Discipline and student queue monitoring at general assembly','#3b82f6','2026-10-30 15:13:56','2026-10-30 15:13:56'),(2,'Main Gate & Perimeter Duty','Morning arrival and afternoon departure gate attendance','#10b981','2026-10-30 15:13:56','2026-10-30 15:13:56'),(3,'Recess Corridor Monitoring','Monitoring cafeteria, ground, and corridors during break','#f59e0b','2026-10-30 15:13:56','2026-10-30 15:13:56'),(4,'Exam Hall Invigilation','Periodic test invigilation and assessment proctoring','#8b5cf6','2026-10-30 15:13:56','2026-10-30 15:13:56');
/*!40000 ALTER TABLE `duty_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grade_classes`
--

DROP TABLE IF EXISTS `grade_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grade_classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_num` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grade_classes`
--

LOCK TABLES `grade_classes` WRITE;
/*!40000 ALTER TABLE `grade_classes` DISABLE KEYS */;
INSERT INTO `grade_classes` VALUES (1,'Class 6',6,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(2,'Class 7',7,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(3,'Class 8',8,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(4,'Class 9',9,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(5,'Class 10',10,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(6,'Class 11',11,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(7,'Class 12',12,'2026-10-30 15:13:55','2026-10-30 15:13:55');
/*!40000 ALTER TABLE `grade_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `institution_settings`
--

DROP TABLE IF EXISTS `institution_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `institution_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Government Higher Secondary School & College',
  `academic_year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2026-2027',
  `term` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Annual Term',
  `motto` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'Excellence in Education & Character',
  `working_days` json DEFAULT NULL,
  `periods_per_day` int NOT NULL DEFAULT '7',
  `friday_periods` int NOT NULL DEFAULT '4',
  `saturday_periods` int NOT NULL DEFAULT '6',
  `day_periods` json DEFAULT NULL,
  `break_after_period` int NOT NULL DEFAULT '4',
  `break_duration_mins` int NOT NULL DEFAULT '30',
  `period_duration_mins` int NOT NULL DEFAULT '45',
  `start_hour` int NOT NULL DEFAULT '8',
  `start_min` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `institution_settings`
--

LOCK TABLES `institution_settings` WRITE;
/*!40000 ALTER TABLE `institution_settings` DISABLE KEYS */;
INSERT INTO `institution_settings` VALUES (1,'Daanish School Boys, Rahim Yar Khan','2026-2027','Annual Term','Excellence in Education, Character & Leadership','[\"Monday\", \"Tuesday\", \"Wednesday\", \"Thursday\", \"Friday\", \"Saturday\"]',8,6,8,'{\"Friday\": 6, \"Monday\": 8, \"Tuesday\": 8, \"Saturday\": 8, \"Thursday\": 8, \"Wednesday\": 8}',4,30,40,8,0,'2026-10-30 15:13:50','2026-10-30 17:27:31');
/*!40000 ALTER TABLE `institution_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_allocations`
--

DROP TABLE IF EXISTS `lesson_allocations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lesson_allocations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `room_id` bigint unsigned DEFAULT NULL,
  `periods_per_week` int NOT NULL DEFAULT '4',
  `block_size` int NOT NULL DEFAULT '1',
  `is_combined` tinyint(1) NOT NULL DEFAULT '0',
  `combined_group_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sync_group_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_allocations_section_id_foreign` (`section_id`),
  KEY `lesson_allocations_subject_id_foreign` (`subject_id`),
  KEY `lesson_allocations_teacher_id_foreign` (`teacher_id`),
  KEY `lesson_allocations_room_id_foreign` (`room_id`),
  KEY `lesson_allocations_sync_group_id_index` (`sync_group_id`),
  KEY `lesson_allocations_combined_group_id_index` (`combined_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=218 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_allocations`
--

LOCK TABLES `lesson_allocations` WRITE;
/*!40000 ALTER TABLE `lesson_allocations` DISABLE KEYS */;
INSERT INTO `lesson_allocations` VALUES (1,1,1,1,1,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(2,1,2,6,1,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(3,1,3,11,1,5,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(4,1,8,34,1,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(5,1,9,37,1,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(6,1,7,31,1,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 17:11:35'),(7,1,12,32,22,2,2,0,NULL,'SYNC_MID_6_PARALLEL','2026-10-30 15:13:55','2026-10-30 18:07:38'),(8,1,10,41,26,3,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(9,1,11,42,27,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(10,1,4,17,1,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(11,2,1,2,2,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(12,2,2,7,2,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(13,2,3,12,2,5,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(14,2,8,35,2,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(15,2,9,38,2,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(16,2,7,33,2,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(17,2,7,31,22,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 17:11:35'),(18,2,7,43,26,3,2,0,NULL,'SYNC_MID_6_PARALLEL','2026-10-30 15:13:55','2026-10-30 18:07:47'),(19,2,11,42,27,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(20,2,4,18,2,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(21,3,1,3,3,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(22,3,2,8,3,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(23,3,3,13,3,5,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(24,3,8,36,3,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(25,3,9,39,3,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(26,3,12,32,3,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 18:07:38'),(27,3,7,33,22,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(28,3,10,41,26,3,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(29,3,11,42,27,2,2,0,NULL,'SYNC_MID_6_PARALLEL','2026-10-30 15:13:55','2026-10-30 15:13:55'),(30,3,4,19,3,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(31,4,1,4,4,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 17:12:24'),(32,4,2,9,4,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(33,4,3,14,4,5,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(34,4,8,34,4,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(36,4,7,31,4,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 17:11:35'),(37,4,12,32,22,2,2,0,NULL,'SYNC_MID_7_PARALLEL','2026-10-30 15:13:55','2026-10-30 18:07:38'),(38,4,7,43,26,3,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 18:07:47'),(39,4,11,42,27,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(40,4,5,22,4,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(41,5,1,5,5,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(42,5,2,10,5,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(43,5,3,15,5,5,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 17:12:42'),(44,5,8,35,5,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(45,5,9,37,5,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(46,5,7,33,5,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(47,5,7,31,22,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 17:11:35'),(48,5,10,41,26,3,2,0,NULL,'SYNC_MID_7_PARALLEL','2026-10-30 15:13:55','2026-10-30 15:13:55'),(49,5,11,42,27,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(50,5,5,23,5,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(51,6,1,1,6,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(52,6,2,6,6,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(53,6,3,16,6,5,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(54,6,8,36,6,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(55,6,9,38,6,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(56,6,12,32,6,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 18:07:38'),(57,6,7,33,22,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(58,6,7,43,26,3,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 18:07:47'),(59,6,11,42,27,2,2,0,NULL,'SYNC_MID_7_PARALLEL','2026-10-30 15:13:55','2026-10-30 15:13:55'),(60,6,14,24,6,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 18:06:21'),(61,7,1,2,7,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(62,7,2,7,7,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(63,7,3,11,7,5,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(64,7,8,34,7,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(65,7,9,39,7,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(66,7,7,31,7,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 17:11:35'),(67,7,12,32,31,1,1,1,'COMBINED_CLASS_3_SUB_12','COMBINED_CLASS_3_SUB_12','2026-10-30 15:13:55','2026-10-31 00:22:57'),(68,7,10,41,26,3,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(69,7,11,42,27,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(70,7,6,27,7,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(71,8,1,3,8,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(72,8,2,8,8,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(73,8,3,12,8,5,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(74,8,8,35,8,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(76,8,7,33,8,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(77,8,7,31,22,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 17:11:35'),(78,8,7,43,26,3,2,0,NULL,'SYNC_MID_8_PARALLEL','2026-10-30 15:13:55','2026-10-30 18:07:47'),(79,8,11,42,27,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(80,8,6,28,8,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(81,9,1,4,9,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 17:12:24'),(82,9,2,9,9,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(83,9,3,13,9,5,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(84,9,8,36,9,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(85,9,9,37,9,3,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(86,9,12,32,31,1,1,1,'COMBINED_CLASS_3_SUB_12','COMBINED_CLASS_3_SUB_12','2026-10-30 15:13:55','2026-10-31 00:22:57'),(87,9,7,33,22,2,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(88,9,10,41,26,3,2,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(89,9,11,42,27,2,2,0,NULL,'SYNC_MID_8_PARALLEL','2026-10-30 15:13:55','2026-10-30 15:13:55'),(90,9,6,29,9,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(91,10,1,5,10,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(92,10,2,10,10,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(93,10,3,14,10,4,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(94,10,4,20,10,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(95,10,4,21,23,2,2,0,NULL,'SYNC_9_SCIENCE_LAB','2026-10-30 15:13:55','2026-10-30 15:13:55'),(96,10,5,25,10,2,1,0,NULL,NULL,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(97,10,5,26,24,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(98,10,6,30,10,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(99,10,6,27,25,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(100,10,9,38,10,6,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(101,11,1,1,11,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(102,11,2,6,11,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(103,11,3,15,11,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 17:12:42'),(104,11,4,17,11,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(105,11,4,18,23,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(106,11,5,22,11,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(107,11,5,23,24,2,2,0,NULL,'SYNC_9_SCIENCE_LAB','2026-10-30 15:13:56','2026-10-30 15:13:56'),(108,11,6,28,11,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(109,11,6,29,25,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(110,11,9,39,11,6,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(111,12,1,2,12,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(112,12,2,7,12,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(113,12,3,16,12,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(114,12,4,19,12,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(115,12,4,20,23,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(116,12,14,24,12,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 18:06:21'),(117,12,5,25,24,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(118,12,6,30,12,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(119,12,6,27,25,2,2,0,NULL,'SYNC_9_SCIENCE_LAB','2026-10-30 15:13:56','2026-10-30 15:13:56'),(121,13,1,3,13,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(122,13,2,8,13,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(123,13,3,11,13,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(124,13,4,21,13,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(125,13,4,17,23,2,2,0,NULL,'SYNC_10_SCIENCE_LAB','2026-10-30 15:13:56','2026-10-30 15:13:56'),(126,13,5,26,13,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(127,13,5,22,24,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(128,13,6,28,13,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(129,13,6,29,25,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(130,13,9,37,13,6,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(131,14,1,4,14,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 17:12:24'),(132,14,2,9,14,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(133,14,3,12,14,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(134,14,4,18,14,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(135,14,4,19,23,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(136,14,5,23,14,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(137,14,14,24,24,2,2,0,NULL,'SYNC_10_SCIENCE_LAB','2026-10-30 15:13:56','2026-10-30 18:06:21'),(138,14,6,30,14,2,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(139,14,6,27,25,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(140,14,9,38,14,6,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(141,15,1,5,15,6,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 17:49:27'),(142,15,2,10,15,6,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 17:52:10'),(143,15,3,13,15,6,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 17:52:05'),(144,15,4,20,15,6,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 17:52:28'),(146,15,5,25,15,6,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 17:51:34'),(147,15,5,26,24,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 17:58:42'),(212,15,4,21,23,2,2,0,NULL,NULL,'2026-10-30 17:51:19','2026-10-30 17:58:47'),(215,15,11,42,27,1,1,0,NULL,NULL,'2026-10-30 17:55:46','2026-10-30 17:55:46'),(214,15,7,33,22,1,1,0,NULL,NULL,'2026-10-30 17:54:51','2026-10-30 17:54:51'),(150,15,13,39,15,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 17:49:52'),(151,16,1,1,16,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(152,16,2,6,16,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(153,16,3,14,16,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(154,16,4,17,16,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(155,16,4,18,23,2,2,0,NULL,'SYNC_11_SCIENCE_LAB','2026-10-30 15:13:56','2026-10-30 15:13:56'),(156,16,5,22,16,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(157,16,5,23,24,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(158,16,6,30,16,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(159,16,6,27,25,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(161,17,1,2,17,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(162,17,2,7,17,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(163,17,3,15,17,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 17:12:42'),(164,17,4,19,17,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(165,17,4,20,23,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(166,17,14,24,17,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 18:06:21'),(167,17,5,25,24,2,2,0,NULL,'SYNC_11_SCIENCE_LAB','2026-10-30 15:13:56','2026-10-30 15:13:56'),(168,17,6,28,17,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(169,17,6,29,25,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(170,17,9,37,17,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(171,18,1,3,18,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(172,18,2,8,18,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(173,18,3,16,18,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(174,18,4,21,18,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(175,18,4,17,23,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(176,18,5,26,18,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(177,18,5,22,24,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(178,18,6,30,18,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(179,18,6,27,25,2,2,0,NULL,'SYNC_11_SCIENCE_LAB','2026-10-30 15:13:56','2026-10-30 15:13:56'),(180,18,9,38,18,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(181,19,1,4,19,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 17:12:24'),(182,19,2,9,19,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(183,19,3,11,19,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(184,19,4,18,19,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(185,19,4,19,23,2,2,0,NULL,'SYNC_12_SCIENCE_LAB','2026-10-30 15:13:56','2026-10-30 15:13:56'),(186,19,5,23,19,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(187,19,14,24,24,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 18:06:21'),(188,19,6,28,19,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(189,19,6,29,25,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(190,19,9,39,19,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(191,20,1,5,20,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(192,20,2,10,20,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(193,20,3,12,20,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(194,20,4,20,20,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(195,20,4,21,23,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(196,20,5,25,20,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(197,20,5,26,24,2,2,0,NULL,'SYNC_12_SCIENCE_LAB','2026-10-30 15:13:56','2026-10-30 15:13:56'),(198,20,6,30,20,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(199,20,6,27,25,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(201,21,1,1,21,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(202,21,2,6,21,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(203,21,3,13,21,4,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(204,21,4,17,21,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(205,21,4,18,23,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(206,21,5,22,21,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(207,21,5,23,24,2,2,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(208,21,6,28,21,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(209,21,6,29,25,2,2,0,NULL,'SYNC_12_SCIENCE_LAB','2026-10-30 15:13:56','2026-10-30 15:13:56'),(210,21,9,37,21,3,1,0,NULL,NULL,'2026-10-30 15:13:56','2026-10-30 15:13:56'),(211,15,7,43,NULL,3,1,0,NULL,NULL,'2026-10-30 17:49:13','2026-10-30 18:29:19'),(216,15,12,32,31,1,1,0,NULL,NULL,'2026-10-30 17:56:47','2026-10-30 18:07:38'),(217,8,12,32,31,1,1,1,'COMBINED_CLASS_3_SUB_12','COMBINED_CLASS_3_SUB_12','2026-10-31 00:22:57','2026-10-31 00:22:57');
/*!40000 ALTER TABLE `lesson_allocations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_31_000001_create_timetable_system_tables',1),(5,'2026_10_31_000002_add_saturday_periods_to_institution_settings',1),(6,'2026_10_31_000003_make_room_id_nullable_in_timetable_slots',2),(7,'2026_10_31_000004_add_combined_session_to_allocations_and_slots',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `grade_class_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_count` int NOT NULL DEFAULT '35',
  `home_room_id` bigint unsigned DEFAULT NULL,
  `class_teacher_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sections_grade_class_id_foreign` (`grade_class_id`),
  KEY `sections_home_room_id_foreign` (`home_room_id`),
  KEY `sections_class_teacher_id_foreign` (`class_teacher_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,1,'6A',38,1,1,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(2,1,'6B',35,2,6,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(3,1,'6C',36,3,11,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(4,2,'7A',34,4,17,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(5,2,'7B',39,5,22,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(6,2,'7C',37,6,27,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(7,3,'8A',34,7,6,'2026-10-30 15:13:55','2026-10-30 18:08:17'),(8,3,'8B',39,8,34,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(9,3,'8C',38,9,37,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(10,4,'9A',37,10,2,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(11,4,'9B',36,11,7,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(12,4,'9C',37,12,12,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(13,5,'10A',34,13,18,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(14,5,'10B',40,14,23,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(15,5,'10C',36,15,28,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(16,6,'11A',37,16,32,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(17,6,'11B',40,17,35,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(18,6,'11C',32,18,38,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(19,7,'12A',39,19,3,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(20,7,'12B',35,20,8,'2026-10-30 15:13:55','2026-10-30 15:13:55'),(21,7,'12C',39,21,13,'2026-10-30 15:13:55','2026-10-30 15:13:55');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('R8Ft0xKuvhzcmcPkUEYWxajlqS7eIP1jcoHA61c9',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJRTnpCZVN1UTl6aGd2SG9hVnNCWHZ5REVhQWRoRXpDcFNicTBHczFVIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljIiwicm91dGUiOiJkYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1793399759),('6ACU8JLcDVWmfO6votxLO1TPdtzjJQIBAaJrnD1H',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJGd1p6UHNJY3AwZHA5U3lPN0RnMm15aUhjdUQyZVpRb3VacXBRZEpKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljXC90aW1ldGFibGUiLCJyb3V0ZSI6InRpbWV0YWJsZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1793399759),('7vU1GoFqZAWwXKw0bU0NcadYFpUC16aYzvPeH6lv',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJHeUN0SnZmNHBDWk56aGNZam0wWHVqV0FsVFU2ZGliWkxrWkI4WklDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljXC9hbGxvdG1lbnRzIiwicm91dGUiOiJhbGxvdG1lbnRzIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1793399760),('kMGlAeBElt25ywP9CYs3kSJTgbdwRMB7wp7QV9bf',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJxUDRqRENXS1Q1ZENON1VVS25Lb2d1YzlTQzlEZVBoQzBtR3VnZHJhIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljXC9mYWN1bHR5Iiwicm91dGUiOiJmYWN1bHR5In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1793399760),('e7hXlg7JFURe4f5d4KsAATK5u5yVgMMQwYsmUckf',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJLdkc0cE1kT1NIR21SZmwwNzYzM3d5VnNmOTlHRDdVWDFzOVJRWTQ0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljXC9jbGFzc2VzIiwicm91dGUiOiJjbGFzc2VzIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1793399761),('T0HYsl694AgsyUwrPWEhZBI3gJiI0rNTnWgfOJ33',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiIyWWFYbXFPTzQ3aVE0amFIZ0R3MTdJVnVOc2ZxSHFiclh5aVlrcFBVIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljXC9zdWJqZWN0cyIsInJvdXRlIjoic3ViamVjdHMifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1793399761),('hMvt7CZbq9cSSII5UYbFmdEtESQlKyzkPxiKblVS',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiI1eW1waVNuSklrOWtNdDFLVjJHUWpZMXluejFVM2hyMlZSczk0QlA2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljXC9yb29tcyIsInJvdXRlIjoicm9vbXMifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1793399761),('74niosoP1BPNWa3DZp8OkEMYIJXNoLtqcWlfh0lU',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJncFEyZ1E5UW5ORFZCWGJmU2NoRGxLcDVZaFJ4TUwxbW0zSmNndHhyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljXC9pbnN0aXR1dGlvbiIsInJvdXRlIjoiaW5zdGl0dXRpb24ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1793399761),('F47itVqRMszWUCGXBz8r1OGIgrtekmcIJgO3Jdro',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJTZTlXd09wVkxNMThEOXNMTG5HekdvR1BHSXkycU4xd1lKbUZFRmRuIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljXC9kdXRpZXMiLCJyb3V0ZSI6ImR1dGllcyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1793399762),('ZuvdcJWUVXboXJQJoIPGlhFNbkB4xoIk2nZtrBmY',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJzSFpkUWJQVWd6VWtKbzJybk1YdGVQQ1dVdVVCcVJVaWliUHpRWXNoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljXC9leHBvcnRzIiwicm91dGUiOiJleHBvcnRzIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1793399762),('VwtcuRbixXP8o6CjxhuN19pOFnj9vlGKHwi4VHsT',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0','eyJfdG9rZW4iOiJZckY4d3NiWk1oSW83SWVrb1NIT0RPQ054ZlhkSTg1SW1ySUxzV2FvIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC90aW1ldGFibGU/aWQ9MTQmbW9kZT1zZWN0aW9uIiwicm91dGUiOiJ0aW1ldGFibGUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1793403424),('vPs7Jz1OmxHW3EUgAGIdGaPsIk64Rff9zNfc54XR',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJqUHppNW5rck41ZXRZU1BvSTF3b1VjY1hTdWpZSVp3Z1d6dmYxVENZIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1793401374),('UZRsFGd6WMDkG65AdoeVpMgmX35yudRJNFWcHcow',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0','eyJfdG9rZW4iOiJ0VHN1YkN6ekFNUnZGMks4dWVTbXRNZTI4SUpiem0wRk9rTXR6Z3VkIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljIiwicm91dGUiOiJkYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1793401413),('j4JFrlRC1AGrqKYAcfl8cMzuOVok0fEsvUoDXuB8',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/156.0.0.0 Safari/537.36 Edg/156.0.0.0','eyJfdG9rZW4iOiJaVWY2UVJIZ1Fsd2p1a2R0cWJJMndVN3RSaTZhUGVJOHRDcGxtZXl6IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvVGltZVRhYmxlLVNvbHZlclwvcHVibGljXC90aW1ldGFibGUiLCJyb3V0ZSI6InRpbWV0YWJsZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1793401417),('77PhroFTrq9sK9xdQNetro0QX5f3XFtPG00Rc9YB',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJuUUp1ejVmSXgzZkhjbUN0U250NEJmRG40dzF6enNvcTdUeHJ6UmFSIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1793402929),('66KAi3jLFm8sRNTJFVEm0usiucrxexm6cf9Q6rLT',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJVUGF5NmozQ0JvRUZvVGJ0c3ZLTW93RnBFWERxbmZUVWU1d1ZPaFZOIiwic3VjY2VzcyI6Ikxlc3NvbiBhbGxvY2F0aW9uIGFuZCBsaW5rZWQgdGltZXRhYmxlIHNsb3RzIHVwZGF0ZWQgc3VjY2Vzc2Z1bGx5LiIsIl9mbGFzaCI6eyJuZXciOltdLCJvbGQiOlsic3VjY2VzcyJdfX0=',1793402959);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Theory',
  `requires_lab` tinyint(1) NOT NULL DEFAULT '0',
  `default_room_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'General Classroom',
  `color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3498db',
  `is_difficult` tinyint(1) NOT NULL DEFAULT '0',
  `allowed_double_period` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subjects_code_index` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,'English Language & Comp','ENG','Theory',0,'General Classroom','#2563eb',0,0,'2026-10-30 15:13:50','2026-10-30 15:13:50'),(2,'Urdu Literature','URDU','Theory',0,'General Classroom','#059669',0,0,'2026-10-30 15:13:50','2026-10-30 15:13:50'),(3,'Mathematics','MATH','Theory',0,'General Classroom','#7c3aed',0,0,'2026-10-30 15:13:50','2026-10-30 17:22:15'),(4,'Physics','PHY','Theory',0,'Physics Lab','#d97706',0,0,'2026-10-30 15:13:51','2026-10-30 17:21:29'),(5,'Chemistry','CHEM','Theory',0,'Chemistry Lab','#db2777',0,0,'2026-10-30 15:13:51','2026-10-30 17:22:08'),(6,'Biology','BIO','Theory',0,'Biology Lab','#0891b2',0,0,'2026-10-30 15:13:51','2026-10-30 17:23:01'),(7,'Computer Science','CS','Theory',0,'Computer Lab','#4f46e5',0,0,'2026-10-30 15:13:51','2026-10-30 17:21:11'),(8,'Social Studies / Pak Studies','SST','Theory',0,'General Classroom','#0d9488',0,0,'2026-10-30 15:13:51','2026-10-30 15:13:51'),(9,'Islamic Studies & Quran','ISL','Theory',0,'General Classroom','#65a30d',0,0,'2026-10-30 15:13:51','2026-10-30 15:13:51'),(10,'Fine Arts & Drawing','ART','Theory',0,'Fine Arts Studio','#ea580c',0,0,'2026-10-30 15:13:51','2026-10-30 17:21:59'),(11,'Central Library & Reading','LIB','Theory',0,'Central Library','#475569',0,0,'2026-10-30 15:13:51','2026-10-30 15:13:51'),(12,'Counseling Session','COUN-SE','Theory',0,'HALL','#2563eb',0,0,'2026-10-30 17:22:51','2026-10-30 18:09:11'),(13,'Tarjuma Tul Quran','TQ','Theory',0,'General Classroom','#2563eb',0,0,'2026-10-30 17:23:35','2026-10-30 17:23:35'),(14,'Genral Science','GS','Theory',0,'General Classroom','#2563eb',0,0,'2026-10-30 18:05:55','2026-10-30 18:05:55');
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teachers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_subject_id` bigint unsigned DEFAULT NULL,
  `qualified_subjects` json DEFAULT NULL,
  `max_periods_per_week` int NOT NULL DEFAULT '24',
  `max_periods_per_day` int NOT NULL DEFAULT '5',
  `unavailable_slots` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teachers_code_unique` (`code`),
  KEY `teachers_primary_subject_id_foreign` (`primary_subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
INSERT INTO `teachers` VALUES (1,'Rizwan Ahmed','RIZ','riz@school.edu.pk','+92-300-5816095',1,'[1]',24,5,'[[\"Monday\", 1], [\"Tuesday\", 2]]','2026-10-30 15:13:54','2026-10-30 15:21:43'),(2,'Junaid Tariq','JUN','jun@school.edu.pk','+92-300-6690190',1,'[1]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(3,'Barkaat Ali','BAR','bar@school.edu.pk','+92-300-2134880',1,'[1]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(4,'Muhammad Afzal','AFZ','afz@school.edu.pk','+92-300-1315209',1,'[1]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 17:12:24'),(5,'Yasir Mehmood','YAS','yas@school.edu.pk','+92-300-6748148',1,'[1]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(6,'Kamran Shah','KAM','kam@school.edu.pk','+92-300-4175839',2,'[2]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(7,'Faisal Iqbal','FI','fi@school.edu.pk','+92-300-5484196',2,'[2]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(8,'Shahzad Aslam','SHZ','shz@school.edu.pk','+92-300-9741754',2,'[2]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(9,'Ismail Farooq','ISM','ism@school.edu.pk','+92-300-1259847',2,'[2]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(10,'Fayaz Hussain','FAY','fay@school.edu.pk','+92-300-2069810',2,'[2]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(11,'Faizan Haider','FAZ','faz@school.edu.pk','+92-300-6881561',3,'[3]',24,5,'[[\"Wednesday\", 7]]','2026-10-30 15:13:54','2026-10-30 15:13:55'),(12,'Abdul Rauf','ARF','arf@school.edu.pk','+92-300-7625932',3,'[3]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(13,'Mohsin Raza','MOH','moh@school.edu.pk','+92-300-4955920',3,'[3]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(14,'Zawar Shah','ZAW','zaw@school.edu.pk','+92-300-9001585',3,'[3]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(15,'Muhammad Ajmal','AJM','ajm@school.edu.pk','+92-300-6891867',3,'[3]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 17:12:42'),(16,'Danish Munir','DAN.M','danm@school.edu.pk','+92-300-3868154',3,'[3]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(17,'Sajid Mehmood','SAJ','saj@school.edu.pk','+92-300-3722119',4,'[4]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(18,'Syed Zaman','SZ','sz@school.edu.pk','+92-300-9354194',4,'[4]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(19,'Irshad Ullah','IRSH','irsh@school.edu.pk','+92-300-5313318',4,'[4]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(20,'Danish Siddiqui','DS','ds@school.edu.pk','+92-300-3656214',4,'[4]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(21,'Zulqurnain Haider','ZUL','zul@school.edu.pk','+92-300-8547675',4,'[4]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(22,'Bilal Hassan','BH','bh@school.edu.pk','+92-300-5881848',5,'[5]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(23,'Abdul Rehman Chem','ARC','arc@school.edu.pk','+92-300-7779403',5,'[5]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(24,'Ishaq Dar','ISH','ish@school.edu.pk','+92-300-9242374',14,'[14]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 18:06:21'),(25,'Usama Qasim','USA','usa@school.edu.pk','+92-300-5565698',5,'[5]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(26,'Mudasar Nawaz','MUD','mud@school.edu.pk','+92-300-5930932',5,'[5]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(27,'Noman Bashir Bio','NOM.B','nomb@school.edu.pk','+92-300-1649738',6,'[6]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(28,'Nazneen Akhtar','NAZ','naz@school.edu.pk','+92-300-9767317',6,'[6]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(29,'Bilal Chaudhry','BC','bc@school.edu.pk','+92-300-3830299',6,'[6]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(30,'Umar Farooq Bio','UMR','umr@school.edu.pk','+92-300-3809416',6,'[6]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(31,'Adnan Altaf','ADN','adn@school.edu.pk','+92-300-7433626',7,'[7]',24,5,'[[\"Friday\", 7], [\"Friday\", 8], [\"Monday\", 1], [\"Tuesday\", 1], [\"Wednesday\", 1], [\"Thursday\", 1], [\"Friday\", 1], [\"Saturday\", 1], [\"Monday\", 8], [\"Thursday\", 8], [\"Saturday\", 8]]','2026-10-30 15:13:54','2026-10-30 17:12:07'),(32,'Shoaib Rashid','SR','sr@school.edu.pk','+92-300-1372815',12,'[12]',7,5,'[]','2026-10-30 15:13:54','2026-10-30 18:07:38'),(33,'Noman IT Specialist','NOM.IT','nomit@school.edu.pk','+92-300-1490986',7,'[7]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(34,'Zaheer Abbas','ZAH','zah@school.edu.pk','+92-300-7793911',8,'[8]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(35,'Akram Gill','AKR','akr@school.edu.pk','+92-300-4436478',8,'[8]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(36,'Abdul Rehman SST','ARS','ars@school.edu.pk','+92-300-4581063',8,'[8]',24,5,'[]','2026-10-30 15:13:54','2026-10-30 15:13:54'),(37,'Fazal Gulzar','FG','fg@school.edu.pk','+92-300-8421595',9,'[9]',24,5,'[]','2026-10-30 15:13:55','2026-10-30 15:13:55'),(38,'Imran Hussain','IH','ih@school.edu.pk','+92-300-3786898',9,'[9]',24,5,'[]','2026-10-30 15:13:55','2026-10-30 15:13:55'),(39,'Zakirullah Khan','ZAK','zak@school.edu.pk','+92-300-2386629',9,'[9]',24,5,'[]','2026-10-30 15:13:55','2026-10-30 15:13:55'),(41,'Naveed Akhtar','NAV','nav@school.edu.pk','+92-300-5843014',10,'[10]',24,5,'[]','2026-10-30 15:13:55','2026-10-30 15:13:55'),(42,'Saleem Yousaf','SAL','sal@school.edu.pk','+92-300-4501252',11,'[11]',24,5,'[]','2026-10-30 15:13:55','2026-10-30 15:13:55'),(43,'Aamir Ali','AA','aa@school.edu.pk','+92-300-5054289',7,'[7]',12,5,'[[\"Monday\", 1], [\"Tuesday\", 1], [\"Wednesday\", 1], [\"Thursday\", 1], [\"Friday\", 1], [\"Saturday\", 1], [\"Monday\", 6], [\"Monday\", 7], [\"Tuesday\", 6], [\"Tuesday\", 7], [\"Wednesday\", 6], [\"Wednesday\", 7], [\"Thursday\", 6], [\"Thursday\", 7], [\"Friday\", 6], [\"Friday\", 7], [\"Saturday\", 6], [\"Saturday\", 7], [\"Monday\", 8], [\"Tuesday\", 8], [\"Wednesday\", 8], [\"Thursday\", 8], [\"Saturday\", 8]]','2026-10-30 15:13:55','2026-10-30 18:00:11');
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetable_slots`
--

DROP TABLE IF EXISTS `timetable_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `timetable_slots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lesson_allocation_id` bigint unsigned DEFAULT NULL,
  `section_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `room_id` bigint unsigned DEFAULT NULL,
  `day` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period` int NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `is_combined` tinyint(1) NOT NULL DEFAULT '0',
  `combined_group_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `timetable_slots_lesson_allocation_id_foreign` (`lesson_allocation_id`),
  KEY `timetable_slots_subject_id_foreign` (`subject_id`),
  KEY `timetable_slots_day_period_index` (`day`,`period`),
  KEY `timetable_slots_teacher_id_day_period_index` (`teacher_id`,`day`,`period`),
  KEY `timetable_slots_section_id_day_period_index` (`section_id`,`day`,`period`),
  KEY `timetable_slots_room_id_day_period_index` (`room_id`,`day`,`period`),
  KEY `timetable_slots_combined_group_id_index` (`combined_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7529 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetable_slots`
--

LOCK TABLES `timetable_slots` WRITE;
/*!40000 ALTER TABLE `timetable_slots` DISABLE KEYS */;
INSERT INTO `timetable_slots` VALUES (7516,202,21,6,2,21,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7514,203,21,13,3,21,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7515,203,21,13,3,21,'Thursday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7513,203,21,13,3,21,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7512,203,21,13,3,21,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7510,201,21,1,1,21,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7511,201,21,1,1,21,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7509,201,21,1,1,21,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7508,201,21,1,1,21,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7507,210,21,37,9,21,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7506,210,21,37,9,21,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7505,210,21,37,9,21,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7504,196,20,25,5,20,'Thursday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7503,196,20,25,5,20,'Wednesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7502,196,20,25,5,20,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7501,198,20,30,6,20,'Thursday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7500,198,20,30,6,20,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7499,198,20,30,6,20,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7498,192,20,10,2,20,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7497,192,20,10,2,20,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7496,192,20,10,2,20,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7495,192,20,10,2,20,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7494,194,20,20,4,20,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7492,194,20,20,4,20,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7493,194,20,20,4,20,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7491,193,20,12,3,20,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7490,193,20,12,3,20,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7488,193,20,12,3,20,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7489,193,20,12,3,20,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7487,191,20,5,1,20,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7486,191,20,5,1,20,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7484,191,20,5,1,20,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7485,191,20,5,1,20,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7483,186,19,23,5,19,'Thursday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7482,186,19,23,5,19,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7480,188,19,28,6,19,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7481,186,19,23,5,19,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7479,188,19,28,6,19,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7478,188,19,28,6,19,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7476,184,19,18,4,19,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7477,184,19,18,4,19,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7475,184,19,18,4,19,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7474,182,19,9,2,19,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7472,182,19,9,2,19,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7473,182,19,9,2,19,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7471,182,19,9,2,19,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7470,181,19,4,1,19,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7469,181,19,4,1,19,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7468,181,19,4,1,19,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7467,181,19,4,1,19,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7466,190,19,39,9,19,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7465,190,19,39,9,19,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7464,190,19,39,9,19,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7463,183,19,11,3,19,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7462,183,19,11,3,19,'Wednesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7461,183,19,11,3,19,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7460,183,19,11,3,19,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7459,174,18,21,4,18,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7458,174,18,21,4,18,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7457,174,18,21,4,18,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7456,178,18,30,6,18,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7454,178,18,30,6,18,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7455,178,18,30,6,18,'Thursday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7453,176,18,26,5,18,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7452,176,18,26,5,18,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7451,176,18,26,5,18,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7450,171,18,3,1,18,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7449,171,18,3,1,18,'Wednesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7447,171,18,3,1,18,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7448,171,18,3,1,18,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7446,173,18,16,3,18,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7445,173,18,16,3,18,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7444,173,18,16,3,18,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7443,173,18,16,3,18,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7442,172,18,8,2,18,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7441,172,18,8,2,18,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7440,172,18,8,2,18,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7439,172,18,8,2,18,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7438,180,18,38,9,18,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7436,180,18,38,9,18,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7437,180,18,38,9,18,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7435,166,17,24,14,17,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7434,166,17,24,14,17,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7432,168,17,28,6,17,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7433,166,17,24,14,17,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7431,168,17,28,6,17,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7430,168,17,28,6,17,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7429,162,17,7,2,17,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7428,162,17,7,2,17,'Wednesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7426,162,17,7,2,17,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7427,162,17,7,2,17,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7425,164,17,19,4,17,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7424,164,17,19,4,17,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7422,163,17,15,3,17,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7423,164,17,19,4,17,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7421,163,17,15,3,17,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7419,163,17,15,3,17,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7420,163,17,15,3,17,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7418,161,17,2,1,17,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7416,161,17,2,1,17,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7417,161,17,2,1,17,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7415,161,17,2,1,17,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7414,170,17,37,9,17,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7413,170,17,37,9,17,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7412,170,17,37,9,17,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7411,154,16,17,4,16,'Thursday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7410,154,16,17,4,16,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7408,158,16,30,6,16,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7409,154,16,17,4,16,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7407,158,16,30,6,16,'Wednesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7406,158,16,30,6,16,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7405,153,16,14,3,16,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7404,153,16,14,3,16,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7402,153,16,14,3,16,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7403,153,16,14,3,16,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7401,152,16,6,2,16,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7400,152,16,6,2,16,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7398,152,16,6,2,16,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7399,152,16,6,2,16,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7397,156,16,22,5,16,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7396,156,16,22,5,16,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7395,156,16,22,5,16,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7393,151,16,1,1,16,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7394,151,16,1,1,16,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7392,151,16,1,1,16,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7390,144,15,20,4,15,'Friday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7391,151,16,1,1,16,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7389,144,15,20,4,15,'Thursday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7388,144,15,20,4,15,'Saturday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7387,144,15,20,4,15,'Friday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7385,144,15,20,4,15,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7386,144,15,20,4,15,'Thursday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7384,141,15,5,1,15,'Saturday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7383,141,15,5,1,15,'Friday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7382,141,15,5,1,15,'Thursday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7381,141,15,5,1,15,'Wednesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7380,141,15,5,1,15,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7379,141,15,5,1,15,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7378,216,15,32,12,31,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7377,142,15,10,2,15,'Saturday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7375,142,15,10,2,15,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7376,142,15,10,2,15,'Friday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7374,142,15,10,2,15,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7373,142,15,10,2,15,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7372,142,15,10,2,15,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7371,146,15,25,5,15,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7370,146,15,25,5,15,'Saturday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7368,146,15,25,5,15,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7369,146,15,25,5,15,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7367,146,15,25,5,15,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7366,146,15,25,5,15,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7364,143,15,13,3,15,'Friday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7365,143,15,13,3,15,'Saturday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7363,143,15,13,3,15,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7362,143,15,13,3,15,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7361,143,15,13,3,15,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7360,143,15,13,3,15,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7359,150,15,39,13,15,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7358,150,15,39,13,15,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7357,150,15,39,13,15,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7356,215,15,42,11,27,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7355,214,15,33,7,22,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7354,211,15,43,7,15,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7353,211,15,43,7,15,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7352,211,15,43,7,15,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7350,136,14,23,5,14,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7351,136,14,23,5,14,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7348,138,14,30,6,14,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7349,138,14,30,6,14,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7347,134,14,18,4,14,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7346,134,14,18,4,14,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7345,133,14,12,3,14,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7344,133,14,12,3,14,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7343,133,14,12,3,14,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7342,133,14,12,3,14,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7341,131,14,4,1,14,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7340,131,14,4,1,14,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7339,131,14,4,1,14,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7337,132,14,9,2,14,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7338,131,14,4,1,14,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7336,132,14,9,2,14,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7335,132,14,9,2,14,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7334,132,14,9,2,14,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7332,140,14,38,9,14,'Friday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7333,140,14,38,9,14,'Saturday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7331,140,14,38,9,14,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7329,140,14,38,9,14,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7330,140,14,38,9,14,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7328,140,14,38,9,14,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7327,128,13,28,6,13,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7326,128,13,28,6,13,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7324,126,13,26,5,13,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7325,126,13,26,5,13,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7323,124,13,21,4,13,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7322,124,13,21,4,13,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7321,121,13,3,1,13,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7320,121,13,3,1,13,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7319,121,13,3,1,13,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7317,122,13,8,2,13,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7318,121,13,3,1,13,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7316,122,13,8,2,13,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7315,122,13,8,2,13,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7314,122,13,8,2,13,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7313,130,13,37,9,13,'Saturday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7312,130,13,37,9,13,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7311,130,13,37,9,13,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7310,130,13,37,9,13,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7308,130,13,37,9,13,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7309,130,13,37,9,13,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7307,123,13,11,3,13,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7306,123,13,11,3,13,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7305,123,13,11,3,13,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7304,123,13,11,3,13,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7303,116,12,24,14,12,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7301,118,12,30,6,12,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7302,116,12,24,14,12,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7300,118,12,30,6,12,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7299,114,12,19,4,12,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7297,113,12,16,3,12,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7298,114,12,19,4,12,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7296,113,12,16,3,12,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7295,113,12,16,3,12,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7294,113,12,16,3,12,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7293,112,12,7,2,12,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7292,112,12,7,2,12,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7291,112,12,7,2,12,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7290,112,12,7,2,12,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7288,111,12,2,1,12,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7289,111,12,2,1,12,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7287,111,12,2,1,12,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7286,111,12,2,1,12,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7285,104,11,17,4,11,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7284,104,11,17,4,11,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7283,101,11,1,1,11,'Friday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7282,101,11,1,1,11,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7281,101,11,1,1,11,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7280,101,11,1,1,11,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7279,108,11,28,6,11,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7278,108,11,28,6,11,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7277,106,11,22,5,11,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7275,110,11,39,9,11,'Saturday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7276,106,11,22,5,11,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7274,110,11,39,9,11,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7273,110,11,39,9,11,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7271,110,11,39,9,11,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7272,110,11,39,9,11,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7270,110,11,39,9,11,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7268,103,11,15,3,11,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7269,103,11,15,3,11,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7267,103,11,15,3,11,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7266,103,11,15,3,11,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7265,102,11,6,2,11,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7264,102,11,6,2,11,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7263,102,11,6,2,11,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7262,102,11,6,2,11,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7261,98,10,30,6,10,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7260,98,10,30,6,10,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7259,96,10,25,5,10,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7257,91,10,5,1,10,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7258,96,10,25,5,10,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7256,91,10,5,1,10,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7255,91,10,5,1,10,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7253,94,10,20,4,10,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7254,91,10,5,1,10,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7252,94,10,20,4,10,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7251,100,10,38,9,10,'Saturday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7250,100,10,38,9,10,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7248,100,10,38,9,10,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7249,100,10,38,9,10,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7247,100,10,38,9,10,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7245,92,10,10,2,10,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7246,100,10,38,9,10,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7244,92,10,10,2,10,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7242,92,10,10,2,10,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7243,92,10,10,2,10,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7240,93,10,14,3,10,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7241,93,10,14,3,10,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7239,93,10,14,3,10,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7237,81,9,4,1,9,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7238,93,10,14,3,10,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7236,81,9,4,1,9,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7234,81,9,4,1,9,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7235,81,9,4,1,9,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7233,85,9,37,9,9,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7232,85,9,37,9,9,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7231,85,9,37,9,9,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7230,82,9,9,2,9,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7229,82,9,9,2,9,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7227,82,9,9,2,9,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7228,82,9,9,2,9,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7226,84,9,36,8,9,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7225,84,9,36,8,9,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7223,83,9,13,3,9,'Friday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7224,84,9,36,8,9,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7222,83,9,13,3,9,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7221,83,9,13,3,9,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7220,83,9,13,3,9,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7219,83,9,13,3,9,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7218,90,9,29,6,9,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7217,90,9,29,6,9,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7216,88,9,41,10,26,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7215,80,8,28,6,8,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7214,80,8,28,6,8,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7213,72,8,8,2,8,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7212,72,8,8,2,8,'Wednesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7211,72,8,8,2,8,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7210,72,8,8,2,8,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7209,73,8,12,3,8,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7208,73,8,12,3,8,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7207,73,8,12,3,8,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7206,73,8,12,3,8,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7205,73,8,12,3,8,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7203,71,8,3,1,8,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7204,71,8,3,1,8,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7202,71,8,3,1,8,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7200,74,8,35,8,8,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7201,71,8,3,1,8,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7199,74,8,35,8,8,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7198,74,8,35,8,8,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7196,76,8,33,7,8,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7197,76,8,33,7,8,'Friday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7195,78,8,43,7,26,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7194,61,7,2,1,7,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7193,61,7,2,1,7,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7192,61,7,2,1,7,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7190,65,7,39,9,7,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7191,61,7,2,1,7,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7189,65,7,39,9,7,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7188,65,7,39,9,7,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7187,64,7,34,8,7,'Wednesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7185,64,7,34,8,7,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7186,64,7,34,8,7,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7184,62,7,7,2,7,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7183,62,7,7,2,7,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7181,62,7,7,2,7,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7182,62,7,7,2,7,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7180,63,7,11,3,7,'Friday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7179,63,7,11,3,7,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7177,63,7,11,3,7,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7178,63,7,11,3,7,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7176,63,7,11,3,7,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7175,70,7,27,6,7,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7174,70,7,27,6,7,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7173,66,7,31,7,7,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7172,66,7,31,7,7,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7171,68,7,41,10,26,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7170,56,6,32,12,6,'Thursday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7169,56,6,32,12,6,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7168,53,6,16,3,6,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7167,53,6,16,3,6,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7165,53,6,16,3,6,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7166,53,6,16,3,6,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7164,53,6,16,3,6,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7163,54,6,36,8,6,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7161,54,6,36,8,6,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7162,54,6,36,8,6,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7160,52,6,6,2,6,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7159,52,6,6,2,6,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7158,52,6,6,2,6,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7157,52,6,6,2,6,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7156,55,6,38,9,6,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7155,55,6,38,9,6,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7153,60,6,24,14,6,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7154,55,6,38,9,6,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7152,60,6,24,14,6,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7151,51,6,1,1,6,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7149,51,6,1,1,6,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7150,51,6,1,1,6,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7148,51,6,1,1,6,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7147,58,6,43,7,26,'Friday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7145,43,5,15,3,5,'Saturday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7146,43,5,15,3,5,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7144,43,5,15,3,5,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7143,43,5,15,3,5,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7142,43,5,15,3,5,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7141,42,5,10,2,5,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7140,42,5,10,2,5,'Wednesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7138,42,5,10,2,5,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7139,42,5,10,2,5,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7137,41,5,5,1,5,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7136,41,5,5,1,5,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7135,41,5,5,1,5,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7134,41,5,5,1,5,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7133,45,5,37,9,5,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7132,45,5,37,9,5,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7131,45,5,37,9,5,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7130,44,5,35,8,5,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7128,44,5,35,8,5,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7129,44,5,35,8,5,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7127,50,5,23,5,5,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7126,50,5,23,5,5,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7125,46,5,33,7,5,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7124,46,5,33,7,5,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7122,33,4,14,3,4,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7123,48,5,41,10,26,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7121,33,4,14,3,4,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7119,33,4,14,3,4,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7120,33,4,14,3,4,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7118,33,4,14,3,4,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7116,32,4,9,2,4,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7117,32,4,9,2,4,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7115,32,4,9,2,4,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7113,31,4,4,1,4,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7114,32,4,9,2,4,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7112,31,4,4,1,4,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7111,31,4,4,1,4,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7110,31,4,4,1,4,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7109,34,4,34,8,4,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7108,34,4,34,8,4,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7107,34,4,34,8,4,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7106,40,4,22,5,4,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7105,40,4,22,5,4,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7104,36,4,31,7,4,'Friday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7103,36,4,31,7,4,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7102,38,4,43,7,26,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7101,30,3,19,4,3,'Thursday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7100,30,3,19,4,3,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7099,25,3,39,9,3,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7098,25,3,39,9,3,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7097,25,3,39,9,3,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7095,24,3,36,8,3,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7096,24,3,36,8,3,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7094,24,3,36,8,3,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7092,23,3,13,3,3,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7093,23,3,13,3,3,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7091,23,3,13,3,3,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7089,23,3,13,3,3,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7090,23,3,13,3,3,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7087,22,3,8,2,3,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7088,22,3,8,2,3,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7086,22,3,8,2,3,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7085,22,3,8,2,3,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7084,21,3,3,1,3,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7083,21,3,3,1,3,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7082,21,3,3,1,3,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7081,21,3,3,1,3,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7080,26,3,32,12,3,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7079,26,3,32,12,3,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7078,28,3,41,10,26,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7077,15,2,38,9,2,'Thursday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7076,15,2,38,9,2,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7075,15,2,38,9,2,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7074,14,2,35,8,2,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7073,14,2,35,8,2,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7072,14,2,35,8,2,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7071,13,2,12,3,2,'Friday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7070,13,2,12,3,2,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7069,13,2,12,3,2,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7068,13,2,12,3,2,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7067,13,2,12,3,2,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7066,12,2,7,2,2,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7065,12,2,7,2,2,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7064,12,2,7,2,2,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7063,12,2,7,2,2,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7062,11,2,2,1,2,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7061,11,2,2,1,2,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7060,11,2,2,1,2,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7059,11,2,2,1,2,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7058,16,2,33,7,2,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7057,16,2,33,7,2,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7056,20,2,18,4,2,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7055,20,2,18,4,2,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7054,18,2,43,7,26,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7053,5,1,37,9,1,'Friday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7051,5,1,37,9,1,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7052,5,1,37,9,1,'Thursday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7049,4,1,34,8,1,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7050,4,1,34,8,1,'Friday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7048,4,1,34,8,1,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7046,2,1,6,2,1,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7047,2,1,6,2,1,'Friday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7045,2,1,6,2,1,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7044,2,1,6,2,1,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7043,3,1,11,3,1,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7042,3,1,11,3,1,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7041,3,1,11,3,1,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7040,3,1,11,3,1,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7039,3,1,11,3,1,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7038,10,1,17,4,1,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7037,10,1,17,4,1,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7036,1,1,1,1,1,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7034,1,1,1,1,1,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7035,1,1,1,1,1,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7033,1,1,1,1,1,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7032,8,1,41,10,26,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7031,6,1,31,7,1,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7030,6,1,31,7,1,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7029,207,21,23,5,24,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7028,207,21,23,5,24,'Thursday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7027,205,21,18,4,23,'Wednesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7026,205,21,18,4,23,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7025,199,20,27,6,25,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7024,199,20,27,6,25,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7023,195,20,21,4,23,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7022,195,20,21,4,23,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7021,189,19,29,6,25,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7020,189,19,29,6,25,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7019,187,19,24,14,24,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7018,187,19,24,14,24,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7017,177,18,22,5,24,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7016,177,18,22,5,24,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7015,175,18,17,4,23,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7014,175,18,17,4,23,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7013,169,17,29,6,25,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7012,169,17,29,6,25,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7011,165,17,20,4,23,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7010,165,17,20,4,23,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7009,159,16,27,6,25,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7008,159,16,27,6,25,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7007,157,16,23,5,24,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7006,157,16,23,5,24,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7005,212,15,21,4,23,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7004,212,15,21,4,23,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7003,147,15,26,5,24,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7002,147,15,26,5,24,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7001,139,14,27,6,25,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7000,139,14,27,6,25,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6999,135,14,19,4,23,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6998,135,14,19,4,23,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6997,129,13,29,6,25,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6996,129,13,29,6,25,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6995,127,13,22,5,24,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6994,127,13,22,5,24,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6993,117,12,25,5,24,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6992,117,12,25,5,24,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6991,115,12,20,4,23,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6990,115,12,20,4,23,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6989,109,11,29,6,25,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6988,109,11,29,6,25,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6986,105,11,18,4,23,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6987,105,11,18,4,23,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6985,99,10,27,6,25,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6983,97,10,26,5,24,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6984,99,10,27,6,25,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6982,97,10,26,5,24,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6981,88,9,41,10,26,'Wednesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6980,88,9,41,10,26,'Wednesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6979,87,9,33,7,22,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6978,87,9,33,7,22,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6977,79,8,42,11,27,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6976,79,8,42,11,27,'Wednesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6975,77,8,31,7,22,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6974,77,8,31,7,22,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6973,69,7,42,11,27,'Wednesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6972,69,7,42,11,27,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6971,68,7,41,10,26,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6970,68,7,41,10,26,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6969,58,6,43,7,26,'Thursday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6968,58,6,43,7,26,'Thursday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6967,57,6,33,7,22,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6966,57,6,33,7,22,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6965,49,5,42,11,27,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6964,49,5,42,11,27,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6963,47,5,31,7,22,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6962,47,5,31,7,22,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6961,39,4,42,11,27,'Tuesday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6960,39,4,42,11,27,'Tuesday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6959,38,4,43,7,26,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6958,38,4,43,7,26,'Wednesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6957,28,3,41,10,26,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6956,28,3,41,10,26,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6955,27,3,33,7,22,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6954,27,3,33,7,22,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6953,19,2,42,11,27,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6952,19,2,42,11,27,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6951,17,2,31,7,22,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6950,17,2,31,7,22,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6949,9,1,42,11,27,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6948,9,1,42,11,27,'Tuesday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6947,8,1,41,10,26,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6946,8,1,41,10,26,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6945,209,21,29,6,25,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6944,209,21,29,6,25,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6943,197,20,26,5,24,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6942,197,20,26,5,24,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6941,185,19,19,4,23,'Monday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6940,185,19,19,4,23,'Monday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6939,179,18,27,6,25,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6938,179,18,27,6,25,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6937,167,17,25,5,24,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6936,167,17,25,5,24,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6935,155,16,18,4,23,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6934,155,16,18,4,23,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6933,137,14,24,14,24,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6932,137,14,24,14,24,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6931,125,13,17,4,23,'Monday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6930,125,13,17,4,23,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6929,119,12,27,6,25,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6928,119,12,27,6,25,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6927,107,11,23,5,24,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6926,107,11,23,5,24,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6925,95,10,21,4,23,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6924,95,10,21,4,23,'Monday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6923,89,9,42,11,27,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6922,89,9,42,11,27,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6921,78,8,43,7,26,'Tuesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6920,78,8,43,7,26,'Tuesday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6919,59,6,42,11,27,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6918,59,6,42,11,27,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6917,48,5,41,10,26,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6916,48,5,41,10,26,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6915,37,4,32,12,22,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6914,37,4,32,12,22,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6913,29,3,42,11,27,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6912,29,3,42,11,27,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6911,18,2,43,7,26,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6910,18,2,43,7,26,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6909,7,1,32,12,22,'Monday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6908,7,1,32,12,22,'Monday',2,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(6907,217,8,32,12,31,'Monday',1,0,1,'COMBINED_CLASS_3_SUB_12','2026-10-31 00:29:18','2026-10-31 00:29:18'),(6906,86,9,32,12,31,'Monday',1,0,1,'COMBINED_CLASS_3_SUB_12','2026-10-31 00:29:18','2026-10-31 00:29:18'),(6905,67,7,32,12,31,'Monday',1,0,1,'COMBINED_CLASS_3_SUB_12','2026-10-31 00:29:18','2026-10-31 00:29:18'),(7517,202,21,6,2,21,'Tuesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7518,202,21,6,2,21,'Wednesday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7519,202,21,6,2,21,'Thursday',7,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7520,208,21,28,6,21,'Tuesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7521,208,21,28,6,21,'Wednesday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7522,208,21,28,6,21,'Thursday',8,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7523,206,21,22,5,21,'Monday',5,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7524,206,21,22,5,21,'Wednesday',3,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7525,206,21,22,5,21,'Friday',1,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7526,204,21,17,4,21,'Monday',6,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7527,204,21,17,4,21,'Tuesday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18'),(7528,204,21,17,4,21,'Thursday',4,0,0,NULL,'2026-10-31 00:29:18','2026-10-31 00:29:18');
/*!40000 ALTER TABLE `timetable_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
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

-- Dump completed on 2026-10-31 10:29:44
