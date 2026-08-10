'use strict';
/* Quick View modal, modal/drawer open-close helpers, checkout flow */

function openQuickView(id) {
  const p = byId(id);
  if (!p) return;
  const off = p.was > p.price ? Math.round((1 - p.price / p.was) * 100) : 0;
  $('#qvBody').innerHTML = `
    <div class="qv__media"><img src="${p.img}" alt="${p.name}" /></div>
    <div class="qv__info">
      <span class="qv__brand">${p.brand}</span>
      <h2 class="qv__name" id="qvName">${p.name}</h2>
      <div class="card__rating"><span class="stars">${starString(p.rating)}</span><span>${p.rating} (${p.reviews} reviews)</span></div>
      <div class="qv__price"><span class="now">${fmt(p.price)}</span>${off ? `<span class="was">${fmt(p.was)}</span><span class="off">-${off}%</span>` : ''}</div>
      <p class="qv__desc">${p.desc}</p>
      <div class="qv__row">
        <div class="qty">
          <button id="qvDec" aria-label="Decrease quantity">−</button>
          <span id="qvQty">1</span>
          <button id="qvInc" aria-label="Increase quantity">+</button>
        </div>
        <button class="btn btn--primary" id="qvAdd">Add to Cart</button>
      </div>
    </div>`;
  let qty = 1;
  $('#qvDec').addEventListener('click', () => { qty = Math.max(1, qty - 1); $('#qvQty').textContent = qty; });
  $('#qvInc').addEventListener('click', () => { qty += 1; $('#qvQty').textContent = qty; });
  $('#qvAdd').addEventListener('click', () => { for (let i = 0; i < qty; i++) addToCart(id, i > 0); closeModal('#quickModal'); });
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
  const { subtotal } = cartTotals();
  if (!subtotal) return;
  $('#coTotal').textContent = fmt(subtotal);
  closeDrawer('#cartDrawer');
  openModal('#checkoutModal');
}
async function handleCheckoutSubmit(e) {
  e.preventDefault();
  const required = ['coName', 'coEmail', 'coAddr', 'coCity', 'coZip', 'coCard'];
  const missing = required.some((id) => !$('#' + id).value.trim());
  const err = $('#coError');
  if (missing) { err.textContent = 'Please fill in every field.'; err.hidden = false; return; }

  const { items, subtotal } = cartTotals();
  const order = {
    userId: currentUser.id,
    items: items.map((c) => ({ id: c.id, name: c.product.name, price: c.product.price, qty: c.qty })),
    total: subtotal,
    name: $('#coName').value.trim(),
    email: $('#coEmail').value.trim(),
    address: $('#coAddr').value.trim(),
    city: $('#coCity').value.trim(),
    zip: $('#coZip').value.trim(),
  };

  const res = await fetch('/api/orders', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(order),
  });
  const data = await res.json();
  if (!res.ok) { err.textContent = data.error || 'Something went wrong. Please try again.'; err.hidden = false; return; }

  err.hidden = true;
  closeModal('#checkoutModal');
  $('#orderId').textContent = '#' + data.id;
  openModal('#successModal');
  state.cart = [];
  LS.set('kp_cart', state.cart);
  renderCart();
  $('#coForm').reset();
}
