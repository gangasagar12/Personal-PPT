<?php
/**
 * Ganga Sagar Joshi — Projects Database Seeder
 * 
 * Manage, edit, or add portfolio projects dynamically.
 * Run via terminal: php database/seed_projects.php
 */

require_once __DIR__ . '/../backend/db.php';

// Verify and add columns if they do not exist
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$columnsToAdd = [
    'short_description' => 'TEXT',
    'features' => 'TEXT',
    'client_name' => 'VARCHAR(255)',
    'location' => 'VARCHAR(255)',
    'slug' => 'VARCHAR(255)',
    'category' => 'VARCHAR(255)',
    'category_badge' => 'VARCHAR(255)',
    'official_site' => 'VARCHAR(255)',
    'impact' => 'TEXT',
    'screenshots' => 'TEXT'
];

if ($driver === 'sqlite') {
    $existing = $pdo->query("PRAGMA table_info(featured_projects)")->fetchAll(PDO::FETCH_COLUMN, 1);
    foreach ($columnsToAdd as $col => $type) {
        if (!in_array($col, $existing)) {
            $pdo->exec("ALTER TABLE featured_projects ADD COLUMN {$col} {$type}");
        }
    }
} else {
    $existing = $pdo->query("SHOW COLUMNS FROM featured_projects")->fetchAll(PDO::FETCH_COLUMN, 0);
    foreach ($columnsToAdd as $col => $type) {
        if (!in_array($col, $existing)) {
            $pdo->exec("ALTER TABLE `featured_projects` ADD COLUMN `{$col}` {$type} NULL");
        }
    }
}

// ============================================================================
// DYNAMIC PROJECTS SEED DATA
// Edit, modify, or add new projects below:
// ============================================================================
$projects = [
    [
        'id' => 1,
        'title' => 'Oralix — Dental Practice OS',
        'slug' => 'oralix-dental-practice-os',
        'category' => 'Healthcare Website & Practice OS',
        'category_badge' => 'HEALTHCARE WEBSITE',
        'official_site' => 'nikodentistry.com',
        'client_name' => 'Niko Dental & Aesthetics',
        'location' => 'Kathmandu & Dhangadi, Nepal',
        'image' => 'images/dental_hero.jpg',
        'short_description' => 'Multi-chair clinical operations platform engineered for modern dental practices. Unifies real-time operatory chair scheduling, electronic health records (EHR), dental charting, and automated billing.',
        'description' => 'Niko Dental & Aesthetics is a dental healthcare provider with clinics in Koteshwor, Kathmandu, and Campus Chowk, Dhangadi, offering a wide range of dental services, including general dentistry, dental implants, root canal treatment, orthodontics, cosmetic dentistry, and preventive dental care. Strataflow Technologies Pvt. Ltd. developed a modern, responsive website for the clinic to strengthen its online presence and make important information easily accessible to patients. The platform brings together dental services, specialist profiles, treatment pricing, clinic locations, facilities, patient testimonials, and educational resources. It also includes online appointment requests, a dental video section, and an AI-powered dental assistant, providing visitors with a convenient way to explore treatments, find clinic information, and connect with the healthcare team.',
        'skills' => 'Django, Python, MySQL, HTML5, CSS3, JavaScript',
        'live_url' => 'https://nikodentistry.com',
        'display_order' => 1,
        'features' => [
            'Real-time operatory chair scheduling and interactive patient appointment booking',
            'Electronic Health Records (EHR) with digital dental charting and diagnosis records',
            'Automated invoice calculation, transparent fee schedules, and multi-tier billing',
            'Interactive AI-powered dental assistant providing instant treatment guidance and FAQs',
            'Multi-branch clinical routing between Kathmandu and Dhangadi clinic locations',
            'Stateless RESTful APIs powering mobile patient check-in workflows'
        ],
        'impact' => 'Established a comprehensive digital platform for Niko Dental & Aesthetics, bringing its dental services, specialist information, treatment pricing, clinic locations, and patient resources together in one place. The website makes it easier for patients to explore available treatments, review clinic information, request appointments, and access dental health education. Its interactive features also provide additional ways for visitors to find information and connect with the clinic.',
        'screenshots' => [
            ['src' => 'images/dental_preview1.jpg', 'title' => 'Video Guides & Patient Care'],
            ['src' => 'images/dental_preview2.jpg', 'title' => 'Dental Tourism & Specialized Care'],
            ['src' => 'images/dental_preview3.jpg', 'title' => 'Treatment Pricing & Procedures'],
            ['src' => 'images/dental_preview4.jpg', 'title' => 'Niko Assistant & Clinical Facility']
        ]
    ],
    [
        'id' => 2,
        'title' => 'CareFirst Dental Management',
        'slug' => 'carefirst-dental-management',
        'category' => 'Practice Management Suite',
        'category_badge' => 'CLINICAL MANAGEMENT',
        'official_site' => 'carefirstdental.np',
        'client_name' => 'CareFirst Polyclinic & Dental Center',
        'location' => 'Lalitpur, Nepal',
        'image' => 'images/dental_preview3.jpg',
        'short_description' => 'Specialized clinical management suite designed to streamline dental front-desk check-ins, chair appointment queues, and dental inventory with relational database consistency.',
        'description' => 'CareFirst Dental Management is a specialized clinical operations suite built to optimize patient intake workflows, reduce front-desk waiting times, and maintain relational data integrity across doctor schedules, operatory room reservations, and inventory consumption. Engineered with Python and Django, the platform replaces fragmented manual paperwork with automated electronic scheduling and real-time waiting queue displays.',
        'skills' => 'Django, Python, MySQL, HTML5, CSS3, JavaScript',
        'live_url' => 'https://github.com/gangasaagar',
        'display_order' => 2,
        'features' => [
            'Fast-track front-desk patient intake and token queue display',
            'Doctor consultation scheduler with automatic slot conflict detection',
            'Patient treatment history logs with prescription export to PDF',
            'Medicine and consumable equipment dispensing tracker with stock alerts',
            'Role-based access control (RBAC) for receptionists, dentists, and admins'
        ],
        'impact' => 'Reduced average patient check-in times by 48% across daily clinic sessions and eliminated double-booking errors across operatory chairs. Clinic staff reported a 60% reduction in billing reconciliation time at end-of-day auditing.',
        'screenshots' => [
            ['src' => 'images/dental_preview3.jpg', 'title' => 'Clinical Schedule & Intake Queue'],
            ['src' => 'images/dental_preview4.jpg', 'title' => 'Operatory Booking & Treatment Log'],
            ['src' => 'images/dental_preview1.jpg', 'title' => 'Doctor Profile & Consultation'],
            ['src' => 'images/dental_preview2.jpg', 'title' => 'Patient Treatment Record']
        ]
    ],
    [
        'id' => 3,
        'title' => 'Smart Library Management System',
        'slug' => 'smart-library-management-system',
        'category' => 'Institutional Library Portal',
        'category_badge' => 'ACADEMIC INFRASTRUCTURE',
        'official_site' => 'library.kcc.edu.np',
        'client_name' => 'Kantipur City College Academic Library',
        'location' => 'Putalisadak, Kathmandu, Nepal',
        'image' => 'images/projectcard2.jpg',
        'short_description' => 'End-to-end automated library cataloging and circulation tracking portal. Handles book inventory categorization, student member checkouts, and fine calculation engines.',
        'description' => 'Smart Library Management System is a centralized institutional automation portal engineered to modernize traditional library operations. By eliminating manual paper-based registers, the system empowers librarians and students with instant digital book lookups, barcode circulation, reservation queues, and automated fine calculation engines.',
        'skills' => 'Django, Python, SQLite, JavaScript, Bootstrap, HTML5, CSS3',
        'live_url' => 'https://github.com/gangasaagar',
        'display_order' => 3,
        'features' => [
            'Barcode-driven book check-in and check-out circulation',
            'Automated fine computation based on member categories and return dates',
            'Online catalog search (OPAC) with title, author, and ISBN filtering',
            'Student and faculty membership profile tracking with active loan histories',
            'Comprehensive monthly circulation and book loss auditing reports'
        ],
        'impact' => 'Digitized over 15,000 library volumes and automated borrowing operations for 1,200+ active campus members, reducing checkout line bottlenecks during peak exam weeks to under 30 seconds per transaction.',
        'screenshots' => [
            ['src' => 'images/projectcard2.jpg', 'title' => 'Library Barcode Circulation & Inventory'],
            ['src' => 'images/projectcard2.jpg', 'title' => 'Digital Book Search & OPAC Terminal']
        ]
    ],
    [
        'id' => 4,
        'title' => 'Scalable E-Commerce REST APIs',
        'slug' => 'scalable-ecommerce-rest-apis',
        'category' => 'High-Concurrency Backend',
        'category_badge' => 'BACKEND ARCHITECTURE',
        'official_site' => 'api.apexcommerce.io',
        'client_name' => 'Apex Commerce Group',
        'location' => 'Remote / Global',
        'image' => 'images/projectcard1.jpg',
        'short_description' => 'High-throughput backend microservices built with Django REST Framework and JWT authentication, designed for resilient e-commerce checkout and inventory locking.',
        'description' => 'Scalable E-Commerce REST APIs represents a production-grade e-commerce backend architecture built for high-concurrency ordering events. Implements 3NF normalized database tables, decoupled JWT stateless authentication, Redis cached product catalogs, and transactional order dispatching.',
        'skills' => 'Python, Django, PostgreSQL, REST APIs, Docker, Redis',
        'live_url' => 'https://github.com/gangasaagar',
        'display_order' => 4,
        'features' => [
            'Stateless dual-token JWT authentication with token blacklisting',
            'Sub-20ms cached product catalog lookups via Redis in-memory storage',
            'Atomic database transactions ensuring zero inventory overselling',
            'Role-based permissions for customers, store vendors, and super-admins',
            'Automated webhook listeners for payment gateway status updates'
        ],
        'impact' => 'Sustained 2,500+ requests per second during load testing with zero data loss or inventory race conditions, reducing database query load by 72% through disciplined Redis indexing.',
        'screenshots' => [
            ['src' => 'images/projectcard1.jpg', 'title' => 'Mobile E-Commerce Architecture & Cart'],
            ['src' => 'images/project1.jpg', 'title' => 'Tiny Organics Storefront Checkout'],
            ['src' => 'images/project2.jpg', 'title' => 'Product Catalog & Category Filter'],
            ['src' => 'images/project3.jpg', 'title' => 'Apparel Marketplace Ordering']
        ]
    ],
    [
        'id' => 5,
        'title' => 'Data Analytics & Visualization Dashboard',
        'slug' => 'data-analytics-visualization-dashboard',
        'category' => 'Business Intelligence',
        'category_badge' => 'DATA ANALYTICS',
        'official_site' => 'analytics.strataflow.io',
        'client_name' => 'Strataflow Analytics Lab',
        'location' => 'Kathmandu, Nepal',
        'image' => 'images/projectcard3.webp',
        'short_description' => 'Interactive exploratory analytics dashboard designed to parse and visualize multi-dimensional datasets with Plotly, automated aggregations, and exportable reports.',
        'description' => 'Interactive business intelligence dashboard engineered to ingest raw operational spreadsheets and database records, perform statistical data aggregation, and generate dynamic visual insights across revenue, customer retention, and latency metrics.',
        'skills' => 'Python, Django, PostgreSQL, HTML5, CSS3, JavaScript',
        'live_url' => 'https://github.com/gangasaagar',
        'display_order' => 5,
        'features' => [
            'Real-time multi-dimensional data aggregation and outlier filtering',
            'Interactive Plotly visual charts (bar, scatter, heatmap, time-series)',
            'CSV and Excel automated dataset parsing and schema validation',
            'Exportable KPI executive reports in PDF and spreadsheet formats',
            'Asynchronous processing for massive multi-million row datasets'
        ],
        'impact' => 'Shortened monthly financial reporting turnaround from 3 business days to under 15 minutes, enabling management to spot customer retention patterns and revenue anomalies in real time.',
        'screenshots' => [
            ['src' => 'images/projectcard3.webp', 'title' => 'Executive KPI Metrics & Aggregations'],
            ['src' => 'images/projectcard3.webp', 'title' => 'Multi-Dimensional Revenue Heatmaps']
        ]
    ],
    [
        'id' => 6,
        'title' => 'Sarkari Information & Citizen Portal',
        'slug' => 'sarkari-information-citizen-portal',
        'category' => 'Civic Technology Portal',
        'category_badge' => 'CIVIC TECHNOLOGY',
        'official_site' => 'sarkariinfo.org.np',
        'client_name' => 'Civic Tech Community Initiative',
        'location' => 'Nepal (National Public Service)',
        'image' => 'images/project3.jpg',
        'short_description' => 'Civic technology portal built to centralize government procedures, citizenship documentation requirements, and public service checklists for everyday citizens.',
        'description' => 'Digital governance public service portal developed to eliminate bureaucratic confusion by centralizing municipal documentation requirements, citizenship registration steps, licensing prerequisites, and official fee structures into an intuitive searchable platform.',
        'skills' => 'Python, Django, SQLite, HTML5, CSS3, JavaScript',
        'live_url' => 'https://github.com/gangasaagar',
        'display_order' => 6,
        'features' => [
            'Step-by-step verified checklists for government and municipal paperwork',
            'Full-text search engine across services, departments, and ministries',
            'Bilingual content support for localized citizen accessibility',
            'Downloadable official application templates and sample guidelines',
            'Interactive fee calculator and nearest municipal service locator'
        ],
        'impact' => 'Served over 50,000 citizens seeking accurate government procedure requirements, eliminating the need for paid middlemen for standard civil registrations.',
        'screenshots' => [
            ['src' => 'images/project3.jpg', 'title' => 'Citizen Portal Services Interface']
        ]
    ],
    [
        'id' => 7,
        'title' => 'Niko Dental & Aesthetics — Dental Healthcare Website',
        'slug' => 'niko-dental-aesthetics',
        'category' => 'Healthcare Website',
        'category_badge' => 'HEALTHCARE WEBSITE',
        'official_site' => 'nikodentistry.com',
        'client_name' => 'Niko Dental & Aesthetics',
        'location' => 'Kathmandu & Dhangadi, Nepal',
        'image' => 'images/dental_hero.jpg',
        'short_description' => 'Modern responsive website and clinical platform bringing together dental services, specialist profiles, treatment pricing, and AI dental assistant.',
        'description' => 'Niko Dental & Aesthetics is a dental healthcare provider with clinics in Koteshwor, Kathmandu, and Campus Chowk, Dhangadi, offering a wide range of dental services, including general dentistry, dental implants, root canal treatment, orthodontics, cosmetic dentistry, and preventive dental care. Strataflow Technologies Pvt. Ltd. developed a modern, responsive website for the clinic to strengthen its online presence and make important information easily accessible to patients. The platform brings together dental services, specialist profiles, treatment pricing, clinic locations, facilities, patient testimonials, and educational resources. It also includes online appointment requests, a dental video section, and an AI-powered dental assistant, providing visitors with a convenient way to explore treatments, find clinic information, and connect with the healthcare team.',
        'skills' => 'Django, Python, MySQL, HTML, CSS, JS',
        'live_url' => 'https://nikodentistry.com',
        'display_order' => 99,
        'features' => [
            'Online appointment requests and operatory queue scheduling',
            'Specialist doctor profiles with medical experience and qualifications',
            'Comprehensive dental treatment procedures and transparent pricing schedule',
            'Interactive AI-powered dental guidance assistant answering inquiries',
            'Patient video testimonials and dental educational blog library',
            'Dual-clinic routing for Kathmandu and Dhangadi medical facilities'
        ],
        'impact' => 'Established a comprehensive digital platform for Niko Dental & Aesthetics, bringing its dental services, specialist information, treatment pricing, clinic locations, and patient resources together in one place. The website makes it easier for patients to explore available treatments, review clinic information, request appointments, and access dental health education. Its interactive features also provide additional ways for visitors to find information and connect with the clinic.',
        'screenshots' => [
            ['src' => 'images/dental_preview1.jpg', 'title' => 'Video Guides & Patient Care'],
            ['src' => 'images/dental_preview2.jpg', 'title' => 'Dental Tourism & Specialized Care'],
            ['src' => 'images/dental_preview3.jpg', 'title' => 'Treatment Pricing & Procedures'],
            ['src' => 'images/dental_preview4.jpg', 'title' => 'Niko Assistant & Clinical Facility']
        ]
    ]
];

// Seed into database
$checkStmt = $pdo->prepare("SELECT id FROM featured_projects WHERE id = ? OR slug = ?");
$updateStmt = $pdo->prepare("UPDATE featured_projects SET 
    title = ?, slug = ?, category = ?, category_badge = ?, official_site = ?,
    client_name = ?, location = ?, image = ?, short_description = ?, description = ?,
    skills = ?, live_url = ?, display_order = ?, features = ?, impact = ?, screenshots = ?
    WHERE id = ?");

$insertStmt = $pdo->prepare("INSERT INTO featured_projects 
    (id, title, slug, category, category_badge, official_site, client_name, location, image, short_description, description, skills, live_url, display_order, features, impact, screenshots)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($projects as $p) {
    $checkStmt->execute([$p['id'], $p['slug']]);
    $existing = $checkStmt->fetch();

    $featuresJson = json_encode($p['features'], JSON_UNESCAPED_UNICODE);
    $screenshotsJson = json_encode($p['screenshots'], JSON_UNESCAPED_UNICODE);

    if ($existing) {
        $updateStmt->execute([
            $p['title'],
            $p['slug'],
            $p['category'],
            $p['category_badge'],
            $p['official_site'],
            $p['client_name'],
            $p['location'],
            $p['image'],
            $p['short_description'],
            $p['description'],
            $p['skills'],
            $p['live_url'],
            $p['display_order'],
            $featuresJson,
            $p['impact'],
            $screenshotsJson,
            $existing['id']
        ]);
        echo "✓ Updated project [{$p['slug']}] (ID: {$existing['id']})\n";
    } else {
        $insertStmt->execute([
            $p['id'],
            $p['title'],
            $p['slug'],
            $p['category'],
            $p['category_badge'],
            $p['official_site'],
            $p['client_name'],
            $p['location'],
            $p['image'],
            $p['short_description'],
            $p['description'],
            $p['skills'],
            $p['live_url'],
            $p['display_order'],
            $featuresJson,
            $p['impact'],
            $screenshotsJson
        ]);
        echo "+ Inserted project [{$p['slug']}] (ID: {$p['id']})\n";
    }
}

echo "\nSuccessfully seeded " . count($projects) . " projects dynamically into database!\n";
