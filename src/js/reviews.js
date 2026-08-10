'use strict';
/* Customer reviews: one card shown at a time, prev/next buttons + dots */

function renderReviews() {
  $('#reviewTrack').innerHTML = REVIEWS.map((r) => `<div class="review-card">
    <img class="review-card__avatar" src="${r.avatar}" alt="${r.name}" loading="lazy" />
    <p class="review-card__stars">${starString(r.rating)}</p>
    <p class="review-card__text">"${r.text}"</p>
    <p class="review-card__name">${r.name}</p>
    <p class="review-card__role">${r.role}</p>
  </div>`).join('');

  const dots = $('#reviewDots');
  dots.innerHTML = REVIEWS.map((_, i) => `<button aria-label="Review ${i + 1}" class="${i === 0 ? 'is-active' : ''}"></button>`).join('');
  const track = $('#reviewTrack');

  function paint() {
    track.style.transform = `translateX(-${state.reviewIndex * 100}%)`;
    $$('button', dots).forEach((d, i) => d.classList.toggle('is-active', i === state.reviewIndex));
  }
  dots.addEventListener('click', (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    state.reviewIndex = Array.from(dots.children).indexOf(btn);
    paint();
  });
  $('#revPrev').addEventListener('click', () => { state.reviewIndex = (state.reviewIndex - 1 + REVIEWS.length) % REVIEWS.length; paint(); });
  $('#revNext').addEventListener('click', () => { state.reviewIndex = (state.reviewIndex + 1) % REVIEWS.length; paint(); });
  paint();
}
