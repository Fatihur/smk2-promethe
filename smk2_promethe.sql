/*
 Navicat Premium Data Transfer

 Source Server         : Database
 Source Server Type    : MySQL
 Source Server Version : 80040
 Source Host           : localhost:3306
 Source Schema         : smk2_promethe

 Target Server Type    : MySQL
 Target Server Version : 80040
 File Encoding         : 65001

 Date: 05/08/2025 10:51:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for jurusan
-- ----------------------------
DROP TABLE IF EXISTS `jurusan`;
CREATE TABLE `jurusan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_jurusan` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_jurusan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `kuota` int NOT NULL DEFAULT 0,
  `kategori` enum('umum','khusus') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'umum',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `jurusan_kode_jurusan_unique`(`kode_jurusan` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jurusan
-- ----------------------------
INSERT INTO `jurusan` VALUES (14, 'TKP', 'Teknik Konstruksi dan Perumahan', NULL, 80, 'umum', 1, '2025-08-03 03:40:36', '2025-08-03 03:40:36');
INSERT INTO `jurusan` VALUES (15, 'KJJ', 'Konstruksi Jalan dan Jembatan', NULL, 30, 'umum', 1, '2025-08-03 03:41:08', '2025-08-03 03:41:08');
INSERT INTO `jurusan` VALUES (16, 'DPIB', 'Desain Pemodelan dan Informasi Bangunan', NULL, 50, 'umum', 1, '2025-08-03 03:41:34', '2025-08-03 03:41:40');
INSERT INTO `jurusan` VALUES (17, 'TEI', 'Teknik Elektronika Industri', NULL, 50, 'umum', 1, '2025-08-03 03:42:00', '2025-08-03 03:42:00');
INSERT INTO `jurusan` VALUES (18, 'TOI', 'Teknik Otomasi Industri', NULL, 50, 'umum', 1, '2025-08-03 03:42:22', '2025-08-03 03:42:22');
INSERT INTO `jurusan` VALUES (19, 'TKR', 'Teknik Kendaraan Ringan', NULL, 50, 'umum', 1, '2025-08-03 03:42:41', '2025-08-03 03:42:41');
INSERT INTO `jurusan` VALUES (20, 'TAB', 'Teknik Alat Berat', NULL, 30, 'khusus', 1, '2025-08-03 03:43:05', '2025-08-03 03:43:11');
INSERT INTO `jurusan` VALUES (21, 'TBKR', 'Teknik Bodi Kendaraan Ringan', NULL, 50, 'umum', 1, '2025-08-03 03:43:40', '2025-08-03 03:46:02');
INSERT INTO `jurusan` VALUES (22, 'TSM', 'Teknik Sepeda Motor', NULL, 40, 'khusus', 1, '2025-08-03 03:43:59', '2025-08-03 03:43:59');
INSERT INTO `jurusan` VALUES (23, 'TPS', 'Teknik Pemesinan', NULL, 50, 'umum', 1, '2025-08-03 03:44:43', '2025-08-03 03:44:43');
INSERT INTO `jurusan` VALUES (24, 'TPL', 'Teknik Pengelasan', NULL, 50, 'umum', 1, '2025-08-03 03:45:05', '2025-08-03 03:45:05');
INSERT INTO `jurusan` VALUES (25, 'TPTU', 'Teknik Pemanasan, Tata Udara dan Pendinginan', NULL, 50, 'umum', 1, '2025-08-03 03:45:36', '2025-08-03 03:45:36');
INSERT INTO `jurusan` VALUES (26, 'TITL', 'Teknik Instalasi Tenaga Listrik', NULL, 50, 'umum', 1, '2025-08-03 03:45:56', '2025-08-03 03:45:56');

-- ----------------------------
-- Table structure for master_kriteria
-- ----------------------------
DROP TABLE IF EXISTS `master_kriteria`;
CREATE TABLE `master_kriteria`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_kriteria` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kriteria` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tipe` enum('benefit','cost') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'benefit',
  `bobot` decimal(5, 2) NOT NULL DEFAULT 1.00,
  `nilai_min` decimal(8, 2) NOT NULL DEFAULT 0.00,
  `nilai_max` decimal(8, 2) NOT NULL DEFAULT 100.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `master_kriteria_kode_kriteria_unique`(`kode_kriteria` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of master_kriteria
-- ----------------------------
INSERT INTO `master_kriteria` VALUES (33, 'TPA', 'Tes Potensi Akademik (TPA)', 'Penilaian kemampuan akademik dasar siswa meliputi logika, matematika, dan bahasa', 'benefit', 0.50, 1.00, 100.00, 1, '2025-08-03 04:18:13', '2025-08-03 11:33:08');
INSERT INTO `master_kriteria` VALUES (34, 'MB', 'Minat dan Bakat', NULL, 'benefit', 0.25, 0.00, 1.00, 1, '2025-08-03 04:18:57', '2025-08-03 10:20:57');
INSERT INTO `master_kriteria` VALUES (35, 'PS', 'Psikotes', NULL, 'benefit', 0.25, 1.00, 5.00, 1, '2025-08-03 04:19:08', '2025-08-03 11:32:49');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2025_07_30_160750_create_permission_tables', 1);
INSERT INTO `migrations` VALUES (5, '2025_07_30_162917_create_jurusan_table', 1);
INSERT INTO `migrations` VALUES (6, '2025_07_30_162953_create_tahun_akademik_table', 1);
INSERT INTO `migrations` VALUES (7, '2025_07_30_163014_create_kriteria_table', 1);
INSERT INTO `migrations` VALUES (8, '2025_07_30_163033_create_sub_kriteria_table', 1);
INSERT INTO `migrations` VALUES (9, '2025_07_30_163053_create_siswa_table', 1);
INSERT INTO `migrations` VALUES (10, '2025_07_30_163116_create_nilai_siswa_table', 1);
INSERT INTO `migrations` VALUES (11, '2025_07_30_163136_create_promethee_results_table', 1);
INSERT INTO `migrations` VALUES (12, '2025_07_30_163201_create_selection_process_status_table', 1);
INSERT INTO `migrations` VALUES (13, '2025_07_30_163419_add_foreign_keys_to_users_table', 1);
INSERT INTO `migrations` VALUES (14, '2025_07_31_020308_add_kategori_to_jurusan_table', 1);
INSERT INTO `migrations` VALUES (15, '2025_07_31_021102_update_existing_jurusan_kategori', 1);
INSERT INTO `migrations` VALUES (16, '2025_07_31_024045_create_guru_table', 1);
INSERT INTO `migrations` VALUES (17, '2025_07_31_034323_add_jurusan_id_to_kriteria_table', 1);
INSERT INTO `migrations` VALUES (18, '2025_07_31_063451_drop_kriteria_and_sub_kriteria_tables', 2);
INSERT INTO `migrations` VALUES (19, '2025_07_31_064943_create_kriterias_table', 3);
INSERT INTO `migrations` VALUES (20, '2025_07_31_070151_add_kriteria_id_to_nilai_siswa_table', 4);
INSERT INTO `migrations` VALUES (21, '2025_07_31_080605_restructure_kriteria_system', 5);
INSERT INTO `migrations` VALUES (22, '2025_07_31_084721_change_kriteria_from_bobot_to_rentang_nilai', 6);
INSERT INTO `migrations` VALUES (23, '2025_07_31_100000_seed_kriteria_data', 7);
INSERT INTO `migrations` VALUES (24, '2025_08_01_100000_fix_nilai_siswa_unique_constraint', 7);
INSERT INTO `migrations` VALUES (25, '2025_08_01_110000_add_missing_fields_to_siswa_table', 7);
INSERT INTO `migrations` VALUES (26, '2025_08_02_fix_nilai_siswa_constraint', 8);
INSERT INTO `migrations` VALUES (27, '2025_08_03_035435_remove_unnecessary_fields_from_siswa_and_drop_guru_table', 9);

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_permissions_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles`  (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_roles_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_roles
-- ----------------------------

-- ----------------------------
-- Table structure for nilai_siswa
-- ----------------------------
DROP TABLE IF EXISTS `nilai_siswa`;
CREATE TABLE `nilai_siswa`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `nilai` decimal(5, 2) NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `master_kriteria_id` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `nilai_siswa_siswa_master_kriteria_unique`(`siswa_id` ASC, `master_kriteria_id` ASC) USING BTREE,
  INDEX `nilai_siswa_master_kriteria_id_foreign`(`master_kriteria_id` ASC) USING BTREE,
  CONSTRAINT `nilai_siswa_master_kriteria_id_foreign` FOREIGN KEY (`master_kriteria_id`) REFERENCES `master_kriteria` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `nilai_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nilai_siswa
-- ----------------------------

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------

-- ----------------------------
-- Table structure for promethee_results
-- ----------------------------
DROP TABLE IF EXISTS `promethee_results`;
CREATE TABLE `promethee_results`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `tahun_akademik_id` bigint UNSIGNED NOT NULL,
  `kategori` enum('khusus','umum') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phi_plus` decimal(10, 6) NOT NULL,
  `phi_minus` decimal(10, 6) NOT NULL,
  `phi_net` decimal(10, 6) NOT NULL,
  `ranking` int NOT NULL,
  `masuk_kuota` tinyint(1) NOT NULL DEFAULT 0,
  `status_validasi` enum('pending','lulus','lulus_pilihan_2','tidak_lulus') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `validated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `catatan_validasi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `promethee_results_siswa_id_tahun_akademik_id_kategori_unique`(`siswa_id` ASC, `tahun_akademik_id` ASC, `kategori` ASC) USING BTREE,
  INDEX `promethee_results_tahun_akademik_id_foreign`(`tahun_akademik_id` ASC) USING BTREE,
  INDEX `promethee_results_validated_by_foreign`(`validated_by` ASC) USING BTREE,
  CONSTRAINT `promethee_results_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `promethee_results_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `promethee_results_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2215 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of promethee_results
-- ----------------------------

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------

-- ----------------------------
-- Table structure for selection_process_status
-- ----------------------------
DROP TABLE IF EXISTS `selection_process_status`;
CREATE TABLE `selection_process_status`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun_akademik_id` bigint UNSIGNED NOT NULL,
  `kategori_khusus_status` enum('belum_dimulai','sedang_berjalan','selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dimulai',
  `kategori_umum_status` enum('tidak_aktif','siap','sedang_berjalan','selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tidak_aktif',
  `kuota_khusus` int NULL DEFAULT NULL,
  `khusus_started_at` timestamp NULL DEFAULT NULL,
  `khusus_completed_at` timestamp NULL DEFAULT NULL,
  `umum_started_at` timestamp NULL DEFAULT NULL,
  `umum_completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `selection_process_status_tahun_akademik_id_unique`(`tahun_akademik_id` ASC) USING BTREE,
  CONSTRAINT `selection_process_status_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of selection_process_status
-- ----------------------------

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('XMHEEPFDaAj3m8jsqaNUUoa9XdMxO0goNtrl0G6x', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMUdwRlB2VkxlNzV1eElzbEExT1I1Y3lNaTBpMGYwRTJrZ0U4WnVIMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTU6Imh0dHA6Ly9zbWsyLXByb21ldGhlLnRlc3QvcGFuaXRpYS9wcm9tZXRoZWUva2h1c3VzL2Zvcm0iO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1754223673);

-- ----------------------------
-- Table structure for siswa
-- ----------------------------
DROP TABLE IF EXISTS `siswa`;
CREATE TABLE `siswa`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `no_pendaftaran` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `asal_sekolah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_akademik_id` bigint UNSIGNED NOT NULL,
  `pilihan_jurusan_1` bigint UNSIGNED NOT NULL,
  `pilihan_jurusan_2` bigint UNSIGNED NULL DEFAULT NULL,
  `kategori` enum('khusus','umum') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','diterima','ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `status_seleksi` enum('pending','lulus','tidak_lulus','lulus_pilihan_2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `jurusan_diterima_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `siswa_no_pendaftaran_unique`(`no_pendaftaran` ASC) USING BTREE,
  UNIQUE INDEX `siswa_nisn_unique`(`nisn` ASC) USING BTREE,
  INDEX `siswa_tahun_akademik_id_foreign`(`tahun_akademik_id` ASC) USING BTREE,
  INDEX `siswa_pilihan_jurusan_1_foreign`(`pilihan_jurusan_1` ASC) USING BTREE,
  INDEX `siswa_pilihan_jurusan_2_foreign`(`pilihan_jurusan_2` ASC) USING BTREE,
  INDEX `siswa_jurusan_diterima_id_foreign`(`jurusan_diterima_id` ASC) USING BTREE,
  CONSTRAINT `siswa_jurusan_diterima_id_foreign` FOREIGN KEY (`jurusan_diterima_id`) REFERENCES `jurusan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `siswa_pilihan_jurusan_1_foreign` FOREIGN KEY (`pilihan_jurusan_1`) REFERENCES `jurusan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `siswa_pilihan_jurusan_2_foreign` FOREIGN KEY (`pilihan_jurusan_2`) REFERENCES `jurusan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `siswa_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 797 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of siswa
-- ----------------------------
INSERT INTO `siswa` VALUES (337, '20250001', '0014024999', 'ARYA FIRMANSYAH', 'L', 'Sumbawa', '2008-08-26', 'Dusun Beru, Rt/Rw005/002, Desa Boak, Unteriwes', 'Amiruddin', NULL, 'SMPN 3 Sumbawa Besar', 1, 17, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (338, '20250002', '0079062426', 'IMAM ABRAR ROSADI', 'L', 'Sumbawa Besar', '2008-10-31', 'Jl Lintas Raberas, Rt/Rw 002/006, Seketeng, Sumbawa', 'Tarmizi', NULL, 'MTSS Putra NW Narmada', 1, 17, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (339, '20250003', '0093957612', 'ARLIN SOFIYAN PRADANA', 'L', 'Malang', '2008-08-06', 'Jl By Pass Depan SMKN 2 Sumbawa Besar', 'Waris', NULL, 'SMPN 2 Sumbawa Besar', 1, 17, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (340, '20250004', '0042117699', 'Imanuddin Adhitya Mulana', 'L', 'Ropang', '2008-01-25', 'Dusun Semaning, Rt/Rw 010/003, Desa Ropang, Ropang', 'Saripuddin', NULL, 'SMPN 1 Ropang', 1, 17, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (341, '20250005', '0018780129', 'Nizul Febriyan Zulkarnaen', 'L', 'Ropang', '2009-01-23', 'Dusun Semaning, Rt/Rw 010/003, Desa Ropang, Ropang', 'Jamal Udin', NULL, 'SMPN 3 Ropang', 1, 17, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (342, '20250006', '0043913022', 'Reza Aditya', 'L', 'Sumbawa', '2008-07-29', 'Dusun Lab.badas, Rt/Rw 002/011, Desa Labuhan Badas', 'Edi Prayetno', NULL, 'SMPN 1 Labuhan Badas', 1, 17, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (343, '20250007', '0077727055', 'Fahat Aldiano', 'L', 'Sumbawa', '2009-05-12', 'Dusun Sumer Payungg, Rt/Rw 002/006, Labuhan Badas', 'Adi Setiawan', NULL, 'PONPES NW Samawa', 1, 17, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (344, '20250008', '0035898212', 'Nabila Noviandini', 'L', 'Sumbawa', '2008-11-29', 'Dusun Leweng, Rt/Rw 002/007, Desa Mama, Lopok', 'Muslimin Febriansyah', NULL, 'SMPN 4 Satap Lopok', 1, 17, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (345, '20250009', '0055468395', 'Fathur Rahman', 'L', 'Sumbawa', '2009-05-16', 'Dusun lab.badas, Rt/Rw 002/001, Desa Labuhan Badas', 'Agus Sunaryo', NULL, 'SMPN 1 Labuhan Badas', 1, 17, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (346, '20250010', '0010801386', 'Yuni Baesi', 'L', 'Sumbawa', '2008-03-26', 'Dusun Griya Idola, Rt/Rw 002/020, Lab.Sumbawa, Badas', 'Slamet Riyadi', NULL, 'SMP IT Sumbawa', 1, 17, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (347, '20250011', '0039758844', 'Sukma Wati', 'L', 'Sumbawa', '2009-08-04', 'Dusun Pungka, Rt/Rw 003/001, Desa Pungka, Unter Iwes', 'A Rahman', NULL, 'SMPN 3 Sumbawa Besar', 1, 17, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (348, '20250012', '0080178868', 'Muhammad Ridho', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (349, '20250013', '0060171412', 'Juan Putra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (350, '20250014', '0029082230', 'Aulia Putri Hayatullah', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (351, '20250015', '0056457685', 'Arianto', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (352, '20250016', '0057610818', 'Ahmad Alfarizy', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (353, '20250017', '0099929003', 'M. Jihad', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (354, '20250018', '0002114228', 'Subhan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (355, '20250019', '0026282466', 'Aulia Riyani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (356, '20250020', '0083670663', 'Revano Ligiesta', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (357, '20250021', '0047302196', 'Muhammad Lutfan Azwar Salam', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (358, '20250022', '0063426805', 'Ilham Ramdhani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (359, '20250023', '0086429684', 'Fahri Dwi Alamsyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (360, '20250024', '0088661546', 'Andi Tata Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (361, '20250025', '0006130977', 'ARZANUL GHIFFARI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (362, '20250026', '0089143919', 'MUHAMMAD FAHRI SURAHMAN', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (363, '20250027', '0002218020', 'Ridho Fahri', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (364, '20250028', '0007266580', 'Sahdan Niansyah', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (365, '20250029', '0014445589', 'Erlangga Okta Viano', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (366, '20250030', '0034634717', 'Muhammad Hefta Maulana', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (367, '20250031', '0001296427', 'Junio Rizky Ferdinand', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (368, '20250032', '0070902250', 'Selfiana', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (369, '20250033', '0002279025', 'Yunanda Saputri', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (370, '20250034', '0053135538', 'Aditya Tri Pranata', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (371, '20250035', '0074887693', 'Suherman', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (372, '20250036', '0038678443', 'Adi Muzaki Akbar', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (373, '20250037', '0000763955', 'Muhammad Fardan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (374, '20250038', '0035409791', 'ZAINUL MAJDI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (375, '20250039', '0004769559', 'M GALANG', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (376, '20250040', '0031709025', 'RIZKI RINDA SAPUTRA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (377, '20250041', '0023364817', 'M Wahyu Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (378, '20250042', '0046105203', 'Dhimas Rahamula', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (379, '20250043', '0096180495', 'Khairil Taufiqurrahman', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (380, '20250044', '0022402521', 'Romy Firmansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (381, '20250045', '0092366114', 'Khalid Do\'a Rieska Fadoly', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (382, '20250046', '0014200225', 'Deka Ariansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (383, '20250047', '0011285153', 'Sahit', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (384, '20250048', '0000334945', 'Muhammad Jipan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (385, '20250049', '0040229656', 'M Fauzy Badillah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (386, '20250050', '0070901500', 'Muhammad Rizky', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (387, '20250051', '0009753547', 'Muhammad Sigit Dwi Virnanda', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (388, '20250052', '0051852497', 'Habibi Zachwan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (389, '20250053', '0049266949', 'Radit Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (390, '20250054', '0062080841', 'Andra Rezi Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (391, '20250055', '0058979399', 'MUHAMMAD ANDIKA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (392, '20250056', '0091560894', 'SABRI RAHMANTIO', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (393, '20250057', '0023280983', 'MATHAR ZULFADLI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (394, '20250058', '0001399301', 'NOVALDI ZAIN ISMAIL', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (395, '20250059', '0075620893', 'SURYA PRATAMA APRILIANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (396, '20250060', '0096922638', 'MUHAMMAD RAFLI ALGIFARI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (397, '20250061', '0027232822', 'FAUZI BAHRI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (398, '20250062', '0065294632', 'ARDI APRIZAL', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (399, '20250063', '0096812986', 'M RIZKI IMAM SAPUTRA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 17, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (400, '20250064', '0082131802', 'Fajduani Naqsyabandi', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (401, '20250065', '0082978723', 'SAFA HAFIZ R', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (402, '20250066', '0031278370', 'HOLDHYNTO GUNAWAN', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (403, '20250067', '0017413063', 'Izzro Rifkyanto Prasetyo', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (404, '20250068', '0013122898', 'Muhammad Naufal Gassani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (405, '20250069', '0095787488', 'Muhammad Fahmi ', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (406, '20250070', '0061096179', 'Muhammad Akbar', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (407, '20250071', '0060696386', 'Fairul Arisandi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (408, '20250072', '0005859103', 'Novrial Airlangga', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (409, '20250073', '0020131756', 'Irawan Dwy Armansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (410, '20250074', '0026831051', 'Putra Hakiki', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (411, '20250075', '0052464475', 'Lita Susanti', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (412, '20250076', '0065998658', 'Rini Ramdani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (413, '20250077', '0022491977', 'Aola Jumadin', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (414, '20250078', '0031399320', 'AZIZUL HAKIM', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (415, '20250079', '0027752010', 'FAHRI AKBAR', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (416, '20250080', '0066641653', 'EVAN KRISTIAN EDO', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (417, '20250081', '0053119685', 'FAJAR AIDIL AKBAR', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (418, '20250082', '0053496950', 'Dwiki Cahya Nugraha', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (419, '20250083', '0047130182', 'Farel Hani Okta Pratama', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (420, '20250084', '0050799129', 'Raditya Lesmathano', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (421, '20250085', '0046768518', 'NASRUL JAYADI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (422, '20250086', '0058993777', 'FATHAN JULIARIADI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (423, '20250087', '0000702550', 'Abdul Rasid', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (424, '20250088', '0097854172', 'Fitrah Sultan Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (425, '20250089', '0015023943', 'Ardhimas Dzulkaannafi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (426, '20250090', '0067748177', 'APRIN RAMDHANI', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (427, '20250091', '0073764984', 'YAHYA SAFRUDDIN', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (428, '20250092', '0090542990', 'AIDIL SYAPUTRA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (429, '20250093', '0096738950', 'AHMAD SAINUL FIRMANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (430, '20250094', '0079601727', 'M. RAHMAD AL HAFSI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (431, '20250095', '0015049178', 'Gilang', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 18, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (432, '20250096', '0079356783', 'Rehan Tri Putra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (433, '20250097', '0001059337', 'Dimas', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (434, '20250098', '0033781042', 'Rifal Ruli Sullman ', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (435, '20250099', '0084870491', 'Cio Anugraha', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (436, '20250100', '0090906440', 'Dinur Aqbar Fabian', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (437, '20250101', '0042177429', 'Hafif Fitrahansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (438, '20250102', '0059774102', 'M Evan Arjuna', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (439, '20250103', '0064814834', 'Muhammad Denis', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (440, '20250104', '0070688945', 'FARRAS ALMUBARAK', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (441, '20250105', '0020918464', 'Andika Septian Ramdhani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (442, '20250106', '0040715828', 'Wahyu Al Fajri', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (443, '20250107', '0064910979', 'Rian Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (444, '20250108', '0073597025', 'Angga Aprikurniawan M', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (445, '20250109', '0000013483', 'Panji Adinata', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (446, '20250110', '0094327310', 'Muhammad Ali Fikri', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (447, '20250111', '0033353491', 'Insani Habibie', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (448, '20250112', '0074141540', 'Satria Aditya Efendi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (449, '20250113', '0031814544', 'Thomas Villah Nova Sanjaya', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (450, '20250114', '0015258108', 'Irawan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (451, '20250115', '0038224644', 'Abdan Zikrullah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (452, '20250116', '0031466690', 'RAMDANI SAURI PUTRA', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (453, '20250117', '0020136319', 'ARGA SAPUTRA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (454, '20250118', '0066592827', 'SANDI BRILLIAN', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (455, '20250119', '0053302696', 'M. ABIZARD', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (456, '20250120', '0043174380', 'Samsul Fajri', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (457, '20250121', '0010700310', 'Dino Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (458, '20250122', '0024777449', 'Juan Satria Hamdan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (459, '20250123', '0028867842', 'Fakhri Mujaddid', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 24, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (460, '20250124', '0017273759', 'Andhy Syahrullah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 14, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (461, '20250125', '0080021305', 'Can Indra Risqullah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 14, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (462, '20250126', '0020118960', 'Zhyandien Zohirah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 14, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (463, '20250127', '0023116246', 'Rabil Alsyafin', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 14, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (464, '20250128', '0026196422', 'DUTA PARA DIKTA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (465, '20250129', '0071369500', 'Irgi Rahmad Suryadi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (466, '20250130', '0099016740', 'Aiman Putra Merdeka', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (467, '20250131', '0003047269', 'Muhammad Afgan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (468, '20250132', '0028617952', 'Firdian Maulana', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (469, '20250133', '0068272597', 'Rizki Aditiyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (470, '20250134', '0024900063', 'ARYA PUTRA PRATAMA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (471, '20250135', '0087401068', 'Sahril Jihad', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (472, '20250136', '0079582374', 'Hamni Molana', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (473, '20250137', '0083323151', 'M RIZAL HIDAYAT', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (474, '20250138', '0094414962', 'DWI APROLLAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (475, '20250139', '0087768809', 'Muhammad Farrel Firdaus', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (476, '20250140', '0014079635', 'Jazadi Alkaromah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (477, '20250141', '0037366880', 'Rehan Kurniawan', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (478, '20250142', '0036480342', 'M Tegar Aryawinata', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (479, '20250143', '0043664448', 'Afdal Febri Alfiansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (480, '20250144', '0052471316', 'Cherli Izzomi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (481, '20250145', '0062061834', 'Resdy Andika Riansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (482, '20250146', '0058558862', 'Tegu Bakri', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (483, '20250147', '0067779790', 'Muhammad Asril Perdana', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (484, '20250148', '0043825092', 'RESFI ADITYA PRATAMA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (485, '20250149', '0007990870', 'FAREL ANUGRAH ADHAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 15, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (486, '20250150', '0011926204', 'Novia Silmita', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (487, '20250151', '0087851946', 'Gali Anotanata', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (488, '20250152', '0083336398', 'Fajri Juliansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (489, '20250153', '0007104414', 'Qiranna Putri Anindi', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (490, '20250154', '0072900791', 'Maila Febrila Faizah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (491, '20250155', '0012627095', 'Aura Adrenalyn', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (492, '20250156', '0000245322', 'YURI FAJRIANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (493, '20250157', '0051426022', 'RIFAL ARDIANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (494, '20250158', '0044268619', 'RESTI RAMDANI', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (495, '20250159', '0019150850', 'Lana Ferdiansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (496, '20250160', '0038088214', 'Risky Safitri', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (497, '20250161', '0024228355', 'Denyes Andrian Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (498, '20250162', '0020683141', 'Khaerunnisa', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (499, '20250163', '0005474635', 'DUDE JUNIAR IRAWAN', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (500, '20250164', '0007565191', 'Noval Julian Ihsan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (501, '20250165', '0081995297', 'Radit Noval Dwi Putra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (502, '20250166', '0016025032', 'Aryo Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (503, '20250167', '0023742239', 'Adri Ardiansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (504, '20250168', '0013375313', 'Ridho Firmansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (505, '20250169', '0078066769', 'NASRULLAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (506, '20250170', '0065625582', 'MORENO ANUGRAH PRADITA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (507, '20250171', '0094223168', 'FARHAN DWI NUGRAHA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (508, '20250172', '0015225984', 'IKRAM KHAIRINSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (509, '20250173', '0040409778', 'OGOT CANDRA WINATA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (510, '20250174', '0064565296', 'Firna Ramdayani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (511, '20250175', '0020984459', 'Nanda Oktaviana', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (512, '20250176', '0003256772', 'Sukmawati', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (513, '20250177', '0025424034', 'Muslimah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (514, '20250178', '0004538712', 'Ika Annisa Puspita Rahmadani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (515, '20250179', '0011963709', 'Ghaliyzah Fatih T', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (516, '20250180', '0099634423', 'Clara Safitri', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (517, '20250181', '0064669024', 'Fitri Evitasari', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (518, '20250182', '0059815364', 'Irawan Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (519, '20250183', '0083702122', 'Inayah Juniasti', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (520, '20250184', '0012222547', 'Intan Afrilliani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 16, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (521, '20250185', '0012589535', 'Yandri Sujiyono', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (522, '20250186', '0016233358', 'Adif Febriansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (523, '20250187', '0009686299', 'Andika Dwi Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (524, '20250188', '0013084279', 'Erwin Anada Putra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (525, '20250189', '0061098769', 'Putra Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (526, '20250190', '0083742686', 'Satria Wiguna', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (527, '20250191', '0088332478', 'Gatan Utama Akmuddin', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (528, '20250192', '0037663790', 'Satria Fahri Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (529, '20250193', '0052655118', 'Andika Diskyantara', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (530, '20250194', '0032931859', 'Nikola Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (531, '20250195', '0088638566', 'Anel Sopiyan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (532, '20250196', '0016264308', 'Dallee Matanoles', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (533, '20250197', '0033433905', 'Indra Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (534, '20250198', '0075371349', 'Muhammad Kyaisal Al Gazali', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (535, '20250199', '0057478023', 'Tomi Herlino', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (536, '20250200', '0044014911', 'Surya Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (537, '20250201', '0040868275', 'Feri Irawansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (538, '20250202', '0095765522', 'Bambang Irwansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (539, '20250203', '0020295594', 'Hendri Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:28', '2025-08-03 04:40:28');
INSERT INTO `siswa` VALUES (540, '20250204', '0094273088', 'EGA SAPUTRA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (541, '20250205', '0078378294', 'MUHAMMAD', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (542, '20250206', '0054713011', 'RIDWAN SAIDI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (543, '20250207', '0054524478', 'Ergi Paranadekamula', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (544, '20250208', '0064658687', 'Khaiwal Akar Azizi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (545, '20250209', '0028900839', 'Iksan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (546, '20250210', '0033669150', 'Gali Rahmansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (547, '20250211', '0003339351', 'Roni Pirmansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (548, '20250212', '0090547068', 'Ahmadi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 21, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (549, '20250213', '0024313096', 'APRI PUTRA DWIYANSA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (550, '20250214', '0053897005', 'Rasya Rizky Ramadhan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (551, '20250215', '0053811611', 'Dirga Yudika Adinata', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (552, '20250216', '0069965724', 'MUHAMMAD RIZKI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (553, '20250217', '0066355453', 'ARDO ARIELMAN DINATA', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (554, '20250218', '0059061622', 'Isni Dwiyanti', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (555, '20250219', '0047979029', 'Arga Anugrah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (556, '20250220', '0054934641', 'Abdil Gaffar', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (557, '20250221', '0041624081', 'Abie Aliman', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (558, '20250222', '0081170092', 'M. Reza Aljanuar', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (559, '20250223', '0024533886', 'Andika Kusuma', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (560, '20250224', '0022974516', 'Adriansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (561, '20250225', '0043356626', 'Arjuna', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (562, '20250226', '0088283239', 'Dirgantara', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (563, '20250227', '0033820826', 'Ahmad Hidayat', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 23, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (564, '20250228', '0066860367', 'REZA FAHRIANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (565, '20250229', '0003568541', 'QIAN AKBAR SAMANTA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 22, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (566, '20250230', '0075879564', 'RIVALDA ALMAGFIRAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 16, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (567, '20250231', '0017363237', 'HARTADI TRIANDI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 20, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (568, '20250232', '0088347436', 'BILHAK ALWI SILFAK', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 21, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (569, '20250233', '0003333920', 'ILHAMUSZANA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (570, '20250234', '0036487167', 'Jul Fakar', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 26, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (571, '20250235', '0047814981', 'M Marfel Suhermansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 14, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (572, '20250236', '0083940667', 'Subianto', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 19, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (573, '20250237', '0020349343', 'Aditya', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 18, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (574, '20250238', '0080921205', 'Putra Ade Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (575, '20250239', '0079724065', 'Rian', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (576, '20250240', '0039857282', 'Gai Fathurrahman', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (577, '20250241', '0034106831', 'Muhammad Salam Faisi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (578, '20250242', '0089452303', 'Bayu Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (579, '20250243', '0026187366', 'Fuad Anggriawan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 25, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (580, '20250244', '0033887618', 'Ifan Agus Setiawan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (581, '20250245', '0080525946', 'Abdul Hafiz', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (582, '20250246', '0097353465', 'Fajarano Sidra Hidayat', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (583, '20250247', '0052175275', 'Tierta Septyan Ramadhan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (584, '20250248', '0061967522', 'Sandir Abdulrahman', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (585, '20250249', '0092920439', 'Arif Fatahurrahman', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (586, '20250250', '0059918623', 'M. Aditya Maulidansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (587, '20250251', '0025510548', 'Iman Syarifuddin', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (588, '20250252', '0047758849', 'Afyan Anugrah Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (589, '20250253', '0028185611', 'Abdillah Ghazy Fauzan ', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (590, '20250254', '0093694498', 'Fery Herlambang', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (591, '20250255', '0018038106', 'Angga Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (592, '20250256', '0081393046', 'Anis ', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (593, '20250257', '0024379824', 'Ikhwan Jayadi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (594, '20250258', '0053540499', 'TITO FEBRYAN', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (595, '20250259', '0053788267', 'MUHAMMAD ARKAN GASALBA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (596, '20250260', '0055187526', 'Wawan Rezky Alfakhreza', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (597, '20250261', '0082368679', 'Qian Zulfikri Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (598, '20250262', '0094498128', 'Irman Muliyansah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (599, '20250263', '0077660060', 'Andika', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (600, '20250264', '0054856160', 'Rezal', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (601, '20250265', '0038065865', 'Gustami Ade Putra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (602, '20250266', '0015154303', 'Yandri Subiansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (603, '20250267', '0064926262', 'Rifki Januar Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (604, '20250268', '0018315963', 'JULIO CRUZ', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (605, '20250269', '0050553707', 'Satria', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (606, '20250270', '0083723603', 'M Fitrah Ramadhan Abidin', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (607, '20250271', '0056843468', 'Aditya Dinata S', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (608, '20250272', '0035970325', 'M. Fadli Ananda', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (609, '20250273', '0071690055', 'Ahmad Jaelani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (610, '20250274', '0053674320', 'Rizky Wahyudi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (611, '20250275', '0013575567', 'Agus Juandika', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (612, '20250276', '0067301003', 'A Faqi Zdulirfan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (613, '20250277', '0039550902', 'Jaka Putiara', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (614, '20250278', '0018322202', 'M Junian Nano Alviansyah', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 26, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (615, '20250279', '0012719068', 'Afdal Sang Putra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (616, '20250280', '0026834873', 'Andika Andani Putra', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (617, '20250281', '0033688292', 'Aditya Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (618, '20250282', '0084266310', 'Prayoga Adinata Firmansyah', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (619, '20250283', '0054523300', 'AN\'AM JAOZAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (620, '20250284', '0065752701', 'ERWIN DESTIAWAN', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (621, '20250285', '0033398421', 'RIZKY SYA\'BANI', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (622, '20250286', '0000670377', 'M. ARIEL MAULANA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (623, '20250287', '0079429455', 'Offan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (624, '20250288', '0099990238', 'Muhammad Aidil Akbar', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (625, '20250289', '0088469790', 'Muhammad Furqon Anshori', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (626, '20250290', '0009222955', 'Fedri Kusuma Atmaja', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (627, '20250291', '0032349401', 'Muhammad Dzulfikar', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (628, '20250292', '0026626492', 'Laela Aprilianti', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (629, '20250293', '0083945345', 'Zhulian Anugrahidayat', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (630, '20250294', '0017679491', 'Nadi Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (631, '20250295', '0044589885', 'Farhivy Al Syafa Wardana', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (632, '20250296', '0045434906', 'Elyanzah Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (633, '20250297', '0094315822', 'Faqih Zul Ikhsan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (634, '20250298', '0065324917', 'Khairi Maulana', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (635, '20250299', '0040468790', 'Dika Ramanda Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (636, '20250300', '0022057284', 'Reza Jul Parisman', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (637, '20250301', '0028722332', 'Muhamad Kosim', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (638, '20250302', '0043758560', 'Fajroel Angkala', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (639, '20250303', '0082325862', 'M Ilham', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (640, '20250304', '0052293914', 'Hamdi Firman', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (641, '20250305', '0026515687', 'ANDRA PRATAMA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (642, '20250306', '0004385732', 'DAVA CHAIRUN AZMI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (643, '20250307', '0095356157', 'MUHAMMAD ZALMAN GIVANO', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (644, '20250308', '0066519717', 'ILHAM AFRIANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (645, '20250309', '0031184794', 'SATRIA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (646, '20250310', '0021318243', 'RESTU MARVEL ANDRIANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (647, '20250311', '0019751792', 'Ifan Faiga Febrian', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (648, '20250312', '0066565231', 'Lingga Alifriaga', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (649, '20250313', '0027353460', 'Risky Aditya Aryadi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (650, '20250314', '0015154372', 'Caesar Andra Safitrah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (651, '20250315', '0069876782', 'Rido Ardiansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (652, '20250316', '0030384876', 'Ramdani Saputra', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (653, '20250317', '0057413961', 'Abdi Alfarizy', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (654, '20250318', '0080784112', 'Ramzi Abrian', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (655, '20250319', '0030293722', 'Fahriansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (656, '20250320', '0093492650', 'Rizki Muharram', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (657, '20250321', '0075887074', 'Debian Rifqi Muharar', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (658, '20250322', '0099547065', 'Baharuddin Ghoni', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (659, '20250323', '0040728694', 'Banyu Panji Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (660, '20250324', '0018950005', 'Naqulha Gavan Algazani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (661, '20250325', '0020271397', 'Novila Oktafia', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (662, '20250326', '0028780704', 'Muhammad Ikhsan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (663, '20250327', '0028276250', 'Aryel Oktawijaya', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (664, '20250328', '0021780125', 'Muhammadiah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (665, '20250329', '0051486316', 'Izam Mahendra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (666, '20250330', '0057196254', 'NOPAL OKTA PRANATA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (667, '20250331', '0006908823', 'FAIS FAHRIANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (668, '20250332', '0097140036', 'RAFA ARDIANSYAH PUTRA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (669, '20250333', '0021607787', 'ERAN AFRIANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (670, '20250334', '0041090792', 'ANDIKA PERATAMA SAPUTRA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (671, '20250335', '0059861514', 'KEPEN', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (672, '20250336', '0040104841', 'HAERUL ANWAR', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (673, '20250337', '0093289453', 'Desta Alif Kalamsyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (674, '20250338', '0091970735', 'Sandika Putra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (675, '20250339', '0048541325', 'Ikhwan Azhart', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (676, '20250340', '0033578335', 'Habibu Rahman', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (677, '20250341', '0011522851', 'Aril Yulistiawan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (678, '20250342', '0010933696', 'Aiman Trisandi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 23, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (679, '20250343', '0035666991', 'Ardiansyah Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (680, '20250344', '0040759003', 'Tri Rizki Maulana', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (681, '20250345', '0070634079', 'MUHAMMAD YUSUF', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (682, '20250346', '0038025460', 'SUHERMAN', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (683, '20250347', '0034875878', 'Dafa Oktadi Yansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (684, '20250348', '0046260775', 'Nabil Awalan Januarta', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (685, '20250349', '0014473324', 'M. Revano Herdianto', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (686, '20250350', '0049759655', 'Marek Ardiansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (687, '20250351', '0093209474', 'Afri Muhammad Geri', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (688, '20250352', '0000554826', 'Wahyu Rafi Zabadi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (689, '20250353', '0040502434', 'Andika Putra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (690, '20250354', '0097934225', 'Josua Freddyang Kofan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (691, '20250355', '0079675399', 'Judith Adikara', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (692, '20250356', '0041086977', 'Agas Putra Ramadhansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (693, '20250357', '0050843976', 'Cio Anugraha', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (694, '20250358', '0086318054', 'Quezal Al-Farisi Adit Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (695, '20250359', '0008782350', 'Adriyan Anugrah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (696, '20250360', '0064748046', 'Lalu Muhammad Pandu Dirgantara', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (697, '20250361', '0097144630', 'Abby Al Ghazaly', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (698, '20250362', '0049529402', 'Muhammad Fuady Afdain', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (699, '20250363', '0051928607', 'Ridho Anugrah Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (700, '20250364', '0017363637', 'Oky Juswandi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (701, '20250365', '0050136321', 'Andre Fatwa Perdana', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (702, '20250366', '0077902597', 'Marvel Badila', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (703, '20250367', '0085920023', 'Hasbi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (704, '20250368', '0073801001', 'Andika', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (705, '20250369', '0097037748', 'Khairul Faiz Walidain', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (706, '20250370', '0098346366', 'Ahmad Ilham', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (707, '20250371', '0075603701', 'Riski Aditya Maulana', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (708, '20250372', '0015055331', 'Abid Novia Zahran', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (709, '20250373', '0060787117', 'Fahmi Septia Ramdani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (710, '20250374', '0075255925', 'WAWAN WILIYAN TARI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (711, '20250375', '0045410155', 'REVAN MAULANA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (712, '20250376', '0020218664', 'REZZA ADE KURNIAWAN', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (713, '20250377', '0006808820', 'ANDIKA HENDRA RAMADAN', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (714, '20250378', '0016197908', 'Badrul Majdi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (715, '20250379', '0005490506', 'Aidan Juman Golwani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (716, '20250380', '0060306001', 'Ali Ridho', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (717, '20250381', '0028131932', 'Virga Mahensa Putra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (718, '20250382', '0013196442', 'Firdaus Rahmat Sabani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (719, '20250383', '0032435958', 'Muhammad Ifan Sofyan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (720, '20250384', '0076360150', 'Sultan Devan Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (721, '20250385', '0083443637', 'ALDY AQUA RIZKI P', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (722, '20250386', '0023543100', 'DANDI NOVA SAPUTRA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (723, '20250387', '0056625450', 'DESYA DAYANTA PRATAMA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (724, '20250388', '0040718653', 'Nawafil Agung Mulia', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (725, '20250389', '0017174788', 'Maulana Riski Ramadhani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (726, '20250390', '0084195440', 'Qonita Movisa Wardani', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (727, '20250391', '0039828731', 'Deni Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (728, '20250392', '0070677547', 'Sultan Rahman', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (729, '20250393', '0061333003', 'RISKY AFRIANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:29', '2025-08-03 04:40:29');
INSERT INTO `siswa` VALUES (730, '20250394', '0011686027', 'DARMAWAN ANUGRAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (731, '20250395', '0021616475', 'IQBAL ADE FIRMANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (732, '20250396', '0039586643', 'KHOLIQ ARLANDO JAYADI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (733, '20250397', '0010488410', 'NANANG SAPUTRA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (734, '20250398', '0096916524', 'RIZKI RAMADANI', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (735, '20250399', '0071041315', 'DIKA SANJAYA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (736, '20250400', '0084745590', 'SRI YULIA WARDANI', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (737, '20250401', '0031196025', 'ABDUL FAHRI', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (738, '20250402', '0085918008', 'RIZAL DINATA SAPUTRA', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (739, '20250403', '0078627995', 'Khamil Udin', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (740, '20250404', '0068142919', 'Rifal Febryan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (741, '20250405', '0035700922', 'Dika Gunawan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (742, '20250406', '0041982924', 'Handika Dahrul Iman', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (743, '20250407', '0007178821', 'Juliansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 19, 24, 'umum', 'pending', 'pending', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (744, '20250408', '0037049037', 'Umam Ardy Fachriansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', 20, '2025-08-03 04:40:30', '2025-08-03 04:58:58');
INSERT INTO `siswa` VALUES (745, '20250409', '0048537132', 'RANDY AHMAD ARDIESTA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus_pilihan_2', 24, '2025-08-03 04:40:30', '2025-08-03 04:49:56');
INSERT INTO `siswa` VALUES (746, '20250410', '0077264525', 'ARIYANZA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus_pilihan_2', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (747, '20250411', '0089292629', 'RADHITIA ANUGRAH PRATAMA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', 20, '2025-08-03 04:40:30', '2025-08-03 04:58:30');
INSERT INTO `siswa` VALUES (748, '20250412', '0053697498', 'KARINKA RAFFAELA WIJAYA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus_pilihan_2', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (749, '20250413', '0090620626', 'Jimy Nurislam', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', 20, '2025-08-03 04:40:30', '2025-08-03 04:58:36');
INSERT INTO `siswa` VALUES (750, '20250414', '0011407960', 'Arsy Hartono', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', 20, '2025-08-03 04:40:30', '2025-08-03 04:50:46');
INSERT INTO `siswa` VALUES (751, '20250415', '0018714792', 'Riski Apriliansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', 20, '2025-08-03 04:40:30', '2025-08-03 04:58:23');
INSERT INTO `siswa` VALUES (752, '20250416', '0062619521', 'Muhammad Jumariadi Rizkyka', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', 20, '2025-08-03 04:40:30', '2025-08-03 04:50:39');
INSERT INTO `siswa` VALUES (753, '20250417', '0056682966', 'Giu Hidayat', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', 20, '2025-08-03 04:40:30', '2025-08-03 04:49:31');
INSERT INTO `siswa` VALUES (754, '20250418', '0051491165', 'Muhammad Rizky Gusnaini', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (755, '20250419', '0084928197', 'Chaca Diannita', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (756, '20250420', '0006180681', 'M. Dapa\'in Afrilian', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', 20, '2025-08-03 04:40:30', '2025-08-03 04:58:15');
INSERT INTO `siswa` VALUES (757, '20250421', '0084051534', 'M. Kadhi Sathir Suryatmana', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (758, '20250422', '0076137171', 'Muhammad Iqbal Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (759, '20250423', '0013757503', 'AHMAD MAULANA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', 20, '2025-08-03 04:40:30', '2025-08-03 04:58:44');
INSERT INTO `siswa` VALUES (760, '20250424', '0037193284', 'Faris Abdul Azis', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus_pilihan_2', 24, '2025-08-03 04:40:30', '2025-08-03 04:58:51');
INSERT INTO `siswa` VALUES (761, '20250425', '0012119465', 'Muhammad Temmi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (762, '20250426', '0039417240', 'Muhammad Aqil Anorawi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'lulus', 20, '2025-08-03 04:40:30', '2025-08-03 04:50:52');
INSERT INTO `siswa` VALUES (763, '20250427', '0014899264', 'Muhammad Reza', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 20, 24, 'khusus', 'pending', 'tidak_lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:50:07');
INSERT INTO `siswa` VALUES (764, '20250428', '0060653550', 'Dede Ardiansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (765, '20250429', '0090295828', 'Nanang Alifi Bahri', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (766, '20250430', '0080675515', 'Syamsul Bahri', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (767, '20250431', '0091703157', 'Prambudi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (768, '20250432', '0018378049', 'Restu Adepratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (769, '20250433', '0019893356', 'Syamsul Hidayat', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (770, '20250434', '0019815614', 'Juliadi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (771, '20250435', '0057966471', 'Akbar Wirawan', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (772, '20250436', '0006375572', 'Abdul Muhsi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (773, '20250437', '0025735147', 'RUDIANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (774, '20250438', '0001363088', 'INDRA JAYA GUSPIANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (775, '20250439', '0063263381', 'Fadil Habibi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (776, '20250440', '0046943062', 'Nanang Syaputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (777, '20250441', '0002898216', 'Hamdani Saputra', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (778, '20250442', '0069101171', 'Syahreza Adeka Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (779, '20250443', '0017724037', 'Andika Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (780, '20250444', '0076648423', 'Zulkarnaen', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (781, '20250445', '0093448281', 'RIZQI AL-MANSYAH', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (782, '20250446', '0088289359', 'HAYKAL SA\'BANI', 'P', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (783, '20250447', '0057201903', 'Lutfi Puadi', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (784, '20250448', '0011179566', 'Adnan Muyassar', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (785, '20250449', '0053894244', 'Hafizh Nur Furqoun ', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (786, '20250450', '0086667063', 'Eki Apriansyah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (787, '20250451', '0000051519', 'Aditya Saputra', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (788, '20250452', '0015883768', 'Ari Tri Hartanto', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (789, '20250453', '0042010402', 'Afdal Al Bhany', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (790, '20250454', '0040583699', 'Fitrah Hanugrah', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (791, '20250455', '0087198851', 'Mujammad Ijaz ', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (792, '20250456', '0026107323', 'JULIAN ADE PRATAMA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (793, '20250457', '0072664737', 'RISKI ADITYA', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (794, '20250458', '0047004776', 'Muhammad Irzad Nabil', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (795, '20250459', '0034835674', 'Muhammad Ary Fany', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (796, '20250460', '0057436886', 'Jusni Pahrudin', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');
INSERT INTO `siswa` VALUES (797, '20250461', '0090619237', 'Darmawansyah Okta Pratama', 'L', 'Sumbawa', '2008-11-29', 'Jln Kerangka Baja, Rt/Rw 02/04, Desa Bugis, Sumbawa', 'Subhan', NULL, 'SMPN 1 Sumbawa Besar', 1, 22, 24, 'khusus', 'pending', 'lulus', NULL, '2025-08-03 04:40:30', '2025-08-03 04:40:30');

-- ----------------------------
-- Table structure for tahun_akademik
-- ----------------------------
DROP TABLE IF EXISTS `tahun_akademik`;
CREATE TABLE `tahun_akademik`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_active_year`(`is_active` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tahun_akademik
-- ----------------------------
INSERT INTO `tahun_akademik` VALUES (1, '2024/2025', 'Ganjil', 1, '2024-07-01', '2025-06-30', '2025-07-31 06:16:30', '2025-07-31 06:16:30');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','panitia','ketua_jurusan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_username_unique`(`username` ASC) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  INDEX `users_jurusan_id_foreign`(`jurusan_id` ASC) USING BTREE,
  CONSTRAINT `users_jurusan_id_foreign` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Administrator', 'admin', 'admin@smk2sumbawa.sch.id', NULL, '$2y$12$ocYnEVEj/8DxxhcK7q2u5Oc77US.muOEidHpMxviXc1thJrzvmZPq', 'admin', NULL, 1, NULL, '2025-07-31 06:16:30', '2025-07-31 06:16:30');
INSERT INTO `users` VALUES (2, 'Panitia PPDB', 'panitia', 'panitia@smk2sumbawa.sch.id', NULL, '$2y$12$atymUG29F7Igw/5c4pS5UO6GUeXbkHhCoStzRORdSmO6SR5T4xnJ.', 'panitia', NULL, 1, 'og75HvAhcCeWdlrJZXuUJK4h0iiYDeAF6SPgs64Nhe4z4PwZs3zFOqxtWjSt', '2025-07-31 06:16:31', '2025-07-31 06:16:31');
INSERT INTO `users` VALUES (3, 'fatih', 'fatih', 'fatihur17@gmail.com', NULL, '$2y$12$9DtoMk1bsKBGxSPZG/Z9nO0TRwZSZ86EYU6lPyv0F/OIVZmFYcMlm', 'ketua_jurusan', 20, 1, NULL, '2025-08-01 01:45:15', '2025-08-03 04:21:16');
INSERT INTO `users` VALUES (4, 'wati', 'wati', 'wati@gmail.com', NULL, '$2y$12$sPo0mJZ7pkgCi/YK452dWObAijuqE.QNEGQKjAbhJ.0F9rwValSSu', 'ketua_jurusan', 22, 1, NULL, '2025-08-02 11:56:59', '2025-08-03 04:52:55');
INSERT INTO `users` VALUES (5, 'andi', 'andi', 'fatihur@gmail.com', NULL, '$2y$12$g6fkG4yOYAQUusnnEc0ts.OQPFkZn8jba9/bpXf87fDEt0TW47X2.', 'ketua_jurusan', 19, 1, NULL, '2025-08-03 04:54:20', '2025-08-03 04:54:20');

SET FOREIGN_KEY_CHECKS = 1;
