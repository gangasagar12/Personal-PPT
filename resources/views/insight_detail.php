<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title id="metaTitle"><?= htmlspecialchars(($post['title'] ?? 'Insight Detail') . ' — Ganga Sagar Joshi') ?></title>

  <?php
  $postTitle = ($post['title'] ?? 'Insight Detail') . ' — Ganga Sagar Joshi';
  $postDesc = !empty($post['excerpt']) ? $post['excerpt'] : (!empty($post['content']) ? mb_strimwidth(strip_tags($post['content']), 0, 160, '...') : 'Technical engineering insights by Ganga Sagar Joshi.');
  $postSlug = $post['slug'] ?? 'article';
  $postImg = !empty($post['image']) ? $post['image'] : '/images/logo-square.png';

  $articleSchema = [
      '@type' => 'TechArticle',
      'headline' => $post['title'] ?? 'Technical Article',
      'description' => $postDesc,
      'image' => str_starts_with($postImg, 'http') ? $postImg : \App\Core\SEO::baseUrl() . '/' . ltrim($postImg, '/'),
      'author' => [
          '@type' => 'Person',
          'name' => 'Ganga Sagar Joshi'
      ],
      'publisher' => [
          '@type' => 'Organization',
          'name' => 'Strataflow Technologies Pvt. Ltd.'
      ],
      'datePublished' => !empty($post['created_at']) ? date('c', strtotime($post['created_at'])) : date('c')
  ];

  \App\Core\View::partial('seo_meta', [
      'seo_title' => $postTitle,
      'seo_desc' => $postDesc,
      'seo_canonical' => \App\Core\SEO::url('/insights/' . $postSlug),
      'seo_image' => $postImg,
      'seo_type' => 'article',
      'seo_custom_schema' => $articleSchema
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

    <!-- ── Main Detail Layout (Article + Recent Articles Sidebar) ── -->
    <main class="insights-detail-main">
      <div class="insights-container">

        <!-- Breadcrumb Navigation -->
        <nav class="article-breadcrumb" aria-label="Breadcrumb">
          <a href="/insights" class="breadcrumb-back-link">
            <i class="bx bx-arrow-back"></i>
            <span>All Insights</span>
          </a>
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
                  <img src="/images/logo-square.png" alt="Ganga Sagar Joshi" class="article-author-avatar" onerror="this.onerror=null; this.src='/images/ganga-sagar-joshi-profile.jpeg'" />
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
              <img src="/<?= ltrim(htmlspecialchars($post['image'] ?? 'images/projectcard1.jpg'), '/') ?>" 
                   id="articleImage" 
                   alt="<?= htmlspecialchars($post['title'] ?? '') ?>" 
                   onerror="this.onerror=null; this.src='/images/projectcard1.jpg'" />
            </div>

            <!-- Full Article Body (Small, professional typography) -->
            <div class="article-body-content" id="articleContent">
              <?= $post['content'] ?? nl2br(htmlspecialchars($post['excerpt'] ?? '')) ?>
            </div>

            <!-- Footer Return Link -->
            <div class="article-footer-nav">
              <a href="/insights" class="btn-back-insights">
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
                <a href="/insights/<?= urlencode($rec['slug']) ?>" 
                   data-slug="<?= htmlspecialchars($rec['slug']) ?>" 
                   class="recent-article-item">
                  <div class="recent-article-thumb">
                    <img src="/<?= ltrim(htmlspecialchars($rec['image'] ?? 'images/projectcard1.jpg'), '/') ?>" 
                         alt="<?= htmlspecialchars($rec['title']) ?>" 
                         loading="lazy" 
                         onerror="this.onerror=null; this.src='/images/projectcard1.jpg'" />
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
                <img src="/images/logo-square.png" alt="Ganga Sagar Joshi" class="sidebar-author-img" onerror="this.onerror=null; this.src='/images/ganga-sagar-joshi-profile.jpeg'" />
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

  </div>

  <!-- ── Shared Portfolio Footer ── -->
  <?php \App\Core\View::partial('footer'); ?>

  <!-- Shared App JS -->
  <script src="/js/app.js"></script>
</body>
</html>
