<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Career Journey &amp; Awards — Ganga Sagar Joshi</title>
  <meta name="description" content="Detailed history of working, software engineering leadership, career milestones, and awards/recognitions earned by Ganga Sagar Joshi." />

  <!-- Favicon & Touch Icons -->
  <link rel="icon" type="image/x-icon" href="/favicon.ico" />
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png" />
  <meta property="og:image" content="/images/logo-square.png" />

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  <!-- Shared & Custom Styles -->
  <link rel="stylesheet" href="/css/style.css" />
  <link rel="stylesheet" href="/css/experience.css" />
</head>
<body class="experience-page-body">

  <!-- ── Shared Navigation Bar & Mobile Drawer ── -->
  <?php \App\Core\View::partial('navbar', ['active_page' => 'experience']); ?>

  <div class="experience-page-wrapper">

    <!-- ── Page Hero Header ── -->
    <section class="exp-hero-banner">
      <div class="container exp-hero-container">
        <div class="exp-breadcrumb">
          <a href="/" class="breadcrumb-back-link">
            <i class="bx bx-arrow-back"></i>
            <span>Back to Home</span>
          </a>
          <span class="breadcrumb-separator">/</span>
          <span class="breadcrumb-current">Career &amp; Honors</span>
        </div>

        <h1 class="exp-hero-title">
          Career Journey &amp; <span class="gradient-text">Honors</span>
        </h1>
        <p class="exp-hero-lead">
          A comprehensive record of production software engineering roles, platform leadership milestones, and industry recognitions earned throughout my career.
        </p>

        <div class="exp-hero-pills">
          <a href="#timeline" class="exp-jump-pill"><i class="bx bx-time-five"></i> History of Working</a>
          <a href="#awards" class="exp-jump-pill"><i class="bx bx-trophy"></i> Awards &amp; Gallery</a>
        </div>
      </div>
    </section>

    <!-- ── 1. Full Working History (Timeline) ── -->
    <section class="exp-full-section" id="timeline">
      <div class="container">

        <div class="section-heading-block">
          <span class="section-tag">Detailed Career Record</span>
          <h2 class="section-main-title">
            History of <span class="gradient-text">Working</span>
          </h2>
          <p class="section-subtitle">
            Chronological progression of engineering responsibilities, architectural decisions, and production systems built.
          </p>
        </div>

        <div class="serpentine-timeline exp-full-tree">
          <?php if (!empty($experiences)): ?>
            <?php foreach ($experiences as $index => $exp): 
              $isEven = ($index % 2 !== 0);
              $stepNum = $index + 1;
              $hasNext = isset($experiences[$index + 1]);
            ?>
              <div class="serpentine-block <?= $isEven ? 'block-right' : 'block-left' ?>">
                
                <!-- Numbered Circle Node -->
                <div class="serpentine-node"><?= $stepNum ?></div>

                <!-- Main Content -->
                <div class="serpentine-content">
                  <h3 class="serpentine-role"><?= htmlspecialchars($exp['role']) ?></h3>
                  <div class="serpentine-company"><?= htmlspecialchars($exp['company']) ?></div>
                  <div class="serpentine-meta"><?= htmlspecialchars($exp['period']) ?>, <?= htmlspecialchars($exp['location']) ?></div>
                  
                  <p class="serpentine-desc"><?= htmlspecialchars($exp['description']) ?></p>

                  <?php if (!empty($exp['responsibilities_array'])): ?>
                    <div class="serpentine-resp-label">Responsibilities:</div>
                    <ul class="serpentine-bullets">
                      <?php foreach ($exp['responsibilities_array'] as $resp): ?>
                        <li><?= htmlspecialchars($resp) ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>

                  <?php if (!empty($exp['skills_array'])): ?>
                    <div class="exp-tech-tags" style="margin-top: 1rem;">
                      <?php foreach ($exp['skills_array'] as $skill): ?>
                        <span class="exp-tech-tag"><?= htmlspecialchars($skill) ?></span>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>

                <!-- Connecting Serpentine Turns -->
                <?php if ($hasNext): ?>
                  <?php if (!$isEven): ?>
                    <div class="serpentine-turn turn-left-to-right" aria-hidden="true">
                      <div class="turn-corner-left"></div>
                      <div class="turn-bar"></div>
                      <div class="turn-corner-right"></div>
                    </div>
                  <?php else: ?>
                    <div class="serpentine-turn turn-right-to-left" aria-hidden="true">
                      <div class="turn-corner-right"></div>
                      <div class="turn-bar"></div>
                      <div class="turn-corner-left"></div>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>

              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

      </div>
    </section>

    <!-- ── 2. Awards & Gallery Section ── -->
    <section class="awards-gallery-section" id="awards">
      <div class="container">

        <div class="section-heading-block">
          <span class="section-tag">Recognitions &amp; Achievements</span>
          <h2 class="section-main-title">
            Awards &amp; <span class="gradient-text">Honors Gallery</span>
          </h2>
          <p class="section-subtitle">
            Verified academic milestones, hackathon awards, and software architecture distinctions earned throughout my journey.
          </p>
        </div>

        <div class="awards-gallery-grid">
          <?php if (!empty($awards)): ?>
            <?php foreach ($awards as $award): ?>
              <article class="award-card">
                <div class="award-image-wrap">
                  <img src="/<?= ltrim(htmlspecialchars($award['image'] ?: 'images/projectcard1.jpg'), '/') ?>" 
                       alt="<?= htmlspecialchars($award['title']) ?>" 
                       loading="lazy" 
                       onerror="this.onerror=null; this.src='/images/projectcard1.jpg'" />
                  <div class="award-image-overlay"></div>
                  
                  <?php if (!empty($award['date'])): ?>
                    <span class="award-year-badge"><?= htmlspecialchars($award['date']) ?></span>
                  <?php endif; ?>
                </div>

                <div class="award-body">
                  <?php if (!empty($award['issuer'])): ?>
                    <div class="award-issuer">
                      <i class="bx bx-award"></i>
                      <span><?= htmlspecialchars($award['issuer']) ?></span>
                    </div>
                  <?php endif; ?>

                  <h3 class="award-title"><?= htmlspecialchars($award['title']) ?></h3>
                  
                  <p class="award-description">
                    <?= htmlspecialchars($award['description'] ?? '') ?>
                  </p>
                </div>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

      </div>
    </section>

    <!-- ── Call To Action Banner ── -->
    <section class="exp-cta-section">
      <div class="container">
        <div class="exp-cta-card">
          <div class="exp-cta-content">
            <span class="exp-cta-tag">Ready for Collaboration</span>
            <h2>Let's Engineer Scalable Software Together</h2>
            <p>
              Looking for a dedicated Django backend architect, clinical systems engineer, or technology partner for your next venture? Let's talk.
            </p>
          </div>
          <div class="exp-cta-buttons">
            <a href="/#contact" class="btn btn-primary">
              <span>Discuss a Project</span>
              <i class="bx bx-right-arrow-alt"></i>
            </a>
            <a href="/pdf/AWS1.pdf" download class="btn btn-secondary">
              <i class="bx bx-download"></i>
              <span>Download CV</span>
            </a>
          </div>
        </div>
      </div>
    </section>

  </div>

  <!-- ── Shared Portfolio Footer ── -->
  <?php \App\Core\View::partial('footer'); ?>

  <!-- Shared App JS -->
  <script src="/js/app.js"></script>
</body>
</html>

