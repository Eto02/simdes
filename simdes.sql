-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.21-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for simdes
CREATE DATABASE IF NOT EXISTS `simdes` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `simdes`;

-- Dumping structure for table simdes.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simdes.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table simdes.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simdes.migrations: ~4 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2022_11_08_132946_create_permission_tables', 2);

-- Dumping structure for table simdes.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simdes.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table simdes.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simdes.model_has_roles: ~3 rows (approximately)
REPLACE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(2, 'App\\Models\\User', 3);

-- Dumping structure for table simdes.mstr_employee
CREATE TABLE IF NOT EXISTS `mstr_employee` (
  `employee_id` varchar(255) NOT NULL DEFAULT (UUID()),
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `login_background` varchar(255) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`employee_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `FK_mstr_employee_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table simdes.mstr_employee: ~2 rows (approximately)
REPLACE INTO `mstr_employee` (`employee_id`, `user_id`, `name`, `address`, `phone_number`, `logo`, `login_background`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	('4d30cb5b-6023-11ed-90eb-a81e84c072fd', 2, 'Company 1', 'Jl. Pattimura No.20, Lumajang, Jawa Timur', '081222333444', 'company/logo/20221109183900_logo.png', 'company/login_background/20221109183900_loginbg.png', 'superadmin@simdes.com', '2022-11-09 11:40:18', NULL, NULL),
	('94b8ba7d-6023-11ed-90eb-a81e84c072fd', 3, 'Company 2', 'Jl. Ir. Soekarno No.25, Lumajang, Jawa Timur', '081222333555', 'company/logo/20221109184100_logo.png', 'company/login_background/20221109184100_loginbg.png', 'superadmin@simdes.com', '2022-11-09 11:42:19', NULL, NULL);

-- Dumping structure for table simdes.mstr_file_type
CREATE TABLE IF NOT EXISTS `mstr_file_type` (
  `file_type_id` varchar(255) NOT NULL DEFAULT (UUID()),
  `employee_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`file_type_id`) USING BTREE,
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `FK_mstr_letter_type_mstr_employee` FOREIGN KEY (`employee_id`) REFERENCES `mstr_employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table simdes.mstr_file_type: ~2 rows (approximately)
REPLACE INTO `mstr_file_type` (`file_type_id`, `employee_id`, `name`, `description`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	('9e005c4c-6415-11ed-8526-a81e84c072fd', '4d30cb5b-6023-11ed-90eb-a81e84c072fd', 'Surat Keputusan', 'Ini adalah contoh deskripsi surat keputusan.', 'company1@simdes.com', '2022-11-14 12:12:23', NULL, NULL),
	('a4853a21-6415-11ed-8526-a81e84c072fd', '4d30cb5b-6023-11ed-90eb-a81e84c072fd', 'Surat Dinas', 'Ini adalah contoh deskripsi surat dinas.', 'company1@simdes.com', '2022-11-14 12:12:34', NULL, NULL),
	('f5d893cb-648c-11ed-8526-a81e84c072fd', '4d30cb5b-6023-11ed-90eb-a81e84c072fd', 'Surat Lamaran Kerja', 'Ini adalah contoh deskripsi surat lamaran kerja.', 'company1@simdes.com', '2022-11-15 02:26:32', NULL, NULL);

-- Dumping structure for table simdes.mstr_service_type
CREATE TABLE IF NOT EXISTS `mstr_service_type` (
  `service_type_id` varchar(255) NOT NULL DEFAULT (UUID()),
  `employee_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`service_type_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `FK_mstr_service_type_mstr_employee` FOREIGN KEY (`employee_id`) REFERENCES `mstr_employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table simdes.mstr_service_type: ~2 rows (approximately)
REPLACE INTO `mstr_service_type` (`service_type_id`, `employee_id`, `name`, `description`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	('0ebc0ce7-641f-11ed-8526-a81e84c072fd', '4d30cb5b-6023-11ed-90eb-a81e84c072fd', 'Perubahan Data Kartu Keluarga', 'Ini adalah contoh deskripsi perubahan data kartu keluarga.', 'company1@simdes.com', '2022-11-14 13:19:58', NULL, NULL),
	('c6255a04-641e-11ed-8526-a81e84c072fd', '4d30cb5b-6023-11ed-90eb-a81e84c072fd', 'Pembuatan KTP', 'Ini adalah contoh deskripsi pembuatan ktp.', 'company1@simdes.com', '2022-11-14 13:17:56', NULL, NULL),
	('d576b6b5-641e-11ed-8526-a81e84c072fd', '4d30cb5b-6023-11ed-90eb-a81e84c072fd', 'Lapor Pemerintah Desa', 'Ini adalah contoh deskripsi lapor pemerintah desa.', 'company1@simdes.com', '2022-11-14 13:18:22', NULL, NULL);

-- Dumping structure for table simdes.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simdes.password_resets: ~0 rows (approximately)

-- Dumping structure for table simdes.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simdes.permissions: ~0 rows (approximately)

-- Dumping structure for table simdes.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simdes.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table simdes.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simdes.roles: ~2 rows (approximately)
REPLACE INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'superadmin', 'web', '2022-11-09 04:32:39', NULL),
	(2, 'company', 'web', '2022-11-09 04:33:37', NULL);

-- Dumping structure for table simdes.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simdes.role_has_permissions: ~0 rows (approximately)

-- Dumping structure for table simdes.tr_service
CREATE TABLE IF NOT EXISTS `tr_service` (
  `service_id` varchar(255) NOT NULL DEFAULT (UUID()),
  `employee_id` varchar(255) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `service_type_id` varchar(255) NOT NULL,
  `letter_number` varchar(255) DEFAULT NULL,
  `serviced_by` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`service_id`),
  KEY `employee_id` (`employee_id`),
  KEY `service_type_id` (`service_type_id`),
  CONSTRAINT `FK_tr_service_mstr_employee` FOREIGN KEY (`employee_id`) REFERENCES `mstr_employee` (`employee_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tr_service_mstr_service_type` FOREIGN KEY (`service_type_id`) REFERENCES `mstr_service_type` (`service_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table simdes.tr_service: ~2 rows (approximately)
REPLACE INTO `tr_service` (`service_id`, `employee_id`, `nik`, `name`, `service_type_id`, `letter_number`, `serviced_by`, `notes`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	('ba3056aa-6431-11ed-8526-a81e84c072fd', '4d30cb5b-6023-11ed-90eb-a81e84c072fd', '12345678', 'Andri Fanky', 'c6255a04-641e-11ed-8526-a81e84c072fd', 'SR-001-14/11/2022', 'Tahta', 'Ini adalah contoh catatan layanan.', 'company1@simdes.com', '2022-11-14 15:33:37', NULL, NULL),
	('d3cbceb7-6431-11ed-8526-a81e84c072fd', '4d30cb5b-6023-11ed-90eb-a81e84c072fd', '12345679', 'Kurniawan', 'd576b6b5-641e-11ed-8526-a81e84c072fd', 'SR-002-14/11/2022', 'Tahta', 'Ini adalah contoh catatan layanan.', 'company1@simdes.com', '2022-11-14 15:34:20', NULL, NULL);

-- Dumping structure for table simdes.tr_service_file
CREATE TABLE IF NOT EXISTS `tr_service_file` (
  `service_file_id` varchar(255) NOT NULL DEFAULT (UUID()),
  `service_id` varchar(255) NOT NULL,
  `file_type_id` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_location` text NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`service_file_id`),
  KEY `service_id` (`service_id`),
  KEY `file_type_id` (`file_type_id`),
  CONSTRAINT `FK_tr_service_file_mstr_file_type` FOREIGN KEY (`file_type_id`) REFERENCES `mstr_file_type` (`file_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tr_service_file_tr_service` FOREIGN KEY (`service_id`) REFERENCES `tr_service` (`service_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table simdes.tr_service_file: ~2 rows (approximately)
REPLACE INTO `tr_service_file` (`service_file_id`, `service_id`, `file_type_id`, `file_name`, `file_location`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	('36f57b36-64b4-11ed-bd8f-a81e84c072fd', 'ba3056aa-6431-11ed-8526-a81e84c072fd', 'a4853a21-6415-11ed-8526-a81e84c072fd', 'company/service_file/20221115070747_Test Berkas 1.pdf', 'A1-002', 'company1@simdes.com', '2022-11-15 07:07:49', NULL, NULL),
	('5e54b795-64ab-11ed-8526-a81e84c072fd', 'ba3056aa-6431-11ed-8526-a81e84c072fd', '9e005c4c-6415-11ed-8526-a81e84c072fd', 'company/service_file/20221115070457_Test Berkas 1.pdf', 'A1-001', 'company1@simdes.com', '2022-11-15 06:04:12', 'company1@simdes.com', '2022-11-15 07:05:57');

-- Dumping structure for table simdes.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table simdes.users: ~2 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Super Admin', 'superadmin@simdes.com', NULL, '$2y$10$Rh32XYhSGDgu8GZEG/0Q2.8j76C..ZSLE/CHRceBHKxevQqWIkBuq', NULL, '2022-11-09 04:34:56', NULL),
	(2, 'Company 1', 'company1@simdes.com', NULL, '$2y$10$mltY3gPvqutBxj/QNvydIOetjouUkBnpW/bl.KfiKjAiYMOy1u9/q', NULL, '2022-11-09 11:36:39', NULL),
	(3, 'Company 2', 'company2@simdes.com', NULL, '$2y$10$mltY3gPvqutBxj/QNvydIOetjouUkBnpW/bl.KfiKjAiYMOy1u9/q', NULL, '2022-11-09 11:36:59', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
