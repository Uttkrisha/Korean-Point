'use strict';
/* Category cards, filter chips, product card markup, filter/search/sort grid,
   and static content renders: why / routine timeline / FAQ / instagram grid */

function renderCategories() {
  const grid = $('#catGrid');
  grid.innerHTML = CATEGORIES.map((c) => {
    const count = PRODUCTS.filter((p) => p.category === c.name).length;
    return `<button class="cat-card" data-category="${c.name}" type="button">
      <span class="cat-card__icon" aria-hidden="true">${c.icon}</span>
      <span class="cat-card__name">${c.name}</span>
      <span class="cat-card__count">${count} products</span>
    </button>`;
  }).join('');
  grid.addEventListener('click', (e) => {
    const card = e.target.closest('.cat-card');
    if (!card) return;
    if ($('#productGrid')) {
      state.category = card.dataset.category;
      syncChips();
      renderProducts();
    } else {
      location.href = 'shop.html?category=' + encodeURIComponent(card.dataset.category);
    }
  });
}

function renderChips() {
  const box = $('#filterChips');
  const cats = ['All', ...CATEGORIES.map((c) => c.name)];
  box.innerHTML = cats.map((c) => `<button class="chip${c === state.category ? ' is-active' : ''}" data-chip="${c}" type="button">${c}</button>`).join('');
  box.addEventListener('click', (e) => {
    const chip = e.target.closest('.chip');
    if (!chip) return;
    state.category = chip.dataset.chip;
    syncChips();
    renderProducts();
  });
}
function syncChips() {
  $$('#filterChips .chip').forEach((c) => c.classList.toggle('is-active', c.dataset.chip === state.category));
  $$('#catGrid .cat-card').forEach((c) => c.classList.toggle('is-active', c.dataset.category === state.category));
}

function productCard(p) {
  const off = p.was > p.price ? Math.round((1 - p.price / p.was) * 100) : 0;
  const badgeMap = { best: '<span class="tag tag--best">Best Seller</span>', new: '<span class="tag tag--new">New</span>', vegan: '<span class="tag tag--vegan">Vegan</span>' };
  return `<article class="card" data-id="${p.id}">
    <div class="card__media">
      <div class="card__badges">${p.badges.map((b) => badgeMap[b] || '').join('')}${off ? `<span class="tag tag--sale">-${off}%</span>` : ''}</div>
      <img data-src="${p.img}" alt="${p.name}" loading="lazy" />
    </div>
    <div class="card__body">
      <span class="card__brand">${p.brand}</span>
      <h3 class="card__name">${p.name}</h3>
      <div class="card__rating"><span class="stars">${starString(p.rating)}</span><span>${p.rating} (${p.reviews})</span></div>
      <div class="card__price"><span class="now">${fmt(p.price)}</span>${off ? `<span class="was">${fmt(p.was)}</span>` : ''}</div>
    </div>
    <div class="card__foot">
      <button class="quick-btn" data-quick="${p.id}" type="button">Quick View</button>
      <button class="add-btn" data-add="${p.id}" type="button">Add to Cart</button>
    </div>
  </article>`;
}

function getFilteredProducts() {
  let list = PRODUCTS.slice();
  if (state.category !== 'All') list = list.filter((p) => p.category === state.category);
  if (state.search.trim()) {
    const q = state.search.trim().toLowerCase();
    list = list.filter((p) => p.name.toLowerCase().includes(q) || p.brand.toLowerCase().includes(q) || p.category.toLowerCase().includes(q));
  }
  switch (state.sort) {
    case 'price-asc': list.sort((a, b) => a.price - b.price); break;
    case 'price-desc': list.sort((a, b) => b.price - a.price); break;
    case 'rating': list.sort((a, b) => b.rating - a.rating); break;
    case 'new': list.sort((a, b) => new Date(b.date) - new Date(a.date)); break;
    default: list.sort((a, b) => (b.badges.includes('best') ? 1 : 0) - (a.badges.includes('best') ? 1 : 0));
  }
  return list;
}

function renderProducts() {
  const grid = $('#productGrid');
  const list = getFilteredProducts();
  grid.innerHTML = list.map(productCard).join('');
  $('#emptyState').hidden = list.length !== 0;
  $('#resultCount').textContent = `${list.length} product${list.length === 1 ? '' : 's'}`;
  lazyize(grid);
}

function renderWhy() {
  $('#whyGrid').innerHTML = WHY.map((w) => `<div class="why-card"><div class="why-card__icon">${w.icon}</div><h3 class="why-card__title">${w.title}</h3><p class="why-card__text">${w.text}</p></div>`).join('');
}
function renderTimeline() {
  $('#timeline').innerHTML = ROUTINE.map((r, i) => `<li class="tl-step"><div class="tl-step__ring">${r.icon}</div><p class="tl-step__num">STEP ${i + 1}</p><h3 class="tl-step__title">${r.title}</h3><p class="tl-step__text">${r.text}</p></li>`).join('');
}
function renderFaq() {
  $('#faqList').innerHTML = FAQS.map((f, i) => `<div class="faq-item" data-i="${i}">
    <button class="faq-q" aria-expanded="false"><span>${f.q}</span><span class="faq-q__icon">+</span></button>
    <div class="faq-a"><p>${f.a}</p></div>
  </div>`).join('');
  $$('#faqList .faq-q').forEach((btn) => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.faq-item');
      const open = item.classList.toggle('is-open');
      btn.setAttribute('aria-expanded', String(open));
    });
  });
}
function renderIg() {
  $('#igGrid').innerHTML = IG.map((slug) => `<div class="ig-item"><img data-src="https://images.unsplash.com/${slug}?auto=format&fit=crop&w=400&q=65" alt="Customer skincare photo" /></div>`).join('');
  lazyize($('#igGrid'));
}
