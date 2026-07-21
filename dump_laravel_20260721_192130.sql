-- ===========================================================================
-- SQL DUMP — Migrasi + Seeder
-- Database  : laravel
-- Host      : 127.0.0.1:3306
-- Dibuat    : 2026-07-21 19:21:30
-- Tabel     : 83 tabel
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `articles`
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `ringkasan` text DEFAULT NULL,
  `konten` longtext NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `kategori` enum('tips','berita','akademik','promo','lainnya') NOT NULL DEFAULT 'berita',
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `penulis_id` bigint(20) unsigned NOT NULL,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_slug_unique` (`slug`),
  KEY `articles_penulis_id_foreign` (`penulis_id`),
  CONSTRAINT `articles_penulis_id_foreign` FOREIGN KEY (`penulis_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `branch_landing_settings`
DROP TABLE IF EXISTS `branch_landing_settings`;
CREATE TABLE `branch_landing_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `key` varchar(80) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branch_landing_settings_branch_id_key_unique` (`branch_id`,`key`),
  CONSTRAINT `branch_landing_settings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `branches`
DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `jenis_kursus` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courses_cabang_id_index` (`cabang_id`),
  KEY `courses_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `curricula`
DROP TABLE IF EXISTS `curricula`;
CREATE TABLE `curricula` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `scope` enum('global','lokal') NOT NULL DEFAULT 'global',
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `curricula_course_id_foreign` (`course_id`),
  KEY `curricula_cabang_id_foreign` (`cabang_id`),
  CONSTRAINT `curricula_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `curricula_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `curriculum_chapters`
DROP TABLE IF EXISTS `curriculum_chapters`;
CREATE TABLE `curriculum_chapters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `curriculum_id` bigint(20) unsigned NOT NULL,
  `judul` varchar(255) NOT NULL,
  `jumlah_sesi` smallint(5) unsigned NOT NULL DEFAULT 1,
  `urutan` smallint(5) unsigned NOT NULL DEFAULT 1,
  `pdf_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `curriculum_chapters_curriculum_id_foreign` (`curriculum_id`),
  CONSTRAINT `curriculum_chapters_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curricula` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `extra_class_requests`
DROP TABLE IF EXISTS `extra_class_requests`;
CREATE TABLE `extra_class_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `tanggal_rencana` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jumlah_sesi` smallint(5) unsigned NOT NULL DEFAULT 1,
  `harga` decimal(15,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `extra_class_requests_siswa_id_foreign` (`siswa_id`),
  KEY `extra_class_requests_course_id_foreign` (`course_id`),
  CONSTRAINT `extra_class_requests_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `extra_class_requests_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Tabel: `landing_faqs`
DROP TABLE IF EXISTS `landing_faqs`;
CREATE TABLE `landing_faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `landing_features`
DROP TABLE IF EXISTS `landing_features`;
CREATE TABLE `landing_features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(60) NOT NULL DEFAULT 'bi-check-circle-fill',
  `label` varchar(150) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `landing_galleries`
DROP TABLE IF EXISTS `landing_galleries`;
CREATE TABLE `landing_galleries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `alt` varchar(150) NOT NULL DEFAULT '',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `landing_highlights`
DROP TABLE IF EXISTS `landing_highlights`;
CREATE TABLE `landing_highlights` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `landing_jenjangs`
DROP TABLE IF EXISTS `landing_jenjangs`;
CREATE TABLE `landing_jenjangs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `label` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `emoji` varchar(10) NOT NULL DEFAULT '?',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `landing_testimonials`
DROP TABLE IF EXISTS `landing_testimonials`;
CREATE TABLE `landing_testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `gradient` varchar(255) NOT NULL DEFAULT 'linear-gradient(135deg,#c84ddf,#68117e)',
  `initial` varchar(5) NOT NULL DEFAULT 'A',
  `photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `landing_tickers`
DROP TABLE IF EXISTS `landing_tickers`;
CREATE TABLE `landing_tickers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `emoji` varchar(10) NOT NULL DEFAULT '?',
  `text` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `landing_trusts`
DROP TABLE IF EXISTS `landing_trusts`;
CREATE TABLE `landing_trusts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(60) NOT NULL DEFAULT 'bi-patch-check-fill',
  `text` varchar(150) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Tabel: `package_course_teachers`
DROP TABLE IF EXISTS `package_course_teachers`;
CREATE TABLE `package_course_teachers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `package_course_teachers_package_id_course_id_teacher_id_unique` (`package_id`,`course_id`,`teacher_id`),
  KEY `package_course_teachers_course_id_foreign` (`course_id`),
  KEY `package_course_teachers_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `package_course_teachers_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `package_course_teachers_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `package_course_teachers_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Tabel: `promos`
DROP TABLE IF EXISTS `promos`;
CREATE TABLE `promos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `tipe` enum('diskon','bundle_upgrade','special_price','lainnya') NOT NULL DEFAULT 'diskon',
  `kode_promo` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `banner_path` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date NOT NULL,
  `target` enum('semua','paket_intensif','cabang','cicilan') NOT NULL DEFAULT 'semua',
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('draft','aktif','berakhir') NOT NULL DEFAULT 'draft',
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `claims` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promos_kode_unique` (`kode`),
  KEY `promos_cabang_id_foreign` (`cabang_id`),
  CONSTRAINT `promos_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Tabel: `rooms`
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `nama_ruangan` varchar(100) NOT NULL,
  `kapasitas` int(10) unsigned NOT NULL DEFAULT 0,
  `status` enum('aktif','maintenance') NOT NULL DEFAULT 'aktif',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rooms_branch_id_foreign` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `pertemuan_ke` smallint(5) unsigned DEFAULT NULL,
  `cabang_id` bigint(20) unsigned DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `topik` varchar(255) DEFAULT NULL,
  `jenis` enum('online','offline','private') NOT NULL DEFAULT 'offline',
  `program_belajar` varchar(255) DEFAULT NULL,
  `ruangan` varchar(255) DEFAULT NULL,
  `link_meeting` varchar(255) DEFAULT NULL,
  `status` enum('dijadwalkan','berlangsung','selesai','dibatalkan') NOT NULL DEFAULT 'dijadwalkan',
  `catatan` text DEFAULT NULL,
  `honor_per_sesi` decimal(12,2) DEFAULT NULL,
  `alamat_kunjungan` varchar(500) DEFAULT NULL,
  `reminder_terkirim` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `mata_pelajaran_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedules_guru_id_index` (`guru_id`),
  KEY `schedules_cabang_id_index` (`cabang_id`),
  KEY `schedules_tanggal_index` (`tanggal`),
  KEY `schedules_paket_id_foreign` (`paket_id`),
  KEY `schedules_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  CONSTRAINT `schedules_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `schedules_paket_id_foreign` FOREIGN KEY (`paket_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `schedules_jenis_check` CHECK (`jenis` in ('online','offline','private'))
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Tabel: `student_leaves`
DROP TABLE IF EXISTS `student_leaves`;
CREATE TABLE `student_leaves` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `school_class_id` bigint(20) unsigned NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `alasan` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `catatan_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_leaves_student_id_foreign` (`student_id`),
  KEY `student_leaves_school_class_id_foreign` (`school_class_id`),
  CONSTRAINT `student_leaves_school_class_id_foreign` FOREIGN KEY (`school_class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_leaves_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
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
  `interest_sessions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interest_sessions`)),
  `interest_teachers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interest_teachers`)),
  `interest_teacher_honor` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interest_teacher_honor`)),
  `interest_teacher_sesi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interest_teacher_sesi`)),
  `day_preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`day_preferences`)),
  `schedule_time` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) NOT NULL DEFAULT 'belum_bayar',
  `academic_status` varchar(255) NOT NULL DEFAULT 'pending',
  `assigned_teacher_id` bigint(20) unsigned DEFAULT NULL,
  `biaya_per_sesi` decimal(15,2) DEFAULT NULL,
  `total_sessions` int(11) DEFAULT NULL,
  `total_biaya` decimal(15,2) DEFAULT NULL,
  `invoice_id` bigint(20) unsigned DEFAULT NULL,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_registrations_no_reg_unique` (`no_reg`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `total_sesi` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT 'Jumlah sesi yang dialokasikan untuk siswa ini, bukan dari paket',
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `teacher_registrations`
DROP TABLE IF EXISTS `teacher_registrations`;
CREATE TABLE `teacher_registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no_reg` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `nig` varchar(255) NOT NULL,
  `gender` enum('L','P') DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `jenis_guru` enum('kontrak','freelance') DEFAULT NULL,
  `salary_base` decimal(15,2) NOT NULL DEFAULT 0.00,
  `course_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`course_ids`)),
  `cv_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_registrations_nig_unique` (`nig`),
  UNIQUE KEY `teacher_registrations_email_unique` (`email`),
  UNIQUE KEY `teacher_registrations_no_reg_unique` (`no_reg`),
  KEY `teacher_registrations_branch_id_foreign` (`branch_id`),
  CONSTRAINT `teacher_registrations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- ---- Tabel: `academic_years` (1 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `academic_years` (`id`, `name`, `year_start`, `year_end`, `is_active`, `created_at`, `updated_at`) VALUES
  ('1', '2025/2026', '2025', '2026', '1', '2026-07-22 02:20:08', '2026-07-22 02:20:08');

-- ---- Tabel: `activity_log` (8 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
  ('1', 'default', 'created', 'App\\Models\\User', 'created', '1', NULL, NULL, '{\"attributes\":{\"id\":1,\"name\":\"Admin Pusat SCI\",\"username\":null,\"email\":\"adminpusatsci@akademi.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$\\/tLETCeT\\/sUAl9d5iChH0eAEGx0EG0gVsBQUM14GxvMlvVNaZXx\\/m\",\"remember_token\":null,\"created_at\":\"2026-07-21T19:20:06.000000Z\",\"updated_at\":\"2026-07-21T19:20:06.000000Z\",\"deleted_at\":null}}', NULL, '2026-07-22 02:20:06', '2026-07-22 02:20:06'),
  ('2', 'default', 'created', 'App\\Models\\User', 'created', '2', NULL, NULL, '{\"attributes\":{\"id\":2,\"name\":\"Admin Cabang SCI\",\"username\":null,\"email\":\"admincabangsci@akademi.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$FMW3G5jJkTIrNv.6emc1ru8qI2P3HlZHqgD8j0kJPFroknwM8MNI2\",\"remember_token\":null,\"created_at\":\"2026-07-21T19:20:06.000000Z\",\"updated_at\":\"2026-07-21T19:20:06.000000Z\",\"deleted_at\":null}}', NULL, '2026-07-22 02:20:06', '2026-07-22 02:20:06'),
  ('3', 'default', 'deleted', 'App\\Models\\User', 'deleted', '2', NULL, NULL, '{\"old\":{\"id\":2,\"name\":\"Admin Cabang SCI\",\"username\":null,\"email\":\"admincabangsci@akademi.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$FMW3G5jJkTIrNv.6emc1ru8qI2P3HlZHqgD8j0kJPFroknwM8MNI2\",\"remember_token\":null,\"created_at\":\"2026-07-21T19:20:06.000000Z\",\"updated_at\":\"2026-07-21T19:20:06.000000Z\",\"deleted_at\":null}}', NULL, '2026-07-22 02:20:08', '2026-07-22 02:20:08'),
  ('4', 'default', 'created', 'App\\Models\\User', 'created', '3', NULL, NULL, '{\"attributes\":{\"id\":3,\"name\":\"Budi Santoso, S.Pd.\",\"username\":null,\"email\":\"budi.santoso@guru.akademisci.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$CTgSAKEDI9xjx35JwfuNDOJXWWsRWAaqsLCIMdiHUUxmtXioW9Mbi\",\"remember_token\":null,\"created_at\":\"2026-07-21T19:20:08.000000Z\",\"updated_at\":\"2026-07-21T19:20:08.000000Z\",\"deleted_at\":null}}', NULL, '2026-07-22 02:20:08', '2026-07-22 02:20:08'),
  ('5', 'default', 'created', 'App\\Models\\User', 'created', '4', NULL, NULL, '{\"attributes\":{\"id\":4,\"name\":\"Sari Dewi, S.Pd.\",\"username\":null,\"email\":\"sari.dewi@guru.akademisci.com\",\"phone\":null,\"avatar\":null,\"branch_id\":2,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$aemFKZHpo8\\/8ksr6au5KI.on.iwr\\/UUqCa\\/8MBtwdvMR15etEdS.C\",\"remember_token\":null,\"created_at\":\"2026-07-21T19:20:08.000000Z\",\"updated_at\":\"2026-07-21T19:20:08.000000Z\",\"deleted_at\":null}}', NULL, '2026-07-22 02:20:08', '2026-07-22 02:20:08'),
  ('6', 'default', 'created', 'App\\Models\\User', 'created', '5', NULL, NULL, '{\"attributes\":{\"id\":5,\"name\":\"Andi Nugroho\",\"username\":null,\"email\":\"andi.nugroho@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$iBFFUVjox7d6j8ge3OPkcejwddodFcoNnF8nUJoCn.CX\\/Pmw9QlIi\",\"remember_token\":null,\"created_at\":\"2026-07-21T19:20:08.000000Z\",\"updated_at\":\"2026-07-21T19:20:08.000000Z\",\"deleted_at\":null}}', NULL, '2026-07-22 02:20:08', '2026-07-22 02:20:08'),
  ('7', 'default', 'created', 'App\\Models\\User', 'created', '6', NULL, NULL, '{\"attributes\":{\"id\":6,\"name\":\"Citra Lestari\",\"username\":null,\"email\":\"citra.lestari@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$rpRzL14MJfYrruQgOYnNPOiQG78XDzyE9ScQq6rkPhqXQ2Dsc2rDK\",\"remember_token\":null,\"created_at\":\"2026-07-21T19:20:08.000000Z\",\"updated_at\":\"2026-07-21T19:20:08.000000Z\",\"deleted_at\":null}}', NULL, '2026-07-22 02:20:08', '2026-07-22 02:20:08'),
  ('8', 'default', 'created', 'App\\Models\\User', 'created', '7', NULL, NULL, '{\"attributes\":{\"id\":7,\"name\":\"Ahmad Fauzi, S.Si.\",\"username\":null,\"email\":\"gurusci@gmail.com\",\"phone\":null,\"avatar\":null,\"branch_id\":1,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$nNeFsX4JG4.rKsEVpmkCZ.nK74GEzX770Cj6zGw\\/Jo5t2pPJUev.y\",\"remember_token\":null,\"created_at\":\"2026-07-21T19:20:09.000000Z\",\"updated_at\":\"2026-07-21T19:20:09.000000Z\",\"deleted_at\":null}}', NULL, '2026-07-22 02:20:09', '2026-07-22 02:20:09');

-- ---- Tabel: `activity_logs` (0 baris) ----
-- (kosong)

-- ---- Tabel: `announcements` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `announcements` (`id`, `cabang_id`, `dibuat_oleh`, `judul`, `konten`, `jenis`, `target`, `target_teacher_ids`, `target_student_ids`, `file`, `tanggal_mulai`, `tanggal_selesai`, `is_pinned`, `status`, `created_at`, `updated_at`) VALUES
  ('1', '1', '1', 'Selamat Datang di Akademi SCI', 'Selamat datang di sistem manajemen Akademi SCI. Kami siap mendukung proses belajar Anda.', 'info', 'semua', NULL, NULL, NULL, '2026-07-22', '2027-01-22', '1', 'aktif', '2026-07-22 02:20:08', '2026-07-22 02:20:08'),
  ('2', '1', '1', 'Jadwal Tryout SNBT Bulan Februari', 'Tryout SNBT akan diadakan pada 15 Februari 2025. Harap mempersiapkan diri dengan baik.', 'event', 'siswa', NULL, NULL, NULL, '2025-02-01', '2025-02-15', '0', 'aktif', '2026-07-22 02:20:08', '2026-07-22 02:20:08');

-- ---- Tabel: `articles` (0 baris) ----
-- (kosong)

-- ---- Tabel: `branch_landing_settings` (0 baris) ----
-- (kosong)

-- ---- Tabel: `branches` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `branches` (`id`, `user_id`, `name`, `address`, `photo`, `phone`, `city`, `admin_id`, `created_by`, `updated_by`, `student_count`, `status`, `created_at`, `updated_at`, `regency`, `email`, `password`, `can_students`, `can_teachers`, `can_schedules`, `can_payments`, `can_tryouts`, `allowed_pages`) VALUES
  ('1', NULL, 'Cabang Jakarta', 'Jl. Sudirman No. 1, Jakarta Pusat', NULL, '021-5555001', 'Jakarta', NULL, NULL, NULL, '0', 'active', '2026-07-22 02:20:08', '2026-07-22 02:20:08', 'Jakarta Pusat', 'jakarta@akademisci.com', NULL, '1', '1', '1', '1', '1', NULL),
  ('2', NULL, 'Cabang Bandung', 'Jl. Braga No. 22, Bandung', NULL, '022-4444001', 'Bandung', NULL, NULL, NULL, '0', 'active', '2026-07-22 02:20:08', '2026-07-22 02:20:08', 'Bandung Kota', 'bandung@akademisci.com', NULL, '1', '1', '1', '1', '1', NULL);

-- ---- Tabel: `categories` (0 baris) ----
-- (kosong)

-- ---- Tabel: `certificates` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `certificates` (`id`, `siswa_id`, `cabang_id`, `course_id`, `diterbitkan_oleh`, `nomor_sertifikat`, `jenis`, `judul`, `deskripsi`, `tanggal_terbit`, `tanggal_expired`, `file_sertifikat`, `file_qrcode`, `created_at`, `updated_at`, `deleted_at`) VALUES
  ('1', '1', '1', '1', '1', 'CERT-2025-0001', 'kelulusan', 'Sertifikat Kelulusan Matematika', 'Dinyatakan lulus program Matematika Reguler.', '2025-01-31', NULL, NULL, NULL, '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL),
  ('2', '2', '1', '2', '1', 'CERT-2025-0002', 'kelulusan', 'Sertifikat Kelulusan Bahasa Inggris', 'Dinyatakan lulus program Bahasa Inggris Dasar.', '2025-01-31', NULL, NULL, NULL, '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL);

-- ---- Tabel: `chat_messages` (0 baris) ----
-- (kosong)

-- ---- Tabel: `chat_rooms` (0 baris) ----
-- (kosong)

-- ---- Tabel: `class_students` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `class_students` (`id`, `class_id`, `student_id`, `created_at`, `updated_at`) VALUES
  ('1', '1', '1', NULL, NULL),
  ('2', '1', '2', NULL, NULL);

-- ---- Tabel: `course_fees` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `course_fees` (`id`, `course_id`, `amount`, `created_at`, `updated_at`) VALUES
  ('1', '1', '350000.00', '2026-07-22 02:20:08', '2026-07-22 02:20:08'),
  ('2', '2', '300000.00', '2026-07-22 02:20:08', '2026-07-22 02:20:08');

-- ---- Tabel: `course_package` (0 baris) ----
-- (kosong)

-- ---- Tabel: `courses` (41 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `courses` (`id`, `created_at`, `updated_at`, `cabang_id`, `kode`, `nama`, `kategori`, `jenis_kursus`, `deskripsi`, `status`, `deleted_at`) VALUES
  ('1', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '1', 'MAT-001', 'Matematika', 'Saintek', NULL, 'Matematika dasar dan lanjutan untuk semua jenjang.', 'aktif', NULL),
  ('2', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '2', 'ING-001', 'Bahasa Inggris', 'Umum', NULL, 'Grammar, reading, writing, dan speaking skills.', 'aktif', NULL),
  ('3', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'KOM-01', 'Microsoft Office Perkantoran', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('4', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'KOM-02', 'Word', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('5', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'KOM-03', 'Excel', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('6', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'KOM-04', 'PowerPoint', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('7', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'KOM-05', 'Desain Grafis', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('8', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'KOM-06', 'CorelDraw', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('9', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'KOM-07', 'Photoshop', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('10', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'KOM-08', 'AutoCAD', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('11', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'KOM-09', 'Programmer / Coding', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('12', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'BHS-01', 'Bahasa Inggris', 'skill', 'bahasa', NULL, 'aktif', NULL),
  ('13', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'BHS-02', 'Bahasa Arab', 'skill', 'bahasa', NULL, 'aktif', NULL),
  ('14', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'BHS-03', 'Bahasa Mandarin', 'skill', 'bahasa', NULL, 'aktif', NULL),
  ('15', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'BHS-04', 'Bahasa Jepang', 'skill', 'bahasa', NULL, 'aktif', NULL),
  ('16', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'BHS-05', 'Bahasa Korea', 'skill', 'bahasa', NULL, 'aktif', NULL),
  ('17', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'MAP-01', 'Matematika', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('18', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'MAP-02', 'Kimia', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('19', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'MAP-03', 'Biologi', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('20', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'MAP-04', 'Bahasa Indonesia', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('21', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'MAP-05', 'Fisika', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('22', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'MAP-06', 'Akuntansi / Ekonomi', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('23', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'MAP-07', 'Geografi', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('24', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'MAP-08', 'IPA', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('25', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'MAP-09', 'IPS', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('26', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'DIN-01', 'SKD TIU', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('27', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'DIN-02', 'SKD TWK', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('28', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'DIN-03', 'SKD TKP', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('29', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'DIN-04', 'TPA', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('30', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'DIN-05', 'Psikotes', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('31', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'DIN-06', 'TBI', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('32', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'AKP-01', 'Pengetahuan Umum', 'academic', 'akpol', NULL, 'aktif', NULL),
  ('33', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'AKP-02', 'Wawasan Kebangsaan', 'academic', 'akpol', NULL, 'aktif', NULL),
  ('34', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'AKP-03', 'TKD', 'academic', 'akpol', NULL, 'aktif', NULL),
  ('35', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'AKP-04', 'Tes Akademik', 'academic', 'akpol', NULL, 'aktif', NULL),
  ('36', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'CPN-01', 'SKD TIU (CPNS)', 'academic', 'cpns', NULL, 'aktif', NULL),
  ('37', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'CPN-02', 'SKD TWK (CPNS)', 'academic', 'cpns', NULL, 'aktif', NULL),
  ('38', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'CPN-03', 'SKD TKP (CPNS)', 'academic', 'cpns', NULL, 'aktif', NULL),
  ('39', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'BUM-01', 'TKD BUMN', 'academic', 'bumn', NULL, 'aktif', NULL),
  ('40', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'BUM-02', 'Tes AKHLAK', 'academic', 'bumn', NULL, 'aktif', NULL),
  ('41', '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL, 'BUM-03', 'TWK BUMN', 'academic', 'bumn', NULL, 'aktif', NULL);

-- ---- Tabel: `curricula` (0 baris) ----
-- (kosong)

-- ---- Tabel: `curriculum_chapters` (0 baris) ----
-- (kosong)

-- ---- Tabel: `extra_class_requests` (0 baris) ----
-- (kosong)

-- ---- Tabel: `failed_jobs` (0 baris) ----
-- (kosong)

-- ---- Tabel: `gajis` (0 baris) ----
-- (kosong)

-- ---- Tabel: `grades` (0 baris) ----
-- (kosong)

-- ---- Tabel: `guru_mapel` (0 baris) ----
-- (kosong)

-- ---- Tabel: `gurus` (0 baris) ----
-- (kosong)

-- ---- Tabel: `invoices` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `invoices` (`id`, `created_at`, `updated_at`, `siswa_id`, `cabang_id`, `kelas_id`, `nomor_invoice`, `subtotal`, `diskon`, `pajak`, `total`, `deskripsi`, `periode`, `jatuh_tempo`, `status`, `catatan`, `deleted_at`) VALUES
  ('1', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '1', '1', NULL, 'INV-2025-0001', '750000.00', '0.00', '0.00', '750000.00', 'Biaya Paket Reguler SMA - Januari 2025', '2025-01', '2025-01-15', 'lunas', NULL, NULL),
  ('2', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '2', '1', NULL, 'INV-2025-0002', '2500000.00', '0.00', '0.00', '2500000.00', 'Biaya Paket Intensif SNBT - Februari 2025', '2025-02', '2025-02-15', 'belum_bayar', NULL, NULL);

-- ---- Tabel: `jadwals` (0 baris) ----
-- (kosong)

-- ---- Tabel: `kelas` (0 baris) ----
-- (kosong)

-- ---- Tabel: `kelas_siswa` (0 baris) ----
-- (kosong)

-- ---- Tabel: `landing_faqs` (6 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `landing_faqs` (`id`, `question`, `answer`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('1', 'Bagaimana cara mendaftar di SCI?', 'Anda bisa mendaftar melalui website ini, menghubungi kami via WhatsApp, atau langsung datang ke cabang SCI terdekat. Tim kami akan membantu proses pendaftaran dengan mudah dan cepat.', '1', '0', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('2', 'Apakah bisa datang ke rumah?', 'Ya! Kami menyediakan layanan home visit di mana tutor kami akan datang langsung ke rumah Anda. Jadwal fleksibel dan nyaman tanpa perlu keluar rumah.', '1', '1', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('3', 'Jenjang apa saja yang dilayani?', 'SCI melayani semua jenjang mulai dari TK, SD, SMP, SMA, hingga mahasiswa dan umum. Tersedia juga kursus bahasa, komputer, dan akuntansi untuk semua usia.', '1', '2', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('4', 'Berapa biaya les privat di SCI?', 'Biaya bervariasi tergantung jenjang, mata pelajaran, dan metode belajar (online/offline/home visit). Hubungi kami untuk mendapatkan penawaran terbaik sesuai kebutuhan Anda.', '1', '3', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('5', 'Apakah ada garansi hasil belajar?', 'Ya! SCI memberikan garansi hasil belajar. Jika nilai tidak meningkat sesuai target yang disepakati, kami siap memberikan sesi tambahan tanpa biaya ekstra.', '1', '4', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('6', 'Bagaimana sistem pembayaran di SCI?', 'Pembayaran bisa dilakukan bulanan atau per paket belajar. Tersedia berbagai metode pembayaran termasuk transfer bank, dompet digital, dan tunai di cabang.', '1', '5', '2026-07-22 02:20:10', '2026-07-22 02:20:10');

-- ---- Tabel: `landing_features` (6 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `landing_features` (`id`, `icon`, `label`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('1', 'bi-patch-check-fill', 'Tutor Bersertifikat', '1', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('2', 'bi-house-heart-fill', 'Bisa Home Visit', '1', '1', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('3', 'bi-camera-video-fill', 'Kelas Online & Offline', '1', '2', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('4', 'bi-bar-chart-fill', 'Evaluasi Rutin Bulanan', '1', '3', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('5', 'bi-headset', 'Konsultasi 24/7', '1', '4', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('6', 'bi-bullseye', 'Target & Hasil Terukur', '1', '5', '2026-07-22 02:20:09', '2026-07-22 02:20:09');

-- ---- Tabel: `landing_galleries` (8 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `landing_galleries` (`id`, `image`, `alt`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('1', 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=700&q=80', 'Kelas Belajar', '1', '0', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('2', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=700&q=80', 'Diskusi Kelompok', '1', '1', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('3', 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=700&q=80', 'Les Online', '1', '2', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('4', 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=700&q=80', 'Tutor Mengajar', '1', '3', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('5', 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=700&q=80', 'Les Privat', '1', '4', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('6', 'https://images.unsplash.com/photo-1509869175650-a1d97972541a?w=700&q=80', 'Kursus Komputer', '1', '5', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('7', 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=700&q=80', 'Persiapan Ujian', '1', '6', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('8', 'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=700&q=80', 'Belajar Bersama', '1', '7', '2026-07-22 02:20:10', '2026-07-22 02:20:10');

-- ---- Tabel: `landing_highlights` (5 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `landing_highlights` (`id`, `image`, `title`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('1', 'https://images.unsplash.com/photo-1607990281513-2c110a25bd8c?w=200&q=80', 'Tutor Profesional', 'Pengajar ahli bersertifikat resmi dengan pengalaman bertahun-tahun dan rekam jejak hasil nyata.', '1', '0', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('2', 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=200&q=80', 'Bisa Home Visit', 'Tutor kami siap datang ke rumah Anda kapan saja. Jadwal fleksibel, nyaman, dan tanpa perlu repot.', '1', '1', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('3', 'https://images.unsplash.com/photo-1581092334651-ddf26d9a09d0?w=200&q=80', 'Metode Modern', 'Sistem belajar interaktif yang disesuaikan dengan gaya belajar masing-masing siswa. Belajar itu menyenangkan!', '1', '2', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('4', 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=200&q=80', 'Hasil Terukur', 'Evaluasi rutin, progress terpantau, laporan bulanan. Nilai meningkat signifikan — dijamin atau kami ulang!', '1', '3', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('5', 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&q=80', 'Support Penuh', 'Bantuan belajar & konsultasi 24/7 via WhatsApp. Kami selalu ada untuk mendukung perjalanan belajar Anda.', '1', '4', '2026-07-22 02:20:10', '2026-07-22 02:20:10');

-- ---- Tabel: `landing_jenjangs` (4 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `landing_jenjangs` (`id`, `name`, `label`, `image`, `emoji`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('1', 'TK', 'Taman Kanak-Kanak', 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=200&q=80&auto=format&fit=crop', '🌱', '1', '0', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('2', 'SD', 'Sekolah Dasar', 'https://images.unsplash.com/photo-1588072432836-e10032774350?w=200&q=80&auto=format&fit=crop', '📚', '1', '1', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('3', 'SMP', 'Sekolah Menengah Pertama', 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=200&q=80&auto=format&fit=crop', '🔬', '1', '2', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('4', 'SMA / Umum', 'SMA & Karyawan', 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=200&q=80&auto=format&fit=crop', '🎓', '1', '3', '2026-07-22 02:20:10', '2026-07-22 02:20:10');

-- ---- Tabel: `landing_programs` (6 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `landing_programs` (`id`, `title`, `description`, `badge_label`, `badge_bg`, `badge_color`, `icon_emoji`, `image`, `is_active`, `is_popular`, `is_new`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('1', 'Bimbel Mata Pelajaran', 'Bimbingan semua mata pelajaran sekolah dengan metode efektif dan menyenangkan.', 'SEMUA JENJANG', '#e8f5e9', '#2e7d32', '📖', 'https://images.unsplash.com/photo-1509228468518-180dd4864904?w=600&q=80', '1', '0', '0', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('2', 'Persiapan Ujian', 'Persiapan UTS, UAS & Ujian Sekolah agar nilai meningkat pesat dan lulus terbaik.', 'SMP · SMA', '#f3e8ff', '#7e22ce', '📝', 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&q=80', '1', '0', '0', '1', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('3', 'Persiapan Tes & SBMPTN', 'Persiapan masuk sekolah favorit, PTN, CPNS & tes lainnya secara intensif.', 'INTENSIF', '#fff7ed', '#c2410c', '🎯', 'https://images.unsplash.com/photo-1503676382389-4809596d5290?w=600&q=80', '1', '0', '0', '2', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('4', 'Kursus Bahasa', 'Inggris, Jepang, Mandarin, Arab — tingkatkan kemampuan bahasa Anda bersama kami.', 'SEMUA LEVEL', '#e0f2fe', '#0369a1', '🗣️', 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80', '1', '0', '0', '3', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('5', 'Kursus Komputer', 'Microsoft Office, Desain Grafis, Programming — teknologi terkini untuk karir masa depan.', 'POPULER 🔥', '#fef2f2', '#b91c1c', '💻', 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=600&q=80', '1', '1', '0', '4', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('6', 'Kursus Akuntansi', 'Akuntansi dasar hingga profesional: perpajakan & keuangan untuk mahasiswa dan karyawan.', 'TERBARU ✨', '#f5f3ff', '#6d28d9', '📊', 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=600&q=80', '1', '0', '1', '5', '2026-07-22 02:20:10', '2026-07-22 02:20:10');

-- ---- Tabel: `landing_settings` (46 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `landing_settings` (`id`, `section`, `key`, `value`, `type`, `label`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('1', 'hero', 'hero.badge_text', 'Bimbel & Kursus Terbaik #1 di Indonesia', 'text', 'Teks Badge Hero', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('2', 'hero', 'hero.title_line1', 'Wujudkan Mimpi,', 'text', 'Judul Baris 1', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('3', 'hero', 'hero.title_line2', 'Raih Prestasi!', 'text', 'Judul Baris 2', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('4', 'hero', 'hero.description', 'Lembaga bimbingan belajar, kursus, dan les privat terbaik di Indonesia. Melayani TK hingga umum dengan tutor profesional dan hasil terukur.', 'text', 'Deskripsi Hero', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('5', 'hero', 'hero.slide_1_url', 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=1920&q=80', 'image', 'Slide 1 URL', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('6', 'hero', 'hero.slide_2_url', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1920&q=80', 'image', 'Slide 2 URL', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('7', 'hero', 'hero.slide_3_url', 'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=1920&q=80', 'image', 'Slide 3 URL', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('8', 'hero', 'hero.float1_title', '', 'text', 'Float Card 1 Judul', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('9', 'hero', 'hero.float1_subtitle', '', 'text', 'Float Card 1 Subjudul', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('10', 'hero', 'hero.float2_title', '', 'text', 'Float Card 2 Judul', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('11', 'hero', 'hero.float2_subtitle', '', 'text', 'Float Card 2 Subjudul', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('12', 'stats', 'stats.years_exp', '14+', 'text', 'Tahun Pengalaman', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('13', 'stats', 'stats.satisfaction', '98%', 'text', 'Kepuasan Pelanggan', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('14', 'cta', 'cta.eyebrow', 'Mulai Sekarang', 'text', 'Eyebrow CTA', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('15', 'cta', 'cta.title', 'Wujudkan Mimpi Bersama SCI!', 'text', 'Judul CTA', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('16', 'cta', 'cta.description', 'Daftar sekarang dan mulai perjalanan belajarmu bersama tutor terbaik kami.', 'text', 'Deskripsi CTA', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('17', 'footer', 'footer.brand_desc', 'Platform pendidikan modern untuk semua jenjang. Dari TK hingga profesional — kami selalu ada untuk mendukung perjalanan belajar Anda.', 'text', 'Deskripsi Brand Footer', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('18', 'footer', 'footer.instagram', '#', 'text', 'URL Instagram', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('19', 'footer', 'footer.facebook', '#', 'text', 'URL Facebook', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('20', 'footer', 'footer.youtube', '#', 'text', 'URL YouTube', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('21', 'tentang', 'tentang.title_line1', 'Tentang', 'text', 'Judul Baris 1', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('22', 'tentang', 'tentang.title_accent', 'Smart Center Indonesia', 'text', 'Judul Aksen', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('23', 'tentang', 'tentang.desc1', 'Smart Center Indonesia (SCI) adalah lembaga pendidikan yang bergerak di bidang bimbingan belajar, kursus, dan les privat (1 guru 1 siswa) berbasis offline dan online yang berkomitmen menjadi lembaga terbaik nomor 1 di Indonesia.', 'text', 'Deskripsi 1', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('24', 'tentang', 'tentang.desc2', 'Dengan metode pembelajaran efektif, pengajar berpengalaman, serta pendekatan personal, SCI hadir sebagai solusi pendidikan terpercaya.', 'text', 'Deskripsi 2', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('25', 'tentang', 'tentang.quote', 'Wujudkan mimpi, raih prestasi!', 'text', 'Kutipan', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('26', 'cariguru', 'cariguru.eyebrow', 'TEMUKAN PENGAJAR TERBAIK', 'text', 'Eyebrow', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('27', 'cariguru', 'cariguru.title_line1', 'Cari Guru', 'text', 'Judul Baris 1', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('28', 'cariguru', 'cariguru.title_accent', 'Terbaik', 'text', 'Judul Aksen', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('29', 'cariguru', 'cariguru.title_line2', ', Secepat Klik', 'text', 'Judul Baris 2', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('30', 'cariguru', 'cariguru.subtitle', 'Temukan tutor privat terbaik di kotamu — pilih berdasarkan mata pelajaran, lokasi, dan metode belajar yang kamu inginkan.', 'text', 'Subjudul', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('31', 'keunggulan', 'keunggulan.title_accent', 'SCI', 'text', 'Judul Aksen', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('32', 'keunggulan', 'keunggulan.subtitle', 'Lima pilar yang membuat SCI menjadi pilihan terpercaya jutaan keluarga Indonesia selama 14+ tahun.', 'text', 'Subjudul', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('33', 'galeri', 'galeri.subtitle', 'Momen belajar menyenangkan bersama siswa dan tutor terbaik SCI di seluruh Indonesia.', 'text', 'Subjudul', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('34', 'galeri', 'galeri.title_line1', 'Galeri', 'text', 'Judul Baris 1', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('35', 'galeri', 'galeri.title_accent', 'Kegiatan', 'text', 'Judul Aksen', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('36', 'bantuan', 'bantuan.eyebrow', 'Bantuan & Kontak', 'text', 'Eyebrow', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('37', 'bantuan', 'bantuan.title_line1', 'Pertanyaan &', 'text', 'Judul Baris 1', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('38', 'bantuan', 'bantuan.title_accent', 'Hubungi Kami', 'text', 'Judul Aksen', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('39', 'bantuan', 'bantuan.subtitle', 'Punya pertanyaan atau ingin bergabung? Kami siap membantu Anda kapan saja.', 'text', 'Subjudul', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('40', 'tutor', 'tutor.eyebrow', 'Tim Pengajar', 'text', 'Eyebrow', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('41', 'tutor', 'tutor.title_line1', 'Tutor', 'text', 'Judul Baris 1', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('42', 'tutor', 'tutor.title_accent', 'Terbaik', 'text', 'Judul Aksen', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('43', 'tutor', 'tutor.title_line2', 'Kami', 'text', 'Judul Baris 2', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('44', 'tutor', 'tutor.subtitle', 'Dilatih secara profesional dan berpengalaman di bidangnya masing-masing untuk memberikan hasil terbaik bagi setiap siswa.', 'text', 'Subjudul', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('45', 'cabang', 'cabang.eyebrow', 'Hadir di Seluruh Indonesia', 'text', 'Eyebrow', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('46', 'cabang', 'cabang.subtitle', 'Dengan 150+ cabang di berbagai kota, SCI selalu dekat dengan Anda dan keluarga.', 'text', 'Subjudul', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09');

-- ---- Tabel: `landing_testimonials` (4 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `landing_testimonials` (`id`, `name`, `role`, `text`, `gradient`, `initial`, `photo`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('1', 'Aisyah Rahma', 'Siswa SMA · Matematika', 'Belajar di SCI sangat menyenangkan! Tutor menjelaskan dengan cara yang mudah dipahami dan nilai saya meningkat pesat. Sangat merekomendasikan untuk semua!', 'linear-gradient(135deg,#c84ddf,#68117e)', 'A', NULL, '1', '0', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('2', 'Ricky Pratama', 'Mahasiswa · Persiapan SBMPTN', 'Program persiapan ujian di SCI sangat membantu. Akhirnya lolos ke kampus impian! Materinya lengkap banget dan tutornya super sabar dan profesional.', 'linear-gradient(135deg,#10b981,#059669)', 'R', NULL, '1', '1', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('3', 'Dinda Lestari', 'Mahasiswi · Akuntansi', 'Kursus akuntansi di SCI sangat bermanfaat untuk tugas kuliah dan persiapan kerja. Tutornya sabar, materi lengkap, dan nilai kuliah saya jadi meningkat!', 'linear-gradient(135deg,#6366f1,#4338ca)', 'D', NULL, '1', '2', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('4', 'Bunda Sari', 'Orang Tua Siswa · Jakarta', 'Anakku yang awalnya kesulitan di pelajaran IPA sekarang jadi juara kelas! Metode belajar di SCI sangat efektif dan tutornya sangat sabar dan perhatian.', 'linear-gradient(135deg,#f97316,#ea580c)', 'B', NULL, '1', '3', '2026-07-22 02:20:10', '2026-07-22 02:20:10');

-- ---- Tabel: `landing_tickers` (6 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `landing_tickers` (`id`, `emoji`, `text`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('1', '🎉', 'Diskon Spesial! Gratis biaya pendaftaran bulan ini', '1', '0', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('2', '📚', 'Daftar sekarang & dapatkan sesi konsultasi GRATIS!', '1', '1', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('3', '🎁', 'Promo Paket Hemat: Beli 10 sesi gratis 2 sesi ekstra', '1', '2', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('4', '⭐', 'Lebih dari 1.000+ siswa sudah bergabung bersama kami', '1', '3', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('5', '🏆', 'Tutor berpengalaman & bersertifikat nasional', '1', '4', '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('6', '📞', 'Hubungi kami sekarang — konsultasi gratis!', '1', '5', '2026-07-22 02:20:09', '2026-07-22 02:20:09');

-- ---- Tabel: `landing_trusts` (4 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `landing_trusts` (`id`, `icon`, `text`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('1', 'bi-patch-check-fill', '500+ Tutor Bersertifikat', '1', '0', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('2', 'bi-lightning-fill', 'Respon dalam 1 Jam', '1', '1', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('3', 'bi-shield-fill-check', 'Aman & Terpercaya', '1', '2', '2026-07-22 02:20:10', '2026-07-22 02:20:10'),
  ('4', 'bi-award-fill', 'Garansi Hasil Belajar', '1', '3', '2026-07-22 02:20:10', '2026-07-22 02:20:10');

-- ---- Tabel: `landing_wa_numbers` (1 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `landing_wa_numbers` (`id`, `label`, `number`, `description`, `is_primary`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('1', 'WhatsApp Pusat', '6285333399210', 'Nomor utama kantor pusat', '1', '1', '0', '2026-07-22 02:20:10', '2026-07-22 02:20:10');

-- ---- Tabel: `mapel_paket` (0 baris) ----
-- (kosong)

-- ---- Tabel: `mapels` (0 baris) ----
-- (kosong)

-- ---- Tabel: `migrations` (119 baris) ----
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
  ('96', '2026_06_22_210000_add_kelas_id_to_invoices_module_id_to_schedules', '1'),
  ('97', '2026_06_25_000001_add_mata_pelajaran_id_to_schedules', '1'),
  ('98', '2026_06_25_100001_create_package_course_teachers_table', '1'),
  ('99', '2026_06_25_200000_add_jenis_kursus_to_courses_table', '1'),
  ('100', '2026_06_25_300000_add_registration_fields_to_student_registrations', '1'),
  ('101', '2026_06_26_100810_create_extra_class_requests_table', '1'),
  ('102', '2026_06_26_122923_add_honor_and_address_to_schedules_table', '1'),
  ('103', '2026_06_26_141554_add_total_sesi_to_students_drop_pertemuan_ke_from_schedules', '1'),
  ('104', '2026_06_26_200000_create_rooms_table', '1'),
  ('105', '2026_06_26_210000_create_student_leaves_table', '1'),
  ('106', '2026_06_26_300000_create_curricula_table', '1'),
  ('107', '2026_06_26_400000_create_promos_table', '1'),
  ('108', '2026_06_28_100000_add_interest_sessions_to_student_registrations', '1'),
  ('109', '2026_06_28_110000_add_interest_teachers_to_student_registrations', '1'),
  ('110', '2026_06_29_044521_add_program_belajar_to_schedules_table', '1'),
  ('111', '2026_06_29_120000_add_freelance_fields_to_student_registrations', '1'),
  ('112', '2026_07_04_170952_create_branch_landing_settings_table', '1'),
  ('113', '2026_07_04_173915_create_landing_extra_content_tables', '1'),
  ('114', '2026_07_04_173916_add_image_fields_to_landing_and_branches_tables', '1'),
  ('115', '2026_07_13_000001_make_rooms_branch_id_nullable', '1'),
  ('116', '2026_07_13_221111_restore_pertemuan_ke_on_schedules', '1'),
  ('117', '2026_07_19_100000_create_articles_table', '1'),
  ('118', '2026_07_19_100001_create_teacher_registrations_table', '1'),
  ('119', '2026_07_21_000001_make_teacher_registration_email_nullable', '1');

-- ---- Tabel: `model_has_permissions` (0 baris) ----
-- (kosong)

-- ---- Tabel: `model_has_roles` (6 baris) ----
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
  ('1', 'App\\Models\\User', '1'),
  ('3', 'App\\Models\\User', '3'),
  ('3', 'App\\Models\\User', '4'),
  ('3', 'App\\Models\\User', '7'),
  ('4', 'App\\Models\\User', '5'),
  ('4', 'App\\Models\\User', '6');

-- ---- Tabel: `modules` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `modules` (`id`, `kode_modul`, `created_at`, `updated_at`, `mata_pelajaran_id`, `diupload_oleh`, `judul`, `deskripsi`, `jenis`, `file_path`, `file_url`, `ukuran_file`, `is_gratis`, `status`, `jumlah_download`, `deleted_at`) VALUES
  ('1', 'MOD-001', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '1', '3', 'Modul Aljabar Dasar', 'Materi aljabar dasar untuk kelas 10 SMA.', 'pdf', NULL, NULL, NULL, '1', 'aktif', '0', NULL),
  ('2', 'MOD-002', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '2', '4', 'Modul Grammar Dasar', 'Panduan grammar Bahasa Inggris untuk pemula.', 'pdf', NULL, NULL, NULL, '1', 'aktif', '0', NULL);

-- ---- Tabel: `moduls` (0 baris) ----
-- (kosong)

-- ---- Tabel: `nilais` (0 baris) ----
-- (kosong)

-- ---- Tabel: `package_course_teachers` (0 baris) ----
-- (kosong)

-- ---- Tabel: `packages` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `packages` (`id`, `created_at`, `updated_at`, `cabang_id`, `guru_id`, `nama`, `deskripsi`, `harga`, `durasi_bulan`, `jumlah_pertemuan`, `jenis`, `metode_absensi`, `tipe_kelas`, `fitur`, `is_unggulan`, `status`, `deleted_at`) VALUES
  ('1', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '1', NULL, 'Paket Reguler SMA', 'Paket reguler 8 pertemuan per bulan untuk siswa SMA.', '750000.00', '1', '8', 'reguler', 'dual', 'offline', '[\"8 pertemuan\\/bulan\",\"Modul digital\",\"Evaluasi bulanan\"]', '0', 'aktif', NULL),
  ('2', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '1', NULL, 'Paket Intensif SNBT', 'Program intensif 3 bulan persiapan SNBT dengan tryout rutin.', '2500000.00', '3', '36', 'intensif', 'dual', 'offline', '[\"36 pertemuan\",\"Tryout mingguan\",\"Mentor pribadi\"]', '1', 'aktif', NULL);

-- ---- Tabel: `pakets` (0 baris) ----
-- (kosong)

-- ---- Tabel: `password_resets` (0 baris) ----
-- (kosong)

-- ---- Tabel: `payments` (1 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `payments` (`id`, `created_at`, `updated_at`, `invoice_id`, `siswa_id`, `cabang_id`, `nomor_pembayaran`, `jumlah`, `metode`, `nama_bank`, `nomor_rekening`, `bukti_pembayaran`, `tanggal_pembayaran`, `status`, `alasan_penolakan`, `catatan`, `disetujui_oleh`, `tanggal_disetujui`, `deleted_at`) VALUES
  ('1', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '1', '1', '1', NULL, '750000.00', 'transfer', NULL, NULL, NULL, '2025-01-10', 'verified', NULL, NULL, NULL, NULL, NULL);

-- ---- Tabel: `pembayarans` (0 baris) ----
-- (kosong)

-- ---- Tabel: `permissions` (38 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
  ('1', 'branch.view', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('2', 'branch.create', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('3', 'branch.edit', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('4', 'branch.delete', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('5', 'student.view', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('6', 'student.create', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('7', 'student.edit', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('8', 'student.delete', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('9', 'teacher.view', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('10', 'teacher.create', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('11', 'teacher.edit', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('12', 'teacher.delete', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('13', 'employee.view', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('14', 'employee.create', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('15', 'employee.edit', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('16', 'employee.delete', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('17', 'schedule.view', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('18', 'schedule.create', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('19', 'schedule.edit', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('20', 'schedule.delete', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('21', 'payment.view', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('22', 'payment.create', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('23', 'payment.edit', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('24', 'payment.approve', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('25', 'tryout.view', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('26', 'tryout.create', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('27', 'tryout.edit', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('28', 'tryout.delete', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('29', 'report.view', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('30', 'report.export', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('31', 'setting.view', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('32', 'setting.edit', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('33', 'salary.view', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('34', 'salary.create', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('35', 'salary.edit', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('36', 'certificate.view', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('37', 'certificate.create', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('38', 'certificate.download', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05');

-- ---- Tabel: `personal_access_tokens` (0 baris) ----
-- (kosong)

-- ---- Tabel: `promos` (0 baris) ----
-- (kosong)

-- ---- Tabel: `questions` (0 baris) ----
-- (kosong)

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
  ('1', 'owner', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('2', 'admin', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('3', 'guru', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('4', 'siswa', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05'),
  ('5', 'karyawan', 'web', '2026-07-22 02:20:05', '2026-07-22 02:20:05');

-- ---- Tabel: `rooms` (0 baris) ----
-- (kosong)

-- ---- Tabel: `salaries` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `salaries` (`id`, `created_at`, `updated_at`, `guru_id`, `cabang_id`, `periode`, `tipe_gaji`, `gaji_pokok`, `jam_mengajar`, `tarif_per_jam`, `total_gaji_mengajar`, `bonus`, `potongan`, `total_gaji`, `metode_pembayaran`, `nama_bank`, `nomor_rekening`, `tanggal_pembayaran`, `status`, `catatan`, `bukti_pembayaran`, `dibayar_oleh`, `deleted_at`) VALUES
  ('1', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '1', '1', '2025-01', 'bulanan', '4500000.00', NULL, NULL, '0.00', '500000.00', '0.00', '5000000.00', NULL, NULL, NULL, '2025-01-28', 'dibayar', NULL, NULL, NULL, NULL),
  ('2', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '2', '2', '2025-01', 'bulanan', '4000000.00', NULL, NULL, '0.00', '0.00', '0.00', '4000000.00', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL);

-- ---- Tabel: `schedule_proposal_approvals` (0 baris) ----
-- (kosong)

-- ---- Tabel: `schedule_proposals` (0 baris) ----
-- (kosong)

-- ---- Tabel: `schedule_student_agreements` (0 baris) ----
-- (kosong)

-- ---- Tabel: `schedules` (0 baris) ----
-- (kosong)

-- ---- Tabel: `school_classes` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `school_classes` (`id`, `created_at`, `updated_at`, `cabang_id`, `mata_pelajaran_id`, `guru_id`, `tahun_akademik_id`, `nama`, `nama_kelas`, `kapasitas`, `jumlah_pertemuan`, `jenis`, `link_zoom`, `status`, `billing_mode`, `deleted_at`) VALUES
  ('1', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '1', '1', '1', '1', NULL, 'Matematika Reguler A', '15', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('2', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '2', '2', '2', '1', NULL, 'Bahasa Inggris Reguler A', '12', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL);

-- ---- Tabel: `semesters` (0 baris) ----
-- (kosong)

-- ---- Tabel: `siswas` (0 baris) ----
-- (kosong)

-- ---- Tabel: `student_course_payments` (0 baris) ----
-- (kosong)

-- ---- Tabel: `student_leaves` (0 baris) ----
-- (kosong)

-- ---- Tabel: `student_registrations` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `student_registrations` (`id`, `no_reg`, `name`, `phone`, `gender`, `education_level`, `birth_place`, `birth_date`, `address`, `parent_name`, `parent_phone`, `job`, `program`, `system`, `learning_place`, `pickup_mode`, `branch`, `interests`, `interest_sessions`, `interest_teachers`, `interest_teacher_honor`, `interest_teacher_sesi`, `day_preferences`, `schedule_time`, `start_date`, `notes`, `status`, `payment_status`, `academic_status`, `assigned_teacher_id`, `biaya_per_sesi`, `total_sessions`, `total_biaya`, `invoice_id`, `student_id`, `created_at`, `updated_at`) VALUES
  ('1', 'REG-2025-0001', 'Rizal Maulana', '081298765401', 'L', 'SMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cabang Jakarta', '[\"Matematika\",\"Fisika\"]', '{\"Matematika\":8,\"Fisika\":8}', NULL, NULL, NULL, NULL, NULL, NULL, 'Ingin mempersiapkan SNBT tahun depan.', 'pending', 'belum_bayar', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-22 02:20:09', '2026-07-22 02:20:09'),
  ('2', 'REG-2025-0002', 'Nadia Putri Utami', '082198765402', 'P', 'SMP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cabang Jakarta', '[\"Bahasa Inggris\"]', '{\"Bahasa Inggris\":8}', NULL, NULL, NULL, NULL, NULL, NULL, 'Perlu persiapan ujian sekolah.', 'verified', 'belum_bayar', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-22 02:20:09', '2026-07-22 02:20:09');

-- ---- Tabel: `student_teachers` (0 baris) ----
-- (kosong)

-- ---- Tabel: `students` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `students` (`id`, `created_at`, `updated_at`, `deleted_at`, `branch_id`, `package_id`, `total_sesi`, `user_id`, `nis`, `name`, `gender`, `birth_date`, `birth_place`, `address`, `phone`, `parent_name`, `parent_phone`, `photo`, `status`, `join_date`, `school_name`, `grade`, `kategori_peserta_didik`) VALUES
  ('1', '2026-07-22 02:20:08', '2026-07-22 02:20:08', NULL, '1', NULL, '0', '5', 'SIS-2025-001', 'Andi Nugroho', 'L', NULL, NULL, NULL, '081200000001', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('2', '2026-07-22 02:20:08', '2026-07-22 02:20:08', NULL, '1', NULL, '0', '6', 'SIS-2025-002', 'Citra Lestari', 'P', NULL, NULL, NULL, '081200000002', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL);

-- ---- Tabel: `system_settings` (0 baris) ----
-- (kosong)

-- ---- Tabel: `tagihans` (0 baris) ----
-- (kosong)

-- ---- Tabel: `tahun_ajarans` (0 baris) ----
-- (kosong)

-- ---- Tabel: `teacher_courses` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `teacher_courses` (`id`, `teacher_id`, `course_id`, `created_at`, `updated_at`) VALUES
  ('1', '1', '1', '2026-07-22 02:20:08', '2026-07-22 02:20:08'),
  ('2', '2', '2', '2026-07-22 02:20:08', '2026-07-22 02:20:08');

-- ---- Tabel: `teacher_registrations` (0 baris) ----
-- (kosong)

-- ---- Tabel: `teachers` (3 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `teachers` (`id`, `user_id`, `branch_id`, `nig`, `name`, `gender`, `birth_date`, `birth_place`, `address`, `phone`, `email`, `education`, `subjects`, `photo`, `cv_path`, `salary_base`, `join_date`, `status`, `jenis_guru`, `deleted_at`, `created_at`, `updated_at`) VALUES
  ('1', '3', '1', 'NIG-2024-001', 'Budi Santoso, S.Pd.', 'L', NULL, NULL, NULL, NULL, 'budi.santoso@guru.akademisci.com', NULL, '[\"Matematika\"]', NULL, NULL, '4500000.00', NULL, 'aktif', 'tetap', NULL, '2026-07-22 02:20:08', '2026-07-22 02:20:08'),
  ('2', '4', '2', 'NIG-2024-002', 'Sari Dewi, S.Pd.', 'P', NULL, NULL, NULL, NULL, 'sari.dewi@guru.akademisci.com', NULL, '[\"Bahasa Inggris\"]', NULL, NULL, '4000000.00', NULL, 'aktif', 'tetap', NULL, '2026-07-22 02:20:08', '2026-07-22 02:20:08'),
  ('3', '7', '1', 'NIG-2024-000', 'Ahmad Fauzi, S.Si.', 'L', NULL, NULL, NULL, NULL, 'gurusci@gmail.com', NULL, '[\"Matematika\"]', NULL, NULL, '4500000.00', NULL, 'aktif', 'tetap', NULL, '2026-07-22 02:20:09', '2026-07-22 02:20:09');

-- ---- Tabel: `tryout_attempts` (0 baris) ----
-- (kosong)

-- ---- Tabel: `tryouts` (2 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `tryouts` (`id`, `created_at`, `updated_at`, `cabang_id`, `dibuat_oleh`, `judul`, `deskripsi`, `kategori`, `durasi_menit`, `total_soal`, `nilai_kelulusan`, `waktu_mulai`, `waktu_selesai`, `is_random`, `tampilkan_hasil_langsung`, `tampilkan_kunci_jawaban`, `maksimal_percobaan`, `status`, `deleted_at`) VALUES
  ('1', '2026-07-22 02:20:08', '2026-07-22 02:20:08', '1', '1', 'Tryout SNBT Februari 2025', 'Simulasi SNBT dengan soal terkini.', 'SNBT', '120', '40', '60.00', '2025-02-15 08:00:00', '2025-02-15 10:00:00', '0', '1', '0', '1', 'aktif', NULL),
  ('2', '2026-07-22 02:20:09', '2026-07-22 02:20:09', '1', '1', 'Tryout Matematika Reguler', 'Latihan soal matematika untuk penilaian bulanan.', 'Reguler', '60', '20', '70.00', '2025-03-01 09:00:00', '2025-03-01 10:00:00', '0', '1', '1', '2', 'draft', NULL);

-- ---- Tabel: `users` (6 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `avatar`, `branch_id`, `is_active`, `last_login_at`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
  ('1', 'Admin Pusat SCI', NULL, 'adminpusatsci@akademi.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$/tLETCeT/sUAl9d5iChH0eAEGx0EG0gVsBQUM14GxvMlvVNaZXx/m', NULL, '2026-07-22 02:20:06', '2026-07-22 02:20:06', NULL),
  ('3', 'Budi Santoso, S.Pd.', NULL, 'budi.santoso@guru.akademisci.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$CTgSAKEDI9xjx35JwfuNDOJXWWsRWAaqsLCIMdiHUUxmtXioW9Mbi', NULL, '2026-07-22 02:20:08', '2026-07-22 02:20:08', NULL),
  ('4', 'Sari Dewi, S.Pd.', NULL, 'sari.dewi@guru.akademisci.com', NULL, NULL, '2', '1', NULL, NULL, '$2y$10$aemFKZHpo8/8ksr6au5KI.on.iwr/UUqCa/8MBtwdvMR15etEdS.C', NULL, '2026-07-22 02:20:08', '2026-07-22 02:20:08', NULL),
  ('5', 'Andi Nugroho', NULL, 'andi.nugroho@siswa.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$iBFFUVjox7d6j8ge3OPkcejwddodFcoNnF8nUJoCn.CX/Pmw9QlIi', NULL, '2026-07-22 02:20:08', '2026-07-22 02:20:08', NULL),
  ('6', 'Citra Lestari', NULL, 'citra.lestari@siswa.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$rpRzL14MJfYrruQgOYnNPOiQG78XDzyE9ScQq6rkPhqXQ2Dsc2rDK', NULL, '2026-07-22 02:20:08', '2026-07-22 02:20:08', NULL),
  ('7', 'Ahmad Fauzi, S.Si.', NULL, 'gurusci@gmail.com', NULL, NULL, '1', '1', NULL, NULL, '$2y$10$nNeFsX4JG4.rKsEVpmkCZ.nK74GEzX770Cj6zGw/Jo5t2pPJUev.y', NULL, '2026-07-22 02:20:09', '2026-07-22 02:20:09', NULL);

-- ===========================================================================
-- Akhir bagian DATA
-- ===========================================================================

SET FOREIGN_KEY_CHECKS = 1;
-- Selesai — 2026-07-21 19:21:30
