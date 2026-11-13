<?php
require_once __DIR__ . '/backend/functions.php';
$featured_projects = get_featured_projects();
$academic_projects = get_academic_projects();
$blog_insights = get_blog_insights();
?>
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
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>

  <!-- Scroll progress indicator -->
  <div class="scroll-progress" id="scrollProgress"></div>

  <header class="header" id="header">
    <div class="header-container">
      <!-- Left: Ganga Sagar Joshi personal branding or GSJ monogram -->
      <a class="brand" href="#home" aria-label="Ganga Sagar Joshi Home">
        <div class="brand-monogram">
          <span class="monogram-text">GSJ</span>
        </div>
        <div class="brand-info">
          <span class="brand-name">Ganga Sagar Joshi</span>
          <span class="brand-title">Software &amp; Systems</span>
        </div>
      </a>

      <!-- Center/Right: Navigation Links -->
      <nav class="navbar" id="desktopNav" aria-label="Primary navigation">
        <a href="#home" class="nav-link active">Home</a>
        <a href="#about" class="nav-link">About</a>
        <a href="#skills" class="nav-link">Skills</a>
        <a href="#services" class="nav-link">Services</a>
        <a href="#projects" class="nav-link">Projects</a>
        <a href="profile.html" class="nav-link">Profile</a>
        <a href="insights.html" class="nav-link">Insights</a>
        <a href="#contact" class="nav-link">Contact</a>
      </nav>

      <!-- Right: Highlighted Let's Talk button & Mobile Hamburger -->
      <div class="nav-actions">
        <a href="#contact" class="btn-talk" aria-label="Let's Talk">
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
      <a href="#home" class="drawer-link active"><i class="bx bx-home-alt-2"></i><span>Home</span></a>
      <a href="#about" class="drawer-link"><i class="bx bx-user"></i><span>About</span></a>
      <a href="#skills" class="drawer-link"><i class="bx bx-layer"></i><span>Skills</span></a>
      <a href="#services" class="drawer-link"><i class="bx bx-wrench"></i><span>Services</span></a>
      <a href="#projects" class="drawer-link"><i class="bx bx-folder"></i><span>Projects</span></a>
      <a href="profile.html" class="drawer-link"><i class="bx bx-user-pin"></i><span>Profile</span></a>
      <a href="insights.html" class="drawer-link"><i class="bx bx-bulb"></i><span>Insights</span></a>
      <a href="#contact" class="drawer-link"><i class="bx bx-envelope"></i><span>Contact</span></a>
    </nav>

    <div class="drawer-footer">
      <a href="#contact" class="drawer-cta drawer-link">
        <span>Let's Talk</span>
        <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
      </a>
      <div class="drawer-socials">
        <a href="https://github.com/gangasaagar" aria-label="GitHub" target="_blank" rel="noopener noreferrer"><i
            class="bx bxl-github"></i></a>
        <a href="https://www.linkedin.com/in/ganga-sagar15" aria-label="LinkedIn" target="_blank"
          rel="noopener noreferrer"><i class="bx bxl-linkedin"></i></a>
        <a href="https://www.instagram.com/ganga_sagar_joshi/" aria-label="Instagram" target="_blank"
          rel="noopener noreferrer"><i class="bx bxl-instagram"></i></a>
        <a href="https://www.facebook.com/ganga.sagar.joshi.2025/directory_links" aria-label="Facebook" target="_blank"
          rel="noopener noreferrer"><i class="bx bxl-facebook"></i></a>
      </div>
    </div>
  </div>
  <!-- Dimmed backdrop when drawer is open -->
  <div class="drawer-backdrop" id="drawerBackdrop"></div>


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

        <!-- ── Right: Professional Photograph & Visual Composition ── -->
        <div class="hero-visual">
          <div class="visual-wrapper">
            <!-- Decorative aura & shapes -->
            <div class="visual-aura aura-blue" aria-hidden="true"></div>
            <div class="visual-aura aura-purple" aria-hidden="true"></div>
            <div class="visual-pattern-dots" aria-hidden="true"></div>

            <!-- Photograph card -->
            <div class="photo-container">
              <div class="photo-frame">
                <img src="images/profile.jpeg" alt="Ganga Sagar Joshi - Professional Portrait" class="hero-photo"
                  width="460" height="580" loading="eager" />
                <div class="photo-gradient-overlay" aria-hidden="true"></div>
              </div>
              <div class="frame-border-glow" aria-hidden="true"></div>
            </div>

            <!-- Floating technology badges -->
            <div class="floating-badge badge-python" aria-hidden="true">
              <div class="badge-icon python-theme">
                <i class="bx bxl-python"></i>
              </div>
              <div class="badge-content">
                <span class="badge-title">Python &amp; Django</span>
                <span class="badge-subtitle">Backend Engineering</span>
              </div>
            </div>

            <div class="floating-badge badge-aws" aria-hidden="true">
              <div class="badge-icon aws-theme">
                <i class="bx bxl-aws"></i>
              </div>
              <div class="badge-content">
                <span class="badge-title">AWS &amp; Cloud</span>
                <span class="badge-subtitle">DevOps &amp; Infra</span>
              </div>
            </div>

            <div class="floating-badge badge-api" aria-hidden="true">
              <div class="badge-icon api-theme">
                <i class="bx bx-server"></i>
              </div>
              <div class="badge-content">
                <span class="badge-title">Scalable APIs</span>
                <span class="badge-subtitle">REST &amp; PostgreSQL</span>
              </div>
            </div>

            <!-- Floating status tag -->
            <div class="floating-pill-status" aria-hidden="true">
              <span class="pill-icon">🚀</span>
              <span class="pill-text">High-Impact Solutions</span>
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
              <img src="images/gangasagar_transparent.png" alt="Ganga Sagar Joshi" class="about-transparent-portrait"
                width="340" height="453" loading="lazy" />
            </div>

            <!-- Original Transparent Signature (No Hover Effect) -->
            <div class="about-signature-block">
              <img src="images/signature.png" alt="Ganga Sagar Joshi Signature" class="about-signature-img" width="220"
                height="94" loading="lazy" />
              <span class="signature-caption">Ganga Sagar Joshi</span>
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


    <!-- ── Skills Section (Icons / Logos Only) ── -->
    <section class="skills-section" id="skills">
      <div class="container">

        <div class="section-heading-block">
          <span class="section-tag">Technical Stack</span>
          <h2 class="section-main-title">
            Technologies &amp; <span class="gradient-text">Tools</span>
          </h2>
          <p class="section-subtitle">
            Core programming languages, web frameworks, databases, and developer infrastructure powering my software
            engineering.
          </p>
        </div>

        <!-- Pure Icon / Logo Grid (No Keywords / Descriptions) -->
        <div class="skills-icons-wrap">
          <div class="skill-logo-tile tile-python" title="Python" aria-label="Python">
            <i class="bx bxl-python" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-django" title="Django" aria-label="Django">
            <i class="bx bxl-django" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-js" title="JavaScript" aria-label="JavaScript">
            <i class="bx bxl-javascript" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-cpp" title="C++" aria-label="C++">
            <i class="bx bx-code-curly" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-postgres" title="PostgreSQL" aria-label="PostgreSQL">
            <i class="bx bx-data" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-mysql" title="MySQL" aria-label="MySQL">
            <i class="bx bxs-data" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-sqlite" title="SQLite" aria-label="SQLite">
            <i class="bx bx-cylinder" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-docker" title="Docker" aria-label="Docker">
            <i class="bx bxl-docker" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-aws" title="Amazon Web Services (AWS)" aria-label="Amazon Web Services">
            <i class="bx bxl-aws" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-git" title="Git" aria-label="Git">
            <i class="bx bxl-git" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-github" title="GitHub" aria-label="GitHub">
            <i class="bx bxl-github" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-linux" title="Linux" aria-label="Linux">
            <i class="bx bxl-tux" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-html" title="HTML5" aria-label="HTML5">
            <i class="bx bxl-html5" aria-hidden="true"></i>
          </div>

          <div class="skill-logo-tile tile-css" title="CSS3" aria-label="CSS3">
            <i class="bx bxl-css3" aria-hidden="true"></i>
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
          <?php foreach ($featured_projects as $p): ?>
          <article class="home-project-card">
            <div class="home-project-image">
              <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy" />
            </div>
            <div class="home-project-body">
              <h3 class="home-project-title"><?= htmlspecialchars($p['title']) ?></h3>
              <p class="home-project-desc">
                <?= htmlspecialchars($p['description']) ?>
              </p>
              <div class="project-skills-pills">
                <?php foreach ($p['skills_array'] as $sk): ?>
                <span><?= htmlspecialchars($sk) ?></span>
                <?php endforeach; ?>
              </div>
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

        <!-- ── Key Academic Project (Exact Structure & Content from Reference) ── -->
        <div class="academic-projects-block">
          <h3 class="academic-section-title">Key Academic Project</h3>

          <div class="academic-items-grid">
            <?php foreach ($academic_projects as $a): ?>
            <div class="academic-project-item">
              <div class="academic-item-header">
                <i class="bx bx-git-repo-forked" aria-hidden="true"></i>
                <h4 class="academic-item-title"><?= htmlspecialchars($a['title']) ?></h4>
              </div>
              <p class="academic-item-desc">
                <?= htmlspecialchars($a['description']) ?>
              </p>
              <div class="academic-skills-pills">
                <?php foreach ($a['skills_array'] as $ask): ?>
                <span><?= htmlspecialchars($ask) ?></span>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endforeach; ?>
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

        <!-- Insights Grid -->
        <div class="insights-grid">
          <?php foreach ($blog_insights as $post): ?>
          <article class="insight-card" onclick="window.location.href='insight-detail.html?slug=<?= urlencode($post['slug']) ?>'" style="cursor: pointer;">
            <div class="insight-meta-row">
              <span class="insight-category-pill"><?= htmlspecialchars($post['category']) ?></span>
              <span class="insight-date-info">
                <i class="bx bx-time-five" aria-hidden="true"></i>
                <?= htmlspecialchars($post['read_time']) ?> &bull; <?= htmlspecialchars($post['date']) ?>
              </span>
            </div>
            <h3 class="insight-card-title"><?= htmlspecialchars($post['title']) ?></h3>
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



  <footer class="footer">
    <div class="container footer-inner">
      <span class="footer-logo">Ganga Sagar<span class="dot">.</span></span>
      <p>
        &copy; <span id="year"></span> Ganga Sagar Joshi.
        Crafted with <i class="bx bx-heart" aria-hidden="true"></i> in Nepal.
      </p>
      <a href="#home" class="back-to-top" aria-label="Back to top">
        <i class="bx bx-up-arrow-alt" aria-hidden="true"></i>
      </a>
    </div>
  </footer>


  <!-- ─── Scripts ─────────────────────────────────────────────── -->
  <!-- Typed.js -->
  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12/lib/typed.min.js"></script>
  <!-- EmailJS -->
  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
  <!-- Your app JS -->
  <script src="js/app.js"></script>

</body>

</html>