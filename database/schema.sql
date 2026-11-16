

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `featured_projects`;
DROP TABLE IF EXISTS `academic_projects`;
DROP TABLE IF EXISTS `blog_insights`;
DROP TABLE IF EXISTS `contact_inquiries`;
DROP TABLE IF EXISTS `company_products`;
SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE `featured_projects` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(255) NULL,
  `title` VARCHAR(255) NOT NULL,
  `short_description` TEXT NULL,
  `description` TEXT NOT NULL,
  `category` VARCHAR(255) NULL,
  `category_badge` VARCHAR(255) NULL,
  `official_site` VARCHAR(255) NULL,
  `impact` TEXT NULL,
  `screenshots` TEXT NULL,
  `image` VARCHAR(255) NOT NULL DEFAULT 'images/projectcard1.jpg',
  `skills` VARCHAR(500) NOT NULL,
  `client_name` VARCHAR(255) NULL,
  `location` VARCHAR(255) NULL,
  `features` TEXT NULL,
  `live_url` VARCHAR(500) NOT NULL DEFAULT 'https://github.com/gangasagar12',
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_featured_order` (`display_order`),
  INDEX `idx_featured_slug` (`slug`)
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
  `image` VARCHAR(255) NOT NULL DEFAULT 'images/projectcard1.jpg',
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

-- ----------------------------------------------------------------------------
-- Table: company_products
-- Strataflow Technologies flagship products and software solutions
-- ----------------------------------------------------------------------------
CREATE TABLE `company_products` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `category` VARCHAR(150) NOT NULL DEFAULT 'Software System',
  `description` TEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL DEFAULT 'images/projectcard1.jpg',
  `status_text` VARCHAR(100) NOT NULL DEFAULT 'Live Product',
  `features` TEXT NOT NULL,
  `tech_pills` VARCHAR(500) NOT NULL DEFAULT '',
  `link_url` VARCHAR(500) NOT NULL DEFAULT 'https://github.com/gangasagar12',
  `link_text` VARCHAR(100) NOT NULL DEFAULT 'View Details',
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_company_products_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table: other_projects
-- Some Other Projects
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `other_projects` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `category` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NOT NULL,
  `detailed_description` LONGTEXT NULL,
  `image` VARCHAR(255) DEFAULT 'images/projectcard3.webp',
  `skills` VARCHAR(500) NOT NULL,
  `github_url` VARCHAR(500) DEFAULT 'https://github.com/gangasagar12',
  `live_url` VARCHAR(500) DEFAULT 'https://github.com/gangasagar12',
  `display_order` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table: experiences
-- Professional work and startup leadership history
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `experiences` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `company` VARCHAR(255) NOT NULL,
  `role` VARCHAR(255) NOT NULL,
  `period` VARCHAR(255) NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `status` VARCHAR(255) DEFAULT 'Active',
  `description` LONGTEXT NOT NULL,
  `responsibilities` LONGTEXT NOT NULL,
  `skills` VARCHAR(255) NOT NULL,
  `display_order` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table: awards
-- Honors, recognitions, hackathons and verified awards gallery
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `awards` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `issuer` VARCHAR(255) NOT NULL,
  `date` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NOT NULL,
  `display_order` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table: promotions
-- Promotional Offer Pop-ups
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `promotions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `button_text` VARCHAR(255) DEFAULT 'Claim Offer',
  `button_url` VARCHAR(255) DEFAULT '#contact',
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

