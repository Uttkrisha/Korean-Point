<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = getProduct($pdo, $id);

if (!$product) {
    header('Location: shop.php');
    exit;
}

$pageTitle = htmlspecialchars($product['name']) . ' — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
  <div class="product-detail">
    <div>
      <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://via.placeholder.com/500x500/a8c3ab/ffffff?text=Skincare'">
    </div>
    <div>
      <h1><?php echo htmlspecialchars($product['name']); ?></h1>
      <p><?php echo htmlspecialchars($product['brand']); ?> &middot; <?php echo htmlspecialchars($product['category']); ?></p>
      <div class="price"><?php echo formatPrice($product['price']); ?></div>

      <?php if (!empty($product['description'])): ?>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
      <?php endif; ?>

      <form method="post" action="../actions/cart.php">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <input type="hidden" name="redirect" value="product_details.php?id=<?php echo $product['id']; ?>">
        <div class="field" style="max-width:120px;">
          <label for="qty">Quantity</label>
          <input type="number" id="qty" name="qty" value="1" min="1">
        </div>
        <button type="submit" class="btn">Add to Cart</button>
      </form>

      <p style="margin-top:1.5rem;"><a href="shop.php">&larr; Back to Products</a></p>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
