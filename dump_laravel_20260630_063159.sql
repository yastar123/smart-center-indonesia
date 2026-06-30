-- ===========================================================================
-- SQL DUMP — Migrasi + Seeder
-- Database  : laravel
-- Host      : 127.0.0.1:3306
-- Dibuat    : 2026-06-30 06:31:59
-- Tabel     : 73 tabel
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `jenis_kursus` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courses_cabang_id_index` (`cabang_id`),
  KEY `courses_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: `rooms`
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `nama_ruangan` varchar(100) NOT NULL,
  `kapasitas` int(10) unsigned NOT NULL DEFAULT 0,
  `status` enum('aktif','maintenance') NOT NULL DEFAULT 'aktif',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rooms_branch_id_foreign` (`branch_id`),
  CONSTRAINT `rooms_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- ---- Tabel: `academic_years` (3 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `academic_years` (`id`, `name`, `year_start`, `year_end`, `is_active`, `created_at`, `updated_at`) VALUES
  ('1', '2024/2025', '2024', '2025', '1', '2026-06-25 12:45:00', '2026-06-25 12:45:00'),
  ('2', '2023/2024', '2023', '2024', '0', '2026-06-25 12:45:00', '2026-06-25 12:45:00'),
  ('3', '2025/2026', '2025', '2026', '1', '2026-06-25 20:29:40', '2026-06-25 20:29:40');

-- ---- Tabel: `activity_log` (112 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
  ('10', 'default', 'created', 'App\\Models\\User', 'created', '10', NULL, NULL, '{\"attributes\":{\"id\":10,\"name\":\"Admin Pusat SCI\",\"username\":null,\"email\":\"adminpusatsci@akademi.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$RHzhELpBF2f1\\/VdG9YBMh.BjOtAcvTHTGooJFTVwelxbHTGFuorn.\",\"remember_token\":null,\"created_at\":\"2026-06-22T06:46:11.000000Z\",\"updated_at\":\"2026-06-22T06:46:11.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-22 13:46:11', '2026-06-22 13:46:11'),
  ('11', 'default', 'created', 'App\\Models\\User', 'created', '11', NULL, NULL, '{\"attributes\":{\"id\":11,\"name\":\"Admin Cabang SCI\",\"username\":null,\"email\":\"admincabangsci@akademi.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$yihYEYoEvNq\\/qw.nwEcMY.cHqLhiUrs\\/unLwwyVe5kbzTzXpz\\/qfu\",\"remember_token\":null,\"created_at\":\"2026-06-22T06:46:11.000000Z\",\"updated_at\":\"2026-06-22T06:46:11.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-22 13:46:11', '2026-06-22 13:46:11'),
  ('12', 'default', 'updated', 'App\\Models\\User', 'updated', '10', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$1063Z1KOHd.cELAw1sdqb.PhR9Tun5AqN7\\/\\/bWksdZhn8wrBK7mi6\"},\"old\":{\"password\":\"$2y$10$RHzhELpBF2f1\\/VdG9YBMh.BjOtAcvTHTGooJFTVwelxbHTGFuorn.\"}}', NULL, '2026-06-22 13:46:11', '2026-06-22 13:46:11'),
  ('13', 'default', 'created', 'App\\Models\\User', 'created', '12', 'App\\Models\\User', '10', '{\"attributes\":{\"id\":12,\"name\":\"admin\",\"username\":null,\"email\":\"admin@sci.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$W83GCi6A8O3Z235HfZ4fReoyDS3DqUE1Ug2.6aHOlmbhi\\/5dB5IC.\",\"remember_token\":null,\"created_at\":\"2026-06-22T06:47:02.000000Z\",\"updated_at\":\"2026-06-22T06:47:02.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-22 13:47:02', '2026-06-22 13:47:02'),
  ('14', 'default', 'updated', 'App\\Models\\User', 'updated', '12', 'App\\Models\\User', '10', '{\"attributes\":{\"branch_id\":2},\"old\":{\"branch_id\":null}}', NULL, '2026-06-22 13:47:02', '2026-06-22 13:47:02'),
  ('15', 'default', 'created', 'App\\Models\\User', 'created', '13', 'App\\Models\\User', '10', '{\"attributes\":{\"id\":13,\"name\":\"yastar iskandar\",\"username\":null,\"email\":\"guru@sci.com\",\"phone\":\"1\",\"avatar\":null,\"branch_id\":2,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$FzUg5LYO2vlgGQwlqshfnuFQCK81yAWVFWOubgdOtTUa\\/p2385A5u\",\"remember_token\":null,\"created_at\":\"2026-06-22T06:49:47.000000Z\",\"updated_at\":\"2026-06-22T06:49:47.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-22 13:49:47', '2026-06-22 13:49:47'),
  ('16', 'default', 'created', 'App\\Models\\User', 'created', '14', 'App\\Models\\User', '10', '{\"attributes\":{\"id\":14,\"name\":\"anton\",\"username\":null,\"email\":\"anton.1782111112@siswa.local\",\"phone\":\"1\",\"avatar\":null,\"branch_id\":2,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$0WFSHc48tYGCT0TCvnyzfur6MHS.UA.CdjbBxR.mjT1rdjaghXc7W\",\"remember_token\":null,\"created_at\":\"2026-06-22T06:51:52.000000Z\",\"updated_at\":\"2026-06-22T06:51:52.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-22 13:51:52', '2026-06-22 13:51:52'),
  ('17', 'default', 'updated', 'App\\Models\\User', 'updated', '14', 'App\\Models\\User', '10', '{\"attributes\":{\"email\":\"anton@sci.com\",\"password\":\"$2y$10$oM6BqFib\\/YW.5ow0uP3PEeKZHX4BBWJ.4LbBJwSaeSGasbTCNoI1.\",\"updated_at\":\"2026-06-22T06:52:18.000000Z\"},\"old\":{\"email\":\"anton.1782111112@siswa.local\",\"password\":\"$2y$10$0WFSHc48tYGCT0TCvnyzfur6MHS.UA.CdjbBxR.mjT1rdjaghXc7W\",\"updated_at\":\"2026-06-22T06:51:52.000000Z\"}}', NULL, '2026-06-22 13:52:18', '2026-06-22 13:52:18'),
  ('18', 'default', 'created', 'App\\Models\\User', 'created', '15', 'App\\Models\\User', '10', '{\"attributes\":{\"id\":15,\"name\":\"ahmad\",\"username\":null,\"email\":\"ahmad.1782111665@siswa.local\",\"phone\":\"1\",\"avatar\":null,\"branch_id\":2,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$j26SuS\\/JXRmF\\/.hJm4hoT.Q.VWofDIuJJhGj5p\\/ENrRs4YynvSpqO\",\"remember_token\":null,\"created_at\":\"2026-06-22T07:01:05.000000Z\",\"updated_at\":\"2026-06-22T07:01:05.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-22 14:01:05', '2026-06-22 14:01:05'),
  ('19', 'default', 'updated', 'App\\Models\\User', 'updated', '10', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$6gQYOa4UKklAPpeTMNBZBeOr09lEsK0XX01AY2FSdeE.uHmf1eSMq\",\"updated_at\":\"2026-06-25T05:45:00.000000Z\"},\"old\":{\"password\":\"$2y$10$1063Z1KOHd.cELAw1sdqb.PhR9Tun5AqN7\\/\\/bWksdZhn8wrBK7mi6\",\"updated_at\":\"2026-06-22T06:46:11.000000Z\"}}', NULL, '2026-06-25 12:45:00', '2026-06-25 12:45:00'),
  ('20', 'default', 'updated', 'App\\Models\\User', 'updated', '11', NULL, NULL, '{\"attributes\":{\"branch_id\":2,\"password\":\"$2y$10$Q6pBjpvpW\\/GHzdAD07jc4eIBuRYL\\/XRpNmuSDvD3F65.c3\\/EOeQDC\",\"updated_at\":\"2026-06-25T05:45:00.000000Z\"},\"old\":{\"branch_id\":null,\"password\":\"$2y$10$yihYEYoEvNq\\/qw.nwEcMY.cHqLhiUrs\\/unLwwyVe5kbzTzXpz\\/qfu\",\"updated_at\":\"2026-06-22T06:46:11.000000Z\"}}', NULL, '2026-06-25 12:45:00', '2026-06-25 12:45:00'),
  ('21', 'default', 'updated', 'App\\Models\\User', 'updated', '10', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$\\/4yoT5lCZpk4JY\\/XeWaCyuVH8JeaiNVgyDCRmDgvNJzmmP8ysjVS6\"},\"old\":{\"password\":\"$2y$10$6gQYOa4UKklAPpeTMNBZBeOr09lEsK0XX01AY2FSdeE.uHmf1eSMq\"}}', NULL, '2026-06-25 12:45:00', '2026-06-25 12:45:00'),
  ('22', 'default', 'created', 'App\\Models\\User', 'created', '16', NULL, NULL, '{\"attributes\":{\"id\":16,\"name\":\"Admin Pusat Jakarta\",\"username\":null,\"email\":\"adminpusat@akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$fVTskgLMJtB4PLfM3DILQO7D7NCCogHCgBmaf6LTJ7lfUrEc5sI9a\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:00.000000Z\",\"updated_at\":\"2026-06-25T05:45:00.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:00', '2026-06-25 12:45:00'),
  ('23', 'default', 'created', 'App\\Models\\User', 'created', '17', NULL, NULL, '{\"attributes\":{\"id\":17,\"name\":\"Admin Cabang Bandung\",\"username\":null,\"email\":\"adminbandung@akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":4,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$6xVz0cFi0ohywuncUBDIKOhZ2dDhomfR1FZ3Vn\\/TtobVNFPV1boQK\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:01.000000Z\",\"updated_at\":\"2026-06-25T05:45:01.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('24', 'default', 'created', 'App\\Models\\User', 'created', '18', NULL, NULL, '{\"attributes\":{\"id\":18,\"name\":\"Admin Cabang Surabaya\",\"username\":null,\"email\":\"adminsurabaya@akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":5,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$A3U3SLhNVWYl3OCBco7PzeGRvLTKzC4OSmwafJ3KeNJbNHPxz9jm6\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:01.000000Z\",\"updated_at\":\"2026-06-25T05:45:01.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('25', 'default', 'created', 'App\\Models\\User', 'created', '19', NULL, NULL, '{\"attributes\":{\"id\":19,\"name\":\"Budi Santoso, S.Pd.\",\"username\":null,\"email\":\"budi.santoso@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$3Uh1dje1JDJjgf2a6Ag0mOaK7pnfy7foTeHqAgtkJrPtUV780UldO\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:01.000000Z\",\"updated_at\":\"2026-06-25T05:45:01.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('26', 'default', 'created', 'App\\Models\\User', 'created', '20', NULL, NULL, '{\"attributes\":{\"id\":20,\"name\":\"Sari Dewi, M.Sc.\",\"username\":null,\"email\":\"sari.dewi@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$rxv\\/enua8rFhBL2QXReIQ.eWKcp.uVfFAwFJqAaKxBOuJtUSDhPka\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:01.000000Z\",\"updated_at\":\"2026-06-25T05:45:01.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('27', 'default', 'created', 'App\\Models\\User', 'created', '21', NULL, NULL, '{\"attributes\":{\"id\":21,\"name\":\"Rizky Pratama, S.Pd.\",\"username\":null,\"email\":\"rizky.pratama@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$h5DdYw3Fe6C2JPhoGwTUZelFmMNgYS78zJNnAu4CatoqmCkYIYKAu\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:01.000000Z\",\"updated_at\":\"2026-06-25T05:45:01.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('28', 'default', 'created', 'App\\Models\\User', 'created', '22', NULL, NULL, '{\"attributes\":{\"id\":22,\"name\":\"Ahmad Fauzi, S.Si.\",\"username\":null,\"email\":\"gurusci@gmail.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$51ZN1Tagcz7bNUg\\/n6M5d.5hzkCUUERIR.y6EKAqSkVyKCM5nNQ4i\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:02.000000Z\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('29', 'default', 'created', 'App\\Models\\User', 'created', '23', NULL, NULL, '{\"attributes\":{\"id\":23,\"name\":\"Hani Rahayu, S.Pd.\",\"username\":null,\"email\":\"hani.rahayu@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":4,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$IQp3bJ4waY6uCS3CT8urJuEvGwTfNYTwtnTepA6qcjmGTtbpOPkPe\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:02.000000Z\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('30', 'default', 'created', 'App\\Models\\User', 'created', '24', NULL, NULL, '{\"attributes\":{\"id\":24,\"name\":\"Dimas Arya, S.Pd.\",\"username\":null,\"email\":\"dimas.arya@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":4,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$IR2DYKQgU25u69Ed8MV4dO7RJHrlqKng09NVaVgsizOzFZU5AN6sm\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:02.000000Z\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('31', 'default', 'created', 'App\\Models\\User', 'created', '25', NULL, NULL, '{\"attributes\":{\"id\":25,\"name\":\"Yuni Kartika, M.Pd.\",\"username\":null,\"email\":\"yuni.kartika@guru.akademibimbel.com\",\"phone\":null,\"avatar\":null,\"branch_id\":5,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$h.b8jlUleZlL0EEBFQ.fjOUWFR0slpYMoHVBo9YyqBmwUdg\\/5QcbW\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:02.000000Z\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('32', 'default', 'created', 'App\\Models\\User', 'created', '26', NULL, NULL, '{\"attributes\":{\"id\":26,\"name\":\"Andi Nugroho\",\"username\":null,\"email\":\"andi.nugroho@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$fohTAh6vl.jGHt7Gt0EHMOtTjlOe0uw0cKlqLRJGxzd.9fWOOpQQu\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:02.000000Z\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('33', 'default', 'created', 'App\\Models\\User', 'created', '27', NULL, NULL, '{\"attributes\":{\"id\":27,\"name\":\"Citra Lestari\",\"username\":null,\"email\":\"citra.lestari@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$GP1MFoIGlpSVQjNJNg3M2uHhyS9p0pAeD7MFXO7vy.yzx\\/FbRci3K\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:02.000000Z\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:03', '2026-06-25 12:45:03'),
  ('34', 'default', 'created', 'App\\Models\\User', 'created', '28', NULL, NULL, '{\"attributes\":{\"id\":28,\"name\":\"Fajar Hidayat\",\"username\":null,\"email\":\"fajar.hidayat@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$5L\\/i0.GWx4F8yfzmH15MueNF4PHvHC4l7AXBi.MvJYnzKq0EsVm8O\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:03.000000Z\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:03', '2026-06-25 12:45:03'),
  ('35', 'default', 'created', 'App\\Models\\User', 'created', '29', NULL, NULL, '{\"attributes\":{\"id\":29,\"name\":\"Gita Permata\",\"username\":null,\"email\":\"gita.permata@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$CUlrsjahBp\\/qtRuldn2VyemxKAqdjNmhbT1KYd2EZgeFjmU25wmKu\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:03.000000Z\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:03', '2026-06-25 12:45:03'),
  ('36', 'default', 'created', 'App\\Models\\User', 'created', '30', NULL, NULL, '{\"attributes\":{\"id\":30,\"name\":\"Hendra Putra\",\"username\":null,\"email\":\"hendra.putra@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$my1RVSeFXbXS6YATxyzFbubtfx6F8Nn5h95.JuNrzt5YDQvSsEYpC\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:03.000000Z\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:03', '2026-06-25 12:45:03'),
  ('37', 'default', 'created', 'App\\Models\\User', 'created', '31', NULL, NULL, '{\"attributes\":{\"id\":31,\"name\":\"Indah Sari\",\"username\":null,\"email\":\"indah.sari@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$bcqERS00i0h2GrgjRpeXqOB76tl.vECdvY42OTTz6HM5J4gUyd4HC\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:03.000000Z\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:03', '2026-06-25 12:45:03'),
  ('38', 'default', 'created', 'App\\Models\\User', 'created', '32', NULL, NULL, '{\"attributes\":{\"id\":32,\"name\":\"Joko Santoso\",\"username\":null,\"email\":\"joko.santoso@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$gZPSCFlY8\\/rVVIv\\/Y7hFgetkFXP6H1gRiNd2haFhAW6TJk336HcHy\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:03.000000Z\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:03', '2026-06-25 12:45:03'),
  ('39', 'default', 'created', 'App\\Models\\User', 'created', '33', NULL, NULL, '{\"attributes\":{\"id\":33,\"name\":\"Kartini Wulandari\",\"username\":null,\"email\":\"kartini.wulandari@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":3,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$eF8x3RoS\\/.kg66g03aiPVeQPkn3mmApIAl6htnaXAd1T5TNccuWLi\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:03.000000Z\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:03', '2026-06-25 12:45:03'),
  ('40', 'default', 'created', 'App\\Models\\User', 'created', '34', NULL, NULL, '{\"attributes\":{\"id\":34,\"name\":\"Luthfi Rahman\",\"username\":null,\"email\":\"luthfi.rahman@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":4,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$KjijpXQ6XrkU0Z5lPOjsROmmRPq.JJzUEDWAayIHrcayZ7hFgqVeO\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:04.000000Z\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:04', '2026-06-25 12:45:04'),
  ('41', 'default', 'created', 'App\\Models\\User', 'created', '35', NULL, NULL, '{\"attributes\":{\"id\":35,\"name\":\"Mira Kusuma\",\"username\":null,\"email\":\"mira.kusuma@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":4,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$TgkSROwfFvjYXbvPgFUEQuH4vbFv4iNX1RAY3zkYVx52bORDadC\\/a\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:04.000000Z\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:04', '2026-06-25 12:45:04'),
  ('42', 'default', 'created', 'App\\Models\\User', 'created', '36', NULL, NULL, '{\"attributes\":{\"id\":36,\"name\":\"Naufal Ardiansyah\",\"username\":null,\"email\":\"naufal.ardiansyah@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":4,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$haTx9GA3887Uzjvk14W\\/j.OYOhJEGxqnolPvtpL\\/tIkqi4zdB9K4W\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:04.000000Z\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:04', '2026-06-25 12:45:04'),
  ('43', 'default', 'created', 'App\\Models\\User', 'created', '37', NULL, NULL, '{\"attributes\":{\"id\":37,\"name\":\"Olivia Putri\",\"username\":null,\"email\":\"olivia.putri@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":4,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$VOL6KQbiiWJUxu9QQXt4seGKOU6m.7IOy\\/fOCQAnEbjA1FCkU092G\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:04.000000Z\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:04', '2026-06-25 12:45:04'),
  ('44', 'default', 'created', 'App\\Models\\User', 'created', '38', NULL, NULL, '{\"attributes\":{\"id\":38,\"name\":\"Prasetyo Adi\",\"username\":null,\"email\":\"prasetyo.adi@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":5,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$yh\\/XW1Z7bUQiIsHZimI0T.r\\/FUiLhfbCbAJ4193X9S4KNnyePQ9AW\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:04.000000Z\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:04', '2026-06-25 12:45:04'),
  ('45', 'default', 'created', 'App\\Models\\User', 'created', '39', NULL, NULL, '{\"attributes\":{\"id\":39,\"name\":\"Rini Agustina\",\"username\":null,\"email\":\"rini.agustina@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":5,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$jg1wBq\\/laqr66sJ2hgWrtOljKix1Nw3aBInrEVrMWDh6M1BVyptFm\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:04.000000Z\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:04', '2026-06-25 12:45:04'),
  ('46', 'default', 'created', 'App\\Models\\User', 'created', '40', NULL, NULL, '{\"attributes\":{\"id\":40,\"name\":\"Sandi Kurniawan\",\"username\":null,\"email\":\"sandi.kurniawan@siswa.com\",\"phone\":null,\"avatar\":null,\"branch_id\":5,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$8aL\\/ZE3KDyCTnctNsoY4wONMqyw1ew0YoXLGPKUsZtpidCw2DePdG\",\"remember_token\":null,\"created_at\":\"2026-06-25T05:45:04.000000Z\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 12:45:04', '2026-06-25 12:45:04'),
  ('47', 'default', 'updated', 'App\\Models\\User', 'updated', '10', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$kMQdXAFxrCOt5kWOjUhq\\/eXZoEbtVtnq3LHaRsPykypP0k6Ip\\/q8S\",\"updated_at\":\"2026-06-25T13:29:36.000000Z\"},\"old\":{\"password\":\"$2y$10$\\/4yoT5lCZpk4JY\\/XeWaCyuVH8JeaiNVgyDCRmDgvNJzmmP8ysjVS6\",\"updated_at\":\"2026-06-25T05:45:00.000000Z\"}}', NULL, '2026-06-25 20:29:36', '2026-06-25 20:29:36'),
  ('48', 'default', 'updated', 'App\\Models\\User', 'updated', '11', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$aC2kn04mGvHoBCDlGPgGJOq8K\\/S4hDTZb7Cn7mPtCTSmTdJXOIfN.\",\"updated_at\":\"2026-06-25T13:29:36.000000Z\"},\"old\":{\"password\":\"$2y$10$Q6pBjpvpW\\/GHzdAD07jc4eIBuRYL\\/XRpNmuSDvD3F65.c3\\/EOeQDC\",\"updated_at\":\"2026-06-25T05:45:00.000000Z\"}}', NULL, '2026-06-25 20:29:36', '2026-06-25 20:29:36'),
  ('49', 'default', 'updated', 'App\\Models\\User', 'updated', '10', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$mt.9QefRq7wP.1PDjDi5EOGMfRgtp4UbPno\\/09.fSht.OSpAHXe..\"},\"old\":{\"password\":\"$2y$10$kMQdXAFxrCOt5kWOjUhq\\/eXZoEbtVtnq3LHaRsPykypP0k6Ip\\/q8S\"}}', NULL, '2026-06-25 20:29:36', '2026-06-25 20:29:36'),
  ('50', 'default', 'updated', 'App\\Models\\User', 'updated', '16', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$5J6CN\\/TEWQE1WWhl0yHADOl86lt\\/ILcgdI4pWznU1E2dKgB8kx\\/aW\",\"updated_at\":\"2026-06-25T13:29:36.000000Z\"},\"old\":{\"password\":\"$2y$10$fVTskgLMJtB4PLfM3DILQO7D7NCCogHCgBmaf6LTJ7lfUrEc5sI9a\",\"updated_at\":\"2026-06-25T05:45:00.000000Z\"}}', NULL, '2026-06-25 20:29:36', '2026-06-25 20:29:36'),
  ('51', 'default', 'updated', 'App\\Models\\User', 'updated', '17', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$ExXH82qQS7.ejQK5kwcYOurhYnVDmRiJHfW7CJR0tibRuGHTT1tlO\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"},\"old\":{\"password\":\"$2y$10$6xVz0cFi0ohywuncUBDIKOhZ2dDhomfR1FZ3Vn\\/TtobVNFPV1boQK\",\"updated_at\":\"2026-06-25T05:45:01.000000Z\"}}', NULL, '2026-06-25 20:29:37', '2026-06-25 20:29:37'),
  ('52', 'default', 'updated', 'App\\Models\\User', 'updated', '18', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$H\\/F5KnQFsqOMjR8u1PoBbeF2w5bDeJFEmcshJADRCweya04nop1Gm\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"},\"old\":{\"password\":\"$2y$10$A3U3SLhNVWYl3OCBco7PzeGRvLTKzC4OSmwafJ3KeNJbNHPxz9jm6\",\"updated_at\":\"2026-06-25T05:45:01.000000Z\"}}', NULL, '2026-06-25 20:29:37', '2026-06-25 20:29:37'),
  ('53', 'default', 'updated', 'App\\Models\\User', 'updated', '19', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$BIErCSzYQri0kuKnWS0tPef5D816jCJ9s8ZuHEB6UWvZdGTWN\\/PPW\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"},\"old\":{\"password\":\"$2y$10$3Uh1dje1JDJjgf2a6Ag0mOaK7pnfy7foTeHqAgtkJrPtUV780UldO\",\"updated_at\":\"2026-06-25T05:45:01.000000Z\"}}', NULL, '2026-06-25 20:29:37', '2026-06-25 20:29:37'),
  ('54', 'default', 'updated', 'App\\Models\\User', 'updated', '20', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$sdRU7\\/1\\/4MEFTq97c0nXeujPiWKkWDyFCayUUVxpH1500WHVKthga\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"},\"old\":{\"password\":\"$2y$10$rxv\\/enua8rFhBL2QXReIQ.eWKcp.uVfFAwFJqAaKxBOuJtUSDhPka\",\"updated_at\":\"2026-06-25T05:45:01.000000Z\"}}', NULL, '2026-06-25 20:29:37', '2026-06-25 20:29:37'),
  ('55', 'default', 'updated', 'App\\Models\\User', 'updated', '21', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$vhX983usHwmcA3DI6P1lEurUkt05dKuDCekmTBkESfLOMxcl1\\/yhe\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"},\"old\":{\"password\":\"$2y$10$h5DdYw3Fe6C2JPhoGwTUZelFmMNgYS78zJNnAu4CatoqmCkYIYKAu\",\"updated_at\":\"2026-06-25T05:45:01.000000Z\"}}', NULL, '2026-06-25 20:29:37', '2026-06-25 20:29:37'),
  ('56', 'default', 'updated', 'App\\Models\\User', 'updated', '22', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$WQ69nNzv\\/\\/dZhPh\\/SmAGGuKQUusjYRNjOSseiftJIhDKfYrmNThG.\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"},\"old\":{\"password\":\"$2y$10$51ZN1Tagcz7bNUg\\/n6M5d.5hzkCUUERIR.y6EKAqSkVyKCM5nNQ4i\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\"}}', NULL, '2026-06-25 20:29:37', '2026-06-25 20:29:37'),
  ('57', 'default', 'updated', 'App\\Models\\User', 'updated', '23', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$XqPlh4AX17bwoPHKR8NitO\\/xITioyMOxJFn0mcVWx984sTiPCdJIG\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"},\"old\":{\"password\":\"$2y$10$IQp3bJ4waY6uCS3CT8urJuEvGwTfNYTwtnTepA6qcjmGTtbpOPkPe\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\"}}', NULL, '2026-06-25 20:29:37', '2026-06-25 20:29:37'),
  ('58', 'default', 'updated', 'App\\Models\\User', 'updated', '24', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$aeu04M7ZzSv.g\\/l7xh5XZ.whtoXZGR.q0fIuJMhj3uuANjzz.sa.a\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"},\"old\":{\"password\":\"$2y$10$IR2DYKQgU25u69Ed8MV4dO7RJHrlqKng09NVaVgsizOzFZU5AN6sm\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\"}}', NULL, '2026-06-25 20:29:38', '2026-06-25 20:29:38'),
  ('59', 'default', 'updated', 'App\\Models\\User', 'updated', '25', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$U0SouiH0zt\\/IfiUuDSFcdOs5nnyAN4kGQyG5B.YERZdvi31mNtXUy\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"},\"old\":{\"password\":\"$2y$10$h.b8jlUleZlL0EEBFQ.fjOUWFR0slpYMoHVBo9YyqBmwUdg\\/5QcbW\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\"}}', NULL, '2026-06-25 20:29:38', '2026-06-25 20:29:38'),
  ('60', 'default', 'updated', 'App\\Models\\User', 'updated', '26', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$UDGvykxmatuu054aduvjxO5xCEZMsxmGKF1MW0tACg.M2FMtwtJ3G\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"},\"old\":{\"password\":\"$2y$10$fohTAh6vl.jGHt7Gt0EHMOtTjlOe0uw0cKlqLRJGxzd.9fWOOpQQu\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\"}}', NULL, '2026-06-25 20:29:38', '2026-06-25 20:29:38'),
  ('61', 'default', 'updated', 'App\\Models\\User', 'updated', '27', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$Q.IaBITmB2X3KLD0p4L07eGz6Z11.4nqUa0h2XAPQJly5GSVRUFHq\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"},\"old\":{\"password\":\"$2y$10$GP1MFoIGlpSVQjNJNg3M2uHhyS9p0pAeD7MFXO7vy.yzx\\/FbRci3K\",\"updated_at\":\"2026-06-25T05:45:02.000000Z\"}}', NULL, '2026-06-25 20:29:38', '2026-06-25 20:29:38'),
  ('62', 'default', 'updated', 'App\\Models\\User', 'updated', '28', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$5VZ35kHtWeMW0GIllCjdBOX0gvQRgU1CewljhVhJhpRKZanLPd..C\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"},\"old\":{\"password\":\"$2y$10$5L\\/i0.GWx4F8yfzmH15MueNF4PHvHC4l7AXBi.MvJYnzKq0EsVm8O\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\"}}', NULL, '2026-06-25 20:29:38', '2026-06-25 20:29:38'),
  ('63', 'default', 'updated', 'App\\Models\\User', 'updated', '29', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$pNZKHUI4vIoLR51UIQW2zeD6UHRtyuWAH\\/uGsCFNum7AF.e86YXC.\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"},\"old\":{\"password\":\"$2y$10$CUlrsjahBp\\/qtRuldn2VyemxKAqdjNmhbT1KYd2EZgeFjmU25wmKu\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\"}}', NULL, '2026-06-25 20:29:38', '2026-06-25 20:29:38'),
  ('64', 'default', 'updated', 'App\\Models\\User', 'updated', '30', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$H9OoA9J4.YUEb2g6VNiMQO\\/pyr5fhRHFnRrQ3xKCtAeYhyCCUGCKy\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"},\"old\":{\"password\":\"$2y$10$my1RVSeFXbXS6YATxyzFbubtfx6F8Nn5h95.JuNrzt5YDQvSsEYpC\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\"}}', NULL, '2026-06-25 20:29:38', '2026-06-25 20:29:38'),
  ('65', 'default', 'updated', 'App\\Models\\User', 'updated', '31', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$CJoP\\/RJoLR\\/dRY\\/2sgRKuetfMYzkB5ITmp8H0tifvWzGXWxPFDJbO\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"},\"old\":{\"password\":\"$2y$10$bcqERS00i0h2GrgjRpeXqOB76tl.vECdvY42OTTz6HM5J4gUyd4HC\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\"}}', NULL, '2026-06-25 20:29:38', '2026-06-25 20:29:38'),
  ('66', 'default', 'updated', 'App\\Models\\User', 'updated', '32', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$vx8wKY4ZC4sH95S3KqeLI.hyyWK0HH7GlZqy0ngwaNcnn.W34TVgS\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"},\"old\":{\"password\":\"$2y$10$gZPSCFlY8\\/rVVIv\\/Y7hFgetkFXP6H1gRiNd2haFhAW6TJk336HcHy\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\"}}', NULL, '2026-06-25 20:29:39', '2026-06-25 20:29:39'),
  ('67', 'default', 'updated', 'App\\Models\\User', 'updated', '33', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$qBJ.B89KOpc\\/iApS354IielRBf2R1mNJAMrbGkzaa2x9NLUlfP.J.\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"},\"old\":{\"password\":\"$2y$10$eF8x3RoS\\/.kg66g03aiPVeQPkn3mmApIAl6htnaXAd1T5TNccuWLi\",\"updated_at\":\"2026-06-25T05:45:03.000000Z\"}}', NULL, '2026-06-25 20:29:39', '2026-06-25 20:29:39'),
  ('68', 'default', 'updated', 'App\\Models\\User', 'updated', '34', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$2DWjFxtGLCSaaKCGfydJh.U8I3dNEdF7cPlhrYQg7xIkLrl3SQfVC\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"},\"old\":{\"password\":\"$2y$10$KjijpXQ6XrkU0Z5lPOjsROmmRPq.JJzUEDWAayIHrcayZ7hFgqVeO\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\"}}', NULL, '2026-06-25 20:29:39', '2026-06-25 20:29:39'),
  ('69', 'default', 'updated', 'App\\Models\\User', 'updated', '35', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$9nh3vLMgu0fVJ2.EdJv7Nuvrdr6cWoKyq1iXKP\\/CxShKwQ4AHAVpC\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"},\"old\":{\"password\":\"$2y$10$TgkSROwfFvjYXbvPgFUEQuH4vbFv4iNX1RAY3zkYVx52bORDadC\\/a\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\"}}', NULL, '2026-06-25 20:29:39', '2026-06-25 20:29:39'),
  ('70', 'default', 'updated', 'App\\Models\\User', 'updated', '36', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$EvvQclEx\\/Jd0aE\\/Eo3aY9OAs54htlwQ3Sz9QKJtu41ZvHYBNAJa\\/6\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"},\"old\":{\"password\":\"$2y$10$haTx9GA3887Uzjvk14W\\/j.OYOhJEGxqnolPvtpL\\/tIkqi4zdB9K4W\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\"}}', NULL, '2026-06-25 20:29:39', '2026-06-25 20:29:39'),
  ('71', 'default', 'updated', 'App\\Models\\User', 'updated', '37', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$5TbBR72aEur..B58N8AbROXCSKV1U69PL3AjX37pPCskCJ.d2Jw5W\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"},\"old\":{\"password\":\"$2y$10$VOL6KQbiiWJUxu9QQXt4seGKOU6m.7IOy\\/fOCQAnEbjA1FCkU092G\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\"}}', NULL, '2026-06-25 20:29:39', '2026-06-25 20:29:39'),
  ('72', 'default', 'updated', 'App\\Models\\User', 'updated', '38', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$C5aol.2hocsc7o5yICLoA.BJNsr09p0ty4raDfs239NiqCJf\\/j4Hu\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"},\"old\":{\"password\":\"$2y$10$yh\\/XW1Z7bUQiIsHZimI0T.r\\/FUiLhfbCbAJ4193X9S4KNnyePQ9AW\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\"}}', NULL, '2026-06-25 20:29:39', '2026-06-25 20:29:39'),
  ('73', 'default', 'updated', 'App\\Models\\User', 'updated', '39', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$HNRFFEDZ88rk\\/LV7kOx15e3NVV6Oa.LAtnQwOUvM34k418W2W1FTO\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"},\"old\":{\"password\":\"$2y$10$jg1wBq\\/laqr66sJ2hgWrtOljKix1Nw3aBInrEVrMWDh6M1BVyptFm\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\"}}', NULL, '2026-06-25 20:29:39', '2026-06-25 20:29:39'),
  ('74', 'default', 'updated', 'App\\Models\\User', 'updated', '40', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$Orf0FoXq5zyXmFFOTH13xOvU47hquvvt.nwW7r\\/0NlISocxqvoBsW\",\"updated_at\":\"2026-06-25T13:29:40.000000Z\"},\"old\":{\"password\":\"$2y$10$8aL\\/ZE3KDyCTnctNsoY4wONMqyw1ew0YoXLGPKUsZtpidCw2DePdG\",\"updated_at\":\"2026-06-25T05:45:04.000000Z\"}}', NULL, '2026-06-25 20:29:40', '2026-06-25 20:29:40'),
  ('75', 'default', 'created', 'App\\Models\\User', 'created', '41', NULL, NULL, '{\"attributes\":{\"id\":41,\"name\":\"Andi Prasetyo, S.Pd.\",\"username\":null,\"email\":\"andi.prasetyo@akademisci.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$jMf7mmilBZlib1\\/NnmO.eOO9gSVzR3QOkzB2ATNnBrhDJU\\/C29Miu\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:40.000000Z\",\"updated_at\":\"2026-06-25T13:29:40.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:40', '2026-06-25 20:29:40'),
  ('76', 'default', 'created', 'App\\Models\\User', 'created', '42', NULL, NULL, '{\"attributes\":{\"id\":42,\"name\":\"Sari Dewi, S.Pd.\",\"username\":null,\"email\":\"sari.dewi@akademisci.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$iyf1zkUW42tnmO1zQ5xm7evcgFO6\\/0O7Jm2rHOjc\\/nI6vNbhzfkkG\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:40.000000Z\",\"updated_at\":\"2026-06-25T13:29:40.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:40', '2026-06-25 20:29:40'),
  ('77', 'default', 'created', 'App\\Models\\User', 'created', '43', NULL, NULL, '{\"attributes\":{\"id\":43,\"name\":\"Rizky Ananta, M.Pd.\",\"username\":null,\"email\":\"rizky.ananta@akademisci.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$FJ8nv7NJ4ix8MzpFzQ9pReg0ctkIvyPFgYd0gxObePTLWHtrkpvqe\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:40.000000Z\",\"updated_at\":\"2026-06-25T13:29:40.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:40', '2026-06-25 20:29:40'),
  ('78', 'default', 'created', 'App\\Models\\User', 'created', '44', NULL, NULL, '{\"attributes\":{\"id\":44,\"name\":\"Nurul Hidayah, S.Pd.\",\"username\":null,\"email\":\"nurul.hidayah@akademisci.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$D.OwC2k4ZpHjbhBPKxLrlesOcebt80u4KOLw.WjJIhVmoIX3SICO2\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:41.000000Z\",\"updated_at\":\"2026-06-25T13:29:41.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('79', 'default', 'created', 'App\\Models\\User', 'created', '45', NULL, NULL, '{\"attributes\":{\"id\":45,\"name\":\"Hendra Wijaya, S.E., M.M.\",\"username\":null,\"email\":\"hendra.wijaya@akademisci.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$VREZRfgNg.DPc2nq83kIPOJwxzcd8NewRW88s8tLUUXVPzx\\/w7h36\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:41.000000Z\",\"updated_at\":\"2026-06-25T13:29:41.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('80', 'default', 'created', 'App\\Models\\User', 'created', '46', NULL, NULL, '{\"attributes\":{\"id\":46,\"name\":\"Fitri Lestari, S.Pd.\",\"username\":null,\"email\":\"fitri.lestari@akademisci.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$3ndhyozdU0jtLMG1Op38KeFoyyCZ1YIY4ZO6UZ4yK9JJgBFX2nShG\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:41.000000Z\",\"updated_at\":\"2026-06-25T13:29:41.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('81', 'default', 'created', 'App\\Models\\User', 'created', '47', NULL, NULL, '{\"attributes\":{\"id\":47,\"name\":\"Dimas Arief, S.Pd.\",\"username\":null,\"email\":\"dimas.arief@akademisci.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$8.XyKj\\/X8RiDC3mqDUrALeZj97IzxHBDIsOE29WbWDNQ3T5hVsM8O\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:41.000000Z\",\"updated_at\":\"2026-06-25T13:29:41.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('82', 'default', 'created', 'App\\Models\\User', 'created', '48', NULL, NULL, '{\"attributes\":{\"id\":48,\"name\":\"Bintang Samudera\",\"username\":null,\"email\":\"bintang@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$wqT3tC0D1DbQ4nVLHzlvg.krQFqmg252pPifZ.tymw\\/RoORYE6b9u\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:41.000000Z\",\"updated_at\":\"2026-06-25T13:29:41.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('83', 'default', 'created', 'App\\Models\\User', 'created', '49', NULL, NULL, '{\"attributes\":{\"id\":49,\"name\":\"Cahaya Bulan\",\"username\":null,\"email\":\"cahaya@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$SkI1Lz\\/b1NGMhRTjW9njwemGvlJGUXf8M3Q7DGzYyTE2S34XkLLQW\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:42.000000Z\",\"updated_at\":\"2026-06-25T13:29:42.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42'),
  ('84', 'default', 'created', 'App\\Models\\User', 'created', '50', NULL, NULL, '{\"attributes\":{\"id\":50,\"name\":\"Darmawan Putra\",\"username\":null,\"email\":\"darmawan@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$xbDSW8UE2OF3Gtg3LlwkwebE4KcVEOlfYmJ6pBWtH21z7S\\/pudtsO\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:42.000000Z\",\"updated_at\":\"2026-06-25T13:29:42.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42'),
  ('85', 'default', 'created', 'App\\Models\\User', 'created', '51', NULL, NULL, '{\"attributes\":{\"id\":51,\"name\":\"Elisa Ramadhani\",\"username\":null,\"email\":\"elisa@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$elCmliul3.qfSW6r9BJ.g.l4JyhyLXPIl.uzPvjbbHpMH.Y\\/3x4sy\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:42.000000Z\",\"updated_at\":\"2026-06-25T13:29:42.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42'),
  ('86', 'default', 'created', 'App\\Models\\User', 'created', '52', NULL, NULL, '{\"attributes\":{\"id\":52,\"name\":\"Fajar Nugroho\",\"username\":null,\"email\":\"fajar@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$eEasaD3yky1wRWrlzUkku.pQQ8KePPiBUxJii2RDT1YYWqfcG.kOi\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:42.000000Z\",\"updated_at\":\"2026-06-25T13:29:42.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42'),
  ('87', 'default', 'created', 'App\\Models\\User', 'created', '53', NULL, NULL, '{\"attributes\":{\"id\":53,\"name\":\"Gita Permatasari\",\"username\":null,\"email\":\"gita@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$KhyhNwPSYWZB8wFkvcejsu6\\/2l.Ru1g9gAQyXVLk.PabrCPhI6uB6\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:42.000000Z\",\"updated_at\":\"2026-06-25T13:29:42.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42'),
  ('88', 'default', 'created', 'App\\Models\\User', 'created', '54', NULL, NULL, '{\"attributes\":{\"id\":54,\"name\":\"Hafiz Ramadhan\",\"username\":null,\"email\":\"hafiz@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$ysEBP4tYWytuYsybU4D7aOCRcmNC7FRdGYT36DcQhrgiDyC.GvHJW\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:42.000000Z\",\"updated_at\":\"2026-06-25T13:29:42.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42'),
  ('89', 'default', 'created', 'App\\Models\\User', 'created', '55', NULL, NULL, '{\"attributes\":{\"id\":55,\"name\":\"Intan Sari\",\"username\":null,\"email\":\"intan@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$kL1aYowPxziVKZ3jDVBkJ.THoACSRyauaiV32mTXD5ZrB2z6YPeTO\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:42.000000Z\",\"updated_at\":\"2026-06-25T13:29:42.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42'),
  ('90', 'default', 'created', 'App\\Models\\User', 'created', '56', NULL, NULL, '{\"attributes\":{\"id\":56,\"name\":\"Joko Santoso\",\"username\":null,\"email\":\"joko@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$athkhFYb6Xl8NrS1TqlhfeYMZFVcb6YyhkGbai.9i4qV2yx9feztu\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:43.000000Z\",\"updated_at\":\"2026-06-25T13:29:43.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:43', '2026-06-25 20:29:43'),
  ('91', 'default', 'created', 'App\\Models\\User', 'created', '57', NULL, NULL, '{\"attributes\":{\"id\":57,\"name\":\"Kania Maharani\",\"username\":null,\"email\":\"kania@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$DWSQTnxtLQ1OZO9Gu2i2F.CvGZ7S\\/k9HFLzXYs6DjzlWYDMXJVDKi\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:43.000000Z\",\"updated_at\":\"2026-06-25T13:29:43.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:43', '2026-06-25 20:29:43'),
  ('92', 'default', 'created', 'App\\Models\\User', 'created', '58', NULL, NULL, '{\"attributes\":{\"id\":58,\"name\":\"Lukman Hakim\",\"username\":null,\"email\":\"lukman@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$GPpP412WxdGehzXhFD2Efus\\/eEJ8ulFMjjtQvzDLoDuv9BZsY1Mv6\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:43.000000Z\",\"updated_at\":\"2026-06-25T13:29:43.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:43', '2026-06-25 20:29:43'),
  ('93', 'default', 'created', 'App\\Models\\User', 'created', '59', NULL, NULL, '{\"attributes\":{\"id\":59,\"name\":\"Maya Sari\",\"username\":null,\"email\":\"maya@student.com\",\"phone\":null,\"avatar\":null,\"branch_id\":null,\"is_active\":true,\"last_login_at\":null,\"email_verified_at\":null,\"password\":\"$2y$10$mJe2r4OrVZG3T0ZCqb0OTOmh8YpJbAS.YTGmJ33prkU79dI1DBHZS\",\"remember_token\":null,\"created_at\":\"2026-06-25T13:29:43.000000Z\",\"updated_at\":\"2026-06-25T13:29:43.000000Z\",\"deleted_at\":null}}', NULL, '2026-06-25 20:29:43', '2026-06-25 20:29:43'),
  ('94', 'default', 'updated', 'App\\Models\\User', 'updated', '10', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$ZpyezXSeoAB3rrA42vd35evXSdDt2PW95u02BJux8rzGaktxtQC4K\",\"updated_at\":\"2026-06-30T06:28:20.000000Z\"},\"old\":{\"password\":\"$2y$10$mt.9QefRq7wP.1PDjDi5EOGMfRgtp4UbPno\\/09.fSht.OSpAHXe..\",\"updated_at\":\"2026-06-25T13:29:36.000000Z\"}}', NULL, '2026-06-30 13:28:20', '2026-06-30 13:28:20'),
  ('95', 'default', 'updated', 'App\\Models\\User', 'updated', '11', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$odXy0uW6H7ZgUKrQW.FLIeDvfzjfScAki.Yl3F3X1.zdVXYnl15eq\",\"updated_at\":\"2026-06-30T06:28:20.000000Z\"},\"old\":{\"password\":\"$2y$10$aC2kn04mGvHoBCDlGPgGJOq8K\\/S4hDTZb7Cn7mPtCTSmTdJXOIfN.\",\"updated_at\":\"2026-06-25T13:29:36.000000Z\"}}', NULL, '2026-06-30 13:28:20', '2026-06-30 13:28:20'),
  ('96', 'default', 'updated', 'App\\Models\\User', 'updated', '10', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$X\\/MKgjU3Zowp62a\\/qJWIVuw4yZMl6kpHwPfzgE6IqvxZ2RzZUox0C\",\"updated_at\":\"2026-06-30T06:30:02.000000Z\"},\"old\":{\"password\":\"$2y$10$ZpyezXSeoAB3rrA42vd35evXSdDt2PW95u02BJux8rzGaktxtQC4K\",\"updated_at\":\"2026-06-30T06:28:20.000000Z\"}}', NULL, '2026-06-30 13:30:02', '2026-06-30 13:30:02'),
  ('97', 'default', 'updated', 'App\\Models\\User', 'updated', '16', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$ejKZAC7WIh1A.5xp5ewUO.g04mZ6\\/R1R24\\/qFkSv8eSvEGG4FRMJK\",\"updated_at\":\"2026-06-30T06:30:38.000000Z\"},\"old\":{\"password\":\"$2y$10$5J6CN\\/TEWQE1WWhl0yHADOl86lt\\/ILcgdI4pWznU1E2dKgB8kx\\/aW\",\"updated_at\":\"2026-06-25T13:29:36.000000Z\"}}', NULL, '2026-06-30 13:30:38', '2026-06-30 13:30:38'),
  ('98', 'default', 'updated', 'App\\Models\\User', 'updated', '17', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$3uAybmAjoF6SRr9enaSvhuwqbPaYAtgI7ygXq7rwXvGYfvC9JDvMK\",\"updated_at\":\"2026-06-30T06:30:39.000000Z\"},\"old\":{\"password\":\"$2y$10$ExXH82qQS7.ejQK5kwcYOurhYnVDmRiJHfW7CJR0tibRuGHTT1tlO\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"}}', NULL, '2026-06-30 13:30:39', '2026-06-30 13:30:39'),
  ('99', 'default', 'updated', 'App\\Models\\User', 'updated', '18', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$X1BcCx9gEKZADKk6rdMbROqAXsdNs\\/sF13Pee8gMgASGN2T0HKmOu\",\"updated_at\":\"2026-06-30T06:30:39.000000Z\"},\"old\":{\"password\":\"$2y$10$H\\/F5KnQFsqOMjR8u1PoBbeF2w5bDeJFEmcshJADRCweya04nop1Gm\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"}}', NULL, '2026-06-30 13:30:39', '2026-06-30 13:30:39'),
  ('100', 'default', 'updated', 'App\\Models\\User', 'updated', '19', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$tpa480lFn7at6gtfQw3I0.KcvW7x8N9x36nX7Dn09OT8jjzJWbDYS\",\"updated_at\":\"2026-06-30T06:30:39.000000Z\"},\"old\":{\"password\":\"$2y$10$BIErCSzYQri0kuKnWS0tPef5D816jCJ9s8ZuHEB6UWvZdGTWN\\/PPW\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"}}', NULL, '2026-06-30 13:30:39', '2026-06-30 13:30:39'),
  ('101', 'default', 'updated', 'App\\Models\\User', 'updated', '20', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$dUhfMauVlkAQh4Te4JNXsuztzN05vDL8H\\/KRXZ9beVKvqo62uAqmS\",\"updated_at\":\"2026-06-30T06:30:39.000000Z\"},\"old\":{\"password\":\"$2y$10$sdRU7\\/1\\/4MEFTq97c0nXeujPiWKkWDyFCayUUVxpH1500WHVKthga\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"}}', NULL, '2026-06-30 13:30:39', '2026-06-30 13:30:39'),
  ('102', 'default', 'updated', 'App\\Models\\User', 'updated', '21', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$MThviHgCVZgO3bahVcA\\/PeRt9Kews2GIoKQQK5e8wBMWgK8Bhgg7O\",\"updated_at\":\"2026-06-30T06:30:39.000000Z\"},\"old\":{\"password\":\"$2y$10$vhX983usHwmcA3DI6P1lEurUkt05dKuDCekmTBkESfLOMxcl1\\/yhe\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"}}', NULL, '2026-06-30 13:30:39', '2026-06-30 13:30:39'),
  ('103', 'default', 'updated', 'App\\Models\\User', 'updated', '22', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$3za8ynMLZbIwwxTsJmYgcuCiE0F0ABiRwyGuwvshrijBeWDa4KCtW\",\"updated_at\":\"2026-06-30T06:30:39.000000Z\"},\"old\":{\"password\":\"$2y$10$WQ69nNzv\\/\\/dZhPh\\/SmAGGuKQUusjYRNjOSseiftJIhDKfYrmNThG.\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"}}', NULL, '2026-06-30 13:30:39', '2026-06-30 13:30:39'),
  ('104', 'default', 'updated', 'App\\Models\\User', 'updated', '23', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$BJAaaS7AIHnxOfMNTwB0aOGSP3ajfLqMvoHK.b\\/ZtHtHYIw\\/jorHO\",\"updated_at\":\"2026-06-30T06:30:39.000000Z\"},\"old\":{\"password\":\"$2y$10$XqPlh4AX17bwoPHKR8NitO\\/xITioyMOxJFn0mcVWx984sTiPCdJIG\",\"updated_at\":\"2026-06-25T13:29:37.000000Z\"}}', NULL, '2026-06-30 13:30:39', '2026-06-30 13:30:39'),
  ('105', 'default', 'updated', 'App\\Models\\User', 'updated', '24', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$U9m\\/5HY3RYRv3vaab7TRMejBbfQUGsL.sbYo\\/owofx5xyhys0Jlcu\",\"updated_at\":\"2026-06-30T06:30:39.000000Z\"},\"old\":{\"password\":\"$2y$10$aeu04M7ZzSv.g\\/l7xh5XZ.whtoXZGR.q0fIuJMhj3uuANjzz.sa.a\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"}}', NULL, '2026-06-30 13:30:39', '2026-06-30 13:30:39'),
  ('106', 'default', 'updated', 'App\\Models\\User', 'updated', '25', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$uBpkXV7.qBsWBC9X2fItxetTn4rIYPgf9TYgq4FxESHhm\\/4.Dgliu\",\"updated_at\":\"2026-06-30T06:30:39.000000Z\"},\"old\":{\"password\":\"$2y$10$U0SouiH0zt\\/IfiUuDSFcdOs5nnyAN4kGQyG5B.YERZdvi31mNtXUy\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"}}', NULL, '2026-06-30 13:30:39', '2026-06-30 13:30:39'),
  ('107', 'default', 'updated', 'App\\Models\\User', 'updated', '26', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$ZPqNhB9Qbh2gvspND.hP3usvN\\/5bELWtq0FE377oShSgDIYh6XSgO\",\"updated_at\":\"2026-06-30T06:30:39.000000Z\"},\"old\":{\"password\":\"$2y$10$UDGvykxmatuu054aduvjxO5xCEZMsxmGKF1MW0tACg.M2FMtwtJ3G\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"}}', NULL, '2026-06-30 13:30:39', '2026-06-30 13:30:39'),
  ('108', 'default', 'updated', 'App\\Models\\User', 'updated', '27', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$o4m0vT4qUc2KVYJrVcwimeR7t.53Hn.Q44JOGX3ecedaTa35qJExe\",\"updated_at\":\"2026-06-30T06:30:40.000000Z\"},\"old\":{\"password\":\"$2y$10$Q.IaBITmB2X3KLD0p4L07eGz6Z11.4nqUa0h2XAPQJly5GSVRUFHq\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"}}', NULL, '2026-06-30 13:30:40', '2026-06-30 13:30:40'),
  ('109', 'default', 'updated', 'App\\Models\\User', 'updated', '28', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$yM8ml8DkJ.DIpW1oDFj5z.7mU2shEWL98Oc2EZ9Xy8U.Pul5mAc\\/y\",\"updated_at\":\"2026-06-30T06:30:40.000000Z\"},\"old\":{\"password\":\"$2y$10$5VZ35kHtWeMW0GIllCjdBOX0gvQRgU1CewljhVhJhpRKZanLPd..C\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"}}', NULL, '2026-06-30 13:30:40', '2026-06-30 13:30:40'),
  ('110', 'default', 'updated', 'App\\Models\\User', 'updated', '29', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$8TkmMTEXW4BGB4N2guZOWOJwpj\\/sMJCryS3dMp99gS0cvGCHl18aq\",\"updated_at\":\"2026-06-30T06:30:40.000000Z\"},\"old\":{\"password\":\"$2y$10$pNZKHUI4vIoLR51UIQW2zeD6UHRtyuWAH\\/uGsCFNum7AF.e86YXC.\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"}}', NULL, '2026-06-30 13:30:40', '2026-06-30 13:30:40'),
  ('111', 'default', 'updated', 'App\\Models\\User', 'updated', '30', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$td73NkB3iGmp6ca604f2weCJrjcbP1Kx96f.d2y2at.nZC.uhYj5q\",\"updated_at\":\"2026-06-30T06:30:40.000000Z\"},\"old\":{\"password\":\"$2y$10$H9OoA9J4.YUEb2g6VNiMQO\\/pyr5fhRHFnRrQ3xKCtAeYhyCCUGCKy\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"}}', NULL, '2026-06-30 13:30:40', '2026-06-30 13:30:40'),
  ('112', 'default', 'updated', 'App\\Models\\User', 'updated', '31', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$5Dj4tymgwcLzQMjoULH5U.eehbyuco9qR3ASbpXtX1N9qmlRvjc8G\",\"updated_at\":\"2026-06-30T06:30:40.000000Z\"},\"old\":{\"password\":\"$2y$10$CJoP\\/RJoLR\\/dRY\\/2sgRKuetfMYzkB5ITmp8H0tifvWzGXWxPFDJbO\",\"updated_at\":\"2026-06-25T13:29:38.000000Z\"}}', NULL, '2026-06-30 13:30:40', '2026-06-30 13:30:40'),
  ('113', 'default', 'updated', 'App\\Models\\User', 'updated', '32', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$5Qv368bA5ZUzsU8tqMKcn.u\\/xLkd19AK04TYFzy8NsdRF0g5f359K\",\"updated_at\":\"2026-06-30T06:30:40.000000Z\"},\"old\":{\"password\":\"$2y$10$vx8wKY4ZC4sH95S3KqeLI.hyyWK0HH7GlZqy0ngwaNcnn.W34TVgS\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"}}', NULL, '2026-06-30 13:30:40', '2026-06-30 13:30:40'),
  ('114', 'default', 'updated', 'App\\Models\\User', 'updated', '33', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$FwHh0hYJ8zR.AnOPcoL1m.5SoFllstdBkHoiDfJukcrFCVOrwYKSy\",\"updated_at\":\"2026-06-30T06:30:40.000000Z\"},\"old\":{\"password\":\"$2y$10$qBJ.B89KOpc\\/iApS354IielRBf2R1mNJAMrbGkzaa2x9NLUlfP.J.\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"}}', NULL, '2026-06-30 13:30:40', '2026-06-30 13:30:40'),
  ('115', 'default', 'updated', 'App\\Models\\User', 'updated', '34', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$BE4xiAX4MtkvPnGD2IAYiOi8z2V01fHCt3hvUg5NBLYjowR77sPM.\",\"updated_at\":\"2026-06-30T06:30:40.000000Z\"},\"old\":{\"password\":\"$2y$10$2DWjFxtGLCSaaKCGfydJh.U8I3dNEdF7cPlhrYQg7xIkLrl3SQfVC\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"}}', NULL, '2026-06-30 13:30:40', '2026-06-30 13:30:40'),
  ('116', 'default', 'updated', 'App\\Models\\User', 'updated', '35', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$\\/sysv8iTmZHsIPKJTXU\\/cutyB20c58FptiD81PDwIoemsuj2lHrVy\",\"updated_at\":\"2026-06-30T06:30:40.000000Z\"},\"old\":{\"password\":\"$2y$10$9nh3vLMgu0fVJ2.EdJv7Nuvrdr6cWoKyq1iXKP\\/CxShKwQ4AHAVpC\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"}}', NULL, '2026-06-30 13:30:40', '2026-06-30 13:30:40'),
  ('117', 'default', 'updated', 'App\\Models\\User', 'updated', '36', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$3lrWGunKD6JKedzWYCzCxuIQNWXvRrzZc71dnJi4v9HWw49sAXbCC\",\"updated_at\":\"2026-06-30T06:30:40.000000Z\"},\"old\":{\"password\":\"$2y$10$EvvQclEx\\/Jd0aE\\/Eo3aY9OAs54htlwQ3Sz9QKJtu41ZvHYBNAJa\\/6\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"}}', NULL, '2026-06-30 13:30:40', '2026-06-30 13:30:40'),
  ('118', 'default', 'updated', 'App\\Models\\User', 'updated', '37', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$6FXecrulMg6Zu7PLES5a.ex6uYeSiU2j.h11rN.V40ENQE5Pn4IKa\",\"updated_at\":\"2026-06-30T06:30:40.000000Z\"},\"old\":{\"password\":\"$2y$10$5TbBR72aEur..B58N8AbROXCSKV1U69PL3AjX37pPCskCJ.d2Jw5W\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"}}', NULL, '2026-06-30 13:30:40', '2026-06-30 13:30:40'),
  ('119', 'default', 'updated', 'App\\Models\\User', 'updated', '38', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$ARAYzuxYsqWFgzXzxmd0NO8ujpJ2LT9vrmJfTJrP5culV9.7HuMpq\",\"updated_at\":\"2026-06-30T06:30:41.000000Z\"},\"old\":{\"password\":\"$2y$10$C5aol.2hocsc7o5yICLoA.BJNsr09p0ty4raDfs239NiqCJf\\/j4Hu\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"}}', NULL, '2026-06-30 13:30:41', '2026-06-30 13:30:41'),
  ('120', 'default', 'updated', 'App\\Models\\User', 'updated', '39', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$eD38qCoyY0qpH5WB485x1eJfF3zPWSViU.rjD57c6Jgqplx1CSyOm\",\"updated_at\":\"2026-06-30T06:30:41.000000Z\"},\"old\":{\"password\":\"$2y$10$HNRFFEDZ88rk\\/LV7kOx15e3NVV6Oa.LAtnQwOUvM34k418W2W1FTO\",\"updated_at\":\"2026-06-25T13:29:39.000000Z\"}}', NULL, '2026-06-30 13:30:41', '2026-06-30 13:30:41'),
  ('121', 'default', 'updated', 'App\\Models\\User', 'updated', '40', NULL, NULL, '{\"attributes\":{\"password\":\"$2y$10$lDx77sk6N278COvxKQ1f1.Y0sdXwsYvI8wfwlFObRwyQoAx1a89AC\",\"updated_at\":\"2026-06-30T06:30:41.000000Z\"},\"old\":{\"password\":\"$2y$10$Orf0FoXq5zyXmFFOTH13xOvU47hquvvt.nwW7r\\/0NlISocxqvoBsW\",\"updated_at\":\"2026-06-25T13:29:40.000000Z\"}}', NULL, '2026-06-30 13:30:41', '2026-06-30 13:30:41');

-- ---- Tabel: `activity_logs` (0 baris) ----
-- (kosong)

-- ---- Tabel: `announcements` (9 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `announcements` (`id`, `cabang_id`, `dibuat_oleh`, `judul`, `konten`, `jenis`, `target`, `target_teacher_ids`, `target_student_ids`, `file`, `tanggal_mulai`, `tanggal_selesai`, `is_pinned`, `status`, `created_at`, `updated_at`) VALUES
  ('1', '3', '10', 'Jadwal Tryout SNBT Nasional Februari 2025', 'Kepada seluruh peserta program Intensif SNBT, tryout nasional akan dilaksanakan pada tanggal 15 Februari 2025. Harap mempersiapkan diri sebaik mungkin. Tryout akan berlangsung selama 3 jam dan mencakup semua subtes SNBT. Informasi lebih lanjut akan disampaikan melalui WhatsApp grup.', 'penting', 'siswa', NULL, NULL, NULL, '2026-06-20', '2026-07-15', '1', 'aktif', '2026-06-25 12:45:06', '2026-06-25 12:45:06'),
  ('2', '3', '10', 'Libur Kelas: Hari Kemerdekaan RI 17 Agustus', 'Diberitahukan kepada seluruh siswa dan guru bahwa tidak ada kegiatan belajar mengajar pada tanggal 17 Agustus 2024 dalam rangka Hari Kemerdekaan Republik Indonesia ke-79. Kelas akan dilanjutkan pada jadwal berikutnya.', 'informasi', 'semua', NULL, NULL, NULL, '2026-06-15', '2026-06-30', '0', 'aktif', '2026-06-25 12:45:06', '2026-06-25 12:45:06'),
  ('3', '3', '10', 'Rapat Guru Bulanan - Evaluasi Pembelajaran', 'Seluruh guru diwajibkan hadir dalam rapat bulanan evaluasi pembelajaran yang akan dilaksanakan pada hari Sabtu, pukul 09.00 WIB di ruang rapat utama. Agenda: evaluasi capaian siswa, persiapan ujian akhir semester, dan koordinasi jadwal.', 'penting', 'guru', NULL, NULL, NULL, '2026-06-23', '2026-06-28', '1', 'aktif', '2026-06-25 12:45:06', '2026-06-25 12:45:06'),
  ('4', '4', '10', 'Promo Daftar Bimbel Bandung - Diskon 20%', 'Spesial untuk pendaftar baru di Cabang Bandung bulan ini, dapatkan diskon 20% untuk bulan pertama. Berlaku hingga akhir bulan. Hubungi admin untuk informasi pendaftaran.', 'informasi', 'semua', NULL, NULL, NULL, '2026-06-22', '2026-06-30', '0', 'aktif', '2026-06-25 12:45:06', '2026-06-25 12:45:06'),
  ('5', NULL, NULL, 'Selamat Datang di Semester Baru 2025/2026', 'Kami menyambut semua siswa di semester baru. Semoga pembelajaran berjalan lancar dan prestasi terus meningkat. Jadwal kelas sudah tersedia di dashboard masing-masing.', 'info', 'all', NULL, NULL, NULL, '2026-05-31', NULL, '0', 'aktif', '2026-06-25 20:29:43', '2026-06-25 20:29:43'),
  ('6', NULL, NULL, 'Libur Hari Raya Idul Fitri', 'Akademi SCI akan libur pada tanggal 28 Maret - 7 April 2025 dalam rangka Hari Raya Idul Fitri 1446 H. Kelas akan dilanjutkan kembali pada 8 April 2025.', 'warning', 'all', NULL, NULL, NULL, '2026-06-19', NULL, '0', 'aktif', '2026-06-25 20:29:43', '2026-06-25 20:29:43'),
  ('7', NULL, NULL, 'Tryout UTBK Perdana Tersedia', 'Tryout UTBK perdana sudah dibuka untuk siswa paket Intensif Saintek dan Soshum. Silakan akses menu Tryout di dashboard siswa Anda.', 'success', 'siswa', NULL, NULL, NULL, '2026-06-04', NULL, '0', 'aktif', '2026-06-25 20:29:43', '2026-06-25 20:29:43'),
  ('8', NULL, NULL, 'Reminder: Input Jurnal Mengajar', 'Kepada seluruh guru, harap mengisi jurnal mengajar setelah setiap sesi selesai. Keterlambatan input jurnal akan mempengaruhi proses penggajian.', 'warning', 'guru', NULL, NULL, NULL, '2026-06-09', NULL, '0', 'aktif', '2026-06-25 20:29:43', '2026-06-25 20:29:43'),
  ('9', NULL, NULL, 'Pembayaran Bulan Maret Jatuh Tempo', 'Tagihan bulan Maret 2025 akan jatuh tempo pada 31 Maret 2025. Harap segera melakukan pembayaran melalui transfer bank atau bayar langsung di cabang.', 'danger', 'siswa', NULL, NULL, NULL, '2026-05-31', NULL, '0', 'aktif', '2026-06-25 20:29:43', '2026-06-25 20:29:43');

-- ---- Tabel: `branches` (5 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `branches` (`id`, `user_id`, `name`, `address`, `phone`, `city`, `admin_id`, `created_by`, `updated_by`, `student_count`, `status`, `created_at`, `updated_at`, `regency`, `email`, `password`, `can_students`, `can_teachers`, `can_schedules`, `can_payments`, `can_tryouts`, `allowed_pages`) VALUES
  ('2', NULL, 'Lampung', 'Jalan Mayjen MT Haryono, Kelurahan Gotong Royong, Kecamatan Tanjung Karang Pusat, dengan kode pos 35119.', '1', 'Bandar Lampung', '12', NULL, NULL, '0', 'active', '2026-06-22 13:47:02', '2026-06-22 13:47:02', '1', 'admin@sci.com', NULL, '0', '0', '0', '0', '0', '[]'),
  ('3', NULL, 'Cabang Pusat Jakarta', 'Jl. Sudirman No. 10, Kebayoran Baru, Jakarta Selatan', '021-5551001', 'Jakarta', '16', NULL, NULL, '0', 'active', '2026-06-25 12:45:00', '2026-06-25 12:45:01', 'Jakarta Selatan', 'cabang.pusat@akademibimbel.com', NULL, '1', '1', '1', '1', '1', NULL),
  ('4', NULL, 'Cabang Bandung', 'Jl. Dago No. 25, Coblong, Bandung', '022-2501234', 'Bandung', '17', NULL, NULL, '0', 'active', '2026-06-25 12:45:00', '2026-06-25 12:45:01', 'Bandung Kota', 'cabang.bandung@akademibimbel.com', NULL, '1', '1', '1', '1', '1', NULL),
  ('5', NULL, 'Cabang Surabaya', 'Jl. Raya Darmo No. 8, Wonokromo, Surabaya', '031-5671234', 'Surabaya', '18', NULL, NULL, '0', 'active', '2026-06-25 12:45:00', '2026-06-25 12:45:01', 'Surabaya Pusat', 'cabang.surabaya@akademibimbel.com', NULL, '1', '1', '1', '1', '0', NULL),
  ('6', NULL, 'Pusat Jakarta', 'Jl. Sudirman No. 1, Jakarta Pusat', '021-5555001', 'Jakarta', NULL, NULL, NULL, '0', 'active', '2026-06-25 20:29:40', '2026-06-25 20:29:40', NULL, 'pusat@akademisci.com', NULL, '1', '1', '1', '1', '1', NULL);

-- ---- Tabel: `categories` (0 baris) ----
-- (kosong)

-- ---- Tabel: `certificates` (3 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `certificates` (`id`, `siswa_id`, `cabang_id`, `course_id`, `diterbitkan_oleh`, `nomor_sertifikat`, `jenis`, `judul`, `deskripsi`, `tanggal_terbit`, `tanggal_expired`, `file_sertifikat`, `file_qrcode`, `created_at`, `updated_at`, `deleted_at`) VALUES
  ('1', '5', '3', '7', 'Admin Pusat SCI', 'CERT-SNBT-2024-001', 'kelulusan', 'Sertifikat Kelulusan Program Intensif SNBT 2024', 'Diberikan kepada Fajar Hidayat atas keberhasilan menyelesaikan Program Intensif Persiapan SNBT 2024.', '2026-06-16', '2027-06-25', NULL, NULL, '2026-06-25 12:45:06', '2026-06-25 12:45:06', NULL),
  ('2', '6', '3', '7', 'Admin Pusat SCI', 'CERT-SNBT-2024-002', 'kelulusan', 'Sertifikat Kelulusan Program Intensif SNBT 2024', 'Diberikan kepada Gita Permata atas keberhasilan menyelesaikan Program Intensif Persiapan SNBT 2024.', '2026-06-06', '2027-06-25', NULL, NULL, '2026-06-25 12:45:06', '2026-06-25 12:45:06', NULL),
  ('3', '10', '3', '7', 'Admin Pusat SCI', 'CERT-SNBT-2024-003', 'kelulusan', 'Sertifikat Kelulusan Program Intensif SNBT 2024', 'Diberikan kepada Kartini Wulandari atas keberhasilan menyelesaikan Program Intensif Persiapan SNBT 2024.', '2026-06-14', '2027-06-25', NULL, NULL, '2026-06-25 12:45:06', '2026-06-25 12:45:06', NULL);

-- ---- Tabel: `chat_messages` (0 baris) ----
-- (kosong)

-- ---- Tabel: `chat_rooms` (0 baris) ----
-- (kosong)

-- ---- Tabel: `class_students` (33 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `class_students` (`id`, `class_id`, `student_id`, `created_at`, `updated_at`) VALUES
  ('1', '1', '1', NULL, NULL),
  ('2', '2', '2', NULL, NULL),
  ('3', '3', '3', NULL, NULL),
  ('4', '4', '3', NULL, NULL),
  ('5', '3', '4', NULL, NULL),
  ('6', '5', '4', NULL, NULL),
  ('7', '6', '4', NULL, NULL),
  ('8', '7', '5', NULL, NULL),
  ('9', '7', '6', NULL, NULL),
  ('10', '3', '6', NULL, NULL),
  ('11', '3', '7', NULL, NULL),
  ('12', '6', '7', NULL, NULL),
  ('13', '6', '8', NULL, NULL),
  ('14', '3', '9', NULL, NULL),
  ('15', '7', '10', NULL, NULL),
  ('16', '8', '11', NULL, NULL),
  ('17', '9', '11', NULL, NULL),
  ('18', '8', '12', NULL, NULL),
  ('19', '9', '13', NULL, NULL),
  ('20', '9', '14', NULL, NULL),
  ('21', '10', '15', NULL, NULL),
  ('22', '10', '16', NULL, NULL),
  ('23', '10', '17', NULL, NULL),
  ('24', '11', '18', NULL, NULL),
  ('25', '11', '19', NULL, NULL),
  ('26', '11', '24', NULL, NULL),
  ('27', '11', '25', NULL, NULL),
  ('28', '12', '20', NULL, NULL),
  ('29', '12', '21', NULL, NULL),
  ('30', '12', '26', NULL, NULL),
  ('31', '13', '22', NULL, NULL),
  ('32', '13', '23', NULL, NULL),
  ('33', '13', '27', NULL, NULL);

-- ---- Tabel: `course_fees` (11 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `course_fees` (`id`, `course_id`, `amount`, `created_at`, `updated_at`) VALUES
  ('1', '2', '350000.00', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('2', '3', '350000.00', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('3', '4', '350000.00', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('4', '5', '350000.00', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('5', '6', '300000.00', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('6', '7', '500000.00', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('7', '8', '300000.00', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('8', '9', '275000.00', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('9', '10', '300000.00', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('10', '11', '325000.00', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('11', '12', '275000.00', '2026-06-25 12:45:01', '2026-06-25 12:45:01');

-- ---- Tabel: `course_package` (35 baris) ----
INSERT INTO `course_package` (`package_id`, `course_id`) VALUES
  ('1', '1'),
  ('2', '2'),
  ('2', '3'),
  ('2', '4'),
  ('2', '5'),
  ('2', '6'),
  ('3', '2'),
  ('3', '3'),
  ('3', '4'),
  ('3', '5'),
  ('3', '6'),
  ('3', '7'),
  ('4', '2'),
  ('4', '6'),
  ('5', '8'),
  ('5', '9'),
  ('5', '10'),
  ('6', '11'),
  ('6', '12'),
  ('7', '1'),
  ('7', '3'),
  ('7', '4'),
  ('7', '5'),
  ('8', '6'),
  ('8', '13'),
  ('8', '14'),
  ('8', '15'),
  ('8', '16'),
  ('8', '17'),
  ('9', '1'),
  ('10', '6'),
  ('11', '1'),
  ('11', '6'),
  ('11', '13'),
  ('11', '14');

-- ---- Tabel: `courses` (56 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `courses` (`id`, `created_at`, `updated_at`, `cabang_id`, `kode`, `nama`, `kategori`, `jenis_kursus`, `deskripsi`, `status`, `deleted_at`) VALUES
  ('1', '2026-06-22 13:47:42', '2026-06-22 13:47:42', '2', 'MTK', 'Matematika', 'academic', NULL, 'Belajar Menghitung', 'aktif', NULL),
  ('2', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '3', 'MAT-001', 'Matematika', 'Akademik', NULL, 'Mata pelajaran matematika dasar hingga lanjutan mencakup aljabar, geometri, dan statistika.', 'aktif', NULL),
  ('3', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '3', 'FIS-001', 'Fisika', 'Akademik', NULL, 'Fisika dasar hingga lanjutan, mekanika, termodinamika, listrik magnet, dan gelombang.', 'aktif', NULL),
  ('4', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '3', 'KIM-001', 'Kimia', 'Akademik', NULL, 'Kimia umum, reaksi kimia, stoikiometri, termokimia, dan kimia organik dasar.', 'aktif', NULL),
  ('5', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '3', 'BIO-001', 'Biologi', 'Akademik', NULL, 'Biologi sel, genetika, ekologi, anatomi manusia, dan fisiologi tumbuhan.', 'aktif', NULL),
  ('6', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '3', 'ING-001', 'Bahasa Inggris', 'Bahasa', NULL, 'Grammar, reading comprehension, writing, speaking, dan listening skills.', 'aktif', NULL),
  ('7', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '3', 'SNBT-001', 'Persiapan SNBT', 'Ujian', NULL, 'Persiapan UTBK-SNBT meliputi TPS, Literasi Bahasa, dan Penalaran Matematika.', 'aktif', NULL),
  ('8', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '4', 'MAT-BDG', 'Matematika', 'Akademik', NULL, 'Matematika dasar dan lanjutan untuk SD, SMP, SMA.', 'aktif', NULL),
  ('9', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '4', 'ING-BDG', 'Bahasa Inggris', 'Bahasa', NULL, 'English course untuk semua jenjang.', 'aktif', NULL),
  ('10', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '4', 'FIS-BDG', 'Fisika', 'Akademik', NULL, 'Fisika SMA dan persiapan ujian nasional.', 'aktif', NULL),
  ('11', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '5', 'MAT-SBY', 'Matematika', 'Akademik', NULL, 'Matematika komprehensif untuk semua jenjang.', 'aktif', NULL),
  ('12', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '5', 'ING-SBY', 'Bahasa Inggris', 'Bahasa', NULL, 'Kursus Bahasa Inggris komunikatif.', 'aktif', NULL),
  ('13', '2026-06-25 20:29:40', '2026-06-25 20:29:40', NULL, NULL, 'Bahasa Indonesia', 'Umum', NULL, NULL, 'aktif', NULL),
  ('14', '2026-06-25 20:29:40', '2026-06-25 20:29:40', NULL, NULL, 'Ekonomi', 'Soshum', NULL, NULL, 'aktif', NULL),
  ('15', '2026-06-25 20:29:40', '2026-06-25 20:29:40', NULL, NULL, 'Sosiologi', 'Soshum', NULL, NULL, 'aktif', NULL),
  ('16', '2026-06-25 20:29:40', '2026-06-25 20:29:40', NULL, NULL, 'Sejarah', 'Soshum', NULL, NULL, 'aktif', NULL),
  ('17', '2026-06-25 20:29:40', '2026-06-25 20:29:40', NULL, NULL, 'Geografi', 'Soshum', NULL, NULL, 'aktif', NULL),
  ('18', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'KOM-01', 'Microsoft Office Perkantoran', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('19', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'KOM-02', 'Word', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('20', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'KOM-03', 'Excel', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('21', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'KOM-04', 'PowerPoint', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('22', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'KOM-05', 'Desain Grafis', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('23', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'KOM-06', 'CorelDraw', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('24', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'KOM-07', 'Photoshop', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('25', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'KOM-08', 'AutoCAD', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('26', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'KOM-09', 'Programmer / Coding', 'skill', 'komputer', NULL, 'aktif', NULL),
  ('27', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'BHS-01', 'Bahasa Inggris', 'skill', 'bahasa', NULL, 'aktif', NULL),
  ('28', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'BHS-02', 'Bahasa Arab', 'skill', 'bahasa', NULL, 'aktif', NULL),
  ('29', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'BHS-03', 'Bahasa Mandarin', 'skill', 'bahasa', NULL, 'aktif', NULL),
  ('30', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'BHS-04', 'Bahasa Jepang', 'skill', 'bahasa', NULL, 'aktif', NULL),
  ('31', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'BHS-05', 'Bahasa Korea', 'skill', 'bahasa', NULL, 'aktif', NULL),
  ('32', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'MAP-01', 'Matematika', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('33', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'MAP-02', 'Kimia', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('34', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'MAP-03', 'Biologi', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('35', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'MAP-04', 'Bahasa Indonesia', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('36', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'MAP-05', 'Fisika', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('37', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'MAP-06', 'Akuntansi / Ekonomi', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('38', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'MAP-07', 'Geografi', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('39', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'MAP-08', 'IPA', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('40', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'MAP-09', 'IPS', 'academic', 'mapel', NULL, 'aktif', NULL),
  ('41', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'DIN-01', 'SKD TIU', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('42', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'DIN-02', 'SKD TWK', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('43', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'DIN-03', 'SKD TKP', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('44', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'DIN-04', 'TPA', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('45', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'DIN-05', 'Psikotes', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('46', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'DIN-06', 'TBI', 'academic', 'kedinasan', NULL, 'aktif', NULL),
  ('47', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'AKP-01', 'Pengetahuan Umum', 'academic', 'akpol', NULL, 'aktif', NULL),
  ('48', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'AKP-02', 'Wawasan Kebangsaan', 'academic', 'akpol', NULL, 'aktif', NULL),
  ('49', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'AKP-03', 'TKD', 'academic', 'akpol', NULL, 'aktif', NULL),
  ('50', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'AKP-04', 'Tes Akademik', 'academic', 'akpol', NULL, 'aktif', NULL),
  ('51', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'CPN-01', 'SKD TIU (CPNS)', 'academic', 'cpns', NULL, 'aktif', NULL),
  ('52', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'CPN-02', 'SKD TWK (CPNS)', 'academic', 'cpns', NULL, 'aktif', NULL),
  ('53', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'CPN-03', 'SKD TKP (CPNS)', 'academic', 'cpns', NULL, 'aktif', NULL),
  ('54', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'BUM-01', 'TKD BUMN', 'academic', 'bumn', NULL, 'aktif', NULL),
  ('55', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'BUM-02', 'Tes AKHLAK', 'academic', 'bumn', NULL, 'aktif', NULL),
  ('56', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, 'BUM-03', 'TWK BUMN', 'academic', 'bumn', NULL, 'aktif', NULL);

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

-- ---- Tabel: `grades` (75 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `grades` (`id`, `created_at`, `updated_at`, `siswa_id`, `mata_pelajaran_id`, `guru_id`, `semester_id`, `jenis_penilaian`, `nama_penilaian`, `nilai`, `nilai_maksimal`, `bobot`, `tanggal`, `catatan`) VALUES
  ('1', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '2', '2', NULL, 'ulangan_harian', 'Ulangan harian', '78.00', '100.00', '30.00', '2026-06-03', NULL),
  ('2', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '2', '2', NULL, 'mid_semester', 'Mid semester', '92.00', '100.00', '30.00', '2026-06-14', NULL),
  ('3', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '2', '2', NULL, 'akhir_semester', 'Akhir semester', '66.00', '100.00', '40.00', '2026-05-31', NULL),
  ('4', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '3', '2', NULL, 'ulangan_harian', 'Ulangan harian', '88.00', '100.00', '30.00', '2026-06-15', NULL),
  ('5', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '3', '2', NULL, 'mid_semester', 'Mid semester', '79.00', '100.00', '30.00', '2026-06-17', NULL),
  ('6', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '3', '2', NULL, 'akhir_semester', 'Akhir semester', '87.00', '100.00', '40.00', '2026-06-03', NULL),
  ('7', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '4', '2', '2', NULL, 'ulangan_harian', 'Ulangan harian', '86.00', '100.00', '30.00', '2026-06-02', NULL),
  ('8', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '4', '2', '2', NULL, 'mid_semester', 'Mid semester', '83.00', '100.00', '30.00', '2026-06-05', NULL),
  ('9', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '4', '2', '2', NULL, 'akhir_semester', 'Akhir semester', '65.00', '100.00', '40.00', '2026-05-29', NULL),
  ('10', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '4', '6', '4', NULL, 'ulangan_harian', 'Ulangan harian', '71.00', '100.00', '30.00', '2026-06-09', NULL),
  ('11', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '4', '6', '4', NULL, 'mid_semester', 'Mid semester', '83.00', '100.00', '30.00', '2026-06-12', NULL),
  ('12', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '4', '6', '4', NULL, 'akhir_semester', 'Akhir semester', '67.00', '100.00', '40.00', '2026-06-03', NULL),
  ('13', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '6', '2', '2', NULL, 'ulangan_harian', 'Ulangan harian', '73.00', '100.00', '30.00', '2026-05-27', NULL),
  ('14', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '6', '2', '2', NULL, 'mid_semester', 'Mid semester', '76.00', '100.00', '30.00', '2026-06-11', NULL),
  ('15', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '6', '2', '2', NULL, 'akhir_semester', 'Akhir semester', '84.00', '100.00', '40.00', '2026-06-14', NULL),
  ('16', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '2', '2', NULL, 'ulangan_harian', 'Ulangan harian', '70.00', '100.00', '30.00', '2026-06-19', NULL),
  ('17', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '2', '2', NULL, 'mid_semester', 'Mid semester', '95.00', '100.00', '30.00', '2026-06-18', NULL),
  ('18', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '2', '2', NULL, 'akhir_semester', 'Akhir semester', '78.00', '100.00', '40.00', '2026-05-31', NULL),
  ('19', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '6', '4', NULL, 'ulangan_harian', 'Ulangan harian', '77.00', '100.00', '30.00', '2026-06-10', NULL),
  ('20', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '6', '4', NULL, 'mid_semester', 'Mid semester', '94.00', '100.00', '30.00', '2026-06-01', NULL),
  ('21', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '6', '4', NULL, 'akhir_semester', 'Akhir semester', '90.00', '100.00', '40.00', '2026-06-01', NULL),
  ('22', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '8', '6', '4', NULL, 'ulangan_harian', 'Ulangan harian', '82.00', '100.00', '30.00', '2026-06-10', NULL),
  ('23', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '8', '6', '4', NULL, 'mid_semester', 'Mid semester', '85.00', '100.00', '30.00', '2026-06-12', NULL),
  ('24', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '8', '6', '4', NULL, 'akhir_semester', 'Akhir semester', '91.00', '100.00', '40.00', '2026-06-16', NULL),
  ('25', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '9', '2', '2', NULL, 'ulangan_harian', 'Ulangan harian', '90.00', '100.00', '30.00', '2026-06-14', NULL),
  ('26', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '9', '2', '2', NULL, 'mid_semester', 'Mid semester', '89.00', '100.00', '30.00', '2026-06-18', NULL),
  ('27', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '9', '2', '2', NULL, 'akhir_semester', 'Akhir semester', '71.00', '100.00', '40.00', '2026-06-02', NULL),
  ('28', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '18', '1', NULL, NULL, 'tugas', NULL, '95.00', '100.00', '1.00', '2026-05-24', 'Input oleh Admin'),
  ('29', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '18', '1', NULL, NULL, 'ulangan_harian', NULL, '73.00', '100.00', '1.00', '2026-05-27', 'Input oleh Admin'),
  ('30', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '18', '1', NULL, NULL, 'uts', NULL, '94.00', '100.00', '1.00', '2026-05-31', 'Input oleh Admin'),
  ('31', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '18', '1', NULL, NULL, 'uas', NULL, '82.00', '100.00', '1.00', '2026-05-06', 'Input oleh Admin'),
  ('32', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '18', '6', NULL, NULL, 'tugas', NULL, '65.00', '100.00', '1.00', '2026-06-01', 'Input oleh Admin'),
  ('33', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '18', '6', NULL, NULL, 'ulangan_harian', NULL, '77.00', '100.00', '1.00', '2026-06-10', 'Input oleh Admin'),
  ('34', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '18', '6', NULL, NULL, 'uts', NULL, '95.00', '100.00', '1.00', '2026-06-14', 'Input oleh Admin'),
  ('35', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '18', '6', NULL, NULL, 'uas', NULL, '75.00', '100.00', '1.00', '2026-05-31', 'Input oleh Admin'),
  ('36', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '19', '1', NULL, NULL, 'tugas', NULL, '93.00', '100.00', '1.00', '2026-05-12', 'Input oleh Admin'),
  ('37', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '19', '1', NULL, NULL, 'ulangan_harian', NULL, '85.00', '100.00', '1.00', '2026-05-15', 'Input oleh Admin'),
  ('38', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '19', '1', NULL, NULL, 'uts', NULL, '71.00', '100.00', '1.00', '2026-05-08', 'Input oleh Admin'),
  ('39', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '19', '1', NULL, NULL, 'uas', NULL, '88.00', '100.00', '1.00', '2026-05-10', 'Input oleh Admin'),
  ('40', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '19', '6', NULL, NULL, 'tugas', NULL, '98.00', '100.00', '1.00', '2026-06-13', 'Input oleh Admin'),
  ('41', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '19', '6', NULL, NULL, 'ulangan_harian', NULL, '92.00', '100.00', '1.00', '2026-05-13', 'Input oleh Admin'),
  ('42', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '19', '6', NULL, NULL, 'uts', NULL, '95.00', '100.00', '1.00', '2026-05-18', 'Input oleh Admin'),
  ('43', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '19', '6', NULL, NULL, 'uas', NULL, '78.00', '100.00', '1.00', '2026-05-25', 'Input oleh Admin'),
  ('44', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '20', '1', NULL, NULL, 'tugas', NULL, '72.00', '100.00', '1.00', '2026-05-30', 'Input oleh Admin'),
  ('45', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '20', '1', NULL, NULL, 'ulangan_harian', NULL, '79.00', '100.00', '1.00', '2026-06-01', 'Input oleh Admin'),
  ('46', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '20', '1', NULL, NULL, 'uts', NULL, '94.00', '100.00', '1.00', '2026-04-28', 'Input oleh Admin'),
  ('47', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '20', '1', NULL, NULL, 'uas', NULL, '67.00', '100.00', '1.00', '2026-05-14', 'Input oleh Admin'),
  ('48', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '20', '6', NULL, NULL, 'tugas', NULL, '92.00', '100.00', '1.00', '2026-05-10', 'Input oleh Admin'),
  ('49', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '20', '6', NULL, NULL, 'ulangan_harian', NULL, '69.00', '100.00', '1.00', '2026-05-21', 'Input oleh Admin'),
  ('50', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '20', '6', NULL, NULL, 'uts', NULL, '87.00', '100.00', '1.00', '2026-04-28', 'Input oleh Admin'),
  ('51', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '20', '6', NULL, NULL, 'uas', NULL, '70.00', '100.00', '1.00', '2026-05-08', 'Input oleh Admin'),
  ('52', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '21', '1', NULL, NULL, 'tugas', NULL, '88.00', '100.00', '1.00', '2026-06-15', 'Input oleh Admin'),
  ('53', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '21', '1', NULL, NULL, 'ulangan_harian', NULL, '83.00', '100.00', '1.00', '2026-05-14', 'Input oleh Admin'),
  ('54', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '21', '1', NULL, NULL, 'uts', NULL, '73.00', '100.00', '1.00', '2026-05-04', 'Input oleh Admin'),
  ('55', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '21', '1', NULL, NULL, 'uas', NULL, '93.00', '100.00', '1.00', '2026-05-23', 'Input oleh Admin'),
  ('56', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '21', '6', NULL, NULL, 'tugas', NULL, '97.00', '100.00', '1.00', '2026-05-29', 'Input oleh Admin'),
  ('57', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '21', '6', NULL, NULL, 'ulangan_harian', NULL, '98.00', '100.00', '1.00', '2026-05-27', 'Input oleh Admin'),
  ('58', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '21', '6', NULL, NULL, 'uts', NULL, '65.00', '100.00', '1.00', '2026-04-27', 'Input oleh Admin'),
  ('59', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '21', '6', NULL, NULL, 'uas', NULL, '89.00', '100.00', '1.00', '2026-06-11', 'Input oleh Admin'),
  ('60', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '22', '1', NULL, NULL, 'tugas', NULL, '79.00', '100.00', '1.00', '2026-05-05', 'Input oleh Admin'),
  ('61', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '22', '1', NULL, NULL, 'ulangan_harian', NULL, '86.00', '100.00', '1.00', '2026-06-07', 'Input oleh Admin'),
  ('62', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '22', '1', NULL, NULL, 'uts', NULL, '74.00', '100.00', '1.00', '2026-05-25', 'Input oleh Admin'),
  ('63', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '22', '1', NULL, NULL, 'uas', NULL, '95.00', '100.00', '1.00', '2026-05-10', 'Input oleh Admin'),
  ('64', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '22', '6', NULL, NULL, 'tugas', NULL, '97.00', '100.00', '1.00', '2026-06-07', 'Input oleh Admin'),
  ('65', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '22', '6', NULL, NULL, 'ulangan_harian', NULL, '75.00', '100.00', '1.00', '2026-04-30', 'Input oleh Admin'),
  ('66', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '22', '6', NULL, NULL, 'uts', NULL, '77.00', '100.00', '1.00', '2026-06-13', 'Input oleh Admin'),
  ('67', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '22', '6', NULL, NULL, 'uas', NULL, '87.00', '100.00', '1.00', '2026-05-15', 'Input oleh Admin'),
  ('68', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '23', '1', NULL, NULL, 'tugas', NULL, '88.00', '100.00', '1.00', '2026-06-12', 'Input oleh Admin'),
  ('69', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '23', '1', NULL, NULL, 'ulangan_harian', NULL, '88.00', '100.00', '1.00', '2026-06-10', 'Input oleh Admin'),
  ('70', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '23', '1', NULL, NULL, 'uts', NULL, '81.00', '100.00', '1.00', '2026-06-13', 'Input oleh Admin'),
  ('71', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '23', '1', NULL, NULL, 'uas', NULL, '97.00', '100.00', '1.00', '2026-06-08', 'Input oleh Admin'),
  ('72', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '23', '6', NULL, NULL, 'tugas', NULL, '91.00', '100.00', '1.00', '2026-05-18', 'Input oleh Admin'),
  ('73', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '23', '6', NULL, NULL, 'ulangan_harian', NULL, '69.00', '100.00', '1.00', '2026-06-14', 'Input oleh Admin'),
  ('74', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '23', '6', NULL, NULL, 'uts', NULL, '78.00', '100.00', '1.00', '2026-05-30', 'Input oleh Admin'),
  ('75', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '23', '6', NULL, NULL, 'uas', NULL, '87.00', '100.00', '1.00', '2026-05-27', 'Input oleh Admin');

-- ---- Tabel: `guru_mapel` (0 baris) ----
-- (kosong)

-- ---- Tabel: `gurus` (0 baris) ----
-- (kosong)

-- ---- Tabel: `invoices` (35 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `invoices` (`id`, `created_at`, `updated_at`, `siswa_id`, `cabang_id`, `kelas_id`, `nomor_invoice`, `subtotal`, `diskon`, `pajak`, `total`, `deskripsi`, `periode`, `jatuh_tempo`, `status`, `catatan`, `deleted_at`) VALUES
  ('1', '2026-06-22 13:51:52', '2026-06-22 13:51:52', '1', '2', '1', 'INV-2026-06001', '1000000.00', '0.00', '0.00', '1000000.00', 'Registrasi: Matamatika Lanjutan - yastar iskandar', '2026-06', '2026-06-29', 'lunas', NULL, NULL),
  ('2', '2026-06-22 14:01:05', '2026-06-22 14:01:05', '2', '2', '2', 'INV-2026-06002', '1000000.00', '0.00', '0.00', '1000000.00', 'Registrasi (Cicilan 1/2): Matamatika Lanjutan - yastar iskandar', '2026-06', '2026-06-29', 'belum_bayar', NULL, NULL),
  ('3', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '3', '3', 'INV-202606-0001', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('4', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '3', '4', 'INV-202606-0002', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Fisika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('5', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '4', '3', '3', 'INV-202606-0003', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('6', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '4', '3', '5', 'INV-202606-0004', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Kimia - May 2026', '2026-05', '2026-05-31', 'sebagian', NULL, NULL),
  ('7', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '4', '3', '6', 'INV-202606-0005', '300000.00', '0.00', '0.00', '300000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('8', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '5', '3', '7', 'INV-202606-0006', '500000.00', '0.00', '0.00', '500000.00', 'Biaya kursus Persiapan SNBT - May 2026', '2026-05', '2026-05-31', 'belum_bayar', NULL, NULL),
  ('9', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '6', '3', '7', 'INV-202606-0007', '500000.00', '0.00', '0.00', '500000.00', 'Biaya kursus Persiapan SNBT - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('10', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '6', '3', '3', 'INV-202606-0008', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'belum_bayar', NULL, NULL),
  ('11', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '3', '3', 'INV-202606-0009', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('12', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '3', '6', 'INV-202606-0010', '300000.00', '0.00', '0.00', '300000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('13', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '8', '3', '6', 'INV-202606-0011', '300000.00', '0.00', '0.00', '300000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'belum_bayar', NULL, NULL),
  ('14', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '9', '3', '3', 'INV-202606-0012', '350000.00', '0.00', '0.00', '350000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'belum_bayar', NULL, NULL),
  ('15', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '10', '3', '7', 'INV-202606-0013', '500000.00', '0.00', '0.00', '500000.00', 'Biaya kursus Persiapan SNBT - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('16', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '11', '4', '8', 'INV-202606-0014', '300000.00', '0.00', '0.00', '300000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'belum_bayar', NULL, NULL),
  ('17', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '11', '4', '9', 'INV-202606-0015', '275000.00', '0.00', '0.00', '275000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('18', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '12', '4', '8', 'INV-202606-0016', '300000.00', '0.00', '0.00', '300000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'belum_bayar', NULL, NULL),
  ('19', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '13', '4', '9', 'INV-202606-0017', '275000.00', '0.00', '0.00', '275000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('20', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '14', '4', '9', 'INV-202606-0018', '275000.00', '0.00', '0.00', '275000.00', 'Biaya kursus Bahasa Inggris - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('21', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '15', '5', '10', 'INV-202606-0019', '325000.00', '0.00', '0.00', '325000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'belum_bayar', NULL, NULL),
  ('22', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '16', '5', '10', 'INV-202606-0020', '325000.00', '0.00', '0.00', '325000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'belum_bayar', NULL, NULL),
  ('23', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '17', '5', '10', 'INV-202606-0021', '325000.00', '0.00', '0.00', '325000.00', 'Biaya kursus Matematika - May 2026', '2026-05', '2026-05-31', 'lunas', NULL, NULL),
  ('24', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '18', '6', NULL, 'INV-2026-1000', '3500000.00', '0.00', '0.00', '3500000.00', 'Biaya Intensif UTBK Saintek', '2025-01', '2025-01-24', 'lunas', NULL, NULL),
  ('25', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '19', '6', NULL, 'INV-2026-1001', '3500000.00', '0.00', '0.00', '3500000.00', 'Biaya Intensif UTBK Saintek', '2025-01', '2025-01-26', 'sebagian', NULL, NULL),
  ('26', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '20', '4', NULL, 'INV-2026-1002', '3200000.00', '0.00', '0.00', '3200000.00', 'Biaya Intensif UTBK Soshum', '2025-01', '2025-01-22', 'lunas', NULL, NULL),
  ('27', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '21', '4', NULL, 'INV-2026-1003', '3200000.00', '0.00', '0.00', '3200000.00', 'Biaya Intensif UTBK Soshum', '2025-01', '2025-01-29', 'belum_bayar', NULL, NULL),
  ('28', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '22', '5', NULL, 'INV-2026-1004', '2000000.00', '0.00', '0.00', '2000000.00', 'Biaya Reguler SMA Kelas 12', '2025-02', '2025-02-15', 'lunas', NULL, NULL),
  ('29', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '23', '5', NULL, 'INV-2026-1005', '2000000.00', '0.00', '0.00', '2000000.00', 'Biaya Reguler SMA Kelas 12', '2025-02', '2025-02-17', 'lunas', NULL, NULL),
  ('30', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '24', '6', NULL, 'INV-2026-1006', '3500000.00', '0.00', '0.00', '3500000.00', 'Biaya Intensif UTBK Saintek', '2025-02', '2025-02-19', 'belum_bayar', NULL, NULL),
  ('31', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '25', '6', NULL, 'INV-2026-1007', '3500000.00', '0.00', '0.00', '3500000.00', 'Biaya Intensif UTBK Saintek', '2025-02', '2025-02-24', 'lunas', NULL, NULL),
  ('32', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '26', '4', NULL, 'INV-2026-1008', '3200000.00', '0.00', '0.00', '3200000.00', 'Biaya Intensif UTBK Soshum', '2025-02', '2025-02-26', 'sebagian', NULL, NULL),
  ('33', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '27', '5', NULL, 'INV-2026-1009', '2000000.00', '0.00', '0.00', '2000000.00', 'Biaya Reguler SMA Kelas 12', '2025-03', '2025-03-15', 'lunas', NULL, NULL),
  ('34', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '28', '6', NULL, 'INV-2026-1010', '1800000.00', '0.00', '0.00', '1800000.00', 'Biaya Privat Matematika', '2025-03', '2025-03-19', 'lunas', NULL, NULL),
  ('35', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '29', '4', NULL, 'INV-2026-1011', '1500000.00', '0.00', '0.00', '1500000.00', 'Biaya Online English Intensive', '2025-03', '2025-03-22', 'lunas', NULL, NULL);

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

-- ---- Tabel: `migrations` (111 baris) ----
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
  ('97', '2026_06_25_000001_add_mata_pelajaran_id_to_schedules', '2'),
  ('98', '2026_06_25_100001_create_package_course_teachers_table', '2'),
  ('99', '2026_06_25_200000_add_jenis_kursus_to_courses_table', '2'),
  ('100', '2026_06_25_300000_add_registration_fields_to_student_registrations', '2'),
  ('101', '2026_06_26_100810_create_extra_class_requests_table', '3'),
  ('102', '2026_06_26_122923_add_honor_and_address_to_schedules_table', '3'),
  ('103', '2026_06_26_141554_add_total_sesi_to_students_drop_pertemuan_ke_from_schedules', '3'),
  ('104', '2026_06_26_200000_create_rooms_table', '3'),
  ('105', '2026_06_26_210000_create_student_leaves_table', '3'),
  ('106', '2026_06_26_300000_create_curricula_table', '3'),
  ('107', '2026_06_26_400000_create_promos_table', '3'),
  ('108', '2026_06_28_100000_add_interest_sessions_to_student_registrations', '3'),
  ('109', '2026_06_28_110000_add_interest_teachers_to_student_registrations', '3'),
  ('110', '2026_06_29_044521_add_program_belajar_to_schedules_table', '3'),
  ('111', '2026_06_29_120000_add_freelance_fields_to_student_registrations', '3');

-- ---- Tabel: `model_has_permissions` (0 baris) ----
-- (kosong)

-- ---- Tabel: `model_has_roles` (50 baris) ----
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
  ('16', 'App\\Models\\User', '10'),
  ('17', 'App\\Models\\User', '11'),
  ('17', 'App\\Models\\User', '12'),
  ('17', 'App\\Models\\User', '16'),
  ('17', 'App\\Models\\User', '17'),
  ('17', 'App\\Models\\User', '18'),
  ('18', 'App\\Models\\User', '13'),
  ('18', 'App\\Models\\User', '19'),
  ('18', 'App\\Models\\User', '20'),
  ('18', 'App\\Models\\User', '21'),
  ('18', 'App\\Models\\User', '22'),
  ('18', 'App\\Models\\User', '23'),
  ('18', 'App\\Models\\User', '24'),
  ('18', 'App\\Models\\User', '25'),
  ('18', 'App\\Models\\User', '41'),
  ('18', 'App\\Models\\User', '42'),
  ('18', 'App\\Models\\User', '43'),
  ('18', 'App\\Models\\User', '44'),
  ('18', 'App\\Models\\User', '45'),
  ('18', 'App\\Models\\User', '46'),
  ('18', 'App\\Models\\User', '47'),
  ('19', 'App\\Models\\User', '14'),
  ('19', 'App\\Models\\User', '15'),
  ('19', 'App\\Models\\User', '26'),
  ('19', 'App\\Models\\User', '27'),
  ('19', 'App\\Models\\User', '28'),
  ('19', 'App\\Models\\User', '29'),
  ('19', 'App\\Models\\User', '30'),
  ('19', 'App\\Models\\User', '31'),
  ('19', 'App\\Models\\User', '32'),
  ('19', 'App\\Models\\User', '33'),
  ('19', 'App\\Models\\User', '34'),
  ('19', 'App\\Models\\User', '35'),
  ('19', 'App\\Models\\User', '36'),
  ('19', 'App\\Models\\User', '37'),
  ('19', 'App\\Models\\User', '38'),
  ('19', 'App\\Models\\User', '39'),
  ('19', 'App\\Models\\User', '40'),
  ('19', 'App\\Models\\User', '48'),
  ('19', 'App\\Models\\User', '49'),
  ('19', 'App\\Models\\User', '50'),
  ('19', 'App\\Models\\User', '51'),
  ('19', 'App\\Models\\User', '52'),
  ('19', 'App\\Models\\User', '53'),
  ('19', 'App\\Models\\User', '54'),
  ('19', 'App\\Models\\User', '55'),
  ('19', 'App\\Models\\User', '56'),
  ('19', 'App\\Models\\User', '57'),
  ('19', 'App\\Models\\User', '58'),
  ('19', 'App\\Models\\User', '59');

-- ---- Tabel: `modules` (21 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `modules` (`id`, `kode_modul`, `created_at`, `updated_at`, `mata_pelajaran_id`, `diupload_oleh`, `judul`, `deskripsi`, `jenis`, `file_path`, `file_url`, `ukuran_file`, `is_gratis`, `status`, `jumlah_download`, `deleted_at`) VALUES
  ('1', 'MAT-001-M1', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '2', '10', 'Modul 1 - Persamaan Linear', 'Pengantar persamaan linear satu dan dua variabel', 'pdf', NULL, NULL, NULL, '1', 'aktif', '60', NULL),
  ('2', 'MAT-001-M2', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '2', '10', 'Modul 2 - Fungsi Kuadrat', 'Fungsi kuadrat dan grafiknya', 'pdf', NULL, NULL, NULL, '0', 'aktif', '94', NULL),
  ('3', 'MAT-001-M3', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '2', '10', 'Modul 3 - Trigonometri', 'Sudut, sinus, cosinus, tangen dan aplikasinya', 'video', NULL, NULL, NULL, '0', 'aktif', '128', NULL),
  ('4', 'MAT-001-M4', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '2', '10', 'Modul 4 - Statistika', 'Ukuran pemusatan dan penyebaran data', 'pdf', NULL, NULL, NULL, '0', 'aktif', '136', NULL),
  ('5', 'FIS-001-M1', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '10', 'Modul 1 - Kinematika', 'Gerak lurus beraturan dan berubah beraturan', 'pdf', NULL, NULL, NULL, '1', 'aktif', '42', NULL),
  ('6', 'FIS-001-M2', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '10', 'Modul 2 - Dinamika', 'Hukum Newton dan aplikasinya', 'pdf', NULL, NULL, NULL, '0', 'aktif', '149', NULL),
  ('7', 'FIS-001-M3', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '10', 'Modul 3 - Listrik', 'Listrik statis dan dinamis', 'video', NULL, NULL, NULL, '0', 'aktif', '125', NULL),
  ('8', 'ING-001-M1', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '6', '10', 'Module 1 - Grammar Fundamentals', 'Tenses, articles, prepositions', 'pdf', NULL, NULL, NULL, '1', 'aktif', '64', NULL),
  ('9', 'ING-001-M2', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '6', '10', 'Module 2 - Reading Strategies', 'Teknik membaca cepat dan pemahaman', 'pdf', NULL, NULL, NULL, '0', 'aktif', '17', NULL),
  ('10', 'ING-001-M3', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '6', '10', 'Module 3 - Writing Skills', 'Essay, letter, and report writing', 'pdf', NULL, NULL, NULL, '0', 'aktif', '149', NULL),
  ('11', 'SNBT-001-M1', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '10', 'Paket Soal TPS Penalaran Umum', 'Kumpulan soal TPS penalaran umum dengan pembahasan', 'pdf', NULL, NULL, NULL, '0', 'aktif', '118', NULL),
  ('12', 'SNBT-001-M2', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '10', 'Strategi Mengerjakan Soal SNBT', 'Tips dan trik mengerjakan soal UTBK-SNBT', 'video', NULL, NULL, NULL, '1', 'aktif', '23', NULL),
  ('13', 'SNBT-001-M3', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '10', 'Paket Soal Literasi Bahasa', 'Latihan literasi bahasa Indonesia dan Inggris', 'pdf', NULL, NULL, NULL, '0', 'aktif', '73', NULL),
  ('14', 'MTK-001', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '1', NULL, 'Modul Matematika Dasar', 'Dasar aljabar, fungsi, dan trigonometri', 'pdf', NULL, NULL, NULL, '1', 'aktif', '0', NULL),
  ('15', 'MTK-002', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '1', NULL, 'Modul Matematika Lanjut', 'Kalkulus, statistika, dan geometri analitik', 'pdf', NULL, NULL, NULL, '1', 'aktif', '0', NULL),
  ('16', 'FIS-001', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '3', NULL, 'Modul Fisika Mekanika', 'Kinematika, dinamika, dan hukum Newton', 'pdf', NULL, NULL, NULL, '1', 'aktif', '0', NULL),
  ('17', 'KIM-001', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '4', NULL, 'Modul Kimia Organik', 'Senyawa organik dan reaksi kimia', 'pdf', NULL, NULL, NULL, '1', 'aktif', '0', NULL),
  ('18', 'BIO-001', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '5', NULL, 'Modul Biologi Sel', 'Struktur sel, organel, dan metabolisme', 'pdf', NULL, NULL, NULL, '1', 'aktif', '0', NULL),
  ('19', 'ENG-001', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '6', NULL, 'Modul Grammar Bahasa Inggris', 'Tata bahasa dan struktur kalimat', 'pdf', NULL, NULL, NULL, '1', 'aktif', '0', NULL),
  ('20', 'IND-001', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '13', NULL, 'Modul Teks Bahasa Indonesia', 'Jenis teks dan teknik menulis', 'pdf', NULL, NULL, NULL, '1', 'aktif', '0', NULL),
  ('21', 'EKO-001', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '14', NULL, 'Modul Ekonomi Makro', 'Ekonomi nasional dan kebijakan fiskal', 'pdf', NULL, NULL, NULL, '1', 'aktif', '0', NULL);

-- ---- Tabel: `moduls` (0 baris) ----
-- (kosong)

-- ---- Tabel: `nilais` (0 baris) ----
-- (kosong)

-- ---- Tabel: `package_course_teachers` (16 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `package_course_teachers` (`id`, `package_id`, `course_id`, `teacher_id`, `created_at`, `updated_at`) VALUES
  ('1', '7', '1', '9', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('2', '7', '3', '9', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('3', '7', '4', '10', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('4', '7', '5', '10', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('5', '8', '13', '12', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('6', '8', '6', '11', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('7', '8', '14', '13', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('8', '8', '15', '13', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('9', '8', '16', '12', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('10', '8', '17', '14', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('11', '9', '1', '9', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('12', '10', '6', '11', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('13', '11', '1', '15', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('14', '11', '13', '12', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('15', '11', '6', '11', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('16', '11', '14', '13', '2026-06-25 20:29:41', '2026-06-25 20:29:41');

-- ---- Tabel: `packages` (11 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `packages` (`id`, `created_at`, `updated_at`, `cabang_id`, `guru_id`, `nama`, `deskripsi`, `harga`, `durasi_bulan`, `jumlah_pertemuan`, `jenis`, `metode_absensi`, `tipe_kelas`, `fitur`, `is_unggulan`, `status`, `deleted_at`) VALUES
  ('1', '2026-06-22 13:50:53', '2026-06-22 13:50:53', '2', NULL, 'Matamatika Lanjutan', 'Belajar menghitung', '1000000.00', '1', '10', 'reguler', 'otomatis', 'online', NULL, '0', 'aktif', NULL),
  ('2', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '3', NULL, 'Paket Reguler SMA', 'Paket bimbingan belajar reguler untuk siswa SMA, 2x pertemuan per minggu.', '750000.00', '1', '8', 'reguler', 'dual', 'offline', '[\"8 pertemuan\\/bulan\",\"Modul digital\",\"Evaluasi bulanan\",\"Konsultasi gratis\"]', '0', 'aktif', NULL),
  ('3', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '3', NULL, 'Paket Intensif SNBT', 'Program intensif persiapan SNBT selama 3 bulan dengan tryout rutin.', '2500000.00', '3', '36', 'intensif', 'dual', 'offline', '[\"36 pertemuan\",\"Tryout mingguan\",\"Analisis hasil\",\"Mentor pribadi\",\"Modul eksklusif\"]', '1', 'aktif', NULL),
  ('4', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '3', NULL, 'Paket Online Basic', 'Belajar online fleksibel, cocok untuk siswa yang sibuk atau jauh dari cabang.', '500000.00', '1', '8', 'online', 'self', 'online', '[\"8 sesi online\",\"Rekaman kelas\",\"Modul digital\",\"Forum diskusi\"]', '0', 'aktif', NULL),
  ('5', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '4', NULL, 'Paket Reguler Bandung', 'Paket bimbel reguler untuk siswa di cabang Bandung.', '700000.00', '1', '8', 'reguler', 'dual', 'offline', '[\"8 pertemuan\\/bulan\",\"Modul belajar\",\"Evaluasi bulanan\"]', '0', 'aktif', NULL),
  ('6', '2026-06-25 12:45:01', '2026-06-25 12:45:01', '5', NULL, 'Paket Reguler Surabaya', 'Paket bimbel reguler untuk siswa di cabang Surabaya.', '725000.00', '1', '8', 'reguler', 'dual', 'offline', '[\"8 pertemuan\\/bulan\",\"Modul belajar\",\"Evaluasi bulanan\"]', '0', 'aktif', NULL),
  ('7', '2026-06-25 20:29:41', '2026-06-25 20:29:41', '6', NULL, 'Intensif UTBK Saintek', 'Program intensif persiapan UTBK khusus jurusan Saintek. Materi lengkap + tryout.', '3500000.00', '3', '24', 'intensif', 'manual', 'offline', NULL, '1', 'aktif', NULL),
  ('8', '2026-06-25 20:29:41', '2026-06-25 20:29:41', '4', NULL, 'Intensif UTBK Soshum', 'Program intensif persiapan UTBK jurusan Soshum. Termasuk materi TPS.', '3200000.00', '3', '20', 'intensif', 'manual', 'offline', NULL, '1', 'aktif', NULL),
  ('9', '2026-06-25 20:29:41', '2026-06-25 20:29:41', '6', NULL, 'Privat Matematika', 'Les privat matematika 1-on-1 dengan guru berpengalaman.', '1800000.00', '1', '8', 'privat', 'manual', 'private', NULL, '0', 'aktif', NULL),
  ('10', '2026-06-25 20:29:41', '2026-06-25 20:29:41', NULL, NULL, 'Online English Intensive', 'Program intensif Bahasa Inggris online. Belajar dari rumah.', '1500000.00', '2', '12', 'online', 'otomatis', 'online', NULL, '0', 'aktif', NULL),
  ('11', '2026-06-25 20:29:41', '2026-06-25 20:29:41', '5', NULL, 'Reguler SMA Kelas 12', 'Program reguler untuk siswa kelas 12 SMA. Semua mapel UN.', '2000000.00', '2', '16', 'reguler', 'manual', 'offline', NULL, '0', 'aktif', NULL);

-- ---- Tabel: `pakets` (0 baris) ----
-- (kosong)

-- ---- Tabel: `password_resets` (0 baris) ----
-- (kosong)

-- ---- Tabel: `payments` (31 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `payments` (`id`, `created_at`, `updated_at`, `invoice_id`, `siswa_id`, `cabang_id`, `nomor_pembayaran`, `jumlah`, `metode`, `nama_bank`, `nomor_rekening`, `bukti_pembayaran`, `tanggal_pembayaran`, `status`, `alasan_penolakan`, `catatan`, `disetujui_oleh`, `tanggal_disetujui`, `deleted_at`) VALUES
  ('1', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '3', '3', 'PAY-INV-202606-0001', '350000.00', 'qris', NULL, NULL, NULL, '2026-06-03', 'verified', NULL, NULL, '16', '2026-06-02 00:00:00', NULL),
  ('2', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '4', '3', '3', 'PAY-INV-202606-0002', '350000.00', 'qris', NULL, NULL, NULL, '2026-06-01', 'verified', NULL, NULL, '16', '2026-06-02 00:00:00', NULL),
  ('3', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '5', '4', '3', 'PAY-INV-202606-0003', '350000.00', 'transfer', NULL, NULL, NULL, '2026-05-30', 'verified', NULL, NULL, '16', '2026-06-01 00:00:00', NULL),
  ('4', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '6', '4', '3', 'PAY-INV-202606-0004', '175000.00', 'qris', NULL, NULL, NULL, '2026-06-01', 'verified', NULL, NULL, '16', '2026-05-31 00:00:00', NULL),
  ('5', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '7', '4', '3', 'PAY-INV-202606-0005', '300000.00', 'cash', NULL, NULL, NULL, '2026-06-02', 'verified', NULL, NULL, '16', '2026-06-03 00:00:00', NULL),
  ('6', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '9', '6', '3', 'PAY-INV-202606-0007', '500000.00', 'qris', NULL, NULL, NULL, '2026-06-01', 'verified', NULL, NULL, '16', '2026-06-01 00:00:00', NULL),
  ('7', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '11', '7', '3', 'PAY-INV-202606-0009', '350000.00', 'qris', NULL, NULL, NULL, '2026-05-26', 'verified', NULL, NULL, '16', '2026-06-05 00:00:00', NULL),
  ('8', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '12', '7', '3', 'PAY-INV-202606-0010', '300000.00', 'cash', NULL, NULL, NULL, '2026-05-29', 'verified', NULL, NULL, '16', '2026-06-01 00:00:00', NULL),
  ('9', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '15', '10', '3', 'PAY-INV-202606-0013', '500000.00', 'cash', NULL, NULL, NULL, '2026-05-30', 'verified', NULL, NULL, '16', '2026-05-30 00:00:00', NULL),
  ('10', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '17', '11', '4', 'PAY-INV-202606-0015', '275000.00', 'cash', NULL, NULL, NULL, '2026-06-04', 'verified', NULL, NULL, '16', '2026-06-02 00:00:00', NULL),
  ('11', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '19', '13', '4', 'PAY-INV-202606-0017', '275000.00', 'qris', NULL, NULL, NULL, '2026-05-28', 'verified', NULL, NULL, '16', '2026-05-30 00:00:00', NULL),
  ('12', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '20', '14', '4', 'PAY-INV-202606-0018', '275000.00', 'cash', NULL, NULL, NULL, '2026-06-01', 'verified', NULL, NULL, '16', '2026-05-29 00:00:00', NULL),
  ('13', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '23', '17', '5', 'PAY-INV-202606-0021', '325000.00', 'cash', NULL, NULL, NULL, '2026-05-27', 'verified', NULL, NULL, '16', '2026-05-29 00:00:00', NULL),
  ('14', '2026-06-25 20:29:40', '2026-06-25 20:29:40', '8', '5', '3', 'PAY-INV-202606-0006', '500000.00', 'qris', NULL, NULL, NULL, '2026-06-04', 'verified', NULL, NULL, '16', '2026-05-26 00:00:00', NULL),
  ('15', '2026-06-25 20:29:40', '2026-06-25 20:29:40', '10', '6', '3', 'PAY-INV-202606-0008', '350000.00', 'transfer', NULL, NULL, NULL, '2026-05-30', 'verified', NULL, NULL, '16', '2026-06-04 00:00:00', NULL),
  ('16', '2026-06-25 20:29:40', '2026-06-25 20:29:40', '14', '9', '3', 'PAY-INV-202606-0012', '175000.00', 'qris', NULL, NULL, NULL, '2026-06-03', 'verified', NULL, NULL, '16', '2026-05-28 00:00:00', NULL),
  ('17', '2026-06-25 20:29:40', '2026-06-25 20:29:40', '16', '11', '4', 'PAY-INV-202606-0014', '150000.00', 'qris', NULL, NULL, NULL, '2026-05-26', 'verified', NULL, NULL, '16', '2026-05-28 00:00:00', NULL),
  ('18', '2026-06-25 20:29:40', '2026-06-25 20:29:40', '18', '12', '4', 'PAY-INV-202606-0016', '300000.00', 'cash', NULL, NULL, NULL, '2026-05-30', 'verified', NULL, NULL, '16', '2026-05-27 00:00:00', NULL),
  ('19', '2026-06-25 20:29:40', '2026-06-25 20:29:40', '21', '15', '5', 'PAY-INV-202606-0019', '325000.00', 'qris', NULL, NULL, NULL, '2026-05-26', 'verified', NULL, NULL, '16', '2026-05-30 00:00:00', NULL),
  ('20', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '24', '18', '6', NULL, '3500000.00', 'transfer', NULL, NULL, NULL, '2025-01-12', 'verified', NULL, NULL, NULL, NULL, NULL),
  ('21', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '25', '19', '6', NULL, '1750000.00', 'cash', NULL, NULL, NULL, '2025-01-14', 'verified', NULL, NULL, NULL, NULL, NULL),
  ('22', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '26', '20', '4', NULL, '3200000.00', 'cash', NULL, NULL, NULL, '2025-01-10', 'verified', NULL, NULL, NULL, NULL, NULL),
  ('23', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '28', '22', '5', NULL, '2000000.00', 'cash', NULL, NULL, NULL, '2025-02-03', 'verified', NULL, NULL, NULL, NULL, NULL),
  ('24', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '29', '23', '5', NULL, '2000000.00', 'qris', NULL, NULL, NULL, '2025-02-05', 'verified', NULL, NULL, NULL, NULL, NULL),
  ('25', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '31', '25', '6', NULL, '3500000.00', 'qris', NULL, NULL, NULL, '2025-02-12', 'verified', NULL, NULL, NULL, NULL, NULL),
  ('26', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '32', '26', '4', NULL, '1600000.00', 'qris', NULL, NULL, NULL, '2025-02-14', 'verified', NULL, NULL, NULL, NULL, NULL),
  ('27', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '33', '27', '5', NULL, '2000000.00', 'transfer', NULL, NULL, NULL, '2025-03-03', 'verified', NULL, NULL, NULL, NULL, NULL),
  ('28', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '34', '28', '6', NULL, '1800000.00', 'cash', NULL, NULL, NULL, '2025-03-07', 'verified', NULL, NULL, NULL, NULL, NULL),
  ('29', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '35', '29', '4', NULL, '1500000.00', 'transfer', NULL, NULL, NULL, '2025-03-10', 'verified', NULL, NULL, NULL, NULL, NULL),
  ('30', '2026-06-30 13:30:41', '2026-06-30 13:30:41', '13', '8', '3', 'PAY-INV-202606-0011', '300000.00', 'qris', NULL, NULL, NULL, '2026-05-31', 'verified', NULL, NULL, '16', '2026-06-03 00:00:00', NULL),
  ('31', '2026-06-30 13:30:41', '2026-06-30 13:30:41', '22', '16', '5', 'PAY-INV-202606-0020', '325000.00', 'transfer', NULL, NULL, NULL, '2026-06-01', 'verified', NULL, NULL, '16', '2026-06-01 00:00:00', NULL);

-- ---- Tabel: `pembayarans` (0 baris) ----
-- (kosong)

-- ---- Tabel: `permissions` (38 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
  ('115', 'branch.view', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('116', 'branch.create', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('117', 'branch.edit', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('118', 'branch.delete', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('119', 'student.view', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('120', 'student.create', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('121', 'student.edit', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('122', 'student.delete', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('123', 'teacher.view', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('124', 'teacher.create', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('125', 'teacher.edit', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('126', 'teacher.delete', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('127', 'employee.view', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('128', 'employee.create', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('129', 'employee.edit', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('130', 'employee.delete', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('131', 'schedule.view', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('132', 'schedule.create', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('133', 'schedule.edit', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('134', 'schedule.delete', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('135', 'payment.view', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('136', 'payment.create', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('137', 'payment.edit', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('138', 'payment.approve', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('139', 'tryout.view', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('140', 'tryout.create', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('141', 'tryout.edit', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('142', 'tryout.delete', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('143', 'report.view', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('144', 'report.export', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('145', 'setting.view', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('146', 'setting.edit', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('147', 'salary.view', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('148', 'salary.create', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('149', 'salary.edit', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('150', 'certificate.view', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('151', 'certificate.create', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10'),
  ('152', 'certificate.download', 'web', '2026-06-22 13:46:10', '2026-06-22 13:46:10');

-- ---- Tabel: `personal_access_tokens` (0 baris) ----
-- (kosong)

-- ---- Tabel: `promos` (0 baris) ----
-- (kosong)

-- ---- Tabel: `questions` (19 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `questions` (`id`, `created_at`, `updated_at`, `tryout_id`, `teks_pertanyaan`, `gambar_pertanyaan`, `jenis`, `pilihan_jawaban`, `kunci_jawaban`, `penjelasan`, `poin`, `urutan`, `tingkat_kesulitan`) VALUES
  ('1', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '1', 'Jika f(x) = 2x² - 3x + 1, maka nilai f(2) adalah...', NULL, 'pilihan_ganda', '{\"A\":\"3\",\"B\":\"5\",\"C\":\"7\",\"D\":\"1\",\"E\":\"4\"}', 'A', NULL, '10.00', '1', 'sedang'),
  ('2', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '1', 'Nilai dari sin 30° + cos 60° adalah...', NULL, 'pilihan_ganda', '{\"A\":\"0\",\"B\":\"1\",\"C\":\"\\u221a2\",\"D\":\"2\",\"E\":\"\\u00bd\"}', 'B', NULL, '10.00', '2', 'mudah'),
  ('3', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '1', 'Sebuah persegi panjang memiliki panjang 12 cm dan lebar 8 cm. Luas persegi panjang tersebut adalah...', NULL, 'pilihan_ganda', '{\"A\":\"40 cm\\u00b2\",\"B\":\"96 cm\\u00b2\",\"C\":\"80 cm\\u00b2\",\"D\":\"48 cm\\u00b2\",\"E\":\"120 cm\\u00b2\"}', 'B', NULL, '10.00', '3', 'mudah'),
  ('4', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '1', 'Jika log 2 = 0,301, maka log 8 adalah...', NULL, 'pilihan_ganda', '{\"A\":\"0,602\",\"B\":\"0,903\",\"C\":\"0,800\",\"D\":\"1,204\",\"E\":\"2,401\"}', 'B', NULL, '15.00', '4', 'sedang'),
  ('5', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '1', 'Himpunan penyelesaian dari |2x - 3| < 5 adalah...', NULL, 'pilihan_ganda', '{\"A\":\"-1 < x < 4\",\"B\":\"x < -1 atau x > 4\",\"C\":\"-4 < x < 1\",\"D\":\"0 < x < 5\",\"E\":\"-2 < x < 4\"}', 'A', NULL, '15.00', '5', 'sulit'),
  ('6', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '2', 'Diskriminan dari persamaan x² - 5x + 6 = 0 adalah...', NULL, 'pilihan_ganda', '{\"A\":\"1\",\"B\":\"-1\",\"C\":\"4\",\"D\":\"25\",\"E\":\"0\"}', 'A', NULL, '20.00', '1', 'mudah'),
  ('7', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '2', 'Akar-akar persamaan 2x² - 7x + 3 = 0 adalah...', NULL, 'pilihan_ganda', '{\"A\":\"3 dan \\u00bd\",\"B\":\"-3 dan \\u00bd\",\"C\":\"3 dan -\\u00bd\",\"D\":\"1 dan 3\",\"E\":\"-1 dan -3\"}', 'A', NULL, '20.00', '2', 'sedang'),
  ('8', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '2', 'Nilai maksimum dari f(x) = -x² + 4x + 5 adalah...', NULL, 'pilihan_ganda', '{\"A\":\"5\",\"B\":\"7\",\"C\":\"9\",\"D\":\"11\",\"E\":\"4\"}', 'C', NULL, '20.00', '3', 'sedang'),
  ('9', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '2', 'tan 45° × cot 45° = ...', NULL, 'pilihan_ganda', '{\"A\":\"0\",\"B\":\"\\u221a2\",\"C\":\"2\",\"D\":\"1\",\"E\":\"\\u00bd\"}', 'D', NULL, '20.00', '4', 'mudah'),
  ('10', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '2', 'Jika sin α = 3/5 dan α di kuadran I, maka cos α = ...', NULL, 'pilihan_ganda', '{\"A\":\"4\\/5\",\"B\":\"3\\/4\",\"C\":\"5\\/4\",\"D\":\"5\\/3\",\"E\":\"4\\/3\"}', 'A', NULL, '20.00', '5', 'sedang'),
  ('11', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '3', 'Soal nomor 1 dari Tryout UTBK Saintek Maret 2025. Ini adalah contoh soal latihan.', NULL, 'pilihan_ganda', '{\"A\":\"Jawaban A\",\"B\":\"Jawaban B\",\"C\":\"Jawaban C\",\"D\":\"Jawaban D\",\"E\":\"Jawaban E\"}', 'A', 'Jawaban yang benar adalah A.', '1.00', '1', 'sedang'),
  ('12', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '3', 'Soal nomor 2 dari Tryout UTBK Saintek Maret 2025. Ini adalah contoh soal latihan.', NULL, 'pilihan_ganda', '{\"A\":\"Jawaban A\",\"B\":\"Jawaban B\",\"C\":\"Jawaban C\",\"D\":\"Jawaban D\",\"E\":\"Jawaban E\"}', 'A', 'Jawaban yang benar adalah A.', '1.00', '2', 'sedang'),
  ('13', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '3', 'Soal nomor 3 dari Tryout UTBK Saintek Maret 2025. Ini adalah contoh soal latihan.', NULL, 'pilihan_ganda', '{\"A\":\"Jawaban A\",\"B\":\"Jawaban B\",\"C\":\"Jawaban C\",\"D\":\"Jawaban D\",\"E\":\"Jawaban E\"}', 'A', 'Jawaban yang benar adalah A.', '1.00', '3', 'sedang'),
  ('14', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '4', 'Soal nomor 1 dari Tryout Fisika April 2025. Ini adalah contoh soal latihan.', NULL, 'pilihan_ganda', '{\"A\":\"Jawaban A\",\"B\":\"Jawaban B\",\"C\":\"Jawaban C\",\"D\":\"Jawaban D\",\"E\":\"Jawaban E\"}', 'A', 'Jawaban yang benar adalah A.', '1.00', '1', 'sedang'),
  ('15', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '4', 'Soal nomor 2 dari Tryout Fisika April 2025. Ini adalah contoh soal latihan.', NULL, 'pilihan_ganda', '{\"A\":\"Jawaban A\",\"B\":\"Jawaban B\",\"C\":\"Jawaban C\",\"D\":\"Jawaban D\",\"E\":\"Jawaban E\"}', 'A', 'Jawaban yang benar adalah A.', '1.00', '2', 'sedang'),
  ('16', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '4', 'Soal nomor 3 dari Tryout Fisika April 2025. Ini adalah contoh soal latihan.', NULL, 'pilihan_ganda', '{\"A\":\"Jawaban A\",\"B\":\"Jawaban B\",\"C\":\"Jawaban C\",\"D\":\"Jawaban D\",\"E\":\"Jawaban E\"}', 'A', 'Jawaban yang benar adalah A.', '1.00', '3', 'sedang'),
  ('17', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '5', 'Soal nomor 1 dari Tryout Bahasa Inggris UTBK. Ini adalah contoh soal latihan.', NULL, 'pilihan_ganda', '{\"A\":\"Jawaban A\",\"B\":\"Jawaban B\",\"C\":\"Jawaban C\",\"D\":\"Jawaban D\",\"E\":\"Jawaban E\"}', 'A', 'Jawaban yang benar adalah A.', '1.00', '1', 'sedang'),
  ('18', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '5', 'Soal nomor 2 dari Tryout Bahasa Inggris UTBK. Ini adalah contoh soal latihan.', NULL, 'pilihan_ganda', '{\"A\":\"Jawaban A\",\"B\":\"Jawaban B\",\"C\":\"Jawaban C\",\"D\":\"Jawaban D\",\"E\":\"Jawaban E\"}', 'A', 'Jawaban yang benar adalah A.', '1.00', '2', 'sedang'),
  ('19', '2026-06-25 20:29:44', '2026-06-25 20:29:44', '5', 'Soal nomor 3 dari Tryout Bahasa Inggris UTBK. Ini adalah contoh soal latihan.', NULL, 'pilihan_ganda', '{\"A\":\"Jawaban A\",\"B\":\"Jawaban B\",\"C\":\"Jawaban C\",\"D\":\"Jawaban D\",\"E\":\"Jawaban E\"}', 'A', 'Jawaban yang benar adalah A.', '1.00', '3', 'sedang');

-- ---- Tabel: `role_has_permissions` (75 baris) ----
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
  ('115', '16'),
  ('116', '16'),
  ('117', '16'),
  ('118', '16'),
  ('119', '16'),
  ('119', '17'),
  ('119', '18'),
  ('120', '16'),
  ('120', '17'),
  ('121', '16'),
  ('121', '17'),
  ('122', '16'),
  ('122', '17'),
  ('123', '16'),
  ('123', '17'),
  ('124', '16'),
  ('124', '17'),
  ('125', '16'),
  ('125', '17'),
  ('126', '16'),
  ('127', '16'),
  ('127', '17'),
  ('128', '16'),
  ('128', '17'),
  ('129', '16'),
  ('129', '17'),
  ('130', '16'),
  ('131', '16'),
  ('131', '17'),
  ('131', '18'),
  ('131', '19'),
  ('131', '20'),
  ('132', '16'),
  ('132', '17'),
  ('133', '16'),
  ('133', '17'),
  ('134', '16'),
  ('134', '17'),
  ('135', '16'),
  ('135', '17'),
  ('135', '19'),
  ('136', '16'),
  ('136', '17'),
  ('137', '16'),
  ('138', '16'),
  ('138', '17'),
  ('139', '16'),
  ('139', '17'),
  ('139', '19'),
  ('140', '16'),
  ('140', '17'),
  ('141', '16'),
  ('141', '17'),
  ('142', '16'),
  ('143', '16'),
  ('143', '17'),
  ('144', '16'),
  ('144', '17'),
  ('145', '16'),
  ('146', '16'),
  ('147', '16'),
  ('147', '17'),
  ('147', '18'),
  ('147', '20'),
  ('148', '16'),
  ('148', '17'),
  ('149', '16'),
  ('150', '16'),
  ('150', '17'),
  ('151', '16'),
  ('151', '17'),
  ('152', '16'),
  ('152', '17'),
  ('152', '18'),
  ('152', '19');

-- ---- Tabel: `roles` (5 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
  ('16', 'owner', 'web', '2026-06-22 13:46:11', '2026-06-22 13:46:11'),
  ('17', 'admin', 'web', '2026-06-22 13:46:11', '2026-06-22 13:46:11'),
  ('18', 'guru', 'web', '2026-06-22 13:46:11', '2026-06-22 13:46:11'),
  ('19', 'siswa', 'web', '2026-06-22 13:46:11', '2026-06-22 13:46:11'),
  ('20', 'karyawan', 'web', '2026-06-22 13:46:11', '2026-06-22 13:46:11');

-- ---- Tabel: `rooms` (0 baris) ----
-- (kosong)

-- ---- Tabel: `salaries` (20 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `salaries` (`id`, `created_at`, `updated_at`, `guru_id`, `cabang_id`, `periode`, `tipe_gaji`, `gaji_pokok`, `jam_mengajar`, `tarif_per_jam`, `total_gaji_mengajar`, `bonus`, `potongan`, `total_gaji`, `metode_pembayaran`, `nama_bank`, `nomor_rekening`, `tanggal_pembayaran`, `status`, `catatan`, `bukti_pembayaran`, `dibayar_oleh`, `deleted_at`) VALUES
  ('1', '2026-06-25 12:45:06', '2026-06-25 12:45:06', '2', '3', '2026-05', 'bulanan', '4500000.00', '32.0', '100000.00', '3200000.00', '0.00', '0.00', '7700000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('2', '2026-06-25 12:45:06', '2026-06-25 12:45:06', '3', '3', '2026-05', 'bulanan', '5000000.00', '24.0', '110000.00', '2640000.00', '0.00', '0.00', '7640000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('3', '2026-06-25 12:45:06', '2026-06-25 12:45:06', '4', '3', '2026-05', 'bulanan', '4000000.00', '24.0', '90000.00', '2160000.00', '0.00', '0.00', '6160000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('4', '2026-06-25 12:45:06', '2026-06-25 12:45:06', '5', '3', '2026-05', 'bulanan', '4500000.00', '40.0', '100000.00', '4000000.00', '0.00', '0.00', '8500000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('5', '2026-06-25 12:45:06', '2026-06-25 12:45:06', '6', '4', '2026-05', 'bulanan', '4000000.00', '24.0', '90000.00', '2160000.00', '0.00', '0.00', '6160000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('6', '2026-06-25 12:45:06', '2026-06-25 12:45:06', '7', '4', '2026-05', 'bulanan', '3800000.00', '16.0', '85000.00', '1360000.00', '0.00', '0.00', '5160000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('7', '2026-06-25 12:45:06', '2026-06-25 12:45:06', '8', '5', '2026-05', 'bulanan', '4800000.00', '24.0', '95000.00', '2280000.00', '0.00', '0.00', '7080000.00', 'transfer', 'BCA', NULL, '2026-05-31', 'dibayar', NULL, NULL, NULL, NULL),
  ('8', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '9', '6', '2025-01', 'bulanan', '5000000.00', '37.0', '0.00', '0.00', '500000.00', '0.00', '5500000.00', 'Transfer Bank', 'BCA', NULL, '2025-01-28', 'dibayar', NULL, NULL, NULL, NULL),
  ('9', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '9', '6', '2025-02', 'bulanan', '5000000.00', '24.0', '0.00', '0.00', '300000.00', '0.00', '5300000.00', 'Transfer Bank', 'BCA', NULL, '2025-02-28', 'dibayar', NULL, NULL, NULL, NULL),
  ('10', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '9', '6', '2025-03', 'bulanan', '5000000.00', '20.0', '0.00', '0.00', '0.00', '0.00', '5000000.00', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL),
  ('11', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '10', '6', '2025-01', 'bulanan', '5000000.00', '21.0', '0.00', '0.00', '400000.00', '0.00', '5400000.00', 'Transfer Bank', 'BCA', NULL, '2025-01-28', 'dibayar', NULL, NULL, NULL, NULL),
  ('12', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '10', '6', '2025-02', 'bulanan', '5000000.00', '32.0', '0.00', '0.00', '200000.00', '0.00', '5200000.00', 'Transfer Bank', 'BCA', NULL, '2025-02-28', 'dibayar', NULL, NULL, NULL, NULL),
  ('13', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '11', '4', '2025-01', 'bulanan', '3500000.00', '28.0', '0.00', '0.00', '0.00', '0.00', '3500000.00', 'Transfer Bank', 'BCA', NULL, '2025-01-28', 'dibayar', NULL, NULL, NULL, NULL),
  ('14', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '11', '4', '2025-02', 'bulanan', '3500000.00', '25.0', '0.00', '0.00', '150000.00', '0.00', '3650000.00', 'Transfer Bank', 'BCA', NULL, '2025-02-28', 'dibayar', NULL, NULL, NULL, NULL),
  ('15', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '12', '4', '2025-01', 'bulanan', '4500000.00', '35.0', '0.00', '0.00', '350000.00', '0.00', '4850000.00', 'Transfer Bank', 'BCA', NULL, '2025-01-28', 'dibayar', NULL, NULL, NULL, NULL),
  ('16', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '12', '4', '2025-02', 'bulanan', '4500000.00', '26.0', '0.00', '0.00', '0.00', '0.00', '4500000.00', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL),
  ('17', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '13', '5', '2025-01', 'freelance', '2500000.00', '20.0', '75000.00', '0.00', '200000.00', '0.00', '2700000.00', 'Transfer Bank', 'BCA', NULL, '2025-01-28', 'dibayar', NULL, NULL, NULL, NULL),
  ('18', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '14', '5', '2025-01', 'bulanan', '4000000.00', '26.0', '0.00', '0.00', '0.00', '0.00', '4000000.00', 'Transfer Bank', 'BCA', NULL, '2025-01-28', 'dibayar', NULL, NULL, NULL, NULL),
  ('19', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '15', '6', '2025-01', 'freelance', '1500000.00', '30.0', '75000.00', '0.00', '100000.00', '0.00', '1600000.00', 'Transfer Bank', 'BCA', NULL, '2025-01-28', 'dibayar', NULL, NULL, NULL, NULL),
  ('20', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '15', '6', '2025-02', 'freelance', '1800000.00', '29.0', '75000.00', '0.00', '0.00', '0.00', '1800000.00', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL);

-- ---- Tabel: `schedule_proposal_approvals` (0 baris) ----
-- (kosong)

-- ---- Tabel: `schedule_proposals` (0 baris) ----
-- (kosong)

-- ---- Tabel: `schedule_student_agreements` (0 baris) ----
-- (kosong)

-- ---- Tabel: `schedules` (3 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `schedules` (`id`, `created_at`, `updated_at`, `kelas_id`, `paket_id`, `module_id`, `guru_id`, `cabang_id`, `tanggal`, `tanggal_selesai`, `jam_mulai`, `jam_selesai`, `topik`, `jenis`, `program_belajar`, `ruangan`, `link_meeting`, `status`, `catatan`, `honor_per_sesi`, `alamat_kunjungan`, `reminder_terkirim`, `deleted_at`, `mata_pelajaran_id`) VALUES
  ('1', '2026-06-24 16:24:20', '2026-06-24 16:24:20', NULL, '1', NULL, '1', '2', '2026-06-24', NULL, '16:24:00', '16:28:00', NULL, 'offline', NULL, NULL, NULL, 'dijadwalkan', NULL, NULL, NULL, '0', NULL, NULL),
  ('2', '2026-06-25 00:09:48', '2026-06-25 00:09:48', NULL, '1', NULL, '1', '2', '2026-06-25', NULL, '00:10:00', '00:15:00', NULL, 'offline', NULL, NULL, NULL, 'dijadwalkan', NULL, NULL, NULL, '0', NULL, NULL),
  ('3', '2026-06-25 13:17:12', '2026-06-25 13:17:12', '6', '3', NULL, '2', '3', '2026-06-25', NULL, '13:17:00', '13:21:00', NULL, 'offline', NULL, NULL, NULL, 'dijadwalkan', NULL, NULL, NULL, '0', NULL, NULL);

-- ---- Tabel: `school_classes` (13 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `school_classes` (`id`, `created_at`, `updated_at`, `cabang_id`, `mata_pelajaran_id`, `guru_id`, `tahun_akademik_id`, `nama`, `nama_kelas`, `kapasitas`, `jumlah_pertemuan`, `jenis`, `link_zoom`, `status`, `billing_mode`, `deleted_at`) VALUES
  ('1', '2026-06-22 13:51:52', '2026-06-22 13:51:52', '2', NULL, '1', NULL, NULL, 'Matamatika Lanjutan - yastar iskandar', '15', '8', 'online', NULL, 'aktif', 'prepaid', NULL),
  ('2', '2026-06-22 14:01:05', '2026-06-22 14:01:05', '2', NULL, '1', NULL, NULL, 'Matamatika Lanjutan - yastar iskandar', '15', '8', 'online', NULL, 'aktif', 'cicilan', NULL),
  ('3', '2026-06-25 12:45:02', '2026-06-25 12:45:02', '3', '2', '2', '1', NULL, 'Matematika SMA Reguler A', '15', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('4', '2026-06-25 12:45:02', '2026-06-25 12:45:02', '3', '3', '2', '1', NULL, 'Fisika SMA Reguler A', '12', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('5', '2026-06-25 12:45:02', '2026-06-25 12:45:02', '3', '4', '3', '1', NULL, 'Kimia SMA Reguler A', '12', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('6', '2026-06-25 12:45:02', '2026-06-25 12:45:02', '3', '6', '4', '1', NULL, 'Bahasa Inggris SMA Reguler', '15', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('7', '2026-06-25 12:45:02', '2026-06-25 12:45:02', '3', '7', '5', '1', NULL, 'Intensif SNBT Batch 1', '20', '36', 'offline', NULL, 'aktif', 'per_paket', NULL),
  ('8', '2026-06-25 12:45:02', '2026-06-25 12:45:02', '4', '8', '6', '1', NULL, 'Matematika Reguler Bandung A', '12', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('9', '2026-06-25 12:45:02', '2026-06-25 12:45:02', '4', '9', '7', '1', NULL, 'Bahasa Inggris Bandung A', '10', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('10', '2026-06-25 12:45:02', '2026-06-25 12:45:02', '5', '11', '8', '1', NULL, 'Matematika Reguler Surabaya A', '12', '8', 'offline', NULL, 'aktif', 'per_kelas', NULL),
  ('11', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '6', '1', '9', NULL, NULL, 'Saintek A - Pusat', '30', '1', 'offline', NULL, 'aktif', 'prepaid', NULL),
  ('12', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '4', '13', '12', NULL, NULL, 'Soshum A - Bandung', '30', '1', 'offline', NULL, 'aktif', 'prepaid', NULL),
  ('13', '2026-06-25 20:29:43', '2026-06-25 20:29:43', '5', '1', '15', NULL, NULL, 'Reguler 12 - Surabaya', '30', '1', 'offline', NULL, 'aktif', 'prepaid', NULL);

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
  ('1', 'DEMO-2025-0001', 'Bimo Prasetyo', '081234500011', 'L', 'SMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cabang Pusat Jakarta', '[\"Matematika\",\"Fisika\"]', '{\"Matematika\":8,\"Fisika\":8}', NULL, NULL, NULL, NULL, NULL, NULL, 'Ingin persiapan SNBT tahun ini.', 'pending', 'belum_bayar', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-30 13:30:41', '2026-06-30 13:30:41'),
  ('2', 'DEMO-2025-0002', 'Tiara Anggraeni', '082234500012', 'P', 'SMP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cabang Bandung', '[\"Bahasa Inggris\",\"Matematika\"]', '{\"Bahasa Inggris\":8,\"Matematika\":8}', NULL, NULL, NULL, NULL, NULL, NULL, 'Butuh bimbingan untuk ujian akhir semester.', 'pending', 'belum_bayar', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-30 13:30:41', '2026-06-30 13:30:41');

-- ---- Tabel: `student_teachers` (0 baris) ----
-- (kosong)

-- ---- Tabel: `students` (29 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `students` (`id`, `created_at`, `updated_at`, `deleted_at`, `branch_id`, `package_id`, `total_sesi`, `user_id`, `nis`, `name`, `gender`, `birth_date`, `birth_place`, `address`, `phone`, `parent_name`, `parent_phone`, `photo`, `status`, `join_date`, `school_name`, `grade`, `kategori_peserta_didik`) VALUES
  ('1', '2026-06-22 13:51:52', '2026-06-22 13:51:52', NULL, '2', '1', '0', '14', 'S202606221351524YF', 'anton', 'L', NULL, NULL, NULL, '1', 'andre', '1', NULL, 'aktif', '2026-06-22', NULL, NULL, NULL),
  ('2', '2026-06-22 14:01:05', '2026-06-22 14:01:05', NULL, '2', '1', '0', '15', 'S20260622140105VHI', 'ahmad', 'L', NULL, NULL, NULL, '1', '1', '1', NULL, 'aktif', '2026-06-22', NULL, NULL, NULL),
  ('3', '2026-06-25 12:45:02', '2026-06-25 12:45:02', NULL, '3', '2', '0', '26', 'SIS-2024-001', 'Andi Nugroho', 'L', '2007-04-12', 'Jakarta', 'Jl. Merdeka No. 5, Jakarta Selatan', '087812340001', 'Bapak Nugroho', '081312340001', NULL, 'aktif', '2024-01-10', 'SMAN 70 Jakarta', 'XI', 'SMA'),
  ('4', '2026-06-25 12:45:03', '2026-06-25 12:45:03', NULL, '3', '2', '0', '27', 'SIS-2024-002', 'Citra Lestari', 'P', '2007-08-23', 'Bogor', 'Jl. Kemang Raya No. 12, Jakarta Selatan', '087812340002', 'Ibu Lestari', '081312340002', NULL, 'aktif', '2024-01-15', 'SMAN 34 Jakarta', 'XI', 'SMA'),
  ('5', '2026-06-25 12:45:03', '2026-06-25 12:45:03', NULL, '3', '3', '0', '28', 'SIS-2024-003', 'Fajar Hidayat', 'L', '2006-01-30', 'Depok', 'Jl. Taman Makam Pahlawan No. 3, Depok', '087812340003', 'Bapak Hidayat', '081312340003', NULL, 'aktif', '2024-02-01', 'SMAN 5 Depok', 'XII', 'SMA'),
  ('6', '2026-06-25 12:45:03', '2026-06-25 12:45:03', NULL, '3', '3', '0', '29', 'SIS-2024-004', 'Gita Permata', 'P', '2006-05-17', 'Tangerang', 'Jl. BSD No. 22, Tangerang Selatan', '087812340004', 'Ibu Permata', '081312340004', NULL, 'aktif', '2024-02-05', 'SMAN 1 Serpong', 'XII', 'SMA'),
  ('7', '2026-06-25 12:45:03', '2026-06-25 12:45:03', NULL, '3', '2', '0', '30', 'SIS-2024-005', 'Hendra Putra', 'L', '2008-10-08', 'Jakarta', 'Jl. Cipete No. 7, Jakarta Selatan', '087812340005', 'Bapak Putra', '081312340005', NULL, 'aktif', '2024-03-01', 'SMPN 49 Jakarta', 'IX', 'SMP'),
  ('8', '2026-06-25 12:45:03', '2026-06-25 12:45:03', NULL, '3', '4', '0', '31', 'SIS-2024-006', 'Indah Sari', 'P', '2007-12-25', 'Bekasi', 'Jl. Galaxy No. 45, Bekasi', '087812340006', 'Bapak Sari', '081312340006', NULL, 'aktif', '2024-03-10', 'SMAN 1 Bekasi', 'XI', 'SMA'),
  ('9', '2026-06-25 12:45:03', '2026-06-25 12:45:03', NULL, '3', '2', '0', '32', 'SIS-2024-007', 'Joko Santoso', 'L', '2009-02-14', 'Jakarta', 'Jl. Pesanggrahan No. 8, Jakarta Barat', '087812340007', 'Ibu Santoso', '081312340007', NULL, 'aktif', '2024-04-01', 'SMPN 115 Jakarta', 'VIII', 'SMP'),
  ('10', '2026-06-25 12:45:03', '2026-06-25 12:45:03', NULL, '3', '3', '0', '33', 'SIS-2024-008', 'Kartini Wulandari', 'P', '2006-07-21', 'Jakarta', 'Jl. Lebak Bulus No. 3, Jakarta Selatan', '087812340008', 'Bapak Wulandari', '081312340008', NULL, 'aktif', '2024-04-15', 'SMAN 86 Jakarta', 'XII', 'SMA'),
  ('11', '2026-06-25 12:45:04', '2026-06-25 12:45:04', NULL, '4', '5', '0', '34', 'SIS-2024-009', 'Luthfi Rahman', 'L', '2007-03-19', 'Bandung', 'Jl. Setiabudi No. 20, Bandung', '087812340009', 'Bapak Rahman', '081312340009', NULL, 'aktif', '2024-01-20', 'SMAN 3 Bandung', 'XI', 'SMA'),
  ('12', '2026-06-25 12:45:04', '2026-06-25 12:45:04', NULL, '4', '5', '0', '35', 'SIS-2024-010', 'Mira Kusuma', 'P', '2007-11-02', 'Cimahi', 'Jl. Cimahi No. 15, Cimahi', '087812340010', 'Ibu Kusuma', '081312340010', NULL, 'aktif', '2024-02-10', 'SMAN 1 Cimahi', 'XI', 'SMA'),
  ('13', '2026-06-25 12:45:04', '2026-06-25 12:45:04', NULL, '4', '5', '0', '36', 'SIS-2024-011', 'Naufal Ardiansyah', 'L', '2008-06-15', 'Bandung', 'Jl. Pasteur No. 30, Bandung', '087812340011', 'Bapak Ardiansyah', '081312340011', NULL, 'aktif', '2024-03-01', 'SMPN 1 Bandung', 'IX', 'SMP'),
  ('14', '2026-06-25 12:45:04', '2026-06-25 12:45:04', NULL, '4', '5', '0', '37', 'SIS-2024-012', 'Olivia Putri', 'P', '2009-09-09', 'Bandung', 'Jl. Antapani No. 5, Bandung', '087812340012', 'Ibu Putri', '081312340012', NULL, 'aktif', '2024-04-05', 'SMPN 14 Bandung', 'VIII', 'SMP'),
  ('15', '2026-06-25 12:45:04', '2026-06-25 12:45:04', NULL, '5', '6', '0', '38', 'SIS-2024-013', 'Prasetyo Adi', 'L', '2007-01-25', 'Surabaya', 'Jl. Darmo No. 50, Surabaya', '087812340013', 'Bapak Adi', '081312340013', NULL, 'aktif', '2024-01-25', 'SMAN 5 Surabaya', 'XI', 'SMA'),
  ('16', '2026-06-25 12:45:04', '2026-06-25 12:45:04', NULL, '5', '6', '0', '39', 'SIS-2024-014', 'Rini Agustina', 'P', '2007-05-30', 'Gresik', 'Jl. Mayjend Sungkono No. 20, Surabaya', '087812340014', 'Ibu Agustina', '081312340014', NULL, 'aktif', '2024-02-20', 'SMAN 2 Surabaya', 'XI', 'SMA'),
  ('17', '2026-06-25 12:45:04', '2026-06-25 12:45:04', NULL, '5', '6', '0', '40', 'SIS-2024-015', 'Sandi Kurniawan', 'L', '2008-08-17', 'Sidoarjo', 'Jl. Sidoarjo No. 12, Sidoarjo', '087812340015', 'Bapak Kurniawan', '081312340015', NULL, 'aktif', '2024-03-15', 'SMPN 1 Sidoarjo', 'IX', 'SMP'),
  ('18', '2026-06-25 20:29:42', '2026-06-25 20:29:43', NULL, '6', '7', '0', '48', '1234567890', 'Bintang Samudera', 'L', NULL, NULL, NULL, '081234567890', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('19', '2026-06-25 20:29:42', '2026-06-25 20:29:43', NULL, '6', '7', '0', '49', '1234567891', 'Cahaya Bulan', 'P', NULL, NULL, NULL, '081234567891', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('20', '2026-06-25 20:29:42', '2026-06-25 20:29:43', NULL, '4', '8', '0', '50', '1234567892', 'Darmawan Putra', 'L', NULL, NULL, NULL, '081234567892', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('21', '2026-06-25 20:29:42', '2026-06-25 20:29:43', NULL, '4', '8', '0', '51', '1234567893', 'Elisa Ramadhani', 'P', NULL, NULL, NULL, '081234567893', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('22', '2026-06-25 20:29:42', '2026-06-25 20:29:43', NULL, '5', '11', '0', '52', '1234567894', 'Fajar Nugroho', 'L', NULL, NULL, NULL, '081234567894', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('23', '2026-06-25 20:29:42', '2026-06-25 20:29:43', NULL, '5', '11', '0', '53', '1234567895', 'Gita Permatasari', 'P', NULL, NULL, NULL, '081234567895', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('24', '2026-06-25 20:29:42', '2026-06-25 20:29:43', NULL, '6', '7', '0', '54', '1234567896', 'Hafiz Ramadhan', 'L', NULL, NULL, NULL, '081234567896', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('25', '2026-06-25 20:29:42', '2026-06-25 20:29:43', NULL, '6', '7', '0', '55', '1234567897', 'Intan Sari', 'P', NULL, NULL, NULL, '081234567897', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('26', '2026-06-25 20:29:43', '2026-06-25 20:29:43', NULL, '4', '8', '0', '56', '1234567898', 'Joko Santoso', 'L', NULL, NULL, NULL, '081234567898', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('27', '2026-06-25 20:29:43', '2026-06-25 20:29:43', NULL, '5', '11', '0', '57', '1234567899', 'Kania Maharani', 'P', NULL, NULL, NULL, '081234567899', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('28', '2026-06-25 20:29:43', '2026-06-25 20:29:43', NULL, '6', NULL, '0', '58', '1234567800', 'Lukman Hakim', 'L', NULL, NULL, NULL, '081234567800', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL),
  ('29', '2026-06-25 20:29:43', '2026-06-25 20:29:43', NULL, '4', NULL, '0', '59', '1234567801', 'Maya Sari', 'P', NULL, NULL, NULL, '081234567801', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, NULL);

-- ---- Tabel: `system_settings` (0 baris) ----
-- (kosong)

-- ---- Tabel: `tagihans` (0 baris) ----
-- (kosong)

-- ---- Tabel: `tahun_ajarans` (0 baris) ----
-- (kosong)

-- ---- Tabel: `teacher_courses` (25 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `teacher_courses` (`id`, `teacher_id`, `course_id`, `created_at`, `updated_at`) VALUES
  ('1', '2', '2', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('2', '2', '3', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('3', '3', '4', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('4', '3', '5', '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('5', '4', '6', '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('6', '5', '2', '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('7', '5', '7', '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('8', '6', '8', '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('9', '6', '10', '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('10', '7', '9', '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('11', '8', '11', '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('12', '8', '12', '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('13', '9', '1', '2026-06-25 20:29:40', '2026-06-25 20:29:40'),
  ('14', '9', '3', '2026-06-25 20:29:40', '2026-06-25 20:29:40'),
  ('15', '10', '4', '2026-06-25 20:29:40', '2026-06-25 20:29:40'),
  ('16', '10', '5', '2026-06-25 20:29:40', '2026-06-25 20:29:40'),
  ('17', '11', '6', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('18', '12', '13', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('19', '12', '16', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('20', '13', '14', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('21', '13', '15', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('22', '14', '17', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('23', '14', '15', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('24', '15', '1', '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('25', '15', '14', '2026-06-25 20:29:41', '2026-06-25 20:29:41');

-- ---- Tabel: `teachers` (15 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `teachers` (`id`, `user_id`, `branch_id`, `nig`, `name`, `gender`, `birth_date`, `birth_place`, `address`, `phone`, `email`, `education`, `subjects`, `photo`, `cv_path`, `salary_base`, `join_date`, `status`, `jenis_guru`, `deleted_at`, `created_at`, `updated_at`) VALUES
  ('1', '13', '2', '1', 'yastar iskandar', 'L', '2026-06-22', NULL, 'Jalan Mayjen MT Haryono, Kelurahan Gotong Royong, Kecamatan Tanjung Karang Pusat, dengan kode pos 35119.', '1', 'guru@sci.com', 'S1', '[\"Matematika\"]', NULL, 'teachers/cv/PVtU8OoCKvow499apZgGl7AqM1F7rBEAg75PJ1yB.pdf', '0.00', '2026-06-22', 'aktif', 'kontrak', NULL, '2026-06-22 13:49:47', '2026-06-22 13:49:47'),
  ('2', '19', '3', 'NIG-2020-001', 'Budi Santoso, S.Pd.', 'L', '1985-03-15', 'Yogyakarta', 'Jl. Tebet Barat No. 12, Jakarta Selatan', '081234560001', 'budi.santoso@guru.akademibimbel.com', 'S1 Pendidikan Matematika UNY', '[\"Matematika\",\"Fisika\"]', NULL, NULL, '4500000.00', '2020-01-15', 'aktif', 'tetap', NULL, '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('3', '20', '3', 'NIG-2020-002', 'Sari Dewi, M.Sc.', 'P', '1988-07-22', 'Bandung', 'Jl. Mampang Prapatan No. 5, Jakarta Selatan', '081234560002', 'sari.dewi@guru.akademibimbel.com', 'S2 Kimia ITB', '[\"Kimia\",\"Biologi\"]', NULL, NULL, '5000000.00', '2020-03-01', 'aktif', 'tetap', NULL, '2026-06-25 12:45:01', '2026-06-25 12:45:01'),
  ('4', '21', '3', 'NIG-2021-003', 'Rizky Pratama, S.Pd.', 'L', '1992-11-05', 'Jakarta', 'Jl. Fatmawati No. 88, Jakarta Selatan', '081234560003', 'rizky.pratama@guru.akademibimbel.com', 'S1 Sastra Inggris UI', '[\"Bahasa Inggris\"]', NULL, NULL, '4000000.00', '2021-06-01', 'aktif', 'tetap', NULL, '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('5', '22', '3', 'NIG-2021-004', 'Ahmad Fauzi, S.Si.', 'L', '1990-04-18', 'Semarang', 'Jl. Ciputat Raya No. 34, Jakarta Selatan', '081234560004', 'gurusci@gmail.com', 'S1 Matematika UNDIP', '[\"Matematika\",\"Persiapan SNBT\"]', NULL, NULL, '4500000.00', '2021-08-01', 'aktif', 'tetap', NULL, '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('6', '23', '4', 'NIG-2022-005', 'Hani Rahayu, S.Pd.', 'P', '1991-09-30', 'Bandung', 'Jl. Cihampelas No. 15, Bandung', '081234560005', 'hani.rahayu@guru.akademibimbel.com', 'S1 Pendidikan Matematika UPI', '[\"Matematika\",\"Fisika\"]', NULL, NULL, '4000000.00', '2022-01-10', 'aktif', 'tetap', NULL, '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('7', '24', '4', 'NIG-2022-006', 'Dimas Arya, S.Pd.', 'L', '1994-02-14', 'Sumedang', 'Jl. Buah Batu No. 40, Bandung', '081234560006', 'dimas.arya@guru.akademibimbel.com', 'S1 Bahasa Inggris UNPAD', '[\"Bahasa Inggris\"]', NULL, NULL, '3800000.00', '2022-04-01', 'aktif', 'honorer', NULL, '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('8', '25', '5', 'NIG-2022-007', 'Yuni Kartika, M.Pd.', 'P', '1987-06-25', 'Surabaya', 'Jl. Diponegoro No. 100, Surabaya', '081234560007', 'yuni.kartika@guru.akademibimbel.com', 'S2 Pendidikan Matematika UNESA', '[\"Matematika\",\"Bahasa Inggris\"]', NULL, NULL, '4800000.00', '2022-07-01', 'aktif', 'tetap', NULL, '2026-06-25 12:45:02', '2026-06-25 12:45:02'),
  ('9', '41', '6', 'NIG002', 'Andi Prasetyo, S.Pd.', 'L', NULL, NULL, NULL, NULL, 'andi.prasetyo@akademisci.com', NULL, '[\"Matematika\",\"Fisika\"]', NULL, NULL, '5000000.00', NULL, 'aktif', 'Guru Tetap', NULL, '2026-06-25 20:29:40', '2026-06-25 20:29:40'),
  ('10', '42', '6', 'NIG003', 'Sari Dewi, S.Pd.', 'P', NULL, NULL, NULL, NULL, 'sari.dewi@akademisci.com', NULL, '[\"Kimia\",\"Biologi\"]', NULL, NULL, '5000000.00', NULL, 'aktif', 'Guru Tetap', NULL, '2026-06-25 20:29:40', '2026-06-25 20:29:40'),
  ('11', '43', '4', 'NIG004', 'Rizky Ananta, M.Pd.', 'L', NULL, NULL, NULL, NULL, 'rizky.ananta@akademisci.com', NULL, '[\"Bahasa Inggris\"]', NULL, NULL, '5000000.00', NULL, 'aktif', 'Guru Paruh Waktu', NULL, '2026-06-25 20:29:40', '2026-06-25 20:29:40'),
  ('12', '44', '4', 'NIG005', 'Nurul Hidayah, S.Pd.', 'P', NULL, NULL, NULL, NULL, 'nurul.hidayah@akademisci.com', NULL, '[\"Bahasa Indonesia\",\"Sejarah\"]', NULL, NULL, '5000000.00', NULL, 'aktif', 'Guru Tetap', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('13', '45', '5', 'NIG006', 'Hendra Wijaya, S.E., M.M.', 'L', NULL, NULL, NULL, NULL, 'hendra.wijaya@akademisci.com', NULL, '[\"Ekonomi\",\"Sosiologi\"]', NULL, NULL, '5000000.00', NULL, 'aktif', 'Guru Paruh Waktu', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('14', '46', '5', 'NIG007', 'Fitri Lestari, S.Pd.', 'P', NULL, NULL, NULL, NULL, 'fitri.lestari@akademisci.com', NULL, '[\"Geografi\",\"Sosiologi\"]', NULL, NULL, '5000000.00', NULL, 'aktif', 'Guru Tetap', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41'),
  ('15', '47', '6', 'NIG008', 'Dimas Arief, S.Pd.', 'L', NULL, NULL, NULL, NULL, 'dimas.arief@akademisci.com', NULL, '[\"Matematika\",\"Ekonomi\"]', NULL, NULL, '0.00', NULL, 'aktif', 'Freelance', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41');

-- ---- Tabel: `tryout_attempts` (3 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `tryout_attempts` (`id`, `created_at`, `updated_at`, `tryout_id`, `siswa_id`, `waktu_mulai`, `waktu_selesai`, `nilai`, `jawaban_benar`, `jawaban_salah`, `tidak_dijawab`, `percobaan_ke`, `status`, `jawaban`) VALUES
  ('1', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '1', '5', '2026-05-31 00:00:00', '2026-05-31 01:10:00', '87.00', '4', '1', '0', '1', 'selesai', NULL),
  ('2', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '1', '6', '2026-05-31 00:00:00', '2026-05-31 01:12:00', '69.00', '3', '2', '0', '1', 'selesai', NULL),
  ('3', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '1', '10', '2026-05-31 00:00:00', '2026-05-31 01:08:00', '72.00', '3', '2', '0', '1', 'selesai', NULL);

-- ---- Tabel: `tryouts` (5 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `tryouts` (`id`, `created_at`, `updated_at`, `cabang_id`, `dibuat_oleh`, `judul`, `deskripsi`, `kategori`, `durasi_menit`, `total_soal`, `nilai_kelulusan`, `waktu_mulai`, `waktu_selesai`, `is_random`, `tampilkan_hasil_langsung`, `tampilkan_kunci_jawaban`, `maksimal_percobaan`, `status`, `deleted_at`) VALUES
  ('1', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '10', 'Tryout SNBT Perdana 2024', 'Tryout perdana persiapan UTBK-SNBT 2024 dengan soal TPS dan Literasi.', 'SNBT', '90', '5', '60.00', '2026-05-26 00:00:00', '2026-06-10 00:00:00', '1', '1', '0', '1', 'selesai', NULL),
  ('2', '2026-06-25 12:45:05', '2026-06-25 12:45:05', '3', '10', 'Tryout Matematika SMA Ulangan Harian', 'Ulangan harian materi fungsi kuadrat dan trigonometri.', 'Matematika', '60', '5', '70.00', '2026-06-28 00:00:00', '2026-06-30 00:00:00', '0', '1', '1', '2', 'terjadwal', NULL),
  ('3', '2026-06-25 20:29:43', '2026-06-25 20:29:43', NULL, NULL, 'Tryout UTBK Saintek Maret 2025', 'Tryout persiapan: Tryout UTBK Saintek Maret 2025', 'latihan', '90', '30', NULL, '2026-05-26 20:29:43', '2026-08-24 20:29:43', '0', '1', '0', NULL, 'aktif', NULL),
  ('4', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, NULL, 'Tryout Fisika April 2025', 'Tryout persiapan: Tryout Fisika April 2025', 'latihan', '60', '20', NULL, '2026-05-26 20:29:44', '2026-08-24 20:29:44', '0', '1', '0', NULL, 'aktif', NULL),
  ('5', '2026-06-25 20:29:44', '2026-06-25 20:29:44', NULL, NULL, 'Tryout Bahasa Inggris UTBK', 'Tryout persiapan: Tryout Bahasa Inggris UTBK', 'latihan', '60', '25', NULL, '2026-05-26 20:29:44', '2026-08-24 20:29:44', '0', '1', '0', NULL, 'aktif', NULL);

-- ---- Tabel: `users` (50 baris) ----
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `avatar`, `branch_id`, `is_active`, `last_login_at`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
  ('10', 'Admin Pusat SCI', NULL, 'adminpusatsci@akademi.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$X/MKgjU3Zowp62a/qJWIVuw4yZMl6kpHwPfzgE6IqvxZ2RzZUox0C', NULL, '2026-06-22 13:46:11', '2026-06-30 13:30:02', NULL),
  ('11', 'Admin Cabang SCI', NULL, 'admincabangsci@akademi.com', NULL, NULL, '2', '1', NULL, NULL, '$2y$10$odXy0uW6H7ZgUKrQW.FLIeDvfzjfScAki.Yl3F3X1.zdVXYnl15eq', NULL, '2026-06-22 13:46:11', '2026-06-30 13:28:20', NULL),
  ('12', 'admin', NULL, 'admin@sci.com', NULL, NULL, '2', '1', NULL, NULL, '$2y$10$W83GCi6A8O3Z235HfZ4fReoyDS3DqUE1Ug2.6aHOlmbhi/5dB5IC.', NULL, '2026-06-22 13:47:02', '2026-06-22 13:47:02', NULL),
  ('13', 'yastar iskandar', NULL, 'guru@sci.com', '1', NULL, '2', '1', NULL, NULL, '$2y$10$FzUg5LYO2vlgGQwlqshfnuFQCK81yAWVFWOubgdOtTUa/p2385A5u', NULL, '2026-06-22 13:49:47', '2026-06-22 13:49:47', NULL),
  ('14', 'anton', NULL, 'anton@sci.com', '1', NULL, '2', '1', NULL, NULL, '$2y$10$oM6BqFib/YW.5ow0uP3PEeKZHX4BBWJ.4LbBJwSaeSGasbTCNoI1.', NULL, '2026-06-22 13:51:52', '2026-06-22 13:52:18', NULL),
  ('15', 'ahmad', NULL, 'ahmad.1782111665@siswa.local', '1', NULL, '2', '1', NULL, NULL, '$2y$10$j26SuS/JXRmF/.hJm4hoT.Q.VWofDIuJJhGj5p/ENrRs4YynvSpqO', NULL, '2026-06-22 14:01:05', '2026-06-22 14:01:05', NULL),
  ('16', 'Admin Pusat Jakarta', NULL, 'adminpusat@akademibimbel.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$ejKZAC7WIh1A.5xp5ewUO.g04mZ6/R1R24/qFkSv8eSvEGG4FRMJK', NULL, '2026-06-25 12:45:00', '2026-06-30 13:30:38', NULL),
  ('17', 'Admin Cabang Bandung', NULL, 'adminbandung@akademibimbel.com', NULL, NULL, '4', '1', NULL, NULL, '$2y$10$3uAybmAjoF6SRr9enaSvhuwqbPaYAtgI7ygXq7rwXvGYfvC9JDvMK', NULL, '2026-06-25 12:45:01', '2026-06-30 13:30:39', NULL),
  ('18', 'Admin Cabang Surabaya', NULL, 'adminsurabaya@akademibimbel.com', NULL, NULL, '5', '1', NULL, NULL, '$2y$10$X1BcCx9gEKZADKk6rdMbROqAXsdNs/sF13Pee8gMgASGN2T0HKmOu', NULL, '2026-06-25 12:45:01', '2026-06-30 13:30:39', NULL),
  ('19', 'Budi Santoso, S.Pd.', NULL, 'budi.santoso@guru.akademibimbel.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$tpa480lFn7at6gtfQw3I0.KcvW7x8N9x36nX7Dn09OT8jjzJWbDYS', NULL, '2026-06-25 12:45:01', '2026-06-30 13:30:39', NULL),
  ('20', 'Sari Dewi, M.Sc.', NULL, 'sari.dewi@guru.akademibimbel.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$dUhfMauVlkAQh4Te4JNXsuztzN05vDL8H/KRXZ9beVKvqo62uAqmS', NULL, '2026-06-25 12:45:01', '2026-06-30 13:30:39', NULL),
  ('21', 'Rizky Pratama, S.Pd.', NULL, 'rizky.pratama@guru.akademibimbel.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$MThviHgCVZgO3bahVcA/PeRt9Kews2GIoKQQK5e8wBMWgK8Bhgg7O', NULL, '2026-06-25 12:45:01', '2026-06-30 13:30:39', NULL),
  ('22', 'Ahmad Fauzi, S.Si.', NULL, 'gurusci@gmail.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$3za8ynMLZbIwwxTsJmYgcuCiE0F0ABiRwyGuwvshrijBeWDa4KCtW', NULL, '2026-06-25 12:45:02', '2026-06-30 13:30:39', NULL),
  ('23', 'Hani Rahayu, S.Pd.', NULL, 'hani.rahayu@guru.akademibimbel.com', NULL, NULL, '4', '1', NULL, NULL, '$2y$10$BJAaaS7AIHnxOfMNTwB0aOGSP3ajfLqMvoHK.b/ZtHtHYIw/jorHO', NULL, '2026-06-25 12:45:02', '2026-06-30 13:30:39', NULL),
  ('24', 'Dimas Arya, S.Pd.', NULL, 'dimas.arya@guru.akademibimbel.com', NULL, NULL, '4', '1', NULL, NULL, '$2y$10$U9m/5HY3RYRv3vaab7TRMejBbfQUGsL.sbYo/owofx5xyhys0Jlcu', NULL, '2026-06-25 12:45:02', '2026-06-30 13:30:39', NULL),
  ('25', 'Yuni Kartika, M.Pd.', NULL, 'yuni.kartika@guru.akademibimbel.com', NULL, NULL, '5', '1', NULL, NULL, '$2y$10$uBpkXV7.qBsWBC9X2fItxetTn4rIYPgf9TYgq4FxESHhm/4.Dgliu', NULL, '2026-06-25 12:45:02', '2026-06-30 13:30:39', NULL),
  ('26', 'Andi Nugroho', NULL, 'andi.nugroho@siswa.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$ZPqNhB9Qbh2gvspND.hP3usvN/5bELWtq0FE377oShSgDIYh6XSgO', NULL, '2026-06-25 12:45:02', '2026-06-30 13:30:39', NULL),
  ('27', 'Citra Lestari', NULL, 'citra.lestari@siswa.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$o4m0vT4qUc2KVYJrVcwimeR7t.53Hn.Q44JOGX3ecedaTa35qJExe', NULL, '2026-06-25 12:45:02', '2026-06-30 13:30:40', NULL),
  ('28', 'Fajar Hidayat', NULL, 'fajar.hidayat@siswa.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$yM8ml8DkJ.DIpW1oDFj5z.7mU2shEWL98Oc2EZ9Xy8U.Pul5mAc/y', NULL, '2026-06-25 12:45:03', '2026-06-30 13:30:40', NULL),
  ('29', 'Gita Permata', NULL, 'gita.permata@siswa.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$8TkmMTEXW4BGB4N2guZOWOJwpj/sMJCryS3dMp99gS0cvGCHl18aq', NULL, '2026-06-25 12:45:03', '2026-06-30 13:30:40', NULL),
  ('30', 'Hendra Putra', NULL, 'hendra.putra@siswa.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$td73NkB3iGmp6ca604f2weCJrjcbP1Kx96f.d2y2at.nZC.uhYj5q', NULL, '2026-06-25 12:45:03', '2026-06-30 13:30:40', NULL),
  ('31', 'Indah Sari', NULL, 'indah.sari@siswa.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$5Dj4tymgwcLzQMjoULH5U.eehbyuco9qR3ASbpXtX1N9qmlRvjc8G', NULL, '2026-06-25 12:45:03', '2026-06-30 13:30:40', NULL),
  ('32', 'Joko Santoso', NULL, 'joko.santoso@siswa.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$5Qv368bA5ZUzsU8tqMKcn.u/xLkd19AK04TYFzy8NsdRF0g5f359K', NULL, '2026-06-25 12:45:03', '2026-06-30 13:30:40', NULL),
  ('33', 'Kartini Wulandari', NULL, 'kartini.wulandari@siswa.com', NULL, NULL, '3', '1', NULL, NULL, '$2y$10$FwHh0hYJ8zR.AnOPcoL1m.5SoFllstdBkHoiDfJukcrFCVOrwYKSy', NULL, '2026-06-25 12:45:03', '2026-06-30 13:30:40', NULL),
  ('34', 'Luthfi Rahman', NULL, 'luthfi.rahman@siswa.com', NULL, NULL, '4', '1', NULL, NULL, '$2y$10$BE4xiAX4MtkvPnGD2IAYiOi8z2V01fHCt3hvUg5NBLYjowR77sPM.', NULL, '2026-06-25 12:45:04', '2026-06-30 13:30:40', NULL),
  ('35', 'Mira Kusuma', NULL, 'mira.kusuma@siswa.com', NULL, NULL, '4', '1', NULL, NULL, '$2y$10$/sysv8iTmZHsIPKJTXU/cutyB20c58FptiD81PDwIoemsuj2lHrVy', NULL, '2026-06-25 12:45:04', '2026-06-30 13:30:40', NULL),
  ('36', 'Naufal Ardiansyah', NULL, 'naufal.ardiansyah@siswa.com', NULL, NULL, '4', '1', NULL, NULL, '$2y$10$3lrWGunKD6JKedzWYCzCxuIQNWXvRrzZc71dnJi4v9HWw49sAXbCC', NULL, '2026-06-25 12:45:04', '2026-06-30 13:30:40', NULL),
  ('37', 'Olivia Putri', NULL, 'olivia.putri@siswa.com', NULL, NULL, '4', '1', NULL, NULL, '$2y$10$6FXecrulMg6Zu7PLES5a.ex6uYeSiU2j.h11rN.V40ENQE5Pn4IKa', NULL, '2026-06-25 12:45:04', '2026-06-30 13:30:40', NULL),
  ('38', 'Prasetyo Adi', NULL, 'prasetyo.adi@siswa.com', NULL, NULL, '5', '1', NULL, NULL, '$2y$10$ARAYzuxYsqWFgzXzxmd0NO8ujpJ2LT9vrmJfTJrP5culV9.7HuMpq', NULL, '2026-06-25 12:45:04', '2026-06-30 13:30:41', NULL),
  ('39', 'Rini Agustina', NULL, 'rini.agustina@siswa.com', NULL, NULL, '5', '1', NULL, NULL, '$2y$10$eD38qCoyY0qpH5WB485x1eJfF3zPWSViU.rjD57c6Jgqplx1CSyOm', NULL, '2026-06-25 12:45:04', '2026-06-30 13:30:41', NULL),
  ('40', 'Sandi Kurniawan', NULL, 'sandi.kurniawan@siswa.com', NULL, NULL, '5', '1', NULL, NULL, '$2y$10$lDx77sk6N278COvxKQ1f1.Y0sdXwsYvI8wfwlFObRwyQoAx1a89AC', NULL, '2026-06-25 12:45:04', '2026-06-30 13:30:41', NULL),
  ('41', 'Andi Prasetyo, S.Pd.', NULL, 'andi.prasetyo@akademisci.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$jMf7mmilBZlib1/NnmO.eOO9gSVzR3QOkzB2ATNnBrhDJU/C29Miu', NULL, '2026-06-25 20:29:40', '2026-06-25 20:29:40', NULL),
  ('42', 'Sari Dewi, S.Pd.', NULL, 'sari.dewi@akademisci.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$iyf1zkUW42tnmO1zQ5xm7evcgFO6/0O7Jm2rHOjc/nI6vNbhzfkkG', NULL, '2026-06-25 20:29:40', '2026-06-25 20:29:40', NULL),
  ('43', 'Rizky Ananta, M.Pd.', NULL, 'rizky.ananta@akademisci.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$FJ8nv7NJ4ix8MzpFzQ9pReg0ctkIvyPFgYd0gxObePTLWHtrkpvqe', NULL, '2026-06-25 20:29:40', '2026-06-25 20:29:40', NULL),
  ('44', 'Nurul Hidayah, S.Pd.', NULL, 'nurul.hidayah@akademisci.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$D.OwC2k4ZpHjbhBPKxLrlesOcebt80u4KOLw.WjJIhVmoIX3SICO2', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41', NULL),
  ('45', 'Hendra Wijaya, S.E., M.M.', NULL, 'hendra.wijaya@akademisci.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$VREZRfgNg.DPc2nq83kIPOJwxzcd8NewRW88s8tLUUXVPzx/w7h36', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41', NULL),
  ('46', 'Fitri Lestari, S.Pd.', NULL, 'fitri.lestari@akademisci.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$3ndhyozdU0jtLMG1Op38KeFoyyCZ1YIY4ZO6UZ4yK9JJgBFX2nShG', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41', NULL),
  ('47', 'Dimas Arief, S.Pd.', NULL, 'dimas.arief@akademisci.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$8.XyKj/X8RiDC3mqDUrALeZj97IzxHBDIsOE29WbWDNQ3T5hVsM8O', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41', NULL),
  ('48', 'Bintang Samudera', NULL, 'bintang@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$wqT3tC0D1DbQ4nVLHzlvg.krQFqmg252pPifZ.tymw/RoORYE6b9u', NULL, '2026-06-25 20:29:41', '2026-06-25 20:29:41', NULL),
  ('49', 'Cahaya Bulan', NULL, 'cahaya@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$SkI1Lz/b1NGMhRTjW9njwemGvlJGUXf8M3Q7DGzYyTE2S34XkLLQW', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42', NULL),
  ('50', 'Darmawan Putra', NULL, 'darmawan@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$xbDSW8UE2OF3Gtg3LlwkwebE4KcVEOlfYmJ6pBWtH21z7S/pudtsO', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42', NULL),
  ('51', 'Elisa Ramadhani', NULL, 'elisa@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$elCmliul3.qfSW6r9BJ.g.l4JyhyLXPIl.uzPvjbbHpMH.Y/3x4sy', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42', NULL),
  ('52', 'Fajar Nugroho', NULL, 'fajar@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$eEasaD3yky1wRWrlzUkku.pQQ8KePPiBUxJii2RDT1YYWqfcG.kOi', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42', NULL),
  ('53', 'Gita Permatasari', NULL, 'gita@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$KhyhNwPSYWZB8wFkvcejsu6/2l.Ru1g9gAQyXVLk.PabrCPhI6uB6', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42', NULL),
  ('54', 'Hafiz Ramadhan', NULL, 'hafiz@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$ysEBP4tYWytuYsybU4D7aOCRcmNC7FRdGYT36DcQhrgiDyC.GvHJW', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42', NULL),
  ('55', 'Intan Sari', NULL, 'intan@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$kL1aYowPxziVKZ3jDVBkJ.THoACSRyauaiV32mTXD5ZrB2z6YPeTO', NULL, '2026-06-25 20:29:42', '2026-06-25 20:29:42', NULL),
  ('56', 'Joko Santoso', NULL, 'joko@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$athkhFYb6Xl8NrS1TqlhfeYMZFVcb6YyhkGbai.9i4qV2yx9feztu', NULL, '2026-06-25 20:29:43', '2026-06-25 20:29:43', NULL),
  ('57', 'Kania Maharani', NULL, 'kania@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$DWSQTnxtLQ1OZO9Gu2i2F.CvGZ7S/k9HFLzXYs6DjzlWYDMXJVDKi', NULL, '2026-06-25 20:29:43', '2026-06-25 20:29:43', NULL),
  ('58', 'Lukman Hakim', NULL, 'lukman@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$GPpP412WxdGehzXhFD2Efus/eEJ8ulFMjjtQvzDLoDuv9BZsY1Mv6', NULL, '2026-06-25 20:29:43', '2026-06-25 20:29:43', NULL),
  ('59', 'Maya Sari', NULL, 'maya@student.com', NULL, NULL, NULL, '1', NULL, NULL, '$2y$10$mJe2r4OrVZG3T0ZCqb0OTOmh8YpJbAS.YTGmJ33prkU79dI1DBHZS', NULL, '2026-06-25 20:29:43', '2026-06-25 20:29:43', NULL);

-- ===========================================================================
-- Akhir bagian DATA
-- ===========================================================================

SET FOREIGN_KEY_CHECKS = 1;
-- Selesai — 2026-06-30 06:31:59
