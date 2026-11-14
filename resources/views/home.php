<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="google-site-verification" content="yC0FjFq6eXnCaYa6D_VLggwR7D7smVDaFGP6gQ3wn4w" />

  <meta name="author" content="Ganga Sagar Joshi" />
  <meta name="description"
    content="Ganga Sagar Joshi's personal portfolio website showcasing his skills, projects, and contact information." />
  <title>Ganga Sagar Joshi</title>

  <!-- Preconnect for faster font/icon load -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

  <!-- Inter & Poppins fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap"
    rel="stylesheet" />

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  <!-- Your CSS -->
  <link rel="stylesheet" href="/css/style.css" />
  <link rel="stylesheet" href="/css/insights.css" />
</head>

<body>

  <!-- ── Shared Navigation Bar & Mobile Drawer ── -->
  <?php \App\Core\View::partial('navbar', ['active_page' => 'home']); ?>

  <main>

    <!-- ── Hero Section ── -->
    <section class="hero-section" id="home">
      <!-- Decorative background effects -->
      <div class="hero-ambient-glows" aria-hidden="true">
        <div class="ambient-glow glow-blue"></div>
        <div class="ambient-glow glow-purple"></div>
        <div class="hero-dot-grid"></div>
      </div>

      <div class="container hero-container">

        <!-- ── Left: Introduction & Headline ── -->
        <div class="hero-content">
          <!-- Availability Tag -->
          <div class="hero-badge">
            <span class="status-pulse" aria-hidden="true"></span>
            <span>Available for new projects &amp; full-time opportunities</span>
          </div>

          <div class="hero-headings">
            <p class="hero-greeting">Hello, It's me</p>
            <h1 class="hero-name">
              Ganga Sagar <span class="gradient-text">Joshi</span>
            </h1>
            <div class="hero-role-line">
              <span class="role-prefix">I am a </span>
              <span class="typed-text"></span>
            </div>
          </div>

          <p class="hero-description">
            Software Developer and Machine Learning enthusiast pursuing a Bachelor of Computer Application (BCA) at
            Kantipur City College. I build robust backend architectures using Python and Django, craft modern web
            solutions, and actively explore cloud infrastructure and AI.
          </p>

          <!-- Action Buttons -->
          <div class="hero-actions">
            <a href="#projects" class="btn btn-primary hero-btn">
              <span>Explore Projects</span>
              <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
            </a>
            <a href="pdf/AWS1.pdf" download class="btn btn-secondary hero-btn">
              <i class="bx bx-download" aria-hidden="true"></i>
              <span>Download CV</span>
            </a>
          </div>

          <!-- Social Links -->
          <div class="hero-social-block">
            <span class="social-caption">Connect with me:</span>
            <div class="hero-social-links">
              <a href="https://github.com/gangasaagar" aria-label="GitHub Profile" target="_blank"
                rel="noopener noreferrer" class="social-icon">
                <i class="bx bxl-github" aria-hidden="true"></i>
              </a>
              <a href="https://www.linkedin.com/in/ganga-sagar15" aria-label="LinkedIn Profile" target="_blank"
                rel="noopener noreferrer" class="social-icon">
                <i class="bx bxl-linkedin" aria-hidden="true"></i>
              </a>
              <a href="https://www.facebook.com/ganga.sagar.joshi.2025/directory_links" aria-label="Facebook Profile"
                target="_blank" rel="noopener noreferrer" class="social-icon">
                <i class="bx bxl-facebook" aria-hidden="true"></i>
              </a>
              <a href="https://www.instagram.com/ganga_sagar_joshi/" aria-label="Instagram Profile" target="_blank"
                rel="noopener noreferrer" class="social-icon">
                <i class="bx bxl-instagram" aria-hidden="true"></i>
              </a>
            </div>
          </div>

          <!-- Highlights bar -->
          <div class="hero-highlights">
            <div class="highlight-item">
              <span class="highlight-val">2+</span>
              <span class="highlight-txt">Years Coding</span>
            </div>
            <div class="highlight-separator" aria-hidden="true"></div>
            <div class="highlight-item">
              <span class="highlight-val">BCA</span>
              <span class="highlight-txt">Kantipur City College</span>
            </div>
            <div class="highlight-separator" aria-hidden="true"></div>
            <div class="highlight-item">
              <span class="highlight-val">100%</span>
              <span class="highlight-txt">Problem Solver</span>
            </div>
          </div>
        </div>

        <!-- ── Right: Professional Photograph & Visual Composition (Transparent Cutout) ── -->
        <div class="hero-visual">
          <div class="visual-wrapper transparent-photo-wrapper">
            <!-- Background Watermark Typography for Right Column (Behind Portrait) -->
            <div class="hero-visual-watermark" aria-hidden="true">
              <span>GANGA</span>
              <span>SAGAR</span>
              <span>JOSHI</span>
            </div>

            <!-- Decorative aura & shapes -->
            <div class="visual-aura aura-blue" aria-hidden="true"></div>
            <div class="visual-aura aura-purple" aria-hidden="true"></div>
            <div class="visual-aura aura-center-glow" aria-hidden="true"></div>
            <div class="visual-pattern-dots" aria-hidden="true"></div>

            <!-- Transparent Cutout Portrait (no box border, no badges over image) -->
            <div class="transparent-portrait-box">
              <img src="/images/profile_transparent.png" alt="Ganga Sagar Joshi - Professional Portrait" class="hero-transparent-photo"
                width="960" height="1280" loading="eager" />
            </div>
          </div>
        </div>

      </div>

      <!-- Subtle scroll cue -->
      <a href="#about" class="hero-scroll-cue" aria-label="Scroll down to About section">
        <span class="mouse-icon">
          <span class="mouse-wheel"></span>
        </span>
        <span class="cue-text">Scroll</span>
      </a>
    </section>

    <!-- ── Continuous Flowing Tech & Capabilities Marquee (Flowing directly on page, NO separate cards) ── -->
    <div class="hero-tech-marquee" aria-label="Key Technologies and Strengths">
      <div class="marquee-track">
        <!-- Sequence 1 -->
        <div class="marquee-flow-item">
          <div class="flow-icon python-theme"><i class="bx bxl-python"></i></div>
          <div class="flow-text">
            <span class="flow-title">Python &amp; Django</span>
            <span class="flow-sub">Backend Engineering</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item">
          <div class="flow-icon pill-rocket-theme"><span class="pill-icon">🚀</span></div>
          <div class="flow-text">
            <span class="flow-title">High-Impact Solutions</span>
            <span class="flow-sub">Production Systems</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item">
          <div class="flow-icon aws-theme"><i class="bx bxl-aws"></i></div>
          <div class="flow-text">
            <span class="flow-title">AWS &amp; Cloud</span>
            <span class="flow-sub">DevOps &amp; Infra</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item">
          <div class="flow-icon api-theme"><i class="bx bx-server"></i></div>
          <div class="flow-text">
            <span class="flow-title">Scalable APIs</span>
            <span class="flow-sub">REST &amp; PostgreSQL</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item">
          <div class="flow-icon db-theme"><i class="bx bx-data"></i></div>
          <div class="flow-text">
            <span class="flow-title">Relational Databases</span>
            <span class="flow-sub">Schema Design &amp; 3NF</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item">
          <div class="flow-icon docker-theme"><i class="bx bxl-docker"></i></div>
          <div class="flow-text">
            <span class="flow-title">Docker &amp; Containers</span>
            <span class="flow-sub">Microservices &amp; CI/CD</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item">
          <div class="flow-icon ml-theme"><i class="bx bx-brain"></i></div>
          <div class="flow-text">
            <span class="flow-title">Machine Learning</span>
            <span class="flow-sub">AI &amp; Predictive Models</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item">
          <div class="flow-icon security-theme"><i class="bx bx-shield-quarter"></i></div>
          <div class="flow-text">
            <span class="flow-title">JWT &amp; RBAC</span>
            <span class="flow-sub">Secure Authentication</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item">
          <div class="flow-icon perf-theme"><i class="bx bx-line-chart"></i></div>
          <div class="flow-text">
            <span class="flow-title">Query Optimization</span>
            <span class="flow-sub">Eliminating N+1 Latency</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item">
          <div class="flow-icon saas-theme"><i class="bx bx-layer"></i></div>
          <div class="flow-text">
            <span class="flow-title">Enterprise SaaS</span>
            <span class="flow-sub">Scalable Architectures</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <!-- Sequence 2 (Duplicate for continuous seamless loop) -->
        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon python-theme"><i class="bx bxl-python"></i></div>
          <div class="flow-text">
            <span class="flow-title">Python &amp; Django</span>
            <span class="flow-sub">Backend Engineering</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon pill-rocket-theme"><span class="pill-icon">🚀</span></div>
          <div class="flow-text">
            <span class="flow-title">High-Impact Solutions</span>
            <span class="flow-sub">Production Systems</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon aws-theme"><i class="bx bxl-aws"></i></div>
          <div class="flow-text">
            <span class="flow-title">AWS &amp; Cloud</span>
            <span class="flow-sub">DevOps &amp; Infra</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon api-theme"><i class="bx bx-server"></i></div>
          <div class="flow-text">
            <span class="flow-title">Scalable APIs</span>
            <span class="flow-sub">REST &amp; PostgreSQL</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon db-theme"><i class="bx bx-data"></i></div>
          <div class="flow-text">
            <span class="flow-title">Relational Databases</span>
            <span class="flow-sub">Schema Design &amp; 3NF</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon docker-theme"><i class="bx bxl-docker"></i></div>
          <div class="flow-text">
            <span class="flow-title">Docker &amp; Containers</span>
            <span class="flow-sub">Microservices &amp; CI/CD</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon ml-theme"><i class="bx bx-brain"></i></div>
          <div class="flow-text">
            <span class="flow-title">Machine Learning</span>
            <span class="flow-sub">AI &amp; Predictive Models</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon security-theme"><i class="bx bx-shield-quarter"></i></div>
          <div class="flow-text">
            <span class="flow-title">JWT &amp; RBAC</span>
            <span class="flow-sub">Secure Authentication</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon perf-theme"><i class="bx bx-line-chart"></i></div>
          <div class="flow-text">
            <span class="flow-title">Query Optimization</span>
            <span class="flow-sub">Eliminating N+1 Latency</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon saas-theme"><i class="bx bx-layer"></i></div>
          <div class="flow-text">
            <span class="flow-title">Enterprise SaaS</span>
            <span class="flow-sub">Scalable Architectures</span>
          </div>
        </div>
        <span class="flow-divider" aria-hidden="true">✦</span>
      </div>
    </div>


    <!-- ── About Me Section (Editorial Layout with Signature) ── -->
    <section class="about-editorial-section" id="about">
      <div class="container">

        <!-- Section Heading -->
        <div class="section-heading-block text-center">
          <span class="section-tag">About Me</span>
          <h2 class="section-main-title">
            Get To Know <span class="gradient-text">Me</span>
          </h2>
          <p class="section-subtitle">
            A glimpse into my background, backend engineering philosophy, and journey in building technology.
          </p>
        </div>

        <!-- Editorial Split Layout -->
        <div class="about-editorial-grid">

          <!-- Left Column: Portrait Photograph + Original Transparent Signature -->
          <div class="about-visual-column">
            <div class="about-transparent-photo-wrap">
              <img src="/images/gangasagar_transparent.png" alt="Ganga Sagar Joshi" class="about-transparent-portrait"
                width="340" height="453" loading="lazy" />
            </div>

            <!-- Original Transparent Signature (No Hover Effect) -->
            <div class="about-signature-block">
              <img src="/images/signature.png" alt="Ganga Sagar Joshi Signature" class="about-signature-img" width="220"
                height="94" loading="lazy" />
              <span class="signature-caption">Ganga Sagar Joshi</span>

              <div class="signature-contact-info">
                <span class="signature-contact-label">Contact Me</span>
                <a href="tel:+9779746332963" class="signature-contact-item">
                  <i class="bx bx-phone" aria-hidden="true"></i> +977 9746332963
                </a>
                <a href="mailto:gangasaagar62@gmail.com" class="signature-contact-item">
                  <i class="bx bx-envelope" aria-hidden="true"></i> gangasaagar62@gmail.com
                </a>
              </div>
            </div>
          </div>

          <!-- Right Column: Description & Story -->
          <div class="about-content-column">
            <div class="about-role-badge">
              <i class="bx bx-code-alt" aria-hidden="true"></i>
              <span>Django Backend Developer &bull; Kathmandu, Nepal</span>
            </div>

            <!-- 2-Column Editorial Paragraphs matching the user's reference -->
            <div class="about-columns-wrap">
              <div class="about-text-col">
                <p class="editorial-p">
                  Hey! I'm <strong>Ganga Sagar Joshi</strong>, a software developer and Django backend engineer based in
                  Kathmandu, Nepal. I hold a Bachelor of Computer Applications (BCA) from Kantipur City College,
                  affiliated with Purbanchal University.
                </p>
                <p class="editorial-p">
                  I specialize in architecting scalable server-side systems, high-throughput REST APIs, and normalized
                  relational database schemas with Python, Django, PostgreSQL, and MySQL. My engineering approach
                  centers on writing clean, maintainable code and solving real-world institutional problems.
                </p>
                <p class="editorial-p">
                  As Co-Founder and technical lead at Inovix Technologies, I've built production clinical practice
                  platforms (such as Oralix and CareFirst Dental), unifying multi-chair operatory scheduling, patient
                  health records, and billing.
                </p>
              </div>

              <div class="about-text-col">
                <p class="editorial-p">
                  When I'm not writing backend services or optimizing database queries, you'll find me exploring AWS
                  cloud infrastructure, containerized deployments with Docker, and practical machine learning workflows.
                </p>
                <p class="editorial-p">
                  I believe software engineering is the most effective medium to create tangible value for businesses
                  and communities. Whether collaborating with startups, enterprise teams, or institutional clients, I
                  prioritize clear architecture and intuitive user experiences.
                </p>
                <p class="editorial-p">
                  Outside of tech, I love traveling, exploring new perspectives, and learning continuously to grow
                  personally and professionally. If you have an exciting project or want to collaborate, let's create
                  something awesome together!
                </p>
              </div>
            </div>

            <!-- Quick Info & Action Bar -->
            <div class="about-actions-bar">
              <div class="about-highlights-strip">
                <div class="about-hl-pill">
                  <strong>2+</strong>
                  <span>Years Coding</span>
                </div>
                <div class="about-hl-pill">
                  <strong>BCA</strong>
                  <span>Kantipur City College</span>
                </div>
                <div class="about-hl-pill">
                  <strong>Co-Founder</strong>
                  <span>Inovix Technologies</span>
                </div>
              </div>

              <div class="about-btns-row">
                <a href="#contact" class="btn btn-primary">
                  <span>Let's Talk</span>
                  <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
                </a>
                <a href="pdf/AWS1.pdf" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">
                  <i class="bx bx-file" aria-hidden="true"></i>
                  <span>Download CV</span>
                </a>
              </div>
            </div>

          </div>

        </div>

        <!-- Academic Career Milestones: Horizontal Timeline Layout -->
        <div class="about-education-stages">
          <div class="edu-stages-header">
            <div class="edu-stages-heading-wrap">
              <span class="edu-stages-icon"><i class="bx bxs-graduation" aria-hidden="true"></i></span>
              <div>
                <h3 class="edu-stages-title">Academic Milestones</h3>
                <span class="edu-stages-sub">3 Educational Stages &bull; Foundational to Degree</span>
              </div>
            </div>
            <span class="edu-stages-tag">Education Timeline</span>
          </div>

          <!-- Timeline Track & Cards -->
          <div class="edu-timeline-wrap">
            <div class="edu-timeline-grid">

              <!-- Step 01: BCA (Active) -->
              <div class="edu-timeline-col stage-active">
                <div class="timeline-node-wrap">
                  <div class="timeline-circle" aria-label="Step 01">01</div>
                </div>
                <div class="edu-stage-card">
                  <div class="stage-card-top">
                    <span class="stage-badge badge-current"><i class="bx bx-radio-circle-marked"></i> Active</span>
                    <span class="stage-time">2024 &ndash; Present</span>
                  </div>
                  <h4 class="stage-title">BCA (Bachelor of Computer Applications)</h4>
                  <span class="stage-school">Kantipur City College &bull; Purbanchal University</span>
                  <p class="stage-desc">
                    Specializing in software engineering, database architectures (PostgreSQL/MySQL), data structures &amp; Python backend development.
                  </p>
                </div>
              </div>

              <!-- Step 02: +2 Management -->
              <div class="edu-timeline-col">
                <div class="timeline-node-wrap">
                  <div class="timeline-circle" aria-label="Step 02">02</div>
                </div>
                <div class="edu-stage-card">
                  <div class="stage-card-top">
                    <span class="stage-badge"><i class="bx bx-check-circle"></i> Completed</span>
                    <span class="stage-time">Higher Secondary</span>
                  </div>
                  <h4 class="stage-title">+2 Management</h4>
                  <span class="stage-school">National Examinations Board (NEB), Nepal</span>
                  <p class="stage-desc">
                    Gained solid foundational grounding in business administration, accounting principles, and quantitative analysis.
                  </p>
                </div>
              </div>

              <!-- Step 03: SEE -->
              <div class="edu-timeline-col">
                <div class="timeline-node-wrap">
                  <div class="timeline-circle" aria-label="Step 03">03</div>
                </div>
                <div class="edu-stage-card">
                  <div class="stage-card-top">
                    <span class="stage-badge"><i class="bx bx-check-circle"></i> Completed</span>
                    <span class="stage-time">Secondary School</span>
                  </div>
                  <h4 class="stage-title">Secondary Education Examination (SEE)</h4>
                  <span class="stage-school">National Examination Board (NEB), Nepal</span>
                  <p class="stage-desc">
                    Completed secondary schooling with distinction in mathematics, science, and computer studies fundamentals.
                  </p>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- ── Professional Experience Section (Serpentine Snake Timeline UI) ── -->
    <section class="experience-section" id="experience">
      <div class="container">

        <div class="serpentine-header">
          <h2 class="serpentine-title">Experiences</h2>
        </div>

        <!-- Serpentine S-Curve Timeline Structure (First 2 Highlights on Home) -->
        <div class="serpentine-timeline">
          <?php 
            $home_experiences = array_slice($experiences ?? [], 0, 2);
            if (!empty($home_experiences)): 
              foreach ($home_experiences as $index => $exp):
                $isEven = ($index % 2 !== 0);
                $stepNum = $index + 1;
          ?>
            <div class="serpentine-block <?= $isEven ? 'block-right' : 'block-left' ?>">
              
              <!-- Numbered Circle Node (1 on Left, 2 on Right) -->
              <div class="serpentine-node"><?= $stepNum ?></div>

              <!-- Main Content (Exact typography matching reference image) -->
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
              </div>

              <!-- Connecting Line between Item 1 and Item 2 -->
              <?php if (!$isEven && isset($home_experiences[$index + 1])): ?>
                <div class="serpentine-turn turn-left-to-right" aria-hidden="true">
                  <div class="turn-corner-left"></div>
                  <div class="turn-bar"></div>
                  <div class="turn-corner-right"></div>
                </div>
              <?php endif; ?>

            </div>
          <?php 
              endforeach;
            endif; 
          ?>
        </div>

        <!-- View All Career History & Awards CTA -->
        <div class="exp-timeline-action">
          <a href="/experience" class="btn-timeline-view-all">
            <span>View All Career History &amp; Awards</span>
            <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
          </a>
        </div>

      </div>
    </section>

    <!-- ── Skills Section (Categorized Technical Stack) ── -->
    <section class="skills-section" id="skills">
      <div class="container">

        <div class="section-heading-block">
          <span class="section-tag">Technical Stack</span>
          <h2 class="section-main-title">
            Technologies &amp; <span class="gradient-text">Tools</span>
          </h2>
          <p class="section-subtitle">
            Core programming languages, web frameworks, databases, and developer infrastructure organized by specialization.
          </p>
        </div>

        <!-- Category Filter Tabs -->
        <div class="skills-filter-nav" role="tablist" aria-label="Skills Category Filter">
          <button type="button" class="skill-tab-btn active" data-filter="all" role="tab" aria-selected="true">
            <i class="bx bx-grid-alt" aria-hidden="true"></i>
            <span>All Skills</span>
          </button>
          <button type="button" class="skill-tab-btn" data-filter="backend" role="tab" aria-selected="false">
            <i class="bx bx-server" aria-hidden="true"></i>
            <span>Backend</span>
          </button>
          <button type="button" class="skill-tab-btn" data-filter="frontend" role="tab" aria-selected="false">
            <i class="bx bx-layout" aria-hidden="true"></i>
            <span>Frontend</span>
          </button>
          <button type="button" class="skill-tab-btn" data-filter="database" role="tab" aria-selected="false">
            <i class="bx bx-data" aria-hidden="true"></i>
            <span>Databases</span>
          </button>
          <button type="button" class="skill-tab-btn" data-filter="devops" role="tab" aria-selected="false">
            <i class="bx bx-cloud" aria-hidden="true"></i>
            <span>DevOps &amp; Cloud</span>
          </button>
        </div>

        <!-- Categorized Skills Grid -->
        <div class="skills-categories-grid">

          <!-- 1. Backend Engineering -->
          <div class="skill-category-card" data-category="backend">
            <div class="skill-category-header">
              <div class="skill-cat-title-wrap">
                <span class="skill-cat-icon"><i class="bx bx-server" aria-hidden="true"></i></span>
                <div>
                  <h3 class="skill-cat-title">Backend Architecture</h3>
                  <p class="skill-cat-desc">Server-side logic, scalable APIs &amp; systems</p>
                </div>
              </div>
            </div>
            <div class="skill-tiles-row">
              <div class="skill-tile-item" title="Python">
                <div class="skill-logo-tile tile-python"><i class="bx bxl-python" aria-hidden="true"></i></div>
                <span class="skill-tile-name">Python</span>
              </div>
              <div class="skill-tile-item" title="Django">
                <div class="skill-logo-tile tile-django"><i class="bx bxl-django" aria-hidden="true"></i></div>
                <span class="skill-tile-name">Django</span>
              </div>
              <div class="skill-tile-item" title="RESTful APIs & Microservices">
                <div class="skill-logo-tile tile-cpp"><i class="bx bx-code-curly" aria-hidden="true"></i></div>
                <span class="skill-tile-name">REST APIs</span>
              </div>
              <div class="skill-tile-item" title="C++">
                <div class="skill-logo-tile tile-cpp"><i class="bx bxl-c-plus-plus" aria-hidden="true"></i></div>
                <span class="skill-tile-name">C++</span>
              </div>
            </div>
          </div>

          <!-- 2. Frontend Development -->
          <div class="skill-category-card" data-category="frontend">
            <div class="skill-category-header">
              <div class="skill-cat-title-wrap">
                <span class="skill-cat-icon"><i class="bx bx-layout" aria-hidden="true"></i></span>
                <div>
                  <h3 class="skill-cat-title">Frontend Engineering</h3>
                  <p class="skill-cat-desc">Responsive user interfaces &amp; interaction</p>
                </div>
              </div>
            </div>
            <div class="skill-tiles-row">
              <div class="skill-tile-item" title="JavaScript">
                <div class="skill-logo-tile tile-js"><i class="bx bxl-javascript" aria-hidden="true"></i></div>
                <span class="skill-tile-name">JavaScript</span>
              </div>
              <div class="skill-tile-item" title="HTML5">
                <div class="skill-logo-tile tile-html"><i class="bx bxl-html5" aria-hidden="true"></i></div>
                <span class="skill-tile-name">HTML5</span>
              </div>
              <div class="skill-tile-item" title="CSS3">
                <div class="skill-logo-tile tile-css"><i class="bx bxl-css3" aria-hidden="true"></i></div>
                <span class="skill-tile-name">CSS3</span>
              </div>
            </div>
          </div>

          <!-- 3. Database Systems -->
          <div class="skill-category-card" data-category="database">
            <div class="skill-category-header">
              <div class="skill-cat-title-wrap">
                <span class="skill-cat-icon"><i class="bx bx-data" aria-hidden="true"></i></span>
                <div>
                  <h3 class="skill-cat-title">Databases &amp; Storage</h3>
                  <p class="skill-cat-desc">Relational schemas, queries &amp; persistence</p>
                </div>
              </div>
            </div>
            <div class="skill-tiles-row">
              <div class="skill-tile-item" title="PostgreSQL">
                <div class="skill-logo-tile tile-postgres"><i class="bx bx-data" aria-hidden="true"></i></div>
                <span class="skill-tile-name">PostgreSQL</span>
              </div>
              <div class="skill-tile-item" title="MySQL">
                <div class="skill-logo-tile tile-mysql"><i class="bx bxs-data" aria-hidden="true"></i></div>
                <span class="skill-tile-name">MySQL</span>
              </div>
              <div class="skill-tile-item" title="SQLite">
                <div class="skill-logo-tile tile-sqlite"><i class="bx bx-cylinder" aria-hidden="true"></i></div>
                <span class="skill-tile-name">SQLite</span>
              </div>
            </div>
          </div>

          <!-- 4. DevOps & Cloud -->
          <div class="skill-category-card" data-category="devops">
            <div class="skill-category-header">
              <div class="skill-cat-title-wrap">
                <span class="skill-cat-icon"><i class="bx bx-cloud" aria-hidden="true"></i></span>
                <div>
                  <h3 class="skill-cat-title">DevOps &amp; Infrastructure</h3>
                  <p class="skill-cat-desc">Containerization, cloud services &amp; Linux</p>
                </div>
              </div>
            </div>
            <div class="skill-tiles-row">
              <div class="skill-tile-item" title="Docker">
                <div class="skill-logo-tile tile-docker"><i class="bx bxl-docker" aria-hidden="true"></i></div>
                <span class="skill-tile-name">Docker</span>
              </div>
              <div class="skill-tile-item" title="AWS">
                <div class="skill-logo-tile tile-aws"><i class="bx bxl-aws" aria-hidden="true"></i></div>
                <span class="skill-tile-name">AWS</span>
              </div>
              <div class="skill-tile-item" title="Linux">
                <div class="skill-logo-tile tile-linux"><i class="bx bxl-tux" aria-hidden="true"></i></div>
                <span class="skill-tile-name">Linux</span>
              </div>
              <div class="skill-tile-item" title="Git">
                <div class="skill-logo-tile tile-git"><i class="bx bxl-git" aria-hidden="true"></i></div>
                <span class="skill-tile-name">Git</span>
              </div>
              <div class="skill-tile-item" title="GitHub">
                <div class="skill-logo-tile tile-github"><i class="bx bxl-github" aria-hidden="true"></i></div>
                <span class="skill-tile-name">GitHub</span>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section>


    <!-- ── Services Section ── -->
    <section class="services-section" id="services">
      <div class="container">

        <div class="section-heading-block">
          <span class="section-tag">What I Offer</span>
          <h2 class="section-main-title">
            Specialized Engineering <span class="gradient-text">Services</span>
          </h2>
          <p class="section-subtitle">
            Delivering robust backend architectures, resilient database solutions, and end-to-end digital systems
            tailored to modern operational needs.
          </p>
        </div>

        <div class="services-cards-grid">

          <!-- Service 1: Backend & RESTful API Engineering -->
          <article class="service-card service-blue">
            <div class="service-icon-wrap">
              <i class="bx bx-server" aria-hidden="true"></i>
            </div>
            <div class="service-body">
              <h3 class="service-title">Backend &amp; API Development</h3>
              <p class="service-desc">
                Architecting scalable server-side systems and production REST APIs with Python and Django. Focused on
                high-throughput endpoints, secure JWT/OAuth authentication, and resilient data processing.
              </p>
              <div class="service-pills">
                <span>Python &amp; Django</span>
                <span>REST APIs</span>
                <span>JWT Auth</span>
                <span>Scalable Logic</span>
              </div>
            </div>
          </article>

          <!-- Service 2: Database Architecture & Optimization -->
          <article class="service-card service-indigo">
            <div class="service-icon-wrap">
              <i class="bx bx-data" aria-hidden="true"></i>
            </div>
            <div class="service-body">
              <h3 class="service-title">Database Design &amp; Optimization</h3>
              <p class="service-desc">
                Structuring normalized relational schemas, ACID-compliant transactions, and optimized database queries
                across PostgreSQL, MySQL, and SQLite for high-load consistency and data reliability.
              </p>
              <div class="service-pills">
                <span>PostgreSQL</span>
                <span>MySQL</span>
                <span>Schema Modeling</span>
                <span>Query Tuning</span>
              </div>
            </div>
          </article>

          <!-- Service 3: Healthcare & Practice Management Systems -->
          <article class="service-card service-cyan">
            <div class="service-icon-wrap">
              <i class="bx bx-pulse" aria-hidden="true"></i>
            </div>
            <div class="service-body">
              <h3 class="service-title">Clinical Practice Management Systems</h3>
              <p class="service-desc">
                Developing custom clinical practice operating systems (e.g., Oralix, CareFirst Dental) featuring
                real-time operatory chair scheduling, patient electronic health records (EHR), and automated billing
                workflows.
              </p>
              <div class="service-pills">
                <span>Practice OS</span>
                <span>Operatory Schedulers</span>
                <span>EHR Systems</span>
                <span>Billing Invoices</span>
              </div>
            </div>
          </article>

          <!-- Service 4: Full-Stack Web Development -->
          <article class="service-card service-purple">
            <div class="service-icon-wrap">
              <i class="bx bx-laptop" aria-hidden="true"></i>
            </div>
            <div class="service-body">
              <h3 class="service-title">Full-Stack Web Development</h3>
              <p class="service-desc">
                Building end-to-end web applications with modern, accessible user interfaces and solid backend logic.
                Bridging responsive HTML5/CSS3/JavaScript frontends seamlessly with Django backends.
              </p>
              <div class="service-pills">
                <span>Full-Stack Solutions</span>
                <span>Modern JavaScript</span>
                <span>Responsive UI</span>
                <span>Template Logic</span>
              </div>
            </div>
          </article>

          <!-- Service 5: Cloud Infrastructure & DevOps -->
          <article class="service-card service-amber">
            <div class="service-icon-wrap">
              <i class="bx bx-cloud" aria-hidden="true"></i>
            </div>
            <div class="service-body">
              <h3 class="service-title">Cloud Deployment &amp; DevOps</h3>
              <p class="service-desc">
                Deploying and maintaining production workloads using containerized Docker environments, AWS cloud
                infrastructure (EC2, S3), Linux system configuration, and automated backup routines.
              </p>
              <div class="service-pills">
                <span>AWS Cloud</span>
                <span>Docker Containers</span>
                <span>Linux Server</span>
                <span>Deployment CI/CD</span>
              </div>
            </div>
          </article>

          <!-- Service 6: Custom Automation & Scripting -->
          <article class="service-card service-emerald">
            <div class="service-icon-wrap">
              <i class="bx bx-cog" aria-hidden="true"></i>
            </div>
            <div class="service-body">
              <h3 class="service-title">Custom Automation &amp; Scripting</h3>
              <p class="service-desc">
                Creating specialized Python automation scripts, web scrapers, and data pipelines to eliminate repetitive
                manual tasks, automate institutional workflows, and parse large structured datasets.
              </p>
              <div class="service-pills">
                <span>Python Scripting</span>
                <span>Workflow Automation</span>
                <span>Data Pipelines</span>
                <span>Web Scraping</span>
              </div>
            </div>
          </article>

        </div>

      </div>
    </section>



    <!-- ── Why Work With Me Section (Clean Minimalist Cards matching Reference UI) ── -->
    <section class="why-work-section" id="why-work">
      <div class="container">

        <!-- Section Heading Block -->
        <div class="section-heading-block text-center">
          <span class="section-tag">Value &amp; Approach</span>
          <h2 class="section-main-title">
            Why Work <span class="gradient-text">With Me</span>
          </h2>
          <p class="section-subtitle">
            Core engineering principles, problem-solving discipline, and product-focused delivery powering dependable software systems.
          </p>
        </div>

        <!-- 4 Minimalist Value Proposition Cards (No Icons, Clean Title + Description) -->
        <div class="why-cards-grid">

          <!-- Card 1: Practical Development -->
          <article class="why-simple-card">
            <h3 class="why-card-title">Practical Development</h3>
            <p class="why-card-desc">
              Focusing on maintainable Django &amp; Python backend architectures, normalized relational database schemas, and deterministic code over trendy over-engineering.
            </p>
          </article>

          <!-- Card 2: Problem-Solving Mindset -->
          <article class="why-simple-card">
            <h3 class="why-card-title">Problem-Solving Mindset</h3>
            <p class="why-card-desc">
              Deconstructing complex organizational bottlenecks into structured, deterministic components with systematic profiling and database query optimization.
            </p>
          </article>

          <!-- Card 3: Product-Oriented Thinking (Tinted accent card matching reference UI) -->
          <article class="why-simple-card card-accent-tint">
            <h3 class="why-card-title">Product-Oriented Thinking</h3>
            <p class="why-card-desc">
              Bridging business objectives and technical execution. Designing with deep empathy for end-users, transactional accuracy, and lasting real-world utility.
            </p>
          </article>

          <!-- Card 4: Continuous Learning -->
          <article class="why-simple-card">
            <h3 class="why-card-title">Continuous Learning</h3>
            <p class="why-card-desc">
              Actively expanding expertise across modern C++ systems programming, container orchestration, distributed caching, and cloud security practices.
            </p>
          </article>

        </div>

      </div>
    </section>



    <!-- ── Featured Projects Section (All Projects Displayed on Home Page) ── -->
    <section class="projects-section" id="projects">
      <div class="container">

        <!-- Section Heading -->
        <div class="section-heading-block text-center">
          <span class="section-tag">Portfolio</span>
          <h2 class="section-main-title">
            Featured <span class="gradient-text">Projects</span>
          </h2>
          <p class="section-subtitle">
            A comprehensive showcase of production clinical operating platforms, scalable Django backend architectures, relational database schemas, and open-source software engineering.
          </p>
        </div>

        <!-- Projects Grid -->
        <div class="home-projects-grid">
          <?php 
          $displayProjects = array_slice($featured_projects, 0, 6);
          foreach ($displayProjects as $p): 
            $shortDesc = !empty($p['short_description']) ? $p['short_description'] : $p['description'];
            $slug = !empty($p['slug']) ? $p['slug'] : \App\Models\FeaturedProject::slugify($p['title']);
            $detailUrl = "/works/" . urlencode($slug);
          ?>
          <article class="home-project-card">
            <a href="<?= $detailUrl ?>" class="home-project-image-link" aria-label="<?= htmlspecialchars($p['title']) ?>">
              <div class="home-project-image">
                <img src="/<?= ltrim(htmlspecialchars($p['image']), '/') ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy" onerror="this.onerror=null; this.src='/images/projectcard1.jpg'" />
              </div>
            </a>

            <div class="home-project-body">
              <h3 class="home-project-title">
                <a href="<?= $detailUrl ?>" class="project-title-link">
                  <?= htmlspecialchars($p['title']) ?>
                </a>
              </h3>
              
              <p class="home-project-desc">
                <?= htmlspecialchars($shortDesc) ?>
              </p>

              <!-- Footer with Live Site link (no technology pills on card face) -->
              <div class="home-project-footer">
                <a href="<?= htmlspecialchars($p['live_url']) ?>" target="_blank" rel="noopener noreferrer" class="live-site-link">
                  <span>Live Site</span>
                  <i class="bx bx-link-external" aria-hidden="true"></i>
                </a>
              </div>
            </div>
          </article>
          <?php endforeach; ?>
        </div>

        <!-- View All Projects Button (Navigates to Full Projects Archive Page) -->
        <div class="projects-view-all-cta text-center">
          <a href="/projects" class="btn-projects-view-all">
            <span>View All Projects</span>
            <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
          </a>
        </div>

        <!-- ── Key Academic Projects (Card Showcase) ── -->
        <div class="academic-projects-block">
          <div class="academic-block-header">
            <span class="section-tag">University Coursework</span>
            <h3 class="academic-section-title">Key Academic <span class="gradient-text">Projects</span></h3>
            <p class="section-subtitle academic-subtitle">
              Engineering applications, algorithms, and systems developed during BCA coursework at Kantipur City College.
            </p>
          </div>

          <div class="home-projects-grid academic-projects-grid">
            <?php foreach ($academic_projects as $a): 
              $acadImage = !empty($a['image']) ? $a['image'] : 'images/projectcard1.jpg';
              $acadShortDesc = $a['description'];
              $acadDetailedDesc = !empty($a['detailed_description']) ? $a['detailed_description'] : $a['description'];
            ?>
            <article class="home-project-card academic-project-card"
                     tabindex="0"
                     role="button"
                     data-academic-modal-trigger
                     data-title="<?= htmlspecialchars($a['title'], ENT_QUOTES) ?>"
                     data-image="/<?= ltrim(htmlspecialchars($acadImage), '/') ?>"
                     data-short-desc="<?= htmlspecialchars($acadShortDesc, ENT_QUOTES) ?>"
                     data-detailed-desc="<?= htmlspecialchars($acadDetailedDesc, ENT_QUOTES) ?>"
                     data-skills="<?= htmlspecialchars($a['skills'], ENT_QUOTES) ?>"
                     aria-haspopup="dialog"
                     aria-label="View project details for <?= htmlspecialchars($a['title']) ?>">
              
              <div class="home-project-image">
                <img src="/<?= ltrim(htmlspecialchars($acadImage), '/') ?>" 
                     alt="<?= htmlspecialchars($a['title']) ?>" 
                     loading="lazy" 
                     onerror="this.onerror=null; this.src='/images/projectcard1.jpg'" />
                <span class="academic-card-badge">BCA Coursework</span>
              </div>

              <div class="home-project-body">
                <h3 class="home-project-title">
                  <span class="project-title-link">
                    <?= htmlspecialchars($a['title']) ?>
                  </span>
                </h3>
                
                <p class="home-project-desc">
                  <?= htmlspecialchars($acadShortDesc) ?>
                </p>

                <div class="home-project-footer">
                  <span class="academic-view-btn">
                    <span>View Project Details</span>
                    <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
                  </span>
                </div>
              </div>
            </article>
            <?php endforeach; ?>
          </div>

          <!-- Bottom View All Projects & Archives CTA -->
          <div class="projects-view-all-cta text-center" style="margin-top: 3.5rem;">
            <a href="/projects" class="btn-projects-view-all">
              <span>View All Projects &amp; Archives</span>
              <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
            </a>
          </div>
        </div>

        <!-- ── Academic Project Details Modal ── -->
        <div class="academic-modal" id="academicDetailModal" aria-hidden="true" role="dialog" aria-labelledby="academicModalTitle">
          <div class="academic-modal-backdrop" data-close-academic-modal></div>
          <div class="academic-modal-dialog">
            <button type="button" class="academic-modal-close" data-close-academic-modal aria-label="Close modal">
              <i class="bx bx-x" aria-hidden="true"></i>
            </button>
            <div class="academic-modal-media">
              <img id="academicModalImg" src="/images/projectcard1.jpg" alt="Academic Project Preview" />
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
      </div>
    </section>


    <!-- ── Insights & Blog Section (Dynamic from PHP) ── -->
    <section class="insights-section" id="insights">
      <div class="container">

        <!-- Section Heading -->
        <div class="section-heading-block text-center">
          <span class="section-tag">Blog &amp; Knowledge</span>
          <h2 class="section-main-title">
            Engineering <span class="gradient-text">Insights</span>
          </h2>
          <p class="section-subtitle">
            Technical write-ups, backend architecture case studies, and engineering notes on systems design and relational databases.
          </p>
        </div>

        <!-- Insights Grid (Image & Title Cards) -->
        <div class="insights-cards-grid">
          <?php foreach ($blog_insights as $post): ?>
          <a href="/insights/<?= urlencode($post['slug']) ?>" class="blog-post-card" title="<?= htmlspecialchars($post['title']) ?>">
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

        <div style="text-align: center; margin-top: 2.5rem;">
          <a href="/insights" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.6rem; border-radius: 9999px; font-size: 0.88rem; font-weight: 600; text-decoration: none; border: 1px solid #cbd5e1; color: #0f172a; background: #fff; transition: all 0.2s;">
            <span>Explore All Insights</span>
            <i class="bx bx-right-arrow-alt"></i>
          </a>
        </div>

      </div>
    </section>

    <!-- Include Font Awesome for icons (add this in your <head> if not already present) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <section class="contact" id="contact">
      <div class="container">

        <header class="section-header reveal">
          <span class="section-label">Get In Touch</span>
          <h2>Contact <span>Me</span></h2>
          <p>Open for full-time, freelance &amp; project inquiries</p>
        </header>

        <div class="contact-content">

          <!-- Form -->
          <form class="contact-form reveal" id="contactForm" novalidate>
            <div class="form-row">
              <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" placeholder="Ganga Sagar" required autocomplete="name" />
              </div>
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required
                  autocomplete="email" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="Project Inquiry" required />
              </div>
              <div class="form-group">
                <label for="phone">Phone <span
                    style="font-weight:400;text-transform:none;opacity:.7">(optional)</span></label>
                <input type="tel" id="phone" name="phone" placeholder="+977 98XXXXXXXX" autocomplete="tel" />
              </div>
            </div>
            <div class="form-group">
              <label for="message">Your Message</label>
              <textarea id="message" name="message" placeholder="Hi Ganga, I'd like to discuss…" rows="6"
                required></textarea>
            </div>
            <button type="submit" class="btn btn-primary send-btn">
              <i class="bx bx-send" aria-hidden="true"></i>
              <span>Send Message</span>
            </button>
          </form>

          <!-- Contact Info -->
          <div class="contact-info reveal reveal-delay">

            <div class="info-item">
              <div class="info-icon"><i class="bx bx-envelope" aria-hidden="true"></i></div>
              <div class="info-text">
                <h3>Email</h3>
                <a href="mailto:gangasaagar62@gmail.com">gangasaagar62@gmail.com</a>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon"><i class="bx bx-phone" aria-hidden="true"></i></div>
              <div class="info-text">
                <h3>Phone</h3>
                <a href="tel:+9779746332963">+977 9746332963</a>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon"><i class="bx bx-map" aria-hidden="true"></i></div>
              <div class="info-text">
                <h3>Location</h3>
                <p>Kathmandu, Nepal</p>
              </div>
            </div>

            <div class="contact-social">
              <a href="https://github.com/gangasaagar" aria-label="GitHub" target="_blank" rel="noopener noreferrer"><i
                  class="bx bxl-github" aria-hidden="true"></i></a>
              <a href="https://www.linkedin.com/in/ganga-sagar15" aria-label="LinkedIn" target="_blank"
                rel="noopener noreferrer"><i class="bx bxl-linkedin" aria-hidden="true"></i></a>

            </div>

          </div>
        </div>
      </div>
    </section>

  </main>



  <!-- ── Shared Portfolio Footer ── -->
  <?php \App\Core\View::partial('footer'); ?>


  <!-- ─── Scripts ─────────────────────────────────────────────── -->
  <!-- Typed.js -->
  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12/lib/typed.min.js"></script>
  <!-- EmailJS -->
  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
  <!-- Your app JS -->
  <script src="/js/app.js"></script>

</body>

</html>