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
      <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://via.placeholder.com/500x500/a8c3ab/ffffff?text=Skincare'">
    </div>
    <div>
      <h1><?php echo htmlspecialchars($product['name']); ?></h1>
      <p><?php echo htmlspecialchars($product['brand']); ?> &middot; <?php echo htmlspecialchars($product['category']); ?></p>
      <div class="price"><?php echo formatPrice($product['price']); ?></div>

      <div class="meta">
        <p><strong>Skin Type:</strong> <?php echo htmlspecialchars($product['skin_type']); ?></p>
        <p><strong>Stock:</strong>
          <?php if ($product['stock'] > 0): ?>
            <span class="stock-in"><?php echo (int) $product['stock']; ?> units available</span>
          <?php else: ?>
            <span class="stock-out">Out of Stock</span>
          <?php endif; ?>
        </p>
      </div>

      <?php if (!empty($product['description'])): ?>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
      <?php endif; ?>

      <?php if ($product['stock'] > 0): ?>
        <form method="post" action="../actions/cart.php">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
          <input type="hidden" name="redirect" value="../pages/product_details.php?id=<?php echo $product['id']; ?>">
          <div class="field" style="max-width:120px;">
            <label for="qty">Quantity</label>
            <input type="number" id="qty" name="qty" value="1" min="1" max="<?php echo (int) $product['stock']; ?>">
          </div>
          <button type="submit" class="btn">Add to Cart</button>
        </form>
      <?php else: ?>
        <p class="stock-out">Out of Stock</p>
      <?php endif; ?>

      <p style="margin-top:1.5rem;"><a href="shop.php">&larr; Back to Products</a></p>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
