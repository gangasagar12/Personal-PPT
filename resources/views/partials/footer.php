<?php
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$is_home = ($uri === '/' || $uri === '' || $uri === '/index.php');
?>

<footer class="footer">
  <div class="container footer-inner">
    <!-- Top Row: Brand & Quick Navigation Links -->
    <div class="footer-top">
      <div class="footer-brand-box">
        <a href="<?= $is_home ? '#home' : '/' ?>" class="footer-logo" aria-label="Ganga Sagar Joshi Home">
          <span class="footer-avatar-wrap">
            <img src="/images/logo-square.png" alt="Ganga Sagar Joshi" class="footer-avatar-img" width="34" height="34" />
          </span>
          <span>Ganga Sagar<span class="dot">.</span></span>
        </a>
        <p class="footer-tagline">
          Software Developer &bull; Co-Founder at Strataflow Technologies
        </p>
      </div>

      <nav class="footer-nav" aria-label="Footer navigation">
        <a href="<?= $is_home ? '#home' : '/' ?>" class="footer-nav-link">Home</a>
        <a href="<?= $is_home ? '#about' : '/#about' ?>" class="footer-nav-link">About</a>
        <a href="/experience" class="footer-nav-link">Experience</a>
        <a href="/projects" class="footer-nav-link">Projects</a>
        <a href="/profile" class="footer-nav-link">Profile</a>
        <a href="/insights" class="footer-nav-link">Insights</a>
        <a href="<?= $is_home ? '#contact' : '/#contact' ?>" class="footer-nav-link">Contact</a>
      </nav>
    </div>

    <!-- Divider -->
    <div class="footer-divider"></div>

    <!-- Bottom Row: Copyright & Social Links & Back to Top -->
    <div class="footer-bottom">
      <p class="footer-copy">
        &copy; <span id="year"><?= date('Y') ?></span> Ganga Sagar Joshi. All rights reserved.
        <span class="footer-crafted">Crafted with <i class="bx bx-heart" aria-hidden="true"></i> in Nepal.</span>
      </p>

      <div class="footer-actions">
        <div class="footer-social">
          <a href="https://github.com/gangasaagar" target="_blank" rel="noopener noreferrer" aria-label="GitHub" title="GitHub">
            <i class="bx bxl-github" aria-hidden="true"></i>
          </a>
          <a href="https://www.linkedin.com/in/ganga-sagar15" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" title="LinkedIn">
            <i class="bx bxl-linkedin" aria-hidden="true"></i>
          </a>
          <a href="mailto:gangasaagar62@gmail.com" aria-label="Email" title="Email">
            <i class="bx bx-envelope" aria-hidden="true"></i>
          </a>
        </div>

        <a href="#header" class="back-to-top" aria-label="Back to top" title="Back to top">
          <i class="bx bx-up-arrow-alt" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </div>
</footer>
