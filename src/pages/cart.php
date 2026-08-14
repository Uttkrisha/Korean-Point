<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$cart = getCart();
$cartItems = [];
$total = 0;

if (!empty($cart)) {
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll() as $p) {
        $qty = (int) $cart[$p['id']];
        $cartItems[] = ['product' => $p, 'qty' => $qty];
        $total += $p['price'] * $qty;
    }
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

  <?php if (count($cartItems) > 0): ?>
    <table class="cart-table">
      <thead>
        <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach ($cartItems as $item): $p = $item['product']; ?>
          <tr>
            <td style="display:flex;align-items:center;gap:10px;">
              <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" onerror="this.src='https://via.placeholder.com/48/a8c3ab/ffffff?text=+'">
              <?php echo htmlspecialchars($p['name']); ?>
            </td>
            <td><?php echo formatPrice($p['price']); ?></td>
            <td>
              <form method="post" action="../actions/cart.php" class="qty-form">
                <input type="hidden" name="action" value="setQty">
                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                <input type="hidden" name="redirect" value="cart.php">
                <input type="number" name="qty" value="<?php echo $item['qty']; ?>" min="1">
                <button type="submit" class="btn btn-sm">Update</button>
              </form>
            </td>
            <td><?php echo formatPrice($p['price'] * $item['qty']); ?></td>
            <td>
              <form method="post" action="../actions/cart.php">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                <input type="hidden" name="redirect" value="cart.php">
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
      <input type="hidden" name="redirect" value="cart.php">
      <div class="field"><label for="name">Full name</label><input id="name" name="name" required></div>
      <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" required></div>
      <div class="field"><label for="address">Address</label><input id="address" name="address" required></div>
      <div class="field"><label for="city">City</label><input id="city" name="city" required></div>
      <div class="field"><label for="phone">Phone number</label><input id="phone" name="phone" type="tel" required></div>
      <button type="submit" class="btn btn-block">Place Order</button>
    </form>
  <?php else: ?>
    <p class="empty">Your cart is empty. <a href="shop.php">Continue shopping</a>.</p>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
