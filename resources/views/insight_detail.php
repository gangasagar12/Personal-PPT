<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title id="metaTitle"><?= htmlspecialchars(($post['title'] ?? 'Insight Detail') . ' — Ganga Sagar Joshi') ?></title>
  <meta name="description" id="metaDesc" content="<?= htmlspecialchars($post['excerpt'] ?? 'Technical engineering insights by Ganga Sagar Joshi.') ?>" />

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  <!-- Shared & Dedicated Styles -->
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/insights.css" />
</head>
<body class="insights-page-body">

  <!-- Scroll progress indicator -->
  <div class="scroll-progress" id="scrollProgress"></div>

  <!-- ── Standard Portfolio Header & Navigation ── -->
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

  <div class="insights-wrapper">

    <!-- ══════════════════════════════════════════════════════════
         MAIN DETAIL LAYOUT (ARTICLE + RECENT ARTICLES SIDEBAR)
         ══════════════════════════════════════════════════════════ -->
    <main class="insights-detail-main">
      <div class="insights-container">

        <!-- Breadcrumb -->
        <nav class="article-breadcrumb" aria-label="Breadcrumb">
          <a href="index.html">Home</a>
          <span class="separator">/</span>
          <a href="insights.html">Insights</a>
          <span class="separator">/</span>
          <span class="active-title" id="breadcrumbTitle"><?= htmlspecialchars($post['title'] ?? 'Insight Detail') ?></span>
        </nav>

        <div class="article-detail-layout">

          <!-- Left Column: Full Blog Content -->
          <article class="article-main-card">
            
            <header class="article-header">
              <span class="article-category-badge" id="articleCategory"><?= htmlspecialchars($post['category'] ?? 'Engineering') ?></span>
              <h1 class="article-main-title" id="articleTitle"><?= htmlspecialchars($post['title'] ?? '') ?></h1>

              <div class="article-meta-row">
                <div class="article-author-info">
                  <img src="images/profile.jpeg" alt="Ganga Sagar Joshi" class="article-author-avatar" onerror="this.src='images/gangasagar.jpeg'" />
                  <span>Ganga Sagar Joshi</span>
                </div>
                <div class="article-meta-item">
                  <i class="bx bx-calendar"></i>
                  <span id="articleDate"><?= htmlspecialchars($post['date'] ?? 'Nov 2026') ?></span>
                </div>
                <div class="article-meta-item">
                  <i class="bx bx-time-five"></i>
                  <span id="articleReadTime"><?= htmlspecialchars($post['read_time'] ?? '5 min read') ?></span>
                </div>
              </div>
            </header>

            <!-- Featured Image -->
            <div class="article-featured-image">
              <img src="<?= htmlspecialchars($post['image'] ?? 'images/projectcard1.jpg') ?>" 
                   id="articleImage" 
                   alt="<?= htmlspecialchars($post['title'] ?? '') ?>" 
                   onerror="this.src='images/projectcard1.jpg'" />
            </div>

            <!-- Full Article Body (Small, professional typography) -->
            <div class="article-body-content" id="articleContent">
              <?= $post['content'] ?? nl2br(htmlspecialchars($post['excerpt'] ?? '')) ?>
            </div>

            <!-- Footer Return Link -->
            <div class="article-footer-nav">
              <a href="insights.html" class="btn-back-insights">
                <i class="bx bx-left-arrow-alt"></i>
                <span>Back to All Insights</span>
              </a>
              <div style="font-size: 0.8rem; color: var(--color-text-muted);">
                Strataflow Technologies &bull; Technical Journal
              </div>
            </div>

          </article>

          <!-- Right Column: Recent Articles Sidebar -->
          <aside class="article-sidebar" aria-label="Recent insights and sidebar">
            
            <div class="sidebar-widget">
              <div class="sidebar-widget-header">
                <i class="bx bx-history"></i>
                <span>Recent Articles</span>
              </div>

              <div class="recent-articles-list" id="recentArticlesList">
                <?php foreach (($recent ?? []) as $rec): ?>
                <a href="insight-detail.html?slug=<?= urlencode($rec['slug']) ?>" 
                   data-slug="<?= htmlspecialchars($rec['slug']) ?>" 
                   class="recent-article-item">
                  <div class="recent-article-thumb">
                    <img src="<?= htmlspecialchars($rec['image'] ?? 'images/projectcard1.jpg') ?>" 
                         alt="<?= htmlspecialchars($rec['title']) ?>" 
                         loading="lazy" 
                         onerror="this.src='images/projectcard1.jpg'" />
                  </div>
                  <div class="recent-article-info">
                    <h4 class="recent-article-title"><?= htmlspecialchars($rec['title']) ?></h4>
                    <span class="recent-article-date"><?= htmlspecialchars($rec['date'] ?? '') ?> &bull; <?= htmlspecialchars($rec['read_time'] ?? '') ?></span>
                  </div>
                </a>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Author Card -->
            <div class="sidebar-widget">
              <div class="sidebar-widget-header">
                <i class="bx bx-user-circle"></i>
                <span>About Author</span>
              </div>
              <div class="sidebar-author-card">
                <img src="images/profile.jpeg" alt="Ganga Sagar Joshi" class="sidebar-author-img" onerror="this.src='images/gangasagar.jpeg'" />
                <div class="sidebar-author-details">
                  <h4>Ganga Sagar Joshi</h4>
                  <p>Co-Founder &amp; Software Engineer at Strataflow Technologies Pvt. Ltd. Specializing in Python, Django, scalable relational databases, and enterprise systems.</p>
                </div>
              </div>
            </div>

          </aside>

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
          <a href="index.html">Home</a>
          <a href="profile.html">Profile</a>
          <a href="insights.html">Insights</a>
          <a href="index.html#contact">Contact</a>
        </div>
      </div>
    </footer>

  </div>

  <!-- Embedded All Posts Payload for Zero-Config Static / GitHub Pages Compatibility -->
  <script id="allPostsPayload" type="application/json">
    <?= json_encode($all_posts ?? [$post]) ?>
  </script>

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

    // Client-side hydration for static GitHub Pages serving (reading ?slug= from URL)
    (function() {
      try {
        const urlParams = new URLSearchParams(window.location.search);
        const slug = urlParams.get('slug');
        const payloadEl = document.getElementById('allPostsPayload');
        
        if (!slug || !payloadEl) return;
        
        const allPosts = JSON.parse(payloadEl.textContent || '[]');
        const target = allPosts.find(p => p.slug === slug);
        
        if (target) {
          document.title = target.title + ' — Ganga Sagar Joshi';
          const metaTitle = document.getElementById('metaTitle');
          if (metaTitle) metaTitle.textContent = target.title + ' — Ganga Sagar Joshi';
          
          const breadcrumbTitle = document.getElementById('breadcrumbTitle');
          if (breadcrumbTitle) breadcrumbTitle.textContent = target.title;
          
          const articleTitle = document.getElementById('articleTitle');
          if (articleTitle) articleTitle.textContent = target.title;
          
          const articleCategory = document.getElementById('articleCategory');
          if (articleCategory) articleCategory.textContent = target.category;
          
          const articleDate = document.getElementById('articleDate');
          if (articleDate) articleDate.textContent = target.date;
          
          const articleReadTime = document.getElementById('articleReadTime');
          if (articleReadTime) articleReadTime.textContent = target.read_time;
          
          const articleImage = document.getElementById('articleImage');
          if (articleImage) {
            articleImage.src = target.image || 'images/projectcard1.jpg';
            articleImage.alt = target.title;
          }
          
          const articleContent = document.getElementById('articleContent');
          if (articleContent && target.content) {
            articleContent.innerHTML = target.content;
          }
          
          // Refresh Recent Articles in sidebar to exclude currently active post
          const recentList = document.getElementById('recentArticlesList');
          if (recentList) {
            const others = allPosts.filter(p => p.slug !== slug).slice(0, 5);
            recentList.innerHTML = others.map(rec => `
              <a href="insight-detail.html?slug=${encodeURIComponent(rec.slug)}" class="recent-article-item">
                <div class="recent-article-thumb">
                  <img src="${rec.image || 'images/projectcard1.jpg'}" alt="${rec.title}" loading="lazy" onerror="this.src='images/projectcard1.jpg'" />
                </div>
                <div class="recent-article-info">
                  <h4 class="recent-article-title">${rec.title}</h4>
                  <span class="recent-article-date">${rec.date || ''} &bull; ${rec.read_time || ''}</span>
                </div>
              </a>
            `).join('');
          }
        }
      } catch (err) {
        console.error('Insight hydration error:', err);
      }
    })();
  </script>
</body>
</html>
