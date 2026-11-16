-- ========================================================
-- Ganga Sagar Joshi Portfolio — Full MySQL Database Dump
-- Generated for cPanel / phpMyAdmin Import
-- ========================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `featured_projects`;
CREATE TABLE `featured_projects` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_badge` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `official_site` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `impact` longtext COLLATE utf8mb4_unicode_ci,
  `screenshots` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skills` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features` longtext COLLATE utf8mb4_unicode_ci,
  `live_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `featured_projects` (`id`, `slug`, `title`, `short_description`, `description`, `category`, `category_badge`, `official_site`, `impact`, `screenshots`, `image`, `skills`, `client_name`, `location`, `features`, `live_url`, `display_order`, `created_at`) VALUES
('1', NULL, 'Oralix — Dental Practice OS', NULL, 'Multi-chair clinical operations platform engineered for modern dental practices. Unifies real-time operatory chair scheduling, electronic health records (EHR), dental charting, and automated billing.', NULL, NULL, NULL, NULL, NULL, 'images/projectcard1.jpg', 'Python, Django, PostgreSQL, REST APIs, Docker', NULL, NULL, NULL, 'https://github.com/gangasagar12', '1', '2026-11-16 03:46:15'),
('2', NULL, 'CareFirst Dental Management', NULL, 'Specialized clinical management suite designed to streamline dental front-desk check-ins, chair appointment queues, and dental inventory with relational database consistency.', NULL, NULL, NULL, NULL, NULL, 'images/projectcard2.jpg', 'Django, Python, MySQL, HTML5 / CSS3, JavaScript', NULL, NULL, NULL, 'https://github.com/gangasagar12', '2', '2026-11-16 03:46:15'),
('3', NULL, 'Smart Library Management System', NULL, 'End-to-end automated library cataloging and circulation tracking portal. Handles book inventory categorization, student member checkouts, and fine calculation engines.', NULL, NULL, NULL, NULL, NULL, 'images/project1.jpg', 'Django, Python, SQLite, JavaScript, Bootstrap', NULL, NULL, NULL, 'https://github.com/gangasagar12', '3', '2026-11-16 03:46:15'),
('4', NULL, 'Scalable E-Commerce REST APIs', NULL, 'High-throughput enterprise e-commerce backend built with Django REST Framework. Architected with normalized PostgreSQL models, stateless JWT authentication, and order transactions.', NULL, NULL, NULL, NULL, NULL, 'images/project2.jpg', 'Django REST, Python, PostgreSQL, JWT Auth, Redis', NULL, NULL, NULL, 'https://github.com/gangasagar12', '4', '2026-11-16 03:46:15'),
('5', NULL, 'Data Analytics & Visualization Dashboard', NULL, 'Interactive exploratory analytics dashboard designed to parse, clean, and visualize multi-dimensional datasets with Pandas, Plotly, and Matplotlib.', NULL, NULL, NULL, NULL, NULL, 'images/project3.jpg', 'Python, Pandas, Plotly, NumPy, Matplotlib', NULL, NULL, NULL, 'https://github.com/gangasagar12', '5', '2026-11-16 03:46:15'),
('6', NULL, 'Sarkari Information & Citizen Portal', NULL, 'Civic technology information portal built to centralize government procedures, municipal document checklists, public service requirements, and citizen inquiry guidelines.', NULL, NULL, NULL, NULL, NULL, 'images/projectcard3.webp', 'Django, Python, PostgreSQL, Modern JS, Responsive UI', NULL, NULL, NULL, 'https://github.com/gangasagar12/sarkari-information-portal.git', '6', '2026-11-16 03:46:15');

DROP TABLE IF EXISTS `academic_projects`;
CREATE TABLE `academic_projects` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `skills` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `detailed_description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'images/projectcard1.jpg',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `academic_projects` (`id`, `title`, `description`, `skills`, `display_order`, `created_at`, `detailed_description`, `image`) VALUES
('1', 'Barcode -based Student Attendance System', 'A desktop-based attendance management system developed using C# .NET and MySQL that automates student attendance recording through barcode-enabled student identification cards.', 'C#, .NET Framework, MySQL, Barcode Integration, Desktop GUI', '1', '2026-11-16 03:46:15', 'Designed and engineered an automated student attendance system utilizing USB barcode scanners to eliminate manual register taking. Built with C# Windows Forms and a normalized MySQL relational database, the system features real-time barcode scanning, automated timestamping, student verification, duplicate check-in prevention, and administrative reporting with export to Excel/PDF. Implemented role-based access control for faculty and administrators, achieving 90% reduction in attendance logging time during departmental trials.', 'images/projectcard2.jpg'),
('2', 'Income and Expense Tracker System', 'Personal finance management software application built as a 3rd Semester Project featuring income and expense tracking, budgeting, reporting, data visualization, and secure user authentication.', 'Python, Django / SQLite, Data Analytics, Financial Logic, User Auth', '2', '2026-11-16 03:46:15', 'Developed a comprehensive personal finance tracking web application using Python and Django with SQLite. The system empowers users to categorize daily income and expenditures, set monthly department budgets with proactive alert thresholds, and visualize cash-flow trends through dynamic charts. Engineered secure multi-user session management, custom Django ORM aggregation queries for monthly summaries, and CSV transaction import/export pipelines.', 'images/projectcard1.jpg'),
('3', 'Social Media CLI Simulator', 'A terminal-based social media simulator built in C++ as a 2nd semester project featuring modular CMake architecture with user registration, posting, feed generation, and follower graphs.', 'C++, CMake, Data Structures, CLI Engine, OOP', '3', '2026-11-16 03:46:15', 'Architected a lightweight console-based social networking engine in modern C++ utilizing custom data structures (graphs, hash maps, and linked lists) to simulate core social media primitives. Features include persistent file-based user profiles, secure password hashing, bidirectional follow graphs, reverse-chronological timeline feeds, and hashtag search indexing. Compiled cleanly across platforms using CMake with zero third-party dependencies.', 'images/projectcard3.webp'),
('4', 'Who Wants to Be a Millionaire Quiz Game', 'A terminal-based quiz game inspired by Who Wants to Be a Millionaire, built in C as a 1st semester project with timed questions, lifelines, multiple categories, and live scoreboard.', 'C Language, Windows Console API, File Handling, Algorithms, Audio Feedback', '4', '2026-11-16 03:46:15', 'Engineered an interactive console quiz game in procedural C featuring the iconic 15-question progression model. Utilized low-level Windows Console API for colored text interfaces, custom timer countdowns, and sound effects. Implemented 3 lifelines (50:50, Phone a Friend, Ask the Audience) using pseudo-random probability algorithms, dynamic question banking from structured binary files, and persistent high-score leaderboards.', 'images/project1.jpg');

DROP TABLE IF EXISTS `other_projects`;
CREATE TABLE `other_projects` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `detailed_description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'images/projectcard3.webp',
  `skills` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `github_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'https://github.com/gangasagar12',
  `live_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'https://github.com/gangasagar12',
  `display_order` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `other_projects` (`id`, `title`, `category`, `description`, `detailed_description`, `image`, `skills`, `github_url`, `live_url`, `display_order`, `created_at`) VALUES
('1', 'Cloud-Native Distributed Log Shipper', 'DevOps & Infrastructure', 'A high-throughput lightweight log shipping daemon that tails container logs, parses structured JSON streams, and indexes into Elasticsearch with backpressure handling.', '', 'images/projectcard3.webp', 'Python, Docker, Elasticsearch, Redis, Go', 'https://github.com/gangasagar12', 'https://github.com/gangasagar12', '1', '2026-11-16 03:46:15'),
('2', 'Distributed Rate Limiter & Token Bucket Gateway', 'Systems & Middleware', 'Custom sliding-window token-bucket rate limiter middleware for Django & FastAPI clusters using Redis atomic Lua scripts to prevent API abuse and DDoS attacks.', '', 'images/projectcard1.jpg', 'Python, FastAPI, Django, Redis, Lua Scripts', 'https://github.com/gangasagar12', 'https://github.com/gangasagar12', '2', '2026-11-16 03:46:15'),
('3', 'Automated Database Backup & S3 Replicator', 'Cloud & Database Automation', 'Scheduled multi-database automated backup daemon that dumps MySQL & PostgreSQL, compresses with zstandard, encrypts with AES-256, and replicates to AWS S3 with instant Slack alerts.', '', 'images/projectcard2.jpg', 'Python, Bash, PostgreSQL, MySQL, AWS S3, Boto3', 'https://github.com/gangasagar12', 'https://github.com/gangasagar12', '3', '2026-11-16 03:46:15'),
('4', 'Stateless JWT Microservices Identity Broker', 'Security & Auth Architecture', 'Centralized stateless identity broker service providing RSA asymmetric token signing, multi-tenant RBAC permissions verification, and real-time token revocation lists.', '', 'images/project2.jpg', 'Python, Django REST, JWT, Cryptography, Docker', 'https://github.com/gangasagar12', 'https://github.com/gangasagar12', '4', '2026-11-16 03:46:15'),
('5', 'Real-Time Linux Host Metric Monitor CLI', 'Systems Engineering', 'Terminal dashboard utility built in C++ that interfaces directly with Linux /proc and sysfs to monitor CPU core states, memory paging, network socket bandwidth, and disk I/O metrics.', '', 'images/project3.jpg', 'C++, Linux sysfs, POSIX Threads, Systems Programming', 'https://github.com/gangasagar12', 'https://github.com/gangasagar12', '5', '2026-11-16 03:46:15');

DROP TABLE IF EXISTS `blog_insights`;
CREATE TABLE `blog_insights` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'images/projectcard1.jpg',
  `read_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int DEFAULT '0',
  `is_published` int DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blog_insights` (`id`, `title`, `slug`, `category`, `image`, `read_time`, `excerpt`, `content`, `date`, `display_order`, `is_published`, `created_at`) VALUES
('1', 'Architecting Scalable Relational Database Schemas for High-Traffic Applications', 'architecting-scalable-relational-database-schemas', 'Database Architecture', 'images/projectcard1.jpg', '5 min read', 'How indexing strategies, foreign key constraints, and 3NF normalization prevent latency spikes in production database backends.', 'In modern backend development, schema design dictates your API response latency more than your server-side code. By adhering strictly to third normal form (3NF) and adding composite indexes on foreign keys, read queries achieve optimal execution plans.', 'Nov 2026', '1', '1', '2026-11-16 03:46:15'),
('2', 'Designing Bulletproof RESTful APIs with Django REST Framework and JWT', 'designing-bulletproof-restful-apis-drf-jwt', 'Backend Engineering', 'images/projectcard1.jpg', '6 min read', 'A pragmatic guide to architecting decoupled, stateless authentication microservices with token refreshing and role-based permissions.', 'Stateless token-based authentication using JSON Web Tokens (JWT) allows multi-platform clients (mobile apps, web SPAs, desktop tools) to consume secure backend services without sticky server sessions.', 'Oct 2026', '2', '1', '2026-11-16 03:46:15'),
('3', 'Optimizing Django QuerySets & Eliminating the Dreaded N+1 Problem', 'optimizing-django-querysets-n-plus-1', 'Python & Django', 'images/projectcard1.jpg', '4 min read', 'Mastering select_related and prefetch_related to drastically reduce SQL roundtrips and improve application throughput.', 'The N+1 query problem is one of the most common causes of slow database transactions in Django applications. Using select_related for single-valued relationships and prefetch_related for many-to-many relationships consolidates hundreds of database queries into single trips.', 'Sep 2026', '3', '1', '2026-11-16 03:46:15');

DROP TABLE IF EXISTS `contact_inquiries`;
CREATE TABLE `contact_inquiries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `company_products`;
CREATE TABLE `company_products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `features` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `tech_pills` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `company_products` (`id`, `title`, `category`, `description`, `image`, `status_text`, `features`, `tech_pills`, `link_url`, `link_text`, `display_order`, `created_at`) VALUES
('1', 'Dental Clinic Management Software', 'Healthcare Operating Platform', 'One of our key products is dental clinic management software, designed to help dental clinics manage their daily operations through a single platform. It brings together essential features such as patient management, appointment scheduling, dental treatment records, billing, expense tracking, and financial reporting. By bringing these processes into one organized system, we aim to reduce manual work, improve accuracy, and help dental professionals spend more time focusing on patient care.', 'images/projectcard1.jpg', 'Live Product', 'Patient Records & Medical History\nReal-time Appointment Scheduling\nDental Treatment & Charting Logs\nAutomated Billing & Invoicing\nClinic Expense & Stock Tracking\nFinancial Analytics & Reports', 'Python, Django, PostgreSQL, REST APIs, Docker', 'https://github.com/gangasagar12', 'View Details', '1', '2026-11-16 09:32:47'),
('2', 'CareFirst Practice & Inventory Management', 'Front-Desk & Inventory Suite', 'Engineered for front-desk efficiency and inventory control. Manages patient queue tokens, waiting room flow, dental consumable supplies, and automated recall notifications with relational ACID database consistency.', 'images/projectcard2.jpg', 'Live Product', 'Front-Desk Patient Intake & Queue\nConsumables & Medicine Inventory\nAutomated Recall Reminders\nRelational ACID Schema', 'Django, Python, MySQL, JavaScript', 'https://github.com/gangasagar12', 'View Details', '2', '2026-11-16 09:32:47'),
('3', 'Business Billing & Accounting Solutions', 'Financial Infrastructure', 'Complete business invoicing, expense tracking, and financial reconciliation software designed to eliminate manual spreadsheet bookkeeping for growing enterprises and healthcare practices.', 'images/project2.jpg', 'Deployed System', 'Itemized Billing & Tax Calculation\nMulti-Payment Ledger Tracking\nAutomated PDF Receipt Dispatch\nReal-time Revenue Summaries', 'Django REST, PostgreSQL, Redis, JWT Auth', 'https://github.com/gangasagar12', 'View Details', '3', '2026-11-16 09:32:47');

DROP TABLE IF EXISTS `experiences`;
CREATE TABLE `experiences` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Active',
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsibilities` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `skills` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `experiences` (`id`, `company`, `role`, `period`, `location`, `status`, `description`, `responsibilities`, `skills`, `display_order`, `created_at`) VALUES
('1', 'Strataflow Technologies Pvt. Ltd.', 'Co-Founder & Technical Lead', '2026 – Present', 'Kathmandu, Nepal', 'Active', 'Directing system architecture, database optimization, and scalable backend services for client institutions and in-house enterprise SaaS products.', 'Lead the company technical strategy, software development direction, and overall technology operations.\nOversee system architecture, technology selection, development standards, and the delivery of scalable software solutions.\nDrive product innovation, coordinate technical teams, and align technology initiatives with business goals and client requirements.', 'Python, Django, PostgreSQL, REST Framework, AWS, System Architecture, MySQL, Linux', '1', '2026-11-16 09:32:47'),
('2', 'Self-Employed', 'Freelance Software Developer', '2025 – 2026', 'Kathmandu, Nepal', 'Completed', 'Providing independent software development services, building custom web applications and backend solutions based on client requirements.', 'Developed custom web applications and backend solutions for individual clients and businesses.\nManaged project requirements, development, testing, and delivery independently.\nCollaborated with clients to understand their needs and implement practical software solutions.', 'Python, Django, MySQL, PostgreSQL, REST APIs, Git', '2', '2026-11-16 09:32:47');

DROP TABLE IF EXISTS `awards`;
CREATE TABLE `awards` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issuer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `awards` (`id`, `title`, `issuer`, `date`, `image`, `description`, `display_order`, `created_at`) VALUES
('1', 'Excellence in Software Engineering & Database Systems', 'Kantipur City College & Purbanchal University', '2025', 'images/projectcard1.jpg', 'Honored for outstanding academic performance, complex relational database architecture design, and high-performance server-side system implementations.', '1', '2026-11-16 09:32:47'),
('2', 'Best Clinical Architecture & HealthTech Innovation', 'Inovix & Health System Stakeholders', '2024', 'images/projectcard2.jpg', 'Recognized for designing Oralix Dental Practice OS, unifying multi-chair scheduling, electronic health records, and automated clinic billing engines.', '2', '2026-11-16 09:32:47'),
('3', 'Open Source Backend Engineering Recognition', 'Developer Community & Tech Hackathon', '2023', 'images/projectcard3.webp', 'Awarded for developing high-throughput Python RESTful APIs, scalable JWT auth services, and contributing maintainable database blueprints.', '3', '2026-11-16 09:32:47');

DROP TABLE IF EXISTS `promotions`;
CREATE TABLE `promotions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Claim Offer',
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '#contact',
  `is_active` int DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `promotions` (`id`, `title`, `description`, `image`, `button_text`, `button_url`, `is_active`, `created_at`, `updated_at`) VALUES
('1', 'Special Offer: Custom Web & SaaS Development', 'Planning to build a high-performance web application, backend API, or specialized business platform? Get a free architecture consultation and priority engineering kickoff for your project.', 'images/projectcard1.jpg', 'Claim Consultation', '#contact', '1', '2026-11-16 03:46:15', '2026-11-16 03:46:15');

SET FOREIGN_KEY_CHECKS = 1;
