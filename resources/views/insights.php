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

  <!-- ── Shared Navigation Bar & Mobile Drawer ── -->
  <?php \App\Core\View::partial('navbar', ['active_page' => 'insights']); ?>

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

  </div>

  <!-- ── Shared Portfolio Footer ── -->
  <?php \App\Core\View::partial('footer'); ?>

  <!-- Shared App JS -->
  <script src="/js/app.js"></script>
</body>
</html>
