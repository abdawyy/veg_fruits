-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 14, 2026 at 10:02 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aldawy`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('al-dawy-cache-content_strings_map', 'a:0:{}', 2093980018),
('aldawy_content_strings_map', 'a:0:{}', 2096792866),
('aldawy_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:176:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:16:\"ViewAny:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:13:\"View:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:15:\"Create:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"Update:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:15:\"Delete:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:18:\"DeleteAny:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:16:\"Restore:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:20:\"ForceDelete:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:23:\"ForceDeleteAny:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:19:\"RestoreAny:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:18:\"Replicate:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"Reorder:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:12:\"ViewAny:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:9:\"View:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:11:\"Create:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:11:\"Update:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:11:\"Delete:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"DeleteAny:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:12:\"Restore:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:16:\"ForceDelete:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:19:\"ForceDeleteAny:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:15:\"RestoreAny:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:14:\"Replicate:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"Reorder:City\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:21:\"ViewAny:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:18:\"View:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:20:\"Create:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:20:\"Update:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:20:\"Delete:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:23:\"DeleteAny:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:21:\"Restore:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:25:\"ForceDelete:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:28:\"ForceDeleteAny:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:24:\"RestoreAny:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:23:\"Replicate:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:21:\"Reorder:ContentString\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:14:\"ViewAny:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:11:\"View:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:13:\"Create:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:13:\"Update:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:13:\"Delete:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:16:\"DeleteAny:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:14:\"Restore:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:18:\"ForceDelete:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:21:\"ForceDeleteAny:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:17:\"RestoreAny:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:16:\"Replicate:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:14:\"Reorder:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:18:\"ViewAny:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:15:\"View:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:17:\"Create:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:17:\"Update:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:17:\"Delete:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:20:\"DeleteAny:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:18:\"Restore:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:22:\"ForceDelete:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:25:\"ForceDeleteAny:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:21:\"RestoreAny:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:20:\"Replicate:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:18:\"Reorder:HomeBanner\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:13:\"ViewAny:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:10:\"View:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:12:\"Create:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:12:\"Update:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:12:\"Delete:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:15:\"DeleteAny:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:13:\"Restore:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:67;a:4:{s:1:\"a\";i:68;s:1:\"b\";s:17:\"ForceDelete:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:68;a:4:{s:1:\"a\";i:69;s:1:\"b\";s:20:\"ForceDeleteAny:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:69;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:16:\"RestoreAny:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:70;a:4:{s:1:\"a\";i:71;s:1:\"b\";s:15:\"Replicate:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:71;a:4:{s:1:\"a\";i:72;s:1:\"b\";s:13:\"Reorder:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:72;a:4:{s:1:\"a\";i:73;s:1:\"b\";s:21:\"ViewAny:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:73;a:4:{s:1:\"a\";i:74;s:1:\"b\";s:18:\"View:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:74;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:20:\"Create:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:75;a:4:{s:1:\"a\";i:76;s:1:\"b\";s:20:\"Update:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:76;a:4:{s:1:\"a\";i:77;s:1:\"b\";s:20:\"Delete:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:77;a:4:{s:1:\"a\";i:78;s:1:\"b\";s:23:\"DeleteAny:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:78;a:4:{s:1:\"a\";i:79;s:1:\"b\";s:21:\"Restore:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:79;a:4:{s:1:\"a\";i:80;s:1:\"b\";s:25:\"ForceDelete:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:80;a:4:{s:1:\"a\";i:81;s:1:\"b\";s:28:\"ForceDeleteAny:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:81;a:4:{s:1:\"a\";i:82;s:1:\"b\";s:24:\"RestoreAny:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:82;a:4:{s:1:\"a\";i:83;s:1:\"b\";s:23:\"Replicate:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:83;a:4:{s:1:\"a\";i:84;s:1:\"b\";s:21:\"Reorder:PackagingType\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:84;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:26:\"ViewAny:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:85;a:4:{s:1:\"a\";i:86;s:1:\"b\";s:23:\"View:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:86;a:4:{s:1:\"a\";i:87;s:1:\"b\";s:25:\"Create:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:87;a:4:{s:1:\"a\";i:88;s:1:\"b\";s:25:\"Update:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:88;a:4:{s:1:\"a\";i:89;s:1:\"b\";s:25:\"Delete:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:89;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:28:\"DeleteAny:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:90;a:4:{s:1:\"a\";i:91;s:1:\"b\";s:26:\"Restore:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:91;a:4:{s:1:\"a\";i:92;s:1:\"b\";s:30:\"ForceDelete:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:92;a:4:{s:1:\"a\";i:93;s:1:\"b\";s:33:\"ForceDeleteAny:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:93;a:4:{s:1:\"a\";i:94;s:1:\"b\";s:29:\"RestoreAny:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:94;a:4:{s:1:\"a\";i:95;s:1:\"b\";s:28:\"Replicate:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:95;a:4:{s:1:\"a\";i:96;s:1:\"b\";s:26:\"Reorder:PreparationService\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:96;a:4:{s:1:\"a\";i:97;s:1:\"b\";s:18:\"ViewAny:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:97;a:4:{s:1:\"a\";i:98;s:1:\"b\";s:15:\"View:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:98;a:4:{s:1:\"a\";i:99;s:1:\"b\";s:17:\"Create:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:99;a:4:{s:1:\"a\";i:100;s:1:\"b\";s:17:\"Update:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:100;a:4:{s:1:\"a\";i:101;s:1:\"b\";s:17:\"Delete:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:101;a:4:{s:1:\"a\";i:102;s:1:\"b\";s:20:\"DeleteAny:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:102;a:4:{s:1:\"a\";i:103;s:1:\"b\";s:18:\"Restore:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:103;a:4:{s:1:\"a\";i:104;s:1:\"b\";s:22:\"ForceDelete:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:104;a:4:{s:1:\"a\";i:105;s:1:\"b\";s:25:\"ForceDeleteAny:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:105;a:4:{s:1:\"a\";i:106;s:1:\"b\";s:21:\"RestoreAny:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:106;a:4:{s:1:\"a\";i:107;s:1:\"b\";s:20:\"Replicate:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:107;a:4:{s:1:\"a\";i:108;s:1:\"b\";s:18:\"Reorder:ProduceBox\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:108;a:4:{s:1:\"a\";i:109;s:1:\"b\";s:15:\"ViewAny:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:109;a:4:{s:1:\"a\";i:110;s:1:\"b\";s:12:\"View:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:110;a:4:{s:1:\"a\";i:111;s:1:\"b\";s:14:\"Create:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:111;a:4:{s:1:\"a\";i:112;s:1:\"b\";s:14:\"Update:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:112;a:4:{s:1:\"a\";i:113;s:1:\"b\";s:14:\"Delete:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:113;a:4:{s:1:\"a\";i:114;s:1:\"b\";s:17:\"DeleteAny:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:114;a:4:{s:1:\"a\";i:115;s:1:\"b\";s:15:\"Restore:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:115;a:4:{s:1:\"a\";i:116;s:1:\"b\";s:19:\"ForceDelete:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:116;a:4:{s:1:\"a\";i:117;s:1:\"b\";s:22:\"ForceDeleteAny:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:117;a:4:{s:1:\"a\";i:118;s:1:\"b\";s:18:\"RestoreAny:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:118;a:4:{s:1:\"a\";i:119;s:1:\"b\";s:17:\"Replicate:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:119;a:4:{s:1:\"a\";i:120;s:1:\"b\";s:15:\"Reorder:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:120;a:4:{s:1:\"a\";i:121;s:1:\"b\";s:18:\"ViewAny:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:121;a:4:{s:1:\"a\";i:122;s:1:\"b\";s:15:\"View:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:122;a:4:{s:1:\"a\";i:123;s:1:\"b\";s:17:\"Create:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:123;a:4:{s:1:\"a\";i:124;s:1:\"b\";s:17:\"Update:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:124;a:4:{s:1:\"a\";i:125;s:1:\"b\";s:17:\"Delete:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:125;a:4:{s:1:\"a\";i:126;s:1:\"b\";s:20:\"DeleteAny:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:126;a:4:{s:1:\"a\";i:127;s:1:\"b\";s:18:\"Restore:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:127;a:4:{s:1:\"a\";i:128;s:1:\"b\";s:22:\"ForceDelete:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:128;a:4:{s:1:\"a\";i:129;s:1:\"b\";s:25:\"ForceDeleteAny:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:129;a:4:{s:1:\"a\";i:130;s:1:\"b\";s:21:\"RestoreAny:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:130;a:4:{s:1:\"a\";i:131;s:1:\"b\";s:20:\"Replicate:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:131;a:4:{s:1:\"a\";i:132;s:1:\"b\";s:18:\"Reorder:SeoSetting\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:132;a:4:{s:1:\"a\";i:133;s:1:\"b\";s:19:\"ViewAny:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:133;a:4:{s:1:\"a\";i:134;s:1:\"b\";s:16:\"View:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:134;a:4:{s:1:\"a\";i:135;s:1:\"b\";s:18:\"Create:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:135;a:4:{s:1:\"a\";i:136;s:1:\"b\";s:18:\"Update:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:136;a:4:{s:1:\"a\";i:137;s:1:\"b\";s:18:\"Delete:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:137;a:4:{s:1:\"a\";i:138;s:1:\"b\";s:21:\"DeleteAny:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:138;a:4:{s:1:\"a\";i:139;s:1:\"b\";s:19:\"Restore:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:139;a:4:{s:1:\"a\";i:140;s:1:\"b\";s:23:\"ForceDelete:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:140;a:4:{s:1:\"a\";i:141;s:1:\"b\";s:26:\"ForceDeleteAny:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:141;a:4:{s:1:\"a\";i:142;s:1:\"b\";s:22:\"RestoreAny:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:142;a:4:{s:1:\"a\";i:143;s:1:\"b\";s:21:\"Replicate:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:143;a:4:{s:1:\"a\";i:144;s:1:\"b\";s:19:\"Reorder:SiteVisitor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:144;a:4:{s:1:\"a\";i:145;s:1:\"b\";s:12:\"ViewAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:145;a:4:{s:1:\"a\";i:146;s:1:\"b\";s:9:\"View:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:146;a:4:{s:1:\"a\";i:147;s:1:\"b\";s:11:\"Create:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:147;a:4:{s:1:\"a\";i:148;s:1:\"b\";s:11:\"Update:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:148;a:4:{s:1:\"a\";i:149;s:1:\"b\";s:11:\"Delete:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:149;a:4:{s:1:\"a\";i:150;s:1:\"b\";s:14:\"DeleteAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:150;a:4:{s:1:\"a\";i:151;s:1:\"b\";s:12:\"Restore:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:151;a:4:{s:1:\"a\";i:152;s:1:\"b\";s:16:\"ForceDelete:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:152;a:4:{s:1:\"a\";i:153;s:1:\"b\";s:19:\"ForceDeleteAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:153;a:4:{s:1:\"a\";i:154;s:1:\"b\";s:15:\"RestoreAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:154;a:4:{s:1:\"a\";i:155;s:1:\"b\";s:14:\"Replicate:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:155;a:4:{s:1:\"a\";i:156;s:1:\"b\";s:12:\"Reorder:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:156;a:4:{s:1:\"a\";i:157;s:1:\"b\";s:12:\"ViewAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:157;a:4:{s:1:\"a\";i:158;s:1:\"b\";s:9:\"View:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:158;a:4:{s:1:\"a\";i:159;s:1:\"b\";s:11:\"Create:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:159;a:4:{s:1:\"a\";i:160;s:1:\"b\";s:11:\"Update:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:160;a:4:{s:1:\"a\";i:161;s:1:\"b\";s:11:\"Delete:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:161;a:4:{s:1:\"a\";i:162;s:1:\"b\";s:14:\"DeleteAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:162;a:4:{s:1:\"a\";i:163;s:1:\"b\";s:12:\"Restore:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:163;a:4:{s:1:\"a\";i:164;s:1:\"b\";s:16:\"ForceDelete:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:164;a:4:{s:1:\"a\";i:165;s:1:\"b\";s:19:\"ForceDeleteAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:165;a:4:{s:1:\"a\";i:166;s:1:\"b\";s:15:\"RestoreAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:166;a:4:{s:1:\"a\";i:167;s:1:\"b\";s:14:\"Replicate:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:167;a:4:{s:1:\"a\";i:168;s:1:\"b\";s:12:\"Reorder:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:168;a:4:{s:1:\"a\";i:169;s:1:\"b\";s:15:\"View:AdminGuide\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:169;a:4:{s:1:\"a\";i:170;s:1:\"b\";s:25:\"View:ActiveVisitorsWidget\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:170;a:4:{s:1:\"a\";i:171;s:1:\"b\";s:27:\"View:CommerceSnapshotWidget\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:171;a:4:{s:1:\"a\";i:172;s:1:\"b\";s:24:\"View:OrdersOverTimeChart\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:172;a:4:{s:1:\"a\";i:173;s:1:\"b\";s:23:\"View:SalesOverviewStats\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:173;a:4:{s:1:\"a\";i:174;s:1:\"b\";s:28:\"View:TopProductsByViewsChart\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:174;a:4:{s:1:\"a\";i:175;s:1:\"b\";s:23:\"View:TopStorePathsChart\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:175;a:4:{s:1:\"a\";i:176;s:1:\"b\";s:26:\"View:TrafficReferrersChart\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}}s:5:\"roles\";a:5:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:15:\"catalog_manager\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"content_manager\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:14:\"orders_manager\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:16:\"analytics_viewer\";s:1:\"c\";s:3:\"web\";}}}', 1781525905),
('laravel-cache-content_strings_map', 'a:0:{}', 2093957322);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` json NOT NULL,
  `slug` json NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '{\"ar\": \"فواكه\", \"en\": \"Fruits\"}', '{\"ar\": \"فواكه\", \"en\": \"fruits\"}', 1, '2026-05-12 09:30:49', '2026-05-12 09:30:49'),
(2, '{\"ar\": \"خضروات\", \"en\": \"Vegetables\"}', '{\"ar\": \"خضروات\", \"en\": \"vegetables\"}', 1, '2026-05-12 09:30:49', '2026-05-12 09:30:49');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` json NOT NULL,
  `shipping_fee` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `code`, `name`, `shipping_fee`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'cairo', '{\"ar\": \"القاهرة\", \"en\": \"Cairo\"}', '25.0000', 1, 0, '2026-05-12 10:00:21', '2026-05-12 10:00:21'),
(2, 'giza', '{\"ar\": \"الجيزة\", \"en\": \"Giza\"}', '30.0000', 1, 1, '2026-05-12 10:00:21', '2026-05-12 10:00:21'),
(3, 'alex', '{\"ar\": \"الإسكندرية\", \"en\": \"Alexandria\"}', '45.0000', 1, 2, '2026-05-12 10:00:21', '2026-05-12 10:00:21'),
(4, 'delta', '{\"ar\": \"مدن الدلتا\", \"en\": \"Delta cities\"}', '35.0000', 1, 3, '2026-05-12 10:00:21', '2026-05-12 10:00:21'),
(5, 'upper', '{\"ar\": \"صعيد مصر\", \"en\": \"Upper Egypt\"}', '55.0000', 1, 4, '2026-05-12 10:00:21', '2026-05-12 10:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `content_strings`
--

CREATE TABLE `content_strings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `value_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `group` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `admin_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(14,4) NOT NULL,
  `min_subtotal` decimal(14,4) DEFAULT NULL,
  `max_uses` int UNSIGNED DEFAULT NULL,
  `used_count` int UNSIGNED NOT NULL DEFAULT '0',
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `home_banners`
--

CREATE TABLE `home_banners` (
  `id` bigint UNSIGNED NOT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `title` json NOT NULL,
  `subtitle` json NOT NULL,
  `badge_text` json DEFAULT NULL,
  `cta_label` json DEFAULT NULL,
  `cta_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gradient_from` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gradient_mid` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gradient_to` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hot_product_skus` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_banners`
--

INSERT INTO `home_banners` (`id`, `sort_order`, `is_active`, `starts_at`, `ends_at`, `title`, `subtitle`, `badge_text`, `cta_label`, `cta_url`, `image_url`, `image_path`, `gradient_from`, `gradient_mid`, `gradient_to`, `hot_product_skus`, `created_at`, `updated_at`) VALUES
(1, 0, 1, NULL, NULL, '{\"ar\": \"موسم المانجو المصري\", \"en\": \"This season: Egyptian mango\"}', '{\"ar\": \"حلوة وناضجة في الشمس — مثالية لسفرة رمضان مع التمر والبرتقال الطازج.\", \"en\": \"Sweet, sun-ripened & perfect for Ramadan tables — pair with dates & fresh juice oranges.\"}', '{\"ar\": \"الأكثر طلباً\", \"en\": \"Hot now\"}', '{\"ar\": \"تسوق المانجو والحمضيات\", \"en\": \"Shop mango & citrus\"}', '/shop?q=مانجو', 'https://images.unsplash.com/photo-1605027990121-c42e40f43593?auto=format&fit=crop&w=1200&q=85', NULL, '#f97316', '#eab308', '#22c55e', 'FRU-022,FRU-024,FRU-010,VEG-153', '2026-05-12 09:44:32', '2026-05-12 10:00:23');

-- --------------------------------------------------------

--
-- Table structure for table `import_audit_logs`
--

CREATE TABLE `import_audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `import_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dry_run` tinyint(1) NOT NULL DEFAULT '0',
  `rows_total` int UNSIGNED NOT NULL DEFAULT '0',
  `rows_ok` int UNSIGNED NOT NULL DEFAULT '0',
  `rows_failed` int UNSIGNED NOT NULL DEFAULT '0',
  `row_errors` json DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_12_120000_create_aldawy_commerce_tables', 1),
(5, '2026_05_12_120001_create_personal_access_tokens_table', 1),
(6, '2026_05_12_120100_create_notifications_table', 1),
(7, '2026_05_13_000001_add_image_url_to_products_table', 2),
(8, '2026_05_13_000002_create_home_banners_table', 2),
(9, '2026_05_14_000001_create_cities_and_order_city', 3),
(10, '2026_05_12_140000_add_image_path_to_products_table', 4),
(11, '2026_05_12_160000_create_content_strings_table', 5),
(12, '2026_05_12_160001_create_seo_settings_table', 5),
(13, '2026_05_12_160002_create_site_visitors_table', 5),
(14, '2026_05_12_160003_add_view_count_to_products_table', 5),
(15, '2026_05_16_000001_add_order_shipping_address_and_fee', 6),
(16, '2026_05_17_000001_add_visitor_insights_and_page_views', 7),
(17, '2026_05_17_000002_home_banners_image_upload', 7),
(18, '2026_05_20_100000_add_default_delivery_fields_to_users_table', 8),
(19, '2026_05_20_200000_phase_e_coupons_stock_import_audit', 8),
(20, '2026_05_22_000001_create_product_views_table', 8),
(21, '2026_05_22_151626_create_permission_tables', 8);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('50312988-1dbe-479c-a2f2-fe96cf879cf2', 'App\\Notifications\\AdminNewOrderNotification', 'App\\Models\\User', 1, '{\"title\":\"New order\",\"body\":\"Order AL-KGHCADOHCW \\u2014 270.0000\",\"order_id\":1}', NULL, '2026-05-12 18:09:48', '2026-05-12 18:09:48'),
('9e6168b1-3e38-442b-ae44-339104df8545', 'App\\Notifications\\AdminNewOrderNotification', 'App\\Models\\User', 1, '{\"title\":\"New order\",\"body\":\"Order AL-KGHCADOHCW \\u2014 270.0000\",\"order_id\":1}', NULL, '2026-05-12 18:09:48', '2026-05-12 18:09:48');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `customer_phone` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_id` bigint UNSIGNED DEFAULT NULL,
  `shipping_address_line1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_line2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_gateway` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `packaging_code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `packaging_fee` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `discount_amount` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `shipping_fee` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `total` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `invoice_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `reference`, `user_id`, `coupon_id`, `customer_phone`, `customer_name`, `customer_email`, `city_id`, `shipping_address_line1`, `shipping_address_line2`, `status`, `payment_gateway`, `packaging_code`, `subtotal`, `packaging_fee`, `discount_amount`, `shipping_fee`, `total`, `invoice_path`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'AL-KGHCADOHCW', NULL, NULL, '01223453010', 'testUser', 'eyadomar.ok@gmail.com', 2, 'العبور الحي التاسع شارع محمدعبدالمطلب', 'دور تالت', 'pending', 'cod', '', '240.0000', '0.0000', '0.0000', '30.0000', '270.0000', 'invoices/AL-KGHCADOHCW.pdf', NULL, '2026-05-12 18:09:47', '2026-05-12 18:09:47');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `produce_box_id` bigint UNSIGNED DEFAULT NULL,
  `product_name_snapshot` json DEFAULT NULL,
  `unit` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(16,4) NOT NULL,
  `services` json DEFAULT NULL,
  `packaging` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `line_total` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `produce_box_id`, `product_name_snapshot`, `unit`, `quantity`, `services`, `packaging`, `unit_price`, `line_total`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 1, 3, NULL, '{\"ar\": \"أفوكادو\", \"en\": \"Avocado\"}', 'kg', '2.0000', '[]', '', '120.0000', '240.0000', NULL, '2026-05-12 18:09:47', '2026-05-12 18:09:47');

-- --------------------------------------------------------

--
-- Table structure for table `packaging_types`
--

CREATE TABLE `packaging_types` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` json NOT NULL,
  `surcharge_amount` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `surcharge_is_percent` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packaging_types`
--

INSERT INTO `packaging_types` (`id`, `code`, `name`, `surcharge_amount`, `surcharge_is_percent`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'bag', '{\"ar\": \"كيس بلاستيك\", \"en\": \"Plastic bag\"}', '0.0000', 0, 1, 0, '2026-05-12 10:00:21', '2026-05-12 10:00:21'),
(2, 'tray', '{\"ar\": \"علبة مغلفة\", \"en\": \"Tray wrap\"}', '5.0000', 0, 1, 1, '2026-05-12 10:00:21', '2026-05-12 10:00:21'),
(3, 'box', '{\"ar\": \"صندوق\", \"en\": \"Box\"}', '12.0000', 0, 1, 2, '2026-05-12 10:00:21', '2026-05-12 10:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `packaging_type_product`
--

CREATE TABLE `packaging_type_product` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `packaging_type_id` bigint UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packaging_type_product`
--

INSERT INTO `packaging_type_product` (`id`, `product_id`, `packaging_type_id`, `is_enabled`) VALUES
(1, 1, 1, 1),
(2, 1, 3, 1),
(3, 1, 2, 1),
(4, 2, 1, 1),
(5, 2, 3, 1),
(6, 2, 2, 1),
(7, 3, 1, 1),
(8, 3, 3, 1),
(9, 3, 2, 1),
(10, 4, 1, 1),
(11, 4, 3, 1),
(12, 4, 2, 1),
(13, 5, 1, 1),
(14, 5, 3, 1),
(15, 5, 2, 1),
(16, 6, 1, 1),
(17, 6, 3, 1),
(18, 6, 2, 1),
(19, 7, 1, 1),
(20, 7, 3, 1),
(21, 7, 2, 1),
(22, 8, 1, 1),
(23, 8, 3, 1),
(24, 8, 2, 1),
(25, 9, 1, 1),
(26, 9, 3, 1),
(27, 9, 2, 1),
(28, 10, 1, 1),
(29, 10, 3, 1),
(30, 10, 2, 1),
(31, 11, 1, 1),
(32, 11, 3, 1),
(33, 11, 2, 1),
(34, 12, 1, 1),
(35, 12, 3, 1),
(36, 12, 2, 1),
(37, 13, 1, 1),
(38, 13, 3, 1),
(39, 13, 2, 1),
(40, 14, 1, 1),
(41, 14, 3, 1),
(42, 14, 2, 1),
(43, 15, 1, 1),
(44, 15, 3, 1),
(45, 15, 2, 1),
(46, 16, 1, 1),
(47, 16, 3, 1),
(48, 16, 2, 1),
(49, 17, 1, 1),
(50, 17, 3, 1),
(51, 17, 2, 1),
(52, 18, 1, 1),
(53, 18, 3, 1),
(54, 18, 2, 1),
(55, 19, 1, 1),
(56, 19, 3, 1),
(57, 19, 2, 1),
(58, 20, 1, 1),
(59, 20, 3, 1),
(60, 20, 2, 1),
(61, 21, 1, 1),
(62, 21, 3, 1),
(63, 21, 2, 1),
(64, 22, 1, 1),
(65, 22, 3, 1),
(66, 22, 2, 1),
(67, 23, 1, 1),
(68, 23, 3, 1),
(69, 23, 2, 1),
(70, 24, 1, 1),
(71, 24, 3, 1),
(72, 24, 2, 1),
(73, 25, 1, 1),
(74, 25, 3, 1),
(75, 25, 2, 1),
(76, 26, 1, 1),
(77, 26, 3, 1),
(78, 26, 2, 1),
(79, 27, 1, 1),
(80, 27, 3, 1),
(81, 27, 2, 1),
(82, 28, 1, 1),
(83, 28, 3, 1),
(84, 28, 2, 1),
(85, 29, 1, 1),
(86, 29, 3, 1),
(87, 29, 2, 1),
(88, 30, 1, 1),
(89, 30, 3, 1),
(90, 30, 2, 1),
(91, 31, 1, 1),
(92, 31, 3, 1),
(93, 31, 2, 1),
(94, 32, 1, 1),
(95, 32, 3, 1),
(96, 32, 2, 1),
(97, 33, 1, 1),
(98, 33, 3, 1),
(99, 33, 2, 1),
(100, 34, 1, 1),
(101, 34, 3, 1),
(102, 34, 2, 1),
(103, 35, 1, 1),
(104, 35, 3, 1),
(105, 35, 2, 1),
(106, 36, 1, 1),
(107, 36, 3, 1),
(108, 36, 2, 1),
(109, 37, 1, 1),
(110, 37, 3, 1),
(111, 37, 2, 1),
(112, 38, 1, 1),
(113, 38, 3, 1),
(114, 38, 2, 1),
(115, 39, 1, 1),
(116, 39, 3, 1),
(117, 39, 2, 1),
(118, 40, 1, 1),
(119, 40, 3, 1),
(120, 40, 2, 1),
(121, 41, 1, 1),
(122, 41, 3, 1),
(123, 41, 2, 1),
(124, 42, 1, 1),
(125, 42, 3, 1),
(126, 42, 2, 1),
(127, 43, 1, 1),
(128, 43, 3, 1),
(129, 43, 2, 1),
(130, 44, 1, 1),
(131, 44, 3, 1),
(132, 44, 2, 1),
(133, 45, 1, 1),
(134, 45, 3, 1),
(135, 45, 2, 1),
(136, 46, 1, 1),
(137, 46, 3, 1),
(138, 46, 2, 1),
(139, 47, 1, 1),
(140, 47, 3, 1),
(141, 47, 2, 1),
(142, 48, 1, 1),
(143, 48, 3, 1),
(144, 48, 2, 1),
(145, 49, 1, 1),
(146, 49, 3, 1),
(147, 49, 2, 1),
(148, 50, 1, 1),
(149, 50, 3, 1),
(150, 50, 2, 1),
(151, 51, 1, 1),
(152, 51, 3, 1),
(153, 51, 2, 1),
(154, 52, 1, 1),
(155, 52, 3, 1),
(156, 52, 2, 1),
(157, 53, 1, 1),
(158, 53, 3, 1),
(159, 53, 2, 1),
(160, 54, 1, 1),
(161, 54, 3, 1),
(162, 54, 2, 1),
(163, 55, 1, 1),
(164, 55, 3, 1),
(165, 55, 2, 1),
(166, 56, 1, 1),
(167, 56, 3, 1),
(168, 56, 2, 1),
(169, 57, 1, 1),
(170, 57, 3, 1),
(171, 57, 2, 1),
(172, 58, 1, 1),
(173, 58, 3, 1),
(174, 58, 2, 1),
(175, 59, 1, 1),
(176, 59, 3, 1),
(177, 59, 2, 1),
(178, 60, 1, 1),
(179, 60, 3, 1),
(180, 60, 2, 1),
(181, 61, 1, 1),
(182, 61, 3, 1),
(183, 61, 2, 1),
(184, 62, 1, 1),
(185, 62, 3, 1),
(186, 62, 2, 1),
(187, 63, 1, 1),
(188, 63, 3, 1),
(189, 63, 2, 1),
(190, 64, 1, 1),
(191, 64, 3, 1),
(192, 64, 2, 1),
(193, 65, 1, 1),
(194, 65, 3, 1),
(195, 65, 2, 1),
(196, 66, 1, 1),
(197, 66, 3, 1),
(198, 66, 2, 1),
(199, 67, 1, 1),
(200, 67, 3, 1),
(201, 67, 2, 1),
(202, 68, 1, 1),
(203, 68, 3, 1),
(204, 68, 2, 1),
(205, 69, 1, 1),
(206, 69, 3, 1),
(207, 69, 2, 1),
(208, 70, 1, 1),
(209, 70, 3, 1),
(210, 70, 2, 1),
(211, 71, 1, 1),
(212, 71, 3, 1),
(213, 71, 2, 1),
(214, 72, 1, 1),
(215, 72, 3, 1),
(216, 72, 2, 1),
(217, 73, 1, 1),
(218, 73, 3, 1),
(219, 73, 2, 1),
(220, 74, 1, 1),
(221, 74, 3, 1),
(222, 74, 2, 1),
(223, 75, 1, 1),
(224, 75, 3, 1),
(225, 75, 2, 1),
(226, 76, 1, 1),
(227, 76, 3, 1),
(228, 76, 2, 1),
(229, 77, 1, 1),
(230, 77, 3, 1),
(231, 77, 2, 1),
(232, 78, 1, 1),
(233, 78, 3, 1),
(234, 78, 2, 1),
(235, 79, 1, 1),
(236, 79, 3, 1),
(237, 79, 2, 1),
(238, 80, 1, 1),
(239, 80, 3, 1),
(240, 80, 2, 1),
(241, 81, 1, 1),
(242, 81, 3, 1),
(243, 81, 2, 1),
(244, 82, 1, 1),
(245, 82, 3, 1),
(246, 82, 2, 1),
(247, 83, 1, 1),
(248, 83, 3, 1),
(249, 83, 2, 1),
(250, 84, 1, 1),
(251, 84, 3, 1),
(252, 84, 2, 1),
(253, 85, 1, 1),
(254, 85, 3, 1),
(255, 85, 2, 1),
(256, 86, 1, 1),
(257, 86, 3, 1),
(258, 86, 2, 1),
(259, 87, 1, 1),
(260, 87, 3, 1),
(261, 87, 2, 1),
(262, 88, 1, 1),
(263, 88, 3, 1),
(264, 88, 2, 1),
(265, 89, 1, 1),
(266, 89, 3, 1),
(267, 89, 2, 1),
(268, 90, 1, 1),
(269, 90, 3, 1),
(270, 90, 2, 1),
(271, 91, 1, 1),
(272, 91, 3, 1),
(273, 91, 2, 1),
(274, 92, 1, 1),
(275, 92, 3, 1),
(276, 92, 2, 1),
(277, 93, 1, 1),
(278, 93, 3, 1),
(279, 93, 2, 1),
(280, 94, 1, 1),
(281, 94, 3, 1),
(282, 94, 2, 1),
(283, 95, 1, 1),
(284, 95, 3, 1),
(285, 95, 2, 1),
(286, 96, 1, 1),
(287, 96, 3, 1),
(288, 96, 2, 1),
(289, 97, 1, 1),
(290, 97, 3, 1),
(291, 97, 2, 1),
(292, 98, 1, 1),
(293, 98, 3, 1),
(294, 98, 2, 1),
(295, 99, 1, 1),
(296, 99, 3, 1),
(297, 99, 2, 1),
(298, 100, 1, 1),
(299, 100, 3, 1),
(300, 100, 2, 1),
(301, 101, 1, 1),
(302, 101, 3, 1),
(303, 101, 2, 1),
(304, 102, 1, 1),
(305, 102, 3, 1),
(306, 102, 2, 1),
(307, 103, 1, 1),
(308, 103, 3, 1),
(309, 103, 2, 1),
(310, 104, 1, 1),
(311, 104, 3, 1),
(312, 104, 2, 1),
(313, 105, 1, 1),
(314, 105, 3, 1),
(315, 105, 2, 1),
(316, 106, 1, 1),
(317, 106, 3, 1),
(318, 106, 2, 1),
(319, 107, 1, 1),
(320, 107, 3, 1),
(321, 107, 2, 1),
(322, 108, 1, 1),
(323, 108, 3, 1),
(324, 108, 2, 1),
(325, 109, 1, 1),
(326, 109, 3, 1),
(327, 109, 2, 1),
(328, 110, 1, 1),
(329, 110, 3, 1),
(330, 110, 2, 1),
(331, 111, 1, 1),
(332, 111, 3, 1),
(333, 111, 2, 1),
(334, 112, 1, 1),
(335, 112, 3, 1),
(336, 112, 2, 1),
(337, 113, 1, 1),
(338, 113, 3, 1),
(339, 113, 2, 1),
(340, 114, 1, 1),
(341, 114, 3, 1),
(342, 114, 2, 1),
(343, 115, 1, 1),
(344, 115, 3, 1),
(345, 115, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'ViewAny:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(2, 'View:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(3, 'Create:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(4, 'Update:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(5, 'Delete:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(6, 'DeleteAny:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(7, 'Restore:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(8, 'ForceDelete:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(9, 'ForceDeleteAny:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(10, 'RestoreAny:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(11, 'Replicate:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(12, 'Reorder:Category', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(13, 'ViewAny:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(14, 'View:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(15, 'Create:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(16, 'Update:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(17, 'Delete:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(18, 'DeleteAny:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(19, 'Restore:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(20, 'ForceDelete:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(21, 'ForceDeleteAny:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(22, 'RestoreAny:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(23, 'Replicate:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(24, 'Reorder:City', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(25, 'ViewAny:ContentString', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(26, 'View:ContentString', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(27, 'Create:ContentString', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(28, 'Update:ContentString', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(29, 'Delete:ContentString', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(30, 'DeleteAny:ContentString', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(31, 'Restore:ContentString', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(32, 'ForceDelete:ContentString', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(33, 'ForceDeleteAny:ContentString', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(34, 'RestoreAny:ContentString', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(35, 'Replicate:ContentString', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(36, 'Reorder:ContentString', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(37, 'ViewAny:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(38, 'View:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(39, 'Create:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(40, 'Update:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(41, 'Delete:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(42, 'DeleteAny:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(43, 'Restore:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(44, 'ForceDelete:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(45, 'ForceDeleteAny:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(46, 'RestoreAny:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(47, 'Replicate:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(48, 'Reorder:Coupon', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(49, 'ViewAny:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(50, 'View:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(51, 'Create:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(52, 'Update:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(53, 'Delete:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(54, 'DeleteAny:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(55, 'Restore:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(56, 'ForceDelete:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(57, 'ForceDeleteAny:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(58, 'RestoreAny:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(59, 'Replicate:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(60, 'Reorder:HomeBanner', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(61, 'ViewAny:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(62, 'View:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(63, 'Create:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(64, 'Update:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(65, 'Delete:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(66, 'DeleteAny:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(67, 'Restore:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(68, 'ForceDelete:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(69, 'ForceDeleteAny:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(70, 'RestoreAny:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(71, 'Replicate:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(72, 'Reorder:Order', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(73, 'ViewAny:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(74, 'View:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(75, 'Create:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(76, 'Update:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(77, 'Delete:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(78, 'DeleteAny:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(79, 'Restore:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(80, 'ForceDelete:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(81, 'ForceDeleteAny:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(82, 'RestoreAny:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(83, 'Replicate:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(84, 'Reorder:PackagingType', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(85, 'ViewAny:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(86, 'View:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(87, 'Create:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(88, 'Update:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(89, 'Delete:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(90, 'DeleteAny:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(91, 'Restore:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(92, 'ForceDelete:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(93, 'ForceDeleteAny:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(94, 'RestoreAny:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(95, 'Replicate:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(96, 'Reorder:PreparationService', 'web', '2026-06-14 09:16:52', '2026-06-14 09:16:52'),
(97, 'ViewAny:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(98, 'View:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(99, 'Create:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(100, 'Update:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(101, 'Delete:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(102, 'DeleteAny:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(103, 'Restore:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(104, 'ForceDelete:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(105, 'ForceDeleteAny:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(106, 'RestoreAny:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(107, 'Replicate:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(108, 'Reorder:ProduceBox', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(109, 'ViewAny:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(110, 'View:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(111, 'Create:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(112, 'Update:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(113, 'Delete:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(114, 'DeleteAny:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(115, 'Restore:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(116, 'ForceDelete:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(117, 'ForceDeleteAny:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(118, 'RestoreAny:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(119, 'Replicate:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(120, 'Reorder:Product', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(121, 'ViewAny:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(122, 'View:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(123, 'Create:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(124, 'Update:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(125, 'Delete:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(126, 'DeleteAny:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(127, 'Restore:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(128, 'ForceDelete:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(129, 'ForceDeleteAny:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(130, 'RestoreAny:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(131, 'Replicate:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(132, 'Reorder:SeoSetting', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(133, 'ViewAny:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(134, 'View:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(135, 'Create:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(136, 'Update:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(137, 'Delete:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(138, 'DeleteAny:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(139, 'Restore:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(140, 'ForceDelete:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(141, 'ForceDeleteAny:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(142, 'RestoreAny:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(143, 'Replicate:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(144, 'Reorder:SiteVisitor', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(145, 'ViewAny:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(146, 'View:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(147, 'Create:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(148, 'Update:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(149, 'Delete:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(150, 'DeleteAny:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(151, 'Restore:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(152, 'ForceDelete:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(153, 'ForceDeleteAny:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(154, 'RestoreAny:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(155, 'Replicate:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(156, 'Reorder:User', 'web', '2026-06-14 09:16:53', '2026-06-14 09:16:53'),
(157, 'ViewAny:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(158, 'View:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(159, 'Create:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(160, 'Update:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(161, 'Delete:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(162, 'DeleteAny:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(163, 'Restore:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(164, 'ForceDelete:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(165, 'ForceDeleteAny:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(166, 'RestoreAny:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(167, 'Replicate:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(168, 'Reorder:Role', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(169, 'View:AdminGuide', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(170, 'View:ActiveVisitorsWidget', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(171, 'View:CommerceSnapshotWidget', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(172, 'View:OrdersOverTimeChart', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(173, 'View:SalesOverviewStats', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(174, 'View:TopProductsByViewsChart', 'web', '2026-06-14 09:16:54', '2026-06-14 09:16:54'),
(175, 'View:TopStorePathsChart', 'web', '2026-06-14 09:16:55', '2026-06-14 09:16:55'),
(176, 'View:TrafficReferrersChart', 'web', '2026-06-14 09:16:55', '2026-06-14 09:16:55');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_verifications`
--

CREATE TABLE `phone_verifications` (
  `id` bigint UNSIGNED NOT NULL,
  `phone_number` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preparation_services`
--

CREATE TABLE `preparation_services` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` json NOT NULL,
  `surcharge_amount` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `surcharge_is_percent` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `preparation_services`
--

INSERT INTO `preparation_services` (`id`, `code`, `name`, `surcharge_amount`, `surcharge_is_percent`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'wash', '{\"ar\": \"غسل وتنظيف\", \"en\": \"Wash & trim\"}', '5.0000', 0, 1, 0, '2026-05-12 10:00:21', '2026-05-12 10:00:21'),
(2, 'peel', '{\"ar\": \"تقشير\", \"en\": \"Peel\"}', '3.0000', 0, 1, 1, '2026-05-12 10:00:21', '2026-05-12 10:00:21'),
(3, 'dice', '{\"ar\": \"تقطيع مكعبات\", \"en\": \"Dice / chop\"}', '7.0000', 0, 1, 2, '2026-05-12 10:00:21', '2026-05-12 10:00:21'),
(4, 'slice', '{\"ar\": \"شرائح\", \"en\": \"Slice\"}', '4.0000', 0, 1, 3, '2026-05-12 10:00:21', '2026-05-12 10:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `preparation_service_product`
--

CREATE TABLE `preparation_service_product` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `preparation_service_id` bigint UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `preparation_service_product`
--

INSERT INTO `preparation_service_product` (`id`, `product_id`, `preparation_service_id`, `is_enabled`) VALUES
(1, 1, 3, 1),
(2, 1, 2, 1),
(3, 1, 4, 1),
(4, 1, 1, 1),
(5, 2, 3, 1),
(6, 2, 2, 1),
(7, 2, 4, 1),
(8, 2, 1, 1),
(9, 3, 3, 1),
(10, 3, 2, 1),
(11, 3, 4, 1),
(12, 3, 1, 1),
(13, 4, 3, 1),
(14, 4, 2, 1),
(15, 4, 4, 1),
(16, 4, 1, 1),
(17, 5, 3, 1),
(18, 5, 2, 1),
(19, 5, 4, 1),
(20, 5, 1, 1),
(21, 6, 3, 1),
(22, 6, 2, 1),
(23, 6, 4, 1),
(24, 6, 1, 1),
(25, 7, 3, 1),
(26, 7, 2, 1),
(27, 7, 4, 1),
(28, 7, 1, 1),
(29, 8, 3, 1),
(30, 8, 2, 1),
(31, 8, 4, 1),
(32, 8, 1, 1),
(33, 9, 3, 1),
(34, 9, 2, 1),
(35, 9, 4, 1),
(36, 9, 1, 1),
(37, 10, 3, 1),
(38, 10, 2, 1),
(39, 10, 4, 1),
(40, 10, 1, 1),
(41, 11, 3, 1),
(42, 11, 2, 1),
(43, 11, 4, 1),
(44, 11, 1, 1),
(45, 12, 3, 1),
(46, 12, 2, 1),
(47, 12, 4, 1),
(48, 12, 1, 1),
(49, 13, 3, 1),
(50, 13, 2, 1),
(51, 13, 4, 1),
(52, 13, 1, 1),
(53, 14, 3, 1),
(54, 14, 2, 1),
(55, 14, 4, 1),
(56, 14, 1, 1),
(57, 15, 3, 1),
(58, 15, 2, 1),
(59, 15, 4, 1),
(60, 15, 1, 1),
(61, 16, 3, 1),
(62, 16, 2, 1),
(63, 16, 4, 1),
(64, 16, 1, 1),
(65, 17, 3, 1),
(66, 17, 2, 1),
(67, 17, 4, 1),
(68, 17, 1, 1),
(69, 18, 3, 1),
(70, 18, 2, 1),
(71, 18, 4, 1),
(72, 18, 1, 1),
(73, 19, 3, 1),
(74, 19, 2, 1),
(75, 19, 4, 1),
(76, 19, 1, 1),
(77, 20, 3, 1),
(78, 20, 2, 1),
(79, 20, 4, 1),
(80, 20, 1, 1),
(81, 21, 3, 1),
(82, 21, 2, 1),
(83, 21, 4, 1),
(84, 21, 1, 1),
(85, 22, 3, 1),
(86, 22, 2, 1),
(87, 22, 4, 1),
(88, 22, 1, 1),
(89, 23, 3, 1),
(90, 23, 2, 1),
(91, 23, 4, 1),
(92, 23, 1, 1),
(93, 24, 3, 1),
(94, 24, 2, 1),
(95, 24, 4, 1),
(96, 24, 1, 1),
(97, 25, 3, 1),
(98, 25, 2, 1),
(99, 25, 4, 1),
(100, 25, 1, 1),
(101, 26, 3, 1),
(102, 26, 2, 1),
(103, 26, 4, 1),
(104, 26, 1, 1),
(105, 27, 3, 1),
(106, 27, 2, 1),
(107, 27, 4, 1),
(108, 27, 1, 1),
(109, 28, 3, 1),
(110, 28, 2, 1),
(111, 28, 4, 1),
(112, 28, 1, 1),
(113, 29, 3, 1),
(114, 29, 2, 1),
(115, 29, 4, 1),
(116, 29, 1, 1),
(117, 30, 3, 1),
(118, 30, 2, 1),
(119, 30, 4, 1),
(120, 30, 1, 1),
(121, 31, 3, 1),
(122, 31, 2, 1),
(123, 31, 4, 1),
(124, 31, 1, 1),
(125, 32, 3, 1),
(126, 32, 2, 1),
(127, 32, 4, 1),
(128, 32, 1, 1),
(129, 33, 3, 1),
(130, 33, 2, 1),
(131, 33, 4, 1),
(132, 33, 1, 1),
(133, 34, 3, 1),
(134, 34, 2, 1),
(135, 34, 4, 1),
(136, 34, 1, 1),
(137, 35, 3, 1),
(138, 35, 2, 1),
(139, 35, 4, 1),
(140, 35, 1, 1),
(141, 36, 3, 1),
(142, 36, 2, 1),
(143, 36, 4, 1),
(144, 36, 1, 1),
(145, 37, 3, 1),
(146, 37, 2, 1),
(147, 37, 4, 1),
(148, 37, 1, 1),
(149, 38, 3, 1),
(150, 38, 2, 1),
(151, 38, 4, 1),
(152, 38, 1, 1),
(153, 39, 3, 1),
(154, 39, 2, 1),
(155, 39, 4, 1),
(156, 39, 1, 1),
(157, 40, 3, 1),
(158, 40, 2, 1),
(159, 40, 4, 1),
(160, 40, 1, 1),
(161, 41, 3, 1),
(162, 41, 2, 1),
(163, 41, 4, 1),
(164, 41, 1, 1),
(165, 42, 3, 1),
(166, 42, 2, 1),
(167, 42, 4, 1),
(168, 42, 1, 1),
(169, 43, 3, 1),
(170, 43, 2, 1),
(171, 43, 4, 1),
(172, 43, 1, 1),
(173, 44, 3, 1),
(174, 44, 2, 1),
(175, 44, 4, 1),
(176, 44, 1, 1),
(177, 45, 3, 1),
(178, 45, 2, 1),
(179, 45, 4, 1),
(180, 45, 1, 1),
(181, 46, 3, 1),
(182, 46, 2, 1),
(183, 46, 4, 1),
(184, 46, 1, 1),
(185, 47, 3, 1),
(186, 47, 2, 1),
(187, 47, 4, 1),
(188, 47, 1, 1),
(189, 48, 3, 1),
(190, 48, 2, 1),
(191, 48, 4, 1),
(192, 48, 1, 1),
(193, 49, 3, 1),
(194, 49, 2, 1),
(195, 49, 4, 1),
(196, 49, 1, 1),
(197, 50, 3, 1),
(198, 50, 2, 1),
(199, 50, 4, 1),
(200, 50, 1, 1),
(201, 51, 3, 1),
(202, 51, 2, 1),
(203, 51, 4, 1),
(204, 51, 1, 1),
(205, 52, 3, 1),
(206, 52, 2, 1),
(207, 52, 4, 1),
(208, 52, 1, 1),
(209, 53, 3, 1),
(210, 53, 2, 1),
(211, 53, 4, 1),
(212, 53, 1, 1),
(213, 54, 3, 1),
(214, 54, 2, 1),
(215, 54, 4, 1),
(216, 54, 1, 1),
(217, 55, 3, 1),
(218, 55, 2, 1),
(219, 55, 4, 1),
(220, 55, 1, 1),
(221, 56, 3, 1),
(222, 56, 2, 1),
(223, 56, 4, 1),
(224, 56, 1, 1),
(225, 57, 3, 1),
(226, 57, 2, 1),
(227, 57, 4, 1),
(228, 57, 1, 1),
(229, 58, 3, 1),
(230, 58, 2, 1),
(231, 58, 4, 1),
(232, 58, 1, 1),
(233, 59, 3, 1),
(234, 59, 2, 1),
(235, 59, 4, 1),
(236, 59, 1, 1),
(237, 60, 3, 1),
(238, 60, 2, 1),
(239, 60, 4, 1),
(240, 60, 1, 1),
(241, 61, 3, 1),
(242, 61, 2, 1),
(243, 61, 4, 1),
(244, 61, 1, 1),
(245, 62, 3, 1),
(246, 62, 2, 1),
(247, 62, 4, 1),
(248, 62, 1, 1),
(249, 63, 3, 1),
(250, 63, 2, 1),
(251, 63, 4, 1),
(252, 63, 1, 1),
(253, 64, 3, 1),
(254, 64, 2, 1),
(255, 64, 4, 1),
(256, 64, 1, 1),
(257, 65, 3, 1),
(258, 65, 2, 1),
(259, 65, 4, 1),
(260, 65, 1, 1),
(261, 66, 3, 1),
(262, 66, 2, 1),
(263, 66, 4, 1),
(264, 66, 1, 1),
(265, 67, 3, 1),
(266, 67, 2, 1),
(267, 67, 4, 1),
(268, 67, 1, 1),
(269, 68, 3, 1),
(270, 68, 2, 1),
(271, 68, 4, 1),
(272, 68, 1, 1),
(273, 69, 3, 1),
(274, 69, 2, 1),
(275, 69, 4, 1),
(276, 69, 1, 1),
(277, 70, 3, 1),
(278, 70, 2, 1),
(279, 70, 4, 1),
(280, 70, 1, 1),
(281, 71, 3, 1),
(282, 71, 2, 1),
(283, 71, 4, 1),
(284, 71, 1, 1),
(285, 72, 3, 1),
(286, 72, 2, 1),
(287, 72, 4, 1),
(288, 72, 1, 1),
(289, 73, 3, 1),
(290, 73, 2, 1),
(291, 73, 4, 1),
(292, 73, 1, 1),
(293, 74, 3, 1),
(294, 74, 2, 1),
(295, 74, 4, 1),
(296, 74, 1, 1),
(297, 75, 3, 1),
(298, 75, 2, 1),
(299, 75, 4, 1),
(300, 75, 1, 1),
(301, 76, 3, 1),
(302, 76, 2, 1),
(303, 76, 4, 1),
(304, 76, 1, 1),
(305, 77, 3, 1),
(306, 77, 2, 1),
(307, 77, 4, 1),
(308, 77, 1, 1),
(309, 78, 3, 1),
(310, 78, 2, 1),
(311, 78, 4, 1),
(312, 78, 1, 1),
(313, 79, 3, 1),
(314, 79, 2, 1),
(315, 79, 4, 1),
(316, 79, 1, 1),
(317, 80, 3, 1),
(318, 80, 2, 1),
(319, 80, 4, 1),
(320, 80, 1, 1),
(321, 81, 3, 1),
(322, 81, 2, 1),
(323, 81, 4, 1),
(324, 81, 1, 1),
(325, 82, 3, 1),
(326, 82, 2, 1),
(327, 82, 4, 1),
(328, 82, 1, 1),
(329, 83, 3, 1),
(330, 83, 2, 1),
(331, 83, 4, 1),
(332, 83, 1, 1),
(333, 84, 3, 1),
(334, 84, 2, 1),
(335, 84, 4, 1),
(336, 84, 1, 1),
(337, 85, 3, 1),
(338, 85, 2, 1),
(339, 85, 4, 1),
(340, 85, 1, 1),
(341, 86, 3, 1),
(342, 86, 2, 1),
(343, 86, 4, 1),
(344, 86, 1, 1),
(345, 87, 3, 1),
(346, 87, 2, 1),
(347, 87, 4, 1),
(348, 87, 1, 1),
(349, 88, 3, 1),
(350, 88, 2, 1),
(351, 88, 4, 1),
(352, 88, 1, 1),
(353, 89, 3, 1),
(354, 89, 2, 1),
(355, 89, 4, 1),
(356, 89, 1, 1),
(357, 90, 3, 1),
(358, 90, 2, 1),
(359, 90, 4, 1),
(360, 90, 1, 1),
(361, 91, 3, 1),
(362, 91, 2, 1),
(363, 91, 4, 1),
(364, 91, 1, 1),
(365, 92, 3, 1),
(366, 92, 2, 1),
(367, 92, 4, 1),
(368, 92, 1, 1),
(369, 93, 3, 1),
(370, 93, 2, 1),
(371, 93, 4, 1),
(372, 93, 1, 1),
(373, 94, 3, 1),
(374, 94, 2, 1),
(375, 94, 4, 1),
(376, 94, 1, 1),
(377, 95, 3, 1),
(378, 95, 2, 1),
(379, 95, 4, 1),
(380, 95, 1, 1),
(381, 96, 3, 1),
(382, 96, 2, 1),
(383, 96, 4, 1),
(384, 96, 1, 1),
(385, 97, 3, 1),
(386, 97, 2, 1),
(387, 97, 4, 1),
(388, 97, 1, 1),
(389, 98, 3, 1),
(390, 98, 2, 1),
(391, 98, 4, 1),
(392, 98, 1, 1),
(393, 99, 3, 1),
(394, 99, 2, 1),
(395, 99, 4, 1),
(396, 99, 1, 1),
(397, 100, 3, 1),
(398, 100, 2, 1),
(399, 100, 4, 1),
(400, 100, 1, 1),
(401, 101, 3, 1),
(402, 101, 2, 1),
(403, 101, 4, 1),
(404, 101, 1, 1),
(405, 102, 3, 1),
(406, 102, 2, 1),
(407, 102, 4, 1),
(408, 102, 1, 1),
(409, 103, 3, 1),
(410, 103, 2, 1),
(411, 103, 4, 1),
(412, 103, 1, 1),
(413, 104, 3, 1),
(414, 104, 2, 1),
(415, 104, 4, 1),
(416, 104, 1, 1),
(417, 105, 3, 1),
(418, 105, 2, 1),
(419, 105, 4, 1),
(420, 105, 1, 1),
(421, 106, 3, 1),
(422, 106, 2, 1),
(423, 106, 4, 1),
(424, 106, 1, 1),
(425, 107, 3, 1),
(426, 107, 2, 1),
(427, 107, 4, 1),
(428, 107, 1, 1),
(429, 108, 3, 1),
(430, 108, 2, 1),
(431, 108, 4, 1),
(432, 108, 1, 1),
(433, 109, 3, 1),
(434, 109, 2, 1),
(435, 109, 4, 1),
(436, 109, 1, 1),
(437, 110, 3, 1),
(438, 110, 2, 1),
(439, 110, 4, 1),
(440, 110, 1, 1),
(441, 111, 3, 1),
(442, 111, 2, 1),
(443, 111, 4, 1),
(444, 111, 1, 1),
(445, 112, 3, 1),
(446, 112, 2, 1),
(447, 112, 4, 1),
(448, 112, 1, 1),
(449, 113, 3, 1),
(450, 113, 2, 1),
(451, 113, 4, 1),
(452, 113, 1, 1),
(453, 114, 3, 1),
(454, 114, 2, 1),
(455, 114, 4, 1),
(456, 114, 1, 1),
(457, 115, 3, 1),
(458, 115, 2, 1),
(459, 115, 4, 1),
(460, 115, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `produce_boxes`
--

CREATE TABLE `produce_boxes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` json NOT NULL,
  `slug` json NOT NULL,
  `price` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produce_box_items`
--

CREATE TABLE `produce_box_items` (
  `id` bigint UNSIGNED NOT NULL,
  `produce_box_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(14,4) NOT NULL,
  `unit` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `name` json NOT NULL,
  `slug` json NOT NULL,
  `description` json DEFAULT NULL,
  `image_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_per_kg` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `price_per_piece` decimal(14,4) DEFAULT NULL,
  `sell_by_piece` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `track_stock` tinyint(1) NOT NULL DEFAULT '0',
  `stock_quantity` decimal(14,4) DEFAULT NULL,
  `view_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `image_url`, `image_path`, `sku`, `price_per_kg`, `price_per_piece`, `sell_by_piece`, `is_active`, `track_stock`, `stock_quantity`, `view_count`, `created_at`, `updated_at`) VALUES
(1, 1, '{\"ar\": \"تفاح\", \"en\": \"Apple\"}', '{\"ar\": \"apple\", \"en\": \"apple\"}', '{\"ar\": \"طازج ومختار بعناية — تفاح\", \"en\": \"Premium fresh Apple — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ9jYuu0QyR-7kTR-UZSPL7BOYDfIS2PE0gGnWDnrXaanj3VjInbXFKqbhrXnnAmRKM-zmHpQ_u3vN1EcTs2dO7dqDmO8avinXtLd-blQ4&s=10', NULL, 'FRU-001', '48.0000', '6.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 09:57:13'),
(2, 1, '{\"ar\": \"مشمش\", \"en\": \"Apricot\"}', '{\"ar\": \"apricot\", \"en\": \"apricot\"}', '{\"ar\": \"طازج ومختار بعناية — مشمش\", \"en\": \"Premium fresh Apricot — hand-selected for quality.\"}', 'https://upload.wikimedia.org/wikipedia/commons/2/2a/Apricot_and_cross_section.jpg', NULL, 'FRU-002', '72.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 09:58:16'),
(3, 1, '{\"ar\": \"أفوكادو\", \"en\": \"Avocado\"}', '{\"ar\": \"avocado\", \"en\": \"avocado\"}', '{\"ar\": \"طازج ومختار بعناية — أفوكادو\", \"en\": \"Premium fresh Avocado — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQZwvLtM_YN_GkQ8mnBmPfalWXgUBdojX3utm5vy6Bgjf03sIRMYIMKKP_oteRQiySv4APNVZlsO9aYalcDWGWKrhEw7tyN6wKJV6BvjDHd&s=10', NULL, 'FRU-003', '120.0000', '18.0000', 1, 1, 0, NULL, 3, '2026-05-12 09:30:49', '2026-06-14 10:06:52'),
(4, 1, '{\"ar\": \"موز\", \"en\": \"Banana\"}', '{\"ar\": \"banana\", \"en\": \"banana\"}', '{\"ar\": \"طازج ومختار بعناية — موز\", \"en\": \"Premium fresh Banana — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?auto=format&fit=crop&w=800&q=85', NULL, 'FRU-004', '32.0000', '4.5000', 1, 1, 0, NULL, 18, '2026-05-12 09:30:49', '2026-05-12 11:29:10'),
(5, 1, '{\"ar\": \"توت أسود\", \"en\": \"Blackberry\"}', '{\"ar\": \"blackberry\", \"en\": \"blackberry\"}', '{\"ar\": \"طازج ومختار بعناية — توت أسود\", \"en\": \"Premium fresh Blackberry — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0jiRVNQ1LRd_9GssVBcgghzFZbbvy9Q6D8fubhOI6Q0qPTyhxTGF7y_knV_1emPk46otW7u5xsbAeTlrAq07P3FZ29u_TH_5A-AOrZbdFjg&s=10', NULL, 'FRU-005', '95.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:14:22'),
(6, 1, '{\"ar\": \"توت أزرق\", \"en\": \"Blueberry\"}', '{\"ar\": \"blueberry\", \"en\": \"blueberry\"}', '{\"ar\": \"طازج ومختار بعناية — توت أزرق\", \"en\": \"Premium fresh Blueberry — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ4spM0Pn_Oojgi4RTQPt4kuRNA6SDGd2_AI52_f1JQO65u7QUGUjrnZX7eOxxtm_JfPtWvs7NOCh90bzrPqDrxOiKc5JRDwc1D3TJ1JIJX&s=10', NULL, 'FRU-006', '140.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:14:44'),
(7, 1, '{\"ar\": \"شمام\", \"en\": \"Cantaloupe\"}', '{\"ar\": \"cantaloupe\", \"en\": \"cantaloupe\"}', '{\"ar\": \"طازج ومختار بعناية — شمام\", \"en\": \"Premium fresh Cantaloupe — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTa0s_iGsRvvAqkFtP95lXjjoQpyYPaCD64kex7DUA7GXH_5-Ehv0ZJh6FE6U6uiL2vS1U1SDmhmYfsY_xPgv-AqDda_SBODhzjCHlaWwJj&s=10', NULL, 'FRU-007', '28.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:15:12'),
(8, 1, '{\"ar\": \"كرز\", \"en\": \"Cherry\"}', '{\"ar\": \"cherry\", \"en\": \"cherry\"}', '{\"ar\": \"طازج ومختار بعناية — كرز\", \"en\": \"Premium fresh Cherry — hand-selected for quality.\"}', 'https://www.sharbatlyfruit.com/Home/fruits-vegetable/1706/image-thumb__1706__commonThumbnail/Cherries%2C%20Sweet%20Heart_6.4a4d85eb.jpg', NULL, 'FRU-008', '180.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:23:55'),
(9, 1, '{\"ar\": \"جوز الهند\", \"en\": \"Coconut\"}', '{\"ar\": \"coconut\", \"en\": \"coconut\"}', '{\"ar\": \"طازج ومختار بعناية — جوز الهند\", \"en\": \"Premium fresh Coconut — hand-selected for quality.\"}', 'https://gourmetegypt.com/media/catalog/product/6/0/6044087000007.jpg?optimize=high&bg-color=255,255,255&fit=bounds&height=&width=', NULL, 'FRU-009', '45.0000', '35.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:25:16'),
(10, 1, '{\"ar\": \"تمر مجدول\", \"en\": \"Date (Medjool)\"}', '{\"ar\": \"date-medjool\", \"en\": \"date-medjool\"}', '{\"ar\": \"طازج ومختار بعناية — تمر مجدول\", \"en\": \"Premium fresh Date (Medjool) — hand-selected for quality.\"}', 'https://gulffruits.com/cdn/shop/files/0a017f8545f6ef42240f28fe2a5a1906.jpg?v=1740777484', NULL, 'FRU-010', '95.0000', '3.5000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:26:12'),
(11, 1, '{\"ar\": \"فاكهة التنين\", \"en\": \"Dragon fruit\"}', '{\"ar\": \"dragon-fruit\", \"en\": \"dragon-fruit\"}', '{\"ar\": \"طازج ومختار بعناية — فاكهة التنين\", \"en\": \"Premium fresh Dragon fruit — hand-selected for quality.\"}', 'https://upload.wikimedia.org/wikipedia/commons/4/43/Pitaya_cross_section_ed2.jpg', NULL, 'FRU-011', '85.0000', '22.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:26:40'),
(12, 1, '{\"ar\": \"تين\", \"en\": \"Fig\"}', '{\"ar\": \"fig\", \"en\": \"fig\"}', '{\"ar\": \"طازج ومختار بعناية — تين\", \"en\": \"Premium fresh Fig — hand-selected for quality.\"}', 'https://www.ok.org/wp-content/uploads/2025/02/OK-Newsletter-Shevat-5785-Email-Assets2.png', NULL, 'FRU-012', '65.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:27:15'),
(13, 1, '{\"ar\": \"جريب فروت\", \"en\": \"Grapefruit\"}', '{\"ar\": \"grapefruit\", \"en\": \"grapefruit\"}', '{\"ar\": \"طازج ومختار بعناية — جريب فروت\", \"en\": \"Premium fresh Grapefruit — hand-selected for quality.\"}', 'https://cdn.britannica.com/22/122522-050-6CD1C3E7/Grapefruit.jpg', NULL, 'FRU-013', '38.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:32:49'),
(14, 1, '{\"ar\": \"عنب أخضر\", \"en\": \"Grapes (green)\"}', '{\"ar\": \"grapes-green\", \"en\": \"grapes-green\"}', '{\"ar\": \"طازج ومختار بعناية — عنب أخضر\", \"en\": \"Premium fresh Grapes (green) — hand-selected for quality.\"}', 'https://www.freshpoint.com/wp-content/uploads/commodity-green-seedless-grapes.jpg', NULL, 'FRU-014', '42.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:36:53'),
(15, 1, '{\"ar\": \"عنب أحمر\", \"en\": \"Grapes (red)\"}', '{\"ar\": \"grapes-red\", \"en\": \"grapes-red\"}', '{\"ar\": \"طازج ومختار بعناية — عنب أحمر\", \"en\": \"Premium fresh Grapes (red) — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/sys-master-root/h1c/h60/64374933094430/304993_main.jpg', NULL, 'FRU-015', '44.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:37:21'),
(16, 1, '{\"ar\": \"جوافة\", \"en\": \"Guava\"}', '{\"ar\": \"guava\", \"en\": \"guava\"}', '{\"ar\": \"طازج ومختار بعناية — جوافة\", \"en\": \"Premium fresh Guava — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT48LZZjnYL4CFhrs7_S55-HWvW8JcusVEAog&s', NULL, 'FRU-016', '36.0000', '5.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:38:13'),
(17, 1, '{\"ar\": \"شمام أصفر\", \"en\": \"Honeydew melon\"}', '{\"ar\": \"honeydew-melon\", \"en\": \"honeydew-melon\"}', '{\"ar\": \"طازج ومختار بعناية — شمام أصفر\", \"en\": \"Premium fresh Honeydew melon — hand-selected for quality.\"}', 'https://upload.wikimedia.org/wikipedia/commons/f/f5/Honeydew.jpg', NULL, 'FRU-017', '26.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:38:57'),
(18, 1, '{\"ar\": \"كيوي\", \"en\": \"Kiwi\"}', '{\"ar\": \"kiwi\", \"en\": \"kiwi\"}', '{\"ar\": \"طازج ومختار بعناية — كيوي\", \"en\": \"Premium fresh Kiwi — hand-selected for quality.\"}', 'https://cdn.britannica.com/45/126445-050-4C0FA9F6/Kiwi-fruit.jpg', NULL, 'FRU-018', '88.0000', '7.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:40:36'),
(19, 1, '{\"ar\": \"ليمون\", \"en\": \"Lemon\"}', '{\"ar\": \"lemon\", \"en\": \"lemon\"}', '{\"ar\": \"طازج ومختار بعناية — ليمون\", \"en\": \"Premium fresh Lemon — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSobc25m5lawAhXCxYNjaUcmqf3fsYEK3thMA&s', NULL, 'FRU-019', '34.0000', '2.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:41:25'),
(20, 1, '{\"ar\": \"ليم\", \"en\": \"Lime\"}', '{\"ar\": \"lime\", \"en\": \"lime\"}', '{\"ar\": \"طازج ومختار بعناية — ليم\", \"en\": \"Premium fresh Lime — hand-selected for quality.\"}', 'https://static.wikia.nocookie.net/fruit/images/4/47/Lime.webp/revision/latest?cb=20241130001344', NULL, 'FRU-020', '40.0000', '1.5000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:41:57'),
(21, 1, '{\"ar\": \"ليتشي\", \"en\": \"Lychee\"}', '{\"ar\": \"lychee\", \"en\": \"lychee\"}', '{\"ar\": \"طازج ومختار بعناية — ليتشي\", \"en\": \"Premium fresh Lychee — hand-selected for quality.\"}', 'https://cdn.britannica.com/18/176518-050-5AB1E61D/lychee-fruits-Southeast-Asia.jpg', NULL, 'FRU-021', '110.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:42:24'),
(22, 1, '{\"ar\": \"مانجو\", \"en\": \"Mango\"}', '{\"ar\": \"mango\", \"en\": \"mango\"}', '{\"ar\": \"طازج ومختار بعناية — مانجو\", \"en\": \"Premium fresh Mango — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcREGDjC4KwrzujdpzZeiEyEWd3jsGOUqyjAPw&s', NULL, 'FRU-022', '55.0000', '12.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:46:11'),
(23, 1, '{\"ar\": \"نكتارين\", \"en\": \"Nectarine\"}', '{\"ar\": \"nectarine\", \"en\": \"nectarine\"}', '{\"ar\": \"طازج ومختار بعناية — نكتارين\", \"en\": \"Premium fresh Nectarine — hand-selected for quality.\"}', 'https://fruitique.in/cdn/shop/products/Nectarine_1_1024x1024.jpg?v=1631554326', NULL, 'FRU-023', '58.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:46:40'),
(24, 1, '{\"ar\": \"برتقال\", \"en\": \"Orange\"}', '{\"ar\": \"orange\", \"en\": \"orange\"}', '{\"ar\": \"طازج ومختار بعناية — برتقال\", \"en\": \"Premium fresh Orange — hand-selected for quality.\"}', 'https://www.quanta.org/orange/orange.jpg', NULL, 'FRU-024', '28.0000', '3.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:47:09'),
(25, 1, '{\"ar\": \"بابايا\", \"en\": \"Papaya\"}', '{\"ar\": \"papaya\", \"en\": \"papaya\"}', '{\"ar\": \"طازج ومختار بعناية — بابايا\", \"en\": \"Premium fresh Papaya — hand-selected for quality.\"}', 'https://exoticfruits.co.uk/cdn/shop/products/papaya-exoticfruitscouk-814405.jpg?v=1645488849', NULL, 'FRU-025', '42.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:47:59'),
(26, 1, '{\"ar\": \"ماراكوجا\", \"en\": \"Passion fruit\"}', '{\"ar\": \"passion-fruit\", \"en\": \"passion-fruit\"}', '{\"ar\": \"طازج ومختار بعناية — ماراكوجا\", \"en\": \"Premium fresh Passion fruit — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSPl7DYxxSgQgCSs1ZstCk-GzVNMtuF3rufEw&s', NULL, 'FRU-026', '95.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 10:48:48'),
(27, 1, '{\"ar\": \"خوخ\", \"en\": \"Peach\"}', '{\"ar\": \"peach\", \"en\": \"peach\"}', '{\"ar\": \"طازج ومختار بعناية — خوخ\", \"en\": \"Premium fresh Peach — hand-selected for quality.\"}', 'https://www.expoegypt.gov.eg/uploads/2024/03/65e88367a3c7f.jpeg', NULL, 'FRU-027', '52.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:16:36'),
(28, 1, '{\"ar\": \"كمثرى\", \"en\": \"Pear\"}', '{\"ar\": \"pear\", \"en\": \"pear\"}', '{\"ar\": \"طازج ومختار بعناية — كمثرى\", \"en\": \"Premium fresh Pear — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/sys-master-root/h16/h09/13179258208286/157806_main.jpg?im=Resize=376', NULL, 'FRU-028', '46.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:17:34'),
(29, 1, '{\"ar\": \"كاكي\", \"en\": \"Persimmon\"}', '{\"ar\": \"persimmon\", \"en\": \"persimmon\"}', '{\"ar\": \"طازج ومختار بعناية — كاكي\", \"en\": \"Premium fresh Persimmon — hand-selected for quality.\"}', 'https://www.kikkoman.com/en/culture/foodforum/japanese-style/img/35-2_im01.jpg', NULL, 'FRU-029', '70.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:18:17'),
(30, 1, '{\"ar\": \"أناناس\", \"en\": \"Pineapple\"}', '{\"ar\": \"pineapple\", \"en\": \"pineapple\"}', '{\"ar\": \"طازج ومختار بعناية — أناناس\", \"en\": \"Premium fresh Pineapple — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?auto=format&fit=crop&w=800&q=85', NULL, 'FRU-030', '38.0000', '45.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(31, 1, '{\"ar\": \"برقوق\", \"en\": \"Plum\"}', '{\"ar\": \"plum\", \"en\": \"plum\"}', '{\"ar\": \"طازج ومختار بعناية — برقوق\", \"en\": \"Premium fresh Plum — hand-selected for quality.\"}', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/Plums_African_Rose_-_whole%2C_halved_and_slice.jpg/1280px-Plums_African_Rose_-_whole%2C_halved_and_slice.jpg', NULL, 'FRU-031', '54.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:39:20'),
(32, 1, '{\"ar\": \"رمان\", \"en\": \"Pomegranate\"}', '{\"ar\": \"pomegranate\", \"en\": \"pomegranate\"}', '{\"ar\": \"طازج ومختار بعناية — رمان\", \"en\": \"Premium fresh Pomegranate — hand-selected for quality.\"}', 'https://www.expoegypt.gov.eg/uploads/2019/10/5da9fa6c98ed5.jpg', NULL, 'FRU-032', '62.0000', '15.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:40:20'),
(33, 1, '{\"ar\": \"سفرجل\", \"en\": \"Quince\"}', '{\"ar\": \"quince\", \"en\": \"quince\"}', '{\"ar\": \"طازج ومختار بعناية — سفرجل\", \"en\": \"Premium fresh Quince — hand-selected for quality.\"}', 'https://static.libertyprim.com/files/familles/coing-large.jpg?1569271744', NULL, 'FRU-033', '48.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:40:48'),
(34, 1, '{\"ar\": \"توت العليق\", \"en\": \"Raspberry\"}', '{\"ar\": \"raspberry\", \"en\": \"raspberry\"}', '{\"ar\": \"طازج ومختار بعناية — توت العليق\", \"en\": \"Premium fresh Raspberry — hand-selected for quality.\"}', 'https://i0.wp.com/nefertoot.com/wp-content/uploads/2023/01/90556584-e874-4838-a364-f8ad8017a5a8-removebg-preview.png?fit=530%2C470&ssl=1', NULL, 'FRU-034', '125.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:41:10'),
(35, 1, '{\"ar\": \"تفاح أحمر\", \"en\": \"Red apple\"}', '{\"ar\": \"red-apple\", \"en\": \"red-apple\"}', '{\"ar\": \"طازج ومختار بعناية — تفاح أحمر\", \"en\": \"Premium fresh Red apple — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?auto=format&fit=crop&w=800&q=85', NULL, 'FRU-035', '50.0000', '6.5000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(36, 1, '{\"ar\": \"فراولة\", \"en\": \"Strawberry\"}', '{\"ar\": \"strawberry\", \"en\": \"strawberry\"}', '{\"ar\": \"طازج ومختار بعناية — فراولة\", \"en\": \"Premium fresh Strawberry — hand-selected for quality.\"}', 'https://mcprod.spinneys-egypt.com/media/catalog/product/cache/74c1057f7991b4edb2bc7bdaa94de933/3/4/342076_ihs7wuedjowgbj3c.jpg', NULL, 'FRU-036', '75.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:42:05'),
(37, 1, '{\"ar\": \"يوسفي\", \"en\": \"Tangerine\"}', '{\"ar\": \"tangerine\", \"en\": \"tangerine\"}', '{\"ar\": \"طازج ومختار بعناية — يوسفي\", \"en\": \"Premium fresh Tangerine — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqoTOERqMiQxMvHkiW7XecB_CWs3dcVVxsTA&s', NULL, 'FRU-037', '30.0000', '2.5000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:42:37'),
(38, 1, '{\"ar\": \"بطيخ\", \"en\": \"Watermelon\"}', '{\"ar\": \"watermelon\", \"en\": \"watermelon\"}', '{\"ar\": \"طازج ومختار بعناية — بطيخ\", \"en\": \"Premium fresh Watermelon — hand-selected for quality.\"}', 'https://weresmartworld.com/sites/default/files/styles/full_screen/public/2021-04/watermeloen_2.jpg?itok=CCYHLr5M', NULL, 'FRU-038', '12.0000', '35.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:45:35'),
(39, 1, '{\"ar\": \"تفاح أخضر\", \"en\": \"Green apple\"}', '{\"ar\": \"green-apple\", \"en\": \"green-apple\"}', '{\"ar\": \"طازج ومختار بعناية — تفاح أخضر\", \"en\": \"Premium fresh Green apple — hand-selected for quality.\"}', 'https://egyptianwatermelon.com/wp-content/uploads/2026/01/%D8%AA%D9%81%D8%A7%D8%AD-%D8%A3%D8%AE%D8%B6%D8%B1-%D8%A5%D9%8A%D8%B7%D8%A7%D9%84%D9%8A-%D9%85%D8%B3%D8%AA%D9%88%D8%B1%D8%AF.png', NULL, 'FRU-039', '52.0000', '6.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:46:17'),
(40, 1, '{\"ar\": \"تفاح جالا\", \"en\": \"Gala apple\"}', '{\"ar\": \"gala-apple\", \"en\": \"gala-apple\"}', '{\"ar\": \"طازج ومختار بعناية — تفاح جالا\", \"en\": \"Premium fresh Gala apple — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/sys-master-root/hcd/h0f/26615195893790/387719_main.jpg', NULL, 'FRU-040', '54.0000', '6.5000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:46:45'),
(41, 1, '{\"ar\": \"كمثرى وليامز\", \"en\": \"Williams pear\"}', '{\"ar\": \"williams-pear\", \"en\": \"williams-pear\"}', '{\"ar\": \"طازج ومختار بعناية — كمثرى وليامز\", \"en\": \"Premium fresh Williams pear — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ4cSfImUJwEaUmhTHiCFm2GpsYoEqmD5gXDQ&s', NULL, 'FRU-041', '48.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:47:14'),
(42, 1, '{\"ar\": \"عنب بدون بذور\", \"en\": \"Seedless grapes\"}', '{\"ar\": \"seedless-grapes\", \"en\": \"seedless-grapes\"}', '{\"ar\": \"طازج ومختار بعناية — عنب بدون بذور\", \"en\": \"Premium fresh Seedless grapes — hand-selected for quality.\"}', 'https://www.freshpoint.com/wp-content/uploads/commodity-green-seedless-grapes.jpg', NULL, 'FRU-042', '46.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:48:31'),
(43, 1, '{\"ar\": \"شمام جاليا\", \"en\": \"Melon (Galia)\"}', '{\"ar\": \"melon-galia\", \"en\": \"melon-galia\"}', '{\"ar\": \"طازج ومختار بعناية — شمام جاليا\", \"en\": \"Premium fresh Melon (Galia) — hand-selected for quality.\"}', 'https://visuals.rijkzwaan.com/transform/PRODUCT_LARGE/3638b95d-addd-40e6-a341-e84085778bc3/', NULL, 'FRU-043', '30.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:49:43'),
(44, 2, '{\"ar\": \"خرشوف\", \"en\": \"Artichoke\"}', '{\"ar\": \"artichoke\", \"en\": \"artichoke\"}', '{\"ar\": \"طازج ومختار بعناية — خرشوف\", \"en\": \"Premium fresh Artichoke — hand-selected for quality.\"}', 'https://cdn.alweb.com/thumbs/knowyourmeals/article/fit710x532/%D9%85%D8%A7-%D9%87%D9%88-%D8%A7%D9%84%D8%AE%D8%B1%D8%B4%D9%88%D9%81-%D9%88%D9%85%D8%A7-%D8%A3%D9%87%D9%85-%D9%81%D9%88%D8%A7%D8%A6%D8%AF%D9%87-%D9%88%D9%85%D8%AD%D8%A7%D8%B0%D9%8A%D8%B1-%D8%A7%D8%B3%D8%AA%D9%87%D9%84%D8%A7%D9%83%D9%87.jpg', NULL, 'VEG-101', '55.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:50:45'),
(45, 2, '{\"ar\": \"هليون\", \"en\": \"Asparagus\"}', '{\"ar\": \"asparagus\", \"en\": \"asparagus\"}', '{\"ar\": \"طازج ومختار بعناية — هليون\", \"en\": \"Premium fresh Asparagus — hand-selected for quality.\"}', 'https://gourmetegypt.com/media/catalog/product/6/0/6044890000003.jpg?optimize=high&bg-color=255,255,255&fit=bounds&height=&width=', NULL, 'VEG-102', '120.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:51:53'),
(46, 2, '{\"ar\": \"سبانخ صغيرة\", \"en\": \"Baby spinach\"}', '{\"ar\": \"baby-spinach\", \"en\": \"baby-spinach\"}', '{\"ar\": \"طازج ومختار بعناية — سبانخ صغيرة\", \"en\": \"Premium fresh Baby spinach — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/sys-master-root/h8c/h88/28721214586910/583803_main.jpg', NULL, 'VEG-103', '38.0000', '15.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 11:52:28'),
(47, 2, '{\"ar\": \"شمندر\", \"en\": \"Beetroot\"}', '{\"ar\": \"beetroot\", \"en\": \"beetroot\"}', '{\"ar\": \"طازج ومختار بعناية — شمندر\", \"en\": \"Premium fresh Beetroot — hand-selected for quality.\"}', 'https://smartyield.in/wp-content/uploads/2021/06/Beetroot.png', NULL, 'VEG-104', '22.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:05:01'),
(48, 2, '{\"ar\": \"فلفل حلو أخضر\", \"en\": \"Bell pepper (green)\"}', '{\"ar\": \"bell-pepper-green\", \"en\": \"bell-pepper-green\"}', '{\"ar\": \"طازج ومختار بعناية — فلفل حلو أخضر\", \"en\": \"Premium fresh Bell pepper (green) — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQo1hoPaUye9qaISKANRwL0mJvr4tzlQm_6uA&s', NULL, 'VEG-105', '36.0000', '4.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:05:55'),
(49, 2, '{\"ar\": \"فلفل حلو أحمر\", \"en\": \"Bell pepper (red)\"}', '{\"ar\": \"bell-pepper-red\", \"en\": \"bell-pepper-red\"}', '{\"ar\": \"طازج ومختار بعناية — فلفل حلو أحمر\", \"en\": \"Premium fresh Bell pepper (red) — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/sys-master-root/h3b/hef/35139576954910/32615_main.jpg', NULL, 'VEG-106', '42.0000', '5.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:06:56'),
(50, 2, '{\"ar\": \"فلفل حلو أصفر\", \"en\": \"Bell pepper (yellow)\"}', '{\"ar\": \"bell-pepper-yellow\", \"en\": \"bell-pepper-yellow\"}', '{\"ar\": \"طازج ومختار بعناية — فلفل حلو أصفر\", \"en\": \"Premium fresh Bell pepper (yellow) — hand-selected for quality.\"}', 'https://pepperjoe.com/cdn/shop/products/YellowBellPepper.jpg?v=1646926510&width=1445', NULL, 'VEG-107', '44.0000', '5.5000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:08:05'),
(51, 2, '{\"ar\": \"بروكلي\", \"en\": \"Broccoli\"}', '{\"ar\": \"broccoli\", \"en\": \"broccoli\"}', '{\"ar\": \"طازج ومختار بعناية — بروكلي\", \"en\": \"Premium fresh Broccoli — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1584270354949-c26b0d5b4a0c?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-108', '48.0000', '18.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(52, 2, '{\"ar\": \"كرنب بروكسيل\", \"en\": \"Brussels sprouts\"}', '{\"ar\": \"brussels-sprouts\", \"en\": \"brussels-sprouts\"}', '{\"ar\": \"طازج ومختار بعناية — كرنب بروكسيل\", \"en\": \"Premium fresh Brussels sprouts — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRFUHFXJCNVGooJlyhWo4CTbGiFAiufeQXMCw&s', NULL, 'VEG-109', '58.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:08:59'),
(53, 2, '{\"ar\": \"قرع عسلي\", \"en\": \"Butternut squash\"}', '{\"ar\": \"butternut-squash\", \"en\": \"butternut-squash\"}', '{\"ar\": \"طازج ومختار بعناية — قرع عسلي\", \"en\": \"Premium fresh Butternut squash — hand-selected for quality.\"}', 'https://www.rhubarbarians.com/wp-content/uploads/2019/12/Butternut-squash-web1-683x1024.jpg', NULL, 'VEG-110', '24.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:09:34'),
(54, 2, '{\"ar\": \"كرنب أخضر\", \"en\": \"Cabbage (green)\"}', '{\"ar\": \"cabbage-green\", \"en\": \"cabbage-green\"}', '{\"ar\": \"طازج ومختار بعناية — كرنب أخضر\", \"en\": \"Premium fresh Cabbage (green) — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/sys-master-root/hd7/h23/9342436540446/32603_main.jpg', NULL, 'VEG-111', '16.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:10:22'),
(55, 2, '{\"ar\": \"كرنب أحمر\", \"en\": \"Cabbage (red)\"}', '{\"ar\": \"cabbage-red\", \"en\": \"cabbage-red\"}', '{\"ar\": \"طازج ومختار بعناية — كرنب أحمر\", \"en\": \"Premium fresh Cabbage (red) — hand-selected for quality.\"}', 'https://vid.alarabiya.net/images/2023/10/24/3550f941-c5dc-44a9-a44c-b6ea07c9d546/3550f941-c5dc-44a9-a44c-b6ea07c9d546_16x9_1200x676.PNG', NULL, 'VEG-112', '18.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:10:51'),
(56, 2, '{\"ar\": \"جزر\", \"en\": \"Carrot\"}', '{\"ar\": \"carrot\", \"en\": \"carrot\"}', '{\"ar\": \"طازج ومختار بعناية — جزر\", \"en\": \"Premium fresh Carrot — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-113', '18.0000', '12.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(57, 2, '{\"ar\": \"قرنبيط\", \"en\": \"Cauliflower\"}', '{\"ar\": \"cauliflower\", \"en\": \"cauliflower\"}', '{\"ar\": \"طازج ومختار بعناية — قرنبيط\", \"en\": \"Premium fresh Cauliflower — hand-selected for quality.\"}', 'https://upload.wikimedia.org/wikipedia/commons/2/2f/Chou-fleur_02.jpg', NULL, 'VEG-114', '28.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 13:05:53'),
(58, 2, '{\"ar\": \"كرفس\", \"en\": \"Celery\"}', '{\"ar\": \"celery\", \"en\": \"celery\"}', '{\"ar\": \"طازج ومختار بعناية — كرفس\", \"en\": \"Premium fresh Celery — hand-selected for quality.\"}', 'https://images.immediate.co.uk/production/volatile/sites/30/2020/02/Celery-stalks-and-leaves-7860193.jpg?quality=90&resize=700,636', NULL, 'VEG-115', '32.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:48:22'),
(59, 2, '{\"ar\": \"سلق\", \"en\": \"Chard\"}', '{\"ar\": \"chard\", \"en\": \"chard\"}', '{\"ar\": \"طازج ومختار بعناية — سلق\", \"en\": \"Premium fresh Chard — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/sys-master-root/h7f/had/9342417764382/33241_main.jpg?im=Resize=376', NULL, 'VEG-116', '26.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:48:55'),
(60, 2, '{\"ar\": \"فلفل حار\", \"en\": \"Chili pepper (hot)\"}', '{\"ar\": \"chili-pepper-hot\", \"en\": \"chili-pepper-hot\"}', '{\"ar\": \"طازج ومختار بعناية — فلفل حار\", \"en\": \"Premium fresh Chili pepper (hot) — hand-selected for quality.\"}', 'https://cdn.mos.cms.futurecdn.net/3arbJYmatsPrWcrCX8cdVc.jpg', NULL, 'VEG-117', '40.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:52:15'),
(61, 2, '{\"ar\": \"ثوم معمر\", \"en\": \"Chives\"}', '{\"ar\": \"chives\", \"en\": \"chives\"}', '{\"ar\": \"طازج ومختار بعناية — ثوم معمر\", \"en\": \"Premium fresh Chives — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQK_7-rvBfvbWdv8yUm33QTJU2-26WARl-HWQ&s', NULL, 'VEG-118', '45.0000', '8.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:52:43'),
(62, 2, '{\"ar\": \"كزبرة\", \"en\": \"Cilantro (coriander)\"}', '{\"ar\": \"cilantro-coriander\", \"en\": \"cilantro-coriander\"}', '{\"ar\": \"طازج ومختار بعناية — كزبرة\", \"en\": \"Premium fresh Cilantro (coriander) — hand-selected for quality.\"}', 'https://www.vedonic.com/cdn/shop/products/1zyEFCLRsb9FwMpU6eE1WihGEvKDeDGBV_1024x1024.jpg?v=1659535880', NULL, 'VEG-119', '35.0000', '5.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:53:31'),
(63, 2, '{\"ar\": \"ذرة حلوة\", \"en\": \"Corn (sweet)\"}', '{\"ar\": \"corn-sweet\", \"en\": \"corn-sweet\"}', '{\"ar\": \"طازج ومختار بعناية — ذرة حلوة\", \"en\": \"Premium fresh Corn (sweet) — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/sys-master-root/h24/hc2/11007946784798/33185_main.jpg', NULL, 'VEG-120', '20.0000', '8.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:53:56'),
(64, 2, '{\"ar\": \"خيار\", \"en\": \"Cucumber\"}', '{\"ar\": \"cucumber\", \"en\": \"cucumber\"}', '{\"ar\": \"طازج ومختار بعناية — خيار\", \"en\": \"Premium fresh Cucumber — hand-selected for quality.\"}', 'https://www.freshpoint.com/wp-content/uploads/2020/02/freshpoint-english-cucumber-scaled.jpg', NULL, 'VEG-121', '22.0000', '3.5000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:54:28'),
(65, 2, '{\"ar\": \"كيل مجعد\", \"en\": \"Curly kale\"}', '{\"ar\": \"curly-kale\", \"en\": \"curly-kale\"}', '{\"ar\": \"طازج ومختار بعناية — كيل مجعد\", \"en\": \"Premium fresh Curly kale — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ9nIU-DCMATriCSXm_vdk8XDePK-KiZFKPWw&s', NULL, 'VEG-122', '42.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:55:13'),
(66, 2, '{\"ar\": \"شبت\", \"en\": \"Dill\"}', '{\"ar\": \"dill\", \"en\": \"dill\"}', '{\"ar\": \"طازج ومختار بعناية — شبت\", \"en\": \"Premium fresh Dill — hand-selected for quality.\"}', 'https://gourmetegypt.com/media/catalog/product/6/0/6044123000008_1.jpg?optimize=high&bg-color=255,255,255&fit=bounds&height=&width=', NULL, 'VEG-123', '40.0000', '6.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:55:48'),
(67, 2, '{\"ar\": \"باذنجان\", \"en\": \"Eggplant\"}', '{\"ar\": \"eggplant\", \"en\": \"eggplant\"}', '{\"ar\": \"طازج ومختار بعناية — باذنجان\", \"en\": \"Premium fresh Eggplant — hand-selected for quality.\"}', 'https://www.veggycation.com.au/siteassets/veggycationvegetable/eggplant.jpg', NULL, 'VEG-124', '24.0000', '4.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:56:20'),
(68, 2, '{\"ar\": \"شمر\", \"en\": \"Fennel\"}', '{\"ar\": \"fennel\", \"en\": \"fennel\"}', '{\"ar\": \"طازج ومختار بعناية — شمر\", \"en\": \"Premium fresh Fennel — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/sys-master-root/h2a/hec/35139576889374/33238_main.jpg?im=Resize=376', NULL, 'VEG-125', '34.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:57:04'),
(69, 2, '{\"ar\": \"ثوم\", \"en\": \"Garlic\"}', '{\"ar\": \"garlic\", \"en\": \"garlic\"}', '{\"ar\": \"طازج ومختار بعناية — ثوم\", \"en\": \"Premium fresh Garlic — hand-selected for quality.\"}', 'https://gourmetegypt.com/media/catalog/product/6/0/6027766000000_1.jpg?optimize=high&bg-color=255,255,255&fit=bounds&height=&width=', NULL, 'VEG-126', '55.0000', '2.5000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:57:31'),
(70, 2, '{\"ar\": \"زنجبيل\", \"en\": \"Ginger\"}', '{\"ar\": \"ginger\", \"en\": \"ginger\"}', '{\"ar\": \"طازج ومختار بعناية — زنجبيل\", \"en\": \"Premium fresh Ginger — hand-selected for quality.\"}', 'https://www.sharbatlyfruit.com/Home/fruits-vegetable/1724/image-thumb__1724__commonThumbnail/ginger%20Mango_1.0427e07a.jpg', NULL, 'VEG-127', '85.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 12:57:57'),
(71, 2, '{\"ar\": \"فاصوليا خضراء\", \"en\": \"Green beans\"}', '{\"ar\": \"green-beans\", \"en\": \"green-beans\"}', '{\"ar\": \"طازج ومختار بعناية — فاصوليا خضراء\", \"en\": \"Premium fresh Green beans — hand-selected for quality.\"}', 'https://media.gemini.media/img/large/2026/3/28/2026_3_28_14_41_2_4.webp', NULL, 'VEG-128', '38.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 13:04:39'),
(72, 2, '{\"ar\": \"خس آيسبرغ\", \"en\": \"Iceberg lettuce\"}', '{\"ar\": \"iceberg-lettuce\", \"en\": \"iceberg-lettuce\"}', '{\"ar\": \"طازج ومختار بعناية — خس آيسبرغ\", \"en\": \"Premium fresh Iceberg lettuce — hand-selected for quality.\"}', 'https://cdn.altibbi.com/cdn/cache/1000x500/image/2022/09/12/94dc7f84570b6830ac045d746e90828b.jpg.webp', NULL, 'VEG-129', '28.0000', '22.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 13:05:03'),
(73, 2, '{\"ar\": \"كيل\", \"en\": \"Kale\"}', '{\"ar\": \"kale\", \"en\": \"kale\"}', '{\"ar\": \"طازج ومختار بعناية — كيل\", \"en\": \"Premium fresh Kale — hand-selected for quality.\"}', 'https://cdn.salla.sa/gVzwa/d5901bc5-8a65-4771-b4da-32458d297a4d-1000x1000-0h5tqzIYogRzCffMeEGliN2D6mHFNxjBdvQxM9Ia.png', NULL, 'VEG-130', '40.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 15:29:59'),
(74, 2, '{\"ar\": \"كراث\", \"en\": \"Leek\"}', '{\"ar\": \"leek\", \"en\": \"leek\"}', '{\"ar\": \"طازج ومختار بعناية — كراث\", \"en\": \"Premium fresh Leek — hand-selected for quality.\"}', 'https://images.cookforyourlife.org/wp-content/uploads/2018/08/shutterstock_234785131-min.jpg', NULL, 'VEG-131', '30.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 15:47:25'),
(75, 2, '{\"ar\": \"خس رومي\", \"en\": \"Lettuce (romaine)\"}', '{\"ar\": \"lettuce-romaine\", \"en\": \"lettuce-romaine\"}', '{\"ar\": \"طازج ومختار بعناية — خس رومي\", \"en\": \"Premium fresh Lettuce (romaine) — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQA6tkrXwPTAdv9325vb1jiDJX3FO04o1ZYag&s', NULL, 'VEG-132', '26.0000', '15.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 15:50:28'),
(76, 2, '{\"ar\": \"نعناع\", \"en\": \"Mint\"}', '{\"ar\": \"mint\", \"en\": \"mint\"}', '{\"ar\": \"طازج ومختار بعناية — نعناع\", \"en\": \"Premium fresh Mint — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/pim-content/EGY/media/product/649578/1741071604/649578_main.jpg', NULL, 'VEG-133', '38.0000', '6.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 15:53:58'),
(77, 2, '{\"ar\": \"فطر أبيض\", \"en\": \"Mushroom (white)\"}', '{\"ar\": \"mushroom-white\", \"en\": \"mushroom-white\"}', '{\"ar\": \"طازج ومختار بعناية — فطر أبيض\", \"en\": \"Premium fresh Mushroom (white) — hand-selected for quality.\"}', 'https://gourmetegypt.com/media/catalog/product/6/0/6046270000009_1.jpg?optimize=high&bg-color=255,255,255&fit=bounds&height=&width=', NULL, 'VEG-134', '95.0000', '12.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 15:54:36'),
(78, 2, '{\"ar\": \"بامية\", \"en\": \"Okra\"}', '{\"ar\": \"okra\", \"en\": \"okra\"}', '{\"ar\": \"طازج ومختار بعناية — بامية\", \"en\": \"Premium fresh Okra — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTjmM5iss4Z0kO8VpN42biEYwFIKVd4o7l5vw&s', NULL, 'VEG-135', '45.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 15:55:18'),
(79, 2, '{\"ar\": \"بصل أحمر\", \"en\": \"Onion (red)\"}', '{\"ar\": \"onion-red\", \"en\": \"onion-red\"}', '{\"ar\": \"طازج ومختار بعناية — بصل أحمر\", \"en\": \"Premium fresh Onion (red) — hand-selected for quality.\"}', 'https://chefsmandala.com/wp-content/uploads/2018/03/Onion-Red.jpg', NULL, 'VEG-136', '16.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 15:55:59'),
(80, 2, '{\"ar\": \"بصل أبيض\", \"en\": \"Onion (white)\"}', '{\"ar\": \"onion-white\", \"en\": \"onion-white\"}', '{\"ar\": \"طازج ومختار بعناية — بصل أبيض\", \"en\": \"Premium fresh Onion (white) — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/sys-master-root/h08/h5d/14257452646430/32763_main.jpg', NULL, 'VEG-137', '15.0000', NULL, 0, 1, 0, NULL, 1, '2026-05-12 09:30:49', '2026-06-14 15:58:49'),
(81, 2, '{\"ar\": \"بصل أصفر\", \"en\": \"Onion (yellow)\"}', '{\"ar\": \"onion-yellow\", \"en\": \"onion-yellow\"}', '{\"ar\": \"طازج ومختار بعناية — بصل أصفر\", \"en\": \"Premium fresh Onion (yellow) — hand-selected for quality.\"}', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSiEeMGt3tBu92MUSGvlpCjX4D27u-pA1Z9dA&s', NULL, 'VEG-138', '15.5000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 16:00:42'),
(82, 2, '{\"ar\": \"بقدونس\", \"en\": \"Parsley\"}', '{\"ar\": \"parsley\", \"en\": \"parsley\"}', '{\"ar\": \"طازج ومختار بعناية — بقدونس\", \"en\": \"Premium fresh Parsley — hand-selected for quality.\"}', 'https://cdn.mafrservices.com/sys-master-root/h67/h61/10067335184414/430465_main.jpg', NULL, 'VEG-139', '32.0000', '5.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 16:04:23'),
(83, 2, '{\"ar\": \"بازلاء\", \"en\": \"Peas (green)\"}', '{\"ar\": \"peas-green\", \"en\": \"peas-green\"}', '{\"ar\": \"طازج ومختار بعناية — بازلاء\", \"en\": \"Premium fresh Peas (green) — hand-selected for quality.\"}', 'https://kidseatincolor.com/wp-content/uploads/2021/07/Green-Peas-1024x683.jpg', NULL, 'VEG-140', '42.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-06-14 16:04:59'),
(84, 2, '{\"ar\": \"بطاطس\", \"en\": \"Potato\"}', '{\"ar\": \"potato\", \"en\": \"potato\"}', '{\"ar\": \"طازج ومختار بعناية — بطاطس\", \"en\": \"Premium fresh Potato — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-141', '14.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(85, 2, '{\"ar\": \"يقطين\", \"en\": \"Pumpkin\"}', '{\"ar\": \"pumpkin\", \"en\": \"pumpkin\"}', '{\"ar\": \"طازج ومختار بعناية — يقطين\", \"en\": \"Premium fresh Pumpkin — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1590165482129-1aefb4e4a4f8?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-142', '18.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(86, 2, '{\"ar\": \"فجل\", \"en\": \"Radish\"}', '{\"ar\": \"radish\", \"en\": \"radish\"}', '{\"ar\": \"طازج ومختار بعناية — فجل\", \"en\": \"Premium fresh Radish — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1604977042234-1fb7b9a39e84?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-143', '24.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(87, 2, '{\"ar\": \"فلفل أحمر حار\", \"en\": \"Red chili\"}', '{\"ar\": \"red-chili\", \"en\": \"red-chili\"}', '{\"ar\": \"طازج ومختار بعناية — فلفل أحمر حار\", \"en\": \"Premium fresh Red chili — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-144', '36.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(88, 2, '{\"ar\": \"جرجير\", \"en\": \"Rocket (arugula)\"}', '{\"ar\": \"rocket-arugula\", \"en\": \"rocket-arugula\"}', '{\"ar\": \"طازج ومختار بعناية — جرجير\", \"en\": \"Premium fresh Rocket (arugula) — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1594282486552-05dee4de219e?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-145', '55.0000', '12.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(89, 2, '{\"ar\": \"قلوب خس رومي\", \"en\": \"Romaine hearts\"}', '{\"ar\": \"romaine-hearts\", \"en\": \"romaine-hearts\"}', '{\"ar\": \"طازج ومختار بعناية — قلوب خس رومي\", \"en\": \"Premium fresh Romaine hearts — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1587735243475-66f406351fcd?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-146', '32.0000', '18.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(90, 2, '{\"ar\": \"بصل أخضر\", \"en\": \"Scallions (spring onion)\"}', '{\"ar\": \"scallions-spring-onion\", \"en\": \"scallions-spring-onion\"}', '{\"ar\": \"طازج ومختار بعناية — بصل أخضر\", \"en\": \"Premium fresh Scallions (spring onion) — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-147', '28.0000', '8.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(91, 2, '{\"ar\": \"كراث صغير\", \"en\": \"Shallot\"}', '{\"ar\": \"shallot\", \"en\": \"shallot\"}', '{\"ar\": \"طازج ومختار بعناية — كراث صغير\", \"en\": \"Premium fresh Shallot — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1592419044706-39796d40fccc?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-148', '48.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(92, 2, '{\"ar\": \"سبانخ\", \"en\": \"Spinach\"}', '{\"ar\": \"spinach\", \"en\": \"spinach\"}', '{\"ar\": \"طازج ومختار بعناية — سبانخ\", \"en\": \"Premium fresh Spinach — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1590165482129-1aefb4e4a4f8?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-149', '30.0000', '10.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(93, 2, '{\"ar\": \"سلطة مشكل\", \"en\": \"Spring mix salad\"}', '{\"ar\": \"spring-mix-salad\", \"en\": \"spring-mix-salad\"}', '{\"ar\": \"طازج ومختار بعناية — سلطة مشكل\", \"en\": \"Premium fresh Spring mix salad — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1592417813508-3f16b1ead1cc?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-150', '42.0000', '18.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(94, 2, '{\"ar\": \"ذرة كاملة\", \"en\": \"Sweet corn cob\"}', '{\"ar\": \"sweet-corn-cob\", \"en\": \"sweet-corn-cob\"}', '{\"ar\": \"طازج ومختار بعناية — ذرة كاملة\", \"en\": \"Premium fresh Sweet corn cob — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-151', '12.0000', '6.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(95, 2, '{\"ar\": \"بطاطا حلوة\", \"en\": \"Sweet potato\"}', '{\"ar\": \"sweet-potato\", \"en\": \"sweet-potato\"}', '{\"ar\": \"طازج ومختار بعناية — بطاطا حلوة\", \"en\": \"Premium fresh Sweet potato — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1593113598334-c2882885f298?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-152', '22.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(96, 2, '{\"ar\": \"طماطم\", \"en\": \"Tomato\"}', '{\"ar\": \"tomato\", \"en\": \"tomato\"}', '{\"ar\": \"طازج ومختار بعناية — طماطم\", \"en\": \"Premium fresh Tomato — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-153', '20.0000', '3.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(97, 2, '{\"ar\": \"طماطم كرزية\", \"en\": \"Cherry tomatoes\"}', '{\"ar\": \"cherry-tomatoes\", \"en\": \"cherry-tomatoes\"}', '{\"ar\": \"طازج ومختار بعناية — طماطم كرزية\", \"en\": \"Premium fresh Cherry tomatoes — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1546094096-0df4bcaaa337?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-154', '36.0000', '14.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(98, 2, '{\"ar\": \"لفت\", \"en\": \"Turnip\"}', '{\"ar\": \"turnip\", \"en\": \"turnip\"}', '{\"ar\": \"طازج ومختار بعناية — لفت\", \"en\": \"Premium fresh Turnip — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1587049352846-4a222e70d878?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-155', '18.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(99, 2, '{\"ar\": \"كوسة\", \"en\": \"Zucchini\"}', '{\"ar\": \"zucchini\", \"en\": \"zucchini\"}', '{\"ar\": \"طازج ومختار بعناية — كوسة\", \"en\": \"Premium fresh Zucchini — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-156', '24.0000', '5.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(100, 2, '{\"ar\": \"ريحان\", \"en\": \"Basil\"}', '{\"ar\": \"basil\", \"en\": \"basil\"}', '{\"ar\": \"طازج ومختار بعناية — ريحان\", \"en\": \"Premium fresh Basil — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-157', '45.0000', '8.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(101, 2, '{\"ar\": \"براعم فاصوليا\", \"en\": \"Bean sprouts\"}', '{\"ar\": \"bean-sprouts\", \"en\": \"bean-sprouts\"}', '{\"ar\": \"طازج ومختار بعناية — براعم فاصوليا\", \"en\": \"Premium fresh Bean sprouts — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1594282486552-05dee4de219e?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-158', '28.0000', '10.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(102, 2, '{\"ar\": \"بوك تشوي\", \"en\": \"Bok choy\"}', '{\"ar\": \"bok-choy\", \"en\": \"bok-choy\"}', '{\"ar\": \"طازج ومختار بعناية — بوك تشوي\", \"en\": \"Premium fresh Bok choy — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-159', '38.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(103, 2, '{\"ar\": \"طماطم كرزية على العنقود\", \"en\": \"Cherry tomatoes on vine\"}', '{\"ar\": \"cherry-tomatoes-on-vine\", \"en\": \"cherry-tomatoes-on-vine\"}', '{\"ar\": \"طازج ومختار بعناية — طماطم كرزية على العنقود\", \"en\": \"Premium fresh Cherry tomatoes on vine — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1592419044706-39796d40fccc?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-160', '40.0000', '16.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(104, 2, '{\"ar\": \"كرنب أخضر مطبوخ\", \"en\": \"Collard greens\"}', '{\"ar\": \"collard-greens\", \"en\": \"collard-greens\"}', '{\"ar\": \"طازج ومختار بعناية — كرنب أخضر مطبوخ\", \"en\": \"Premium fresh Collard greens — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1595855709687-aabed89a6e2b?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-161', '22.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(105, 2, '{\"ar\": \"إدامامي\", \"en\": \"Edamame\"}', '{\"ar\": \"edamame\", \"en\": \"edamame\"}', '{\"ar\": \"طازج ومختار بعناية — إدامامي\", \"en\": \"Premium fresh Edamame — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1604977042234-1fb7b9a39e84?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-162', '65.0000', '18.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(106, 2, '{\"ar\": \"بازلاء مقشرة\", \"en\": \"Green peas (shelled)\"}', '{\"ar\": \"green-peas-shelled\", \"en\": \"green-peas-shelled\"}', '{\"ar\": \"طازج ومختار بعناية — بازلاء مقشرة\", \"en\": \"Premium fresh Green peas (shelled) — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-163', '48.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(107, 2, '{\"ar\": \"أرض شوكي\", \"en\": \"Jerusalem artichoke\"}', '{\"ar\": \"jerusalem-artichoke\", \"en\": \"jerusalem-artichoke\"}', '{\"ar\": \"طازج ومختار بعناية — أرض شوكي\", \"en\": \"Premium fresh Jerusalem artichoke — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-164', '52.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(108, 2, '{\"ar\": \"ميكروجرينز\", \"en\": \"Microgreens mix\"}', '{\"ar\": \"microgreens-mix\", \"en\": \"microgreens-mix\"}', '{\"ar\": \"طازج ومختار بعناية — ميكروجرينز\", \"en\": \"Premium fresh Microgreens mix — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1599599810769-bcde5a160d17?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-165', '85.0000', '20.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(109, 2, '{\"ar\": \"فطر بورتوبيلو\", \"en\": \"Portobello mushroom\"}', '{\"ar\": \"portobello-mushroom\", \"en\": \"portobello-mushroom\"}', '{\"ar\": \"طازج ومختار بعناية — فطر بورتوبيلو\", \"en\": \"Premium fresh Portobello mushroom — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-166', '110.0000', '15.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(110, 2, '{\"ar\": \"كرنب بنفسجي\", \"en\": \"Purple cabbage\"}', '{\"ar\": \"purple-cabbage\", \"en\": \"purple-cabbage\"}', '{\"ar\": \"طازج ومختار بعناية — كرنب بنفسجي\", \"en\": \"Premium fresh Purple cabbage — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1593113598334-c2882885f298?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-167', '20.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(111, 2, '{\"ar\": \"كرنب سافوي\", \"en\": \"Savoy cabbage\"}', '{\"ar\": \"savoy-cabbage\", \"en\": \"savoy-cabbage\"}', '{\"ar\": \"طازج ومختار بعناية — كرنب سافوي\", \"en\": \"Premium fresh Savoy cabbage — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1592419044706-39796d40fccc?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-168', '19.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(112, 2, '{\"ar\": \"بازلاء ثلجية\", \"en\": \"Snow peas\"}', '{\"ar\": \"snow-peas\", \"en\": \"snow-peas\"}', '{\"ar\": \"طازج ومختار بعناية — بازلاء ثلجية\", \"en\": \"Premium fresh Snow peas — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1587735243615-2d6d8a8b0e8a?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-169', '52.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(113, 2, '{\"ar\": \"بازلاء حلوة\", \"en\": \"Sugar snap peas\"}', '{\"ar\": \"sugar-snap-peas\", \"en\": \"sugar-snap-peas\"}', '{\"ar\": \"طازج ومختار بعناية — بازلاء حلوة\", \"en\": \"Premium fresh Sugar snap peas — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1592419044706-39796d40fccc?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-170', '54.0000', NULL, 0, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(114, 2, '{\"ar\": \"جرجير الماء\", \"en\": \"Watercress\"}', '{\"ar\": \"watercress\", \"en\": \"watercress\"}', '{\"ar\": \"طازج ومختار بعناية — جرجير الماء\", \"en\": \"Premium fresh Watercress — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-171', '48.0000', '10.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32'),
(115, 2, '{\"ar\": \"فطر أبيض\", \"en\": \"White mushroom\"}', '{\"ar\": \"white-mushroom\", \"en\": \"white-mushroom\"}', '{\"ar\": \"طازج ومختار بعناية — فطر أبيض\", \"en\": \"Premium fresh White mushroom — hand-selected for quality.\"}', 'https://images.unsplash.com/photo-1594282486552-05dee4de219e?auto=format&fit=crop&w=800&q=85', NULL, 'VEG-172', '88.0000', '11.0000', 1, 1, 0, NULL, 0, '2026-05-12 09:30:49', '2026-05-12 09:44:32');

-- --------------------------------------------------------

--
-- Table structure for table `product_views`
--

CREATE TABLE `product_views` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `session_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `visited_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2026-06-14 09:16:51', '2026-06-14 09:16:51'),
(2, 'orders_manager', 'web', '2026-06-14 09:16:59', '2026-06-14 09:16:59'),
(3, 'catalog_manager', 'web', '2026-06-14 09:16:59', '2026-06-14 09:16:59'),
(4, 'content_manager', 'web', '2026-06-14 09:17:00', '2026-06-14 09:17:00'),
(5, 'analytics_viewer', 'web', '2026-06-14 09:17:00', '2026-06-14 09:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 1),
(129, 1),
(130, 1),
(131, 1),
(132, 1),
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(150, 1),
(151, 1),
(152, 1),
(153, 1),
(154, 1),
(155, 1),
(156, 1),
(157, 1),
(158, 1),
(159, 1),
(160, 1),
(161, 1),
(162, 1),
(163, 1),
(164, 1),
(165, 1),
(166, 1),
(167, 1),
(168, 1),
(169, 1),
(170, 1),
(171, 1),
(172, 1),
(173, 1),
(174, 1),
(175, 1),
(176, 1),
(61, 2),
(62, 2),
(63, 2),
(64, 2),
(65, 2),
(66, 2),
(67, 2),
(68, 2),
(69, 2),
(70, 2),
(71, 2),
(72, 2),
(145, 2),
(146, 2),
(172, 2),
(173, 2),
(175, 2),
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(15, 3),
(16, 3),
(17, 3),
(18, 3),
(19, 3),
(20, 3),
(21, 3),
(22, 3),
(23, 3),
(24, 3),
(37, 3),
(38, 3),
(39, 3),
(40, 3),
(41, 3),
(42, 3),
(43, 3),
(44, 3),
(45, 3),
(46, 3),
(47, 3),
(48, 3),
(73, 3),
(74, 3),
(75, 3),
(76, 3),
(77, 3),
(78, 3),
(79, 3),
(80, 3),
(81, 3),
(82, 3),
(83, 3),
(84, 3),
(85, 3),
(86, 3),
(87, 3),
(88, 3),
(89, 3),
(90, 3),
(91, 3),
(92, 3),
(93, 3),
(94, 3),
(95, 3),
(96, 3),
(97, 3),
(98, 3),
(99, 3),
(100, 3),
(101, 3),
(102, 3),
(103, 3),
(104, 3),
(105, 3),
(106, 3),
(107, 3),
(108, 3),
(109, 3),
(110, 3),
(111, 3),
(112, 3),
(113, 3),
(114, 3),
(115, 3),
(116, 3),
(117, 3),
(118, 3),
(119, 3),
(120, 3),
(25, 4),
(26, 4),
(27, 4),
(28, 4),
(29, 4),
(30, 4),
(31, 4),
(32, 4),
(33, 4),
(34, 4),
(35, 4),
(36, 4),
(49, 4),
(50, 4),
(51, 4),
(52, 4),
(53, 4),
(54, 4),
(55, 4),
(56, 4),
(57, 4),
(58, 4),
(59, 4),
(60, 4),
(121, 4),
(122, 4),
(123, 4),
(124, 4),
(125, 4),
(126, 4),
(127, 4),
(128, 4),
(129, 4),
(130, 4),
(131, 4),
(132, 4),
(134, 5),
(169, 5),
(170, 5),
(171, 5),
(172, 5),
(174, 5),
(175, 5),
(176, 5);

-- --------------------------------------------------------

--
-- Table structure for table `seo_settings`
--

CREATE TABLE `seo_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `home_meta_title_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_meta_title_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_meta_description_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `home_meta_description_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shop_meta_title_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_meta_title_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_meta_description_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shop_meta_description_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `services_meta_title_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `services_meta_title_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `services_meta_description_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `services_meta_description_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `product_meta_title_suffix_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_meta_title_suffix_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seo_settings`
--

INSERT INTO `seo_settings` (`id`, `home_meta_title_en`, `home_meta_title_ar`, `home_meta_description_en`, `home_meta_description_ar`, `shop_meta_title_en`, `shop_meta_title_ar`, `shop_meta_description_en`, `shop_meta_description_ar`, `services_meta_title_en`, `services_meta_title_ar`, `services_meta_description_en`, `services_meta_description_ar`, `product_meta_title_suffix_en`, `product_meta_title_suffix_ar`, `og_image_url`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 11:03:50', '2026-05-12 11:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4BjwhlolpZscAMUa0cloVVPhWCYle1Nyy7PRob6K', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'eyJfdG9rZW4iOiJhdGY0NWxOSWdDVFFiYnJXUEttblk2Q0FKZk1Tckt2dGh1bk95VEtLIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvcHJvZHVjdHNcLzE2XC9lZGl0Iiwicm91dGUiOiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMucHJvZHVjdHMuZWRpdCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX3dlYiI6ImQwNmQ2YTQ1NjkzNmIyN2ZlYzBlZDJjOWY2YmYyNDMxOWJjNDI2MzI3YjU2MWQ2MmU0N2QwZmNiMTEwMWVkNTAiLCJ0YWJsZXMiOnsiOGZhYzZlYjFjZWMyNjgwM2IzZjdmYjQ0MGEyNzExMWJfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJkaXNwbGF5X2ltYWdlX3VybCIsImxhYmVsIjoiSW1hZ2UiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic2t1IiwibGFiZWwiOiJTa3UiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoibmFtZSIsImxhYmVsIjoiTmFtZSAoRU4pIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNhdGVnb3J5Lm5hbWUiLCJsYWJlbCI6IkNhdGVnb3J5IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InByaWNlX3Blcl9rZyIsImxhYmVsIjoiUHJpY2UgcGVyIGtnIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InNvbGRfa2dfdG90YWwiLCJsYWJlbCI6IlNvbGQgKGtnKSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOnRydWUsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6ZmFsc2V9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJ2aWV3X2NvdW50IiwibGFiZWwiOiJUb3RhbCB2aWV3cyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJ1bmlxdWVfdmlzaXRvcnNfY291bnQiLCJsYWJlbCI6IlVuaXF1ZSB2aXNpdG9ycyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJwcm9kdWN0X3ZpZXdzXzdkIiwibGFiZWwiOiJWaWV3cyAoN2QpIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpmYWxzZX0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InByb2R1Y3Rfdmlld3NfMzBkIiwibGFiZWwiOiJWaWV3cyAoMzBkKSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjpmYWxzZSwiaXNUb2dnbGVhYmxlIjp0cnVlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOnRydWV9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzdG9ja19xdWFudGl0eSIsImxhYmVsIjoiU3RvY2siLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjp0cnVlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOmZhbHNlfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfYWN0aXZlIiwibGFiZWwiOiJJcyBhY3RpdmUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidXBkYXRlZF9hdCIsImxhYmVsIjoiVXBkYXRlZCBhdCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjpmYWxzZSwiaXNUb2dnbGVhYmxlIjp0cnVlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOnRydWV9XSwiOGZhYzZlYjFjZWMyNjgwM2IzZjdmYjQ0MGEyNzExMWJfcGVyX3BhZ2UiOiIxMDAifSwiZmlsYW1lbnQiOltdfQ==', 1781464350),
('nG3XFiBBQi59EaOLWhDKTxvz3l1GE3Nv0GwsqE33', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/3.7.36 Chrome/142.0.7444.265 Electron/39.8.1 Safari/537.36', 'eyJfdG9rZW4iOiJDYXk4TkQ4WHdMRzN1YlpJY0QxMlk4NmZCZjNiQ2x5VmJTMzVDVk94IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJzdG9yZS5ob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1781461183);

-- --------------------------------------------------------

--
-- Table structure for table `site_page_views`
--

CREATE TABLE `site_page_views` (
  `id` bigint UNSIGNED NOT NULL,
  `session_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `referrer` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visited_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_page_views`
--

INSERT INTO `site_page_views` (`id`, `session_id`, `path`, `referrer`, `visited_at`, `created_at`, `updated_at`) VALUES
(1, '62XMOj1tXZZANx4lKS6RN0IYdgw7o8vLgVjJ3arx', '/', NULL, '2026-06-14 07:27:45', '2026-06-14 07:27:45', '2026-06-14 07:27:45'),
(2, 'LcO77PFQsMZLIXufmfo1ELPFWP9rPXltrmTrAjTs', '/', NULL, '2026-06-14 09:00:02', '2026-06-14 09:00:03', '2026-06-14 09:00:03'),
(3, 'LcO77PFQsMZLIXufmfo1ELPFWP9rPXltrmTrAjTs', 'login', 'http://127.0.0.1:8000/', '2026-06-14 09:12:11', '2026-06-14 09:12:12', '2026-06-14 09:12:12'),
(4, 'jZNpDK8Tljs9HxTeV4aKJxzoxgLOGaSKkmcFEZgT', '/', 'http://127.0.0.1:8000/login', '2026-06-14 09:13:17', '2026-06-14 09:13:17', '2026-06-14 09:13:17'),
(5, 'nG3XFiBBQi59EaOLWhDKTxvz3l1GE3Nv0GwsqE33', '/', NULL, '2026-06-14 15:19:24', '2026-06-14 15:19:25', '2026-06-14 15:19:25'),
(6, 'Zc81zu3m9BNUSlHR8dFX3yKFmbqasJqCmVxRuvSy', '/', NULL, '2026-06-14 15:19:59', '2026-06-14 15:19:59', '2026-06-14 15:19:59'),
(7, 'Zc81zu3m9BNUSlHR8dFX3yKFmbqasJqCmVxRuvSy', 'login', 'http://127.0.0.1:8000/', '2026-06-14 15:21:19', '2026-06-14 15:21:19', '2026-06-14 15:21:19'),
(8, '4BjwhlolpZscAMUa0cloVVPhWCYle1Nyy7PRob6K', '/', 'http://127.0.0.1:8000/login', '2026-06-14 15:22:58', '2026-06-14 15:22:58', '2026-06-14 15:22:58');

-- --------------------------------------------------------

--
-- Table structure for table `site_visitors`
--

CREATE TABLE `site_visitors` (
  `id` bigint UNSIGNED NOT NULL,
  `session_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `last_path` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_path` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_source` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_medium` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_campaign` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_seen_at` timestamp NULL DEFAULT NULL,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_visitors`
--

INSERT INTO `site_visitors` (`id`, `session_id`, `user_id`, `last_path`, `first_path`, `referrer`, `utm_source`, `utm_medium`, `utm_campaign`, `device_type`, `ip_hash`, `user_agent_hash`, `first_seen_at`, `last_seen_at`, `created_at`, `updated_at`) VALUES
(1, 'JjXYFQtP2upoBG0GmY9QlOPDXHSfoshw0ea2M90s', 1, 'vegetables', NULL, NULL, NULL, NULL, NULL, NULL, 'f659ce9e50d406d932a6ec74635b2abae8f80e07ce43d1220caf511816fb02c7', '2efe31d10f76c485332c7bf4dba8fe8272eb4cb7f5e35d544dc126eb3bf993be', '2026-05-12 11:03:54', '2026-05-12 12:32:24', '2026-05-12 11:03:54', '2026-05-12 12:32:24'),
(2, 'QEo0jyyM0l969OwrgK2q9LzcQ2WAwBn2V4kfarnE', NULL, 'register', NULL, NULL, NULL, NULL, NULL, NULL, 'f659ce9e50d406d932a6ec74635b2abae8f80e07ce43d1220caf511816fb02c7', '2efe31d10f76c485332c7bf4dba8fe8272eb4cb7f5e35d544dc126eb3bf993be', '2026-05-12 17:52:55', '2026-05-12 17:53:17', '2026-05-12 17:52:55', '2026-05-12 17:53:17'),
(3, '8Ilrtj3j6ckbZAzvnXMupZ3Mt1W0QfC86A7MdPOf', 2, '/', NULL, NULL, NULL, NULL, NULL, NULL, 'f659ce9e50d406d932a6ec74635b2abae8f80e07ce43d1220caf511816fb02c7', '2efe31d10f76c485332c7bf4dba8fe8272eb4cb7f5e35d544dc126eb3bf993be', '2026-05-12 17:53:33', '2026-05-12 18:06:53', '2026-05-12 17:53:33', '2026-05-12 18:06:53'),
(4, 'Y3PHDnGXnALlAOTmG5bU9dYruKb873JsFWbfFopf', NULL, 'login', NULL, NULL, NULL, NULL, NULL, NULL, 'f659ce9e50d406d932a6ec74635b2abae8f80e07ce43d1220caf511816fb02c7', '2efe31d10f76c485332c7bf4dba8fe8272eb4cb7f5e35d544dc126eb3bf993be', '2026-05-12 18:06:58', '2026-05-12 18:11:30', '2026-05-12 18:06:58', '2026-05-12 18:11:30'),
(5, '5zZFQe4fs0SfvDQ2V8qQG9965Tvqh2iImwF2mKMG', 1, '/', NULL, NULL, NULL, NULL, NULL, 'desktop', 'f659ce9e50d406d932a6ec74635b2abae8f80e07ce43d1220caf511816fb02c7', '2efe31d10f76c485332c7bf4dba8fe8272eb4cb7f5e35d544dc126eb3bf993be', '2026-05-12 18:16:33', '2026-05-12 18:29:12', '2026-05-12 18:16:33', '2026-05-12 18:29:12'),
(6, '62XMOj1tXZZANx4lKS6RN0IYdgw7o8vLgVjJ3arx', NULL, '/', '/', NULL, NULL, NULL, NULL, 'desktop', '20d6ed7cad3d118d779500dd16232704d64fd09ec70c62ce35323b5b00464285', '50b1d9dbbd8904cf66b0676c7663ad15e47366a6b4f7a903f59e9196b3e1c2b9', '2026-06-14 07:27:45', '2026-06-14 07:27:45', '2026-06-14 07:27:45', '2026-06-14 07:27:45'),
(7, 'LcO77PFQsMZLIXufmfo1ELPFWP9rPXltrmTrAjTs', NULL, 'login', '/', NULL, NULL, NULL, NULL, 'desktop', '20d6ed7cad3d118d779500dd16232704d64fd09ec70c62ce35323b5b00464285', 'f9d9e5fc7a9e5166686bdd114f1297037eeccac3a3d2e78979e5f727bf4bb895', '2026-06-14 09:00:02', '2026-06-14 09:12:11', '2026-06-14 09:00:03', '2026-06-14 09:12:12'),
(8, 'jZNpDK8Tljs9HxTeV4aKJxzoxgLOGaSKkmcFEZgT', 1, '/', '/', 'http://127.0.0.1:8000/login', NULL, NULL, NULL, 'desktop', '20d6ed7cad3d118d779500dd16232704d64fd09ec70c62ce35323b5b00464285', 'f9d9e5fc7a9e5166686bdd114f1297037eeccac3a3d2e78979e5f727bf4bb895', '2026-06-14 09:13:17', '2026-06-14 09:13:34', '2026-06-14 09:13:17', '2026-06-14 09:13:34'),
(9, 'nG3XFiBBQi59EaOLWhDKTxvz3l1GE3Nv0GwsqE33', NULL, '/', '/', NULL, NULL, NULL, NULL, 'desktop', '20d6ed7cad3d118d779500dd16232704d64fd09ec70c62ce35323b5b00464285', '50b1d9dbbd8904cf66b0676c7663ad15e47366a6b4f7a903f59e9196b3e1c2b9', '2026-06-14 15:19:24', '2026-06-14 15:19:43', '2026-06-14 15:19:24', '2026-06-14 15:19:43'),
(10, 'Zc81zu3m9BNUSlHR8dFX3yKFmbqasJqCmVxRuvSy', NULL, 'login', '/', NULL, NULL, NULL, NULL, 'desktop', '20d6ed7cad3d118d779500dd16232704d64fd09ec70c62ce35323b5b00464285', '8701240454c90b2df29494afeb42494e58f77041bf8d7482e14b866fd821288c', '2026-06-14 15:19:59', '2026-06-14 15:21:19', '2026-06-14 15:19:59', '2026-06-14 15:21:19'),
(11, '4BjwhlolpZscAMUa0cloVVPhWCYle1Nyy7PRob6K', 1, '/', '/', 'http://127.0.0.1:8000/login', NULL, NULL, NULL, 'desktop', '20d6ed7cad3d118d779500dd16232704d64fd09ec70c62ce35323b5b00464285', '8701240454c90b2df29494afeb42494e58f77041bf8d7482e14b866fd821288c', '2026-06-14 15:22:58', '2026-06-14 15:22:58', '2026-06-14 15:22:58', '2026-06-14 15:22:58');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `produce_box_id` bigint UNSIGNED NOT NULL,
  `interval` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `starts_at` timestamp NOT NULL,
  `next_order_at` timestamp NOT NULL,
  `last_generated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `default_city_id` bigint UNSIGNED DEFAULT NULL,
  `default_address_line1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_address_line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `phone_verified_at`, `email_verified_at`, `password`, `is_admin`, `default_city_id`, `default_address_line1`, `default_address_line2`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'AL-DAWY Admin', 'admin@aldawy.local', '01000000000', '2026-05-12 10:00:23', NULL, '$2y$12$dZ3SbaVSRuTadkcUBfL3W.JDkuVRiFtxxQpuN0N0EC8yvysnzwh.2', 1, NULL, NULL, NULL, NULL, '2026-05-12 09:30:49', '2026-05-12 10:00:23'),
(2, 'testUser', 'eyadomar.ok@gmail.com', '+201223453010', NULL, NULL, '$2y$12$BPw2m4ElfTIP6H7VDmi96O1VgMbAbUMWWigmi0yCsfwC1i8t/yyyO', 0, NULL, NULL, NULL, NULL, '2026-05-12 17:53:33', '2026-05-12 17:53:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cities_code_unique` (`code`);

--
-- Indexes for table `content_strings`
--
ALTER TABLE `content_strings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `content_strings_key_unique` (`key`),
  ADD KEY `content_strings_group_index` (`group`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `home_banners`
--
ALTER TABLE `home_banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `home_banners_sort_order_index` (`sort_order`),
  ADD KEY `home_banners_is_active_index` (`is_active`);

--
-- Indexes for table `import_audit_logs`
--
ALTER TABLE `import_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `import_audit_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_reference_unique` (`reference`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_customer_phone_index` (`customer_phone`),
  ADD KEY `orders_status_index` (`status`),
  ADD KEY `orders_city_id_foreign` (`city_id`),
  ADD KEY `orders_coupon_id_foreign` (`coupon_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_produce_box_id_foreign` (`produce_box_id`);

--
-- Indexes for table `packaging_types`
--
ALTER TABLE `packaging_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `packaging_types_code_unique` (`code`);

--
-- Indexes for table `packaging_type_product`
--
ALTER TABLE `packaging_type_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pkg_type_product_unique` (`product_id`,`packaging_type_id`),
  ADD KEY `packaging_type_product_packaging_type_id_foreign` (`packaging_type_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `phone_verifications`
--
ALTER TABLE `phone_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone_verifications_phone_number_index` (`phone_number`);

--
-- Indexes for table `preparation_services`
--
ALTER TABLE `preparation_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `preparation_services_code_unique` (`code`);

--
-- Indexes for table `preparation_service_product`
--
ALTER TABLE `preparation_service_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prep_svc_product_unique` (`product_id`,`preparation_service_id`),
  ADD KEY `preparation_service_product_preparation_service_id_foreign` (`preparation_service_id`);

--
-- Indexes for table `produce_boxes`
--
ALTER TABLE `produce_boxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produce_box_items`
--
ALTER TABLE `produce_box_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produce_box_items_produce_box_id_foreign` (`produce_box_id`),
  ADD KEY `produce_box_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_views`
--
ALTER TABLE `product_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_views_user_id_foreign` (`user_id`),
  ADD KEY `product_views_product_id_visited_at_index` (`product_id`,`visited_at`),
  ADD KEY `product_views_product_id_session_id_index` (`product_id`,`session_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `seo_settings`
--
ALTER TABLE `seo_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `site_page_views`
--
ALTER TABLE `site_page_views`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_visitors`
--
ALTER TABLE `site_visitors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_visitors_session_id_unique` (`session_id`),
  ADD KEY `site_visitors_user_id_foreign` (`user_id`),
  ADD KEY `site_visitors_ip_hash_index` (`ip_hash`),
  ADD KEY `site_visitors_last_seen_at_index` (`last_seen_at`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `subscriptions_produce_box_id_foreign` (`produce_box_id`),
  ADD KEY `subscriptions_status_index` (`status`),
  ADD KEY `subscriptions_next_order_at_index` (`next_order_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_number_unique` (`phone_number`),
  ADD KEY `users_default_city_id_foreign` (`default_city_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `content_strings`
--
ALTER TABLE `content_strings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `home_banners`
--
ALTER TABLE `home_banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `import_audit_logs`
--
ALTER TABLE `import_audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `packaging_types`
--
ALTER TABLE `packaging_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `packaging_type_product`
--
ALTER TABLE `packaging_type_product`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phone_verifications`
--
ALTER TABLE `phone_verifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preparation_services`
--
ALTER TABLE `preparation_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `preparation_service_product`
--
ALTER TABLE `preparation_service_product`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=461;

--
-- AUTO_INCREMENT for table `produce_boxes`
--
ALTER TABLE `produce_boxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produce_box_items`
--
ALTER TABLE `produce_box_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `product_views`
--
ALTER TABLE `product_views`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `seo_settings`
--
ALTER TABLE `seo_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_page_views`
--
ALTER TABLE `site_page_views`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `site_visitors`
--
ALTER TABLE `site_visitors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `import_audit_logs`
--
ALTER TABLE `import_audit_logs`
  ADD CONSTRAINT `import_audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_produce_box_id_foreign` FOREIGN KEY (`produce_box_id`) REFERENCES `produce_boxes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `packaging_type_product`
--
ALTER TABLE `packaging_type_product`
  ADD CONSTRAINT `packaging_type_product_packaging_type_id_foreign` FOREIGN KEY (`packaging_type_id`) REFERENCES `packaging_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `packaging_type_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `preparation_service_product`
--
ALTER TABLE `preparation_service_product`
  ADD CONSTRAINT `preparation_service_product_preparation_service_id_foreign` FOREIGN KEY (`preparation_service_id`) REFERENCES `preparation_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `preparation_service_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `produce_box_items`
--
ALTER TABLE `produce_box_items`
  ADD CONSTRAINT `produce_box_items_produce_box_id_foreign` FOREIGN KEY (`produce_box_id`) REFERENCES `produce_boxes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `produce_box_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_views`
--
ALTER TABLE `product_views`
  ADD CONSTRAINT `product_views_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_views_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `site_visitors`
--
ALTER TABLE `site_visitors`
  ADD CONSTRAINT `site_visitors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_produce_box_id_foreign` FOREIGN KEY (`produce_box_id`) REFERENCES `produce_boxes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_default_city_id_foreign` FOREIGN KEY (`default_city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
