<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title><?= htmlspecialchars($page_title ?? 'Technical Insights & Engineering Notes — Ganga Sagar Joshi') ?></title>

  <?php
  \App\Core\View::partial('seo_meta', [
      'seo_title' => $page_title ?? 'Technical Insights & Engineering Notes — Ganga Sagar Joshi',
      'seo_desc' => 'Technical engineering insights, software architecture deep dives, database design tutorials, and system engineering notes by Ganga Sagar Joshi.',
      'seo_canonical' => \App\Core\SEO::url('/insights'),
      'seo_image' => '/images/ganga-sagar-joshi-profile.jpeg',
      'seo_type' => 'blog'
  ]);
  ?>

  <!-- Fonts & Icons -->
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
          Practical articles on backend development, databases, and software solutions.
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

        <?php if (empty($posts)): ?>
          <div style="text-align: center; padding: 4rem 1rem; color: var(--color-text-muted);">
            <i class="bx bx-news" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem; display: block;"></i>
            <p style="font-size: 1.1rem; font-weight: 500;">No articles published yet</p>
            <p style="font-size: 0.85rem; margin-top: 0.25rem;">New articles, tutorials, and development notes will appear here soon.</p>
          </div>
        <?php else: ?>
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
        <?php endif; ?>

      </div>
    </main>

  </div>

  <!-- ── Shared Portfolio Footer ── -->
  <?php \App\Core\View::partial('footer'); ?>

  <!-- Shared App JS -->
  <script src="/js/app.js"></script>
</body>
</html>
