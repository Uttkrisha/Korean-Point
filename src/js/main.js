'use strict';
/* Global click delegation + app bootstrap.
   The same script list runs on every page, so each render/setup call
   is guarded by whether that page actually has the matching element. */

function setupDelegation() {
  document.addEventListener('click', (e) => {
    const add = e.target.closest('[data-add]');
    if (add) addToCart(add.dataset.add);

    const quick = e.target.closest('[data-quick]');
    if (quick) openQuickView(quick.dataset.quick);

    const inc = e.target.closest('[data-inc]');
    if (inc) { const c = state.cart.find((x) => x.id === inc.dataset.inc); setQty(inc.dataset.inc, c.qty + 1); }

    const dec = e.target.closest('[data-dec]');
    if (dec) { const c = state.cart.find((x) => x.id === dec.dataset.dec); setQty(dec.dataset.dec, c.qty - 1); }

    const remove = e.target.closest('[data-remove]');
    if (remove) removeFromCart(remove.dataset.remove);
  });

  $('#cartToggle').addEventListener('click', () => openDrawer('#cartDrawer'));
  $('#cartClose').addEventListener('click', () => closeDrawer('#cartDrawer'));
  $('#cartToggleClose').addEventListener('click', () => closeDrawer('#cartDrawer'));
  $('#scrim').addEventListener('click', closeAllOverlays);

  $('#qvClose').addEventListener('click', () => closeModal('#quickModal'));
  $('#checkoutBtn').addEventListener('click', openCheckout);
  $('#coClose').addEventListener('click', () => closeModal('#checkoutModal'));
  $('#coForm').addEventListener('submit', handleCheckoutSubmit);
  $('#okClose').addEventListener('click', () => closeModal('#successModal'));

  $$('.modal').forEach((m) => m.addEventListener('click', (e) => { if (e.target === m) closeModal('#' + m.id); }));

  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAllOverlays(); });

  $('#sortSelect')?.addEventListener('change', (e) => { state.sort = e.target.value; renderProducts(); });
  $('#logoutBtn')?.addEventListener('click', logout);
}

async function init() {
  await dataLoaded;

  if ($('#productGrid')) {
    // shop page can arrive with ?category=Serums or ?q=toner from other pages
    const params = new URLSearchParams(location.search);
    if (params.get('category')) state.category = params.get('category');
    if (params.get('q')) state.search = params.get('q');
  }

  if ($('#catGrid')) renderCategories();
  if ($('#filterChips')) renderChips();
  if ($('#productGrid')) renderProducts();
  if ($('#searchInput') && state.search) $('#searchInput').value = state.search;
  if ($('#whyGrid')) renderWhy();
  if ($('#timeline')) renderTimeline();
  if ($('#reviewTrack')) renderReviews();
  if ($('#igGrid')) renderIg();
  if ($('#faqList')) renderFaq();
  renderCart();

  if ($('#baRange')) setupBeforeAfter();
  setupNav();
  setupSearch();
  setupTheme();
  setupBackTop();
  setupDelegation();

  lazyize();

  $('#year').textContent = new Date().getFullYear();
}

document.addEventListener('DOMContentLoaded', init);
