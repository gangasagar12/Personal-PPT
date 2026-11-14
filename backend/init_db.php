<?php
require_once __DIR__ . '/db.php';

$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$autoIncrement = ($driver === 'sqlite') ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';
$textType = ($driver === 'sqlite') ? 'TEXT' : 'VARCHAR(255)';
$longTextType = ($driver === 'sqlite') ? 'TEXT' : 'LONGTEXT';

// 1. Featured Projects Table
$pdo->exec("CREATE TABLE IF NOT EXISTS featured_projects (
    id {$autoIncrement},
    title {$textType} NOT NULL,
    description {$longTextType} NOT NULL,
    image {$textType} NOT NULL,
    skills {$textType} NOT NULL,
    live_url {$textType} NOT NULL,
    display_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// 2. Academic Projects Table
$pdo->exec("CREATE TABLE IF NOT EXISTS academic_projects (
    id {$autoIncrement},
    title {$textType} NOT NULL,
    description {$longTextType} NOT NULL,
    detailed_description {$longTextType} DEFAULT '',
    image {$textType} DEFAULT 'images/projectcard1.jpg',
    skills {$textType} NOT NULL,
    display_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Safely add missing columns to academic_projects if they don't exist
try {
    $pdo->exec("ALTER TABLE academic_projects ADD COLUMN detailed_description {$longTextType} DEFAULT ''");
} catch (\Exception $e) {}
try {
    $pdo->exec("ALTER TABLE academic_projects ADD COLUMN image {$textType} DEFAULT 'images/projectcard1.jpg'");
} catch (\Exception $e) {}

// 3. Other Projects Table (Some Other Projects)
$pdo->exec("CREATE TABLE IF NOT EXISTS other_projects (
    id {$autoIncrement},
    title {$textType} NOT NULL,
    category {$textType} NOT NULL,
    description {$longTextType} NOT NULL,
    image {$textType} DEFAULT 'images/projectcard3.webp',
    skills {$textType} NOT NULL,
    github_url {$textType} DEFAULT 'https://github.com/gangasagar12',
    live_url {$textType} DEFAULT 'https://github.com/gangasagar12',
    display_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// 4. Blog & Insights Table
$pdo->exec("CREATE TABLE IF NOT EXISTS blog_insights (
    id {$autoIncrement},
    title {$textType} NOT NULL,
    slug {$textType} NOT NULL,
    category {$textType} NOT NULL,
    read_time {$textType} NOT NULL,
    excerpt {$longTextType} NOT NULL,
    content {$longTextType} NOT NULL,
    date {$textType} NOT NULL,
    display_order INTEGER DEFAULT 0,
    is_published INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// 4. Contact Inquiries Table
$pdo->exec("CREATE TABLE IF NOT EXISTS contact_inquiries (
    id {$autoIncrement},
    name {$textType} NOT NULL,
    email {$textType} NOT NULL,
    subject {$textType} NOT NULL,
    phone {$textType},
    message {$longTextType} NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Seed Featured Projects if table is empty
$count = $pdo->query("SELECT COUNT(*) FROM featured_projects")->fetchColumn();
if ($count == 0) {
    $stmt = $pdo->prepare("INSERT INTO featured_projects (title, description, image, skills, live_url, display_order) VALUES (?, ?, ?, ?, ?, ?)");

    $featured = [
        [
            'Oralix — Dental Practice OS',
            'Multi-chair clinical operations platform engineered for modern dental practices. Unifies real-time operatory chair scheduling, electronic health records (EHR), dental charting, and automated billing.',
            'images/projectcard1.jpg',
            'Python, Django, PostgreSQL, REST APIs, Docker',
            'https://github.com/gangasaagar',
            1
        ],
        [
            'CareFirst Dental Management',
            'Specialized clinical management suite designed to streamline dental front-desk check-ins, chair appointment queues, and dental inventory with relational database consistency.',
            'images/projectcard2.jpg',
            'Django, Python, MySQL, HTML5 / CSS3, JavaScript',
            'https://github.com/gangasaagar',
            2
        ],
        [
            'Smart Library Management System',
            'End-to-end automated library cataloging and circulation tracking portal. Handles book inventory categorization, student member checkouts, and fine calculation engines.',
            'images/project1.jpg',
            'Django, Python, SQLite, JavaScript, Bootstrap',
            'https://github.com/gangasaagar',
            3
        ],
        [
            'Scalable E-Commerce REST APIs',
            'High-throughput enterprise e-commerce backend built with Django REST Framework. Architected with normalized PostgreSQL models, stateless JWT authentication, and order transactions.',
            'images/project2.jpg',
            'Django REST, Python, PostgreSQL, JWT Auth, Redis',
            'https://github.com/gangasaagar',
            4
        ],
        [
            'Data Analytics & Visualization Dashboard',
            'Interactive exploratory analytics dashboard designed to parse, clean, and visualize multi-dimensional datasets with Pandas, Plotly, and Matplotlib.',
            'images/project3.jpg',
            'Python, Pandas, Plotly, NumPy, Matplotlib',
            'https://github.com/gangasaagar',
            5
        ],
        [
            'Sarkari Information & Citizen Portal',
            'Civic technology information portal built to centralize government procedures, municipal document checklists, public service requirements, and citizen inquiry guidelines.',
            'images/projectcard3.webp',
            'Django, Python, PostgreSQL, Modern JS, Responsive UI',
            'https://github.com/gangasaagar/sarkari-information-portal.git',
            6
        ]
    ];

    foreach ($featured as $p) {
        $stmt->execute($p);
    }
}

// Seed Academic Projects if table is empty
$count_acad = $pdo->query("SELECT COUNT(*) FROM academic_projects")->fetchColumn();
if ($count_acad == 0) {
    $stmt_acad = $pdo->prepare("INSERT INTO academic_projects (title, description, detailed_description, image, skills, display_order) VALUES (?, ?, ?, ?, ?, ?)");

    $academics = [
        [
            'Barcode -based Student Attendance System',
            'A desktop-based attendance management system developed using C# .NET and MySQL that automates student attendance recording through barcode-enabled student identification cards.',
            'Designed and engineered an automated student attendance system utilizing USB barcode scanners to eliminate manual register taking. Built with C# Windows Forms and a normalized MySQL relational database, the system features real-time barcode scanning, automated timestamping, student verification, duplicate check-in prevention, and administrative reporting with export to Excel/PDF. Implemented role-based access control for faculty and administrators, achieving 90% reduction in attendance logging time during departmental trials.',
            'images/projectcard2.jpg',
            'C#, .NET Framework, MySQL, Barcode Integration, Desktop GUI',
            1
        ],
        [
            'Income and Expense Tracker System',
            'Personal finance management software application built as a 3rd Semester Project featuring income and expense tracking, budgeting, reporting, data visualization, and secure user authentication.',
            'Developed a comprehensive personal finance tracking web application using Python and Django with SQLite. The system empowers users to categorize daily income and expenditures, set monthly department budgets with proactive alert thresholds, and visualize cash-flow trends through dynamic charts. Engineered secure multi-user session management, custom Django ORM aggregation queries for monthly summaries, and CSV transaction import/export pipelines.',
            'images/projectcard1.jpg',
            'Python, Django / SQLite, Data Analytics, Financial Logic, User Auth',
            2
        ],
        [
            'Social Media CLI Simulator',
            'A terminal-based social media simulator built in C++ as a 2nd semester project featuring modular CMake architecture with user registration, posting, feed generation, and follower graphs.',
            'Architected a lightweight console-based social networking engine in modern C++ utilizing custom data structures (graphs, hash maps, and linked lists) to simulate core social media primitives. Features include persistent file-based user profiles, secure password hashing, bidirectional follow graphs, reverse-chronological timeline feeds, and hashtag search indexing. Compiled cleanly across platforms using CMake with zero third-party dependencies.',
            'images/projectcard3.webp',
            'C++, CMake, Data Structures, CLI Engine, OOP',
            3
        ],
        [
            'Who Wants to Be a Millionaire Quiz Game',
            'A terminal-based quiz game inspired by Who Wants to Be a Millionaire, built in C as a 1st semester project with timed questions, lifelines, multiple categories, and live scoreboard.',
            'Engineered an interactive console quiz game in procedural C featuring the iconic 15-question progression model. Utilized low-level Windows Console API for colored text interfaces, custom timer countdowns, and sound effects. Implemented 3 lifelines (50:50, Phone a Friend, Ask the Audience) using pseudo-random probability algorithms, dynamic question banking from structured binary files, and persistent high-score leaderboards.',
            'images/project1.jpg',
            'C Language, Windows Console API, File Handling, Algorithms, Audio Feedback',
            4
        ]
    ];

    foreach ($academics as $a) {
        $stmt_acad->execute($a);
    }
} else {
    // Ensure existing academic projects have image and detailed description populated
    $p1 = $pdo->prepare("UPDATE academic_projects SET image = ?, detailed_description = ? WHERE id = ? AND (detailed_description IS NULL OR detailed_description = '' OR image IS NULL OR image = '')");
    $p1->execute([
        'images/projectcard2.jpg',
        'Designed and engineered an automated student attendance system utilizing USB barcode scanners to eliminate manual register taking. Built with C# Windows Forms and a normalized MySQL relational database, the system features real-time barcode scanning, automated timestamping, student verification, duplicate check-in prevention, and administrative reporting with export to Excel/PDF. Implemented role-based access control for faculty and administrators, achieving 90% reduction in attendance logging time during departmental trials.',
        1
    ]);
    $p1->execute([
        'images/projectcard1.jpg',
        'Developed a comprehensive personal finance tracking web application using Python and Django with SQLite. The system empowers users to categorize daily income and expenditures, set monthly department budgets with proactive alert thresholds, and visualize cash-flow trends through dynamic charts. Engineered secure multi-user session management, custom Django ORM aggregation queries for monthly summaries, and CSV transaction import/export pipelines.',
        2
    ]);
    $p1->execute([
        'images/projectcard3.webp',
        'Architected a lightweight console-based social networking engine in modern C++ utilizing custom data structures (graphs, hash maps, and linked lists) to simulate core social media primitives. Features include persistent file-based user profiles, secure password hashing, bidirectional follow graphs, reverse-chronological timeline feeds, and hashtag search indexing. Compiled cleanly across platforms using CMake with zero third-party dependencies.',
        3
    ]);
    $p1->execute([
        'images/project1.jpg',
        'Engineered an interactive console quiz game in procedural C featuring the iconic 15-question progression model. Utilized low-level Windows Console API for colored text interfaces, custom timer countdowns, and sound effects. Implemented 3 lifelines (50:50, Phone a Friend, Ask the Audience) using pseudo-random probability algorithms, dynamic question banking from structured binary files, and persistent high-score leaderboards.',
        4
    ]);
}

// Seed Blog & Insights if table is empty
$count_blog = $pdo->query("SELECT COUNT(*) FROM blog_insights")->fetchColumn();
if ($count_blog == 0) {
    $stmt_blog = $pdo->prepare("INSERT INTO blog_insights (title, slug, category, read_time, excerpt, content, date, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $blogs = [
        [
            'Architecting Scalable Relational Database Schemas for High-Traffic Applications',
            'architecting-scalable-relational-database-schemas',
            'Database Architecture',
            '5 min read',
            'How indexing strategies, foreign key constraints, and 3NF normalization prevent latency spikes in production database backends.',
            'In modern backend development, schema design dictates your API response latency more than your server-side code. By adhering strictly to third normal form (3NF) and adding composite indexes on foreign keys, read queries achieve optimal execution plans.',
            'Nov 2026',
            1
        ],
        [
            'Designing Bulletproof RESTful APIs with Django REST Framework and JWT',
            'designing-bulletproof-restful-apis-drf-jwt',
            'Backend Engineering',
            '6 min read',
            'A pragmatic guide to architecting decoupled, stateless authentication microservices with token refreshing and role-based permissions.',
            'Stateless token-based authentication using JSON Web Tokens (JWT) allows multi-platform clients (mobile apps, web SPAs, desktop tools) to consume secure backend services without sticky server sessions.',
            'Oct 2026',
            2
        ],
        [
            'Optimizing Django QuerySets & Eliminating the Dreaded N+1 Problem',
            'optimizing-django-querysets-n-plus-1',
            'Python & Django',
            '4 min read',
            'Mastering select_related and prefetch_related to drastically reduce SQL roundtrips and improve application throughput.',
            'The N+1 query problem is one of the most common causes of slow database transactions in Django applications. Using select_related for single-valued relationships and prefetch_related for many-to-many relationships consolidates hundreds of database queries into single trips.',
            'Sep 2026',
            3
        ]
    ];

    foreach ($blogs as $b) {
        $stmt_blog->execute($b);
    }
}

// Seed Other Projects if table is empty
$count_other = $pdo->query("SELECT COUNT(*) FROM other_projects")->fetchColumn();
if ($count_other == 0) {
    $stmt_other = $pdo->prepare("INSERT INTO other_projects (title, category, description, image, skills, github_url, live_url, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $others = [
        [
            'Cloud-Native Distributed Log Shipper',
            'DevOps & Infrastructure',
            'A high-throughput lightweight log shipping daemon that tails container logs, parses structured JSON streams, and indexes into Elasticsearch with backpressure handling.',
            'images/projectcard3.webp',
            'Python, Docker, Elasticsearch, Redis, Go',
            'https://github.com/gangasagar12',
            'https://github.com/gangasagar12',
            1
        ],
        [
            'Distributed Rate Limiter & Token Bucket Gateway',
            'Systems & Middleware',
            'Custom sliding-window token-bucket rate limiter middleware for Django & FastAPI clusters using Redis atomic Lua scripts to prevent API abuse and DDoS attacks.',
            'images/projectcard1.jpg',
            'Python, FastAPI, Django, Redis, Lua Scripts',
            'https://github.com/gangasagar12',
            'https://github.com/gangasagar12',
            2
        ],
        [
            'Automated Database Backup & S3 Replicator',
            'Cloud & Database Automation',
            'Scheduled multi-database automated backup daemon that dumps MySQL & PostgreSQL, compresses with zstandard, encrypts with AES-256, and replicates to AWS S3 with instant Slack alerts.',
            'images/projectcard2.jpg',
            'Python, Bash, PostgreSQL, MySQL, AWS S3, Boto3',
            'https://github.com/gangasagar12',
            'https://github.com/gangasagar12',
            3
        ],
        [
            'Stateless JWT Microservices Identity Broker',
            'Security & Auth Architecture',
            'Centralized stateless identity broker service providing RSA asymmetric token signing, multi-tenant RBAC permissions verification, and real-time token revocation lists.',
            'images/project2.jpg',
            'Python, Django REST, JWT, Cryptography, Docker',
            'https://github.com/gangasagar12',
            'https://github.com/gangasagar12',
            4
        ],
        [
            'Real-Time Linux Host Metric Monitor CLI',
            'Systems Engineering',
            'Terminal dashboard utility built in C++ that interfaces directly with Linux /proc and sysfs to monitor CPU core states, memory paging, network socket bandwidth, and disk I/O metrics.',
            'images/project3.jpg',
            'C++, Linux sysfs, POSIX Threads, Systems Programming',
            'https://github.com/gangasagar12',
            'https://github.com/gangasagar12',
            5
        ]
    ];

    foreach ($others as $o) {
        $stmt_other->execute($o);
    }
}

