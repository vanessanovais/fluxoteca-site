// ======================
// CONTROLE DE TEMA
// ======================
(function () {
  const root = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');

  function applyTheme(theme) {
    if (theme === 'dark') {
      root.setAttribute('data-theme', 'dark');
      if (themeIcon) {
        themeIcon.innerHTML = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>';
      }
    } else {
      root.removeAttribute('data-theme');
      if (themeIcon) {
        themeIcon.innerHTML = '<circle cx="12" cy="12" r="5" />'
          + '<line x1="12" y1="1" x2="12" y2="3" />'
          + '<line x1="12" y1="21" x2="12" y2="23" />'
          + '<line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />'
          + '<line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />'
          + '<line x1="1" y1="12" x2="3" y2="12" />'
          + '<line x1="21" y1="12" x2="23" y2="12" />'
          + '<line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />'
          + '<line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />';
      }
    }
  }

  const savedTheme = localStorage.getItem('fluxoteca-theme');
  if (savedTheme) {
    applyTheme(savedTheme);
  }

  if (themeToggle) {
    themeToggle.addEventListener('click', function () {
      const isDark = root.getAttribute('data-theme') === 'dark';
      const newTheme = isDark ? 'light' : 'dark';
      applyTheme(newTheme);
      localStorage.setItem('fluxoteca-theme', newTheme);
    });
  }
})();

// =============================
// SIDEBAR MOBILE + VOLTAR TOPO
// =============================
(function () {
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('mobileSidebarToggle');
  const overlay = document.getElementById('mobileSidebarOverlay');
  const backToTop = document.getElementById('backToTop');

  if (toggleBtn && sidebar && overlay) {
    function openSidebar() {
      sidebar.classList.add('open');
      overlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
      sidebar.classList.remove('open');
      overlay.classList.remove('active');
      document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', function () {
      const isOpen = sidebar.classList.contains('open');
      if (isOpen) {
        closeSidebar();
      } else {
        openSidebar();
      }
    });

    overlay.addEventListener('click', closeSidebar);

    const items = sidebar.querySelectorAll('.sidebar-item');
    items.forEach(item => {
      item.addEventListener('click', () => {
        if (window.innerWidth <= 900) {
          closeSidebar();
        }
      });
    });
  }

  if (backToTop) {
    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }
})();

// =============================
// TOC DO ARTIGO (sumário)
// =============================
(function () {
  const article = document.getElementById('articleContent');
  const tocList = document.getElementById('tocList');
  const tocCard = document.getElementById('tocCard');
  const tocToggle = document.querySelector('.toc-toggle');

  if (!article || !tocList) return;

  const headings = Array.from(article.querySelectorAll('h2, h3'));

  headings.forEach((heading, index) => {
    const id = heading.id || 'sec-' + (index + 1);
    heading.id = id;

    const li = document.createElement('li');
    const a = document.createElement('a');
    a.href = '#' + id;
    a.textContent = heading.textContent.trim();
    a.className = 'toc-link';

    if (heading.tagName.toLowerCase() === 'h3') {
      a.style.marginLeft = '12px';
    }

    a.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.getElementById(id);
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });

    li.appendChild(a);
    tocList.appendChild(li);
  });

  // Toggle mobile
  if (tocToggle && tocCard) {
    tocToggle.addEventListener('click', function () {
      const isOpen = tocCard.classList.toggle('is-open');
      tocToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  // Destaque da seção ativa
  const links = Array.from(document.querySelectorAll('.toc-link'));

  function updateActive() {
    let currentId = null;
    const scrollPos = window.scrollY || window.pageYOffset;

    headings.forEach(h => {
      const top = h.getBoundingClientRect().top + window.scrollY - 140;
      if (scrollPos >= top) {
        currentId = h.id;
      }
    });

    links.forEach(link => {
      const href = link.getAttribute('href');
      const isActive = currentId && href === '#' + currentId;
      link.classList.toggle('is-active', isActive);
    });
  }

  window.addEventListener('scroll', updateActive);
  updateActive();
})();
