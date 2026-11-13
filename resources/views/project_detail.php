<?php
/**
 * Ganga Sagar Joshi — Dynamic Project Details Case Study View
 * Built to match the exact dark hero banner & 2-column layout reference
 */

$title = $project['title'] ?? 'Project Case Study';
$categoryBadge = !empty($project['category_badge']) ? $project['category_badge'] : 'PROJECT';
$category = !empty($project['category']) ? $project['category'] : '';
$officialSite = !empty($project['official_site']) ? $project['official_site'] : '';
$clientName = !empty($project['client_name']) ? $project['client_name'] : '';
$location = !empty($project['location']) ? $project['location'] : '';
$liveUrl = !empty($project['live_url']) ? $project['live_url'] : '#';
$description = !empty($project['description']) ? $project['description'] : ($project['short_description'] ?? '');
$impact = !empty($project['impact']) ? $project['impact'] : '';
$features = !empty($project['features_array']) ? $project['features_array'] : [];
$screenshots = !empty($project['screenshots_array']) ? $project['screenshots_array'] : [];
$skills = !empty($project['skills_array']) ? $project['skills_array'] : [];

// Root path adjustment depending on whether we are in a subfolder or root
$isSubdir = isset($is_subdir) && $is_subdir;
$rootPrefix = $isSubdir ? '../' : '';

// Helper for tool badges
if (!function_exists('getTechBadgeInfo')) {
    function getTechBadgeInfo($skill) {
        $s = strtolower(trim($skill));
        if (strpos($s, 'django') !== false) {
            return ['name' => 'Django', 'badge_class' => 'tech-dj', 'symbol' => 'dj', 'is_text' => true];
        } elseif (strpos($s, 'python') !== false) {
            return ['name' => 'Python', 'badge_class' => 'tech-py', 'icon' => 'bxl-python', 'is_text' => false];
        } elseif (strpos($s, 'mysql') !== false) {
            return ['name' => 'MySQL', 'badge_class' => 'tech-mysql', 'icon' => 'bxs-data', 'is_text' => false];
        } elseif (strpos($s, 'postgres') !== false) {
            return ['name' => 'PostgreSQL', 'badge_class' => 'tech-postgres', 'icon' => 'bx-data', 'is_text' => false];
        } elseif (strpos($s, 'sqlite') !== false) {
            return ['name' => 'SQLite', 'badge_class' => 'tech-sqlite', 'icon' => 'bx-cylinder', 'is_text' => false];
        } elseif (strpos($s, 'html') !== false) {
            return ['name' => 'HTML', 'badge_class' => 'tech-html', 'symbol' => '5', 'is_text' => true];
        } elseif (strpos($s, 'css') !== false) {
            return ['name' => 'CSS', 'badge_class' => 'tech-css', 'symbol' => '3', 'is_text' => true];
        } elseif (strpos($s, 'javascript') !== false || $s === 'js') {
            return ['name' => 'JS', 'badge_class' => 'tech-js', 'symbol' => 'JS', 'is_text' => true];
        } elseif (strpos($s, 'docker') !== false) {
            return ['name' => 'Docker', 'badge_class' => 'tech-docker', 'icon' => 'bxl-docker', 'is_text' => false];
        } elseif (strpos($s, 'redis') !== false) {
            return ['name' => 'Redis', 'badge_class' => 'tech-redis', 'icon' => 'bx-server', 'is_text' => false];
        } else {
            return ['name' => trim($skill), 'badge_class' => 'tech-default', 'icon' => 'bx-code-alt', 'is_text' => false];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($title) ?> — Ganga Sagar Joshi</title>
  <meta name="description" content="<?= htmlspecialchars($project['short_description'] ?? 'Software engineering case study by Ganga Sagar Joshi.') ?>" />

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  <!-- Stylesheets -->
  <link rel="stylesheet" href="/css/style.css" />
  <link rel="stylesheet" href="/css/insights.css" />

  <style>
    /* ─── Hero Dark Banner with Navbar Clearance ─── */
    .project-hero-banner {
      background: linear-gradient(180deg, #070d1e 0%, #0b1329 60%, #0f172a 100%);
      color: #ffffff;
      margin-top: var(--nav-h, 72px);
      padding: clamp(3rem, 5vw, 4.5rem) 0 clamp(3rem, 5vw, 4rem);
      position: relative;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .project-hero-breadcrumbs {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.88rem;
      color: #94a3b8;
      margin-bottom: 1.25rem;
    }
    .project-hero-breadcrumbs a {
      color: #94a3b8;
      text-decoration: none;
      transition: color 0.2s ease;
    }
    .project-hero-breadcrumbs a:hover {
      color: #38bdf8;
    }
    .project-hero-breadcrumbs .crumb-sep {
      color: #475569;
      font-size: 0.8rem;
    }
    .project-hero-breadcrumbs .crumb-active {
      color: #38bdf8;
      font-weight: 500;
    }
    .project-hero-tags {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      flex-wrap: wrap;
      margin-bottom: 1.25rem;
    }
    .project-tag-pill {
      display: inline-flex;
      align-items: center;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 0.35rem 0.85rem;
      border-radius: 6px;
    }
    .tag-category {
      background: rgba(56, 189, 248, 0.16);
      color: #38bdf8;
      border: 1px solid rgba(56, 189, 248, 0.3);
    }
    .tag-domain {
      background: rgba(255, 255, 255, 0.08);
      color: #e2e8f0;
      border: 1px solid rgba(255, 255, 255, 0.16);
      text-transform: lowercase;
      letter-spacing: normal;
      font-weight: 500;
    }
    .project-hero-title {
      font-family: var(--ff-h);
      font-size: clamp(2rem, 3.8vw, 3rem);
      font-weight: 700;
      line-height: 1.22;
      color: #ffffff;
      margin-bottom: 1.25rem;
      letter-spacing: -0.02em;
    }
    .project-hero-meta {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 1.75rem;
      font-size: 0.95rem;
      color: #94a3b8;
    }
    .project-hero-meta strong {
      color: #ffffff;
      font-weight: 600;
    }
    .project-hero-meta a {
      color: #38bdf8;
      text-decoration: none;
      transition: color 0.2s;
    }
    .project-hero-meta a:hover {
      text-decoration: underline;
    }

    /* ─── Main Details Section Layout ─── */
    .project-body-section {
      padding: clamp(3rem, 6vw, 5rem) 0;
      background: #ffffff;
    }
    .project-body-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) 350px;
      gap: clamp(2.5rem, 4vw, 4rem);
      align-items: start;
    }
    @media (max-width: 992px) {
      .project-body-grid {
        grid-template-columns: 1fr;
        gap: 3rem;
      }
    }

    /* ─── Left Column Showcase ─── */
    .project-main-showcase {
      width: 100%;
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid #e2e8f0;
      box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.1);
      margin-bottom: 2.75rem;
      background: #0f172a;
    }
    .project-main-showcase img {
      width: 100%;
      height: auto;
      display: block;
      object-fit: cover;
    }

    .project-sec-heading {
      font-family: var(--ff-h);
      font-size: clamp(1.4rem, 2.2vw, 1.85rem);
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 1.15rem;
      line-height: 1.3;
    }
    .project-sec-subheading {
      font-size: 0.76rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: #64748b;
      margin-bottom: 0.35rem;
      display: block;
    }

    .project-intro-text {
      font-size: 1.02rem;
      line-height: 1.82;
      color: #334155;
      margin-bottom: 2.75rem;
    }

    /* Core Features List */
    .project-features-list {
      list-style: none;
      padding: 0;
      margin: 0 0 3rem;
      display: grid;
      gap: 0.85rem;
    }
    .project-features-list li {
      display: flex;
      align-items: flex-start;
      gap: 0.85rem;
      font-size: 0.98rem;
      line-height: 1.6;
      color: #334155;
    }
    .project-features-list li i {
      color: #4B86A5;
      font-size: 1.35rem;
      margin-top: 0.15rem;
      flex-shrink: 0;
    }

    /* Demo Screenshots Grid */
    .project-screenshots-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.25rem;
      margin-bottom: 3rem;
    }
    @media (max-width: 640px) {
      .project-screenshots-grid {
        grid-template-columns: 1fr;
      }
    }
    .screenshot-thumb-card {
      position: relative;
      border-radius: 14px;
      overflow: hidden;
      border: 1px solid #e2e8f0;
      box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
      background: #0f172a;
      cursor: pointer;
      aspect-ratio: 16 / 10;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .screenshot-thumb-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 28px -6px rgba(15, 23, 42, 0.16);
    }
    .screenshot-thumb-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform 0.35s ease;
    }
    .screenshot-thumb-card:hover img {
      transform: scale(1.03);
    }
    .screenshot-zoom-pill {
      position: absolute;
      bottom: 0.85rem;
      right: 0.85rem;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      padding: 0.35rem 0.75rem;
      border-radius: 6px;
      background: rgba(15, 23, 42, 0.85);
      color: #ffffff;
      font-size: 0.76rem;
      font-weight: 600;
      backdrop-filter: blur(4px);
      transition: background 0.2s ease;
    }
    .screenshot-thumb-card:hover .screenshot-zoom-pill {
      background: rgba(37, 99, 235, 0.95);
    }

    /* Business Impact Callout Box */
    .project-impact-box {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      border-radius: 14px;
      padding: 1.75rem 2rem;
      color: #166534;
      font-size: 1rem;
      line-height: 1.75;
      margin-bottom: 2rem;
    }

    /* ─── Right Column Sticky Sidebar ─── */
    .project-sidebar {
      position: sticky;
      top: calc(var(--nav-h, 72px) + 1.5rem);
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }
    .project-overview-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 18px;
      padding: 2rem 1.75rem;
      box-shadow: 0 4px 20px -4px rgba(15, 23, 42, 0.05);
    }
    .sidebar-card-title {
      font-family: var(--ff-h);
      font-size: 1.35rem;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 1.5rem;
    }
    .overview-meta-list {
      display: grid;
      gap: 1.25rem;
      margin-bottom: 1.75rem;
    }
    .overview-meta-item .meta-label {
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #94a3b8;
      display: block;
      margin-bottom: 0.25rem;
    }
    .overview-meta-item .meta-val {
      font-size: 0.95rem;
      font-weight: 600;
      color: #0f172a;
      line-height: 1.4;
    }
    .overview-meta-item a.meta-val {
      color: #0f172a;
      text-decoration: none;
      transition: color 0.2s;
    }
    .overview-meta-item a.meta-val:hover {
      color: #4B86A5;
    }

    .sidebar-divider {
      border: none;
      border-top: 1px solid #f1f5f9;
      margin: 1.5rem 0;
    }

    .tech-utilized-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.15rem;
    }
    .tech-utilized-title {
      font-size: 0.74rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #64748b;
    }
    .tech-tools-count {
      font-size: 0.74rem;
      font-weight: 600;
      color: #94a3b8;
    }

    /* Tech App Badges Grid */
    .tech-tools-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0.85rem;
      margin-bottom: 1.75rem;
    }
    .tech-tool-badge {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 0.45rem;
    }
    .tech-tool-icon-box {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      transition: transform 0.2s ease;
    }
    .tech-tool-badge:hover .tech-tool-icon-box {
      transform: scale(1.08);
    }
    .tech-tool-name {
      font-size: 0.76rem;
      font-weight: 600;
      color: #475569;
    }

    /* Icon Box Variants matching the reference screenshot */
    .tech-dj .tech-tool-icon-box {
      background: #ecfdf5;
      color: #059669;
      font-weight: 800;
      font-family: monospace;
      font-size: 1.2rem;
    }
    .tech-py .tech-tool-icon-box {
      background: #fefce8;
      color: #ca8a04;
    }
    .tech-mysql .tech-tool-icon-box {
      background: rgba(75, 134, 165, 0.1);
      color: #4B86A5;
    }
    .tech-postgres .tech-tool-icon-box {
      background: rgba(75, 134, 165, 0.1);
      color: #3d6f8a;
    }
    .tech-sqlite .tech-tool-icon-box {
      background: rgba(75, 134, 165, 0.1);
      color: #4B86A5;
    }
    .tech-html .tech-tool-icon-box {
      background: #fef2f2;
      color: #dc2626;
      font-weight: 800;
      font-family: sans-serif;
      font-size: 1.25rem;
    }
    .tech-css .tech-tool-icon-box {
      background: rgba(75, 134, 165, 0.1);
      color: #4B86A5;
      font-weight: 800;
      font-family: sans-serif;
      font-size: 1.25rem;
    }
    .tech-js .tech-tool-icon-box {
      background: #fefce8;
      color: #d97706;
      font-weight: 800;
      font-family: monospace;
      font-size: 1.15rem;
    }
    .tech-docker .tech-tool-icon-box {
      background: #f0f9ff;
      color: #0284c7;
    }
    .tech-redis .tech-tool-icon-box {
      background: #fef2f2;
      color: #dc2626;
    }
    .tech-default .tech-tool-icon-box {
      background: #f1f5f9;
      color: #475569;
    }

    /* Action Buttons in Sidebar */
    .sidebar-actions {
      display: grid;
      gap: 0.75rem;
    }
    .btn-visit-live {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      background: #1e293b;
      color: #ffffff;
      padding: 0.85rem 1.25rem;
      border-radius: 10px;
      font-family: var(--ff-h);
      font-size: 0.92rem;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.2s ease, transform 0.2s ease;
    }
    .btn-visit-live:hover {
      background: #0f172a;
      transform: translateY(-2px);
      color: #ffffff;
    }
    .btn-back-works {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      background: #ffffff;
      color: #334155;
      border: 1px solid #cbd5e1;
      padding: 0.85rem 1.25rem;
      border-radius: 10px;
      font-family: var(--ff-h);
      font-size: 0.92rem;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.2s ease, border-color 0.2s ease;
    }
    .btn-back-works:hover {
      background: #f8fafc;
      border-color: #94a3b8;
      color: #0f172a;
    }

    /* Need a Similar Platform Card */
    .similar-platform-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 2rem 1.5rem;
      text-align: center;
    }
    .similar-card-title {
      font-family: var(--ff-h);
      font-size: 1.15rem;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 0.5rem;
    }
    .similar-card-desc {
      font-size: 0.88rem;
      color: #64748b;
      line-height: 1.55;
      margin-bottom: 1.25rem;
    }
    .btn-get-touch {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #1e293b;
      color: #ffffff;
      padding: 0.65rem 1.5rem;
      border-radius: 8px;
      font-family: var(--ff-h);
      font-size: 0.88rem;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.2s ease;
    }
    .btn-get-touch:hover {
      background: #0f172a;
      color: #ffffff;
    }

    /* ─── Lightbox Modal for Screenshots ─── */
    .lightbox-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.88);
      backdrop-filter: blur(8px);
      z-index: 99999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s ease;
    }
    .lightbox-backdrop.is-open {
      opacity: 1;
      pointer-events: auto;
    }
    .lightbox-content {
      position: relative;
      max-width: 960px;
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .lightbox-content img {
      max-width: 100%;
      max-height: 82vh;
      border-radius: 12px;
      box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.5);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .lightbox-caption {
      color: #e2e8f0;
      font-size: 0.95rem;
      margin-top: 0.75rem;
      text-align: center;
    }
    .lightbox-close {
      position: absolute;
      top: -45px;
      right: 0;
      background: transparent;
      border: none;
      color: #ffffff;
      font-size: 2rem;
      cursor: pointer;
      line-height: 1;
      padding: 0.25rem;
    }
  </style>
</head>
<body>

  <!-- ─── Standard Header & Navigation ────────────────────────── -->
  <header class="header" id="header">
    <div class="header-container">
      <a class="brand" href="/" aria-label="Ganga Sagar Joshi Home">
        <div class="brand-monogram">
          <span class="monogram-text">GSJ</span>
        </div>
        <div class="brand-info">
          <span class="brand-name">Ganga Sagar Joshi</span>
          <span class="brand-title">Software &amp; Systems</span>
        </div>
      </a>

      <!-- Center/Right: Navigation Links -->
      <nav class="navbar" id="desktopNav" aria-label="Primary navigation">
        <a href="/" class="nav-link">Home</a>
        <a href="/#about" class="nav-link">About</a>
        <a href="/#experience" class="nav-link">Experience</a>
        <a href="/#skills" class="nav-link">Skills</a>
        <a href="/#services" class="nav-link">Services</a>
        <a href="/#projects" class="nav-link active">Projects</a>
        <a href="/profile" class="nav-link">Profile</a>
        <a href="/insights" class="nav-link">Insights</a>
        <a href="/#contact" class="nav-link">Contact</a>
      </nav>

      <!-- Right: Highlighted Let's Talk button & Mobile Hamburger -->
      <div class="nav-actions">
        <a href="/#contact" class="btn-talk" aria-label="Let's Talk">
          <span>Let's Talk</span>
          <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
        </a>

        <button class="hamburger" id="hamburger" aria-label="Toggle navigation menu" aria-expanded="false"
          aria-controls="mobileDrawer">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </button>
      </div>
    </div>
  </header>

  <!-- Mobile slide-in drawer -->
  <div class="mobile-drawer" id="mobileDrawer" role="dialog" aria-label="Mobile navigation menu" aria-hidden="true">
    <div class="drawer-header">
      <div class="brand-monogram sm">
        <span class="monogram-text">GSJ</span>
      </div>
      <div class="drawer-meta">
        <span class="drawer-name">Ganga Sagar Joshi</span>
        <span class="drawer-status"><span class="status-indicator"></span> Available for work</span>
      </div>
      <button class="drawer-close" id="drawerClose" aria-label="Close menu">
        <i class="bx bx-x" aria-hidden="true"></i>
      </button>
    </div>

    <nav class="drawer-nav" aria-label="Mobile navigation">
      <a href="/" class="drawer-link"><i class="bx bx-home-alt-2"></i><span>Home</span></a>
      <a href="/#about" class="drawer-link"><i class="bx bx-user"></i><span>About</span></a>
      <a href="/#experience" class="drawer-link"><i class="bx bx-briefcase"></i><span>Experience</span></a>
      <a href="/#skills" class="drawer-link"><i class="bx bx-layer"></i><span>Skills</span></a>
      <a href="/#services" class="drawer-link"><i class="bx bx-wrench"></i><span>Services</span></a>
      <a href="/#projects" class="drawer-link active"><i class="bx bx-folder"></i><span>Projects</span></a>
      <a href="/profile" class="drawer-link"><i class="bx bx-user-pin"></i><span>Profile</span></a>
      <a href="/insights" class="drawer-link"><i class="bx bx-bulb"></i><span>Insights</span></a>
      <a href="/#contact" class="drawer-link"><i class="bx bx-envelope"></i><span>Contact</span></a>
    </nav>
  </div>

  <!-- ─── Top Dark Tech Hero Banner ────────────────────────────── -->
  <section class="project-hero-banner">
    <div class="container">
      <div class="project-hero-breadcrumbs">
        <a href="/">Home</a>
        <span class="crumb-sep">/</span>
        <a href="/#projects">Works</a>
        <span class="crumb-sep">/</span>
        <span class="crumb-active"><?= htmlspecialchars($project['title']) ?></span>
      </div>

      <div class="project-hero-tags">
        <span class="project-tag-pill tag-category"><?= htmlspecialchars($categoryBadge) ?></span>
        <?php if (!empty($officialSite)): ?>
        <span class="project-tag-pill tag-domain"><?= htmlspecialchars($officialSite) ?></span>
        <?php endif; ?>
      </div>

      <h1 class="project-hero-title">
        <?= htmlspecialchars($project['title']) ?>
      </h1>

      <?php if (!empty($clientName) || !empty($officialSite) || (!empty($liveUrl) && $liveUrl !== '#')): ?>
      <div class="project-hero-meta">
        <?php if (!empty($clientName)): ?>
        <div>Client: <strong><?= htmlspecialchars($clientName) ?></strong></div>
        <?php endif; ?>
        <?php if (!empty($officialSite)): ?>
        <div>Official Site: <a href="https://<?= htmlspecialchars($officialSite) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($officialSite) ?></a></div>
        <?php elseif (!empty($liveUrl) && $liveUrl !== '#'): ?>
        <div>Live Preview: <a href="<?= htmlspecialchars($liveUrl) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars(parse_url($liveUrl, PHP_URL_HOST) ?: 'Live Demo') ?></a></div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ─── Main Details 2-Column Section ────────────────────────── -->
  <main class="project-body-section">
    <div class="container">
      <div class="project-body-grid">

        <!-- Left Column: Primary Content -->
        <div class="project-main-col">
          <!-- Main Hero Showcase Screenshot -->
          <div class="project-main-showcase">
            <img src="/<?= ltrim(htmlspecialchars($project['image']), '/') ?>" alt="<?= htmlspecialchars($project['title']) ?>" onerror="this.onerror=null; this.src='/images/projectcard1.jpg'" />
          </div>

          <!-- Section 1: Project Introduction -->
          <section class="project-sec-block">
            <h2 class="project-sec-heading">Project Introduction</h2>
            <p class="project-intro-text">
              <?= nl2br(htmlspecialchars($description)) ?>
            </p>
          </section>

          <!-- Section 2: Core Features & System Capabilities -->
          <?php if (!empty($features)): ?>
          <section class="project-sec-block">
            <h2 class="project-sec-heading">Core Features &amp; System Capabilities</h2>
            <ul class="project-features-list">
              <?php foreach ($features as $f): ?>
              <li>
                <i class="bx bx-check-circle" aria-hidden="true"></i>
                <span><?= htmlspecialchars($f) ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
          </section>
          <?php endif; ?>

          <!-- Section 3: Demo Screenshots & Application Interface -->
          <?php if (!empty($screenshots)): ?>
          <section class="project-sec-block">
            <span class="project-sec-subheading">SYSTEM PREVIEWS &amp; UI WALKTHROUGH</span>
            <h2 class="project-sec-heading">Demo Screenshots &amp; Application Interface</h2>

            <div class="project-screenshots-grid">
              <?php foreach ($screenshots as $s): 
                $sSrc = '/' . ltrim(htmlspecialchars($s['src']), '/');
                $sTitle = htmlspecialchars($s['title'] ?? 'Interface Preview');
              ?>
              <div class="screenshot-thumb-card" onclick="openLightbox('<?= $sSrc ?>', '<?= addslashes($sTitle) ?>')">
                <img src="<?= $sSrc ?>" alt="<?= $sTitle ?>" loading="lazy" />
                <div class="screenshot-zoom-pill">
                  <span>Zoom</span>
                  <i class="bx bx-expand-alt" aria-hidden="true"></i>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </section>
          <?php endif; ?>

          <!-- Section 4: Business Impact & Results (Optional) -->
          <?php if (!empty($impact)): ?>
          <section class="project-sec-block">
            <h2 class="project-sec-heading">Business Impact &amp; Results</h2>
            <div class="project-impact-box">
              <p><?= nl2br(htmlspecialchars($impact)) ?></p>
            </div>
          </section>
          <?php endif; ?>
        </div>

        <!-- Right Column: Sticky Sidebar -->
        <aside class="project-sidebar">
          
          <!-- Project Overview Card -->
          <div class="project-overview-card">
            <h3 class="sidebar-card-title">Project Overview</h3>

            <div class="overview-meta-list">
              <div class="overview-meta-item">
                <span class="meta-label">PROJECT CATEGORY</span>
                <span class="meta-val"><?= htmlspecialchars(!empty($category) ? $category : 'Software Engineering') ?></span>
              </div>

              <?php if (!empty($clientName)): ?>
              <div class="overview-meta-item">
                <span class="meta-label">ORGANIZATION / CLIENT</span>
                <span class="meta-val"><?= htmlspecialchars($clientName) ?></span>
              </div>
              <?php endif; ?>

              <?php if (!empty($officialSite)): ?>
              <div class="overview-meta-item">
                <span class="meta-label">OFFICIAL SITE</span>
                <a href="https://<?= htmlspecialchars($officialSite) ?>" target="_blank" rel="noopener noreferrer" class="meta-val">
                  <?= htmlspecialchars($officialSite) ?>
                </a>
              </div>
              <?php endif; ?>

              <?php if (!empty($location)): ?>
              <div class="overview-meta-item">
                <span class="meta-label">LOCATION</span>
                <span class="meta-val"><?= htmlspecialchars($location) ?></span>
              </div>
              <?php endif; ?>
            </div>

            <hr class="sidebar-divider" />

            <!-- Technologies Utilized -->
            <div class="tech-utilized-block">
              <div class="tech-utilized-header">
                <span class="tech-utilized-title">TECHNOLOGIES UTILIZED</span>
                <span class="tech-tools-count"><?= count($skills) ?> TOOLS</span>
              </div>

              <div class="tech-tools-grid">
                <?php foreach ($skills as $skill): 
                  $t = getTechBadgeInfo($skill);
                ?>
                <div class="tech-tool-badge <?= $t['badge_class'] ?>">
                  <div class="tech-tool-icon-box">
                    <?php if ($t['is_text']): ?>
                      <span><?= htmlspecialchars($t['symbol']) ?></span>
                    <?php else: ?>
                      <i class="bx <?= htmlspecialchars($t['icon']) ?>" aria-hidden="true"></i>
                    <?php endif; ?>
                  </div>
                  <span class="tech-tool-name"><?= htmlspecialchars($t['name']) ?></span>
                </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="sidebar-actions">
              <a href="<?= htmlspecialchars($liveUrl) ?>" target="_blank" rel="noopener noreferrer" class="btn-visit-live">
                <span>Visit Live System</span>
                <i class="bx bx-link-external" aria-hidden="true"></i>
              </a>

              <a href="/#projects" class="btn-back-works">
                <span>&larr; Back to Works</span>
              </a>
            </div>
          </div>

          <!-- Need a Similar Platform Card -->
          <div class="similar-platform-card">
            <h4 class="similar-card-title">Need a Similar Platform?</h4>
            <p class="similar-card-desc">
              We can architect and engineer a custom system tailored for your business workflow.
            </p>
            <a href="/#contact" class="btn-get-touch">
              Get in Touch
            </a>
          </div>

        </aside>

      </div>
    </div>
  </main>

  <!-- ─── Standard Footer ──────────────────────────────────────── -->
  <footer class="footer">
    <div class="container footer-inner">
      <span class="footer-logo">Ganga Sagar<span class="dot">.</span></span>
      <p>
        &copy; <?= date('Y') ?> Ganga Sagar Joshi.
        Crafted with <i class="bx bx-heart" aria-hidden="true"></i> in Nepal.
      </p>
      <a href="#header" class="back-to-top" aria-label="Back to top">
        <i class="bx bx-up-arrow-alt" aria-hidden="true"></i>
      </a>
    </div>
  </footer>

  <!-- ─── Interactive Lightbox for Screenshot Preview ─────────── -->
  <div class="lightbox-backdrop" id="lightboxModal" onclick="closeLightbox(event)">
    <div class="lightbox-content">
      <button type="button" class="lightbox-close" onclick="closeLightbox(event)" aria-label="Close preview">&times;</button>
      <img id="lightboxImg" src="" alt="Screenshot Full View" />
      <div class="lightbox-caption" id="lightboxCaption"></div>
    </div>
  </div>

  <script>
    function openLightbox(src, caption) {
      const modal = document.getElementById('lightboxModal');
      const img = document.getElementById('lightboxImg');
      const cap = document.getElementById('lightboxCaption');
      if (!modal || !img) return;
      img.src = src;
      cap.textContent = caption || '';
      modal.classList.add('is-open');
      document.body.style.overflow = 'hidden';
    }

    function closeLightbox(e) {
      const modal = document.getElementById('lightboxModal');
      if (!modal) return;
      if (e.target.id === 'lightboxModal' || e.target.classList.contains('lightbox-close')) {
        modal.classList.remove('is-open');
        document.body.style.overflow = '';
      }
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const modal = document.getElementById('lightboxModal');
        if (modal && modal.classList.contains('is-open')) {
          modal.classList.remove('is-open');
          document.body.style.overflow = '';
        }
      }
    });

    // Mobile nav toggle
    const hamburger = document.getElementById('hamburger');
    const drawer = document.getElementById('mobileDrawer');
    const drawerClose = document.getElementById('drawerClose');
    if (hamburger && drawer) {
      hamburger.addEventListener('click', () => {
        drawer.classList.add('open');
      });
    }
    if (drawerClose && drawer) {
      drawerClose.addEventListener('click', () => {
        drawer.classList.remove('open');
      });
    }
  </script>

</body>
</html>
