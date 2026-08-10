'use strict';
/* Cross-cutting utilities: toasts, lazy-load, star markup */

function toast(msg, icon = '✓') {
  const box = $('#toasts');
  const el = document.createElement('div');
  el.className = 'toast';
  el.innerHTML = `<span>${icon}</span><span>${msg}</span>`;
  box.appendChild(el);
  setTimeout(() => el.remove(), 2600);
}

/* Lazy image loading: real src is only set once the image scrolls into view */
const lazyObserver = new IntersectionObserver((entries, obs) => {
  entries.forEach((entry) => {
    if (!entry.isIntersecting) return;
    const img = entry.target;
    img.src = img.dataset.src;
    img.removeAttribute('data-src');
    img.addEventListener('load', () => img.classList.add('is-loaded'), { once: true });
    obs.unobserve(img);
  });
}, { rootMargin: '150px' });

function lazyize(root = document) {
  $$('img[data-src]', root).forEach((img) => lazyObserver.observe(img));
}

function starString(rating) {
  const full = Math.round(rating);
  return '★★★★★☆☆☆☆☆'.slice(5 - full, 10 - full);
}
