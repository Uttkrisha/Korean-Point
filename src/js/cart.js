'use strict';
function renderCart() {
  const redirect = location.pathname + location.search;
  $('#cartCount').textContent = CART_COUNT;
  const box = $('#cartItems');
  box.innerHTML = CART_ITEMS.length ? CART_ITEMS.map((c) => `
    <div class="cart-item" data-id="${c.id}">
      <img src="${c.img}" alt="${c.name}" />
      <div class="cart-item__info">
        <p class="cart-item__name">${c.name}</p>
        <p class="cart-item__price">${fmt(c.price)}</p>
        <div class="cart-item__actions">
          <div class="qty">
            <form method="post" action="../actions/cart.php">
              <input type="hidden" name="action" value="setQty" />
              <input type="hidden" name="id" value="${c.id}" />
              <input type="hidden" name="qty" value="${c.qty - 1}" />
              <input type="hidden" name="redirect" value="${redirect}" />
              <button type="submit" aria-label="Decrease quantity" ${c.qty <= 1 ? 'disabled' : ''}>−</button>
            </form>
            <span>${c.qty}</span>
            <form method="post" action="../actions/cart.php">
              <input type="hidden" name="action" value="setQty" />
              <input type="hidden" name="id" value="${c.id}" />
              <input type="hidden" name="qty" value="${c.qty + 1}" />
              <input type="hidden" name="redirect" value="${redirect}" />
              <button type="submit" aria-label="Increase quantity">+</button>
            </form>
          </div>
          <form method="post" action="../actions/cart.php">
            <input type="hidden" name="action" value="remove" />
            <input type="hidden" name="id" value="${c.id}" />
            <input type="hidden" name="redirect" value="${redirect}" />
            <button class="cart-item__remove" type="submit">Remove</button>
          </form>
        </div>
      </div>
    </div>`).join('') : '<p class="drawer__empty">Your cart is empty.</p>';
  $('#cartSubtotal').textContent = fmt(CART_SUBTOTAL);
  $('#cartTotal').textContent = fmt(CART_SUBTOTAL);
  $('#checkoutBtn').disabled = CART_ITEMS.length === 0;
}
