<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$search = trim($_GET['search'] ?? '');
$sql = 'SELECT * FROM products WHERE 1=1';
$params = [];
if ($search !== '') {
    $sql .= ' AND name LIKE ?';
    $params[] = '%' . $search . '%';
}
$sql .= ' ORDER BY id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$deleteError = $_GET['delete_error'] ?? '';
$pageTitle = 'Manage Products — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<div class="container section">
  <?php $activePage = 'products'; include __DIR__ . '/_nav.php'; ?>

  <div class="section__head--row">
    <h2>Products</h2>
    <a href="add_product.php" class="btn btn-sm">+ Add Product</a>
  </div>

  <?php if ($deleteError): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($deleteError); ?></div>
  <?php endif; ?>

  <form method="get" class="filter-form">
    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit" class="btn btn-sm">Search</button>
    <?php if ($search !== ''): ?><a href="products.php">Clear</a><?php endif; ?>
  </form>

  <table class="table">
    <thead>
      <tr><th>Name</th><th>Category</th><th>Brand</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($products as $p): ?>
        <tr>
          <td><?php echo htmlspecialchars($p['name']); ?></td>
          <td><?php echo htmlspecialchars($p['category']); ?></td>
          <td><?php echo htmlspecialchars($p['brand']); ?></td>
          <td><?php echo formatPrice($p['price']); ?></td>
          <td><?php echo (int) $p['stock']; ?></td>
          <td>
            <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-outline btn-sm">Edit</a>
            <form method="post" action="delete_product.php" style="display:inline;" onsubmit="return confirm('Delete this product?');">
              <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
              <button type="submit" class="btn btn-outline btn-sm">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (count($products) === 0): ?>
        <tr><td colspan="6" class="empty">No products found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
