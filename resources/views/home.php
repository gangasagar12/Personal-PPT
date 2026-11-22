<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title>Ganga Sagar Joshi — Software Developer &amp; Django Backend Engineer</title>
  <meta name="google-site-verification" content="yC0FjFq6eXnCaYa6D_VLggwR7D7smVDaFGP6gQ3wn4w" />

  <?php
  \App\Core\View::partial('seo_meta', [
      'seo_title' => 'Ganga Sagar Joshi — Software Developer & Django Backend Engineer',
      'seo_desc' => 'Official portfolio of Ganga Sagar Joshi, Software Developer & Co-Founder at Strataflow Technologies specializing in Python, Django, scalable REST APIs, and full-stack software systems in Kathmandu, Nepal.',
      'seo_canonical' => 'https://gangasagar.com.np',
      'seo_image' => '/images/ganga-sagar-joshi-profile.jpeg',
      'seo_type' => 'website'
  ]);
  ?>

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
            I build backend systems, business applications, and scalable software solutions using  modern technologies.

As Co-Founder of Strataflow Technologies, I work on turning real-world business challenges into practical digital products.
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
              <a href="https://github.com/gangasagar12" aria-label="GitHub Profile" target="_blank"
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
              <span class="highlight-val">10+</span>
              <span class="highlight-txt">Project Completed</span>
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
            <span class="flow-title">Python</span>
          </div>
        </div>
        

        <div class="marquee-flow-item">
          <div class="flow-icon django-theme"><i class="bx bxl-django"></i></div>
          <div class="flow-text">
            <span class="flow-title">Django</span>
          </div>
        </div>
        

        <div class="marquee-flow-item">
          <div class="flow-icon api-theme"><i class="bx bx-server"></i></div>
          <div class="flow-text">
            <span class="flow-title">REST APIs</span>
          </div>
        </div>
      

        <div class="marquee-flow-item">
          <div class="flow-icon postgres-theme"><i class="bx bxl-postgresql"></i></div>
          <div class="flow-text">
            <span class="flow-title">PostgreSQL</span>
          </div>
        </div>
   

        <div class="marquee-flow-item">
          <div class="flow-icon db-theme"><i class="bx bx-data"></i></div>
          <div class="flow-text">
            <span class="flow-title">MySQL</span>
          </div>
        </div>
        

        <div class="marquee-flow-item">
          <div class="flow-icon docker-theme"><i class="bx bxl-docker"></i></div>
          <div class="flow-text">
            <span class="flow-title">Docker</span>
          </div>
        </div>
       

        <div class="marquee-flow-item">
          <div class="flow-icon linux-theme"><i class="bx bxl-tux"></i></div>
          <div class="flow-text">
            <span class="flow-title">Linux</span>
          </div>
        </div>
        

        <div class="marquee-flow-item">
          <div class="flow-icon saas-theme"><i class="bx bx-layer"></i></div>
          <div class="flow-text">
            <span class="flow-title">SaaS Development</span>
          </div>
        </div>
      

        <div class="marquee-flow-item">
          <div class="flow-icon git-theme"><i class="bx bxl-github"></i></div>
          <div class="flow-text">
            <span class="flow-title">Git &amp; GitHub</span>
          </div>
        </div>
        

        <div class="marquee-flow-item">
          <div class="flow-icon aws-theme"><i class="bx bxl-aws"></i></div>
          <div class="flow-text">
            <span class="flow-title">AWS Cloud</span>
          </div>
        </div>
       
        <div class="marquee-flow-item">
          <div class="flow-icon ml-theme"><i class="bx bx-brain"></i></div>
          <div class="flow-text">
            <span class="flow-title">Machine Learning</span>
          </div>
        </div>
    

        <!-- Sequence 2 (Duplicate for continuous seamless loop) -->
        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon python-theme"><i class="bx bxl-python"></i></div>
          <div class="flow-text">
            <span class="flow-title">Python</span>
          </div>
        </div>
       

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon django-theme"><i class="bx bxl-django"></i></div>
          <div class="flow-text">
            <span class="flow-title">Django</span>
          </div>
        </div>
       

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon api-theme"><i class="bx bx-server"></i></div>
          <div class="flow-text">
            <span class="flow-title">REST APIs</span>
          </div>
        </div>
       

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon postgres-theme"><i class="bx bxl-postgresql"></i></div>
          <div class="flow-text">
            <span class="flow-title">PostgreSQL</span>
          </div>
        </div>
      

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon db-theme"><i class="bx bx-data"></i></div>
          <div class="flow-text">
            <span class="flow-title">MySQL</span>
          </div>
        </div>
     

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon docker-theme"><i class="bx bxl-docker"></i></div>
          <div class="flow-text">
            <span class="flow-title">Docker</span>
          </div>
        </div>
     

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon linux-theme"><i class="bx bxl-tux"></i></div>
          <div class="flow-text">
            <span class="flow-title">Linux</span>
          </div>
        </div>
      

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon saas-theme"><i class="bx bx-layer"></i></div>
          <div class="flow-text">
            <span class="flow-title">SaaS Development</span>
          </div>
        </div>
       

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon git-theme"><i class="bx bxl-github"></i></div>
          <div class="flow-text">
            <span class="flow-title">Git &amp; GitHub</span>
          </div>
        </div>
       

        <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon aws-theme"><i class="bx bxl-aws"></i></div>
          <div class="flow-text">
            <span class="flow-title">AWS Cloud</span>
          </div>
        </div>
       <div class="marquee-flow-item" aria-hidden="true">
          <div class="flow-icon ml-theme"><i class="bx bx-brain"></i></div>
          <div class="flow-text">
            <span class="flow-title">Machine Learning</span>
          </div>
        </div>
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
            A glimpse into my background, passion for software development, and journey in building technology.
          </p>
        </div>

        <!-- Editorial Split Layout -->
        <div class="about-editorial-grid">

          
          <div class="about-visual-column">
            <div class="about-transparent-photo-wrap">
              <img src="/images/ganga-sagar-joshi.png" alt="Ganga Sagar Joshi — Software Developer & Tech Entrepreneur" class="about-transparent-portrait"
                width="340" height="403" loading="lazy" />
            </div>

            <!-- Original Transparent Signature (No Hover Effect) -->
            <div class="about-signature-block">
              <img src="/images/ganga-sagar-joshi-signature.png" alt="Ganga Sagar Joshi Signature" class="about-signature-img" width="220"
                height="94" loading="lazy" />
              <span class="signature-caption">Ganga Sagar Joshi</span>

              <div class="signature-contact-info">
                <span class="signature-contact-label">Contact Me</span>
                <a href="tel:+9779746332963" class="signature-contact-item">
                  <i class="bx bx-phone" aria-hidden="true"></i> +977 9746332963
                </a>
                <a href="mailto:info@gangasagar.com.np" class="signature-contact-item">
                  <i class="bx bx-envelope" aria-hidden="true"></i> info@gangasagar.com.np
                </a>
              </div>
            </div>
          </div>

          <!-- Right Column: Description & Story -->
          <div class="about-content-column">
            <div class="about-role-badge">
              <i class="bx bx-code-alt" aria-hidden="true"></i>
              <span>Software Developer &amp; Tech Entrepreneur &bull; Kathmandu, Nepal</span>
            </div>

            <!-- 2-Column Editorial Paragraphs matching the user's reference -->
            <div class="about-columns-wrap">
              <div class="about-text-col">
                <p class="editorial-p">
                  Hey! I'm Ganga Sagar Joshi, a Software Developer, Backend Engineer, and Technology Entrepreneur based in Kathmandu, Nepal. I'm currently pursuing a Bachelor of Computer Applications (BCA) at Kantipur City College, with a passion for building practical software solutions and exploring emerging technologies.
                </p>
                <p class="editorial-p">
                  I primarily work with Python, Django, REST APIs, and relational databases, focusing on backend development, application architecture, and scalable business solutions. I enjoy transforming complex requirements into reliable, maintainable, and user-friendly software.
                </p>
                <p class="editorial-p">
                  Beyond development, I'm a Co-Founder of Strataflow Technologies Pvt. Ltd., where I contribute to building technology-driven solutions for businesses and institutions. My work includes product development and healthcare software solutions, particularly dental practice management platforms.
                </p>
              </div>

              <div class="about-text-col">
                <p class="editorial-p">
                  I'm continuously exploring AI and Machine Learning, AWS, Docker, DevOps, and cloud technologies to expand my engineering capabilities. I believe great software is built through thoughtful architecture, continuous learning, and a genuine understanding of real-world problems.
                </p>
                <p class="editorial-p">
                  Outside technology, I enjoy traveling, discovering new perspectives, and learning new things. I'm always open to meaningful collaborations, innovative ideas, and opportunities to build technology that creates real value.
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
                  <span>Strataflow Technologies</span>
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
                <span class="edu-stages-sub"> Educational Stages</span>
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
                    Currently i am studying at Kantipur City College, pursuing a Bachelor of Computer Applications (BCA). 
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
                  <span class="stage-school">Ambition College</span>
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
            <span>View All Career  &amp; Awards</span>
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
            Programming languages, frameworks, databases, and tools I use to build modern software.
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

          <!-- 1. Backend Development -->
          <div class="skill-category-card" data-category="backend">
            <div class="skill-category-header">
              <div class="skill-cat-title-wrap">
                <span class="skill-cat-icon"><i class="bx bx-server" aria-hidden="true"></i></span>
                <div>
                  <h3 class="skill-cat-title">Backend Development</h3>
                  <p class="skill-cat-desc">Server-side logic, REST APIs &amp; databases</p>
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
              <div class="skill-tile-item" title="PHP">
                <div class="skill-logo-tile tile-php"><i class="bx bxl-php" aria-hidden="true"></i></div>
                <span class="skill-tile-name">PHP</span>
              </div>
              <div class="skill-tile-item" title=".NET">
                <div class="skill-logo-tile tile-dotnet"><span class="dotnet-logo-mark">.NET</span></div>
                <span class="skill-tile-name">.NET</span>
              </div>
              <div class="skill-tile-item" title="RESTful APIs">
                <div class="skill-logo-tile tile-api"><i class="bx bx-code-curly" aria-hidden="true"></i></div>
                <span class="skill-tile-name">REST APIs</span>
              </div>
            </div>
          </div>

          <!-- 2. Frontend Development -->
          <div class="skill-category-card" data-category="frontend">
            <div class="skill-category-header">
              <div class="skill-cat-title-wrap">
                <span class="skill-cat-icon"><i class="bx bx-layout" aria-hidden="true"></i></span>
                <div>
                  <h3 class="skill-cat-title">Frontend Development</h3>
                  <p class="skill-cat-desc">Clean, responsive web interfaces</p>
                </div>
              </div>
            </div>
            <div class="skill-tiles-row">
              <div class="skill-tile-item" title="React js">
                <div class="skill-logo-tile tile-react"><i class="bx bxl-react" aria-hidden="true"></i></div>
                <span class="skill-tile-name">React js</span>
              </div>
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
                  <h3 class="skill-cat-title">Databases</h3>
                  <p class="skill-cat-desc">Database design, queries &amp; data management</p>
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
                  <h3 class="skill-cat-title">DevOps &amp; Cloud</h3>
                  <p class="skill-cat-desc">Docker, cloud hosting &amp; Linux environments</p>
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
            Services &amp; <span class="gradient-text">Solutions</span>
          </h2>
          <p class="section-subtitle">
            Building reliable backend systems, practical web applications, and database solutions that help businesses run smoothly.
          </p>
        </div>

        <div class="services-cards-grid">

          <!-- Service 1: Backend & RESTful API Development -->
          <article class="service-card service-blue">
            <div class="service-icon-wrap">
              <i class="bx bx-server" aria-hidden="true"></i>
            </div>
            <div class="service-body">
              <h3 class="service-title">Backend &amp; API Development</h3>
              <p class="service-desc">
                Building fast and secure server-side applications and REST APIs using Python and Django. Focused on reliable performance, clean code, and secure authentication.
              </p>
            </div>
          </article>

          <!-- Service 2: Database Design & Optimization -->
          <article class="service-card service-indigo">
            <div class="service-icon-wrap">
              <i class="bx bx-data" aria-hidden="true"></i>
            </div>
            <div class="service-body">
              <h3 class="service-title">Database Design &amp; Optimization</h3>
              <p class="service-desc">
                Designing well-structured database schemas and writing fast, optimized queries in PostgreSQL and MySQL to keep data safe, organized, and reliable.
              </p>
            </div>
          </article>

          <!-- Service 3: Clinic & Practice Management Software -->
          <article class="service-card service-cyan">
            <div class="service-icon-wrap">
              <i class="bx bx-pulse" aria-hidden="true"></i>
            </div>
            <div class="service-body">
              <h3 class="service-title">Clinic &amp; Business Management Software</h3>
              <p class="service-desc">
                Creating specialized software for clinics and businesses (like dental practice platforms) with easy appointment scheduling, patient records, and billing tools.
              </p>
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
                Developing complete web applications from start to finish — combining user-friendly, responsive frontends with solid Django backend systems.
              </p>
            </div>
          </article>

          <!-- Service 5: Cloud Deployment & DevOps -->
          <article class="service-card service-amber">
            <div class="service-icon-wrap">
              <i class="bx bx-cloud" aria-hidden="true"></i>
            </div>
            <div class="service-body">
              <h3 class="service-title">Cloud Deployment &amp; DevOps</h3>
              <p class="service-desc">
                Setting up and maintaining web applications in the cloud using Docker, AWS, and Linux servers with automated backups to ensure high uptime.
              </p>
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
                Writing custom Python scripts and scrapers to automate repetitive tasks, import/export data, and save time for everyday business operations.
              </p>
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
            A practical approach to building dependable software, solving real problems, and delivering results.
          </p>
        </div>

        <!-- 4 Minimalist Value Proposition Cards (No Icons, Clean Title + Description) -->
        <div class="why-cards-grid">

          <!-- Card 1: Practical Development -->
          <article class="why-simple-card">
            <h3 class="why-card-title">Practical Development</h3>
            <p class="why-card-desc">
              I focus on writing clean, maintainable Python and Django code with solid database design, choosing simple and reliable solutions over unnecessary complexity.
            </p>
          </article>

          <!-- Card 2: Problem-Solving Mindset -->
          <article class="why-simple-card">
            <h3 class="why-card-title">Problem-Solving Mindset</h3>
            <p class="why-card-desc">
              I break down challenging business requirements into clear, step-by-step solutions, optimizing queries and logic for smooth everyday performance.
            </p>
          </article>

          <!-- Card 3: Product-Oriented Thinking (Tinted accent card matching reference UI) -->
          <article class="why-simple-card card-accent-tint">
            <h3 class="why-card-title">Product-Oriented Thinking</h3>
            <p class="why-card-desc">
              I connect business goals with technology, always keeping the end-user in mind to create software that is genuinely easy to use and adds real value.
            </p>
          </article>

          <!-- Card 4: Continuous Learning -->
          <article class="why-simple-card">
            <h3 class="why-card-title">Continuous Learning</h3>
            <p class="why-card-desc">
              I constantly sharpen my skills with modern tools, AI, cloud technologies, and best practices to build better and faster applications.
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
            A showcase of web applications, SaaS platforms, and backend systems I have built and delivered.
          </p>
        </div>

        <!-- Projects Grid -->
        <div class="home-projects-grid">
          <?php 
          $displayProjects = array_slice($featured_projects ?? [], 0, 6);
          foreach ($displayProjects as $p): 
            $shortDesc = !empty($p['short_description']) ? $p['short_description'] : ($p['description'] ?? '');
            $slug = !empty($p['slug']) ? $p['slug'] : \App\Models\FeaturedProject::slugify($p['title'] ?? 'project');
            $detailUrl = "/works/" . urlencode($slug);
          ?>
          <article class="home-project-card">
            <a href="<?= $detailUrl ?>" class="home-project-image-link" aria-label="<?= htmlspecialchars($p['title'] ?? '') ?>">
              <div class="home-project-image">
                <img src="/<?= ltrim(htmlspecialchars($p['image'] ?? 'images/projectcard1.jpg'), '/') ?>" alt="<?= htmlspecialchars($p['title'] ?? '') ?>" loading="lazy" onerror="this.onerror=null; this.src='/images/projectcard1.jpg'" />
              </div>
            </a>

            <div class="home-project-body">
              <h3 class="home-project-title">
                <a href="<?= $detailUrl ?>" class="project-title-link">
                  <?= htmlspecialchars($p['title'] ?? '') ?>
                </a>
              </h3>
              
              <p class="home-project-desc">
                <?= htmlspecialchars($shortDesc) ?>
              </p>

              <!-- Footer with Live Site link (no technology pills on card face) -->
              <div class="home-project-footer">
                <a href="<?= htmlspecialchars($p['live_url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="live-site-link">
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
              Practical software applications and projects developed during my BCA coursework at Kantipur City College.
            </p>
          </div>

          <div class="home-projects-grid academic-projects-grid">
            <?php foreach (($academic_projects ?? []) as $a): 
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
            Articles &amp; <span class="gradient-text">Insights</span>
          </h2>
          <p class="section-subtitle">
            Practical notes, tutorials, and case studies on backend development, databases, and software building.
          </p>
        </div>

        <!-- Insights Grid (Image & Title Cards) -->
        <div class="insights-cards-grid">
          <?php foreach (($blog_insights ?? []) as $post): ?>
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

    <section class="contact" id="contact">
      <div class="container">

        <div class="section-heading-block text-center reveal">
          <span class="section-tag">get in touch</span>
          <h2 class="section-main-title">Contact <span>Me</span></h2>
          <p class="section-subtitle">Open for full-time, freelance &amp; project inquiries</p>
        </div>

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
              <textarea id="message" name="message" placeholder="Hi Ganga, I'd like to discuss…" rows="5"
                required></textarea>
            </div>
            <div class="contact-btn-wrap">
              <button type="submit" class="btn btn-primary send-btn">
                <i class="bx bx-send" aria-hidden="true"></i>
                <span>Send Message</span>
              </button>
            </div>
            <div id="contactFormStatus" class="contact-status-msg" style="display:none;" role="status"></div>
          </form>

          <!-- Contact Info -->
          <div class="contact-info reveal reveal-delay">

            <div class="info-item">
              <div class="info-icon"><i class="bx bx-envelope" aria-hidden="true"></i></div>
              <div class="info-text">
                <h3>Email</h3>
                <a href="mailto:info@gangasagar.com.np">info@gangasagar.com.np</a>
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
              <a href="https://github.com/gangasagar12" aria-label="GitHub" target="_blank" rel="noopener noreferrer"><i
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

  <?php if (!empty($active_promotion)): ?>
  <!-- ── Dynamic Offer & Promotional Pop-up Window ── -->
  <div class="promo-popup-modal" id="promoPopupModal" role="dialog" aria-modal="true" aria-labelledby="promoPopupTitle" aria-hidden="true">
    <div class="promo-popup-backdrop" data-close-promo></div>
    <div class="promo-popup-dialog">
      <button type="button" class="promo-popup-close" data-close-promo aria-label="Close offer pop-up">
        <i class="bx bx-x" aria-hidden="true"></i>
      </button>

      <?php if (!empty($active_promotion['image'])): ?>
      <div class="promo-popup-media">
        <img src="/<?= ltrim(htmlspecialchars($active_promotion['image']), '/') ?>" 
             alt="<?= htmlspecialchars($active_promotion['title']) ?>" 
             class="promo-popup-img"
             onerror="this.src='/images/projectcard1.jpg'" />
      </div>
      <?php endif; ?>

      <div class="promo-popup-body">
        <h3 class="promo-popup-title" id="promoPopupTitle">
          <?= htmlspecialchars($active_promotion['title']) ?>
        </h3>
        <p class="promo-popup-desc">
          <?= nl2br(htmlspecialchars($active_promotion['description'])) ?>
        </p>

        <div class="promo-popup-actions">
          <?php 
            $btnUrl = !empty($active_promotion['button_url']) ? $active_promotion['button_url'] : '#contact';
            $btnText = !empty($active_promotion['button_text']) ? $active_promotion['button_text'] : 'Claim Offer';
          ?>
          <a href="<?= htmlspecialchars($btnUrl) ?>" class="btn-promo-primary" data-close-promo>
            <span><?= htmlspecialchars($btnText) ?></span>
            <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
          </a>
          <button type="button" class="btn-promo-secondary" data-close-promo>
            Dismiss
          </button>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>


  <!-- ─── Scripts ─────────────────────────────────────────────── -->
  <!-- Typed.js -->
  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12/lib/typed.min.js"></script>
  <!-- EmailJS -->
  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
  <!-- Your app JS -->
  <script src="/js/app.js"></script>

</body>

</html>