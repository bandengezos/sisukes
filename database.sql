-- Database Schema SQL Export
-- Generated from Laravel Migrations
-- Database: laravel (SQLite)
-- Created: 2026-06-22

-- ============================================================================
-- Core Tables
-- ============================================================================

-- Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `email_verified_at` DATETIME DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(255) NOT NULL DEFAULT 'petugas',
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL
);

-- Password Reset Tokens Table
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` VARCHAR(255) PRIMARY KEY,
  `token` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT NULL
);

-- Sessions Table
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(255) PRIMARY KEY,
  `user_id` INTEGER DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INTEGER NOT NULL
);

CREATE INDEX IF NOT EXISTS `sessions_user_id_index` ON `sessions` (`user_id`);
CREATE INDEX IF NOT EXISTS `sessions_last_activity_index` ON `sessions` (`last_activity`);

-- ============================================================================
-- Cache Tables
-- ============================================================================

-- Cache Table
CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(255) PRIMARY KEY,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` BIGINT NOT NULL
);

CREATE INDEX IF NOT EXISTS `cache_expiration_index` ON `cache` (`expiration`);

-- Cache Locks Table
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` VARCHAR(255) PRIMARY KEY,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` BIGINT NOT NULL
);

CREATE INDEX IF NOT EXISTS `cache_locks_expiration_index` ON `cache_locks` (`expiration`);

-- ============================================================================
-- Jobs Tables
-- ============================================================================

-- Jobs Table
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` SMALLINT UNSIGNED NOT NULL,
  `reserved_at` INTEGER UNSIGNED DEFAULT NULL,
  `available_at` INTEGER UNSIGNED NOT NULL,
  `created_at` INTEGER UNSIGNED NOT NULL
);

CREATE INDEX IF NOT EXISTS `jobs_queue_index` ON `jobs` (`queue`);

-- Job Batches Table
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` VARCHAR(255) PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INTEGER NOT NULL,
  `pending_jobs` INTEGER NOT NULL,
  `failed_jobs` INTEGER NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT DEFAULT NULL,
  `cancelled_at` INTEGER DEFAULT NULL,
  `created_at` INTEGER NOT NULL,
  `finished_at` INTEGER DEFAULT NULL
);

-- Failed Jobs Table
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `uuid` VARCHAR(255) NOT NULL UNIQUE,
  `connection` VARCHAR(255) NOT NULL,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS `failed_jobs_connection_queue_failed_at_index` ON `failed_jobs` (`connection`, `queue`, `failed_at`);

-- ============================================================================
-- Business Domain Tables
-- ============================================================================

-- Complaint Categories Table
CREATE TABLE IF NOT EXISTS `complaint_categories` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL
);

-- Complaints Table
CREATE TABLE IF NOT EXISTS `complaints` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `category_id` INTEGER NOT NULL,
  `complainant_name` VARCHAR(255) NOT NULL,
  `complainant_phone` VARCHAR(255) DEFAULT NULL,
  `complainant_email` VARCHAR(255) DEFAULT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `priority` VARCHAR(255) NOT NULL DEFAULT 'medium',
  `status` VARCHAR(255) NOT NULL DEFAULT 'received',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  FOREIGN KEY (`category_id`) REFERENCES `complaint_categories` (`id`) ON DELETE CASCADE
);

-- Complaint Responses Table
CREATE TABLE IF NOT EXISTS `complaint_responses` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `complaint_id` INTEGER NOT NULL,
  `user_id` INTEGER NOT NULL,
  `response_text` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

-- Surveys Table
CREATE TABLE IF NOT EXISTS `surveys` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'draft',
  `start_date` DATE DEFAULT NULL,
  `end_date` DATE DEFAULT NULL,
  `created_by` INTEGER NOT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

-- Survey Questions Table
CREATE TABLE IF NOT EXISTS `survey_questions` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `survey_id` INTEGER NOT NULL,
  `question_text` VARCHAR(255) NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  `options` JSON DEFAULT NULL,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE
);

-- Survey Responses Table
CREATE TABLE IF NOT EXISTS `survey_responses` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `survey_id` INTEGER NOT NULL,
  `respondent_name` VARCHAR(255) DEFAULT NULL,
  `submitted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE
);

-- Survey Answers Table
CREATE TABLE IF NOT EXISTS `survey_answers` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `response_id` INTEGER NOT NULL,
  `question_id` INTEGER NOT NULL,
  `answer_value` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  FOREIGN KEY (`response_id`) REFERENCES `survey_responses` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`question_id`) REFERENCES `survey_questions` (`id`) ON DELETE CASCADE
);

-- Notifications Table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL
);

-- ============================================================================
-- Indexes
-- ============================================================================

CREATE INDEX IF NOT EXISTS `users_email_index` ON `users` (`email`);
CREATE INDEX IF NOT EXISTS `complaints_category_id_index` ON `complaints` (`category_id`);
CREATE INDEX IF NOT EXISTS `complaints_status_index` ON `complaints` (`status`);
CREATE INDEX IF NOT EXISTS `complaint_responses_complaint_id_index` ON `complaint_responses` (`complaint_id`);
CREATE INDEX IF NOT EXISTS `complaint_responses_user_id_index` ON `complaint_responses` (`user_id`);
CREATE INDEX IF NOT EXISTS `surveys_created_by_index` ON `surveys` (`created_by`);
CREATE INDEX IF NOT EXISTS `survey_questions_survey_id_index` ON `survey_questions` (`survey_id`);
CREATE INDEX IF NOT EXISTS `survey_responses_survey_id_index` ON `survey_responses` (`survey_id`);
CREATE INDEX IF NOT EXISTS `survey_answers_response_id_index` ON `survey_answers` (`response_id`);
CREATE INDEX IF NOT EXISTS `survey_answers_question_id_index` ON `survey_answers` (`question_id`);

-- ============================================================================
-- End of Schema
-- ============================================================================
