'use strict';
/* Navbar active-link/mobile menu, search bar, theme toggle, back-to-top,
   and logout (destroys the PHP session). */

function logout() {
  location.href = '../actions/logout.php';
}

function setupNav() {
  const hamburger = $('#hamburger');
  const links = $('#navLinks');
  const overlay = $('#navOverlay');
  function toggleMenu(open) {
    hamburger.setAttribute('aria-expanded', String(open));
    links.classList.toggle('is-open', open);
    overlay.hidden = !open;
    document.body.style.overflow = open ? 'hidden' : '';
  }
  hamburger.addEventListener('click', () => toggleMenu(!links.classList.contains('is-open')));
  overlay.addEventListener('click', () => toggleMenu(false));
  $$('.nav__link').forEach((l) => l.addEventListener('click', () => toggleMenu(false)));

  // highlight the link for the page we're currently on
  const here = location.pathname.split('/').pop() || 'index.php';
  $$('.nav__link').forEach((l) => l.classList.toggle('is-active', l.getAttribute('href') === here));
}

//search bar toggle and search input handling (on shop page, filter products; on other pages, jump to shop page)
function setupSearch() {
  const toggle = $('#searchToggle');
  const bar = $('#searchbar');
  const input = $('#searchInput');
  const onShop = typeof renderProducts === 'function';

  toggle.addEventListener('click', () => {
    const open = bar.hidden;
    bar.hidden = !open;
    toggle.setAttribute('aria-expanded', String(open));
    if (open) input.focus();
  });

  if (onShop) {
    input.addEventListener('input', (e) => { state.search = e.target.value; renderProducts(); });
    $('#searchClear').addEventListener('click', () => { input.value = ''; state.search = ''; renderProducts(); });
  } else {
    // other pages don't have a product grid — jump to the shop page to search
    input.addEventListener('keydown', (e) => {
      if (e.key !== 'Enter') return;
      e.preventDefault();
      location.href = 'shop.php?q=' + encodeURIComponent(input.value.trim());
    });
    $('#searchClear').addEventListener('click', () => { input.value = ''; });
  }
}

function setupTheme() {
  const initial = state.theme || 'light'; // light mode by default, regardless of OS setting
  document.documentElement.setAttribute('data-theme', initial);
  $('#themeToggle').addEventListener('click', () => {
    const next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    LS.set('kp_theme', next);
  });
}

function setupBackTop() {
  const btn = $('#backTop');
  window.addEventListener('scroll', () => { btn.hidden = window.scrollY < 500; }, { passive: true });
  btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}
