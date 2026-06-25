-- ===========================================================================
-- SQL DUMP — Migrasi + Seeder
-- Database  : smart_center_indonesia
-- Host      : 127.0.0.1:3306
-- Dibuat    : 2026-06-24 21:11:24
-- Tabel     : 66 tabel
-- Generator : generate_sql_dump.php
-- ===========================================================================

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET TIME_ZONE = '+00:00';
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';


-- ----------------------------------------------------------------------
-- BAGIAN 1 — SCHEMA / MIGRASI (DDL)
-- ----------------------------------------------------------------------

-- Jalankan bagian ini untuk membuat ulang struktur tabel.

-- Tabel: `absensi_gurus`
DROP TABLE IF EXISTS `absensi_gurus`;
CREATE TABLE `absensi_gurus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jadwal_id` bigint(20) unsigned NOT NULL,
  `guru_id` bigint(20) unsigned NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'hadir',
  `catatan` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absensi_gurus_jadwal_id_foreign` (`jadwal_id`),
  KEY `absensi_gurus_guru_id_foreign` (`guru_id`),
  CONSTRAINT `absensi_gurus_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensi_gurus_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `absensi_siswas`
DROP TABLE IF EXISTS `absensi_siswas`;
CREATE TABLE `absensi_siswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jadwal_id` bigint(20) unsigned NOT NULL,
  `siswa_id` bigint(20) unsigned NOT NULL,
  `guru_hadir` tinyint(1) NOT NULL DEFAULT 0,
  `siswa_konfirmasi_at` timestamp NULL DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'hadir',
  `catatan` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absensi_siswas_jadwal_id_foreign` (`jadwal_id`),
  KEY `absensi_siswas_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `absensi_siswas_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensi_siswas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `academic_years`
DROP TABLE IF EXISTS `academic_years`;
CREATE TABLE `academic_years` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `year_start` year(4) NOT NULL,
  `year_end` year(4) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `activity_log`
DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `activity_logs`
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `announcements`
DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `dibuat_oleh` bigint(20) unsigned DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `jenis` varchar(30) NOT NULL DEFAULT 'info',
  `target` varchar(30) NOT NULL DEFAULT 'semua',
  `target_teacher_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`target_teacher_ids`)),
  `target_student_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`target_student_ids`)),
  `file` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `branches`
DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `student_count` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `regency` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `can_students` tinyint(1) NOT NULL DEFAULT 1,
  `can_teachers` tinyint(1) NOT NULL DEFAULT 1,
  `can_schedules` tinyint(1) NOT NULL DEFAULT 1,
  `can_payments` tinyint(1) NOT NULL DEFAULT 1,
  `can_tryouts` tinyint(1) NOT NULL DEFAULT 1,
  `allowed_pages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`allowed_pages`)),
  PRIMARY KEY (`id`),
  KEY `branches_admin_id_foreign` (`admin_id`),
  KEY `branches_status_city_index` (`status`,`city`),
  KEY `branches_created_by_foreign` (`created_by`),
  KEY `branches_updated_by_foreign` (`updated_by`),
  CONSTRAINT `branches_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `branches_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `branches_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `categories`
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `certificates`
DROP TABLE IF EXISTS `certificates`;
CREATE TABLE `certificates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint(20) unsigned DEFAULT NULL,
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `course_id` bigint(20) unsigned DEFAULT NULL,
  `diterbitkan_oleh` varchar(200) DEFAULT NULL,
  `nomor_sertifikat` varchar(255) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_terbit` date DEFAULT NULL,
  `tanggal_expired` date DEFAULT NULL,
  `file_sertifikat` varchar(255) DEFAULT NULL,
  `file_qrcode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `certificates_course_id_foreign` (`course_id`),
  CONSTRAINT `certificates_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `chat_messages`
DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) unsigned NOT NULL,
  `pengirim_id` bigint(20) unsigned NOT NULL,
  `jenis` varchar(20) NOT NULL DEFAULT 'teks',
  `pesan` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `dibaca_oleh` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dibaca_oleh`)),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_room_id_foreign` (`room_id`),
  KEY `chat_messages_pengirim_id_foreign` (`pengirim_id`),
  CONSTRAINT `chat_messages_pengirim_id_foreign` FOREIGN KEY (`pengirim_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chat_messages_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `chat_rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `chat_rooms`
DROP TABLE IF EXISTS `chat_rooms`;
CREATE TABLE `chat_rooms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_room` varchar(100) NOT NULL,
  `jenis_room` varchar(20) NOT NULL DEFAULT 'grup',
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `peserta_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`peserta_id`)),
  `waktu_pesan_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `class_students`
DROP TABLE IF EXISTS `class_students`;
CREATE TABLE `class_students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `class_students_class_id_student_id_unique` (`class_id`,`student_id`),
  KEY `class_students_student_id_foreign` (`student_id`),
  CONSTRAINT `class_students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_students_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `course_fees`
DROP TABLE IF EXISTS `course_fees`;
CREATE TABLE `course_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_fees_course_id_unique` (`course_id`),
  CONSTRAINT `course_fees_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `course_package`
DROP TABLE IF EXISTS `course_package`;
CREATE TABLE `course_package` (
  `package_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`package_id`,`course_id`),
  KEY `course_package_course_id_foreign` (`course_id`),
  CONSTRAINT `course_package_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_package_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `courses`
DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `kode` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) NOT NULL DEFAULT 'academic',
  `deskripsi` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courses_cabang_id_index` (`cabang_id`),
  KEY `courses_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `failed_jobs`
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `gajis`
DROP TABLE IF EXISTS `gajis`;
CREATE TABLE `gajis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `grades`
DROP TABLE IF EXISTS `grades`;
CREATE TABLE `grades` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `siswa_id` bigint(20) unsigned NOT NULL,
  `mata_pelajaran_id` bigint(20) unsigned DEFAULT NULL,
  `guru_id` bigint(20) unsigned DEFAULT NULL,
  `semester_id` bigint(20) unsigned DEFAULT NULL,
  `jenis_penilaian` varchar(50) NOT NULL,
  `nama_penilaian` varchar(100) DEFAULT NULL,
  `nilai` decimal(5,2) NOT NULL,
  `nilai_maksimal` decimal(5,2) NOT NULL DEFAULT 100.00,
  `bobot` decimal(5,2) NOT NULL DEFAULT 1.00,
  `tanggal` date DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grades_siswa_id_foreign` (`siswa_id`),
  KEY `grades_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `grades_guru_id_foreign` (`guru_id`),
  KEY `grades_semester_id_foreign` (`semester_id`),
  CONSTRAINT `grades_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `grades_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `grades_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `grades_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `guru_mapel`
DROP TABLE IF EXISTS `guru_mapel`;
CREATE TABLE `guru_mapel` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `gurus`
DROP TABLE IF EXISTS `gurus`;
CREATE TABLE `gurus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `invoices`
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `siswa_id` bigint(20) unsigned DEFAULT NULL,
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `kelas_id` bigint(20) unsigned DEFAULT NULL,
  `nomor_invoice` varchar(255) DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `diskon` decimal(15,2) NOT NULL DEFAULT 0.00,
  `pajak` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `deskripsi` text DEFAULT NULL,
  `periode` varchar(50) DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `status` enum('belum_bayar','sebagian','lunas') NOT NULL DEFAULT 'belum_bayar',
  `catatan` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_nomor_invoice_unique` (`nomor_invoice`),
  KEY `invoices_siswa_id_index` (`siswa_id`),
  KEY `invoices_cabang_id_index` (`cabang_id`),
  KEY `invoices_jatuh_tempo_index` (`jatuh_tempo`),
  KEY `invoices_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `jadwals`
DROP TABLE IF EXISTS `jadwals`;
CREATE TABLE `jadwals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `kelas`
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `kelas_siswa`
DROP TABLE IF EXISTS `kelas_siswa`;
CREATE TABLE `kelas_siswa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `landing_programs`
DROP TABLE IF EXISTS `landing_programs`;
CREATE TABLE `landing_programs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `badge_label` varchar(80) NOT NULL DEFAULT 'PROGRAM',
  `badge_bg` varchar(255) NOT NULL DEFAULT 'rgba(200,77,223,.1)',
  `badge_color` varchar(255) NOT NULL DEFAULT '#68117e',
  `icon_emoji` varchar(10) NOT NULL DEFAULT '?',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `landing_settings`
DROP TABLE IF EXISTS `landing_settings`;
CREATE TABLE `landing_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(60) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` longtext DEFAULT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'text',
  `label` varchar(150) NOT NULL,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `landing_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `landing_testimonials`
DROP TABLE IF EXISTS `landing_testimonials`;
CREATE TABLE `landing_testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `gradient` varchar(255) NOT NULL DEFAULT 'linear-gradient(135deg,#c84ddf,#68117e)',
  `initial` varchar(5) NOT NULL DEFAULT 'A',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `landing_wa_numbers`
DROP TABLE IF EXISTS `landing_wa_numbers`;
CREATE TABLE `landing_wa_numbers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `number` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `mapel_paket`
DROP TABLE IF EXISTS `mapel_paket`;
CREATE TABLE `mapel_paket` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `mapels`
DROP TABLE IF EXISTS `mapels`;
CREATE TABLE `mapels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `migrations`
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `model_has_permissions`
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `model_has_roles`
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `modules`
DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_modul` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mata_pelajaran_id` bigint(20) unsigned DEFAULT NULL,
  `diupload_oleh` bigint(20) unsigned DEFAULT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `jenis` varchar(20) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `ukuran_file` bigint(20) unsigned DEFAULT NULL,
  `is_gratis` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `jumlah_download` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_kode_modul_unique` (`kode_modul`),
  KEY `modules_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `modules_diupload_oleh_foreign` (`diupload_oleh`),
  CONSTRAINT `modules_diupload_oleh_foreign` FOREIGN KEY (`diupload_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `modules_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `moduls`
DROP TABLE IF EXISTS `moduls`;
CREATE TABLE `moduls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `nilais`
DROP TABLE IF EXISTS `nilais`;
CREATE TABLE `nilais` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `packages`
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `guru_id` bigint(20) unsigned DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `durasi_bulan` int(10) unsigned NOT NULL DEFAULT 1,
  `jumlah_pertemuan` int(10) unsigned NOT NULL DEFAULT 1,
  `jenis` varchar(50) NOT NULL,
  `metode_absensi` varchar(30) NOT NULL DEFAULT 'manual',
  `tipe_kelas` varchar(30) NOT NULL DEFAULT 'offline',
  `fitur` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fitur`)),
  `is_unggulan` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'aktif',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `packages_cabang_id_foreign` (`cabang_id`),
  KEY `packages_guru_id_foreign` (`guru_id`),
  CONSTRAINT `packages_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `packages_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `pakets`
DROP TABLE IF EXISTS `pakets`;
CREATE TABLE `pakets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `password_resets`
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `payments`
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice_id` bigint(20) unsigned DEFAULT NULL,
  `siswa_id` bigint(20) unsigned DEFAULT NULL,
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `nomor_pembayaran` varchar(255) DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `metode` enum('cash','transfer','qris') NOT NULL DEFAULT 'cash',
  `nama_bank` varchar(255) DEFAULT NULL,
  `nomor_rekening` varchar(255) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `alasan_penolakan` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `disetujui_oleh` bigint(20) unsigned DEFAULT NULL,
  `tanggal_disetujui` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_nomor_pembayaran_unique` (`nomor_pembayaran`),
  KEY `payments_invoice_id_index` (`invoice_id`),
  KEY `payments_siswa_id_index` (`siswa_id`),
  KEY `payments_cabang_id_index` (`cabang_id`),
  KEY `payments_tanggal_pembayaran_index` (`tanggal_pembayaran`),
  KEY `payments_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `pembayarans`
DROP TABLE IF EXISTS `pembayarans`;
CREATE TABLE `pembayarans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `permissions`
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `personal_access_tokens`
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `questions`
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tryout_id` bigint(20) unsigned NOT NULL,
  `teks_pertanyaan` text NOT NULL,
  `gambar_pertanyaan` varchar(255) DEFAULT NULL,
  `jenis` varchar(30) NOT NULL DEFAULT 'pilihan_ganda',
  `pilihan_jawaban` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pilihan_jawaban`)),
  `kunci_jawaban` varchar(10) DEFAULT NULL,
  `penjelasan` text DEFAULT NULL,
  `poin` decimal(5,2) NOT NULL DEFAULT 1.00,
  `urutan` int(10) unsigned NOT NULL DEFAULT 1,
  `tingkat_kesulitan` varchar(20) NOT NULL DEFAULT 'sedang',
  PRIMARY KEY (`id`),
  KEY `questions_tryout_id_foreign` (`tryout_id`),
  CONSTRAINT `questions_tryout_id_foreign` FOREIGN KEY (`tryout_id`) REFERENCES `tryouts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `role_has_permissions`
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `roles`
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `salaries`
DROP TABLE IF EXISTS `salaries`;
CREATE TABLE `salaries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `guru_id` bigint(20) unsigned NOT NULL,
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `periode` varchar(20) NOT NULL,
  `tipe_gaji` varchar(50) NOT NULL DEFAULT 'bulanan',
  `gaji_pokok` decimal(12,2) NOT NULL DEFAULT 0.00,
  `jam_mengajar` decimal(6,1) DEFAULT NULL,
  `tarif_per_jam` decimal(12,2) DEFAULT NULL,
  `total_gaji_mengajar` decimal(12,2) NOT NULL DEFAULT 0.00,
  `bonus` decimal(12,2) NOT NULL DEFAULT 0.00,
  `potongan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_gaji` decimal(12,2) NOT NULL DEFAULT 0.00,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `nama_bank` varchar(50) DEFAULT NULL,
  `nomor_rekening` varchar(50) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `dibayar_oleh` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `salaries_guru_id_foreign` (`guru_id`),
  KEY `salaries_cabang_id_foreign` (`cabang_id`),
  KEY `salaries_dibayar_oleh_foreign` (`dibayar_oleh`),
  CONSTRAINT `salaries_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `salaries_dibayar_oleh_foreign` FOREIGN KEY (`dibayar_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `salaries_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `schedule_proposal_approvals`
DROP TABLE IF EXISTS `schedule_proposal_approvals`;
CREATE TABLE `schedule_proposal_approvals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) unsigned NOT NULL,
  `approver_type` enum('guru','siswa') NOT NULL,
  `approver_id` bigint(20) unsigned NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proposal_approvals_unique` (`proposal_id`,`approver_type`,`approver_id`),
  CONSTRAINT `schedule_proposal_approvals_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `schedule_proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `schedule_proposals`
DROP TABLE IF EXISTS `schedule_proposals`;
CREATE TABLE `schedule_proposals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint(20) unsigned NOT NULL,
  `pertemuan_ke` tinyint(3) unsigned DEFAULT NULL,
  `proposed_by_type` enum('guru','siswa') NOT NULL,
  `proposed_by_id` bigint(20) unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `jenis` enum('online','offline','private') NOT NULL DEFAULT 'offline',
  `ruangan` varchar(255) DEFAULT NULL,
  `link_meeting` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `schedule_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Filled after approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedule_proposals_class_id_foreign` (`class_id`),
  KEY `schedule_proposals_schedule_id_foreign` (`schedule_id`),
  CONSTRAINT `schedule_proposals_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedule_proposals_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `schedule_student_agreements`
DROP TABLE IF EXISTS `schedule_student_agreements`;
CREATE TABLE `schedule_student_agreements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint(20) unsigned NOT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `guru_confirmed_at` timestamp NULL DEFAULT NULL,
  `siswa_confirmed_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','agreed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `schedule_student_agreements_schedule_id_student_id_unique` (`schedule_id`,`student_id`),
  KEY `schedule_student_agreements_student_id_foreign` (`student_id`),
  CONSTRAINT `schedule_student_agreements_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedule_student_agreements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `schedules`
DROP TABLE IF EXISTS `schedules`;
CREATE TABLE `schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kelas_id` bigint(20) unsigned DEFAULT NULL,
  `paket_id` bigint(20) unsigned DEFAULT NULL,
  `module_id` bigint(20) unsigned DEFAULT NULL,
  `guru_id` bigint(20) unsigned DEFAULT NULL,
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `pertemuan_ke` smallint(5) unsigned DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `topik` varchar(255) DEFAULT NULL,
  `jenis` enum('online','offline','private') NOT NULL DEFAULT 'offline',
  `ruangan` varchar(255) DEFAULT NULL,
  `link_meeting` varchar(255) DEFAULT NULL,
  `status` enum('dijadwalkan','berlangsung','selesai','dibatalkan') NOT NULL DEFAULT 'dijadwalkan',
  `catatan` text DEFAULT NULL,
  `reminder_terkirim` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedules_guru_id_index` (`guru_id`),
  KEY `schedules_cabang_id_index` (`cabang_id`),
  KEY `schedules_tanggal_index` (`tanggal`),
  KEY `schedules_paket_id_foreign` (`paket_id`),
  CONSTRAINT `schedules_paket_id_foreign` FOREIGN KEY (`paket_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `school_classes`
DROP TABLE IF EXISTS `school_classes`;
CREATE TABLE `school_classes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `mata_pelajaran_id` bigint(20) unsigned DEFAULT NULL,
  `guru_id` bigint(20) unsigned DEFAULT NULL,
  `tahun_akademik_id` bigint(20) unsigned DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nama_kelas` varchar(255) DEFAULT NULL,
  `kapasitas` smallint(5) unsigned NOT NULL DEFAULT 30,
  `jumlah_pertemuan` smallint(5) unsigned NOT NULL DEFAULT 1,
  `jenis` enum('online','offline','private') NOT NULL DEFAULT 'offline',
  `link_zoom` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `billing_mode` varchar(20) DEFAULT 'prepaid',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_classes_cabang_id_index` (`cabang_id`),
  KEY `school_classes_guru_id_index` (`guru_id`),
  KEY `school_classes_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `semesters`
DROP TABLE IF EXISTS `semesters`;
CREATE TABLE `semesters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `academic_year_id` bigint(20) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `semester_number` tinyint(4) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `semesters_academic_year_id_foreign` (`academic_year_id`),
  CONSTRAINT `semesters_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `siswas`
DROP TABLE IF EXISTS `siswas`;
CREATE TABLE `siswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `student_course_payments`
DROP TABLE IF EXISTS `student_course_payments`;
CREATE TABLE `student_course_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `proof` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `rejected_reason` text DEFAULT NULL,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_course_payments_student_id_foreign` (`student_id`),
  KEY `student_course_payments_course_id_foreign` (`course_id`),
  KEY `student_course_payments_verified_by_foreign` (`verified_by`),
  CONSTRAINT `student_course_payments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_course_payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_course_payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `student_registrations`
DROP TABLE IF EXISTS `student_registrations`;
CREATE TABLE `student_registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no_reg` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `education_level` varchar(255) DEFAULT NULL,
  `birth_place` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `parent_name` varchar(255) DEFAULT NULL,
  `parent_phone` varchar(255) DEFAULT NULL,
  `job` varchar(255) DEFAULT NULL,
  `program` varchar(255) DEFAULT NULL,
  `system` varchar(255) DEFAULT NULL,
  `learning_place` varchar(255) DEFAULT NULL,
  `pickup_mode` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `interests` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interests`)),
  `day_preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`day_preferences`)),
  `schedule_time` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_registrations_no_reg_unique` (`no_reg`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `student_teachers`
DROP TABLE IF EXISTS `student_teachers`;
CREATE TABLE `student_teachers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_teachers_student_id_teacher_id_unique` (`student_id`,`teacher_id`),
  KEY `student_teachers_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `student_teachers_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_teachers_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `students`
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `package_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `nis` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('L','P') NOT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `parent_name` varchar(255) DEFAULT NULL,
  `parent_phone` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'aktif',
  `join_date` date DEFAULT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `kategori_peserta_didik` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_nis_unique` (`nis`),
  KEY `students_branch_id_foreign` (`branch_id`),
  KEY `students_user_id_foreign` (`user_id`),
  KEY `students_package_id_foreign` (`package_id`),
  CONSTRAINT `students_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `students_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `system_settings`
DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `tagihans`
DROP TABLE IF EXISTS `tagihans`;
CREATE TABLE `tagihans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `tahun_ajarans`
DROP TABLE IF EXISTS `tahun_ajarans`;
CREATE TABLE `tahun_ajarans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `teacher_courses`
DROP TABLE IF EXISTS `teacher_courses`;
CREATE TABLE `teacher_courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_courses_teacher_id_course_id_unique` (`teacher_id`,`course_id`),
  KEY `teacher_courses_course_id_foreign` (`course_id`),
  CONSTRAINT `teacher_courses_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_courses_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `teachers`
DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `nig` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('L','P') DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `subjects` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `salary_base` decimal(15,2) NOT NULL DEFAULT 0.00,
  `join_date` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `jenis_guru` varchar(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teachers_nig_unique` (`nig`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `tryout_attempts`
DROP TABLE IF EXISTS `tryout_attempts`;
CREATE TABLE `tryout_attempts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tryout_id` bigint(20) unsigned NOT NULL,
  `siswa_id` bigint(20) unsigned NOT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `nilai` decimal(5,2) DEFAULT NULL,
  `jawaban_benar` int(10) unsigned NOT NULL DEFAULT 0,
  `jawaban_salah` int(10) unsigned NOT NULL DEFAULT 0,
  `tidak_dijawab` int(10) unsigned NOT NULL DEFAULT 0,
  `percobaan_ke` int(10) unsigned NOT NULL DEFAULT 1,
  `status` varchar(20) NOT NULL DEFAULT 'berlangsung',
  `jawaban` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jawaban`)),
  PRIMARY KEY (`id`),
  KEY `tryout_attempts_tryout_id_foreign` (`tryout_id`),
  KEY `tryout_attempts_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `tryout_attempts_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tryout_attempts_tryout_id_foreign` FOREIGN KEY (`tryout_id`) REFERENCES `tryouts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `tryouts`
DROP TABLE IF EXISTS `tryouts`;
CREATE TABLE `tryouts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `dibuat_oleh` bigint(20) unsigned DEFAULT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `kategori` varchar(50) NOT NULL,
  `durasi_menit` int(10) unsigned NOT NULL DEFAULT 60,
  `total_soal` int(10) unsigned NOT NULL DEFAULT 0,
  `nilai_kelulusan` decimal(5,2) DEFAULT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `is_random` tinyint(1) NOT NULL DEFAULT 0,
  `tampilkan_hasil_langsung` tinyint(1) NOT NULL DEFAULT 1,
  `tampilkan_kunci_jawaban` tinyint(1) NOT NULL DEFAULT 0,
  `maksimal_percobaan` int(10) unsigned DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tryouts_cabang_id_foreign` (`cabang_id`),
  KEY `tryouts_dibuat_oleh_foreign` (`dibuat_oleh`),
  CONSTRAINT `tryouts_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tryouts_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================================
-- Akhir bagian SCHEMA
-- ===========================================================================


-- ----------------------------------------------------------------------
-- BAGIAN 2 — DATA / SEEDER (DML)
-- ----------------------------------------------------------------------

-- Jalankan bagian ini untuk mengisi data (setelah schema sudah ada).

-- ---- Tabel: `absensi_gurus` (0 baris) ----
-- (kosong)

-- ---- Tabel: `absensi_siswas` (0 baris) ----
-- (kosong)

-- ---- Tabel: `academic_years` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `academic_years` (`id`, `name`, `year_start`, `year_end`, `is_active`, `created_at`, `updated_at`) VALUES
  ('1', '2024/2025', '2024', '2025', '1', '2026-06-25 04:07:07', '2026-06-25 04:07:07'),
  ('2', '2023/2024', '2023', '2024', '0', '2026-06-25 04:07:07', '2026-06-25 04:07:07');

-- ---- Tabel: `activity_log` (28 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
  ('1', 'default', 'created', 'App\\Models\\User', 'created', '1', NULL, NULL, '{\"attributes\":{\"id\":1,\"name\":\"Admin Pusat SCI\",\"username\":null,\"email\":\"adminpusatsci@akademi.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$GI2ymsFL8ShR0tvhd26f0.sHupaBlrJ74LlhuGtNog8UWApTQKj1O\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:06.000000Z\",\"updated_at\":\"2026-06-24T21:07:06.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:07', '2026-06-25 04:07:07'),
  ('2', 'default', 'created', 'App\\Models\\User', 'created', '2', NULL, NULL, '{\"attributes\":{\"id\":2,\"name\":\"Admin Cabang SCI\",\"username\":null,\"email\":\"admincabangsci@akademi.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$Ec6.2gc4m4H20Zu3rJcCv.AZpjmazTKoZtZ1fzJ\\/\\/8r161eJjGG9C\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:07.000000Z\",\"updated_at\":\"2026-06-24T21:07:07.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:07', '2026-06-25 04:07:07'),
  ('3', 'default', 'updated', 'App\\Models\\User', 'updated', '1', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$5PJ2.WLCRHAi28aBT66QeedqN6cE8RAidKdJqmmmetpfiPVigC3wS\",\"updated_at\":\"2026-06-24T21:07:07.000000Z\"},\"old\":{\"password\":\"$2y$10$GI2ymsFL8ShR0tvhd26f0.sHupaBlrJ74LlhuGtNog8UWApTQKj1O\",\"updated_at\":\"2026-06-24T21:07:06.000000Z\"}}', NULL, '2026-06-25 04:07:07', '2026-06-25 04:07:07'),
  ('4', 'default', 'created', 'App\\Models\\User', 'created', '3', NULL, NULL, '{\"attributes\":{\"id\":3,\"name\":\"Admin Pusat Jakarta\",\"username\":null,\"email\":\"adminpusat@akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$A8PdA4kqN9ekEcOKzBWTYuhfBR7wfkIyyqY3sz08gKB8kMB.jK0BC\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:07.000000Z\",\"updated_at\":\"2026-06-24T21:07:07.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:07', '2026-06-25 04:07:07'),
  ('5', 'default', 'created', 'App\\Models\\User', 'created', '4', NULL, NULL, '{\"attributes\":{\"id\":4,\"name\":\"Admin Cabang Bandung\",\"username\":null,\"email\":\"adminbandung@akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":2,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$lwXMCRedvU6PiFhs8teJKOY7SPL9hvx4xuR7KcMEjVmI36H2nafry\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:07.000000Z\",\"updated_at\":\"2026-06-24T21:07:07.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:07', '2026-06-25 04:07:07'),
  ('6', 'default', 'created', 'App\\Models\\User', 'created', '5', NULL, NULL, '{\"attributes\":{\"id\":5,\"name\":\"Admin Cabang Surabaya\",\"username\":null,\"email\":\"adminsurabaya@akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$r\\/vO8g0n5aOopE57ylKg5OJXPIu0LaTTqzt3c0Ec3Jfq.2zhux0CG\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:07.000000Z\",\"updated_at\":\"2026-06-24T21:07:07.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:07', '2026-06-25 04:07:07'),
  ('7', 'default', 'created', 'App\\Models\\User', 'created', '6', NULL, NULL, '{\"attributes\":{\"id\":6,\"name\":\"Budi Santoso, S.Pd.\",\"username\":null,\"email\":\"budi.santoso@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$g\\/PsWUFoWxiVIRStZYABT.fRJwSWma9u0TOenOW09V7UNBoPheX9q\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:08.000000Z\",\"updated_at\":\"2026-06-24T21:07:08.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('8', 'default', 'created', 'App\\Models\\User', 'created', '7', NULL, NULL, '{\"attributes\":{\"id\":7,\"name\":\"Sari Dewi, M.Sc.\",\"username\":null,\"email\":\"sari.dewi@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$3DbdwdkhG8Xkrwih7pkipeeZpBtMkBw0IZQx4xcX0.zcmKIjhkJm2\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:08.000000Z\",\"updated_at\":\"2026-06-24T21:07:08.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('9', 'default', 'created', 'App\\Models\\User', 'created', '8', NULL, NULL, '{\"attributes\":{\"id\":8,\"name\":\"Rizky Pratama, S.Pd.\",\"username\":null,\"email\":\"rizky.pratama@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$4hxwjORoj\\/yC2xmEZd\\/e9urISIWV.yvWw\\/\\/T2gaeqpOXuX38N79CS\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:08.000000Z\",\"updated_at\":\"2026-06-24T21:07:08.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('10', 'default', 'created', 'App\\Models\\User', 'created', '9', NULL, NULL, '{\"attributes\":{\"id\":9,\"name\":\"Ahmad Fauzi, S.Si.\",\"username\":null,\"email\":\"gurusci@gmail.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$bU88T9pjYz7rqbf8p.0lpuHDRHNR.VTnlvzaTJ1uvcMzhAL20az4C\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:08.000000Z\",\"updated_at\":\"2026-06-24T21:07:08.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('11', 'default', 'created', 'App\\Models\\User', 'created', '10', NULL, NULL, '{\"attributes\":{\"id\":10,\"name\":\"Hani Rahayu, S.Pd.\",\"username\":null,\"email\":\"hani.rahayu@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":2,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$cWyQQNKaUri5xk.PH8JUUOES9oBPIVwwB7Qx5PwMDSA5lsuWt5U0K\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:08.000000Z\",\"updated_at\":\"2026-06-24T21:07:08.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('12', 'default', 'created', 'App\\Models\\User', 'created', '11', NULL, NULL, '{\"attributes\":{\"id\":11,\"name\":\"Dimas Arya, S.Pd.\",\"username\":null,\"email\":\"dimas.arya@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":2,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$UXOaSSY3nqy7OQQhRrYo2u7NUfF7gzwFknqDmhBzYbrYt7Ievfnke\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:09.000000Z\",\"updated_at\":\"2026-06-24T21:07:09.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09'),
  ('13', 'default', 'created', 'App\\Models\\User', 'created', '12', NULL, NULL, '{\"attributes\":{\"id\":12,\"name\":\"Yuni Kartika, M.Pd.\",\"username\":null,\"email\":\"yuni.kartika@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$bVZh7MrhWTRCrzRnH4Byx.nEsHNf94.CTEUgMZGZFlJhkefP8893i\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:09.000000Z\",\"updated_at\":\"2026-06-24T21:07:09.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09'),
  ('14', 'default', 'created', 'App\\Models\\User', 'created', '13', NULL, NULL, '{\"attributes\":{\"id\":13,\"name\":\"Andi Nugroho\",\"username\":null,\"email\":\"andi.nugroho@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$nXlrRGZubin44GaLZ8UB9.hbfVK\\/59hxUkk9CRD85NmRn.nfJ6fMq\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:09.000000Z\",\"updated_at\":\"2026-06-24T21:07:09.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09'),
  ('15', 'default', 'created', 'App\\Models\\User', 'created', '14', NULL, NULL, '{\"attributes\":{\"id\":14,\"name\":\"Citra Lestari\",\"username\":null,\"email\":\"citra.lestari@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$N7HaCu8LZJkikQvm5h.Si.uzOpsmKpsjBg00PE5EzCypWdAEiSCK6\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:09.000000Z\",\"updated_at\":\"2026-06-24T21:07:09.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09'),
  ('16', 'default', 'created', 'App\\Models\\User', 'created', '15', NULL, NULL, '{\"attributes\":{\"id\":15,\"name\":\"Fajar Hidayat\",\"username\":null,\"email\":\"fajar.hidayat@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$SjeLSjJnfvYsDDXk9\\/IXZuYTBEF1te\\/pUK1QDN5hIbJ\\/D5W7C5anO\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:09.000000Z\",\"updated_at\":\"2026-06-24T21:07:09.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09'),
  ('17', 'default', 'created', 'App\\Models\\User', 'created', '16', NULL, NULL, '{\"attributes\":{\"id\":16,\"name\":\"Gita Permata\",\"username\":null,\"email\":\"gita.permata@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$5KTRjDbpxJvhHhV8buO3f.BfaAm.2w5ppN0cCVgNMzRasp9pH03O.\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:09.000000Z\",\"updated_at\":\"2026-06-24T21:07:09.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09'),
  ('18', 'default', 'created', 'App\\Models\\User', 'created', '17', NULL, NULL, '{\"attributes\":{\"id\":17,\"name\":\"Hendra Putra\",\"username\":null,\"email\":\"hendra.putra@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$sqlEj6e949MwDPyz.v02NuC\\/OqXWGH0Xab447dsa4EUG8cjK79Qgm\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:10.000000Z\",\"updated_at\":\"2026-06-24T21:07:10.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10'),
  ('19', 'default', 'created', 'App\\Models\\User', 'created', '18', NULL, NULL, '{\"attributes\":{\"id\":18,\"name\":\"Indah Sari\",\"username\":null,\"email\":\"indah.sari@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$jZ2WuarPeFmLNDbhdUq2sesGHqHvfrQrGAWin4.dvcyYwoohB0M.i\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:10.000000Z\",\"updated_at\":\"2026-06-24T21:07:10.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10'),
  ('20', 'default', 'created', 'App\\Models\\User', 'created', '19', NULL, NULL, '{\"attributes\":{\"id\":19,\"name\":\"Joko Santoso\",\"username\":null,\"email\":\"joko.santoso@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$QLE1EkfsvDZgJYNR85QVburZtghAkGVXw7Xs5AdGyO.2Awmpis0gS\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:10.000000Z\",\"updated_at\":\"2026-06-24T21:07:10.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10'),
  ('21', 'default', 'created', 'App\\Models\\User', 'created', '20', NULL, NULL, '{\"attributes\":{\"id\":20,\"name\":\"Kartini Wulandari\",\"username\":null,\"email\":\"kartini.wulandari@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$Bmr4seUy.YbczJZgMFery.zQnCOe\\/Y7W18Rd5UW8ODLNAPGTNw462\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:10.000000Z\",\"updated_at\":\"2026-06-24T21:07:10.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10'),
  ('22', 'default', 'created', 'App\\Models\\User', 'created', '21', NULL, NULL, '{\"attributes\":{\"id\":21,\"name\":\"Luthfi Rahman\",\"username\":null,\"email\":\"luthfi.rahman@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":2,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$2GLEVSVXGaiTkSLUjK9dXeK60.8LZ9YRDbx8bD2U4jvSd0UxQf3OG\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:10.000000Z\",\"updated_at\":\"2026-06-24T21:07:10.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10'),
  ('23', 'default', 'created', 'App\\Models\\User', 'created', '22', NULL, NULL, '{\"attributes\":{\"id\":22,\"name\":\"Mira Kusuma\",\"username\":null,\"email\":\"mira.kusuma@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":2,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$piyuB\\/qnb\\/DlxOgFLN1\\/B.fEL2\\/QebdG3Fbw\\/Y\\/ftdmCM6AIq8SuS\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:10.000000Z\",\"updated_at\":\"2026-06-24T21:07:10.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10'),
  ('24', 'default', 'created', 'App\\Models\\User', 'created', '23', NULL, NULL, '{\"attributes\":{\"id\":23,\"name\":\"Naufal Ardiansyah\",\"username\":null,\"email\":\"naufal.ardiansyah@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":2,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$mBt5i8CgzB6Ifs0GA6wyjeK5FaNkPEDlPVGFGkih0BC9lcj155iJC\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:10.000000Z\",\"updated_at\":\"2026-06-24T21:07:10.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10'),
  ('25', 'default', 'created', 'App\\Models\\User', 'created', '24', NULL, NULL, '{\"attributes\":{\"id\":24,\"name\":\"Olivia Putri\",\"username\":null,\"email\":\"olivia.putri@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":2,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$fwiTsOCiWxyy6mNYrsz9qOSPypzpl0KvJ7x\\/g8AZfGCODZ7cxvtE2\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:10.000000Z\",\"updated_at\":\"2026-06-24T21:07:10.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:11', '2026-06-25 04:07:11'),
  ('26', 'default', 'created', 'App\\Models\\User', 'created', '25', NULL, NULL, '{\"attributes\":{\"id\":25,\"name\":\"Prasetyo Adi\",\"username\":null,\"email\":\"prasetyo.adi@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$5gDBZ8MUgwzNpVrEE7n4X.Dtyu8JDr6SfMZusoC5.0Ov28.fjX6jq\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:11.000000Z\",\"updated_at\":\"2026-06-24T21:07:11.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:11', '2026-06-25 04:07:11'),
  ('27', 'default', 'created', 'App\\Models\\User', 'created', '26', NULL, NULL, '{\"attributes\":{\"id\":26,\"name\":\"Rini Agustina\",\"username\":null,\"email\":\"rini.agustina@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$b5\\/6JcErUbra98v\\/3Kl.geeO84QmYN\\/iBk.7VsOg5klkJfxQFk0H2\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:11.000000Z\",\"updated_at\":\"2026-06-24T21:07:11.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:11', '2026-06-25 04:07:11'),
  ('28', 'default', 'created', 'App\\Models\\User', 'created', '27', NULL, NULL, '{\"attributes\":{\"id\":27,\"name\":\"Sandi Kurniawan\",\"username\":null,\"email\":\"sandi.kurniawan@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$7\\/US7xeRyW1yJGgqTmCMKOMnAFS9VFW80Ao4OKqrYY1iTvbllYHoe\",\"remember_token\":null,\"created_at\":\"2026-06-24T21:07:11.000000Z\",\"updated_at\":\"2026-06-24T21:07:11.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 04:07:11', '2026-06-25 04:07:11');

-- ---- Tabel: `activity_logs` (0 baris) ----
-- (kosong)

-- ---- Tabel: `announcements` (4 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `announcements` (`id`, `cabang_id`, `dibuat_oleh`, `judul`, `konten`, `jenis`, `target`, `target_teacher_ids`, `target_student_ids`, `file`, `tanggal_mulai`, `tanggal_selesai`, `is_pinned`, `status`, `created_at`, `updated_at`) VALUES
  ('1', '1', '1', 'Jadwal Tryout SNBT Nasional Februari 2025', 'Kepada seluruh peserta program Intensif SNBT, tryout nasional akan dilaksanakan pada tanggal 15 Februari 2025. Harap mempersiapkan diri sebaik mungkin. Tryout akan berlangsung selama 3 jam dan mencakup semua subtes SNBT. Informasi lebih lanjut akan disampaikan melalui WhatsApp grup.', 'penting', 'siswa', NULL, NULL, NULL, '2026-06-20', '2026-07-15', '1', 'aktif', '2026-06-25 04:07:12', '2026-06-25 04:07:12'),
  ('2', '1', '1', 'Libur Kelas: Hari Kemerdekaan RI 17 Agustus', 'Diberitahukan kepada seluruh siswa dan guru bahwa tidak ada kegiatan belajar mengajar pada tanggal 17 Agustus 2024 dalam rangka Hari Kemerdekaan Republik Indonesia ke-79. Kelas akan dilanjutkan pada jadwal berikutnya.', 'informasi', 'semua', NULL, NULL, NULL, '2026-06-15', '2026-06-30', '0', 'aktif', '2026-06-25 04:07:12', '2026-06-25 04:07:12'),
  ('3', '1', '1', 'Rapat Guru Bulanan - Evaluasi Pembelajaran', 'Seluruh guru diwajibkan hadir dalam rapat bulanan evaluasi pembelajaran yang akan dilaksanakan pada hari Sabtu, pukul 09.00 WIB di ruang rapat utama. Agenda: evaluasi capaian siswa, persiapan ujian akhir semester, dan koordinasi jadwal.', 'penting', 'guru', NULL, NULL, NULL, '2026-06-23', '2026-06-28', '1', 'aktif', '2026-06-25 04:07:12', '2026-06-25 04:07:12'),
  ('4', '2', '1', 'Promo Daftar Bimbel Bandung - Diskon 20%', 'Spesial untuk pendaftar baru di Cabang Bandung bulan ini, dapatkan diskon 20% untuk bulan pertama. Berlaku hingga akhir bulan. Hubungi admin untuk informasi pendaftaran.', 'informasi', 'semua', NULL, NULL, NULL, '2026-06-22', '2026-06-30', '0', 'aktif', '2026-06-25 04:07:12', '2026-06-25 04:07:12');

-- ---- Tabel: `branches` (3 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `branches` (`id`, `user_id`, `name`, `address`, `phone`, `city`, `admin_id`, `created_by`, `updated_by`, `student_count`, `status`, `created_at`, `updated_at`, `regency`, `email`, `password`, `can_students`, `can_teachers`, `can_schedules`, `can_payments`, `can_tryouts`, `allowed_pages`) VALUES
  ('1', NULL, 'Cabang Pusat Jakarta', 'Jl. Sudirman No. 10, Kebayoran Baru, Jakarta Selatan', '021-5551001', 'Jakarta', '3', NULL, NULL, '0', 'active', '2026-06-25 04:07:07', '2026-06-25 04:07:07', 'Jakarta Selatan', 'cabang.pusat@akademibimbel.com', NULL, '1', '1', '1', '1', '1', NULL),
  ('2', NULL, 'Cabang Bandung', 'Jl. Dago No. 25, Coblong, Bandung', '022-2501234', 'Bandung', '4', NULL, NULL, '0', 'active', '2026-06-25 04:07:07', '2026-06-25 04:07:07', 'Bandung Kota', 'cabang.bandung@akademibimbel.com', NULL, '1', '1', '1', '1', '1', NULL),
  ('3', NULL, 'Cabang Surabaya', 'Jl. Raya Darmo No. 8, Wonokromo, Surabaya', '031-5671234', 'Surabaya', '5', NULL, NULL, '0', 'active', '2026-06-25 04:07:07', '2026-06-25 04:07:07', 'Surabaya Pusat', 'cabang.surabaya@akademibimbel.com', NULL, '1', '1', '1', '1', '0', NULL);

-- ---- Tabel: `categories` (0 baris) ----
-- (kosong)

-- ---- Tabel: `certificates` (3 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `certificates` (`id`, `siswa_id`, `cabang_id`, `course_id`, `diterbitkan_oleh`, `nomor_sertifikat`, `jenis`, `judul`, `deskripsi`, `tanggal_terbit`, `tanggal_expired`, `file_sertifikat`, `file_qrcode`, `created_at`, `updated_at`, `deleted_at`) VALUES
  ('1', '3', '1', '6', 'Admin Pusat SCI', 'CERT-SNBT-2024-001', 'kelulusan', 'Sertifikat Kelulusan Program Intensif SNBT 2024', 'Diberikan kepada Fajar Hidayat atas keberhasilan menyelesaikan Program Intensif Persiapan SNBT 2024.', '2026-06-19', '2027-06-25', NULL, NULL, '2026-06-25 04:07:12', '2026-06-25 04:07:12', NULL),
  ('2', '4', '1', '6', 'Admin Pusat SCI', 'CERT-SNBT-2024-002', 'kelulusan', 'Sertifikat Kelulusan Program Intensif SNBT 2024', 'Diberikan kepada Gita Permata atas keberhasilan menyelesaikan Program Intensif Persiapan SNBT 2024.', '2026-06-12', '2027-06-25', NULL, NULL, '2026-06-25 04:07:12', '2026-06-25 04:07:12', NULL),
  ('3', '8', '1', '6', 'Admin Pusat SCI', 'CERT-SNBT-2024-003', 'kelulusan', 'Sertifikat Kelulusan Program Intensif SNBT 2024', 'Diberikan kepada Kartini Wulandari atas keberhasilan menyelesaikan Program Intensif Persiapan SNBT 2024.', '2026-06-19', '2027-06-25', NULL, NULL, '2026-06-25 04:07:12', '2026-06-25 04:07:12', NULL);

-- ---- Tabel: `chat_messages` (0 baris) ----
-- (kosong)

-- ---- Tabel: `chat_rooms` (0 baris) ----
-- (kosong)

-- ---- Tabel: `class_students` (21 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `class_students` (`id`, `class_id`, `student_id`, `created_at`, `updated_at`) VALUES
  ('1', '1', '1', NULL, NULL),
  ('2', '2', '1', NULL, NULL),
  ('3', '1', '2', NULL, NULL),
  ('4', '3', '2', NULL, NULL),
  ('5', '4', '2', NULL, NULL),
  ('6', '5', '3', NULL, NULL),
  ('7', '5', '4', NULL, NULL),
  ('8', '1', '4', NULL, NULL),
  ('9', '1', '5', NULL, NULL),
  ('10', '4', '5', NULL, NULL),
  ('11', '4', '6', NULL, NULL),
  ('12', '1', '7', NULL, NULL),
  ('13', '5', '8', NULL, NULL),
  ('14', '6', '9', NULL, NULL),
  ('15', '7', '9', NULL, NULL),
  ('16', '6', '10', NULL, NULL),
  ('17', '7', '11', NULL, NULL),
  ('18', '7', '12', NULL, NULL),
  ('19', '8', '13', NULL, NULL),
  ('20', '8', '14', NULL, NULL),
  ('21', '8', '15', NULL, NULL);

-- ---- Tabel: `course_fees` (11 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `course_fees` (`id`, `course_id`, `amount`, `created_at`, `updated_at`) VALUES
  ('1', '1', '350000.00', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('2', '2', '350000.00', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('3', '3', '350000.00', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('4', '4', '350000.00', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('5', '5', '300000.00', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('6', '6', '500000.00', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('7', '7', '300000.00', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('8', '8', '275000.00', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('9', '9', '300000.00', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('10', '10', '325000.00', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('11', '11', '275000.00', '2026-06-25 04:07:08', '2026-06-25 04:07:08');

-- ---- Tabel: `course_package` (8 baris) ----
INSERT INTO `course_package` (`package_id`, `course_id`) VALUES
  ('1', '3'),
  ('1', '4'),
  ('1', '5'),
  ('2', '1'),
  ('2', '10'),
  ('3', '7'),
  ('3', '9'),
  ('3', '11');

-- ---- Tabel: `courses` (11 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `courses` (`id`, `created_at`, `updated_at`, `cabang_id`, `kode`, `nama`, `kategori`, `deskripsi`, `status`, `deleted_at`) VALUES
  ('1', '2026-06-25 04:07:07', '2026-06-25 04:07:07', '1', 'MAT-001', 'Matematika', 'Akademik', 'Mata pelajaran matematika dasar hingga lanjutan mencakup aljabar, geometri, dan statistika.', 'aktif', NULL),
  ('2', '2026-06-25 04:07:07', '2026-06-25 04:07:07', '1', 'FIS-001', 'Fisika', 'Akademik', 'Fisika dasar hingga lanjutan, mekanika, termodinamika, listrik magnet, dan gelombang.', 'aktif', NULL),
  ('3', '2026-06-25 04:07:08', '2026-06-25 04:07:08', '1', 'KIM-001', 'Kimia', 'Akademik', 'Kimia umum, reaksi kimia, stoikiometri, termokimia, dan kimia organik dasar.', 'aktif', NULL),
  ('4', '2026-06-25 04:07:08', '2026-06-25 04:07:08', '1', 'BIO-001', 'Biologi', 'Akademik', 'Biologi sel, genetika, ekologi, anatomi manusia, dan fisiologi tumbuhan.', 'aktif', NULL),
  ('5', '2026-06-25 04:07:08', '2026-06-25 04:07:08', '1', 'ING-001', 'Bahasa Inggris', 'Bahasa', 'Grammar, reading comprehension, writing, speaking, dan listening skills.', 'aktif', NULL),
  ('6', '2026-06-25 04:07:08', '2026-06-25 04:07:08', '1', 'SNBT-001', 'Persiapan SNBT', 'Ujian', 'Persiapan UTBK-SNBT meliputi TPS, Literasi Bahasa, dan Penalaran Matematika.', 'aktif', NULL),
  ('7', '2026-06-25 04:07:08', '2026-06-25 04:07:08', '2', 'MAT-BDG', 'Matematika', 'Akademik', 'Matematika dasar dan lanjutan untuk SD, SMP, SMA.', 'aktif', NULL),
  ('8', '2026-06-25 04:07:08', '2026-06-25 04:07:08', '2', 'ING-BDG', 'Bahasa Inggris', 'Bahasa', 'English course untuk semua jenjang.', 'aktif', NULL),
  ('9', '2026-06-25 04:07:08', '2026-06-25 04:07:08', '2', 'FIS-BDG', 'Fisika', 'Akademik', 'Fisika SMA dan persiapan ujian nasional.', 'aktif', NULL),
  ('10', '2026-06-25 04:07:08', '2026-06-25 04:07:08', '3', 'MAT-SBY', 'Matematika', 'Akademik', 'Matematika komprehensif untuk semua jenjang.', 'aktif', NULL),
  ('11', '2026-06-25 04:07:08', '2026-06-25 04:07:08', '3', 'ING-SBY', 'Bahasa Inggris', 'Bahasa', 'Kursus Bahasa Inggris komunikatif.', 'aktif', NULL);

-- ---- Tabel: `failed_jobs` (0 baris) ----
-- (kosong)

-- ---- Tabel: `gajis` (0 baris) ----
-- (kosong)

-- ---- Tabel: `grades` (27 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `grades` (`id`, `created_at`, `updated_at`, `siswa_id`, `mata_pelajaran_id`, `guru_id`, `semester_id`, `jenis_penilaian`, `nama_penilaian`, `nilai`, `nilai_maksimal`, `bobot`, `tanggal`, `catatan`) VALUES
  ('1', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '1', '1', NULL, 'ulangan_harian', 'Ulangan harian', '71.00', '100.00', '30.00', '2026-06-04', NULL),
  ('2', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '1', '1', NULL, 'mid_semester', 'Mid semester', '97.00', '100.00', '30.00', '2026-05-31', NULL),
  ('3', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '1', '1', NULL, 'akhir_semester', 'Akhir semester', '67.00', '100.00', '40.00', '2026-06-06', NULL),
  ('4', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '2', '1', NULL, 'ulangan_harian', 'Ulangan harian', '78.00', '100.00', '30.00', '2026-06-06', NULL),
  ('5', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '2', '1', NULL, 'mid_semester', 'Mid semester', '72.00', '100.00', '30.00', '2026-06-12', NULL),
  ('6', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '2', '1', NULL, 'akhir_semester', 'Akhir semester', '65.00', '100.00', '40.00', '2026-06-05', NULL),
  ('7', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', '1', '1', NULL, 'ulangan_harian', 'Ulangan harian', '76.00', '100.00', '30.00', '2026-06-07', NULL),
  ('8', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', '1', '1', NULL, 'mid_semester', 'Mid semester', '71.00', '100.00', '30.00', '2026-06-03', NULL),
  ('9', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', '1', '1', NULL, 'akhir_semester', 'Akhir semester', '86.00', '100.00', '40.00', '2026-06-18', NULL),
  ('10', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', '5', '3', NULL, 'ulangan_harian', 'Ulangan harian', '91.00', '100.00', '30.00', '2026-06-06', NULL),
  ('11', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', '5', '3', NULL, 'mid_semester', 'Mid semester', '90.00', '100.00', '30.00', '2026-06-09', NULL),
  ('12', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', '5', '3', NULL, 'akhir_semester', 'Akhir semester', '77.00', '100.00', '40.00', '2026-06-19', NULL),
  ('13', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '4', '1', '1', NULL, 'ulangan_harian', 'Ulangan harian', '69.00', '100.00', '30.00', '2026-06-12', NULL),
  ('14', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '4', '1', '1', NULL, 'mid_semester', 'Mid semester', '83.00', '100.00', '30.00', '2026-05-28', NULL),
  ('15', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '4', '1', '1', NULL, 'akhir_semester', 'Akhir semester', '65.00', '100.00', '40.00', '2026-06-16', NULL),
  ('16', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '5', '1', '1', NULL, 'ulangan_harian', 'Ulangan harian', '68.00', '100.00', '30.00', '2026-06-16', NULL),
  ('17', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '5', '1', '1', NULL, 'mid_semester', 'Mid semester', '92.00', '100.00', '30.00', '2026-05-30', NULL),
  ('18', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '5', '1', '1', NULL, 'akhir_semester', 'Akhir semester', '78.00', '100.00', '40.00', '2026-05-27', NULL),
  ('19', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '5', '5', '3', NULL, 'ulangan_harian', 'Ulangan harian', '86.00', '100.00', '30.00', '2026-06-11', NULL),
  ('20', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '5', '5', '3', NULL, 'mid_semester', 'Mid semester', '95.00', '100.00', '30.00', '2026-05-29', NULL),
  ('21', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '5', '5', '3', NULL, 'akhir_semester', 'Akhir semester', '77.00', '100.00', '40.00', '2026-06-17', NULL),
  ('22', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '6', '5', '3', NULL, 'ulangan_harian', 'Ulangan harian', '69.00', '100.00', '30.00', '2026-06-08', NULL),
  ('23', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '6', '5', '3', NULL, 'mid_semester', 'Mid semester', '98.00', '100.00', '30.00', '2026-06-09', NULL),
  ('24', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '6', '5', '3', NULL, 'akhir_semester', 'Akhir semester', '77.00', '100.00', '40.00', '2026-05-28', NULL),
  ('25', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '7', '1', '1', NULL, 'ulangan_harian', 'Ulangan harian', '83.00', '100.00', '30.00', '2026-06-03', NULL),
  ('26', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '7', '1', '1', NULL, 'mid_semester', 'Mid semester', '85.00', '100.00', '30.00', '2026-06-09', NULL),
  ('27', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '7', '1', '1', NULL, 'akhir_semester', 'Akhir semester', '83.00', '100.00', '40.00', '2026-06-18', NULL);

-- ---- Tabel: `guru_mapel` (0 baris) ----
-- (kosong)

-- ---- Tabel: `gurus` (0 baris) ----
-- (kosong)

-- ---- Tabel: `invoices` (21 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `invoices` (`id`, `created_at`, `updated_at`, `siswa_id`, `cabang_id`, `kelas_id`, `nomor_invoice`, `subtotal`, `diskon`, `pajak`, `total`, `deskripsi`, `periode`, `jatuh_tempo`, `status`, `catatan`, `deleted_at`) VALUES
  ('1', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '1', '1', '1', 'INV-202606-0001', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('2', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '1', '1', '2', 'INV-202606-0002', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Fisika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('3', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '2', '1', '1', 'INV-202606-0003', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('4', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '2', '1', '3', 'INV-202606-0004', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Kimia - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('5', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '2', '1', '4', 'INV-202606-0005', '300000.00', '0.00', '0.00', '300000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('6', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '3', '1', '5', 'INV-202606-0006', '500000.00', '0.00', '0.00', '500000.00', 'Biaya kursus Persiapan SNBT - May 2026', '2026-05', '2026-05-31', 'sebagian', NULL, NULL),
  ('7', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '4', '1', '5', 'INV-202606-0007', '500000.00', '0.00', '0.00', '500000.00', 'Biaya kursus Persiapan SNBT - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('8', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '4', '1', '1', 'INV-202606-0008', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('9', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '5', '1', '1', 'INV-202606-0009', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'sebagian', NULL, NULL),
  ('10', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '5', '1', '4', 'INV-202606-0010', '300000.00', '0.00', '0.00', '300000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('11', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '6', '1', '4', 'INV-202606-0011', '300000.00', '0.00', '0.00', '300000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'sebagian', NULL, NULL),
  ('12', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '7', '1', '1', 'INV-202606-0012', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'belum_bayar', NULL, NULL),
  ('13', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '8', '1', '5', 'INV-202606-0013', '500000.00', '0.00', '0.00', '500000.00', 'Biaya kursus Persiapan SNBT - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('14', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '9', '2', '6', 'INV-202606-0014', '300000.00', '0.00', '0.00', '300000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('15', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '9', '2', '7', 'INV-202606-0015', '275000.00', '0.00', '0.00', '275000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('16', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '10', '2', '6', 'INV-202606-0016', '300000.00', '0.00', '0.00', '300000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('17', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '11', '2', '7', 'INV-202606-0017', '275000.00', '0.00', '0.00', '275000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('18', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '12', '2', '7', 'INV-202606-0018', '275000.00', '0.00', '0.00', '275000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('19', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '13', '3', '8', 'INV-202606-0019', '325000.00', '0.00', '0.00', '325000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('20', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '14', '3', '8', 'INV-202606-0020', '325000.00', '0.00', '0.00', '325000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('21', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '15', '3', '8', 'INV-202606-0021', '325000.00', '0.00', '0.00', '325000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'sebagian', NULL, NULL);

-- ---- Tabel: `jadwals` (0 baris) ----
-- (kosong)

-- ---- Tabel: `kelas` (0 baris) ----
-- (kosong)

-- ---- Tabel: `kelas_siswa` (0 baris) ----
-- (kosong)

-- ---- Tabel: `landing_programs` (0 baris) ----
-- (kosong)

-- ---- Tabel: `landing_settings` (0 baris) ----
-- (kosong)

-- ---- Tabel: `landing_testimonials` (0 baris) ----
-- (kosong)

-- ---- Tabel: `landing_wa_numbers` (0 baris) ----
-- (kosong)

-- ---- Tabel: `mapel_paket` (0 baris) ----
-- (kosong)

-- ---- Tabel: `mapels` (0 baris) ----
-- (kosong)

-- ---- Tabel: `migrations` (96 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
  ('1', '2014_10_12_000000_create_users_table', '1'),
  ('2', '2014_10_12_100000_create_password_resets_table', '1'),
  ('3', '2019_08_19_000000_create_failed_jobs_table', '1'),
  ('4', '2019_12_14_000001_create_personal_access_tokens_table', '1'),
  ('5', '2026_05_29_071839_create_branches_table', '1'),
  ('6', '2026_05_29_071858_create_academic_years_table', '1'),
  ('7', '2026_05_29_071947_create_courses_table', '1'),
  ('8', '2026_05_29_071954_create_packages_table', '1'),
  ('9', '2026_05_29_072000_create_students_table', '1'),
  ('10', '2026_05_29_072005_create_teachers_table', '1'),
  ('11', '2026_05_29_072011_create_school_classes_table', '1'),
  ('12', '2026_05_29_072018_create_schedules_table', '1'),
  ('13', '2026_05_29_072035_create_modules_table', '1'),
  ('14', '2026_05_29_072043_create_invoices_table', '1'),
  ('15', '2026_05_29_072050_create_payments_table', '1'),
  ('16', '2026_05_29_072107_create_salaries_table', '1'),
  ('17', '2026_05_29_072126_create_grades_table', '1'),
  ('18', '2026_05_29_072133_create_tryouts_table', '1'),
  ('19', '2026_05_29_072138_create_questions_table', '1'),
  ('20', '2026_05_29_072143_create_tryout_attempts_table', '1'),
  ('21', '2026_05_29_072149_create_certificates_table', '1'),
  ('22', '2026_05_29_072159_create_chat_rooms_table', '1'),
  ('23', '2026_05_29_072205_create_chat_messages_table', '1'),
  ('24', '2026_05_29_072211_create_activity_logs_table', '1'),
  ('25', '2026_05_29_075106_create_tahun_ajarans_table', '1'),
  ('26', '2026_05_29_075124_create_mapels_table', '1'),
  ('27', '2026_05_29_075129_create_pakets_table', '1'),
  ('28', '2026_05_29_075134_create_mapel_paket_table', '1'),
  ('29', '2026_05_29_075140_create_siswas_table', '1'),
  ('30', '2026_05_29_075147_create_gurus_table', '1'),
  ('31', '2026_05_29_075152_create_guru_mapel_table', '1'),
  ('32', '2026_05_29_075157_create_kelas_table', '1'),
  ('33', '2026_05_29_075222_create_kelas_siswa_table', '1'),
  ('34', '2026_05_29_075226_create_jadwals_table', '1'),
  ('35', '2026_05_29_075231_create_absensi_siswas_table', '1'),
  ('36', '2026_05_29_075236_create_absensi_gurus_table', '1'),
  ('37', '2026_05_29_075247_create_moduls_table', '1'),
  ('38', '2026_05_29_075252_create_tagihans_table', '1'),
  ('39', '2026_05_29_075256_create_pembayarans_table', '1'),
  ('40', '2026_05_29_075301_create_gajis_table', '1'),
  ('41', '2026_05_29_075313_create_nilais_table', '1'),
  ('42', '2026_05_29_083356_create_permission_tables', '1'),
  ('43', '2026_05_29_083817_add_columns_to_users_table', '1'),
  ('44', '2026_05_29_084319_create_activity_log_table', '1'),
  ('45', '2026_05_29_084320_add_event_column_to_activity_log_table', '1'),
  ('46', '2026_05_29_084321_add_batch_uuid_column_to_activity_log_table', '1'),
  ('47', '2026_05_29_093916_add_deleted_at_to_students_table', '1'),
  ('48', '2026_05_29_100111_add_branch_id_to_students_table', '1'),
  ('49', '2026_06_02_000001_add_columns_to_students_table', '1'),
  ('50', '2026_06_02_092952_add_account_to_branches_table', '1'),
  ('51', '2026_06_02_093248_add_branch_accounts_table', '1'),
  ('52', '2026_06_05_000001_add_username_to_users_table', '1'),
  ('53', '2026_06_05_000002_add_branch_extra_columns', '1'),
  ('54', '2026_06_05_100001_add_columns_to_schedules_table', '1'),
  ('55', '2026_06_05_100002_add_columns_to_core_tables', '1'),
  ('56', '2026_06_05_100003_add_user_id_to_teachers', '1'),
  ('57', '2026_06_06_000000_create_categories_table', '1'),
  ('58', '2026_06_06_010000_modify_courses_icon_length', '1'),
  ('59', '2026_06_06_150000_add_deleted_at_to_certificates_table', '1'),
  ('60', '2026_06_06_160000_update_certificates_add_columns', '1'),
  ('61', '2026_06_07_100000_create_announcements_table', '1'),
  ('62', '2026_06_09_000001_add_cv_path_to_teachers_table', '1'),
  ('63', '2026_06_09_132034_add_columns_to_chat_tables', '1'),
  ('64', '2026_06_09_200000_add_missing_columns_to_all_tables', '1'),
  ('65', '2026_06_10_000001_refactor_courses_teachers_modules', '1'),
  ('66', '2026_06_10_000002_add_student_accounts_private_types_announcement_targets', '1'),
  ('67', '2026_06_10_000003_student_teachers_class_sessions', '1'),
  ('68', '2026_06_10_100000_add_kunci_jawaban_to_questions', '1'),
  ('69', '2026_06_10_100000_create_class_students_table', '1'),
  ('70', '2026_06_10_110000_create_course_fees_table', '1'),
  ('71', '2026_06_10_110100_create_student_course_payments_table', '1'),
  ('72', '2026_06_10_161434_create_landing_content_tables', '1'),
  ('73', '2026_06_10_162503_create_landing_wa_numbers_table', '1'),
  ('74', '2026_06_10_163351_create_system_settings_table', '1'),
  ('75', '2026_06_11_000000_add_bukti_pembayaran_to_salaries', '1'),
  ('76', '2026_06_11_100000_create_schedule_agreements_and_payment_fields', '1'),
  ('77', '2026_06_11_120000_add_allowed_pages_to_branches_table', '1'),
  ('78', '2026_06_11_120000_add_course_id_to_certificates_table', '1'),
  ('79', '2026_06_11_120000_add_tipe_gaji_to_salaries', '1'),
  ('80', '2026_06_11_182851_add_pertemuan_ke_to_schedule_proposals', '1'),
  ('81', '2026_06_12_000001_create_schedule_proposals_tables', '1'),
  ('82', '2026_06_12_100000_revamp_attendance_dual_confirmation', '1'),
  ('83', '2026_06_20_000001_create_student_registrations_table', '1'),
  ('84', '2026_06_20_000507_add_kategori_to_courses_table', '1'),
  ('85', '2026_06_20_001742_create_course_package_table', '1'),
  ('86', '2026_06_20_003643_add_kode_modul_to_modules_table', '1'),
  ('87', '2026_06_20_100000_add_jenis_guru_to_teachers_table', '1'),
  ('88', '2026_06_20_152029_add_guru_id_to_packages_and_paket_id_to_schedules', '1'),
  ('89', '2026_06_21_000001_add_kategori_peserta_didik_to_students_table', '1'),
  ('90', '2026_06_21_000002_add_attendance_and_class_type_to_packages_table', '1'),
  ('91', '2026_06_21_000003_add_package_id_to_students_table', '1'),
  ('92', '2026_06_22_000001_add_billing_mode_to_school_classes', '1'),
  ('93', '2026_06_22_000001_add_education_level_to_student_registrations', '1'),
  ('94', '2026_06_22_122921_add_columns_to_payments_table', '1'),
  ('95', '2026_06_22_200000_change_diterbitkan_oleh_to_string_in_certificates', '1'),
  ('96', '2026_06_22_210000_add_kelas_id_to_invoices_module_id_to_schedules', '1');

-- ---- Tabel: `model_has_permissions` (0 baris) ----
-- (kosong)

-- ---- Tabel: `model_has_roles` (27 baris) ----
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
  ('1', 'App\\Models\\User', '1'),
  ('2', 'App\\Models\\User', '2'),
  ('2', 'App\\Models\\User', '3'),
  ('2', 'App\\Models\\User', '4'),
  ('2', 'App\\Models\\User', '5'),
  ('3', 'App\\Models\\User', '6'),
  ('3', 'App\\Models\\User', '7'),
  ('3', 'App\\Models\\User', '8'),
  ('3', 'App\\Models\\User', '9'),
  ('3', 'App\\Models\\User', '10'),
  ('3', 'App\\Models\\User', '11'),
  ('3', 'App\\Models\\User', '12'),
  ('4', 'App\\Models\\User', '13'),
  ('4', 'App\\Models\\User', '14'),
  ('4', 'App\\Models\\User', '15'),
  ('4', 'App\\Models\\User', '16'),
  ('4', 'App\\Models\\User', '17'),
  ('4', 'App\\Models\\User', '18'),
  ('4', 'App\\Models\\User', '19'),
  ('4', 'App\\Models\\User', '20'),
  ('4', 'App\\Models\\User', '21'),
  ('4', 'App\\Models\\User', '22'),
  ('4', 'App\\Models\\User', '23'),
  ('4', 'App\\Models\\User', '24'),
  ('4', 'App\\Models\\User', '25'),
  ('4', 'App\\Models\\User', '26'),
  ('4', 'App\\Models\\User', '27');

-- ---- Tabel: `modules` (13 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `modules` (`id`, `kode_modul`, `created_at`, `updated_at`, `mata_pelajaran_id`, `diupload_oleh`, `judul`, `deskripsi`, `jenis`, `file_path`, `file_url`, `ukuran_file`, `is_gratis`, `status`, `jumlah_download`, `deleted_at`) VALUES
  ('1', 'MAT-001-M1', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '1', 'Modul 1 - Persamaan Linear', 'Pengantar persamaan linear satu dan dua variabel', 'pdf', NULL, NULL, NULL, '1', 'aktif', '60', NULL),
  ('2', 'MAT-001-M2', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '1', 'Modul 2 - Fungsi Kuadrat', 'Fungsi kuadrat dan grafiknya', 'pdf', NULL, NULL, NULL, '0', 'aktif', '113', NULL),
  ('3', 'MAT-001-M3', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '1', 'Modul 3 - Trigonometri', 'Sudut, sinus, cosinus, tangen dan aplikasinya', 'video', NULL, NULL, NULL, '0', 'aktif', '31', NULL),
  ('4', 'MAT-001-M4', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '1', 'Modul 4 - Statistika', 'Ukuran pemusatan dan penyebaran data', 'pdf', NULL, NULL, NULL, '0', 'aktif', '105', NULL),
  ('5', 'FIS-001-M1', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', '1', 'Modul 1 - Kinematika', 'Gerak lurus beraturan dan berubah beraturan', 'pdf', NULL, NULL, NULL, '1', 'aktif', '145', NULL),
  ('6', 'FIS-001-M2', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', '1', 'Modul 2 - Dinamika', 'Hukum Newton dan aplikasinya', 'pdf', NULL, NULL, NULL, '0', 'aktif', '36', NULL),
  ('7', 'FIS-001-M3', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', '1', 'Modul 3 - Listrik', 'Listrik statis dan dinamis', 'video', NULL, NULL, NULL, '0', 'aktif', '118', NULL),
  ('8', 'ING-001-M1', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '5', '1', 'Module 1 - Grammar Fundamentals', 'Tenses, articles, prepositions', 'pdf', NULL, NULL, NULL, '1', 'aktif', '93', NULL),
  ('9', 'ING-001-M2', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '5', '1', 'Module 2 - Reading Strategies', 'Teknik membaca cepat dan pemahaman', 'pdf', NULL, NULL, NULL, '0', 'aktif', '33', NULL),
  ('10', 'ING-001-M3', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '5', '1', 'Module 3 - Writing Skills', 'Essay, letter, and report writing', 'pdf', NULL, NULL, NULL, '0', 'aktif', '84', NULL),
  ('11', 'SNBT-001-M1', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '6', '1', 'Paket Soal TPS Penalaran Umum', 'Kumpulan soal TPS penalaran umum dengan pembahasan', 'pdf', NULL, NULL, NULL, '0', 'aktif', '29', NULL),
  ('12', 'SNBT-001-M2', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '6', '1', 'Strategi Mengerjakan Soal SNBT', 'Tips dan trik mengerjakan soal UTBK-SNBT', 'video', NULL, NULL, NULL, '1', 'aktif', '135', NULL),
  ('13', 'SNBT-001-M3', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '6', '1', 'Paket Soal Literasi Bahasa', 'Latihan literasi bahasa Indonesia dan Inggris', 'pdf', NULL, NULL, NULL, '0', 'aktif', '99', NULL);

-- ---- Tabel: `moduls` (0 baris) ----
-- (kosong)

-- ---- Tabel: `nilais` (0 baris) ----
-- (kosong)

-- ---- Tabel: `packages` (5 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `packages` (`id`, `created_at`, `updated_at`, `cabang_id`, `guru_id`, `nama`, `deskripsi`, `harga`, `durasi_bulan`, `jumlah_pertemuan`, `jenis`, `metode_absensi`, `tipe_kelas`, `fitur`, `is_unggulan`, `status`, `deleted_at`) VALUES
  ('1', '2026-06-25 04:07:08', '2026-06-25 04:08:58', '1', NULL, 'Paket Reguler SMA', 'Paket bimbingan belajar reguler untuk siswa SMA, 2x pertemuan per minggu.', '750000.00', '1', '8', 'reguler', 'manual', 'offline', '[\"8 pertemuan\\/bulan\",\"Modul digital\",\"Evaluasi bulanan\",\"Konsultasi gratis\"]', '0', 'aktif', NULL),
  ('2', '2026-06-25 04:07:08', '2026-06-25 04:09:10', '1', NULL, 'Paket Intensif SNBT', 'Program intensif persiapan SNBT selama 3 bulan dengan tryout rutin.', '2500000.00', '3', '36', 'intensif', 'manual', 'offline', '[\"36 pertemuan\",\"Tryout mingguan\",\"Analisis hasil\",\"Mentor pribadi\",\"Modul eksklusif\"]', '0', 'aktif', NULL),
  ('3', '2026-06-25 04:07:08', '2026-06-25 04:09:32', '1', NULL, 'Paket Online Basic', 'Belajar online fleksibel, cocok untuk siswa yang sibuk atau jauh dari cabang.', '500000.00', '1', '8', 'online', 'manual', 'online', '[\"8 sesi online\",\"Rekaman kelas\",\"Modul digital\",\"Forum diskusi\"]', '0', 'aktif', NULL),
  ('4', '2026-06-25 04:07:08', '2026-06-25 04:09:41', '2', NULL, 'Paket Reguler Bandung', 'Paket bimbel reguler untuk siswa di cabang Bandung.', '700000.00', '1', '8', 'reguler', 'dual', 'offline', '[\"8 pertemuan\\/bulan\",\"Modul belajar\",\"Evaluasi bulanan\"]', '0', 'aktif', '2026-06-25 04:09:41'),
  ('5', '2026-06-25 04:07:08', '2026-06-25 04:09:37', '3', NULL, 'Paket Reguler Surabaya', 'Paket bimbel reguler untuk siswa di cabang Surabaya.', '725000.00', '1', '8', 'reguler', 'dual', 'offline', '[\"8 pertemuan\\/bulan\",\"Modul belajar\",\"Evaluasi bulanan\"]', '0', 'aktif', '2026-06-25 04:09:37');

-- ---- Tabel: `pakets` (0 baris) ----
-- (kosong)

-- ---- Tabel: `password_resets` (0 baris) ----
-- (kosong)

-- ---- Tabel: `payments` (20 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `payments` (`id`, `created_at`, `updated_at`, `invoice_id`, `siswa_id`, `cabang_id`, `nomor_pembayaran`, `jumlah`, `metode`, `nama_bank`, `nomor_rekening`, `bukti_pembayaran`, `tanggal_pembayaran`, `status`, `alasan_penolakan`, `catatan`, `disetujui_oleh`, `tanggal_disetujui`, `deleted_at`) VALUES
  ('1', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '1', '1', '1', 'PAY-INV-202606-0001', '350000.00', 'transfer', NULL, NULL, NULL, '2026-05-29', 'verified', NULL, NULL, '3', '2026-05-28 00:00:00', NULL),
  ('2', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '2', '1', '1', 'PAY-INV-202606-0002', '350000.00', 'transfer', NULL, NULL, NULL, '2026-06-01', 'verified', NULL, NULL, '3', '2026-05-29 00:00:00', NULL),
  ('3', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '3', '2', '1', 'PAY-INV-202606-0003', '350000.00', 'cash', NULL, NULL, NULL, '2026-06-01', 'verified', NULL, NULL, '3', '2026-05-30 00:00:00', NULL),
  ('4', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '4', '2', '1', 'PAY-INV-202606-0004', '350000.00', 'transfer', NULL, NULL, NULL, '2026-06-01', 'verified', NULL, NULL, '3', '2026-05-28 00:00:00', NULL),
  ('5', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '5', '2', '1', 'PAY-INV-202606-0005', '300000.00', 'transfer', NULL, NULL, NULL, '2026-06-03', 'verified', NULL, NULL, '3', '2026-06-05 00:00:00', NULL),
  ('6', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '6', '3', '1', 'PAY-INV-202606-0006', '250000.00', 'qris', NULL, NULL, NULL, '2026-05-29', 'verified', NULL, NULL, '3', '2026-05-28 00:00:00', NULL),
  ('7', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '7', '4', '1', 'PAY-INV-202606-0007', '500000.00', 'qris', NULL, NULL, NULL, '2026-05-31', 'verified', NULL, NULL, '3', '2026-05-30 00:00:00', NULL),
  ('8', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '8', '4', '1', 'PAY-INV-202606-0008', '350000.00', 'transfer', NULL, NULL, NULL, '2026-06-01', 'verified', NULL, NULL, '3', '2026-05-29 00:00:00', NULL),
  ('9', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '9', '5', '1', 'PAY-INV-202606-0009', '175000.00', 'qris', NULL, NULL, NULL, '2026-06-02', 'verified', NULL, NULL, '3', '2026-05-28 00:00:00', NULL),
  ('10', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '10', '5', '1', 'PAY-INV-202606-0010', '300000.00', 'transfer', NULL, NULL, NULL, '2026-06-04', 'verified', NULL, NULL, '3', '2026-06-05 00:00:00', NULL),
  ('11', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '11', '6', '1', 'PAY-INV-202606-0011', '150000.00', 'cash', NULL, NULL, NULL, '2026-06-01', 'verified', NULL, NULL, '3', '2026-05-30 00:00:00', NULL),
  ('12', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '13', '8', '1', 'PAY-INV-202606-0013', '500000.00', 'qris', NULL, NULL, NULL, '2026-05-31', 'verified', NULL, NULL, '3', '2026-06-02 00:00:00', NULL),
  ('13', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '14', '9', '2', 'PAY-INV-202606-0014', '300000.00', 'transfer', NULL, NULL, NULL, '2026-05-30', 'verified', NULL, NULL, '3', '2026-05-31 00:00:00', NULL),
  ('14', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '15', '9', '2', 'PAY-INV-202606-0015', '275000.00', 'qris', NULL, NULL, NULL, '2026-05-26', 'verified', NULL, NULL, '3', '2026-05-29 00:00:00', NULL),
  ('15', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '16', '10', '2', 'PAY-INV-202606-0016', '300000.00', 'transfer', NULL, NULL, NULL, '2026-06-02', 'verified', NULL, NULL, '3', '2026-06-04 00:00:00', NULL),
  ('16', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '17', '11', '2', 'PAY-INV-202606-0017', '275000.00', 'qris', NULL, NULL, NULL, '2026-06-02', 'verified', NULL, NULL, '3', '2026-06-03 00:00:00', NULL),
  ('17', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '18', '12', '2', 'PAY-INV-202606-0018', '275000.00', 'cash', NULL, NULL, NULL, '2026-06-01', 'verified', NULL, NULL, '3', '2026-05-28 00:00:00', NULL),
  ('18', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '19', '13', '3', 'PAY-INV-202606-0019', '325000.00', 'transfer', NULL, NULL, NULL, '2026-05-27', 'verified', NULL, NULL, '3', '2026-05-29 00:00:00', NULL),
  ('19', '2026-06-25 04:07:11', '2026-06-25 04:07:11', '20', '14', '3', 'PAY-INV-202606-0020', '325000.00', 'cash', NULL, NULL, NULL, '2026-05-28', 'verified', NULL, NULL, '3', '2026-05-29 00:00:00', NULL),
  ('20', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '21', '15', '3', 'PAY-INV-202606-0021', '162500.00', 'qris', NULL, NULL, NULL, '2026-06-03', 'verified', NULL, NULL, '3', '2026-05-28 00:00:00', NULL);

-- ---- Tabel: `pembayarans` (0 baris) ----
-- (kosong)

-- ---- Tabel: `permissions` (38 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
  ('1', 'branch.view', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('2', 'branch.create', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('3', 'branch.edit', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('4', 'branch.delete', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('5', 'student.view', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('6', 'student.create', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('7', 'student.edit', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('8', 'student.delete', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('9', 'teacher.view', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('10', 'teacher.create', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('11', 'teacher.edit', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('12', 'teacher.delete', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('13', 'employee.view', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('14', 'employee.create', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('15', 'employee.edit', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('16', 'employee.delete', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('17', 'schedule.view', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('18', 'schedule.create', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('19', 'schedule.edit', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('20', 'schedule.delete', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('21', 'payment.view', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('22', 'payment.create', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('23', 'payment.edit', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('24', 'payment.approve', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('25', 'tryout.view', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('26', 'tryout.create', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('27', 'tryout.edit', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('28', 'tryout.delete', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('29', 'report.view', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('30', 'report.export', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('31', 'setting.view', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('32', 'setting.edit', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('33', 'salary.view', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('34', 'salary.create', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('35', 'salary.edit', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('36', 'certificate.view', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('37', 'certificate.create', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('38', 'certificate.download', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05');

-- ---- Tabel: `personal_access_tokens` (0 baris) ----
-- (kosong)

-- ---- Tabel: `questions` (10 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `questions` (`id`, `created_at`, `updated_at`, `tryout_id`, `teks_pertanyaan`, `gambar_pertanyaan`, `jenis`, `pilihan_jawaban`, `kunci_jawaban`, `penjelasan`, `poin`, `urutan`, `tingkat_kesulitan`) VALUES
  ('1', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', 'Jika f(x) = 2x² - 3x + 1, maka nilai f(2) adalah...', NULL, 'pilihan_ganda', '{\"A\":\"3\",\"B\":\"5\",\"C\":\"7\",\"D\":\"1\",\"E\":\"4\"}', 'A', NULL, '10.00', '1', 'sedang'),
  ('2', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', 'Nilai dari sin 30° + cos 60° adalah...', NULL, 'pilihan_ganda', '{\"A\":\"0\",\"B\":\"1\",\"C\":\"\\u221a2\",\"D\":\"2\",\"E\":\"\\u00bd\"}', 'B', NULL, '10.00', '2', 'mudah'),
  ('3', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', 'Sebuah persegi panjang memiliki panjang 12 cm dan lebar 8 cm. Luas persegi panjang tersebut adalah...', NULL, 'pilihan_ganda', '{\"A\":\"40 cm\\u00b2\",\"B\":\"96 cm\\u00b2\",\"C\":\"80 cm\\u00b2\",\"D\":\"48 cm\\u00b2\",\"E\":\"120 cm\\u00b2\"}', 'B', NULL, '10.00', '3', 'mudah'),
  ('4', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', 'Jika log 2 = 0,301, maka log 8 adalah...', NULL, 'pilihan_ganda', '{\"A\":\"0,602\",\"B\":\"0,903\",\"C\":\"0,800\",\"D\":\"1,204\",\"E\":\"2,401\"}', 'B', NULL, '15.00', '4', 'sedang'),
  ('5', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', 'Himpunan penyelesaian dari |2x - 3| < 5 adalah...', NULL, 'pilihan_ganda', '{\"A\":\"-1 < x < 4\",\"B\":\"x < -1 atau x > 4\",\"C\":\"-4 < x < 1\",\"D\":\"0 < x < 5\",\"E\":\"-2 < x < 4\"}', 'A', NULL, '15.00', '5', 'sulit'),
  ('6', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', 'Diskriminan dari persamaan x² - 5x + 6 = 0 adalah...', NULL, 'pilihan_ganda', '{\"A\":\"1\",\"B\":\"-1\",\"C\":\"4\",\"D\":\"25\",\"E\":\"0\"}', 'A', NULL, '20.00', '1', 'mudah'),
  ('7', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', 'Akar-akar persamaan 2x² - 7x + 3 = 0 adalah...', NULL, 'pilihan_ganda', '{\"A\":\"3 dan \\u00bd\",\"B\":\"-3 dan \\u00bd\",\"C\":\"3 dan -\\u00bd\",\"D\":\"1 dan 3\",\"E\":\"-1 dan -3\"}', 'A', NULL, '20.00', '2', 'sedang'),
  ('8', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', 'Nilai maksimum dari f(x) = -x² + 4x + 5 adalah...', NULL, 'pilihan_ganda', '{\"A\":\"5\",\"B\":\"7\",\"C\":\"9\",\"D\":\"11\",\"E\":\"4\"}', 'C', NULL, '20.00', '3', 'sedang'),
  ('9', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', 'tan 45° × cot 45° = ...', NULL, 'pilihan_ganda', '{\"A\":\"0\",\"B\":\"\\u221a2\",\"C\":\"2\",\"D\":\"1\",\"E\":\"\\u00bd\"}', 'D', NULL, '20.00', '4', 'mudah'),
  ('10', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', 'Jika sin α = 3/5 dan α di kuadran I, maka cos α = ...', NULL, 'pilihan_ganda', '{\"A\":\"4\\/5\",\"B\":\"3\\/4\",\"C\":\"5\\/4\",\"D\":\"5\\/3\",\"E\":\"4\\/3\"}', 'A', NULL, '20.00', '5', 'sedang');

-- ---- Tabel: `role_has_permissions` (75 baris) ----
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
  ('1', '1'),
  ('2', '1'),
  ('3', '1'),
  ('4', '1'),
  ('5', '1'),
  ('5', '2'),
  ('5', '3'),
  ('6', '1'),
  ('6', '2'),
  ('7', '1'),
  ('7', '2'),
  ('8', '1'),
  ('8', '2'),
  ('9', '1'),
  ('9', '2'),
  ('10', '1'),
  ('10', '2'),
  ('11', '1'),
  ('11', '2'),
  ('12', '1'),
  ('13', '1'),
  ('13', '2'),
  ('14', '1'),
  ('14', '2'),
  ('15', '1'),
  ('15', '2'),
  ('16', '1'),
  ('17', '1'),
  ('17', '2'),
  ('17', '3'),
  ('17', '4'),
  ('17', '5'),
  ('18', '1'),
  ('18', '2'),
  ('19', '1'),
  ('19', '2'),
  ('20', '1'),
  ('20', '2'),
  ('21', '1'),
  ('21', '2'),
  ('21', '4'),
  ('22', '1'),
  ('22', '2'),
  ('23', '1'),
  ('24', '1'),
  ('24', '2'),
  ('25', '1'),
  ('25', '2'),
  ('25', '4'),
  ('26', '1'),
  ('26', '2'),
  ('27', '1'),
  ('27', '2'),
  ('28', '1'),
  ('29', '1'),
  ('29', '2'),
  ('30', '1'),
  ('30', '2'),
  ('31', '1'),
  ('32', '1'),
  ('33', '1'),
  ('33', '2'),
  ('33', '3'),
  ('33', '5'),
  ('34', '1'),
  ('34', '2'),
  ('35', '1'),
  ('36', '1'),
  ('36', '2'),
  ('37', '1'),
  ('37', '2'),
  ('38', '1'),
  ('38', '2'),
  ('38', '3'),
  ('38', '4');

-- ---- Tabel: `roles` (5 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
  ('1', 'owner', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('2', 'admin', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('3', 'guru', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('4', 'siswa', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05'),
  ('5', 'karyawan', 'web', '2026-06-25 04:07:05', '2026-06-25 04:07:05');

-- ---- Tabel: `salaries` (7 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `salaries` (`id`, `created_at`, `updated_at`, `guru_id`, `cabang_id`, `periode`, `tipe_gaji`, `gaji_pokok`, `jam_mengajar`, `tarif_per_jam`, `total_gaji_mengajar`, `bonus`, `potongan`, `total_gaji`, `metode_pembayaran`, `nama_bank`, `nomor_rekening`, `tanggal_pembayaran`, `status`, `catatan`, `bukti_pembayaran`, `dibayar_oleh`, `deleted_at`) VALUES
  ('1', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '1', '2026-05', 'bulanan', '4500000.00', '32.0', '100000.00', '3200000.00', '0.00', '0.00', '7700000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('2', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '2', '1', '2026-05', 'bulanan', '5000000.00', '24.0', '110000.00', '2640000.00', '0.00', '0.00', '7640000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('3', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '3', '1', '2026-05', 'bulanan', '4000000.00', '24.0', '90000.00', '2160000.00', '0.00', '0.00', '6160000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('4', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '4', '1', '2026-05', 'bulanan', '4500000.00', '40.0', '100000.00', '4000000.00', '0.00', '0.00', '8500000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('5', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '5', '2', '2026-05', 'bulanan', '4000000.00', '24.0', '90000.00', '2160000.00', '0.00', '0.00', '6160000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('6', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '6', '2', '2026-05', 'bulanan', '3800000.00', '16.0', '85000.00', '1360000.00', '0.00', '0.00', '5160000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('7', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '7', '3', '2026-05', 'bulanan', '4800000.00', '24.0', '95000.00', '2280000.00', '0.00', '0.00', '7080000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL);

-- ---- Tabel: `schedule_proposal_approvals` (0 baris) ----
-- (kosong)

-- ---- Tabel: `schedule_proposals` (0 baris) ----
-- (kosong)

-- ---- Tabel: `schedule_student_agreements` (0 baris) ----
-- (kosong)

-- ---- Tabel: `schedules` (0 baris) ----
-- (kosong)

-- ---- Tabel: `school_classes` (8 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `school_classes` (`id`, `created_at`, `updated_at`, `cabang_id`, `mata_pelajaran_id`, `guru_id`, `tahun_akademik_id`, `nama`, `nama_kelas`, `kapasitas`, `jumlah_pertemuan`, `jenis`, `link_zoom`, `status`, `billing_mode`, `deleted_at`) VALUES
  ('1', '2026-06-25 04:07:09', '2026-06-25 04:07:09', '1', '1', '1', '1', NULL, 'Matematika SMA Reguler A', '15', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('2', '2026-06-25 04:07:09', '2026-06-25 04:07:09', '1', '2', '1', '1', NULL, 'Fisika SMA Reguler A', '12', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('3', '2026-06-25 04:07:09', '2026-06-25 04:07:09', '1', '3', '2', '1', NULL, 'Kimia SMA Reguler A', '12', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('4', '2026-06-25 04:07:09', '2026-06-25 04:07:09', '1', '5', '3', '1', NULL, 'Bahasa Inggris SMA Reguler', '15', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('5', '2026-06-25 04:07:09', '2026-06-25 04:07:09', '1', '6', '4', '1', NULL, 'Intensif SNBT Batch 1', '20', '36', 'offline', NULL, 'aktif', 'per_paket', NULL),
  ('6', '2026-06-25 04:07:09', '2026-06-25 04:07:09', '2', '7', '5', '1', NULL, 'Matematika Reguler Bandung A', '12', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('7', '2026-06-25 04:07:09', '2026-06-25 04:07:09', '2', '8', '6', '1', NULL, 'Bahasa Inggris Bandung A', '10', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('8', '2026-06-25 04:07:09', '2026-06-25 04:07:09', '3', '10', '7', '1', NULL, 'Matematika Reguler Surabaya A', '12', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL);

-- ---- Tabel: `semesters` (0 baris) ----
-- (kosong)

-- ---- Tabel: `siswas` (0 baris) ----
-- (kosong)

-- ---- Tabel: `student_course_payments` (0 baris) ----
-- (kosong)

-- ---- Tabel: `student_registrations` (0 baris) ----
-- (kosong)

-- ---- Tabel: `student_teachers` (0 baris) ----
-- (kosong)

-- ---- Tabel: `students` (15 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `students` (`id`, `created_at`, `updated_at`, `deleted_at`, `branch_id`, `package_id`, `user_id`, `nis`, `name`, `gender`, `birth_date`, `birth_place`, `address`, `phone`, `parent_name`, `parent_phone`, `photo`, `status`, `join_date`, `school_name`, `grade`, `kategori_peserta_didik`) VALUES
  ('1', '2026-06-25 04:07:09', '2026-06-25 04:07:09', NULL, '1', '1', '13', 'SIS-2024-001', 'Andi Nugroho', 'L', '2007-04-12', 'Jakarta', 'Jl. Merdeka No. 5, Jakarta Selatan', '087812340001', 'Bapak Nugroho', '081312340001', NULL, 'aktif', '2024-01-10', 'SMAN 70 Jakarta', 'XI', 'SMA'),
  ('2', '2026-06-25 04:07:09', '2026-06-25 04:07:09', NULL, '1', '1', '14', 'SIS-2024-002', 'Citra Lestari', 'P', '2007-08-23', 'Bogor', 'Jl. Kemang Raya No. 12, Jakarta Selatan', '087812340002', 'Ibu Lestari', '081312340002', NULL, 'aktif', '2024-01-15', 'SMAN 34 Jakarta', 'XI', 'SMA'),
  ('3', '2026-06-25 04:07:09', '2026-06-25 04:07:09', NULL, '1', '2', '15', 'SIS-2024-003', 'Fajar Hidayat', 'L', '2006-01-30', 'Depok', 'Jl. Taman Makam Pahlawan No. 3, Depok', '087812340003', 'Bapak Hidayat', '081312340003', NULL, 'aktif', '2024-02-01', 'SMAN 5 Depok', 'XII', 'SMA'),
  ('4', '2026-06-25 04:07:09', '2026-06-25 04:07:09', NULL, '1', '2', '16', 'SIS-2024-004', 'Gita Permata', 'P', '2006-05-17', 'Tangerang', 'Jl. BSD No. 22, Tangerang Selatan', '087812340004', 'Ibu Permata', '081312340004', NULL, 'aktif', '2024-02-05', 'SMAN 1 Serpong', 'XII', 'SMA'),
  ('5', '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL, '1', '1', '17', 'SIS-2024-005', 'Hendra Putra', 'L', '2008-10-08', 'Jakarta', 'Jl. Cipete No. 7, Jakarta Selatan', '087812340005', 'Bapak Putra', '081312340005', NULL, 'aktif', '2024-03-01', 'SMPN 49 Jakarta', 'IX', 'SMP'),
  ('6', '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL, '1', '3', '18', 'SIS-2024-006', 'Indah Sari', 'P', '2007-12-25', 'Bekasi', 'Jl. Galaxy No. 45, Bekasi', '087812340006', 'Bapak Sari', '081312340006', NULL, 'aktif', '2024-03-10', 'SMAN 1 Bekasi', 'XI', 'SMA'),
  ('7', '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL, '1', '1', '19', 'SIS-2024-007', 'Joko Santoso', 'L', '2009-02-14', 'Jakarta', 'Jl. Pesanggrahan No. 8, Jakarta Barat', '087812340007', 'Ibu Santoso', '081312340007', NULL, 'aktif', '2024-04-01', 'SMPN 115 Jakarta', 'VIII', 'SMP'),
  ('8', '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL, '1', '2', '20', 'SIS-2024-008', 'Kartini Wulandari', 'P', '2006-07-21', 'Jakarta', 'Jl. Lebak Bulus No. 3, Jakarta Selatan', '087812340008', 'Bapak Wulandari', '081312340008', NULL, 'aktif', '2024-04-15', 'SMAN 86 Jakarta', 'XII', 'SMA'),
  ('9', '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL, '2', '4', '21', 'SIS-2024-009', 'Luthfi Rahman', 'L', '2007-03-19', 'Bandung', 'Jl. Setiabudi No. 20, Bandung', '087812340009', 'Bapak Rahman', '081312340009', NULL, 'aktif', '2024-01-20', 'SMAN 3 Bandung', 'XI', 'SMA'),
  ('10', '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL, '2', '4', '22', 'SIS-2024-010', 'Mira Kusuma', 'P', '2007-11-02', 'Cimahi', 'Jl. Cimahi No. 15, Cimahi', '087812340010', 'Ibu Kusuma', '081312340010', NULL, 'aktif', '2024-02-10', 'SMAN 1 Cimahi', 'XI', 'SMA'),
  ('11', '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL, '2', '4', '23', 'SIS-2024-011', 'Naufal Ardiansyah', 'L', '2008-06-15', 'Bandung', 'Jl. Pasteur No. 30, Bandung', '087812340011', 'Bapak Ardiansyah', '081312340011', NULL, 'aktif', '2024-03-01', 'SMPN 1 Bandung', 'IX', 'SMP'),
  ('12', '2026-06-25 04:07:11', '2026-06-25 04:07:11', NULL, '2', '4', '24', 'SIS-2024-012', 'Olivia Putri', 'P', '2009-09-09', 'Bandung', 'Jl. Antapani No. 5, Bandung', '087812340012', 'Ibu Putri', '081312340012', NULL, 'aktif', '2024-04-05', 'SMPN 14 Bandung', 'VIII', 'SMP'),
  ('13', '2026-06-25 04:07:11', '2026-06-25 04:07:11', NULL, '3', '5', '25', 'SIS-2024-013', 'Prasetyo Adi', 'L', '2007-01-25', 'Surabaya', 'Jl. Darmo No. 50, Surabaya', '087812340013', 'Bapak Adi', '081312340013', NULL, 'aktif', '2024-01-25', 'SMAN 5 Surabaya', 'XI', 'SMA'),
  ('14', '2026-06-25 04:07:11', '2026-06-25 04:07:11', NULL, '3', '5', '26', 'SIS-2024-014', 'Rini Agustina', 'P', '2007-05-30', 'Gresik', 'Jl. Mayjend Sungkono No. 20, Surabaya', '087812340014', 'Ibu Agustina', '081312340014', NULL, 'aktif', '2024-02-20', 'SMAN 2 Surabaya', 'XI', 'SMA'),
  ('15', '2026-06-25 04:07:11', '2026-06-25 04:07:11', NULL, '3', '5', '27', 'SIS-2024-015', 'Sandi Kurniawan', 'L', '2008-08-17', 'Sidoarjo', 'Jl. Sidoarjo No. 12, Sidoarjo', '087812340015', 'Bapak Kurniawan', '081312340015', NULL, 'aktif', '2024-03-15', 'SMPN 1 Sidoarjo', 'IX', 'SMP');

-- ---- Tabel: `system_settings` (0 baris) ----
-- (kosong)

-- ---- Tabel: `tagihans` (0 baris) ----
-- (kosong)

-- ---- Tabel: `tahun_ajarans` (0 baris) ----
-- (kosong)

-- ---- Tabel: `teacher_courses` (12 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `teacher_courses` (`id`, `teacher_id`, `course_id`, `created_at`, `updated_at`) VALUES
  ('1', '1', '1', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('2', '1', '2', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('3', '2', '3', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('4', '2', '4', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('5', '3', '5', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('6', '4', '1', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('7', '4', '6', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('8', '5', '7', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('9', '5', '9', '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('10', '6', '8', '2026-06-25 04:07:09', '2026-06-25 04:07:09'),
  ('11', '7', '10', '2026-06-25 04:07:09', '2026-06-25 04:07:09'),
  ('12', '7', '11', '2026-06-25 04:07:09', '2026-06-25 04:07:09');

-- ---- Tabel: `teachers` (7 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `teachers` (`id`, `user_id`, `branch_id`, `nig`, `name`, `gender`, `birth_date`, `birth_place`, `address`, `phone`, `email`, `education`, `subjects`, `photo`, `cv_path`, `salary_base`, `join_date`, `status`, `jenis_guru`, `deleted_at`, `created_at`, `updated_at`) VALUES
  ('1', '6', '1', 'NIG-2020-001', 'Budi Santoso, S.Pd.', 'L', '1985-03-15', 'Yogyakarta', 'Jl. Tebet Barat No. 12, Jakarta Selatan', '081234560001', 'budi.santoso@guru.akademibimbel.com', 'S1 Pendidikan Matematika UNY', '[\"Matematika\",\"Fisika\"]', NULL, NULL, '4500000.00', '2020-01-15', 'aktif', 'tetap', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('2', '7', '1', 'NIG-2020-002', 'Sari Dewi, M.Sc.', 'P', '1988-07-22', 'Bandung', 'Jl. Mampang Prapatan No. 5, Jakarta Selatan', '081234560002', 'sari.dewi@guru.akademibimbel.com', 'S2 Kimia ITB', '[\"Kimia\",\"Biologi\"]', NULL, NULL, '5000000.00', '2020-03-01', 'aktif', 'tetap', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('3', '8', '1', 'NIG-2021-003', 'Rizky Pratama, S.Pd.', 'L', '1992-11-05', 'Jakarta', 'Jl. Fatmawati No. 88, Jakarta Selatan', '081234560003', 'rizky.pratama@guru.akademibimbel.com', 'S1 Sastra Inggris UI', '[\"Bahasa Inggris\"]', NULL, NULL, '4000000.00', '2021-06-01', 'aktif', 'tetap', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('4', '9', '1', 'NIG-2021-004', 'Ahmad Fauzi, S.Si.', 'L', '1990-04-18', 'Semarang', 'Jl. Ciputat Raya No. 34, Jakarta Selatan', '081234560004', 'gurusci@gmail.com', 'S1 Matematika UNDIP', '[\"Matematika\",\"Persiapan SNBT\"]', NULL, NULL, '4500000.00', '2021-08-01', 'aktif', 'tetap', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('5', '10', '2', 'NIG-2022-005', 'Hani Rahayu, S.Pd.', 'P', '1991-09-30', 'Bandung', 'Jl. Cihampelas No. 15, Bandung', '081234560005', 'hani.rahayu@guru.akademibimbel.com', 'S1 Pendidikan Matematika UPI', '[\"Matematika\",\"Fisika\"]', NULL, NULL, '4000000.00', '2022-01-10', 'aktif', 'tetap', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08'),
  ('6', '11', '2', 'NIG-2022-006', 'Dimas Arya, S.Pd.', 'L', '1994-02-14', 'Sumedang', 'Jl. Buah Batu No. 40, Bandung', '081234560006', 'dimas.arya@guru.akademibimbel.com', 'S1 Bahasa Inggris UNPAD', '[\"Bahasa Inggris\"]', NULL, NULL, '3800000.00', '2022-04-01', 'aktif', 'honorer', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09'),
  ('7', '12', '3', 'NIG-2022-007', 'Yuni Kartika, M.Pd.', 'P', '1987-06-25', 'Surabaya', 'Jl. Diponegoro No. 100, Surabaya', '081234560007', 'yuni.kartika@guru.akademibimbel.com', 'S2 Pendidikan Matematika UNESA', '[\"Matematika\",\"Bahasa Inggris\"]', NULL, NULL, '4800000.00', '2022-07-01', 'aktif', 'tetap', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09');

-- ---- Tabel: `tryout_attempts` (3 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `tryout_attempts` (`id`, `created_at`, `updated_at`, `tryout_id`, `siswa_id`, `waktu_mulai`, `waktu_selesai`, `nilai`, `jawaban_benar`, `jawaban_salah`, `tidak_dijawab`, `percobaan_ke`, `status`, `jawaban`) VALUES
  ('1', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '3', '2026-05-31 00:00:00', '2026-05-31 01:18:00', '61.00', '3', '2', '0', '1', 'selesai', NULL),
  ('2', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '4', '2026-05-31 00:00:00', '2026-05-31 01:22:00', '88.00', '4', '1', '0', '1', 'selesai', NULL),
  ('3', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '8', '2026-05-31 00:00:00', '2026-05-31 01:06:00', '89.00', '4', '1', '0', '1', 'selesai', NULL);

-- ---- Tabel: `tryouts` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `tryouts` (`id`, `created_at`, `updated_at`, `cabang_id`, `dibuat_oleh`, `judul`, `deskripsi`, `kategori`, `durasi_menit`, `total_soal`, `nilai_kelulusan`, `waktu_mulai`, `waktu_selesai`, `is_random`, `tampilkan_hasil_langsung`, `tampilkan_kunci_jawaban`, `maksimal_percobaan`, `status`, `deleted_at`) VALUES
  ('1', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '1', 'Tryout SNBT Perdana 2024', 'Tryout perdana persiapan UTBK-SNBT 2024 dengan soal TPS dan Literasi.', 'SNBT', '90', '5', '60.00', '2026-05-26 00:00:00', '2026-06-10 00:00:00', '1', '1', '0', '1', 'selesai', NULL),
  ('2', '2026-06-25 04:07:12', '2026-06-25 04:07:12', '1', '1', 'Tryout Matematika SMA Ulangan Harian', 'Ulangan harian materi fungsi kuadrat dan trigonometri.', 'Matematika', '60', '5', '70.00', '2026-06-28 00:00:00', '2026-06-30 00:00:00', '0', '1', '1', '2', 'terjadwal', NULL);

-- ---- Tabel: `users` (27 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `avatar`, `branch_id`, `is_active`, `last_login_at`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
  ('1', 'Admin Pusat SCI', NULL, 'adminpusatsci@akademi.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$5PJ2.WLCRHAi28aBT66QeedqN6cE8RAidKdJqmmmetpfiPVigC3wS', NULL, '2026-06-25 04:07:06', '2026-06-25 04:07:07', NULL),
  ('2', 'Admin Cabang SCI', NULL, 'admincabangsci@akademi.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$Ec6.2gc4m4H20Zu3rJcCv.AZpjmazTKoZtZ1fzJ//8r161eJjGG9C', NULL, '2026-06-25 04:07:07', '2026-06-25 04:07:07', NULL),
  ('3', 'Admin Pusat Jakarta', NULL, 'adminpusat@akademibimbel.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$A8PdA4kqN9ekEcOKzBWTYuhfBR7wfkIyyqY3sz08gKB8kMB.jK0BC', NULL, '2026-06-25 04:07:07', '2026-06-25 04:07:07', NULL),
  ('4', 'Admin Cabang Bandung', NULL, 'adminbandung@akademibimbel.com', NULL, NULL, '2', '1', NULL, NULL, '$2y$10$lwXMCRedvU6PiFhs8teJKOY7SPL9hvx4xuR7KcMEjVmI36H2nafry', NULL, '2026-06-25 04:07:07', '2026-06-25 04:07:07', NULL),
  ('5', 'Admin Cabang Surabaya', NULL, 'adminsurabaya@akademibimbel.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$r/vO8g0n5aOopE57ylKg5OJXPIu0LaTTqzt3c0Ec3Jfq.2zhux0CG', NULL, '2026-06-25 04:07:07', '2026-06-25 04:07:07', NULL),
  ('6', 'Budi Santoso, S.Pd.', NULL, 'budi.santoso@guru.akademibimbel.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$g/PsWUFoWxiVIRStZYABT.fRJwSWma9u0TOenOW09V7UNBoPheX9q', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08', NULL),
  ('7', 'Sari Dewi, M.Sc.', NULL, 'sari.dewi@guru.akademibimbel.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$3DbdwdkhG8Xkrwih7pkipeeZpBtMkBw0IZQx4xcX0.zcmKIjhkJm2', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08', NULL),
  ('8', 'Rizky Pratama, S.Pd.', NULL, 'rizky.pratama@guru.akademibimbel.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$4hxwjORoj/yC2xmEZd/e9urISIWV.yvWw//T2gaeqpOXuX38N79CS', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08', NULL),
  ('9', 'Ahmad Fauzi, S.Si.', NULL, 'gurusci@gmail.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$bU88T9pjYz7rqbf8p.0lpuHDRHNR.VTnlvzaTJ1uvcMzhAL20az4C', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08', NULL),
  ('10', 'Hani Rahayu, S.Pd.', NULL, 'hani.rahayu@guru.akademibimbel.com', NULL, NULL, '2', '1', NULL, NULL, '$2y$10$cWyQQNKaUri5xk.PH8JUUOES9oBPIVwwB7Qx5PwMDSA5lsuWt5U0K', NULL, '2026-06-25 04:07:08', '2026-06-25 04:07:08', NULL),
  ('11', 'Dimas Arya, S.Pd.', NULL, 'dimas.arya@guru.akademibimbel.com', NULL, NULL, '2', '1', NULL, NULL, '$2y$10$UXOaSSY3nqy7OQQhRrYo2u7NUfF7gzwFknqDmhBzYbrYt7Ievfnke', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09', NULL),
  ('12', 'Yuni Kartika, M.Pd.', NULL, 'yuni.kartika@guru.akademibimbel.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$bVZh7MrhWTRCrzRnH4Byx.nEsHNf94.CTEUgMZGZFlJhkefP8893i', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09', NULL),
  ('13', 'Andi Nugroho', NULL, 'andi.nugroho@siswa.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$nXlrRGZubin44GaLZ8UB9.hbfVK/59hxUkk9CRD85NmRn.nfJ6fMq', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09', NULL),
  ('14', 'Citra Lestari', NULL, 'citra.lestari@siswa.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$N7HaCu8LZJkikQvm5h.Si.uzOpsmKpsjBg00PE5EzCypWdAEiSCK6', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09', NULL),
  ('15', 'Fajar Hidayat', NULL, 'fajar.hidayat@siswa.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$SjeLSjJnfvYsDDXk9/IXZuYTBEF1te/pUK1QDN5hIbJ/D5W7C5anO', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09', NULL),
  ('16', 'Gita Permata', NULL, 'gita.permata@siswa.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$5KTRjDbpxJvhHhV8buO3f.BfaAm.2w5ppN0cCVgNMzRasp9pH03O.', NULL, '2026-06-25 04:07:09', '2026-06-25 04:07:09', NULL),
  ('17', 'Hendra Putra', NULL, 'hendra.putra@siswa.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$sqlEj6e949MwDPyz.v02NuC/OqXWGH0Xab447dsa4EUG8cjK79Qgm', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL),
  ('18', 'Indah Sari', NULL, 'indah.sari@siswa.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$jZ2WuarPeFmLNDbhdUq2sesGHqHvfrQrGAWin4.dvcyYwoohB0M.i', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL),
  ('19', 'Joko Santoso', NULL, 'joko.santoso@siswa.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$QLE1EkfsvDZgJYNR85QVburZtghAkGVXw7Xs5AdGyO.2Awmpis0gS', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL),
  ('20', 'Kartini Wulandari', NULL, 'kartini.wulandari@siswa.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$Bmr4seUy.YbczJZgMFery.zQnCOe/Y7W18Rd5UW8ODLNAPGTNw462', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL),
  ('21', 'Luthfi Rahman', NULL, 'luthfi.rahman@siswa.com', NULL, NULL, '2', '1', NULL, NULL, '$2y$10$2GLEVSVXGaiTkSLUjK9dXeK60.8LZ9YRDbx8bD2U4jvSd0UxQf3OG', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL),
  ('22', 'Mira Kusuma', NULL, 'mira.kusuma@siswa.com', NULL, NULL, '2', '1', NULL, NULL, '$2y$10$piyuB/qnb/DlxOgFLN1/B.fEL2/QebdG3Fbw/Y/ftdmCM6AIq8SuS', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL),
  ('23', 'Naufal Ardiansyah', NULL, 'naufal.ardiansyah@siswa.com', NULL, NULL, '2', '1', NULL, NULL, '$2y$10$mBt5i8CgzB6Ifs0GA6wyjeK5FaNkPEDlPVGFGkih0BC9lcj155iJC', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL),
  ('24', 'Olivia Putri', NULL, 'olivia.putri@siswa.com', NULL, NULL, '2', '1', NULL, NULL, '$2y$10$fwiTsOCiWxyy6mNYrsz9qOSPypzpl0KvJ7x/g8AZfGCODZ7cxvtE2', NULL, '2026-06-25 04:07:10', '2026-06-25 04:07:10', NULL),
  ('25', 'Prasetyo Adi', NULL, 'prasetyo.adi@siswa.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$5gDBZ8MUgwzNpVrEE7n4X.Dtyu8JDr6SfMZusoC5.0Ov28.fjX6jq', NULL, '2026-06-25 04:07:11', '2026-06-25 04:07:11', NULL),
  ('26', 'Rini Agustina', NULL, 'rini.agustina@siswa.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$b5/6JcErUbra98v/3Kl.geeO84QmYN/iBk.7VsOg5klkJfxQFk0H2', NULL, '2026-06-25 04:07:11', '2026-06-25 04:07:11', NULL),
  ('27', 'Sandi Kurniawan', NULL, 'sandi.kurniawan@siswa.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$7/US7xeRyW1yJGgqTmCMKOMnAFS9VFW80Ao4OKqrYY1iTvbllYHoe', NULL, '2026-06-25 04:07:11', '2026-06-25 04:07:11', NULL);

-- ===========================================================================
-- Akhir bagian DATA
-- ===========================================================================

SET FOREIGN_KEY_CHECKS = 1;
-- Selesai — 2026-06-24 21:11:24
