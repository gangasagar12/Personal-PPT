/* ================================================================
   app.js — Ganga Sagar Joshi Portfolio
   Covers: scroll-progress · header shrink · hamburger drawer ·
           active nav · scroll-reveal · typed.js · form handler
   ================================================================ */

'use strict';

/* ─── 1. Typed.js ───────────────────────────────────────────────── */
new Typed('.typed-text', {
  strings: [
    'Django Developer',
    'Python Enthusiast',
    'ML Explorer',
    'Backend Engineer',
    'BCA Student',
  ],
  typeSpeed:  65,
  backSpeed:  35,
  backDelay: 1800,
  loop: true,
});

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

/* ─── 4. Header shrink on scroll ────────────────────────────────── */
const header = document.getElementById('header');

function onScroll() {
  updateProgress();
  if (header) header.classList.toggle('scrolled', window.scrollY > 50);
  setActiveLink();
}

window.addEventListener('scroll', onScroll, { passive: true });

/* ─── 5. Mobile drawer ──────────────────────────────────────────── */
const hamburger      = document.getElementById('hamburger');
const drawer         = document.getElementById('mobileDrawer');
const backdrop       = document.getElementById('drawerBackdrop');

function openDrawer() {
  drawer  .classList.add('open');
  backdrop.classList.add('open');
  hamburger.classList.add('open');
  hamburger.setAttribute('aria-expanded', 'true');
  drawer.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';   // prevent page scroll behind drawer
}

function closeDrawer() {
  drawer  .classList.remove('open');
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

  // Close on any drawer link click
  drawer.querySelectorAll('.drawer-link').forEach(link => {
    link.addEventListener('click', closeDrawer);
  });

  // Close on Escape key
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && drawer.classList.contains('open')) closeDrawer();
  });
}

/* ─── 6. Active nav link on scroll ─────────────────────────────── */
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-link');

function setActiveLink() {
  let current = '';
  const offset = parseFloat(getComputedStyle(document.documentElement)
    .getPropertyValue('--nav-h')) || 70;

  sections.forEach(sec => {
    if (window.scrollY >= sec.offsetTop - offset - 10) {
      current = sec.id;
    }
  });

  navLinks.forEach(link => {
    link.classList.toggle('active', link.getAttribute('href') === `#${current}`);
  });
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