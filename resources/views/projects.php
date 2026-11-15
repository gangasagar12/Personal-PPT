<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title><?= htmlspecialchars($page_title ?? 'Projects — Ganga Sagar Joshi') ?></title>
  <meta name="description" content="Portfolio and projects of Ganga Sagar Joshi, featuring web applications, Django backends, and software solutions." />

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

  <!-- Shared & Page Styles -->
  <link rel="stylesheet" href="/css/style.css" />
  <link rel="stylesheet" href="/css/projects.css" />
</head>
<body class="projects-page-body">

  <!-- ── Shared Navigation Bar & Mobile Drawer ── -->
  <?php \App\Core\View::partial('navbar', ['active_page' => 'projects']); ?>

  <!-- ── Main Projects Archive Content ── -->
  <main class="projects-page-wrap">
    <div class="container">

      <!-- Hero Header Block -->
      <section class="projects-hero-header">
        <span class="section-tag">Portfolio</span>
        <h1 class="projects-hero-title">
          All <span class="gradient-text">Projects</span>
        </h1>
        <p class="projects-hero-subtitle">
          Web applications, backend systems, and projects I have built.
        </p>
      </section>

      <?php 
        $countFeatured = count($featured_projects ?? []);
        $countAcademic = count($academic_projects ?? []);
        $countOther = count($other_projects ?? []);
        $countTotal = $countFeatured + $countAcademic + $countOther;
      ?>

      <!-- Category Filter Tabs Bar -->
      <div class="projects-filter-wrap">
        <div class="projects-filter-bar" role="tablist" aria-label="Project category filters">
          <button class="project-tab-btn active" data-filter="all" role="tab" aria-selected="true">
            <span>All Projects</span>
            <span class="tab-badge"><?= $countTotal ?></span>
          </button>
          <button class="project-tab-btn" data-filter="featured" role="tab" aria-selected="false">
            <span>Featured Projects</span>
            <span class="tab-badge"><?= $countFeatured ?></span>
          </button>
          <button class="project-tab-btn" data-filter="academic" role="tab" aria-selected="false">
            <span>Key Academic Projects</span>
            <span class="tab-badge"><?= $countAcademic ?></span>
          </button>
          <button class="project-tab-btn" data-filter="other" role="tab" aria-selected="false">
            <span>Some Other Projects</span>
            <span class="tab-badge"><?= $countOther ?></span>
          </button>
        </div>
      </div>

      <!-- Projects Archive Grid -->
      <div class="projects-archive-grid" id="projectsGrid">

        <!-- ── 1. Featured Projects ── -->
        <?php foreach ($featured_projects ?? [] as $fp): 
          $slug = !empty($fp['slug']) ? $fp['slug'] : \App\Models\FeaturedProject::slugify($fp['title']);
          $detailUrl = "/works/" . urlencode($slug);
          $fpImage = !empty($fp['image']) ? $fp['image'] : 'images/projectcard1.jpg';
          $fpDesc = !empty($fp['short_description']) ? $fp['short_description'] : $fp['description'];
        ?>
        <article class="project-archive-card" data-category="featured">
          <div class="project-archive-image-wrap">
            <a href="<?= $detailUrl ?>" aria-label="<?= htmlspecialchars($fp['title']) ?>">
              <img src="/<?= ltrim(htmlspecialchars($fpImage), '/') ?>" 
                   alt="<?= htmlspecialchars($fp['title']) ?>" 
                   loading="lazy" 
                   onerror="this.onerror=null; this.src='/images/projectcard1.jpg'" />
            </a>
            <span class="project-category-pill pill-featured">Featured Project</span>
          </div>

          <div class="project-archive-body">
            <h3 class="project-archive-title">
              <a href="<?= $detailUrl ?>" style="color:inherit; text-decoration:none;">
                <?= htmlspecialchars($fp['title']) ?>
              </a>
            </h3>

            <p class="project-archive-desc">
              <?= htmlspecialchars($fpDesc) ?>
            </p>

            <div class="project-archive-skills">
              <?php foreach (array_slice($fp['skills_array'] ?? [], 0, 4) as $s): ?>
                <span><?= htmlspecialchars($s) ?></span>
              <?php endforeach; ?>
            </div>

            <div class="project-archive-footer">
              <a href="<?= $detailUrl ?>" class="project-action-link">
                <span>View Case Study</span>
                <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
              </a>

              <?php if (!empty($fp['live_url'])): ?>
              <a href="<?= htmlspecialchars($fp['live_url']) ?>" target="_blank" rel="noopener noreferrer" class="project-secondary-link" title="Visit Live Platform">
                <span>Live Site</span>
                <i class="bx bx-link-external" aria-hidden="true"></i>
              </a>
              <?php endif; ?>
            </div>
          </div>
        </article>
        <?php endforeach; ?>

        <!-- ── 2. Key Academic Projects ── -->
        <?php foreach ($academic_projects ?? [] as $ap): 
          $apImage = !empty($ap['image']) ? $ap['image'] : 'images/projectcard2.jpg';
          $apShortDesc = $ap['description'];
          $apDetailedDesc = !empty($ap['detailed_description']) ? $ap['detailed_description'] : $ap['description'];
        ?>
        <article class="project-archive-card academic-project-card" 
                 data-category="academic"
                 tabindex="0"
                 role="button"
                 data-academic-modal-trigger
                 data-title="<?= htmlspecialchars($ap['title'], ENT_QUOTES) ?>"
                 data-image="/<?= ltrim(htmlspecialchars($apImage), '/') ?>"
                 data-short-desc="<?= htmlspecialchars($apShortDesc, ENT_QUOTES) ?>"
                 data-detailed-desc="<?= htmlspecialchars($apDetailedDesc, ENT_QUOTES) ?>"
                 data-skills="<?= htmlspecialchars($ap['skills'], ENT_QUOTES) ?>"
                 aria-haspopup="dialog"
                 aria-label="View details for <?= htmlspecialchars($ap['title']) ?>">
          
          <div class="project-archive-image-wrap">
            <img src="/<?= ltrim(htmlspecialchars($apImage), '/') ?>" 
                 alt="<?= htmlspecialchars($ap['title']) ?>" 
                 loading="lazy" 
                 onerror="this.onerror=null; this.src='/images/projectcard2.jpg'" />
            <span class="project-category-pill pill-academic">BCA Academic Project</span>
          </div>

          <div class="project-archive-body">
            <h3 class="project-archive-title">
              <?= htmlspecialchars($ap['title']) ?>
            </h3>

            <p class="project-archive-desc">
              <?= htmlspecialchars($apShortDesc) ?>
            </p>

            <div class="project-archive-skills">
              <?php foreach (array_slice($ap['skills_array'] ?? [], 0, 4) as $ask): ?>
                <span><?= htmlspecialchars($ask) ?></span>
              <?php endforeach; ?>
            </div>

            <div class="project-archive-footer">
              <span class="project-action-link">
                <span>View Project Details</span>
                <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
              </span>
              <span class="project-secondary-link" style="color:var(--t3);">
                <i class="bx bxs-graduation" aria-hidden="true"></i> Coursework
              </span>
            </div>
          </div>
        </article>
        <?php endforeach; ?>

        <!-- ── 3. Some Other Projects ── -->
        <?php foreach ($other_projects ?? [] as $op): 
          $opImage = !empty($op['image']) ? $op['image'] : 'images/projectcard3.webp';
          $githubUrl = !empty($op['github_url']) ? $op['github_url'] : 'https://github.com/gangasagar12';
          $liveUrl = !empty($op['live_url']) ? $op['live_url'] : $githubUrl;
        ?>
        <article class="project-archive-card" data-category="other">
          <div class="project-archive-image-wrap">
            <a href="<?= htmlspecialchars($githubUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= htmlspecialchars($op['title']) ?>">
              <img src="/<?= ltrim(htmlspecialchars($opImage), '/') ?>" 
                   alt="<?= htmlspecialchars($op['title']) ?>" 
                   loading="lazy" 
                   onerror="this.onerror=null; this.src='/images/projectcard3.webp'" />
            </a>
            <span class="project-category-pill pill-other"><?= htmlspecialchars($op['category'] ?? 'Software Tool') ?></span>
          </div>

          <div class="project-archive-body">
            <h3 class="project-archive-title">
              <a href="<?= htmlspecialchars($githubUrl) ?>" target="_blank" rel="noopener noreferrer" style="color:inherit; text-decoration:none;">
                <?= htmlspecialchars($op['title']) ?>
              </a>
            </h3>

            <p class="project-archive-desc">
              <?= htmlspecialchars($op['description']) ?>
            </p>

            <div class="project-archive-skills">
              <?php foreach (array_slice($op['skills_array'] ?? [], 0, 4) as $osk): ?>
                <span><?= htmlspecialchars($osk) ?></span>
              <?php endforeach; ?>
            </div>

            <div class="project-archive-footer">
              <a href="<?= htmlspecialchars($githubUrl) ?>" target="_blank" rel="noopener noreferrer" class="project-action-link">
                <span>Explore Source Code</span>
                <i class="bx bx-link-external" aria-hidden="true"></i>
              </a>

              <span class="project-secondary-link" style="color:var(--t3);">
                <i class="bx bxl-github" aria-hidden="true"></i> GitHub
              </span>
            </div>
          </div>
        </article>
        <?php endforeach; ?>

        <!-- Empty State (Fallback for filters) -->
        <div class="projects-empty-state" id="projectsEmptyState">
          <i class="bx bx-folder-open"></i>
          <h4>No projects found</h4>
          <p>No projects match the selected category filter at this time.</p>
        </div>

      </div>

    </div>
  </main>

  <!-- ── Academic Project Details Modal (Popup on Card Click) ── -->
  <div class="academic-modal" id="academicDetailModal" aria-hidden="true" role="dialog" aria-labelledby="academicModalTitle">
    <div class="academic-modal-backdrop" data-close-academic-modal></div>
    <div class="academic-modal-dialog">
      <button type="button" class="academic-modal-close" data-close-academic-modal aria-label="Close modal">
        <i class="bx bx-x" aria-hidden="true"></i>
      </button>
      <div class="academic-modal-media">
        <img id="academicModalImg" src="/images/projectcard2.jpg" alt="Academic Project Preview" />
        <span class="academic-modal-tag" id="academicModalTag">University Coursework</span>
      </div>
      <div class="academic-modal-body">
        <div class="academic-modal-header">
          <span class="section-tag" style="margin-bottom:0.35rem; display:inline-block;">Kantipur City College &bull; BCA</span>
          <h3 id="academicModalTitle" class="academic-modal-title">Project Title</h3>
        </div>
        <div class="academic-modal-content">
          <h4 class="academic-modal-subheading">Project Architecture &amp; Implementation</h4>
          <p id="academicModalDesc" class="academic-modal-desc"></p>
        </div>
        <div class="academic-modal-tech-wrap">
          <h5 class="academic-modal-tech-title">Technologies &amp; Core Concepts</h5>
          <div id="academicModalSkills" class="academic-modal-skills"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── Shared Portfolio Footer ── -->
  <?php \App\Core\View::partial('footer'); ?>

  <!-- Shared App JS -->
  <script src="/js/app.js"></script>

  <!-- Page Specific Scripts -->
  <script>
    // ── Instant Category Filter Tabs ──
    const filterTabs = document.querySelectorAll('.project-tab-btn');
    const projectCards = document.querySelectorAll('.project-archive-card');
    const emptyState = document.getElementById('projectsEmptyState');

    filterTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        filterTabs.forEach(t => {
          t.classList.remove('active');
          t.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('active');
        tab.setAttribute('aria-selected', 'true');

        const filter = tab.getAttribute('data-filter');
        let visibleCount = 0;

        projectCards.forEach(card => {
          const category = card.getAttribute('data-category');
          if (filter === 'all' || category === filter) {
            card.style.display = 'flex';
            card.style.opacity = '0';
            card.style.transform = 'translateY(12px)';
            setTimeout(() => {
              card.style.transition = 'opacity 0.28s ease, transform 0.28s ease';
              card.style.opacity = '1';
              card.style.transform = 'translateY(0)';
            }, 30);
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });

        if (emptyState) {
          emptyState.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
      });
    });

    // ── Key Academic Projects Modal Handling ──
    const academicModal = document.getElementById('academicDetailModal');
    if (academicModal) {
      const modalImg = document.getElementById('academicModalImg');
      const modalTitle = document.getElementById('academicModalTitle');
      const modalDesc = document.getElementById('academicModalDesc');
      const modalSkills = document.getElementById('academicModalSkills');
      const closeBtns = academicModal.querySelectorAll('[data-close-academic-modal]');
      const triggers = document.querySelectorAll('[data-academic-modal-trigger]');

      function openModal(card) {
        const title = card.getAttribute('data-title') || '';
        const img = card.getAttribute('data-image') || '/images/projectcard1.jpg';
        const desc = card.getAttribute('data-detailed-desc') || card.getAttribute('data-short-desc') || '';
        const skills = card.getAttribute('data-skills') || '';

        if (modalTitle) modalTitle.textContent = title;
        if (modalDesc) modalDesc.textContent = desc;
        if (modalImg) {
          modalImg.src = img;
          modalImg.alt = title;
        }

        if (modalSkills) {
          modalSkills.innerHTML = '';
          if (skills) {
            const list = skills.split(',').map(s => s.trim()).filter(Boolean);
            list.forEach(skill => {
              const span = document.createElement('span');
              span.textContent = skill;
              modalSkills.appendChild(span);
            });
          }
        }

        academicModal.classList.add('is-open');
        academicModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
      }

      function closeModal() {
        academicModal.classList.remove('is-open');
        academicModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }

      triggers.forEach(card => {
        card.addEventListener('click', (e) => {
          e.preventDefault();
          openModal(card);
        });

        card.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            openModal(card);
          }
        });
      });

      closeBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          closeModal();
        });
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && academicModal.classList.contains('is-open')) {
          closeModal();
        }
      });
    }
  </script>
</body>
</html>
