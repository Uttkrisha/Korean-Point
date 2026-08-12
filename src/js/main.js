'use strict';
/* Global click delegation + app bootstrap.
   The same script list runs on every page, so each render/setup call
   is guarded by whether that page actually has the matching element.
   PRODUCTS/CATEGORIES/CART_ITEMS are embedded by PHP before this runs —
   nothing here waits on a fetch(). */

function setupDelegation() {
  document.addEventListener('click', (e) => {
    const quick = e.target.closest('[data-quick]');
    if (quick) openQuickView(quick.dataset.quick);
  });

  $('#cartToggle').addEventListener('click', () => openDrawer('#cartDrawer'));
  $('#cartClose').addEventListener('click', () => closeDrawer('#cartDrawer'));
  $('#cartToggleClose').addEventListener('click', () => closeDrawer('#cartDrawer'));
  $('#scrim').addEventListener('click', closeAllOverlays);

  $('#qvClose').addEventListener('click', () => closeModal('#quickModal'));
  $('#checkoutBtn').addEventListener('click', openCheckout);
  $('#coClose').addEventListener('click', () => closeModal('#checkoutModal'));

  $$('.modal').forEach((m) => m.addEventListener('click', (e) => { if (e.target === m) closeModal('#' + m.id); }));

  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAllOverlays(); });

  $('#sortSelect')?.addEventListener('change', (e) => { state.sort = e.target.value; renderProducts(); });
  $('#logoutBtn')?.addEventListener('click', logout);
}

function init() {
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

  // Checkout failed server-side and redirected back here with an error —
  // reopen the checkout modal and show it.
  if (window.__checkoutError) {
    $('#coError').textContent = window.__checkoutError;
    $('#coError').hidden = false;
    closeDrawer('#cartDrawer');
    openModal('#checkoutModal');
  }

  lazyize();

  $('#year').textContent = new Date().getFullYear();
}

document.addEventListener('DOMContentLoaded', init);
