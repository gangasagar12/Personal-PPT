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
    skills {$textType} NOT NULL,
    display_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// 3. Blog & Insights Table
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
    $stmt_acad = $pdo->prepare("INSERT INTO academic_projects (title, description, skills, display_order) VALUES (?, ?, ?, ?)");

    $academics = [
        [
            'Barcode -based Student Attendance System',
            'A desktop-based attendance management system developed using C# .NET and MySQL that automates student attendance recording through barcode-enabled student identification cards.',
            'C#, .NET Framework, MySQL, Barcode Integration, Desktop GUI',
            1
        ],
        [
            'Income and Expense Tracker System',
            'Personal finance management software application built as a 3rd Semester Project with ,featuring income and expense tracking, budgeting, reporting, data visualization, and secure user authentication.',
            'Python, Django / SQLite, Data Analytics, Financial Logic, User Auth',
            2
        ],
        [
            'Social Media CLI Simulator',
            'A terminal-based social media simulator built in C++ as a 2nd semester project.it modular CMake version with features like user registration, posting, and following.',
            'C++, CMake, Data Structures, CLI Engine, OOP',
            3
        ],
        [
            'Who Wants to Be a Millionaire Quiz Game',
            'A terminal-based quiz game inspired by Who Wants to Be a Millionaire, built in C as a 1st semester project. Features timed questions, lifelines, multiple categories, a live scoreboard, and audio feedback — all running in the Windows console.',
            'C Language, Windows Console API, File Handling, Algorithms, Audio Feedback',
            4
        ]
    ];

    foreach ($academics as $a) {
        $stmt_acad->execute($a);
    }
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

