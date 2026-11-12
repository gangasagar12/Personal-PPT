
  //  app.js — Ganga Sagar Joshi Portfolio
  //  Covers: scroll-progress · header shrink · hamburger drawer ·
  //          active nav · scroll-reveal · typed.js · form handler


'use strict';

/* ─── 1. Typed.js ───────────────────────────────────────────────── */
if (document.querySelector('.typed-text')) {
  new Typed('.typed-text', {
    strings: [
      'Django Backend Developer',
      'Python & Cloud Enthusiast',
      'Machine Learning Explorer',
      'Full Stack Engineer',
      'Tech Entrepreneur & Builder',
    ],
    typeSpeed: 60,
    backSpeed: 35,
    backDelay: 1900,
    loop: true,
  });
}

/* ─── 2. Year in footer ─────────────────────────────────────────── */
const yearEl = document.getElementById('year');
if (yearEl) yearEl.textContent = new Date().getFullYear();

/* ─── 3. Scroll-progress bar ────────────────────────────────────── */
const progressBar = document.getElementById('scrollProgress');

function updateProgress() {
  const scrollTop    = document.documentElement.scrollTop  || document.body.scrollTop;
  const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  const pct          = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
  if (progressBar) progressBar.style.width = pct + '%';
}

/* ─── 4. Header blur & shadow on scroll ─────────────────────────── */
const header = document.getElementById('header');

function onScroll() {
  updateProgress();
  if (header) header.classList.toggle('scrolled', window.scrollY > 25);
  setActiveLink();
}

window.addEventListener('scroll', onScroll, { passive: true });

/* ─── 5. Mobile drawer ──────────────────────────────────────────── */
const hamburger      = document.getElementById('hamburger');
const drawer         = document.getElementById('mobileDrawer');
const backdrop       = document.getElementById('drawerBackdrop');
const drawerCloseBtn = document.getElementById('drawerClose');

function openDrawer() {
  if (!drawer || !backdrop || !hamburger) return;
  drawer.classList.add('open');
  backdrop.classList.add('open');
  hamburger.classList.add('open');
  hamburger.setAttribute('aria-expanded', 'true');
  drawer.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden'; // prevent background scrolling
}

function closeDrawer() {
  if (!drawer || !backdrop || !hamburger) return;
  drawer.classList.remove('open');
  backdrop.classList.remove('open');
  hamburger.classList.remove('open');
  hamburger.setAttribute('aria-expanded', 'false');
  drawer.setAttribute('aria-hidden', 'true');
  document.body.style.overflow = '';
}

if (hamburger && drawer && backdrop) {
  hamburger.addEventListener('click', () => {
    drawer.classList.contains('open') ? closeDrawer() : openDrawer();
  });

  backdrop.addEventListener('click', closeDrawer);

  if (drawerCloseBtn) {
    drawerCloseBtn.addEventListener('click', closeDrawer);
  }

  // Close on any drawer link click
  drawer.querySelectorAll('.drawer-link').forEach(link => {
    link.addEventListener('click', closeDrawer);
  });

  // Close on Escape key
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && drawer.classList.contains('open')) closeDrawer();
  });
}

/* ─── 6. Active nav link on scroll & Smooth Navigation ─────────── */
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-link');
const drawerLinks = document.querySelectorAll('.drawer-link');

function setActiveLink() {
  let current = '';
  const offset = parseFloat(getComputedStyle(document.documentElement)
    .getPropertyValue('--nav-h')) || 74;

  sections.forEach(sec => {
    if (window.scrollY >= sec.offsetTop - offset - 40) {
      current = sec.id;
    }
  });

  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    link.classList.toggle('active', href === `#${current}`);
  });

  drawerLinks.forEach(link => {
    const href = link.getAttribute('href');
    link.classList.toggle('active', href === `#${current}`);
  });
}

// Intercept clicks on links for graceful handling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const targetId = this.getAttribute('href');
    if (targetId === '#' || !targetId) return;

    const targetEl = document.querySelector(targetId);
    if (targetEl) {
      e.preventDefault();
      const offset = parseFloat(getComputedStyle(document.documentElement)
        .getPropertyValue('--nav-h')) || 74;
      const targetPos = targetEl.getBoundingClientRect().top + window.pageYOffset - offset;
      window.scrollTo({
        top: targetPos,
        behavior: 'smooth'
      });
    } else {
      // Future section notification placeholder
      e.preventDefault();
      showToast(`${targetId.replace('#', '').toUpperCase()} section will be unlocked in the next step!`);
    }
  });
});

// Toast notification helper
function showToast(message) {
  let toast = document.getElementById('portfolioToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'portfolioToast';
    toast.style.cssText = `
      position: fixed;
      bottom: 24px;
      right: 24px;
      background: #0f172a;
      color: #ffffff;
      padding: 12px 20px;
      border-radius: 12px;
      font-size: 0.88rem;
      font-weight: 500;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.2);
      z-index: 2000;
      opacity: 0;
      transform: translateY(12px);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      align-items: center;
      gap: 8px;
      border-left: 4px solid #2563eb;
    `;
    document.body.appendChild(toast);
  }
  toast.innerHTML = `<i class='bx bx-info-circle' style='color:#38bdf8;font-size:1.1rem;'></i> ${message}`;
  toast.style.opacity = '1';
  toast.style.transform = 'translateY(0)';

  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(12px)';
  }, 3200);
}

// Run once on load
setActiveLink();

/* ─── 7. Scroll Reveal (IntersectionObserver) ──────────────────── */
const revealEls = document.querySelectorAll('.reveal');

const revealObserver = new IntersectionObserver(
  entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;

      // Stagger children inside grid containers
      const parent = entry.target.parentElement;
      const isInGrid = parent &&
        (parent.classList.contains('skills-grid') ||
         parent.classList.contains('projects-grid'));

      const index  = isInGrid
        ? Array.from(parent.children).indexOf(entry.target)
        : 0;
      const delay  = index * 90; // ms

      setTimeout(() => {
        entry.target.classList.add('visible');
      }, delay);

      revealObserver.unobserve(entry.target);
    });
  },
  { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
);

revealEls.forEach(el => revealObserver.observe(el));

/* ─── 8. Contact Form (EmailJS) ─────────────────────────────────── */
/*
  Replace the three placeholder strings below with your real
  EmailJS credentials from https://dashboard.emailjs.com
*/
emailjs.init('YOUR_PUBLIC_KEY');

const contactForm = document.getElementById('contactForm');

if (contactForm) {
  contactForm.addEventListener('submit', async e => {
    e.preventDefault();

    const btn     = contactForm.querySelector('.send-btn');
    const btnText = btn.querySelector('span');

    // Basic client-side validation
    const required = contactForm.querySelectorAll('[required]');
    let valid = true;
    required.forEach(field => {
      field.style.borderColor = '';
      if (!field.value.trim()) {
        field.style.borderColor = '#e05555';
        valid = false;
      }
    });
    if (!valid) return;

    // Loading state
    btnText.textContent = 'Sending…';
    btn.disabled = true;

    try {
      await emailjs.sendForm('YOUR_SERVICE_ID', 'YOUR_TEMPLATE_ID', contactForm);

      btnText.textContent = '✓ Message Sent!';
      btn.style.background = 'linear-gradient(135deg,#00d4aa,#009e7f)';
      contactForm.reset();

    } catch (err) {
      console.error('EmailJS error:', err);
      btnText.textContent = 'Failed — Try Again';
      btn.style.background = 'linear-gradient(135deg,#e05555,#b03030)';
    }

    // Reset button after 4 s
    setTimeout(() => {
      btnText.textContent = 'Send Message';
      btn.disabled        = false;
      btn.style.background = '';
    }, 4000);
  });
}