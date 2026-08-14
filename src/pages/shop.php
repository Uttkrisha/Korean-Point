<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$category = trim($_GET['category'] ?? '');
$skinType = trim($_GET['skin_type'] ?? '');
$search = trim($_GET['search'] ?? '');

$sql = 'SELECT * FROM products WHERE 1=1';
$params = [];

if ($category !== '') {
    $sql .= ' AND category = ?';
    $params[] = $category;
}
if ($skinType !== '') {
    $sql .= ' AND (skin_type = ? OR skin_type = "All")';
    $params[] = $skinType;
}
if ($search !== '') {
    $sql .= ' AND (name LIKE ? OR brand LIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}
$sql .= ' ORDER BY created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query('SELECT DISTINCT category FROM products ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
$skinTypes = $pdo->query('SELECT DISTINCT skin_type FROM products WHERE skin_type != "All" ORDER BY skin_type')->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = 'Shop — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<section class="section">
  <div class="container">
    <h2>Our Products</h2>

    <form method="get" class="filter-form">
      <select name="category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?php echo htmlspecialchars($c); ?>" <?php echo $category === $c ? 'selected' : ''; ?>><?php echo htmlspecialchars($c); ?></option>
        <?php endforeach; ?>
      </select>
      <select name="skin_type">
        <option value="">All Skin Types</option>
        <?php foreach ($skinTypes as $t): ?>
          <option value="<?php echo htmlspecialchars($t); ?>" <?php echo $skinType === $t ? 'selected' : ''; ?>><?php echo htmlspecialchars($t); ?></option>
        <?php endforeach; ?>
      </select>
      <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
      <button type="submit" class="btn btn-sm">Filter</button>
      <?php if ($category !== '' || $skinType !== '' || $search !== ''): ?>
        <a href="shop.php">Clear filters</a>
      <?php endif; ?>
    </form>

    <div class="product-grid">
      <?php if (count($products) > 0): ?>
        <?php foreach ($products as $p): ?>
          <div class="card">
            <div class="card__media">
              <img src="<?php echo htmlspecialchars($p['image_url']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" onerror="this.src='https://via.placeholder.com/300x300/a8c3ab/ffffff?text=Skincare'">
            </div>
            <div class="card__body">
              <span class="card__brand"><?php echo htmlspecialchars($p['brand']); ?></span>
              <h3 class="card__name"><?php echo htmlspecialchars($p['name']); ?></h3>
              <div class="card__price"><?php echo formatPrice($p['price']); ?></div>
            </div>
            <div class="card__foot">
              <a href="product_details.php?id=<?php echo $p['id']; ?>" class="btn btn-outline btn-sm">View Details</a>
              <?php if ($p['stock'] > 0): ?>
                <form method="post" action="../actions/cart.php">
                  <input type="hidden" name="action" value="add">
                  <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                  <input type="hidden" name="redirect" value="../pages/shop.php<?php echo $_SERVER['QUERY_STRING'] ? '?' . htmlspecialchars($_SERVER['QUERY_STRING']) : ''; ?>">
                  <button type="submit" class="btn btn-sm">Add to Cart</button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="empty">No products found. Try adjusting your filters.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
