<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($page_title ?? 'Insights — Ganga Sagar Joshi') ?></title>
  <meta name="description" content="Technical engineering insights, backend architecture case studies, and systems design notes by Ganga Sagar Joshi." />

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  <!-- Shared & Dedicated Styles -->
  <link rel="stylesheet" href="/css/style.css" />
  <link rel="stylesheet" href="/css/insights.css" />
</head>
<body class="insights-page-body">

  <!-- Scroll progress indicator -->
  <div class="scroll-progress" id="scrollProgress"></div>

  <!-- ── Standard Portfolio Header & Navigation ── -->
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

      <!-- Desktop Navigation -->
      <nav class="navbar" id="desktopNav" aria-label="Primary navigation">
        <a href="/" class="nav-link">Home</a>
        <a href="/#about" class="nav-link">About</a>
        <a href="/#experience" class="nav-link">Experience</a>
        <a href="/#skills" class="nav-link">Skills</a>
        <a href="/#services" class="nav-link">Services</a>
        <a href="/#projects" class="nav-link">Projects</a>
        <a href="/profile" class="nav-link">Profile</a>
        <a href="/insights" class="nav-link active">Insights</a>
        <a href="/#contact" class="nav-link">Contact</a>
      </nav>

      <!-- Right Action Button & Hamburger -->
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
      <a href="/#projects" class="drawer-link"><i class="bx bx-folder"></i><span>Projects</span></a>
      <a href="/profile" class="drawer-link"><i class="bx bx-user-pin"></i><span>Profile</span></a>
      <a href="/insights" class="drawer-link active"><i class="bx bx-bulb"></i><span>Insights</span></a>
      <a href="/#contact" class="drawer-link"><i class="bx bx-envelope"></i><span>Contact</span></a>
    </nav>

    <div class="drawer-footer">
      <a href="/#contact" class="drawer-cta drawer-link">
        <span>Let's Talk</span>
        <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
      </a>
    </div>
  </div>

  <div class="insights-wrapper">

    <!-- ══════════════════════════════════════════════════════════
         1. HERO SECTION (RELATED BACKGROUND IMAGE WITH INSIGHTS TITLE)
         ══════════════════════════════════════════════════════════ -->
    <section class="insights-hero-banner" id="heroBanner">
      <div class="insights-hero-overlay"></div>
      <div class="insights-hero-content">
        <h1 class="insights-hero-title">Insights</h1>
        <p class="insights-hero-subtitle">
          Engineering case studies, database architecture, and production backend practices.
        </p>
      </div>
    </section>

    <!-- ══════════════════════════════════════════════════════════
         2. BLOG LISTING GRID (ONLY IMAGE AND TITLE ON CARDS)
         ══════════════════════════════════════════════════════════ -->
    <main class="insights-main-section">
      <div class="insights-container">

        <div class="insights-section-header">
          <div class="insights-section-count">
            <i class="bx bx-layer"></i> All Published Insights (<?= count($posts) ?>)
          </div>
        </div>

        <div class="insights-cards-grid">
          <?php foreach ($posts as $post): ?>
          <a href="/insights/<?= urlencode($post['slug']) ?>" 
             data-slug="<?= htmlspecialchars($post['slug']) ?>" 
             class="blog-post-card" 
             title="<?= htmlspecialchars($post['title']) ?>">
            <div class="blog-card-image-wrap">
              <img src="/<?= ltrim(htmlspecialchars($post['image'] ?? 'images/projectcard1.jpg'), '/') ?>" 
                   alt="<?= htmlspecialchars($post['title']) ?>" 
                   loading="lazy" 
                   onerror="this.onerror=null; this.src='/images/projectcard1.jpg'" />
            </div>
            <div class="blog-card-content">
              <h3 class="blog-card-title"><?= htmlspecialchars($post['title']) ?></h3>
            </div>
          </a>
          <?php endforeach; ?>
        </div>

      </div>
    </main>

    <!-- ══════════════════════════════════════════════════════════
         FOOTER
         ══════════════════════════════════════════════════════════ -->
    <footer class="footer">
      <div class="container footer-content">
        <p>&copy; <?= date('Y') ?> Ganga Sagar Joshi &bull; Engineering &amp; Technology Portfolio. All rights reserved.</p>
        <div class="footer-links">
          <a href="/">Home</a>
          <a href="/profile">Profile</a>
          <a href="/insights">Insights</a>
          <a href="/#contact">Contact</a>
        </div>
      </div>
    </footer>

  </div>

  <script>
    // Header scroll & Hamburger Drawer logic
    const header = document.getElementById('header');
    const hamburger = document.getElementById('hamburger');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const drawerClose = document.getElementById('drawerClose');

    window.addEventListener('scroll', () => {
      if (window.scrollY > 20) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });

    if (hamburger && mobileDrawer) {
      hamburger.addEventListener('click', () => {
        mobileDrawer.classList.add('open');
        hamburger.classList.add('active');
        hamburger.setAttribute('aria-expanded', 'true');
      });
    }

    if (drawerClose && mobileDrawer) {
      drawerClose.addEventListener('click', () => {
        mobileDrawer.classList.remove('open');
        hamburger.classList.remove('active');
        hamburger.setAttribute('aria-expanded', 'false');
      });
    }
  </script>
</body>
</html>
