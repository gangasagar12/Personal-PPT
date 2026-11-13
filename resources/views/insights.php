<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($page_title ?? 'Engineering Insights — Ganga Sagar Joshi') ?></title>
  <meta name="description" content="Technical write-ups, backend architecture case studies, and engineering notes on systems design and relational databases by Ganga Sagar Joshi." />

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  <!-- Base & Shared Stylesheet -->
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/insights.css" />
</head>
<body>

  <!-- Scroll progress indicator -->
  <div class="scroll-progress" id="scrollProgress"></div>

  <!-- ── Header & Navigation (Matching Exact Portfolio Header) ── -->
  <header class="header" id="header">
    <div class="header-container">
      <a class="brand" href="index.html#home" aria-label="Ganga Sagar Joshi Home">
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
        <a href="index.html#home" class="nav-link">Home</a>
        <a href="index.html#about" class="nav-link">About</a>
        <a href="index.html#skills" class="nav-link">Skills</a>
        <a href="index.html#services" class="nav-link">Services</a>
        <a href="index.html#projects" class="nav-link">Projects</a>
        <a href="profile.html" class="nav-link">Profile</a>
        <a href="insights.html" class="nav-link active">Insights</a>
        <a href="index.html#contact" class="nav-link">Contact</a>
      </nav>

      <!-- Right Action Button & Hamburger -->
      <div class="nav-actions">
        <a href="index.html#contact" class="btn-talk" aria-label="Let's Talk">
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
      <a href="index.html#home" class="drawer-link"><i class="bx bx-home-alt-2"></i><span>Home</span></a>
      <a href="index.html#about" class="drawer-link"><i class="bx bx-user"></i><span>About</span></a>
      <a href="index.html#skills" class="drawer-link"><i class="bx bx-layer"></i><span>Skills</span></a>
      <a href="index.html#services" class="drawer-link"><i class="bx bx-wrench"></i><span>Services</span></a>
      <a href="index.html#projects" class="drawer-link"><i class="bx bx-folder"></i><span>Projects</span></a>
      <a href="profile.html" class="drawer-link"><i class="bx bx-user-pin"></i><span>Profile</span></a>
      <a href="insights.html" class="drawer-link active"><i class="bx bx-bulb"></i><span>Insights</span></a>
      <a href="index.html#contact" class="drawer-link"><i class="bx bx-envelope"></i><span>Contact</span></a>
    </nav>

    <div class="drawer-footer">
      <a href="index.html#contact" class="drawer-cta drawer-link">
        <span>Let's Talk</span>
        <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
      </a>
    </div>
  </div>

  <!-- ── Main Insights Showcase (Exact UI Matching Reference) ── -->
  <main class="insights-page-main">
    <div class="container">

      <!-- Breadcrumb Navigation -->
      <nav class="profile-breadcrumb" aria-label="Breadcrumb">
        <a href="index.html#home" class="breadcrumb-back-link">
          <i class="bx bx-arrow-back"></i>
          <span>Portfolio</span>
        </a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">Engineering Insights</span>
      </nav>

      <!-- Section Heading Block (Identical to reference screenshot) -->
      <div class="section-heading-block text-center" style="margin-bottom: 2.75rem;">
        <span class="section-tag">Blog &amp; Knowledge</span>
        <h1 class="section-main-title">
          Engineering <span class="gradient-text">Insights</span>
        </h1>
        <p class="section-subtitle">
          Technical write-ups, backend architecture case studies, and engineering notes on systems design and relational databases.
        </p>
      </div>

      <!-- Insights Grid (Identical to reference screenshot) -->
      <div class="insights-grid">
        <?php foreach ($posts as $post): ?>
        <article class="insight-card" onclick="window.location.href='insight-detail.html?slug=<?= urlencode($post['slug']) ?>'" style="cursor: pointer;">
          <div class="insight-meta-row">
            <span class="insight-category-pill"><?= htmlspecialchars($post['category']) ?></span>
            <span class="insight-date-info">
              <i class="bx bx-time-five" aria-hidden="true"></i>
              <?= htmlspecialchars($post['read_time']) ?> &bull; <?= htmlspecialchars($post['date']) ?>
            </span>
          </div>
          <h2 class="insight-card-title"><?= htmlspecialchars($post['title']) ?></h2>
          <p class="insight-card-excerpt"><?= htmlspecialchars($post['excerpt']) ?></p>
          <div class="insight-card-footer">
            <a href="insight-detail.html?slug=<?= urlencode($post['slug']) ?>" class="live-site-link" onclick="event.stopPropagation();">
              <span>Read Article</span>
              <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
            </a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>

    </div>
  </main>

  <!-- ── Footer ── -->
  <footer class="footer">
    <div class="container footer-content">
      <p>&copy; <?= date('Y') ?> Ganga Sagar Joshi &bull; Software &amp; Systems. All rights reserved.</p>
      <div class="footer-links">
        <a href="index.html#home">Home</a>
        <a href="profile.html">Profile</a>
        <a href="insights.html">Insights</a>
        <a href="index.html#contact">Contact</a>
      </div>
    </div>
  </footer>

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

    // Dynamic routing adapter: if running on local PHP server without .html
    if (window.location.pathname.indexOf('.html') === -1 && window.location.port !== '') {
      document.querySelectorAll('.insight-card').forEach(card => {
        const link = card.querySelector('.live-site-link');
        if (link) {
          const href = link.getAttribute('href');
          const slugMatch = href.match(/slug=([^&]+)/);
          if (slugMatch && slugMatch[1]) {
            card.onclick = () => window.location.href = 'insights/' + decodeURIComponent(slugMatch[1]);
            link.setAttribute('href', 'insights/' + decodeURIComponent(slugMatch[1]));
          }
        }
      });
    }
  </script>
</body>
</html>
