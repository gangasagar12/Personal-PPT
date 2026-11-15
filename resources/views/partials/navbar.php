<?php
/**
 * Shared Portfolio Navigation Bar & Mobile Drawer
 * Provides unified desktop header, mobile slide-in drawer, and active link states
 */
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$active_page = $active_page ?? (
    (strpos($uri, '/project') !== false || strpos($uri, '/works') !== false) ? 'projects' :
    (strpos($uri, '/experience') !== false ? 'experience' :
    (strpos($uri, '/profile') !== false ? 'profile' :
    (strpos($uri, '/insight') !== false ? 'insights' :
    'home')))
);
$is_home = ($active_page === 'home' && ($uri === '/' || $uri === '' || $uri === '/index.php'));
?>

<!-- Scroll progress indicator -->
<div class="scroll-progress" id="scrollProgress"></div>

<!-- ── Main Navigation Header ── -->
<header class="header" id="header">
  <div class="header-container">
    <!-- Left: Personal Branding & Logo Avatar -->
    <a class="brand" href="<?= $is_home ? '#home' : '/' ?>" aria-label="Ganga Sagar Joshi Home">
      <div class="brand-monogram">
        <img src="/images/logo-square.png" alt="Ganga Sagar Joshi" class="brand-logo-img" width="42" height="42" />
      </div>
      <div class="brand-info">
        <span class="brand-name">Ganga Sagar</span>
       
      </div>
    </a>

    <!-- Center: Desktop Navigation Links -->
    <nav class="navbar" id="desktopNav" aria-label="Primary navigation">
      <a href="<?= $is_home ? '#home' : '/' ?>" class="nav-link <?= $active_page === 'home' ? 'active' : '' ?>">Home</a>
      <a href="<?= $is_home ? '#about' : '/#about' ?>" class="nav-link <?= $active_page === 'about' ? 'active' : '' ?>">About</a>
      <a href="/experience" class="nav-link <?= $active_page === 'experience' ? 'active' : '' ?>">Experience</a>
     
      <a href="<?= $is_home ? '#services' : '/#services' ?>" class="nav-link <?= $active_page === 'services' ? 'active' : '' ?>">Services</a>
      <a href="/projects" class="nav-link <?= $active_page === 'projects' ? 'active' : '' ?>">Projects</a>
      <a href="/profile" class="nav-link <?= $active_page === 'profile' ? 'active' : '' ?>">Entrepreneur Profile</a>
      <a href="/insights" class="nav-link <?= $active_page === 'insights' ? 'active' : '' ?>">Insights</a>
      <a href="<?= $is_home ? '#contact' : '/#contact' ?>" class="nav-link <?= $active_page === 'contact' ? 'active' : '' ?>">Contact</a>
    </nav>

    <!-- Right: Let's Talk Action Button & Mobile Hamburger -->
    <div class="nav-actions">
      <a href="<?= $is_home ? '#contact' : '/#contact' ?>" class="btn-talk" aria-label="Let's Talk">
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
      <img src="/images/logo-square.png" alt="Ganga Sagar Joshi" class="brand-logo-img" width="38" height="38" />
    </div>
    <div class="drawer-meta">
      <span class="drawer-name">Ganga Sagar </span>
      <span class="drawer-status"><span class="status-indicator"></span> Available for work</span>
    </div>
    <button class="drawer-close" id="drawerClose" aria-label="Close menu">
      <i class="bx bx-x" aria-hidden="true"></i>
    </button>
  </div>

  <nav class="drawer-nav" aria-label="Mobile navigation">
    <a href="<?= $is_home ? '#home' : '/' ?>" class="drawer-link <?= $active_page === 'home' ? 'active' : '' ?>"><i class="bx bx-home-alt-2"></i><span>Home</span></a>
    <a href="<?= $is_home ? '#about' : '/#about' ?>" class="drawer-link <?= $active_page === 'about' ? 'active' : '' ?>"><i class="bx bx-user"></i><span>About</span></a>
    <a href="/experience" class="drawer-link <?= $active_page === 'experience' ? 'active' : '' ?>"><i class="bx bx-briefcase"></i><span>Experience</span></a>
    
    <a href="<?= $is_home ? '#services' : '/#services' ?>" class="drawer-link <?= $active_page === 'services' ? 'active' : '' ?>"><i class="bx bx-wrench"></i><span>Services</span></a>
    <a href="/projects" class="drawer-link <?= $active_page === 'projects' ? 'active' : '' ?>"><i class="bx bx-folder"></i><span>Projects</span></a>
    <a href="/profile" class="drawer-link <?= $active_page === 'profile' ? 'active' : '' ?>"><i class="bx bx-user-pin"></i><span>Entrepreneur Profile</span></a>
    <a href="/insights" class="drawer-link <?= $active_page === 'insights' ? 'active' : '' ?>"><i class="bx bx-bulb"></i><span>Insights</span></a>
    <a href="<?= $is_home ? '#contact' : '/#contact' ?>" class="drawer-link <?= $active_page === 'contact' ? 'active' : '' ?>"><i class="bx bx-envelope"></i><span>Contact</span></a>
  </nav>

  <div class="drawer-footer" style="margin-top:auto; padding-top:1.25rem; border-top:1px solid var(--bdr, #e2e8f0);">
    <a href="<?= $is_home ? '#contact' : '/#contact' ?>" class="btn-talk" style="width:100%; justify-content:center;">
      <span>Let's Talk</span>
      <i class="bx bx-right-arrow-alt"></i>
    </a>
  </div>
</div>

<!-- Backdrop Overlay for Mobile Drawer -->
<div class="drawer-backdrop" id="drawerBackdrop"></div>
