// ===== MOBILE MENU TOGGLE =====
const hamburger = document.querySelector('.hamburger');
const navbar = document.querySelector('.navbar');
const navLinks = document.querySelectorAll('.nav-link');

hamburger.addEventListener('click', () => {
  hamburger.classList.toggle('active');
  navbar.classList.toggle('active');
  document.body.style.overflow = navbar.classList.contains('active') ? 'hidden' : '';
});

navLinks.forEach(link => {
  link.addEventListener('click', () => {
    hamburger.classList.remove('active');
    navbar.classList.remove('active');
    document.body.style.overflow = '';
  });
});

// ===== TYPED.JS INITIALIZATION =====
document.addEventListener('DOMContentLoaded', () => {
  const typed = new Typed('.typed-text', {
    strings: ['Django Developer', 'Python Developer', 'Backend Developer', 'Web Developer'],
    typeSpeed: 60,
    backSpeed: 40,
    backDelay: 1500,
    loop: true,
    smartBackspace: true,
    cursorChar: '|',
    fadeOut: false
  });

  // ===== SCROLL REVEAL ANIMATION =====
  const revealElements = document.querySelectorAll('.reveal');
  
  const revealOnScroll = () => {
    const windowHeight = window.innerHeight;
    const revealPoint = 150;
    
    revealElements.forEach(element => {
      const revealTop = element.getBoundingClientRect().top;
      
      if (revealTop < windowHeight - revealPoint) {
        element.classList.add('active');
      }
    });
  };
  
  window.addEventListener('scroll', revealOnScroll);
  revealOnScroll(); // Initial check

  // ===== ACTIVE NAV LINK ON SCROLL =====
  const sections = document.querySelectorAll('section[id]');
  
  const highlightNavLink = () => {
    const scrollY = window.pageYOffset;
    
    sections.forEach(section => {
      const sectionHeight = section.offsetHeight;
      const sectionTop = section.offsetTop - 100;
      const sectionId = section.getAttribute('id');
      const navLink = document.querySelector(`.nav-link[href="#${sectionId}"]`);
      
      if (navLink) {
        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
          navLink.classList.add('active');
        } else {
          navLink.classList.remove('active');
        }
      }
    });
  };
  
  window.addEventListener('scroll', highlightNavLink);

  // ===== ANIMATED COUNTER FOR SKILLS =====
  const skillCards = document.querySelectorAll('.skill-card');
  const counters = document.querySelectorAll('.counter');
  const progressBars = document.querySelectorAll('.progress-bar span');
  
  const animateCounters = () => {
    skillCards.forEach((card, index) => {
      const cardTop = card.getBoundingClientRect().top;
      const windowHeight = window.innerHeight;
      
      if (cardTop < windowHeight - 100) {
        const percent = card.getAttribute('data-percent');
        const counter = counters[index];
        const progressBar = progressBars[index];
        
        let count = 0;
        const duration = 2000; // 2 seconds
        const increment = percent / (duration / 16); // 60fps
        
        const updateCounter = () => {
          count += increment;
          if (count < percent) {
            counter.textContent = Math.floor(count);
            progressBar.style.width = count + '%';
            setTimeout(updateCounter, 16);
          } else {
            counter.textContent = percent;
            progressBar.style.width = percent + '%';
          }
        };
        
        updateCounter();
        
        // Remove event listener after animation
        window.removeEventListener('scroll', animateCounters);
      }
    });
  };
  
  window.addEventListener('scroll', animateCounters);
  animateCounters(); // Initial check

  // ===== SMOOTH SCROLL FOR NAV LINKS =====
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        window.scrollTo({
          top: targetElement.offsetTop - 80,
          behavior: 'smooth'
        });
      }
    });
  });


  // ===== PARALLAX EFFECT =====
  const parallaxElements = document.querySelectorAll('.photo-wrap, .profile-wrap');
  
  window.addEventListener('mousemove', (e) => {
    const mouseX = e.clientX / window.innerWidth;
    const mouseY = e.clientY / window.innerHeight;
    
    parallaxElements.forEach(element => {
      const speed = 20;
      const x = (mouseX - 0.5) * speed;
      const y = (mouseY - 0.5) * speed;
      
      element.style.transform = `translate(${x}px, ${y}px)`;
    });
  });

  // ===== GLOW EFFECT ON HOVER =====
  const buttons = document.querySelectorAll('.btn-primary');
  
  buttons.forEach(button => {
    button.addEventListener('mouseenter', (e) => {
      const rect = button.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      button.style.setProperty('--x', `${x}px`);
      button.style.setProperty('--y', `${y}px`);
    });
  });

  // ===== THEME TOGGLE (Optional - can be added later) =====
  const themeToggle = document.createElement('button');
  themeToggle.className = 'theme-toggle';
  themeToggle.innerHTML = '<i class="bx bx-moon"></i>';
  themeToggle.style.cssText = `
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--gradient);
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 5px 15px rgba(0, 217, 255, 0.3);
    transition: all 0.3s ease;
  `;
  
  document.body.appendChild(themeToggle);
  
  themeToggle.addEventListener('click', () => {
    document.body.classList.toggle('light-mode');
    
    if (document.body.classList.contains('light-mode')) {
      themeToggle.innerHTML = '<i class="bx bx-sun"></i>';
      document.documentElement.style.setProperty('--dark', '#f8f9fa');
      document.documentElement.style.setProperty('--darker', '#e9ecef');
      document.documentElement.style.setProperty('--light', '#212529');
      document.documentElement.style.setProperty('--gray', '#6c757d');
    } else {
      themeToggle.innerHTML = '<i class="bx bx-moon"></i>';
      document.documentElement.style.setProperty('--dark', '#0a0a0f');
      document.documentElement.style.setProperty('--darker', '#050508');
      document.documentElement.style.setProperty('--light', '#f0f0f0');
      document.documentElement.style.setProperty('--gray', '#8a8aa3');
    }
  });

  // ===== LOADING ANIMATION =====
  window.addEventListener('load', () => {
    document.body.classList.add('loaded');
  });

  // ===== CURSOR EFFECT (Optional) =====
  const cursor = document.createElement('div');
  cursor.className = 'custom-cursor';
  cursor.style.cssText = `
    position: fixed;
    width: 20px;
    height: 20px;
    border: 2px solid var(--primary);
    border-radius: 50%;
    pointer-events: none;
    z-index: 9999;
    transform: translate(-50%, -50%);
    transition: width 0.3s, height 0.3s, background 0.3s;
  `;
  document.body.appendChild(cursor);
  
  document.addEventListener('mousemove', (e) => {
    cursor.style.left = `${e.clientX}px`;
    cursor.style.top = `${e.clientY}px`;
  });
  
  document.addEventListener('mousedown', () => {
    cursor.style.width = '15px';
    cursor.style.height = '15px';
  });
  
  document.addEventListener('mouseup', () => {
    cursor.style.width = '20px';
    cursor.style.height = '20px';
  });
  
  const clickableElements = document.querySelectorAll('a, button, .project-card');
  clickableElements.forEach(el => {
    el.addEventListener('mouseenter', () => {
      cursor.style.width = '40px';
      cursor.style.height = '40px';
      cursor.style.background = 'rgba(0, 217, 255, 0.1)';
    });
    
    el.addEventListener('mouseleave', () => {
      cursor.style.width = '20px';
      cursor.style.height = '20px';
      cursor.style.background = 'transparent';
    });
  });
});

//  for the services of contact form

// Initialize EmailJS
(function () {
  emailjs.init("OAkGZN_XUBWCjNlGn"); // paste public key here
})();

const form = document.getElementById("contact-form");

form.addEventListener("submit", function (e) {
  e.preventDefault();

  emailjs.sendForm(
    "service_0hd5syd",     // service id
    "template_hprhgrp",    // template id
    this
  ).then(
    function () {
      alert("✅ Message sent successfully!");
      form.reset();
    },
    function (error) {
      alert("❌ Failed to send message");
      console.log(error);
    }
  );
});
