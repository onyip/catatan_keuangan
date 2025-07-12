/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: catatan_keuangan
-- ------------------------------------------------------
-- Server version	11.4.7-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `dat_transaksi`
--

DROP TABLE IF EXISTS `dat_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dat_transaksi` (
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `deleted_at` varchar(100) DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL,
  `transaksi_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `transaksi_type` tinyint(4) DEFAULT NULL,
  `transaksi_tgl` timestamp NULL DEFAULT NULL,
  `nominal` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`transaksi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dat_transaksi`
--

LOCK TABLES `dat_transaksi` WRITE;
/*!40000 ALTER TABLE `dat_transaksi` DISABLE KEYS */;
INSERT INTO `dat_transaksi` VALUES
('2025-07-09 22:23:23','ALIF NUR ROMADHON','2025-07-09 23:08:25','ALIF NUR ROMADHON',NULL,NULL,2,1,1,1,'2025-07-09 22:23:00',100000,'Gaji Mei 2025'),
('2025-07-09 23:14:21','ALIF NUR ROMADHON',NULL,NULL,NULL,NULL,6,1,2,0,'2025-07-09 23:14:00',5000000,'hilaf'),
('2025-07-11 02:41:08','ALIF NUR ROMADHON',NULL,NULL,NULL,NULL,7,1,5,0,'2025-07-11 02:41:00',500000,'beli kulkas'),
('2025-07-11 02:46:23','ALIF NUR ROMADHON',NULL,NULL,NULL,NULL,8,1,4,1,'2025-07-11 02:46:00',100000,'bikin canva'),
('2025-07-12 12:21:42','ALIF NUR ROMADHON',NULL,NULL,NULL,NULL,9,1,2,0,'2025-07-12 12:21:00',100000,'siang'),
('2025-07-12 12:43:17','ALIF NUR ROMADHON',NULL,NULL,NULL,NULL,10,1,2,0,'2025-07-12 12:43:00',1000,'12');
/*!40000 ALTER TABLE `dat_transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_kategori`
--

DROP TABLE IF EXISTS `mst_kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_kategori` (
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `deleted_at` varchar(100) DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL,
  `kategori_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `kategori_nm` varchar(100) DEFAULT NULL,
  `transaksi_type` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`kategori_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_kategori`
--

LOCK TABLES `mst_kategori` WRITE;
/*!40000 ALTER TABLE `mst_kategori` DISABLE KEYS */;
INSERT INTO `mst_kategori` VALUES
('2025-07-09 20:28:00','Alif Nur Romadhon',NULL,NULL,NULL,NULL,1,1,'Gaji',1),
('2025-07-09 20:28:00','Alif Nur Romadhon','2025-07-10 07:13:10','ALIF NUR ROMADHON',NULL,NULL,2,1,'Makan',0),
('2025-07-10 07:00:00','ALIF NUR ROMADHON',NULL,NULL,NULL,NULL,4,1,'Sampingan',1),
('2025-07-10 07:14:14','ALIF NUR ROMADHON',NULL,NULL,NULL,NULL,5,1,'Bensin',0),
('2025-07-10 19:46:00','ALIF NUR ROMADHON',NULL,NULL,'2025-07-10 19:51:09','ALIF NUR ROMADHON',6,1,'foya foya',0);
/*!40000 ALTER TABLE `mst_kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_user`
--

DROP TABLE IF EXISTS `mst_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_user` (
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `deleted_at` varchar(100) DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `mst_user_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_user`
--

LOCK TABLES `mst_user` WRITE;
/*!40000 ALTER TABLE `mst_user` DISABLE KEYS */;
INSERT INTO `mst_user` VALUES
('2025-07-08 22:50:00','OG',NULL,NULL,NULL,NULL,1,'ogdang@gmail.com','test','$2y$10$isVZnZu9kvzU///CaqKL8uo6BZ8bIv135pYMOHA6mUv3RQ93AuSP2','ALIF NUR ROMADHON','1752159325_77e9b4b6108f292da07d.png'),
('2025-07-09 00:19:58','sadas',NULL,NULL,NULL,NULL,2,'odading@mail.com','odading','$2y$10$9jUpdXUrsvXtTKNMHBz1u.gz7waKWJRkkX7CsGv8rwDjSYMltDuZ2','SAMSUL HAL',NULL),
('2025-07-09 00:20:21','qwerty',NULL,NULL,NULL,NULL,3,'cimbrut@mail.com','cimbrut','$2y$10$6FxmbRVTtDWGRud0IZL5v.F2uxW.LH5wOzB2CsOV3v8gANaJ2esfu','CINTA KASIH',NULL),
('2025-07-12 12:12:52','ALIF NUR ROMADHON',NULL,NULL,NULL,NULL,7,'ogdang.id@gmail.com','budal','$2y$10$fbLolkheJXXV27HX2OxXO.2.LO.09w/sNyxLOVfM2y32qrrBrvEz.','ALIF NUR ROMADHON',NULL);
/*!40000 ALTER TABLE `mst_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'catatan_keuangan'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-12 13:42:21
