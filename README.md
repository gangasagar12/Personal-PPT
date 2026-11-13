# Ganga Sagar Joshi — Developer Portfolio & Systems Platform

> Modern PHP 8.3+ Application Architecture with MVC Pattern, PSR-4 Autoloading, SQLite/MySQL Support, Dedicated Entrepreneurship Profile, and Dual-Mode GitHub Pages Static Export.

---

## 📁 Standard Application Folder Structure

This project adheres strictly to the modern, clean PHP application directory layout:

```
Personal-PPT-main/
│
├── app/                         # Core application logic
│   ├── Controllers/             # HTTP Controllers (Home, Profile, Contact, Admin)
│   ├── Core/                    # Framework Core (Autoloader, Database, Router, View)
│   └── Models/                  # Data Models (Featured, Academic, Insights, Inquiries)
│
├── config/                      # Application configuration
│   ├── app.php                  # Environment, debug, and application settings
│   ├── database.php             # Database connections (SQLite & MySQL)
│   └── env.php                  # Lightweight .env loader helper
│
├── database/                    # Migrations, seeders and backups
│   ├── portfolio.sqlite         # Zero-config local SQLite database
│   ├── schema.sql               # Production MySQL / MariaDB DDL schema
│   └── seeds.sql                # Production database seed records
│
├── public/                      # Public entry point and static assets
│   ├── index.php                # Front Controller (all HTTP requests entry point)
│   ├── .htaccess                # Apache URL rewriting & security headers
│   ├── robots.txt               # Direct search crawler directives
│   ├── sitemap.xml              # Direct search engine XML sitemap
│   ├── css/                     # Production stylesheets (style.css)
│   ├── js/                      # Production scripts (app.js)
│   ├── images/                  # Production image assets
│   └── pdf/                     # Resume & certificate PDF downloads
│
├── resources/                   # Views, templates and frontend source
│   └── views/                   # Application templates
│       ├── home.php             # Master portfolio template
│       └── profile.php          # Entrepreneurship Profile (Inovix Technologies)
│
├── routes/                      # Application routes
│   └── web.php                  # Web route definitions mapped to Controllers
│
├── storage/                     # Logs, cache and uploaded files
│   ├── cache/                   # Runtime render cache
│   ├── logs/                    # Application and error logs
│   └── uploads/                 # Uploaded media assets
│
├── tests/                       # Automated tests
│   ├── DatabaseTest.php         # Database connectivity & seed integrity assertions
│   ├── RouteTest.php            # Route and view template assertions
│   └── run_tests.php            # Automated CLI test runner
│
├── vendor/                      # Composer dependencies (generated)
│   └── .gitkeep
│
├── admin/                       # Web-based Admin Dashboard
│   └── index.php                # Content management & 1-click static export
│
├── backend/                     # CLI Scripts & Background Utilities
│   ├── contact.php              # Standalone AJAX contact endpoint
│   ├── db.php                   # Database connection helper
│   ├── export_static.php        # Static site compiler (syncs DB -> index.html & profile.html)
│   ├── functions.php            # Helper query functions
│   └── init_db.php              # Database schema & initial seeder script
│
├── .env                         # Environment-specific secrets (git-ignored)
├── .env.example                 # Environment template
├── .gitignore                   # Files excluded from Git
├── composer.json                # PHP dependency management
├── composer.lock                # Locked dependency versions
├── CNAME                        # GitHub Pages custom domain (gangasagar.com.np)
├── index.html                   # Compiled static home portfolio (GitHub Pages)
├── profile.html                 # Compiled static entrepreneurship profile (GitHub Pages)
└── projects.html                # Compiled project section code
```

---

## 🧪 Automated Testing

Run the built-in automated test suite to verify database connection, model records, routes, and views:

```bash
php tests/run_tests.php
```

Or via composer:
```bash
composer test
```

---

## 🚀 Quick Start (Local Development)

### 1. Requirements
- **PHP 8.1 or higher** (Tested with PHP 8.3)
- PHP Extensions: `pdo`, `pdo_sqlite` (or `pdo_mysql`), `json`, `mbstring`

### 2. Run the Local Server
```bash
# Recommended: Serve from public/ (Standard DocumentRoot)
php -S localhost:8000 -t public

# Alternative: Serve from project root
php -S localhost:8000
```

- **Home Portfolio:** [http://localhost:8000](http://localhost:8000)
- **Entrepreneurship Profile:** [http://localhost:8000/profile](http://localhost:8000/profile)
- **Admin Dashboard:** [http://localhost:8000/admin](http://localhost:8000/admin)

---

## 🌐 Dual-Mode Deployment Workflow

Because this portfolio utilizes free GitHub Pages hosting at `gangasagar.com.np` alongside a dynamic PHP backend:

1. **Local Dynamic Editing:**
   - Add or edit projects, academic items, or insights via `/admin`.
2. **Export to Static HTML:**
   - Run `php backend/export_static.php` (or click "Export Static for GitHub Pages" inside Admin).
   - This automatically compiles both `index.html` and `profile.html`.
3. **Commit & Push:**
   ```bash
   git add index.html profile.html database/
   git commit -m "Update portfolio content"
   git push origin main
   ```
