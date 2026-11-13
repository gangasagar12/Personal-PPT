-- ============================================================================
-- Ganga Sagar Joshi — Portfolio Database Schema (MySQL / MariaDB / cPanel)
-- Character Set: utf8mb4, Collation: utf8mb4_unicode_ci
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `featured_projects`;
DROP TABLE IF EXISTS `academic_projects`;
DROP TABLE IF EXISTS `blog_insights`;
DROP TABLE IF EXISTS `contact_inquiries`;
SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------------------------
-- Table: featured_projects
-- Production & flagship engineering projects
-- ----------------------------------------------------------------------------
CREATE TABLE `featured_projects` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL DEFAULT 'images/projectcard1.jpg',
  `skills` VARCHAR(500) NOT NULL,
  `live_url` VARCHAR(500) NOT NULL DEFAULT 'https://github.com/gangasaagar',
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_featured_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table: academic_projects
-- Key academic coursework and semester engineering projects
-- ----------------------------------------------------------------------------
CREATE TABLE `academic_projects` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `skills` VARCHAR(500) NOT NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_academic_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table: blog_insights
-- Technical blog articles & engineering insights
-- ----------------------------------------------------------------------------
CREATE TABLE `blog_insights` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `category` VARCHAR(100) NOT NULL,
  `read_time` VARCHAR(50) NOT NULL DEFAULT '5 min read',
  `excerpt` TEXT NOT NULL,
  `content` LONGTEXT NOT NULL,
  `date` VARCHAR(50) NOT NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_blog_order` (`display_order`),
  INDEX `idx_blog_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table: contact_inquiries
-- Messages submitted through the contact section
-- ----------------------------------------------------------------------------
CREATE TABLE `contact_inquiries` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_contact_email` (`email`),
  INDEX `idx_contact_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

