'use strict';
/* Shopping cart — state mutation + rendering + localStorage sync */

function cartTotals() {
  const items = state.cart.map((c) => ({ ...c, product: byId(c.id) })).filter((c) => c.product);
  const subtotal = items.reduce((sum, c) => sum + c.product.price * c.qty, 0);
  const count = items.reduce((sum, c) => sum + c.qty, 0);
  return { items, subtotal, count };
}

function addToCart(id, silent = false) {
  const line = state.cart.find((c) => c.id === id);
  if (line) line.qty += 1; else state.cart.push({ id, qty: 1 });
  LS.set('kp_cart', state.cart);
  renderCart();
  if (!silent) toast(`${byId(id)?.name || 'Item'} added to cart`, '🛍️');
}
function removeFromCart(id) {
  state.cart = state.cart.filter((c) => c.id !== id);
  LS.set('kp_cart', state.cart);
  renderCart();
  toast('Item removed', '🗑️');
}
function setQty(id, qty) {
  const line = state.cart.find((c) => c.id === id);
  if (!line) return;
  line.qty = Math.max(1, qty);
  LS.set('kp_cart', state.cart);
  renderCart();
}

function renderCart() {
  const { items, subtotal, count } = cartTotals();
  $('#cartCount').textContent = count;
  const box = $('#cartItems');
  box.innerHTML = items.length ? items.map((c) => `
    <div class="cart-item" data-id="${c.id}">
      <img src="${c.product.img}" alt="${c.product.name}" />
      <div class="cart-item__info">
        <p class="cart-item__name">${c.product.name}</p>
        <p class="cart-item__price">${fmt(c.product.price)}</p>
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
