<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$items = getCartItems($pdo, $_SESSION['user_id']);
$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}

$checkoutError = $_GET['checkout_error'] ?? '';
$pageTitle = 'Your Cart — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<div class="container section">
  <h2>Shopping Cart</h2>

  <?php if ($checkoutError): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($checkoutError); ?></div>
  <?php endif; ?>

  <?php if (count($items) > 0): ?>
    <table class="cart-table">
      <thead>
        <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td style="display:flex;align-items:center;gap:10px;">
              <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" onerror="this.src='https://via.placeholder.com/48/a8c3ab/ffffff?text=+'">
              <?php echo htmlspecialchars($item['name']); ?>
            </td>
            <td><?php echo formatPrice($item['price']); ?></td>
            <td>
              <form method="post" action="../actions/cart.php" class="qty-form">
                <input type="hidden" name="action" value="setQty">
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                <input type="hidden" name="redirect" value="../pages/cart.php">
                <input type="number" name="qty" value="<?php echo $item['quantity']; ?>" min="1">
                <button type="submit" class="btn btn-sm">Update</button>
              </form>
            </td>
            <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
            <td>
              <form method="post" action="../actions/cart.php">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                <input type="hidden" name="redirect" value="../pages/cart.php">
                <button type="submit" class="btn btn-outline btn-sm">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <p class="cart-total">Total: <?php echo formatPrice($total); ?></p>

    <h3>Checkout</h3>
    <form method="post" action="../actions/checkout.php">
      <input type="hidden" name="redirect" value="../pages/cart.php">
      <div class="field">
        <label for="shipping_address">Shipping address</label>
        <textarea id="shipping_address" name="shipping_address" rows="3" required></textarea>
      </div>
      <div class="field">
        <label for="payment_method">Payment method</label>
        <select id="payment_method" name="payment_method" required>
          <option value="Cash on Delivery">Cash on Delivery</option>
          <option value="Card">Card</option>
        </select>
      </div>
      <button type="submit" class="btn btn-block">Place Order</button>
    </form>
  <?php else: ?>
    <p class="empty">Your cart is empty. <a href="shop.php">Continue shopping</a>.</p>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
