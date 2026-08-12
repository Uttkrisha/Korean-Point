'use strict';
/* Quick View modal, modal/drawer open-close helpers, checkout modal.
   Add-to-cart and checkout are real HTML forms that POST to
   actions/cart.php and actions/checkout.php and reload the page —
   no fetch() anywhere. */

function openQuickView(id) {
  const p = byId(id);
  if (!p) return;
  const redirect = location.pathname + location.search;
  $('#qvBody').innerHTML = `
    <div class="qv__media"><img src="${p.img}" alt="${p.name}" /></div>
    <div class="qv__info">
      <span class="qv__brand">${p.brand}</span>
      <h2 class="qv__name" id="qvName">${p.name}</h2>
      <div class="qv__price"><span class="now">${fmt(p.price)}</span></div>
      <p class="qv__desc">${p.desc}</p>
      <form class="qv__row" method="post" action="../actions/cart.php">
        <input type="hidden" name="action" value="add" />
        <input type="hidden" name="id" value="${p.id}" />
        <input type="hidden" name="redirect" value="${redirect}" />
        <div class="qty">
          <button type="button" id="qvDec" aria-label="Decrease quantity">−</button>
          <span id="qvQty">1</span>
          <button type="button" id="qvInc" aria-label="Increase quantity">+</button>
        </div>
        <input type="hidden" name="qty" id="qvQtyInput" value="1" />
        <button class="btn btn--primary" type="submit">Add to Cart</button>
      </form>
    </div>`;
  let qty = 1;
  $('#qvDec').addEventListener('click', () => { qty = Math.max(1, qty - 1); $('#qvQty').textContent = qty; $('#qvQtyInput').value = qty; });
  $('#qvInc').addEventListener('click', () => { qty += 1; $('#qvQty').textContent = qty; $('#qvQtyInput').value = qty; });
  openModal('#quickModal');
}

function openModal(sel) {
  const modal = $(sel);
  modal.hidden = false;
  document.body.style.overflow = 'hidden';
}
function closeModal(sel) {
  $(sel).hidden = true;
  if (!$$('.modal').some((m) => !m.hidden) && !$('#cartDrawer').classList.contains('is-open')) {
    document.body.style.overflow = '';
  }
}

function openDrawer(drawerSel) {
  $(drawerSel).hidden = false;
  requestAnimationFrame(() => $(drawerSel).classList.add('is-open'));
  $('#scrim').hidden = false;
  document.body.style.overflow = 'hidden';
}
function closeDrawer(drawerSel) {
  $(drawerSel).classList.remove('is-open');
  $('#scrim').hidden = true;
  document.body.style.overflow = '';
  setTimeout(() => { if (!$(drawerSel).classList.contains('is-open')) $(drawerSel).hidden = true; }, 500);
}
function closeAllOverlays() {
  if ($('#cartDrawer').classList.contains('is-open')) closeDrawer('#cartDrawer');
  $$('.modal').forEach((m) => { if (!m.hidden) m.hidden = true; });
  document.body.style.overflow = '';
}

function openCheckout() {
  if (!CART_SUBTOTAL) return;
  $('#coTotal').textContent = fmt(CART_SUBTOTAL);
  closeDrawer('#cartDrawer');
  openModal('#checkoutModal');
}
