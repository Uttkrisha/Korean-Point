'use strict';
/* Shopping cart — backed by the PHP session (see api/cart.php).
   The server always returns fresh product name/price/image looked up
   from MySQL, so the browser never has to be trusted with prices. */

let cartData = { items: [], subtotal: 0, count: 0 };

function cartTotals() {
  return cartData;
}

async function postCart(body) {
  const res = await fetch('../api/cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body),
  });
  cartData = await res.json();
  paintCart();
  return cartData;
}

async function refreshCart() {
  const res = await fetch('../api/cart.php');
  cartData = await res.json();
  paintCart();
}

async function addToCart(id, silent = false) {
  await postCart({ action: 'add', id });
  if (!silent) toast(`${byId(id)?.name || 'Item'} added to cart`, '🛍️');
}
async function removeFromCart(id) {
  await postCart({ action: 'remove', id });
  toast('Item removed', '🗑️');
}
async function setQty(id, qty) {
  await postCart({ action: 'setQty', id, qty: Math.max(1, qty) });
}

function paintCart() {
  const { items, subtotal, count } = cartData;
  $('#cartCount').textContent = count;
  const box = $('#cartItems');
  box.innerHTML = items.length ? items.map((c) => `
    <div class="cart-item" data-id="${c.id}">
      <img src="${c.img}" alt="${c.name}" />
      <div class="cart-item__info">
        <p class="cart-item__name">${c.name}</p>
        <p class="cart-item__price">${fmt(c.price)}</p>
        <div class="cart-item__actions">
          <div class="qty">
            <button data-dec="${c.id}" aria-label="Decrease quantity">−</button>
            <span>${c.qty}</span>
            <button data-inc="${c.id}" aria-label="Increase quantity">+</button>
          </div>
          <button class="cart-item__remove" data-remove="${c.id}">Remove</button>
        </div>
      </div>
    </div>`).join('') : '<p class="drawer__empty">Your cart is empty.</p>';
  $('#cartSubtotal').textContent = fmt(subtotal);
  $('#cartTotal').textContent = fmt(subtotal);
  $('#checkoutBtn').disabled = items.length === 0;
}

async function renderCart() {
  await refreshCart();
}
