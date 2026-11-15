<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title>Strataflow Technologies &amp; Leadership Profile — Ganga Sagar Joshi</title>

  <?php
  \App\Core\View::partial('seo_meta', [
      'seo_title' => 'Strataflow Technologies & Leadership Profile — Ganga Sagar Joshi',
      'seo_desc' => 'Official company and entrepreneurship profile of Strataflow Technologies Pvt. Ltd. co-founded by Ganga Sagar Joshi, delivering enterprise IT solutions and scalable software systems in Kathmandu, Nepal.',
      'seo_canonical' => \App\Core\SEO::url('/profile'),
      'seo_image' => '/images/ganga-sagar-joshi-profile.jpeg',
      'seo_type' => 'profile'
  ]);
  ?>

  <!-- Clean, standard Inter typography -->
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
    rel="stylesheet" />

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  <!-- Base Stylesheet & Dedicated Profile Stylesheet -->
  <link rel="stylesheet" href="/css/style.css" />
  <link rel="stylesheet" href="/css/profile.css" />
</head>

<body>

  <!-- ── Shared Navigation Bar & Mobile Drawer ── -->
  <?php \App\Core\View::partial('navbar', ['active_page' => 'profile']); ?>

  <!-- ── Main Content ── -->
  <main class="profile-page-main">
    <div class="profile-container">

      <!-- Breadcrumb Navigation -->
      <nav class="profile-breadcrumb" aria-label="Breadcrumb">
        <a href="/" class="breadcrumb-back-link">
          <i class="bx bx-arrow-back"></i>
          <span>Portfolio</span>
        </a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">Entrepreneurship &amp; Venture Profile</span>
      </nav>

 
      <section class="strataflow-fullwidth-blur-hero">
        <div class="strataflow-blur-stage">
          <span class="strataflow-shadow-blur-layer" aria-hidden="true">STRATAFLOW</span>
          <h1 class="strataflow-shadow-main-text">STRATAFLOW</h1>
        </div>
      </section>

     
      <section class="about-company-showcase-card">
        <!-- Left Column: Exact Text Content -->
        <div class="about-left-content">
          <h2 class="about-card-title">About Us</h2>

          <p class="about-p-main">
            Strataflow Technologies Pvt. Ltd. is a Nepal-based software development company dedicated to helping businesses grow through technology. We provide a range of digital services, including website design and development, custom software development, AI solutions and automation, UI/UX design, and digital marketing.
          </p>

          <p class="about-p-main">
            Our goal is to understand the unique needs of every business and deliver practical, reliable, and user-friendly solutions that make everyday work easier. From building a professional online presence to developing complete business management systems, we focus on creating technology that delivers real value. Beyond providing technology services to clients, Strataflow also focuses on developing its own software products to address specific industry needs.
          </p>

          <div class="about-card-brand-logo">
            Strata<span>flow</span>
          </div>
        </div>

        <!-- Right Column: Clean Desktop Display Mockup -->
        <div class="about-right-visual-panel">
          <div class="desktop-monitor-mockup">
            <div class="monitor-screen-bezel">
              <div class="monitor-camera"></div>
              <div class="monitor-screen-display">
                <div class="monitor-screen-brand">
                  Strata<span>flow</span>
                </div>
                <div class="monitor-screen-tagline">
                  Unlocking the Power of Technology
                </div>
              </div>
            </div>
            <div class="monitor-chin-bar"></div>
            <div class="monitor-stand-neck"></div>
            <div class="monitor-stand-base"></div>

            <div class="desktop-accessories-row">
              <div class="mini-keyboard"></div>
              <div class="mini-mouse"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════════════════
           3. MY ROLE IN THE COMPANY
           ══════════════════════════════════════════════════════════ -->
      <section class="profile-content-section">
        <div class="section-title-wrap">
          <span class="section-kicker">Leadership</span>
          <h2 class="section-heading-h2">My Role in Strataflow Technologies</h2>
          <p class="section-caption-p">
            Leading product development, backend systems, and technical strategy.
          </p>
        </div>

        <div class="founder-profile-box">
          <div class="founder-header-flex">
            <div class="founder-name-group">
              <h3>Ganga Sagar Joshi</h3>
              <span>Co-Founder &amp; Technical Lead</span>
            </div>
            <div class="founder-tag">
              <i class="bx bx-shield-quarter"></i>
              <span>Technology &amp; Product Development</span>
            </div>
          </div>

          <p class="founder-narrative">
            As Co-Founder and Technical Lead at Strataflow Technologies, I help turn business needs into dependable software. I guide our tech choices, lead backend development, and shape our product roadmap to build tools that solve practical problems for clients.
          </p>

          <!-- 4 Responsibility Pillars -->
          <div class="founder-roles-grid">
            <div class="role-mini-card">
              <div class="role-mini-title">
                <i class="bx bx-git-branch"></i>
                <span>Product Planning &amp; Development</span>
              </div>
              <p>
                Turning client requirements and business needs into clear specifications, project goals, and reliable release schedules.
              </p>
            </div>

            <div class="role-mini-card">
              <div class="role-mini-title">
                <i class="bx bx-layer"></i>
                <span>Architecture &amp; Tech Stack Decisions</span>
              </div>
              <p>
                Selecting reliable tech stacks using Python, Django, REST APIs, and relational databases to build stable applications.
              </p>
            </div>

            <div class="role-mini-card">
              <div class="role-mini-title">
                <i class="bx bx-chip"></i>
                <span>AI &amp; Automation Integration</span>
              </div>
              <p>
                Integrating practical AI tools, automated billing, and data workflows that save time and reduce manual work for businesses.
              </p>
            </div>

            <div class="role-mini-card">
              <div class="role-mini-title">
                <i class="bx bx-line-chart"></i>
                <span>Business Growth &amp; Strategy</span>
              </div>
              <p>
                Collaborating on digital strategy, client consultation, and ensuring every system we deploy creates real value for clients.
              </p>
            </div>
          </div>
        </div>
      </section>

      <section class="profile-content-section">
        <div class="section-title-wrap">
          <span class="section-kicker">Core Expertise</span>
          <h2 class="section-heading-h2">Company Focus Areas</h2>
          <p class="section-caption-p">
            Comprehensive digital services and solutions offered by Strataflow Technologies Pvt. Ltd.
          </p>
        </div>

        <div class="services-card-grid">
          <!-- 1. Website Design & Development -->
          <div class="service-clean-card">
            <div class="service-icon-box">
              <i class="bx bx-globe"></i>
            </div>
            <h3>Website Design &amp; Development</h3>
            <p>
              Building fast, accessible, and responsive websites that establish a credible digital presence and convert visitors into long-term clients.
            </p>
          </div>

          <!-- 2. Custom Software Development -->
          <div class="service-clean-card">
            <div class="service-icon-box">
              <i class="bx bx-code-alt"></i>
            </div>
            <h3>Custom Software Development</h3>
            <p>
              Developing tailored business management systems, centralized databases, and custom backend platforms designed around your specific business needs.
            </p>
          </div>

          <!-- 3. AI Solutions & Automation -->
          <div class="service-clean-card">
            <div class="service-icon-box">
              <i class="bx bx-bot"></i>
            </div>
            <h3>AI Solutions &amp; Automation</h3>
            <p>
              Delivering practical artificial intelligence workflows, smart automated reporting, and process automation to minimize manual overhead.
            </p>
          </div>

          <!-- 4. UI/UX Design -->
          <div class="service-clean-card">
            <div class="service-icon-box">
              <i class="bx bx-palette"></i>
            </div>
            <h3>UI/UX Design</h3>
            <p>
              Designing intuitive, user-friendly digital interfaces focused on simplicity, accessibility, and frictionless user journeys.
            </p>
          </div>

          <!-- 5. Digital Marketing -->
          <div class="service-clean-card">
            <div class="service-icon-box">
              <i class="bx bx-trending-up"></i>
            </div>
            <h3>Digital Marketing</h3>
            <p>
              Strategic digital growth initiatives, brand positioning, and targeted marketing campaigns to help businesses reach their ideal customers.
            </p>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════════════════
           5. PRODUCTS & SOLUTIONS (DENTAL CLINIC OS & BILLING)
           ══════════════════════════════════════════════════════════ -->
      <section class="profile-content-section">
        <div class="section-title-wrap">
          <span class="section-kicker">Software Products</span>
          <h2 class="section-heading-h2">Products &amp; Solutions</h2>
          <p class="section-caption-p">
            Proprietary software products developed by Strataflow Technologies to address specific industry needs.
          </p>
        </div>

        <div class="products-clean-list">
          <?php if (!empty($company_products)): ?>
            <?php foreach ($company_products as $prod): ?>
              <article class="product-feature-card">
                <div class="product-thumb-wrap">
                  <img src="/<?= ltrim(htmlspecialchars($prod['image'] ?: 'images/projectcard1.jpg'), '/') ?>" alt="<?= htmlspecialchars($prod['title']) ?>" loading="lazy" onerror="this.onerror=null; this.src='/images/projectcard1.jpg'" />
                  <span class="product-status-badge">
                    <i class="bx bx-check-circle"></i> <?= htmlspecialchars($prod['status_text'] ?: 'Active Product') ?>
                  </span>
                </div>

                <div class="product-details-wrap">
                  <span class="product-cat-kicker"><?= htmlspecialchars($prod['category'] ?: 'Software Platform') ?></span>
                  <h3><?= htmlspecialchars($prod['title']) ?></h3>
                  <p><?= nl2br(htmlspecialchars($prod['description'])) ?></p>

                  <?php if (!empty($prod['features_array'])): ?>
                    <ul class="product-points-grid">
                      <?php foreach ($prod['features_array'] as $feature): ?>
                        <li><i class="bx bx-check"></i> <?= htmlspecialchars($feature) ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>

                  <div class="product-card-footer">
                    <?php if (!empty($prod['pills_array'])): ?>
                      <div class="product-pills">
                        <?php foreach ($prod['pills_array'] as $pill): ?>
                          <span><?= htmlspecialchars($pill) ?></span>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars($prod['link_url'] ?: 'https://github.com/gangasaagar') ?>" target="_blank" rel="noopener noreferrer" class="product-live-link">
                      <span><?= htmlspecialchars($prod['link_text'] ?: 'View Details') ?></span>
                      <i class="bx bx-right-arrow-alt"></i>
                    </a>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════════════════
           6. CORPORATE ACTION STRIP
           ══════════════════════════════════════════════════════════ -->
      <section class="clean-corporate-cta">
        <div class="cta-left-text">
          <h3>Discuss a Project or Technology Partnership</h3>
          <p>
            Whether you want to build a modern online presence, develop custom business software, or implement our dental clinic management platform, let's talk.
          </p>
        </div>
        <div class="cta-action-btns">
          <a href="/#contact" class="btn-primary-compact">
            <span>Get in Touch</span>
            <i class="bx bx-right-arrow-alt"></i>
          </a>
          <a href="/#projects" class="btn-secondary-compact">
            <span>View Portfolio</span>
          </a>
        </div>
      </section>

    </div>
  </main>

  <!-- ── Shared Portfolio Footer ── -->
  <?php \App\Core\View::partial('footer'); ?>

  <!-- Scripts -->
  <script src="/js/app.js"></script>
</body>

</html>
